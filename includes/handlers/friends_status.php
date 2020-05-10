<?php 
include('../../dbCon.php');
include('../classes/User.php');

if(isset($_SESSION['username'])){
    $userLoggedIn = $_SESSION['username'];
    $user_obj = new User($con,$userLoggedIn);
}else{
    header("Location: register.php");
}

if(isset($_REQUEST['call']) == true && $_REQUEST['call'] == 'get_friends_status'){



    //get friends list
    $friend_array_query = $con->prepare("SELECT friend_array FROM users WHERE username='$userLoggedIn'");
    $friend_array_query->execute();
    $friend_array_temp = $friend_array_query->fetch(\PDO::FETCH_ASSOC);
    $friends_array = preg_split("/\,/",$friend_array_temp['friend_array']);
    $friends_array = array_splice($friends_array,1,count($friends_array),"");
    if($friends_array[count($friends_array)-1] == ""){
        array_pop($friends_array);
    }

    //convert friends array to comma separated string
    $friends_list = "'";
    $friends_list .= implode("','",$friends_array);
    $friends_list .= "'";
    $str = '';
    //check if already in status table
    $check_already = $con->prepare("SELECT * FROM users_status WHERE username='$userLoggedIn'");
    $check_already->execute();

    //if it is already in the list then don't make a new row
    if($check_already->rowCount() == 0){

    //set current user status to online
    $set_current_user_status = $con->prepare("INSERT INTO users_status VAlUES('','$userLoggedIn',CURRENT_TIMESTAMP)");
    $set_current_user_status->execute();

    }else{
        $update_current_user = $con->prepare("UPDATE users_status SET last_seen=CURRENT_TIMESTAMP WHERE username='$userLoggedIn'");
        $update_current_user->execute();
    }
    //get friends status
    $get_friends_status = $con->prepare("SELECT * FROM users_status WHERE username IN($friends_list)");
    $get_friends_status->execute();
    $chk = $get_friends_status->rowCount();
        if($get_friends_status->rowCount() >= 1){
        $friends_status = $get_friends_status->fetchAll(\PDO::FETCH_ASSOC);
            foreach($friends_status as $friend){
                //get full name
                $friend_username = $friend['username'];
                $friend_obj = new User($con,$friend_username);
                $friend_fullname = $friend_obj->getFistAndLastName();
                $friend_pic = $friend_obj->getProfilePic();
                $current_status = "offline";
                // check if friend is in the list
                $check_friend_in_list = $con->prepare("SELECT * FROM users_status WHERE username='$friend_username'");
                $check_friend_in_list->execute();
                if($check_friend_in_list->rowCount() > 0){
                    $get_time_stamp = $con->prepare("SELECT last_seen FROM users_status WHERE username='$friend_username'");
                    $get_time_stamp->execute();
                    $time_stamp = $get_time_stamp->fetch(\PDO::FETCH_ASSOC);
                    $time_stamp = $time_stamp['last_seen'];
                    $last_seen = strtotime($time_stamp);
                    $current_time = time();
                    if($current_time - $last_seen >= 30){
                        $current_status = "offline";
                    }else{
                        $current_status = "online";
                    }
        
                }
                $str = '<div class='.'"f_status"'.'>
                <div class='.'"status_profile_pic"'.'>
                <img src='.$friend_pic.' width='.'"100%"'.' height='.'"100%"'.' style='.'"display:inline-block !important;"'.'>
                </div>
                <span class='.'"status_fname"'.'>'.$friend_fullname.'&nbsp;&nbsp;<span class='.'"status_indicator"'.' style='.'"';
                if($current_status == "online"){
                $str .= 'color:green;';
                }elseif($current_status == "offline"){
                    $str .= 'color:grey;';
                }
                $str .= 'text-align:left;font-size:0.6em;"'.'><i class="fas fa-circle"></i></span></span>
                <input type="hidden" value='.$friend_username.'>
                </div>';
                echo $str;
            }
        }else{
            echo "You have No Friends Online";
        }

}



?>