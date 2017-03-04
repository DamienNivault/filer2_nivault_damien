<!DOCTYPE html>
<html>
<head>
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
    <form action="?action=register" method="POST">
        Login : <input type="text" name="usernameRegister"><br>
        Email : <input type="text" name="email"><br>
        Password : <input type="password" name="passwordRegister"><br>
        Confirm Password : <input type="password" name="passwordConfirm"><br>
        <input type="submit">
        <span> Vous avez déjà un compte ? <a href="?action=login">Connectez vous</a></span>

    </form>
</div>
</body>
</html>
