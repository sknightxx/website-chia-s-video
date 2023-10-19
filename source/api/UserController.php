<?php
require_once("connection.php");
class UserController {
    public function check_user($username) {
        global $conn;
        $sql = $conn->prepare("select UID from user where UID like ?");
        $sql->bind_param("s", $username);
        $sql->execute();
        $uid = $sql->fetch();
        if(!is_null($uid)) {
            return true;
        }

        return false;
    }

    public function check_email($email) {
        global $conn;
        $sql = $conn->prepare("select Email from user where Email like ?");
        $sql->bind_param("s", $email);
        $sql->execute();
        $email = $sql->fetch();
        if(!is_null($email)) {
            return true;
        }

        return false;
    }

    public function add_user($username, $password, $email) {
        global $conn;
        $sql = $conn->prepare("insert into user (UID, Email, Password) values(?,?,?)");
        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
        $sql->bind_param("sss", $username, $email, $encrypted_password);
        return $sql->execute();
    }

    public function get_user($username) {
        global $conn;
        $sql = $conn->prepare("select UID, Name, Email, Bdate, Password, PermissionID, ProfilePic from user where UID like ?");
        $sql->bind_param("s", $username);
        $sql->bind_result($UID, $Name, $Email, $Bdate, $Password, $PermissionID, $ProfilePic);
        $sql->execute();
        $sql->fetch();
        return new UserInfo($UID, $Name, $Email, $Bdate, $Password, $PermissionID, $ProfilePic);
    }
}

class UserInfo {   
    public $UID;
    public $Name;
    public $Email;
    public $Bdate;
    public $Password;
    public $PermissionID;
    public $ProfilePic;

    public function __construct(
        $UID,
        $Name,
        $Email,
        $Bdate,
        $Password,
        $PermissionID,
        $ProfilePic)
    {
        $this->UID = $UID;
        $this->Name = $Name;
        $this->Email = $Email;
        $this->Bdate = $Bdate;
        $this->Password = $Password;
        $this->PermissionID = $PermissionID;
        $this->ProfilePic = $ProfilePic;
    }
}