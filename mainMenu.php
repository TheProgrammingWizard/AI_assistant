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

	body{
		background-color: rgb(11, 61, 145);
		text-align: center;
      	padding: 50px;
	}
	
	header{
		color: white;
		font-size: 20px;
	}
	
	.menu {
		display: flex;
		justify-content: center;
		gap: 20px;        
	}
	
	
	button {
      	background-color:rgb(255, 128, 64);
      	color: white;
      	border: none;
      	padding: 10px;
      	margin: 50px auto;
    }

</style>
</head>
<body>

<header>NASA Hunch Project Main Menu</header>

<div class = menu>
	<form action="logout.php">
	  <button type="submit">Log Out</button>
	</form>
	
	<form action="notesPage.php">
	  <button type="submit">Notes</button>
	</form>
	
	<form action="aiAssistant.php">
	  <button type="submit">AI Assistant</button>
	</form>
</div>

</body>
</html>
