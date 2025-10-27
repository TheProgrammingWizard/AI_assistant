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
<title>AI Project Operation: ECLIPSE</title>
<style>

	body{
		background-color: rgb(11, 61, 145);
		text-align: center;
      	padding: 50px;
	}
	
	.login-box{
		background-color: rgb(0, 128, 255);
		padding: 20px;
		border-radius: 10px;
		width: 300px;
		margin: 50px auto;
	}
	
	input {
      	width: 90%;
      	padding: 10px;
      	margin: 10px 0;
    }

	button {
      	background-color:rgb(227, 62, 62);
      	color: white;
      	border: none;
      	padding: 20px;
      	margin: 20px auto;
     }

</style>
</head>
<body>

<div class="login-box">
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