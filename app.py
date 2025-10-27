from flask import Flask, request, jsonify
from flask_cors import CORS
from Untitled1 import train_and_build, parse_user_input

# Initialize Flask
app = Flask(__name__)
from flask_cors import CORS
CORS(app)

# Train model once on startup
print("🚀 Loading astronaut-support AI prototype...")
analyzer, X_reduced, logreg, rf, scaler, nn = train_and_build(save_artifacts=True)
print("✅ Model training complete. Server ready at http://127.0.0.1:5000")

# Chat endpoint
@app.route("/chat", methods=["POST"])
def chat():
    data = request.get_json()
    user_message = data.get("message", "").strip().lower()
    if not user_message:
        return jsonify({"reply": "Please share how you're feeling."})

    # Convert free-text into features
    report_features = parse_user_input(user_message)
    res = analyzer(report_features)

    return jsonify({
        "reply": res['message'],
        "recommended_actions": res['recommended_actions']
    })

if __name__ == "__main__":
    # Run Flask server
    app.run(host="127.0.0.1", port=5000, debug=True)