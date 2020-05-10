<?php 

class User{
    private $user;
    private $con;
    
    public function __construct($con,$user){
        $this->con = $con;
        $stmt = $con->prepare("SELECT * FROM users WHERE username='$user'");
        $stmt->execute();
        $this->user = $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    public function getFistAndLastName(){
        $username = $this->user['username'];
        $stmt = $this->con->prepare("SELECT first_name, last_name FROM users WHERE username='$username'");
        $stmt->execute();
        $res = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $res['first_name']. " ". $res['last_name'];
    }
    public function getUsername(){
        return $this->user['username'];
    }

    public function getNumPosts(){
        $username = $this->user['username'];
        $stmt = $this->con->prepare("SELECT num_posts FROM users WHERE username = '$username'");
        $stmt->execute();
        $res = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $res['num_posts'];
    }
    public function getProfilePic(){
        $username = $this->user['username'];
        $stmt = $this->con->prepare("SELECT profile_pic FROM users WHERE username='$username'");
        $stmt->execute();
        $res = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $res['profile_pic'];
    }
    public function is_closed(){
        $username = $this->user['username'];
        $check_closed_user = $this->con->prepare("SELECT user_closed FROM users WHERE username='$username'");
        $check_closed_user->execute();
        $row = $check_closed_user->fetch(\PDO::FETCH_ASSOC);
        if($row['user_closed'] == 'yes'){
            return true;
        }else{
            return false;
        }
    }
    public function isFriend($username_to_check){
        $usernameComma = $username_to_check;
        // if((strstr($this->user['friend_array'],$usernameComma)) || $username_to_check == $this->user['username']){
            if((strpos($this->user['friend_array'],$usernameComma)) || $username_to_check == $this->user['username']){
            return true;
        }else{
            return false;
        }
    }
    public function addFriend($username){
        $currentUser = $this->user['username'];
        $friend_array = $this->user['friend_array'] .$username.",";
        $submtit_new_friends = $this->con->prepare("UPDATE users SET friend_array='$friend_array' WHERE username='$currentUser'");
        $submtit_new_friends->execute();
    }
    public function removeFriend($username){
        $currentUser = $this->user['username'];
        $friend_array = $this->user['friend_array'];
        $friend_array = str_replace("$username,","",$friend_array);
        $update_friends = $this->con->prepare("UPDATE users SET friend_array='$friend_array' WHERE username='$currentUser'");
        $update_friends->execute();
    }
    public function totalFriends(){
        $currentUser = $this->user['username'];
        $friend_array_query = $this->con->prepare("SELECT friend_array FROM users WHERE username = '$currentUser'");
        $friend_array_query->execute();
        $friend_array_temp = $friend_array_query->fetch(\PDO::FETCH_ASSOC);
        $friends_array = preg_split("/\,/",$friend_array_temp['friend_array']);
        $friends_array = array_splice($friends_array,1,count($friends_array),"");
        if($friends_array[count($friends_array)-1] == ""){
            array_pop($friends_array);
            return count($friends_array);
        }else{
            return count($friends_array);
        }
    }
    public function addFriendRequest($username){
        $currentUser = $this->user['username'];
        $check_if_already = $this->con->prepare("SELECT * FROM friend_requests WHERE requester='$currentUser' AND receiver='$username'");
        $check_if_already->execute();
        if($check_if_already->rowCount() == 0){
            $add_friend_request = $this->con->prepare("INSERT INTO friend_requests VALUES('','$currentUser','$username','none')");
            $add_friend_request->execute();
            return true;
        }else{
            return false;
        }
    }
    public function check_friend_request($username){
        $currentUser = $this->user['username'];
        $check_if_already = $this->con->prepare("SELECT * FROM friend_requests WHERE requester='$currentUser' AND receiver='$username'");
        $check_if_already->execute();
        if($check_if_already->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }
}
?>