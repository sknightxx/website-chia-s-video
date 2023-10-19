<?php
require_once('connection.php');
function checkSize($file) {
    $size  = stat($file)['size'];
    if($size <= 2*1048576*1048576) {
        return true;
    }
    return false;
}
if(isset($_GET['action'])) {
    $action = $_GET['action'] ;
    if($action === "view_update") {
        $id = $_GET['vid'];
        (new VideoController())->update_view($id);
    }
    else if($action === 'upload') {
        $title = $_POST['title'];
        $desc = $_POST['desc'];
        $uid = $_GET['uid'];
        $_FILES["video"]["name"] = str_replace(' ','', $_FILES["video"]["name"]);
        $_FILES["thumbnail"]["name"] = str_replace(' ','', $_FILES["thumbnail"]["name"]);
        $target_video = "./videos/";
        $target_img = "./images/thumbnails/";
        $url = $target_video . basename($_FILES["video"]["name"]);
        $thumbnail = $target_img . basename($_FILES["thumbnail"]["name"]);
        $flag = 1;
        if (file_exists("." . $thumbnail)) {
            $flag = 0;
        }
        else {
            move_uploaded_file($_FILES["thumbnail"]["tmp_name"], "." . $thumbnail);
        }
        $flag = 1;
        $size  = stat("." . $url)['size'];
        if(checkSize("." . $url)) {
            if (file_exists("." . $url)) {
                $flag = 0;
            }
            else {
                move_uploaded_file($_FILES["video"]["tmp_name"], "." . $url);
            }
    
            require_once('ChannelController.php');
            $chc = new ChannelController();
            $channel = $chc->get_channel_by_owner($uid);
            $vc = new VideoController();
            if(is_null($channel->owner_id)) {
                $sql = $conn->prepare('insert into channel(ChannelName, UID) values(?, ?)');
                $sql->bind_param('ss', $uid, $uid);
                $sql->execute();
            }
            if($vc->upload_video($title, $desc, $url, $thumbnail, $size, $uid)) {
                echo json_encode(array('status'=>true, 'messege'=>'Upload video thành công'));
            }
        }
        else {
            echo json_encode(array('status'=>false, "messege"=>"Kích cỡ file quá lớn (Yêu cầu <= 2 GB"));
        }
        die();
    }
}
class VideoController {
    public function get_all() {
        global $conn;
        $sql = "select VID, Title, Size, Description, TIMESTAMPDIFF(DAY, UploadTime, CURRENT_TIMESTAMP()) as daydiff, Thumbnail, ModifiedTime, View, Url, UID, PrivacyID from video where PrivacyID = 1";
        $stm = $conn->query($sql);
        $videos = [];
        while($res = $stm->fetch_assoc()) {
            $videos[] = new VideoInfo($res['VID'], $res['Title'], $res['Size'], $res['Description'], $res['daydiff'], $res['Thumbnail'], $res['ModifiedTime'], $res['View'], $res['Url'], $res['UID'], $res['PrivacyID']);
        }
        return $videos;
    }

    public function get_related($UID) {
        global $conn;
        $sql = "select VID, Title, Size, Description, TIMESTAMPDIFF(DAY, UploadTime, CURRENT_TIMESTAMP()) as daydiff, Thumbnail, ModifiedTime, View, Url, UID, PrivacyID from video where PrivacyID = 1 limit 25";
        $stm = $conn->prepare($sql);
        $videos = [];
        if($stm->execute()) {
            $stm->bind_result($VID, $Title, $Size, $Description, $UploadDate, $Thumbnail, $ModifiedDate, $View, $Url, $UID, $TypeID);
            while($stm->fetch()) {
                $videos[] = new VideoInfo($VID, $Title, $Size, $Description, $UploadDate, $Thumbnail, $ModifiedDate, $View, $Url, $UID, $TypeID);
            }
        }
        return $videos;
    }

    public function get_video_by_id($id) {
        global $conn;
        $sql = $conn->prepare("select VID, Title, Size, Description, TIMESTAMPDIFF(DAY, UploadTime, CURRENT_TIMESTAMP()) as daydiff, Thumbnail, ModifiedTime, View, Url, UID, PrivacyID from video where VID = ?");
        $sql->bind_param("i", $id);
        if($sql->execute()) {
            $sql->bind_result($VID, $Title, $Size, $Description, $UploadDate, $Thumbnail, $ModifiedDate, $View, $Url, $UID, $TypeID);
            $sql->fetch();
            return new VideoInfo($VID, $Title, $Size, $Description, $UploadDate, $Thumbnail, $ModifiedDate, $View, $Url, $UID, $TypeID);
        }

        return null;
    }

    public function day_convert($day) {
        if($day == 0) {
            return 0;
        }
        
        if($day >= 365) {
            $day = intdiv($day, 365);
            if($day < 2) {
                $day .= ' year';
            }
            else {
                $day .= ' years';
            }
        }
        else if($day >= 30) {
            $day = intdiv($day, 30);
            if($day < 2) {
                $day .= ' month';
            }
            else {
                $day .= ' months';
            }
        }
        else if($day >= 7) {
            $day = intdiv($day, 7);
            if($day < 2) {
                $day .= ' week';
            }
            else {
                $day .= ' weeks';
            }
        }
        else {
            if($day < 2) {
                $day .= ' day';
            }
            else {
                $day .= ' days';
            }
        }
        return $day;
    }
    public function upload_video($title, $desc, $url, $thumnail, $size, $uid) {
        global $conn;
        $sql = $conn->prepare('insert into video(Title, Description, URL, Thumbnail, Size, UID) values(?, ?, ?, ?, ?, ?)');
        $sql->bind_param("ssssis", $title, $desc, $url, $thumnail, $size, $uid);
        if($sql->execute()) {
            return true;
        }
        return false;

    }
    public function view_format($view) {
        if($view >= 1000 && $view < 1000000) {
            $view_format = number_format($view / 1000, 1) . 'K';
        }
        else if($view >= 1000000 && $view < 1000000000) {
            $view_format = number_format($view / 1000000, 1) . 'M';
        }
        else if ($view >= 1000000000) {
            $view_format = number_format($view / 1000000000, 1) . 'B';
        }
        else {
            $view_format = $view;
        }

        return $view_format;
    }

    public function update_view($vid) {
        global $conn;
        $sql = $conn->prepare("update video set view = view + 1 where VID = ?");
        $sql->bind_param("i", $vid);
        if(!$sql->execute()) {
            return true;
        }
        return false;
    }
}

class VideoInfo {
    public $VID;
    public $Title;
    public $Size;
    public $Description;
    public $UploadDate;
    public $Thumbnail;
    public $ModifiedDate;
    public $View;
    public $Url;
    public $UID;
    public $TypeID;

    public function __construct($VID, $Title, $Size, $Description, $UploadDate, $Thumbnail, $ModifiedDate, $View, $Url, $UID, $TypeID)
    {
        
        $this->VID = $VID;
        $this->Title = $Title;
        $this->Size = $Size;
        $this->Description = $Description;
        $this->UploadDate = $UploadDate;
        $this->Thumbnail = $Thumbnail;
        $this->ModifiedDate = $ModifiedDate;
        $this->View = $View;
        $this->Url = $Url;
        $this->UID = $UID;
        $this->TypeID = $TypeID;
    } 
}