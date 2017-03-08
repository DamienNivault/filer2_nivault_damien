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
        <label for="usernameRegister">Login : </label>
        <input type="text" name="usernameRegister" id="usernameRegister"><br>
        <label for="email">Email :</label>
         <input type="text" name="email" id="email"><br> <span id="errorBlockEmail"></span>
        <label for="passwordRegister">Password : </label>
        <input type="password" name="passwordRegister" id="passwordRegister"><br>
        <label for="passwordConfirm">Confirm Password : </label>
        <input type="password" name="passwordConfirm" id="passwordConfirm"><br>
        <input type="submit" name="register">
            <span> Already have an account ? <a href="?action=login">Sign In</a></span>

    </form>
</div>
</body>
</html>
