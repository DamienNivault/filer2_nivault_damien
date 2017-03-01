<?php


require_once('model/user.php');

function login_action()
{
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (user_check_login($_POST))
        {
            user_login($_POST['username']);
            header('Location: ?action=home');
            exit(0);
        }
        else {
            $error = "Invalid username or password";
        }
    }
    require('views/login.php');
}

function logout_action()
{
    session_destroy();
    header('Location: ?action=login');
    exit(0);
}


function register_action()
{
   $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (user_check_register($_POST))
        {
            user_register($_POST);
            header('Location: ?action=home');
            exit(0);
        }
        else {
            $error = "Invalid data";
        }
    }
    require('views/register.php');
}
function home_action()
{
    if (!empty($_SESSION['user_id']))
    {
        $user = get_user_by_id($_SESSION['user_id']);

        $add_file_succes = '';
        $delete_file_succes = '';


            if(upload_file()){
                $add_file_succes = 'Fichier ajouté avec succés';
            }
        if(delete_file()){
            header('Location: ?action=home');
        }
        if(rename_file()){
            header('Location: ?action=home');
        }
                 $files = my_files();




        //$user = get_user_by_id(1);
        $username = $user['username'];
        require('/views/home.php');
    }
    else {
        header('Location: ?action=login');
        exit(0);
    }
}

