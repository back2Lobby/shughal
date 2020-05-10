<?php 
include('../../dbCon.php');
include('../classes/User.php');
if(isset($_SESSION['username'])){
    $userLoggedIn = $_SESSION['username'];
}else{
    header("Location: register.php");
}


if(isset($_REQUEST['userLoggedIn']) && $_REQUEST['userLoggedIn'] == $_SESSION['username']){
    $userLoggedIn = $_REQUEST['userLoggedIn'];

    //remove accepted friend request seen already
    $remove_accepted_request = $con->prepare("DELETE FROM notifications WHERE action_notif='accepted your friend request' AND seen='yes'");
    $remove_accepted_request->execute();
    $total_removed = $remove_accepted_request->rowCount();

    //get notification data
    $getNotifications = $con->prepare("SELECT * FROM notifications WHERE notif_to='$userLoggedIn' ORDER BY id DESC"); 
    $getNotifications->execute();

    if($getNotifications->rowCount() > 0){
        $notif_data = $getNotifications->fetchAll(\PDO::FETCH_ASSOC);
        //getting all notifications
            foreach($notif_data as $notif){
                if($notif['notif_from'] !== $userLoggedIn){
                  if($notif['notif_from'] !== $notif['notif_to']){  
                    $notif_post_id = $notif['post_id']; 

                    //if it is zero it means it is a friend request notification
                    if($notif_post_id !== '0'){
                    //get post body of current notification
                    $get_post_body = $con->prepare("SELECT body FROM posts WHERE id='$notif_post_id'");
                    $get_post_body->execute();
                    $post_body_full = $get_post_body->fetch(\PDO::FETCH_ASSOC);
                    $post_body_full = $post_body_full['body'];
                    //max length of body is about 35 characters
                    if(strlen($post_body_full) > 40){
                    $post_body = substr($post_body_full,0,35);
                    $post_body = $post_body . "...";
                    }else{
                        $post_body = $post_body_full;
                    }
                    }else{
                        $post_body = "";
                    }
                    $notif_from = $notif['notif_from'];
                    $sender_profile = preg_replace("/\//","//",$notif['sender_profile'],);
                    $notif_id = $notif['id'];
                    $notif_sender_obj = new User($con,$notif['notif_from']);
                    $notif_fullname = $notif_sender_obj->getFistAndLastName();
                    //set all these notifications to seen
                    $make_notif_seen = $con->prepare("UPDATE notifications SET seen='yes' WHERE id='$notif_id'");
                    $make_notif_seen->execute();


                    //get friend request id for the accept button
                    $friend_requests = $con->prepare("SELECT id FROM friend_requests WHERE requester='$notif_from' AND receiver='$userLoggedIn'");
                    $friend_requests->execute();
                    $fr_id = $friend_requests->fetch(\PDO::FETCH_ASSOC);
                    if($friend_requests->rowCount() > 0){
                    $fr_id = $fr_id['id'];
                    }

                    //send the output notifications
                    $str = '';
                    $str .= '<div class='.'"notification"'.' id='.'"notification'.$notif["id"].'">
                        <div class='.'"notif_action"'.'>';
                    if($post_body == "" && $notif_post_id == '0'){
                        $str .= '<i class='.'"fas fa-user"'.' style='.'"color:#e0245e;"'.'></i>
                        </div>';
                    }elseif($notif['action_notif'] == "commented on your post"){
                        $str .= '<i class='.'"fas fa-comment"'.' style='.'"color:#e0245e;"'.'></i>
                        </div>';
                    }else{
                    $str .= '<i class='.'"fas fa-heart"'.' style='.'"color:#e0245e;"'.'></i>
                        </div>';
                    }
                    $str .= '<div class='.'"notif_body"'.'>
                        <div class='.'"notif_image"'.' style='.'"background:url('."'".$sender_profile."'".');"'.'>
                        </div>
                        <div class='.'"notif_head"'.'><b>
                        '.$notif_fullname."</b> ".$notif['action_notif'];


                        if($post_body == "" && $notif_post_id == '0' && $notif['action_notif'] == 'sent you friend request'){
                            $str .= '&nbsp;&nbsp;<button class='.'"accept-btn btn"'.' id='.'"accept'.$fr_id.'"'.' onClick='.'"javscript:(function(e){fetch('."'"."includes/handlers/friends.php?request_id=".$fr_id."'".').then(res=>{res.text().then(data=>{if('."'accepted'".'==data){if(document.querySelector('."'.friend_option'".') !== null)document.querySelector('."'.friend_option'".').innerHTML = '."`Friends <i class="."'fas fa-user-check'"."></i>`".';fetch('.'`'."includes/handlers/del_notifs.php?delNotif=".$notif_id.'`'.');debugger;setTimeout(()=>{document.querySelector('.'`#notif_del'.$notif_id.'`'.').click()},500);}})})})()"'.'>Accept</button>';
                        }     
                        $str .='
                        </div>
                        <div class='.'"notif_text"'.'>
                            '.$post_body;  
                    $str .='
                        </div>
                    </div>
                    <div class='.'"notif_del"'.' id='.'"notif_del'.$notif_id.'"'.'>
                        <i class="fas fa-times"></i>
                    </div>
                    </div><hr>';
                    echo $str;
                  }
                }
            }
    }else{
        echo "No notifications Found For You";
    }
}
    //getting a notification
    if(isset($_REQUEST['get_new_notif']) == true && $_REQUEST['get_new_notif'] == "yes"){
        $get_new_notifications = $con->prepare("SELECT * FROM notifications WHERE notif_to='$userLoggedIn' AND seen='no'"); 
        $get_new_notifications->execute();
        $new_notifications = $get_new_notifications->rowCount();
        if($new_notifications >= 1){
            echo "new notifications found";
        }else{
            echo "No new notifications found for you";
        }
    }


?>