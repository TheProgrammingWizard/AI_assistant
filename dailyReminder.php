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

// Load existing reminder
if (file_exists($reminderFile)) {
    $currentReminder = trim(file_get_contents($reminderFile));
}

// Save new reminder
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newReminder = $_POST['reminderText'];
    file_put_contents($reminderFile, $newReminder);
    $currentReminder = $newReminder;

    echo "<script>alert('Reminder saved!');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Daily Reminder - ECLIPSE</title>

<style>
    body {
        background-color: rgb(11, 61, 145);
        color: white;
        text-align: center;
        font-family: 'Segoe UI', sans-serif;
        padding: 40px;
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
        margin-top: 15px;
        background-color: rgb(226, 62, 13);
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }
</style>
</head>

<body>

<h2>Daily Reminder</h2>

<form method="post">
    <textarea name="reminderText" placeholder="Write your daily reminder..."><?php echo htmlspecialchars($currentReminder); ?></textarea>
    <br>
    <button type="submit">Save Reminder</button>
</form>

</body>
</html>
