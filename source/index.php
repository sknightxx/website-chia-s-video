<?php
session_start();
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
    <title>Trang chủ</title>
    <link rel="stylesheet" href="./styles/style.css">
</head>

<body>
    <nav class="d-flex align-items-center p-2 justify-content-between">
        <div class="nav-left flex-div">
            <img src="images/menu.png" alt="" class="menu-icon">
            <a href="./index.php"><img src="images/logo.png" alt="" class="logo"></a>
        </div>
        <div class="nav-middle flex-div">
            <div class="search-box flex-div">
                <input type="text" placeholder="Search">
                <img src="images/search.png">
            </div>
        </div>
        <div class="nav-right flex-div">
            <?php if(isset($_SESSION['uid'])) { 
                require_once("./api/UserController.php");
                $uid = $_SESSION['uid'];
                $user = (new UserController())->get_user($uid);
            ?>  
                <img src="<?php echo $user->ProfilePic ?>" alt="" class="user-icon">
            <?php } else { ?>
                <a href="./login.php" class="btn btn-outline-primary">Đăng nhập</a>
            <?php }?>
        </div>
    </nav>

    <!-- ----------sidebar -->
    <div class="sidebar">
        <div class="shortcut-links mt-5">
            <ul class="list-unstyled">
                <li class="px-2 btn btn-outline-light w-100 text-start mb-3 active"><a href="#" class="text-decoration-none text-black-50"><i class="fa-solid fa-house me-4"></i> <p class="tab-text">Trang chủ</p></a></li>
                <li class="px-2 btn btn-outline-light w-100 text-start mb-3"><a href="./trending.php" class="text-decoration-none text-black-50"><i class="fa-solid fa-fire me-4"></i> <p class="tab-text">Thịnh hành</p></a></li>
                <li class="px-2 btn btn-outline-light w-100 text-start mb-3"><a href="#" class="text-decoration-none text-black-50"><i class="fa-brands fa-square-youtube me-4"></i> <p class="tab-text">Kênh đã đăng ký</p></a></li>
                <li class="px-2 btn btn-outline-light w-100 text-start mb-3"><a href="#" class="text-decoration-none text-black-50"><i class="fa-brands fa-youtube me-4"></i> <p class="tab-text">Thư viện</p></a></li>
                <li class="px-2 btn btn-outline-light w-100 text-start mb-3"><a href="./user.php" class="text-decoration-none text-black-50"><i class="fa-solid fa-play me-4"></i> <p class="tab-text">Quản lý video</p></a></li>
                <li class="px-2 btn btn-outline-light w-100 text-start mb-3"><a href="#" class="text-decoration-none text-black-50"><i class="fa-solid fa-bars-staggered me-4"></i> <p class="tab-text">Danh sách phát</p></a></li>
                <?php if($_SESSION['permission'] == 1) { ?>
                    <li class="px-2 btn btn-outline-light w-100 text-start mb-3"><a href="./admin.php" class="text-decoration-none text-black-50"><i class="fa-solid fa-users me-4"></i> <p class="tab-text">Admin dashboard</p></a></li>
                <?php }?>
            </ul>

            <hr>
        </div>
        <div class="subcribed-list">
            <h3>Kênh đăng ký</h3>
            <a href=""><img src="images/Jack.png" alt="">
                <p>Jack Nicholson</p>
            </a>
            <a href=""><img src="images/simon.png" alt="">
                <p>Simon Baker</p>
            </a>
            <a href=""><img src="images/tom.png" alt="">
                <p>Tom Hardy</p>
            </a>
            <a href=""><img src="images/megan.png" alt="">
                <p>Megan Ryan</p>
            </a>
            <a href=""><img src="images/cameron.png" alt="">
                <p>Cameron Diaz</p>
            </a>
        </div>
    </div>
    <!-- ----------main----------- -->
    <div class="container">
        
        <div class="list-container">
            <?php 
                require_once('./api/VideoController.php');
                require_once('./api/UserController.php');
                $vc = new VideoController();
                $videos = $vc->get_all();
                foreach($videos as $video) {
                    $user = (new UserController())->get_user($video->UID);
                     $view = $vc->view_format($video->View);
                ?>
                    <div class="vid-list" onclick="viewUpdate(<?php echo $video->VID?>)">
                        <a href="play-video.php?id=<?php echo $video->VID?>"><img src="<?php echo $video->Thumbnail ?>" class="thumbnail" alt=""></a>
                            <div class="flex-div">
                                <img src="images/Jack.png" alt="">
                                <div class="vid-info">
                                    <a href="play-video.php?id=<?php echo $video->VID?>"><?php echo $video->Title ?></a>
                                    <a href="#" class="fw-lighter"><?php echo $user->Name ?></a>
                                    <p><?php echo $view?> Views &bull; <?php if($vc->day_convert($video->UploadDate) == 0) echo 'today'; else echo $vc->day_convert($video->UploadDate) . ' ago'?></p>
                                </div>
                            </div>
                    </div>
            <?php } ?>
        </div>
    </div>


    <script src="./vendor/jquery/jquery-3.6.4.min.js"></script>
    <script src="./js/script.js"></script>
</body>

</html>