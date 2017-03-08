<!DOCTYPE html>
<html>
<head>
    <link href="assets/css/login.css" rel="stylesheet">
    <meta charset="utf-8">
    <title>Login page</title>
</head>
<body>
<header>
<span id="title"> Login in LibertyFile</span>
</header>
<div id="blockError">
<?php if (!empty($error)): ?>
    <p>Error : <?php echo $error ?></p>
<?php endif; ?>
</div>
<div id="formLogin">

    <form action="?action=login" method="POST">
        <label for="username">Login :</label><br><br>
        <input type="text" name="username" id="username"><br>
        <label for="password"> Password :</label><br><br>
        <input type="password" name="password" id="password"><br>

        <input type="submit" value="Sign in">
        <span> Don't  have an account ? <a href="?action=register"> Subscribe</a></span>
    </form>
</div>
</body>
</html>
