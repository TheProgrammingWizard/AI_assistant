<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['username'])) {
    header("Location: loginPage.php");
    exit;
}

$noteFile = 'notes.txt';
$currentNote = "";

// Load the saved notes (if any)
if (file_exists($noteFile)) {
    $currentNote = trim(file_get_contents($noteFile));
}

// Save notes when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newNote = $_POST['noteContent'];
    file_put_contents($noteFile, $newNote);
    $currentNote = $newNote;
    echo "<script>alert('Notes saved!');</script>";
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
        background-color: rgb(226, 62, 13);
        color: white;
        border: none;
        padding: 10px 20px;
        margin-top: 15px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: rgb(226, 62, 13);
    }

</style>
</head>
<body>

<header>Notes</header>

<form method="post">
    <textarea name="noteContent" placeholder="Type your mission notes here..."><?php echo htmlspecialchars($currentNote); ?></textarea><br>
    <button type="submit">Save Notes</button>
</form>

</body>
</html>
