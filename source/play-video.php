<?php
session_start();
if(isset($_GET['id'])) {
    $vid = $_GET['id'];
    require_once('./api/VideoController.php');
    require_once('./api/UserController.php');
    require_once('./api/ChannelController.php');
    $vc = new VideoController();
    $video = $vc->get_video_by_id($vid);
    $user = (new UserController())->get_user($video->UID);
    $chc = new ChannelController();
    $channel = $chc->get_channel_by_owner($video->UID);
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
    <title><?php echo $video->Title ?></title>
    <link rel="stylesheet" href="./styles/style.css">
</head>

<body>
    <!-- navbar -->
    <nav class="flex-div justify-content-between">
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
                $uid = $_SESSION['uid'];
                $user = (new UserController())->get_user($uid);
            ?>  
                <img src="<?php echo $user->ProfilePic ?>" alt="" class="user-icon">
            <?php } else { ?>
                <a href="./login.php" class="btn btn-outline-primary">Đăng nhập</a>
            <?php }?>
        </div>
    </nav>
    <!-- hiển thị video -->
    <div class="container play-container">
        <div class="row">
            <div class="play-video col-md-12">
                <?php
                    if(!is_null($video)) {
                        $view = $vc->view_format($video->View); ?>
                        <div class="play-video-repon" id="video1">
                            <iframe width="1000" height="580" src="<?php echo $video->Url ?>?autoplay=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                        <div class="tags">
                            <a href="">#Coding</a>
                        </div>
                        <h3><?php echo $video->Title?></h3>
                        <div class="play-video-info">
                            <p><?php echo $view?> Views &bull; <?php if($vc->day_convert($video->UploadDate) == 0) echo 'today'; else echo $vc->day_convert($video->UploadDate) . ' ago'?></p>
                            <div>
                                <a href=""><img src="images/share.png" alt="">Share</a>
                            </div>
                        </div>
                        <hr>
                        <div class="plublisher">
                            <img src="<?php echo $user->ProfilePic?>" alt="">
                            <div>
                                <p><?php echo $channel->name?></p>
                                <span><?php $subscribe =  $chc->get_subscribes($channel->id);if($subscribe > 0) { echo $chc->subscribe_format($subscribe)?>Người đăng ký <?php }?></span>
                            </div>
                            <button type="button">Đăng ký</button>
                        </div>
                        <div class="vid-description">
                            <p><?php echo $video->Description ?></p>
                        </div>
                        <hr>
                        <h4>134 Comments</h4>
                        <div class="add-comment">
                            <img src="images/Jack.png" alt="">
                            <input type="text" placeholder="Viết bình luận của bạn">
                        </div>
                        <div class="old-comment">
                            <img src="images/Jack.png" alt="">
                            <div>
                                <h3>Jack Nicholson <span>2 days ago</span></h3>
                                <p>A global computer network</p>
                            </div>
                        </div>
                        <div class="acomment-action">
                            <a href="">Tất cả bình luận</a>
                        </div>
                <?php
                    }
                ?>


            </div>
            <!-- right-sidebar -->
            <div class="right-sidebar">
                <?php
                    $related = $vc->get_related($user->UID);
                    foreach($related as $rvid) {
                        $view = $vc->view_format($rvid->View);
                        if($rvid->VID == $vid) {
                            continue;
                        }
                        ?>
                        <div class="side-video-list">
                        <a href="play-video.php?id=<?php echo $rvid->VID?>"><img src="<?php echo $rvid->Thumbnail ?>" class="small-thumbnail" alt=""></a>
                            <div class="vid-info">
                                <a href="play-video.php?id=<?php echo $rvid->VID?>"><?php echo $rvid->Title ?></a>
                                <p><?php echo $channel->name?></p>
                                <p><?php echo $view?> Views</p>
                            </div>
                        </div>
                <?php }?>
            </div>
        </div>
    </div>
    <script src="./vendor/jquery/jquery-3.6.4.min.js"></script>
    <script src="./js/script.js"></script>
</body>

</html>