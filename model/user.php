<?php

require_once('model/db.php');

function get_user_by_id($id)
{
    $id = (int)$id;
    $data = find_one("SELECT * FROM users WHERE id = ".$id);
    return $data;
}

function get_user_by_username($username)
{
    $data = find_one_secure("SELECT * FROM users WHERE username = :username",['username' => $username]);
    return $data;
}

/**
 * @param $data
 * @return bool
 */
function user_check_register($data)
{
    if (empty($data['username']) OR empty($data['password']) OR empty($data['email']))
        return false;
    $data = getUserByUsername($data['username']);
    if ($data !== false)
        return false;


    // TODO : Check valid email
    return true;
}

function user_hash($password)
{
    $hash = password_hash($password, PASSWORD_BCRYPT, ['salt' => 'saltysaltysaltysalty!!']);
    return $hash;
}

function user_register($data)
{
    $user['username'] = $data['username'];
    $user['password'] = user_hash($data['password']);
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
    if ($hash !== $user['password'])
    {
        return false;
    }
    return true;
}

function user_login($username)
{
    $data = get_user_by_username($username);
    if ($data === false)
    {
        return false;
    }
    $_SESSION['user_id'] = $data['id'];
    $_SESSION['username'] = $data['username'];
    return true;
}

function upload_file()
{
    if (isset($_POST['upload'])) {
        $type='';
        $extension_images = array('.jpg', '.jpeg','.png','gif');
        $extension_txt = array('.txt','.docx');
        $extension_pdf = array('.pdf');
        if(in_array(file_extension($_FILES["file"]['name']),$extension_images)){
            $type='image';

        }else if(in_array(file_extension($_FILES["file"]['name']),$extension_txt)){
            $type = 'text';
        }
        else if(in_array(file_extension($_FILES["file"]['name']),$extension_pdf)){
            $type='pdf';
        }
        echo file_extension($_FILES["file"]['name']);
        $username =$_SESSION['username'];
        $files['file_name'] = $_FILES["file"]['name'];
        $files['file_url'] = 'uploads/' . $username . '/' . $files["file_name"];
        $files['type']= $type;
        $files['id_user'] = $_SESSION['user_id'];

        db_insert('files', $files);
        move_uploaded_file($_FILES["file"]["tmp_name"], $files['file_url']);

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

function file_exist($url){
    $data = get_file_by_file_url($url);
    if ($data == false){
        return false;
    }
    return true;
}

function my_files(){
    $id_user = $_SESSION['user_id'];

    $data = find_all_secure("SELECT * FROM files WHERE id_user = :id_user",
        ['id_user' => $id_user]);
    return $data;
}

function file_extension($file_name){
    return strrchr($file_name, '.');
}
function extension_txt(){
    $extension_txt = '.txt';
    return $extension_txt;
}
function extension_accept($file_name){
    $extension_accept = array('.jpg', '.jpeg', '.txt','.png','.pdf');
    $format = file_extension($file_name);
    return in_array($format, $extension_accept);
}
function extension_img(){
    $extension_images = array('.jpg', '.jpeg','.png');
    return $extension_images;
}
function delete_file(){
    $bool = false;
    if(isset($_POST['submit_delete'])){
        if($_POST['file_to_delete'] !== ''){
            $id_user = $_SESSION['user_id'];
            $file_url = $_POST['file_to_delete'];
            delete_one_secure("DELETE FROM files WHERE file_url = :file_url AND id_user = :id_user",
                ['file_url' => $file_url,
                    'id_user' => $id_user]);
                unlink($file_url);
                $bool = true;
            }
        }

    return $bool;
}
function rename_file(){
    $bool = false;
    if(isset($_POST['submit_rename'])){
        if($_POST['current_file_name'] != '' && $_POST['file_to_rename'] != '' && $_POST['new_name'] != ''){
            $id_user = $_SESSION['user_id'];
            $file_to_rename = $_POST['current_file_name'];
            $current_file_url = $_POST['file_to_rename'];
            $file_ext = strrchr($file_to_rename, '.');
            $file_name = $_POST['new_name'].$file_ext;
            $file_url = substr($current_file_url, 0, -(strlen($file_to_rename))).$file_name;


            if(!file_exist($file_url)){
                rename_one_secure("UPDATE files SET file_name = :file_name , file_url = :file_url  WHERE id_user = :id_user AND file_url = :current_file_url",
                    ['file_name' => $file_name,
                        'file_url' => $file_url,
                        'current_file_url' => $current_file_url,
                        'id_user' => $id_user]);
                    echo $current_file_url;
                    echo $file_url;
                    rename($current_file_url ,$file_url);
                    $bool = true;
                }

        }
    }
    return $bool;
}