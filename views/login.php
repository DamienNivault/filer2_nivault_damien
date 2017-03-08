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
        Login : <input type="text" name="username"><br>
        Password : <input type="password" name="password"><br>

        <input type="submit">
        <span> Vous n'avez pas de compte ? <a href="?action=register">Inscrivez vous</a></span>
    </form>
</div>
</body>
</html>
