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
        padding: 50px;
        font-family: Arial, sans-serif;
        color: white;
    }

    header {
        color: white;
        font-size: 24px;
        margin-bottom: 20px;
    }

    button {
        background-color: rgb(255, 128, 64);
        color: white;
        border: none;
        padding: 10px 20px;
        margin: 10px;
        cursor: pointer;
        border-radius: 5px;
    }

    button:hover {
        background-color: rgb(230, 100, 40);
    }

    #chat {
        max-width: 600px;
        margin: 20px auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        color: black;
        text-align: left;
        height: 300px;
        overflow-y: auto;
    }

    .msg {
        margin: 10px 0;
    }

    .bot {
        color: rgb(0, 0, 255);
    }

    .user {
        color: rgb(128, 0, 255);
        text-align: right;
    }

    #input-box {
        margin-top: 10px;
    }

    #input {
        width: 70%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid rgb(192, 192, 192);
    }
</style>
</head>
<body>

<header>AI Assistant</header>

<div>
    <form action="mainMenu.php">
        <button type="submit">Back to Menu</button>
    </form>
</div>

<div id="chat"></div>

<div id="input-box">
    <input type="text" id="input" placeholder="Type here...">
    <button onclick="send()">Send</button>
</div>

<script>
    let chat = document.getElementById("chat");

    function send() {
        let input = document.getElementById("input");
        let msg = input.value;
        if (msg.trim() === "") return;

        chat.innerHTML += `<div class="msg user">${msg}</div>`;
        input.value = "";
        chat.scrollTop = chat.scrollHeight;

        
        if (msg.toLowerCase().includes("next")) {
            chat.innerHTML += `<div class="msg bot">🚀 Your next task is Photo Op at 10:30 AM.</div>`;
        } else if (msg.toLowerCase().includes("status")) {
            chat.innerHTML += `<div class="msg bot">🛰 All systems are operational. Oxygen levels nominal.</div>`;
        } else {
            chat.innerHTML += `<div class="msg bot">🤖 Got it. Want me to remind you about something later?</div>`;
        }

        chat.scrollTop = chat.scrollHeight;
    }
</script>

</body>
</html>
