<?php 
    require 'dbCon.php';

           //Get id of post
           if(isset($_REQUEST['id']) == true && isset($_REQUEST['cUser']) == true && isset($_REQUEST['val']) == true){
            $userLoggedIn = $_SESSION['username'];
            $post_id = $_REQUEST['id'];
            $value = $_REQUEST['val'];
            if($value == "like" || $value == "dislike"){
                $check_if_liked = $con->prepare("SELECT * FROM likes WHERE post_id ='$post_id' AND username='$userLoggedIn'");
                $check_if_liked->execute();
                $check_response = $check_if_liked->rowCount();
                if($check_response == 1 && $value == "dislike"){
                    //if the user has already liked the post then dislike it
                    $remove_like = $con->prepare("DELETE FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id'");
                    $remove_like->execute();

                    //Get new total likes
                    $get_total_likes = $con->prepare("SELECT * FROM likes WHERE post_id='$post_id'");
                    $get_total_likes->execute();
                    $total_likes = $get_total_likes->rowCount();

                    //update likes in the posts table
                    $update_likes = $con->prepare("UPDATE posts SET likes = '$total_likes' WHERE id='$post_id' AND deleted='no'");
                    $update_likes->execute();

                    echo "$total_likes,successfully removed";
                }elseif($check_response == 0 && $value == "like"){
                    //if the user has not liked the post already then like it
                     $add_like = $con->prepare("INSERT INTO likes VALUES('','$userLoggedIn','$post_id')");
                     $add_like->execute();

                     //Get new total likes
                     $get_total_likes = $con->prepare("SELECT * FROM likes WHERE post_id='$post_id'");
                     $get_total_likes->execute();
                     $total_likes = $get_total_likes->rowCount();
                    
                    //update likes in the posts table
                    $update_likes = $con->prepare("UPDATE posts SET likes='$total_likes' WHERE id='$post_id' AND deleted='no'");
                    $update_likes->execute();

                    //get the username of current post owner
                    $get_post_owner = $con->prepare("SELECT added_by FROM posts WHERE id='$post_id' AND deleted='no'");
                    $get_post_owner->execute();
                    $post_owner = $get_post_owner->fetch(\PDO::FETCH_ASSOC);
                    $post_owner = $post_owner['added_by'];

                    //get post_owner's profile pic
                    $poster_profile_pic = $con->prepare("SELECT profile_pic FROM users WHERE username='$post_owner'");
                    $poster_profile_pic->execute();
                    $post_owner_profile_pic = $poster_profile_pic->fetch(\PDO::FETCH_ASSOC);
                    $post_owner_profile_pic = $post_owner_profile_pic['profile_pic']; 


                    //send Notification
                    if($post_owner !== $userLoggedIn){
                    $sendNotification = $con->prepare("INSERT INTO notifications VALUES('','liked your post','$post_owner','$userLoggedIn','$post_owner_profile_pic','$post_id','no')");
                    $sendNotification->execute();
                    }
                     echo "$total_likes,successfully added";
                }
            }
        }else{
            header("Location: register.php");
        }
    ?>