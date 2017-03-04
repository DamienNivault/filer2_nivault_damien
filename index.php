<?php
session_start();
require('config/config.php');

if (empty($_GET['action']))
    $action = 'home';
else {
    $action = $_GET['action'];
}

if (isset($routes[$action]))
{
    require('controllers/'.$routes[$action].'_controller.php');
    call_user_func($action.'_action');
}
else
    die('Illegal route');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Home page</title>
        <script type="text/javascript" src="assets/script/jquery-3.1.1.min.js"></script>
        <script src="assets/script/script.js"></script>
        <link href="assets/css/style.css" rel="stylesheet">
        <link href="assets/css/home.css" rel="stylesheet">
        <link href="assets/css/login.css" rel="stylesheet">
        <link href="assets/css/register.css" rel="stylesheet">
        <link href="assets/css/profile.css" rel="stylesheet">

    </head>
    <body>
</body>
</html>