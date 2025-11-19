<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['username'])) {
    header("Location: loginPage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Main Menu - ECLIPSE</title>
<style>
  body {
    background-color: rgb(11, 61, 145);
    color: white;
    font-family: 'Segoe UI', sans-serif;
    text-align: center;
    margin: 0;
    padding: 0;
    overflow: hidden;
  }

  header {
    margin-top: 40px;
    font-size: 2.2em;
    letter-spacing: 1px;
    text-shadow: 0 0 10px rgba(0, 0, 0, 0.6);
  }

  .username-banner {
    position: absolute;
    top: 20px;
    left: 20px;
    background: rgba(0, 0, 0, 0.3);
    padding: 8px 15px;
    border-radius: 8px;
    font-size: 1em;
    color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
  }

  .menu-box {
    background: rgba(0, 0, 0, 0.3);
    width: 380px;
    margin: 60px auto;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 0 25px rgba(255, 255, 255, 0.15);
    transition: opacity 0.5s ease;
  }

  .menu-button {
    width: 85%;
    padding: 16px;
    margin: 15px 0;
    border: none;
    border-radius: 8px;
    font-size: 1.1em;
    letter-spacing: 1px;
    cursor: pointer;
    transition: all 0.3s;
    background-color: #0080ff;
    color: white;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.4);
  }

  .menu-button:hover {
    background-color: #4db8ffff;
    transform: translateY(-3px);
  }

  .logout-btn {
    background-color: rgb(182, 17, 17);
  }

  .logout-btn:hover {
    background-color: rgb(211, 49, 49);
  }

  /* Overlay windows for AI and Notes */
  .overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 10;
  }

  iframe {
    width: 80%;
    height: 80%;
    border: none;
    border-radius: 15px;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.6);
  }

  .closeBtn {
    position: absolute;
    top: 40px;
    right: 60px;
    background-color: rgb(182, 17, 17);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    z-index: 20;
  }

  .closeBtn:hover {
    background-color: rgb(211, 49, 49);
  }
</style>
</head>
<body>

<div class="username-banner">
  Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
</div>

<header>Operation: ECLIPSE</header>

<div class="menu-box" id="menu">
    <button type="button" class="menu-button" onclick="openAI()">AI Assistant</button>
    <button type="button" class="menu-button" onclick="openNotes()">Notes</button>
    <button type="button" class="menu-button" onclick="openReminder()">Daily Reminder</button>

    <form action="logout.php">
        <button type="submit" class="menu-button logout-btn">Log Out</button>
    </form>
</div>

<div class="overlay" id="aiOverlay">
    <button class="closeBtn" onclick="closeAI()">✖ Close</button>
    <iframe src="aiAssistant.php"></iframe>
</div>

<div class="overlay" id="notesOverlay">
    <button class="closeBtn" onclick="closeNotes()">✖ Close</button>
    <iframe src="notesPage.php"></iframe>
</div>

<div class="overlay" id="reminderOverlay">
    <button class="closeBtn" onclick="closeReminder()">✖ Close</button>
    <iframe src="dailyReminder.php"></iframe>
</div>

<script>
function openAI() {
    document.getElementById("aiOverlay").style.display = "flex";
    document.getElementById("menu").style.opacity = "0.3";
}

function closeAI() {
    document.getElementById("aiOverlay").style.display = "none";
    document.getElementById("menu").style.opacity = "1";
}

function openNotes() {
    document.getElementById("notesOverlay").style.display = "flex";
    document.getElementById("menu").style.opacity = "0.3";
}

function closeNotes() {
    document.getElementById("notesOverlay").style.display = "none";
    document.getElementById("menu").style.opacity = "1";
}

function openReminder() {
    document.getElementById("reminderOverlay").style.display = "flex";
    document.getElementById("menu").style.opacity = "0.3";
}

function closeReminder() {
    document.getElementById("reminderOverlay").style.display = "none";
    document.getElementById("menu").style.opacity = "1";
}
</script>

</body>
</html>

