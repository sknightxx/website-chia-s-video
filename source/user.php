<?php
session_start();
if(!isset($_SESSION['uid'])) {
    header("location: login.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="./vendor/jquery/jquery-3.6.4.min.js"></script>
    <title>User</title>
    <link rel="stylesheet" href="./styles/user.css">
</head>
<body>
    <div class="main d-flex">
        <div class="sidebar" id="side_nav">
            <div class="header-box px-3 pt-3 pb-4 d-flex justify-content-between">
            <button class="btn d-block close-btn px-1 py-0 text-black-50"><i class="fa-sharp fa-solid fa-bars"></i></button>
                <h1 class="fs-4"><a href="./index.php"><img src="./images/logo.png" alt="" class="logo px-2 me-2"></a><span class="text-black">VideoShare</span></h1>
            </div>
            <ul class="list-unstyled row">
                <li class="px-2 btn btn-outline-light text-start my-5 ms-3 fs-5 service" id="upload" uid="<?php echo $_SESSION['uid']?>"><a href="#" class="text-decoration-none text-black-50"><i class="fa-solid fa-upload me-4"></i> Đăng Video</a></li>
                <li class="px-2 btn btn-outline-light text-start mb-5 ms-3 fs-5 service" id="videos"><a href="#" class="text-decoration-none text-black-50"><i class="fa-solid fa-circle-play me-4"></i></i>Video đã đăng</a></li>
                <li class="px-2 btn btn-outline-light text-start mb-5 ms-3 fs-5 service" id="subscription"><a href="#" class="text-decoration-none text-black-50"><i class="fa-brands fa-square-youtube me-4"></i> Kênh đã đăng ký</a></li>
                <li class="px-2 btn btn-outline-light text-start mb-5 ms-3 fs-5 service" id="playlist"><a href="#" class="text-decoration-none text-black-50"><i class="fa-solid fa-bars-staggered me-4"></i> Danh sách phát</a></li>
            </ul>
        </div>
        <div class="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between d-block nav-header">
                        <button class="btn px-1 py-0 open-btn"><i class="fa-sharp fa-solid fa-bars"></i></button>
                        <a class="navbar-brand fs-4" href="#"><img src="./images/logo.png" alt="" class="logo px-2 me-2">VideoShare</a>
                    </div>
                    
                </div>
              </nav>
            <div class="home container">
                <div class="row d-flex justify-content-center">
                    <h1 class="h1 text-center pt-2 col-12">Chào mừng đến trang người dùng</h1>
                    <img src="./images/logo.png" alt="" class="px-2 me-2 col-12">
                </div>
            </div>
        </div>
    </div>
    <script src="./js/user.js"></script>
</body>
</html>