<?php

require_once('model/user.php');

function home_action()
{
    if (!empty($_SESSION['user_id']))
    {
        $user = get_user_by_id($_SESSION['user_id']);
        //$user = get_user_by_id(1);

        //CAN SHOW INFO IN BDD
        $username = $user['username'];
        $firstname = $user['firstname'];
        $lastname = $user['lastname'];
        $email = $user['email'];

        require('views/home.php');
        //require('views/profil.php');
    }
    else {
        header('Location: ?action=login');
        exit(0);
    }
}
function about_action()
{
    require('views/about.html');
}

function contact_action()
{
    require('views/contact.html');
}
