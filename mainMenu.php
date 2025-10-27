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
<title>AI Project Operation: ECLIPSE</title>
<style>
    body {
        background-color: rgb(11, 61, 145);
        text-align: center;
        font-family: Arial, sans-serif;
        color: white;
        margin: 0;
        overflow: hidden;
    }

    header {
        margin-top: 40px;
        font-size: 26px;
        font-weight: bold;
    }

    .menu {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 40px;
        margin-top: 80px;
        transition: opacity 0.5s ease;
    }

    button {
        background-color: rgb(255, 128, 64);
        color: white;
        border: none;
        padding: 20px 40px;
        font-size: 18px;
        border-radius: 10px;
        cursor: pointer;
        transition: transform 0.3s, background-color 0.3s;
    }

    button:hover {
        background-color: rgb(230, 100, 40);
        transform: scale(1.05);
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
        background-color: rgb(227, 62, 62);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
    }

    .closeBtn:hover {
        background-color: rgb(190, 40, 40);
    }
</style>
</head>
<body>

<header>NASA Hunch Project Main Menu</header>

<div class="menu" id="menu">
    <form action="logout.php">
        <button type="submit">Log Out</button>
    </form>

    <button type="button" onclick="openNotes()">Notes</button>
    <button type="button" onclick="openAI()">AI Assistant</button>
</div>

<div class="overlay" id="aiOverlay">
    <button class="closeBtn" onclick="closeAI()">✖ Close</button>
    <iframe src="aiAssistant.php"></iframe>
</div>

<div class="overlay" id="notesOverlay">
    <button class="closeBtn" onclick="closeNotes()">✖ Close</button>
    <iframe src="notesPage.php"></iframe>
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
</script>

</body>
</html>

