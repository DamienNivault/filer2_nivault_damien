<!DOCTYPE html>
<html>
<head>
    <link href="assets/css/register.css" rel="stylesheet">
    <meta charset="utf-8">
    <title>Register page</title>
</head>
<body>
<header>
    <span id="title"> Register in LibertyFile</span>
</header>
<div id="blockError">
<?php if (!empty($error)): ?>
    <p>Error : <?php echo $error ?></p>
<?php endif; ?>
</div>
<div id="formRegister">
    <form action="?action=register" method="POST" name="register">
        Login : <input type="text" name="usernameRegister"><br>
        Email : <input type="text" name="email"><br> <span id="errorBlockEmail"></span>
        Password : <input type="password" name="passwordRegister"><br>
        Confirm Password : <input type="password" name="passwordConfirm"><br>
        <input type="submit" name="register">
        <span> Vous avez déjà un compte ? <a href="?action=login">Connectez vous</a></span>

    </form>
</div>
</body>
</html>
