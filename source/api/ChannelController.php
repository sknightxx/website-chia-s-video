<?php
require_once("connection.php");
class ChannelController {
    public function get_channel_by_owner($owner_id) {
        global $conn;
        $sql = $conn->prepare("select * from channel where UID = ?");
        $sql->bind_param("s", $owner_id);
        if($sql->execute()) {
            $sql->bind_result($id, $name, $uid);
            $sql->fetch();
            return new ChannelInfo($id, $name, $uid);
        }

        return null;
    }

    public function get_subscribes($id) {
        global $conn;
        $sql = $conn->prepare("select count(*) from subscribe where ChannelID = ?");
        $sql->bind_param("i", $id);
        if($sql->execute()) {
            $sql->bind_result($subscribes);
            $sql->fetch();
            return $subscribes;
        }

        return null;
    }


    public function subscribe_format($subscribe) {
        if($subscribe >= 1000 && $subscribe < 1000000) {
            $subscribe_format = number_format($subscribe / 1000, 1) . 'K';
        }
        else if($subscribe >= 1000000 && $subscribe < 1000000000) {
            $subscribe_format = number_format($subscribe / 1000000, 1) . 'M';
        }
        else if ($subscribe >= 1000000000) {
            $subscribe_format = number_format($subscribe / 1000000000, 1) . 'B';
        }
        else {
            $subscribe_format = $subscribe;
        }

        return $subscribe_format;
    }
}

class ChannelInfo {
    public $id;
    public $name;
    public $owner_id;

    public function __construct($id, $name, $owner_id)
    {
        $this->owner_id = $owner_id;
        $this->name = $name;
        $this->id = $id;
    }
}