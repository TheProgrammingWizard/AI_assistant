<?php
session_start();

$_SESSION = [];
session_destroy();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Logout - ECLIPSE</title>
<style>

	body{
		background-color: rgb(11, 61, 145);
		text-align: center;
      	padding: 50px;
	}
	
	.logout-box{
		background: rgba(0, 0, 0, 0.3);
		padding: 40px 50px;
		border-radius: 15px;
		box-shadow: 0 0 25px rgba(255, 255, 255, 0.15);
		text-align: center;
		color: white;
	}
	
	input {
      	width: 90%;
      	padding: 10px;
      	margin: 10px 0;
    }

	button {
      	background-color: rgb(182, 17, 17);
		color: white;
		border: none;
		padding: 14px 30px;
		border-radius: 8px;
		font-size: 1.1em;
		cursor: pointer;
		box-shadow: 0 3px 12px rgba(0, 0, 0, 0.4);
     }

</style>
</head>
<body>

<div class="logout-box">
	<h2>You have been logged out</h2>
	<form action="loginPage.php" method="post">
	  <button type="submit">Return to Login</button>
	</form>
</div>

<script>
  window.history.forward();
  function noBack() { 
	window.history.forward(); 
}
  window.onload = noBack;
  window.onpageshow = function(evt) { 
	if (evt.persisted) noBack(); 
}
</script>

</body>
</html>
