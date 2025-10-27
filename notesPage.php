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
<title>Notes - ECLIPSE</title>
<style>
    body {
        background-color: rgb(11, 61, 145);
        text-align: center;
        font-family: Arial, sans-serif;
        color: white;
        margin: 0;
        padding: 30px;
    }

    header {
        font-size: 24px;
        margin-bottom: 20px;
    }

    textarea {
        width: 80%;
        height: 300px;
        border-radius: 10px;
        border: none;
        padding: 15px;
        font-size: 16px;
    }

    button {
        background-color: rgb(255, 128, 64);
        color: white;
        border: none;
        padding: 10px 20px;
        margin-top: 15px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: rgb(230, 100, 40);
    }

	#message {
        color: red;
        opacity: 1;
        transition: opacity 1s ease-out;
        margin-top: 15px;
    }

</style>
</head>
<body>

<header>Notes</header>

<form method="post" action="">
    <textarea name="noteContent" placeholder="Type your mission notes here..."></textarea><br>
    <button type="submit">Delete Notes</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["noteContent"])) {
    $note = htmlspecialchars($_POST["noteContent"]);
    file_put_contents("notes.txt", $note);
    echo "<p id='message'>Notes deleted successfully!</p>";
}
?>

<script>
    window.onload = function() {
        const msg = document.getElementById("message");
        if (msg) {
            setTimeout(() => {
                msg.style.opacity = "0";
                setTimeout(() => msg.remove(), 1000);
            }, 3000);
        }
    };
</script>

</body>
</html>
