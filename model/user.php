<?php

require_once('model/db.php');

function give_me_date()
{
    $date = date("d-m-Y");
    $heure = date("H:i");
    return $date . " " . $heure;
}

function write_log($file, $text)
{
    $file_log = fopen('logs/' . $file, 'a');
    fwrite($file_log, $text);
    fclose($file_log);
}

function get_user_by_id($id)
{
    $id = (int)$id;
    $data = find_one("SELECT * FROM users WHERE id = " . $id);
    return $data;
}

function get_user_by_username($username)
{
    $data = find_one_secure("SELECT * FROM users WHERE username = :username", ['username' => $username]);
    return $data;
}

/**
 * @param $data
 * @return bool
 */
function user_check_register($data)
{


    if (empty($data['usernameRegister']) OR empty($data['passwordRegister']) OR empty($data['email']) OR empty($data['passwordConfirm'])) {
        echo $error['empty'] = 'Please enter all information';
        return false;
    }


    $data = get_user_by_username($data['usernameRegister']);
    if ($data !== false)
        return false;


    // TODO : Check valid email
    return true;
}
function get_user_by_email($email)
{
    $data = find_one_secure("SELECT * FROM users WHERE email = :email",
        ['email' => $email]);
    return $data;
}

function user_hash($password)
{
    $hash = password_hash($password, PASSWORD_BCRYPT, ['salt' => 'saltysaltysaltysalty!!']);
    return $hash;
}

function user_register($data)
{
    $user['username'] = $data['usernameRegister'];
    $user['password'] = user_hash($data['passwordRegister']);
    $user['email'] = $data['email'];
    db_insert('users', $user);
    mkdir('uploads/' . $user['username']);
}

function user_check_login($data)
{
    if (empty($data['username']) OR empty($data['password']))
        return false;
    $user = get_user_by_username($data['username']);
    if ($user === false)
        return false;
    $hash = user_hash($data['password']);
    if ($hash !== $user['password']) {
        return false;
    }
    return true;
}
function file_extension($file_name)
{
    return strrchr($file_name, '.');
}

function user_login($username)
{
    $data = get_user_by_username($username);
    if ($data === false) {
        return false;
    }

    $_SESSION['user_id'] = $data['id'];
    $_SESSION['username'] = $data['username'];
    $date = give_me_date();
    $text = $date . ' ' . $_SESSION['username'] . ' logged in' . "\n";
    write_log('access.log', $text);
    return true;
}

function upload_file($post="")
{
    if (isset($_POST['upload'])) {
        $type = '';
        $valid_form = false;
        $file_exist = false;
        $status_form = '';
        $status_files='';
        $extension_images = array('.jpg', '.jpeg', '.png', 'gif');
        $extension_txt = array('.txt', '.docx');
        $extension_pdf = array('.pdf');
        $username = $_SESSION['username'];
        $name = $_FILES["file"]['name'];
        $url ='uploads/' . $username . '/' .  $_FILES["file"]['name'];;



                if (in_array(file_extension($_FILES["file"]['name']), $extension_images)) {
                    $type = 'image';

                } else if (in_array(file_extension($_FILES["file"]['name']), $extension_txt)) {
                    $type = 'text';
                } else if (in_array(file_extension($_FILES["file"]['name']), $extension_pdf)) {
                    $type = 'pdf';
                }


                if ($post['edit_name']) {
                    $files['file_name'] = $post['edit_name'];

                } else {
                    $files['file_name'] = $name;
                }
                $files['file_url'] = $url;
                $files['type'] = $type;
                $files['id_user'] = $_SESSION['user_id'];
                db_insert('files', $files);
                move_uploaded_file($_FILES["file"]["tmp_name"], $files['file_url']);
                $date = give_me_date();
                $text = $date . ' ' . $_SESSION['username'] . ' has upload' . ' ' . $_FILES["file"]['name'] . ' type ' . $type . "\n";
                write_log('access.log', $text);
            }

}

function get_file_by_file_url($file_url)
{
    $id_user = $_SESSION['user_id'];
    $data = find_one_secure("SELECT * FROM files WHERE file_url = :file_url AND 
    								id_user = :id_user",
        ['file_url' => $file_url,
            'id_user' => $id_user]);
    return $data;
}

function file_exist($url)
{
    $data = get_file_by_file_url($url);
    if ($data == false) {
        return false;
    }
    return true;
}

function my_files()
{
    $id_user = $_SESSION['user_id'];

    $data = find_all_secure("SELECT * FROM files WHERE id_user = :id_user",
        ['id_user' => $id_user]);
    return $data;
}


function extension_txt()
{
    $extension_txt = '.txt';
    return $extension_txt;
}

function extension_accept($file_name)
{
    $extension_accept = array('.jpg', '.jpeg', '.txt', '.png', '.pdf','.docx');
    $format = file_extension($file_name);
    return in_array($format, $extension_accept);
}

function extension_img()
{
    $extension_images = array('.jpg', '.jpeg', '.png');
    return $extension_images;
}

function delete_file()
{
    $bool = false;
    if (isset($_POST['submit_delete'])) {
        if ($_POST['file_to_delete'] !== '') {
            $id_user = $_SESSION['user_id'];
            $file_url = $_POST['file_to_delete'];
            delete_one_secure("DELETE FROM files WHERE file_url = :file_url AND id_user = :id_user",
                ['file_url' => $file_url,
                    'id_user' => $id_user]);
            $date = give_me_date();
            $text = $date . ' ' . $_SESSION['username'] . ' has delete' . ' ' . $file_url . "\n";
            write_log('access.log', $text);
            unlink($file_url);
            $bool = true;

        }
    }

    return $bool;
}

function rename_file()
{
    $bool = false;
    if (isset($_POST['submit_rename'])) {
        if ($_POST['current_file_name'] != '' && $_POST['file_to_rename'] != '' && $_POST['new_name'] != '') {
            $id_user = $_SESSION['user_id'];
            $file_to_rename = $_POST['current_file_name'];
            $current_file_url = $_POST['file_to_rename'];
            $file_ext = strrchr($file_to_rename, '.');
            $file_name = $_POST['new_name'] . $file_ext;
            $file_url = substr($current_file_url, 0, -(strlen($file_to_rename))) . $file_name;


            if (!file_exist($file_url)) {
                rename_one_secure("UPDATE files SET file_name = :file_name , file_url = :file_url  WHERE id_user = :id_user AND file_url = :current_file_url",
                    ['file_name' => $file_name,
                        'file_url' => $file_url,
                        'current_file_url' => $current_file_url,
                        'id_user' => $id_user]);
                          rename($current_file_url, $file_url);
                $bool = true;
            }
            $date = give_me_date();
            $text = $date . ' ' . $_SESSION['username'] . ' has rename' . ' ' . $file_to_rename . ' ' . 'in' . ' ' . $file_name . "\n";
            write_log('access.log', $text);
        }
    }
    return $bool;
}

function errorsRegister(){
    $error = array();
    $error['password'] = 'Please enter a correct password in the confirm';
    $error['email']= 'Email already exist';
    $error['empty'] = 'Empty information';
    return $error;
}