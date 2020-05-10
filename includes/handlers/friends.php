<?php 
include('../../dbCon.php');
include('../classes/User.php');

if(isset($_SESSION['username'])){
    $userLoggedIn = $_SESSION['username'];
    $user_obj = new User($con,$userLoggedIn);
}else{
    header("Location: register.php");
}

if(isset($_REQUEST['friendBtn']) == true && isset($_REQUEST['target']) == true && $_REQUEST['friendBtn'] == "yes"){  

    $profile_username = $_REQUEST['target'];
    //make sure it is not user's own profile
    if($userLoggedIn !== $profile_username){
        if($user_obj->isFriend($profile_username)){
        //if both are already friends
            $user_obj->removeFriend($profile_username);
            // removing from profile user's friend array
            $profile_user = new User($con,$profile_username);
            $profile_user->removeFriend($userLoggedIn);
            echo "already friends";
        }elseif($user_obj->check_friend_request($profile_username) == true){
             echo "friend request added";
        }else{
        $user_profile_pic = $user_obj->getProfilePic();
        //send notification to other guy
        $sendNotification = $con->prepare("INSERT INTO notifications VALUES('','sent you friend request','$profile_username','$userLoggedIn','$user_profile_pic','0','no')");
        $sendNotification->execute();
            //if both are not friends already
            if($user_obj->addFriendRequest($profile_username)){
            echo "friend request added";
        }
        }
    }else{
        echo "own profile";
    }

}
if(isset($_REQUEST['request_id'])){
    $request_id = $_REQUEST['request_id'];

    //get request data
    $get_request_data = $con->prepare("SELECT * FROM friend_requests WHERE id='$request_id'");
    $get_request_data->execute();
    $request_data = $get_request_data->fetch(\PDO::FETCH_ASSOC);
    if($get_request_data->rowCount() > 0){
    $requester = $request_data['requester'];
    $receiver = $request_data['receiver'];
    $logged_user_pic = $user_obj->getProfilePic();
    if($userLoggedIn == $receiver){
    $user_obj->addFriend($requester);
    // adding to profile user's friend array
    $profile_user = new User($con,$requester);
    $profile_user->addFriend($receiver);
    //removing from requests list
    $remove_request = $con->prepare("DELETE FROM friend_requests WHERE id='$request_id'");
    $remove_request->execute();

     //check if already
        $check_notif = $con->prepare("SELECT * FROM notifications WHERE action_notif='accepted your friend request' AND notif_to='$requester' AND notif_from='$receiver' AND seen='no'");
        $check_notif->execute();
        if($check_notif->rowCount() == 0){
            // sending notification back to the requester
            $accepted = $con->prepare("INSERT INTO notifications VALUES('','accepted your friend request','$requester','$receiver','$logged_user_pic','0','no')");
            $accepted->execute();
            echo "accepted";
        }
    }
   }
}   

if(isset($_REQUEST['call']) == true && isset($_REQUEST['profile_target']) == true && $_REQUEST['call'] == "check_friend"){
    if($user_obj->isFriend($_REQUEST['profile_target'])){
        echo "true";
    }
}
if(isset($_REQUEST['friends_list'])){
        //get friends list
        $friend_array_query = $con->prepare("SELECT friend_array FROM users WHERE username='$userLoggedIn'");
        $friend_array_query->execute();
        $friend_array_temp = $friend_array_query->fetch(\PDO::FETCH_ASSOC);
        $friends_array = preg_split("/\,/",$friend_array_temp['friend_array']);
        $friends_array = array_splice($friends_array,1,count($friends_array),"");
        if($friends_array[count($friends_array)-1] == ""){
            array_pop($friends_array);
        }
        foreach($friends_array as $friend){
            $get_friends_data = $con->prepare("SELECT first_name, last_name,profile_pic FROM users WHERE username='$friend'");
            $get_friends_data->execute();
            $friend_data = $get_friends_data->fetch(\PDO::FETCH_ASSOC);
            $profile_pic = $friend_data['profile_pic'];
            $fullName = $friend_data['first_name']. " ".$friend_data['last_name'];
            echo "<div class='card friend_profile_card' style='width: 11rem;border-color:#527ca2;'>
            <img class='card-img-top' src='$profile_pic' alt='Card image cap'>
            <div class='card-body' style='background-color:#527ca2;'>
              <h5 class='card-title'>$fullName</h5>
              <a href='http://localhost/Shughal/$friend' class='btn btn-warning'>Visit Profile</a>
            </div>
          </div>";
        }
}
if(isset($_REQUEST['random_people'])){
        $get_random_data = $con->prepare("SELECT * FROM users ORDER BY RAND() LIMIT 6");
        $get_random_data->execute();
        $random_data = $get_random_data->fetchAll(\PDO::FETCH_ASSOC);
        foreach($random_data as $random){
        $profile_pic = $random['profile_pic'];
        $fullName = $random['first_name']. " ".$random['last_name'];
        $randomName = $random['username'];
        echo "<div class='card people_profile_card' style='width: 11rem;border-color:#527ca2;'>
        <img class='card-img-top' src='$profile_pic' alt='Card image cap'>
        <div class='card-body' style='background-color:#527ca2;'>
          <h5 class='card-title'>$fullName</h5>
          <a href='http://localhost/Shughal/$randomName' class='btn btn-warning'>Visit Profile</a>
        </div>
      </div>";
        }
}

?>