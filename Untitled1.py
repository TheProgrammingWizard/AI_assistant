#!/usr/bin/env python
# coding: utf-8

# In[1]:


import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import OrdinalEncoder, StandardScaler
from sklearn.impute import SimpleImputer
from sklearn.linear_model import LogisticRegression
from sklearn.ensemble import RandomForestClassifier
from sklearn.feature_selection import mutual_info_classif
from sklearn.neighbors import NearestNeighbors
from sklearn.metrics import roc_auc_score
import joblib
import warnings
import re
from flask import Flask, request, jsonify
from flask_cors import CORS
import random

# In[2]:

DATA_PATH = "Depression-Dataset.csv"
LOGREG_PATH = "logreg_astronaut_baseline.pkl"
RF_PATH = "rf_astronaut_baseline.pkl"
SCALER_PATH = "scaler_astronaut_baseline.pkl"

TOP_K_FEATURES = 12
NN_NEIGHBORS = 10

warnings.filterwarnings("ignore")



# In[3]:

# ---------------------------
# Load and preprocess dataset
# ---------------------------
def load_and_retheme(path=DATA_PATH):
    df = pd.read_csv(path)
    rename_map = {
        'DEPRI': 'Depressed',
        'ANXI': 'Anxiety',
        'FAMSTR': 'CrewConflict',
        'FINSTR': 'MissionStress',
        'WORKST': 'WorkStress',
        'INFER': 'InferiorityFeelings',
        'SUICIDE': 'SuicidalIdeation',
        'ABUSED': 'PastAbuse',
        'LOST': 'LossExperience',
        'CHEAT': 'InfidelityOrBetrayal',
        'SLEEP': 'SleepQuality',
        'AVGSLP': 'AvgSleepHours',
        'INSOM': 'InsomniaSymptoms',
        'EATING': 'EatingChanges',
        'EATDIS': 'EatingDisorder',
        'DRINK': 'AlcoholUse',
        'SMOKE': 'Smoke',
        'SUBS': 'SubstanceUse',
        'SUPPORT': 'PerceivedSupport',
        'MENTHEAL': 'AccessToCare'
    }
    existing_rename = {k: v for k, v in rename_map.items() if k in df.columns}
    df = df.rename(columns=existing_rename)
    return df


# In[4]:

def preprocess(df):
    binary_map = {'Yes':1, 'No':0, 'yes':1, 'no':0, 'Y':1, 'N':0, 'y':1, 'n':0}
    work = df.copy()
    for col in work.columns:
        if work[col].dtype == object:
            work[col] = work[col].astype(str).str.strip().replace(binary_map)

    if 'Depressed' not in work.columns:
        raise RuntimeError("Target column 'Depressed' not found after renaming.")

    y = work['Depressed'].astype(int)
    X = work.drop(columns=['Depressed'])

    cat_cols = X.select_dtypes(include=['object']).columns.tolist()
    num_cols = X.select_dtypes(include=[np.number]).columns.tolist()

    if cat_cols:
        cat_imputer = SimpleImputer(strategy='most_frequent')
        enc = OrdinalEncoder()
        X[cat_cols] = enc.fit_transform(cat_imputer.fit_transform(X[cat_cols]))
    else:
        enc = None

    if num_cols:
        num_imputer = SimpleImputer(strategy='median')
        X[num_cols] = num_imputer.fit_transform(X[num_cols])

    return X, y, cat_cols, enc




def select_top_features(X, y, k=TOP_K_FEATURES):
    mi = mutual_info_classif(X, y, random_state=42)
    mi_ser = pd.Series(mi, index=X.columns).sort_values(ascending=False)
    top = list(mi_ser.head(k).index)
    return top


