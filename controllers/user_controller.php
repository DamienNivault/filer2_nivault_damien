<?php


require_once('model/user.php');

function login_action()
{
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (user_check_login($_POST)) {
            user_login($_POST['username']);
            header('Location: ?action=profile');
            exit(0);
        } else {
            $error = "Invalid username or password";
            $date = give_date();
            $text = $date . '=> ' . 'A user enter wrong log' . "\n";
            write_log('security.log', $text);

        }
    }
    require('views/login.php');
}

function logout_action()
{
    session_destroy();
    header('Location: ?action=login');
    $date = give_date();
    $text = $date . ' => ' . $_SESSION['username'] . ' disconnected' . "\n";
    write_log('access.log', $text);
    exit(0);
}


function register_action()
{
    $error = '';
    $errors = errors();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (!empty($_POST['email']) && get_user_by_email($_POST["email"])) {
            $error = $errors['email'];
            $date = give_date();
            $text = $date . '=> ' . 'User enter an email who already exist  ' . "\n";
            write_log('security.log', $text);
        } else if (!empty($_POST['usernameRegister']) && get_user_by_username($_POST['usernameRegister'])) {
            $error = $errors['username'];
            $date = give_date();
            $text = $date . '=> ' . 'User enter a username who already exist  ' . "\n";
            write_log('security.log', $text);
        } else if (!empty($_POST['email']) && user_check_by_email($_POST['email'])) {
            $error = $errors['invalid'];
            $date = give_date();
            $text = $date . '=> ' . 'User enter an invalid email' . "\n";
            write_log('security.log', $text);
        } else if (user_check_by_password($_POST)) {
            $error = $errors['password'];
            $date = give_date();
            $text = $date . '=> ' . 'The confirm password is different' . "\n";
            write_log('security.log', $text);
        } else if (!user_check_register($_POST)) {
            $error = $errors['empty'];
            $date = give_date();
            $text = $date . '=> ' . 'User forget information' . "\n";
            write_log('security.log', $text);
        } else {
            user_register($_POST);
            header('Location: ?action=profile');
            exit(0);
        }

    }


    require('views/register.php');
}

function profile_action()
{
    $error = '';
    $errors = errors();
    if (!empty($_SESSION['user_id'])) {
        $user = get_user_by_id($_SESSION['user_id']);
        $files = my_files();

        if (upload_file($_POST)) {

            if (file_exist($files['file_url'])) {
                $error = 'Ur file is already in';
                $date = give_date();
                $text = $date . ' ' . $user['username'] . ' try to upload an exist file' . "\n";
                write_log('security.log', $text);
            }
            if (!extension_accept($files['file_name'])) {
                $error = 'Extension accept : .png,.jpeg,.gif,.docx,.txt,.pdf ';
                $date = give_date();
                $text = $date . ' ' . $user['username'] . ' try to upload a wrong file' . "\n";
                write_log('security.log', $text);
            }
        }

        if (delete_file()) {
            header('Location: ?action=profile');
        }
        if (rename_file()) {
            if ($_POST['current_file_name'] === $_POST['new_name']) {
                $error = $errors['rename'];
            }

            header("Refresh:0");
            header('Location: ?action=profile');
        }
        if (replace_file()) {
            header('Location: ?action=profile');
        }


        if (modify()) {
            $error = 'File modify';
            header('Location: ?action=profile');
        }


        $username = $user['username'];

        require('/views/profile.php');
    } else {
        header('Location: ?action=login');
        exit(0);
    }
}

