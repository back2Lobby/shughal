<?php 
    require 'dbCon.php';
    include('includes/classes/User.php');
    include('includes/classes/Post.php');

        if(isset($_SESSION['username'])){
            $userLoggedIn = $_SESSION['username'];
            $user_details_query = $con->prepare("SELECT * FROM users WHERE username='$userLoggedIn'");
            $user_details_query->execute();
            $user = $user_details_query->fetch(\PDO::FETCH_ASSOC);
            //profile pic for sending notification
            $user_profile_pic = $user['profile_pic'];
        }else{
            header("Location: register.php");
        }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style='margin:0;'>

 

    <?php 
    
    if(isset($_GET['post_id'])){
        $post_id = $_GET['post_id'];
    }

    $user_query = $con->prepare("SELECT added_by, user_to FROM posts WHERE id='$post_id'");
    $user_query->execute();
    $row = $user_query->fetch(\PDO::FETCH_ASSOC);

    $posted_to = $row['added_by'];

    if(isset($_POST['postComment' . $post_id])){
        $post_body = $_POST['post_body'];
        function escape_String_Remover($value)
        {
            $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
            $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");
        
            return str_replace($search, $replace, $value);
        }
        $post_body = escape_String_Remover($post_body);
        $date_time_now = date('Y-m-d H:i:s');
        //comments posting
        $insert_post = $con->prepare("INSERT INTO comments VALUES('','$post_body','$userLoggedIn','$posted_to','$date_time_now','no','$post_id')");
        $insert_post->execute();
        //send Notification
        if($posted_to !== $userLoggedIn){
        $sendNotification = $con->prepare("INSERT INTO notifications VALUES('','commented on your post','$posted_to','$userLoggedIn','$user_profile_pic','$post_id','no')");
        $sendNotification->execute();
        }
    }


    ?>

    <form action="comment_frame.php?post_id=<?php echo $post_id;?>" id="comment_form" name="postComment <?php echo $post_id;?>" method="POST">
        <textarea name="post_body" maxlength='280' id='textarea_for_comment' class='comment_body_area' onkeyup='keyAction(this)'></textarea>
        <input type="submit" class='comment_post_button' onClick="bodyLength()" name="postComment<?php echo $post_id;?>" value="Reply">
        <meter class="max_length" min='0' high='200' max='280' value='0'></meter>
    </form>
    <!-- Load Comments -->
    <?php 
    $get_comment = $con->prepare("SELECT * FROM comments WHERE post_id='$post_id' ORDER BY id ASC");
    $get_comment->execute();
    $comments_count = $get_comment->rowCount();

    if($comments_count !== 0){

        while($comment = $get_comment->fetch(\PDO::FETCH_ASSOC)){
            $comment_body = $comment['post_body'];
            $posted_to = $comment['posted_to'];
            $comment_posted_by = $comment['posted_by'];
            $date_added = $comment['date_added'];
            $removed = $comment['removed'];

            
            //Timeframe
            $date_time_now = date("Y-m-d H:i:s");
            $start_date = new DateTime($date_added); //Time of post
            $end_date = new DateTime($date_time_now); //current time

            $interval = $start_date->diff($end_date); //diff b/w dates

            if($interval->y >= 1){
                if($interval == 1){
                    $time_message = $interval->y . " year ago"; //1 year ago
                }else{
                    $time_message = $interval->y . " years ago"; //1+ years ago
                }
            }else if($interval-> m >= 1){
                if($interval-> d == 0){
                    $days = " ago";
                }else if($interval->d == 1){
                    $days = $interval->d . " day ago";
                }else{
                    $days = $interval->d . " days ago";
                }

                if($interval->m == 1){
                    $time_message = $interval->m . " month".$days;
                }else{
                    $time_message = $interval->m . " months".$days;
                }
            }else if($interval->d >= 1){
                if($interval->d == 1){
                    $time_message = "Yesterday";
                }else{
                    $time_message = $interval->d . " days ago";
                }
            }else if($interval->h >= 1){
                if($interval->h == 1){
                    $time_message = $interval->h . " hour ago";
                }else{
                    $time_message = $interval->h . " hours ago";
                }
            }else if($interval->i >= 1){
                if($interval->i == 1){
                    $time_message = $interval->i . " minute ago";
                }else{
                    $time_message = $interval->i . " minutes ago";
                }
            }else{
                if($interval->s < 30){
                    $time_message = "Just Now";
                }else{
                    $time_message = $interval->s . " seconds ago";
                }
            }

            $user_obj = new User($con, $comment_posted_by);

            ?>
            
            <div class="comment_section" style="margin-top:10px;">
                <a class="cProfile" href="<?php if(isset($comment_posted_by))echo $comment_posted_by;?>" target="_parent"><img src="<?php echo $user_obj->getProfilePic();?>" title="<?php echo $comment_posted_by;?>" alt="" style="border-radius:50%;" height="40"></a>
                <a class="cBody" href="<?php if(isset($comment_posted_by))echo $comment_posted_by;?>" target="_parent"><b><?php echo $user_obj->getFistAndLastName();?></b><span class='commentTime' style='font-size:12px;'><?php echo $time_message?></span><span class='mainComment'><?php echo $comment_body;?></span></a>
            </div>
            <hr style="border-color:#333;opacity:0.3;">
            <?php
        }

    }else{
        echo "<center style='color:white;'><br>No Comments To Show<br></center>";
    }

    ?>
 

    <script>
        window.onload = function(){
            var mIndicator = false;
            window.addEventListener('message',(e)=>{
                if(e.data == "showcomment"){
                    window.parent.postMessage({winHeight:document.body.offsetHeight,winName: window.name},"*");
                    mIndicator = true;
                }
                if(mIndicator == false){
                    setTimeout(function(){
                        window.parent.postMessage({winHeight:document.body.offsetHeight,winName: window.name},"*");
                    },1000);
                }
            });
        }

        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
        //comment text length meter 
        function keyAction(e){
            e.parentNode.lastElementChild.value = e.value.length;
        };
        //makes the comment section height dynamic when a new comment is added
        function bodyLength(){
            window.parent.postMessage({winHeight:document.body.offsetHeight,winName: window.name},"*");
        }


    </script>
</body>
</html>