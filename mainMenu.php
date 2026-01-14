<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['username'])) {
    header("Location: loginPage.php");
    exit;
}

$reminderFile = 'reminder.txt';
$currentReminder = "";

if (file_exists($reminderFile)) {
    $currentReminder = trim(file_get_contents($reminderFile));
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
    background-color: rgb(0, 128, 255);
    color: white;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.4);
  }

  .menu-button:hover {
    background-color: rgb(77, 184, 255);
    transform: translateY(-3px);
  }

  .logout-btn {
    background-color: rgb(182, 17, 17);
  }

  .logout-btn:hover {
    background-color: rgb(211, 49, 49);
  }

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

  .reminder-display {
    background: rgba(0, 0, 0, 0.35);
    width: 500px;
    margin: 25px auto 10px auto;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.15);
    font-size: 1.05em;
    line-height: 1.5em;
}

.reminder-title {
    font-weight: bold;
    margin-bottom: 10px;
    color: rgb(77, 184, 255);
}

.nasa-logo {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 120px;
    opacity: 0.9;
    pointer-events: none;
    filter: drop-shadow(0 0 10px rgba(255,255,255,0.3));
}

</style>
</head>
<body>

<div class="username-banner">
  Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
</div>

<header>Operation: ECLIPSE</header>

<div class="reminder-display">
    <div class="reminder-title">Daily Reminder</div>
    <?php
        if (!empty($currentReminder)) {
            echo nl2br(htmlspecialchars($currentReminder));
        } else {
            echo "<em>No reminder set.</em>";
        }
    ?>
</div>

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

<img src="NASA_logo.svg.png" alt="NASA HUNCH Logo" class="nasa-logo">

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
