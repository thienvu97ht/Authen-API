<?php
session_start();
header('Access-Control-Allow-Origin: *');
require_once('../utils/utility.php');
require_once('../db/dbhelper.php');

$action = getPOST('action');
switch ($action) {
    case "login":
        doLogin();
        break;
    case "logout":
        doLogout();
        break;

    case "register":
        doRegister();
        break;

    case "list":
        doUserList();
        break;
};

function doLogout()
{
    $token = getCOOKIE('token');
    if (empty($token)) {
        $res = [
            "status" => 1,
            "msg" => "Đăng xuất thành công!!!",
        ];
        echo json_encode($res);
        return;
    }

    // Xoa token khoi database
    $sql = "delete from login_tokens where token = '$token'";
    execute($sql);

    // Xoa token khoi cookie
    setcookie('token', '', time() - 7 * 24 * 60 * 60, '/');

    $res = [
        "status" => 1,
        "msg" => "Đăng xuất thành công!!!",
    ];
    echo json_encode($res);
    session_destroy();
    return;
}

function doLogin()
{
    $email = getPOST('email');
    $password = getPOST('password');

    $password = md5Security($password);

    // Check xem email có tồn tại trong hệ thống
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $user = executeResult($sql, true);



    if ($user != null) {
        // Đăng nhập thành công
        $email = $user['email'];
        $id    = $user['id'];
        $token = md5Security($email . time() . $id);

        // Lưu token vào COOkIE
        setcookie('token', $token, time() + 7 * 24 * 60 * 60, '/');

        // Lưu token vòa database login_tokens
        $sql = "insert into login_tokens (id_user, token) values ('$id', '$token')";
        execute($sql);

        $res = [
            "status" => 1,
            "msg" => "Đăng nhập thành công!!!",
        ];
    } else {
        $res = [
            "status" => -1,
            "msg" => "Tài khoản hoặc mật khẩu không chính xác!!!"
        ];
    }

    echo json_encode($res);
}

function doRegister()
{
    $username = getPOST('username');
    $fullname = getPOST('fullname');
    $email    = getPOST('email');
    $password = getPOST('password');
    $address  = getPOST('address');

    // Kiểu tra xem username và email có tồn tại trong hệ thống
    $sql = "select * from users where username = '$username' or email = '$email'";

    // Lấy dữ liệu ra
    $result = executeResult($sql);

    if ($result == null || count($result) == 0) {
        // Tài khoản không tồn tại trong hệ thống
        $password = md5Security($password);
        $sql = "insert into users(fullname, username, email, password, address) values 
        ('$fullname', '$username', '$email', '$password', '$address')";
        execute($sql);

        $res = [
            "status" => 1,
            "msg" => "Tạo tài khoản thành công!!!"
        ];
    } else {
        // Tài khoản đã tồn tại trong hệ thống
        $res = [
            "status" => -1,
            "msg" => "Email hoặc Username đã tồn tại!!!"
        ];
    }
    echo json_encode($res);
}

function doUserList()
{
    // Lấy dữ liệu người dùng từ token
    $user = authenToken();

    // Nếu dữ liệu người dùng không tồn tại
    if ($user == null) {
        $res = [
            "status" => -1,
            "msg" => "Not login!!!",
            "userList" => [],
        ];
        echo json_encode($res);
        return;
    }

    // // Nếu người dùng tồn tại
    $sql = "select * from users";
    $result = executeResult($sql);
    $res = [
        "status" => 1,
        "msg" => "Logged!!!",
        "userList" => $result,
    ];

    echo json_encode($res);
}