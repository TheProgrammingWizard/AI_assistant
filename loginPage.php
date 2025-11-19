<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - ECLIPSE</title>

<style>
    body {
        background-color: rgb(11, 61, 145);
        color: white;
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
        padding: 0;
        text-align: center;
        overflow: hidden;
    }

    h1 {
        margin-top: 60px;
        font-size: 2.5em;
        text-shadow: 0 0 12px rgba(0, 0, 0, 0.6);
    }

    h3 {
        margin-top: 10px;
        font-size: 1.3em;
        opacity: 0.9;
    }

    /* Login container styled similar to the menu box */
    .login-box {
        background: rgba(0, 0, 0, 0.25);
        width: 380px;
        margin: 60px auto;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 0 25px rgba(255, 255, 255, 0.15);
    }

    input {
        width: 80%;
        padding: 14px;
        margin: 12px 0;
        border: none;
        border-radius: 8px;
        font-size: 1em;
    }

    button {
        width: 85%;
        padding: 14px;
        margin-top: 20px;
        border: none;
        border-radius: 8px;
        font-size: 1.1em;
        letter-spacing: 1px;
        cursor: pointer;
        background-color: rgb(226, 62, 13);
        color: white;
        transition: 0.3s;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.4);
    }

    button:hover {
        background-color: rgb(226, 62, 13);
        transform: translateY(-3px);
    }
</style>

</head>
<body>

<h1>Project Operation: ECLIPSE</h1>
<h3>Login Box</h3>

<div class="login-box">
    <form method="post" action="loginProcess.php">
        <input type="text" name="username" placeholder="Enter username" required><br>
        <input type="password" name="password" placeholder="Enter password" required><br>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
