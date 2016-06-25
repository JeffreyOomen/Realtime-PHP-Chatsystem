<?php

    require_once("../sanitizer.php");
    require_once("../businesslogic/loginmanager.php");

    if (isset($_SESSION['user_id'])) {
        ob_start();
        header("Location: chat_rooms.php");
        ob_end_flush();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userMail = $_POST['email'];
        $userPass = $_POST['pass'];
        if (Sanitizer::hasValue($userMail) && Sanitizer::hasValue($userPass)) {
            echo "username: " . $userMail . " password" . $userPass; 
            $loginManager = new LoginManager();
            $loginManager->login($userMail, $userPass);
        }
    }

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Login Form</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

    <div class="login">
        <div class="login-triangle"></div>
        <h2 class="login-header">Log in</h2>
        <form action="" method="post" class="login-container">
            <p><input name="email" type="email" placeholder="Email"></p>
            <p><input name= "pass" type="password" placeholder="Password"></p>
            <p><input type="submit" value="Log in"></p>
        </form>
    </div>

    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
</body>
</html>