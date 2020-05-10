<?php 
include('../../dbCon.php');
include('../classes/User.php');
include('../classes/Post.php');

if(isset($_SESSION['username'])){
    $userLoggedIn = $_SESSION['username'];
    $user_obj = new User($con,$userLoggedIn);
}else{
    header("Location: register.php");
}

if(isset($_REQUEST['call']) && $_REQUEST['call'] == "message_post"){
    //make sure we have opened/selected a friend
    if($_REQUEST['friend_name'] !== "none"){
    $friend_name = $_REQUEST['friend_name'];
    $message_body = $_REQUEST['message_body'];

    $res = file_get_contents("php://input");
    $res = "data:image/jpg;base64,".base64_encode($res);

    $post_obj = new Post($con,$userLoggedIn);
    $post_obj->submitMessage($message_body,$friend_name);
    }
}
// get all the conversation b/w current logged user and the opened friend
if(isset($_REQUEST['call']) && $_REQUEST['call'] == "get_all_messages"){
    $friend_name = $_REQUEST['friend_name'];
    $get_all_messages = $con->prepare("SELECT * FROM messages WHERE (msg_from='$userLoggedIn' AND msg_to='$friend_name') OR (msg_from='$friend_name' AND msg_to='$userLoggedIn') ORDER BY msg_time");
    $get_all_messages->execute();
    $all_messages = $get_all_messages->fetchAll(\PDO::FETCH_ASSOC);
    $str = "";
    $tempDate = "";
    foreach($all_messages as $message){
        $msg_id = $message['id'];
        $msg_body = $message['msg_body'];
        $msg_time = date("h:i",strtotime($message['msg_time']));

        //make it seen, if it is not already
        $make_message_seen = $con->prepare("UPDATE messages SET seen='yes' WHERE id='$msg_id'");
        $make_message_seen->execute();

        //date and time
        if(date("d-m-Y",strtotime($message['msg_time'])) !== $tempDate){
            $msg_date = date("d-m-Y",strtotime($message['msg_time']));
            $str .= "<div class='date_stamp' style='width:40%;margin:auto;text-align:center;background-color:#527ca2;'>$msg_date</div>";
            $tempDate = date("d-m-Y",strtotime($message['msg_time']));
        }
        $tempDate = date("d-m-Y",strtotime($message['msg_time']));
        $str .= "<div  class='msg_carry' style='width:100% !important;min-width:60vw !important;min-height:30px;margin-bottom:10px !important;'><div style='max-width:50% !important;";
        if($message['msg_from'] == $userLoggedIn){
            $str.= "float:right;";
            }
        //text colors   
        $str.= "'><span class='f_message";
        if($message['msg_from'] == $userLoggedIn){
            $str.= " right_floated";
            }
        $str.= "' id='$msg_id' style='background-color:";
        if($message['msg_from'] == $userLoggedIn){
            $str.= "lightblue;";            
        }else{
        $str.= "coral;";
        }
        $str.= "max-width:100% !important;padding:3px;";
        if($message['msg_from'] == $userLoggedIn){
        $str.= "float:right;";
        }
        //main message body
        $str.="border-radius:5px;color:#333333;position:relative;'>$msg_body<div class='f_msg_time' style='background-color:#dddddd;position:absolute;font-size:0.75em;";
        if($message['msg_from'] == $userLoggedIn){
            $str.= "right:0;";
            }
        $str.="border-radius:4px;'>$msg_time</div></span></div></div>";
    }
    echo $str;
}
//check for new messages
if(isset($_REQUEST['new_friend_messages']) == true && $_REQUEST['new_friend_messages'] == "yes"){
    if(isset($_REQUEST['friend_name']) == true && $_REQUEST['friend_name'] !== "none"){
        $friend_name = $_REQUEST['friend_name'];
        $last_date = $_REQUEST['last_date'];
        $get_unseen_messages = $con->prepare("SELECT * FROM messages WHERE ((msg_from='$userLoggedIn' AND msg_to='$friend_name') OR (msg_from='$friend_name' AND msg_to='$userLoggedIn')) AND seen='no' ORDER BY msg_time");
        $get_unseen_messages->execute();
        $unseen_messages = $get_unseen_messages->fetchAll(\PDO::FETCH_ASSOC);
        if($get_unseen_messages->rowCount() > 0){
            $str = "";
            $tempDate = $last_date;
        foreach($unseen_messages as $message){
            $msg_id = $message['id'];
            $msg_body = $message['msg_body'];
            $msg_time = date("h:i",strtotime($message['msg_time']));
    
            //make it seen, if it is not already
            $make_message_seen = $con->prepare("UPDATE messages SET seen='yes' WHERE id='$msg_id'");
            $make_message_seen->execute();
    
            //date and time
            if(date("d-m-Y",strtotime($message['msg_time'])) !== $tempDate){
                $msg_date = date("d-m-Y",strtotime($message['msg_time']));
                $str .= "<div class='date_stamp' style='width:40%;margin:auto;text-align:center;background-color:#527ca2;'>$msg_date</div>";
                $tempDate = date("d-m-Y",strtotime($message['msg_time']));
            }
            $tempDate = date("d-m-Y",strtotime($message['msg_time']));
            $str .= "<div class='msg_carry' style='width:100% !important;min-width:60vw !important;min-height:30px;'><div style='max-width:50% !important;";
            if($message['msg_from'] == $userLoggedIn){
                $str.= "float:right;";
                }
            //text colors   
            $str.= "'><span class='f_message' id='$msg_id' style='background-color:";
            if($message['msg_from'] == $userLoggedIn){
                $str.= "lightblue;";            
            }else{
            $str.= "coral;";
            }
            $str.= "max-width:100% !important;padding:3px;";
            if($message['msg_from'] == $userLoggedIn){
            $str.= "float:right;";
            }
            //main message body
            $str.="border-radius:5px;color:#333333;position:relative;'>$msg_body<div class='f_msg_time' style='background-color:#dddddd;position:absolute;font-size:0.75em;";
            if($message['msg_from'] == $userLoggedIn){
                $str.= "right:0;";
                }
            $str.="border-radius:4px;'>$msg_time</div></span></div></div><br>";
        }
        echo $str;
       }else{
           echo "no new message";
       }
    }
}
?>