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
<title>AI Assistant - ECLIPSE</title>
<style>
       body {
        background-color: rgb(11, 61, 145);
        text-align: center;
        padding: 40px;
        font-family: Arial, sans-serif;
        color: white;
        margin: 0;
        overflow: hidden;
    }

    header {
        font-size: 24px;
        margin-bottom: 20px;
    }

    #chat {
        max-width: 600px;
        margin: 0 auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        color: black;
        height: 300px;
        overflow-y: auto;
        text-align: left;
    }

    .msg {
        margin: 10px 0;
        word-wrap: break-word;
    }

    .bot {
        color: rgb(0, 0, 255);
    }

    .user {
        color: rgb(128, 0, 255);
        text-align: right;
    }

    #input-box {
        margin-top: 15px;
    }

    #input {
        width: 70%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid rgb(192, 192, 192);
    }

    button {
        background-color: rgb(226, 62, 13);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        margin-left: 10px;
    }

    button:hover {
        background-color: rgb(226, 62, 13);
    }
</style>
</head>
<body>

<header>AI Assistant</header>

<div id="chat">
    <div class="msg bot">Hello, astronaut! How can I assist your mission today?</div>
</div>

<div id="input-box">
    <input type="text" id="input" placeholder="Type your message here..." onkeypress="if(event.key==='Enter') send();">
    <button onclick="send()">Send</button>
</div>

<script>
const chat = document.getElementById("chat");

async function send() {
    const input = document.getElementById("input");
    const msg = input.value.trim();
    if (msg === "") return;

    // Display user message
    chat.innerHTML += `<div class="msg user">${msg}</div>`;
    input.value = "";
    chat.scrollTop = chat.scrollHeight;

    try {
        // Call Flask AI backend
        const response = await fetch("http://localhost:5000/chat", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message: msg }) // <-- send user's text
        });

        if (!response.ok) throw new Error("Network error");

        const data = await response.json();
        chat.innerHTML += `<div class="msg bot">${data.reply}</div>`;

        // Optionally, show recommended actions
        //if (data.recommended_actions && data.recommended_actions.length > 0) {
            //chat.innerHTML += `<div class="msg bot"><strong>Recommended actions:</strong><ul>${data.recommended_actions.map(a => `<li>${a}</li>`).join('')}</ul></div>`;
        //}
        if (data.ml_analysis && data.ml_analysis.recommended_actions && data.ml_analysis.recommended_actions.length > 0) {
            chat.innerHTML += `<div class="msg bot"><strong>Recommended actions:</strong><ul>${data.ml_analysis.recommended_actions.map(a => `<li>${a}</li>`).join('')}</ul></div>`;
        }

    } 
    catch (error) {
        console.error(error);
        chat.innerHTML += `<div class="msg bot">Unable to reach AI backend. Make sure the Python server is running.</div>`;
    }

    chat.scrollTop = chat.scrollHeight;
}
</script>

</body>
</html>
