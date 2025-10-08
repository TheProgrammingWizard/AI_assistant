<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Project: ECLIPSE</title>
<style>
	body {
		background-color: rgb(11, 61, 145);
		text-align: center;
		padding: 100px;
		color: white;
		font-family: Arial, sans-serif;
	}
	input {
		margin: 10px;
		padding: 10px;
		width: 200px;
		border-radius: 5px;
		border: none;
	}
	button {
		background-color: rgb(255, 128, 64);
		color: white;
		border: none;
		padding: 10px 20px;
		cursor: pointer;
	}
</style>
</head>
<body>

<h1>AI Project Operation: ECLIPSE</h1>
<h3>Login Portal</h3>

<form method="post" action="loginProcess.php">
	<input type="text" name="username" placeholder="Enter username" required><br>
	<input type="password" name="password" placeholder="Enter password" required><br>
	<button type="submit">Login</button>
</form>

</body>
</html>
