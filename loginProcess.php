<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $valid_users = [
        //username => password
        "commander_BlackHole" => "apollo11",
        "engineer_Green" => "apollo14",
        "navigator_Venom" => "apollo23"
    ];

    if (array_key_exists($username, $valid_users) && $valid_users[$username] === $password) {
        $_SESSION['username'] = $username;

        echo '<form id="redirectForm" method="post" action="mainMenu.php">
                <input type="hidden" name="username" value="' . $username . '">
              </form>
              <script>document.getElementById("redirectForm").submit();</script>';
        exit;
    } else {
        echo "<body style='background-color:rgb(11,61,145);color:white;text-align:center;padding:50px;'>";
        echo "<h2>Access Denied</h2>";
        echo "<p>Invalid username or password.</p>";
        echo "<a href='loginPage.php' style='color:orange;'>Try Again</a>";
        echo "</body>";
    }
} else {
    header("Location: loginPage.php");
    exit;
}
?>
