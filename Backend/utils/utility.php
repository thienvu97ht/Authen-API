<?php
function fixSqlInjection($str)
{
    // abc\okok -> abc\\okok
    //abc\okok (user) -> abc\okok (server) -> sql (abc\okok) -> xuat hien ky tu \ -> ky tu dac biet -> error query
    //abc\okok (user) -> abc\okok (server) -> convert -> abc\\okok -> sql (abc\\okok) -> chinh xac
    $str = str_replace('\\', '\\\\', $str);
    //abc'okok -> abc\'okok
    //abc'okok (user) -> abc'okok (server) -> sql (abc'okok) -> xuat hien ky tu \ -> ky tu dac biet -> error query
    //abc'okok (user) -> abc'okok (server) -> convert -> abc\'okok -> sql (abc\'okok) -> chinh xac
    $str = str_replace('\'', '\\\'', $str);

    return $str;
}

// Hàm lấy dữ liệu người dùng từ token
function authenToken()
{

    if (isset($_SESSION['user'])) {
        return $_SESSION['user'];
    }
    // Lấy token từ cookie
    $token = getCOOKIE('token');
    if (empty($token)) {
        return null;
    }

    // Từ token gửi câu truy vấn lên sever để lấy thông tin người dùng
    $sql    = "select users.* from users, login_tokens where users.id = login_tokens.id_user and login_tokens.token = '$token'";
    $result = executeResult($sql);

    // Trả về dữ liệu người dùng tương ứng với token
    if ($result != null && count($result) > 0) {
        $_SESSION['user'] = $result[0];

        return $result[0];
    }

    return null;
}

function getPOST($key)
{
    $value = '';
    if (isset($_POST[$key])) {
        $value = $_POST[$key];
    }
    return fixSqlInjection($value);
}

function getCOOKIE($key)
{
    $value = '';
    if (isset($_COOKIE[$key])) {
        $value = $_COOKIE[$key];
    }
    return fixSqlInjection($value);
}

function getGET($key)
{
    $value = '';
    if (isset($_GET[$key])) {
        $value = $_GET[$key];
    }
    return fixSqlInjection($value);
}

function md5Security($pwd)
{
    return md5(md5($pwd) . MD5_PRIVATE_KEY);
}
