<?php

require_once('model/db.php');

function give_date()
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
        return false;
    }
    $data = get_user_by_username($data['usernameRegister']);
    if ($data !== false)
        return false;
    return true;
}
function user_check_by_password($data){
    if( !isset($data['passwordRegister']) ||
        ($data['passwordConfirm']) ||
        $data['passwordRegister'] !=$data['passwordConfirm']) {
            return false;
    }
    return true;

}
function get_user_by_email($email)
{
    $data = find_one_secure("SELECT * FROM users WHERE email = :email",
        ['email' => $email]);
    return $data;
}
function user_check_by_email($data){
    $invalid = false;
    if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
        $invalid = true;
        return $invalid;
    }
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
    $date = give_date();
    $text = $date . ' ' . $user['username'] . ' has register'."\n";
    write_log('access.log', $text);
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
    $date = give_date();
    $text = $date . ' ' . $_SESSION['username'] . ' logged in' . "\n";
    write_log('access.log', $text);
    return true;
}

function upload_file($post="")
{
    if (isset($_POST['upload'])) {
        $type = '';
        $username = $_SESSION['username'];
        $name = $_FILES["file"]['name'];
        $url ='uploads/' . $username . '/' .  $_FILES["file"]['name'];
        $type= dirname(mime_content_type($_FILES["file"]["tmp_name"]));
         if ($post['edit_name']) {
                    $files['file_name'] = $post['edit_name'];

                } else {
                    $files['file_name'] = $name;
                }
                $files['file_url'] = $url;
                $files['types'] = $type;
                $files['id_user'] = $_SESSION['user_id'];
                db_insert('files', $files);
                move_uploaded_file($_FILES["file"]["tmp_name"], $files['file_url']);
                $date = give_date();
                $text = $date . ' ' . $_SESSION['username'] . ' has upload' . ' ' . $_FILES["file"]['name'] . ' type ' . $type . $_FILES["file"]["tmp_name"]. "\n";
                write_log('access.log', $text);
                header("Refresh:0");
            }
}

function replace_file()
{
    if (isset($_POST['replace'])) {
        $new_file_name = $_FILES["file_to_replace"]['name'];
        $tmp_name = $_FILES['file_to_replace']['tmp_name'];
        $file_to_replace = $_POST['name_file_to_replace'];
        $id_user = $_SESSION['user_id'];
        $new_url = 'uploads/' . $_SESSION['username'] . '/' . $new_file_name;
        $old_url = 'uploads/' . $_SESSION['username'] . '/' . $file_to_replace;
        $type= dirname(mime_content_type($_FILES["file_to_replace"]["tmp_name"]));
        find_one_secure("UPDATE files SET file_url = :new_url
            WHERE  id_user= :id_user AND file_name = :file_to_replace ",
            ['id_user' => $id_user,
                'file_to_replace' => $file_to_replace,
                'new_url' => $new_url
            ]);
        find_one_secure("UPDATE files SET types = :types
            WHERE  id_user= :id_user AND file_name = :file_to_replace  ",
            ['id_user' => $id_user,
                'types', $type,
                'file_to_replace' => $new_file_name
            ]);
        find_one_secure("UPDATE files SET file_name = :new_file_name
            WHERE  id_user= :id_user AND file_name = :file_to_replace ",
            ['id_user' => $id_user,
                'file_to_replace' => $file_to_replace,
                'new_file_name' => $new_file_name
            ]);
            move_uploaded_file($tmp_name, $new_url);
            unlink($old_url);
            $date = give_date();
            $text = $date . ' ' . $_SESSION['username'] . ' has replace' . $file_to_replace . ' into ' . $new_file_name . ' ' . $new_url . $old_url.$type."\n";
            write_log('access.log', $text);
            echo "File replace with success";
            header("Refreh:0");
            return true;
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
    $error = false;
    if (isset($_POST['submit_delete'])) {
        if ($_POST['file_to_delete'] !== '') {
            $id_user = $_SESSION['user_id'];
            $file_url = $_POST['file_to_delete'];
            delete_one_secure("DELETE FROM files WHERE file_url = :file_url AND id_user = :id_user",
                ['file_url' => $file_url,
                    'id_user' => $id_user]);
            $date = give_date();
            $text = $date . ' ' . $_SESSION['username'] . ' has delete' . ' ' . $file_url . "\n";
            write_log('access.log', $text);
            unlink($file_url);
            $error = true;
        }
    }
    return $error;
}

function rename_file()
{
    $error='';
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
                }
                $date = give_date();
                $text = $date . ' ' . $_SESSION['username'] . ' has rename' . ' ' . $file_to_rename . ' ' . 'in' . ' ' . $file_name . "\n";
                write_log('access.log', $text);
            if ($file_to_rename === $file_name) {
                return false;
            }
            }
        }
}

function errors(){
    $error = array();
    $error['password'] = 'Please enter a correct password in the confirm';
    $error['email']= 'Email already exist';
    $error['empty'] = 'Empty information';
    $error['rename'] = 'Name already taken';
    $error['invalid'] = 'invalid Email';
    return $error;
}

function modify()
{
    $valid = false;
    if (isset($_POST['modify'])) {

            if(isset($_POST['txt_content']) && isset($_POST['url_txt']) && $_POST['url_txt'] != '') {
            $file = $_POST['url_txt'];
            $txt_content = $_POST['txt_content'];
            file_put_contents($file, $txt_content);

            $date = give_date();
            $text = $date . ' ' . $_SESSION['username'] . ' has modify a txt' . ' ' . $file . ' ' . $txt_content . "\n";
            write_log('access.log', $text);
            $valid = true;
        }
    }
    return $valid;
}