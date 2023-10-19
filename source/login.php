<?php
session_start();
require_once("./api/connection.php");



if($_SERVER['REQUEST_METHOD'] === "POST") {
    if($_POST['type'] === 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $sql = $conn->prepare("select UID, Name, PermissionID, Password from user where UID = ?");
        $sql->bind_param("s", $username);
        if($sql->execute()) {
            $sql->bind_result($uid, $name, $pid, $pw);
            $sql->fetch();
            if(password_verify($password, $pw)) {
                $_SESSION['uid'] = $uid;
                $_SESSION['name'] = $name;
                $_SESSION['permission'] = $pid;
                echo json_encode(array("status" => true));
            }
            else {
                echo json_encode(array("status" => false,));
            }
        }
    }

    else if($_POST['type'] === 'register') {
        require_once("./api/UserController.php");
        $uc = new UserController();
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        if($uc->check_user($username)) {
            echo json_encode(array("status"=>false, "messege"=>"Tên người dùng đã tồn tại"));
        }
        else if($uc->check_email($email)) {
            echo json_encode(array("status"=>false, "messege"=>"Email đã tồn tại"));
        }
        else{
            if(!is_null($uc->add_user($username, $password, $email))) {
                echo json_encode(array("status"=>true, "messege"=>"Tạo tài khoản thành công"));
            }
            else {
                echo json_encode(array("status"=>false, "messege"=>"Có lỗi xảy ra, vui lòng thử lại sau"));
            }
        }
    }
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./vendor/bootstrap-5.0.2/bootstrap.css">
    <script src="./vendor/bootstrap-5.0.2/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="./styles/login_style.css">
    <style>
        #invalidMSG {
            font-size: 13px;
        }
        #registerForm {

            display: none;
        }


    </style>
    <title>Login</title>
</head>
<body class="d-flex vh-100">
    <form id="registerForm" method="post" class="container align-self-center justify-content-center">
        <div class="row h-100 mx-2 main d-flex">
            <div class="col-12 col-md-6 header text-center px-4 py-sm-5 d-flex justify-content-center">
                <div class="content align-self-center">
                    <div class="h2 mb-3">Chào mừng đến với đăng ký</div>
                    <p class="mb-3">Bạn đã có tài khoản?</p>
                    <a href="#" class="btn btn-outline-light login-btn" >Đăng Nhập</a>
                </div>

            </div>
            <div class="col-12 col-md-6 py-3 ps-md-4">
                <h3 class="fw-light mt-3 mb-5 text-md-center">Đăng Ký</h3>
                <div class="user-container mb-4">
                    <label for="rusername" class="fw-bold fs-6">Tên đăng nhập</label><br>
                    <input type="text" name="rusername" id="rusername" placeholder="Tên đăng nhập" class="login-input" ><br>
                </div>
                <div class="password-container mb-3">
                    <label for="rpassword" class="fw-bold fs-6">Mật khẩu</label><br>
                    <input type="password" name="rpassword" id="rpassword" placeholder="Mật khẩu" class="login-input">
                </div>
                <div class="email-container mb-2">
                    <label for="email" class="fw-bold fs-6">Email</label><br>
                    <input type="text"  name="email" id="email" placeholder="Email" class="login-input">
                </div>
                <div class="show--container mb-3">
                    <input type="checkbox" class="form-check-input" id="showregist" name="showregist">
                    <label for="showregist" class="ps-2 remember-text fw-bold">Hiện mật khẩu</label><br>
                </div>
                <input type="submit" value="Đăng ký" class="btn btn-primary submit-btn w-100 mt-3 mb-1 " id="registbtn">
            </div>
        </div>
    </form>

    <form id="loginForm" method="post" class="container d-flex align-self-center justify-content-center">
        <div class="row h-100 mx-2 main d-flex">
            <div class="col-12 col-md-6 header text-center px-4 py-sm-5 d-flex justify-content-center">
                <div class="content align-self-center">
                    <div class="h2 mb-3">Chào mừng đến với đăng nhập</div>
                    <p class="mb-3">Bạn chưa có tài khoản?</p>
                    <a href="#" class="btn btn-outline-light register-btn" >Đăng Ký</a>
                </div>

            </div>
            <div class="col-12 col-md-6 py-3 ps-md-4">
                <h3 class="fw-light mt-3 mb-5 text-md-center">Đăng nhập</h3>
                <div class="user-container mb-3">
                    <label for="username" class="fw-bold fs-6">Tên đăng nhập</label><br>
                    <input type="text" name="username" id="username" placeholder="Tên đăng nhập" class="login-input" ><br>
                </div>
                <div class="password-container mb-3">
                    <label for="password" class="fw-bold fs-6">Mật khẩu</label><br>
                    <input type="password" name="password" id="password" placeholder="Mật khẩu" class="login-input">
                </div>
                <div class="show--container mb-3">
                    <input type="checkbox" class="form-check-input" id="showlogin" name="showlogin">
                    <label for="showlogin" class="ps-2 remember-text fw-bold">Hiện mật khẩu</label><br>
                </div>
                <div class="remember-container mb-3">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" value="lsRememberMe">
                    <label for="remember" class="ps-2 remember-text fw-bold">Lưu dăng nhập</label><br>
                </div>
                <a href="#" class="text-black-50 text-decoration-none">Quên mật khẩu?</a>
                <input type="submit" id="loginbtn" value="Đăng nhập" class="btn btn-primary submit-btn w-100 mt-3 mb-1" onclick="isRememberMe">
            </div>
        </div>
    </form>

    <script src="./vendor/jquery/jquery-3.6.4.min.js"></script>
    <script src="./js/login.js"></script>
</body>
</html>