def train_models(X_reduced, y):
    X_train, X_test, y_train, y_test = train_test_split(X_reduced, y, test_size=0.2, stratify=y, random_state=42)
    scaler = StandardScaler()
    X_train_scaled = scaler.fit_transform(X_train)
    X_test_scaled = scaler.transform(X_test)

    logreg = LogisticRegression(max_iter=500, random_state=42)
    rf = RandomForestClassifier(n_estimators=300, random_state=42)

    logreg.fit(X_train_scaled, y_train)
    rf.fit(X_train, y_train)

    probs_lr = logreg.predict_proba(X_test_scaled)[:,1]
    probs_rf = rf.predict_proba(X_test)[:,1]
    print("LR ROC AUC:", roc_auc_score(y_test, probs_lr))
    print("RF ROC AUC:", roc_auc_score(y_test, probs_rf))

    return logreg, rf, scaler, X_train, y_train, X_test, y_test

def build_nn_index(X_train):
    nn = NearestNeighbors(n_neighbors=NN_NEIGHBORS)
    nn.fit(X_train)
    return nn


# In[8]:


# ---------------------------
# Action mapping for suggestions
# ---------------------------
ACTION_MAP = {
    'PerceivedSupport': "Reach out to a crew-member or mission support for support.",
    'Anxiety': "Try a 5-minute breathing or grounding exercise.",
    'SleepQuality': "Short nap or structured wind-down; avoid caffeine for 6 hours before sleep.",
    'AvgSleepHours': "Aim for a consistent sleep window; follow a sleep routine.",
    'MissionStress': "Break tasks into smaller steps and schedule a rest block.",
    'WorkStress': "Delegate tasks or request a quick status sync to reduce stress.",
    'CrewConflict': "Pause and use a neutral mediation script or breathing exercise.",
    'InferiorityFeelings': "Write down 3 recent wins and remind yourself of your strengths.",
    'SubstanceUse': "Replace intake with low-effort healthy activities like walking or stretching.",
    'DepressionHistory': "Consider a check-in with the mental-health officer.",
    'PastAbuse': "If triggered, use grounding techniques or contact a mental-health officer.",
    'LossExperience': "Take a moment for self-reflection and seek social support if needed.",
    'InfidelityOrBetrayal': "Pause, reflect, and consider talking to a trusted crew-member.",
    'InsomniaSymptoms': "Follow structured sleep routines; limit screen time before bed.",
    'EatingChanges': "Maintain regular meals and track changes.",
    'EatingDisorder': "Contact a mental-health officer for guidance.",
    'AlcoholUse': "Limit alcohol intake; hydrate and do relaxing activities.",
    'Smoke': "Reduce or avoid smoking; try stress-reduction alternatives.",
    'AccessToCare': "Ensure regular check-ins with mission health support."
}


# In[9]:


# ---------------------------
# NLP parser for free-text input
# ---------------------------
def parse_user_input(text):
    """
    Maps free-text to features.
    """
    feature_map = {
        'anxious': 'Anxiety',
        'worried': 'Anxiety',
        'sleep': 'SleepQuality',
        'tired': 'SleepQuality',
        'stressed': 'MissionStress',
        'overwhelmed': 'MissionStress',
        'lonely': 'PerceivedSupport',
        'unsupported': 'PerceivedSupport',
        'sad': 'DepressionHistory',
        'abused': 'PastAbuse',
        'cheated': 'InfidelityOrBetrayal'
    }
    features = {}
    text = text.lower()
    for word, feature in feature_map.items():
        if word in text:
            features[feature] = 1
    return features


# In[10]:


# ---------------------------
# Build analyzer function
# ---------------------------
def build_analyzer(X_reduced, y, X_train, y_train, logreg, rf, scaler, nn, cat_cols):
    reduced_cols = list(X_reduced.columns)
    conversation_history = []

    def analyze_report(report, top_n_suggestions=3):
        # Track conversation history
        conversation_history.append(report)

        # encode report to full X_reduced feature dict using modes as fallback
        base = X_reduced.mode().iloc[0].to_dict()
        rep_full_dict = base.copy()
        for k, v in report.items():
            if k in rep_full_dict:
                rep_full_dict[k] = 1 if v else 0

        vec = np.array([rep_full_dict[c] for c in reduced_cols], dtype=float).reshape(1, -1)
        vec_df = pd.DataFrame(vec, columns=reduced_cols)

        # Predict probabilities internally (not shown to user)
        prob_log = float(logreg.predict_proba(scaler.transform(vec_df))[0,1])
        prob_rf = float(rf.predict_proba(vec_df)[0,1])
        prob = (prob_log + prob_rf)/2.0

        # Find similar records
        nbrs_idx = nn.kneighbors(vec, return_distance=False)[0]
        similar = X_train.iloc[nbrs_idx].copy()
        similar['Depressed'] = y_train.iloc[nbrs_idx].values

        # Generate recommendations
        recommended_actions = []
        for feature in reduced_cols:
            if rep_full_dict.get(feature,0) > 0:
                action = ACTION_MAP.get(feature)
                if action and action not in recommended_actions:
                    recommended_actions.append(action)
            if len(recommended_actions) >= top_n_suggestions:
                break

        # Construct human-readable message
        casual = "Hey — you've handled rough days before. We can do a small reset."
        soft = "You're safe. Your emotions are valid. Let's regulate together."
        message = f"{casual} {soft}\n\nHere are some small steps you can take today:\n"
        if recommended_actions:
            for i, act in enumerate(recommended_actions, start=1):
                message += f"{i}. {act}\n"
        else:
            message += "Try a general reset: 5-min breathing, brief walk, hydration, or a 20-min comfort activity.\n"

        return {
            'probability': prob,  # internal only
            'recommended_actions': recommended_actions,
            'message': message,
            'history': conversation_history,
            'similar_examples': similar.head(5)
        }

    return analyze_report


# In[11]:


# ---------------------------
# Training and building
# ---------------------------
def train_and_build(path=DATA_PATH, save_artifacts=True):
    df = load_and_retheme(path)
    X, y, cat_cols, enc = preprocess(df)
    top_features = select_top_features(X, y)
    X_reduced = X[top_features]

    logreg, rf, scaler, X_train, y_train, X_test, y_test = train_models(X_reduced, y)
    nn = build_nn_index(X_train)
    analyzer = build_analyzer(X_reduced, y, X_train, y_train, logreg, rf, scaler, nn, cat_cols)

    if save_artifacts:
        joblib.dump(logreg, LOGREG_PATH)
        joblib.dump(rf, RF_PATH)
        joblib.dump(scaler, SCALER_PATH)
        print(f"Saved artifacts: {LOGREG_PATH}, {RF_PATH}, {SCALER_PATH}")

    return analyzer, X_reduced, logreg, rf, scaler, nn


# In[12]:


if __name__ == "__main__":
    print("🚀 Loading astronaut-support AI prototype...")
    analyzer, X_reduced, logreg, rf, scaler, nn = train_and_build(DATA_PATH, save_artifacts=True)
    print("✅ Model training complete. You can now type how you feel today!\n")

    app = Flask(__name__)
    CORS(app)  # Allow frontend requests (e.g., from PHP or JS)

    @app.route('/chat', methods=['POST'])
    def chat():
        data = request.get_json()
        user_message = data.get("message", "").strip().lower()
        if not user_message:
            return jsonify({"reply": "Please share how you're feeling or describe your situation."})

        # Convert free-text into features
        report_features = parse_user_input(user_message)
        res = analyzer(report_features)

        return jsonify({
            "reply": res['message'],
            "recommended_actions": res['recommended_actions']
        })

    # Start Flask server for external access (comment out if running offline only)
    app.run(host="127.0.0.1", port=5000)


# In[ ]:





# In[ ]:




def analyze_message(message):
    """
    Takes a text message and returns an AI-generated response.
    Replace this with your actual model’s analysis logic.
    """
    # Example — plug in your trained model or text analyzer here:
    if "tired" in message.lower():
        return "You might be fatigued. Consider taking a short rest."
    elif "stressed" in message.lower():
        return "Try some breathing exercises — a calm mind helps with performance."
    elif "happy" in message.lower():
        return "That’s great! Keep that positive energy going."
    else:
        return "I understand. Can you tell me more about how you’re feeling?"