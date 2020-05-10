<?php 
include('../../dbCon.php');
include('../classes/User.php');

if(isset($_SESSION['username'])){
    $userLoggedIn = $_SESSION['username'];
    $user_obj = new User($con,$userLoggedIn);
}else{
    header("Location: register.php");
}


if(isset($_REQUEST['call'])){
    //this array contains the hashtags and their number of likes
    $hashtags_array = [];
   //fetch all the posts with #
   $fetch_posts_db = $con->prepare("SELECT * FROM posts WHERE body LIKE '%\#%' ORDER BY likes DESC"); 
   $fetch_posts_db->execute();
   $fetch_posts = $fetch_posts_db->fetchAll(\PDO::FETCH_ASSOC);
   foreach($fetch_posts as $post){
        //extract and push the hashtag from this post to hashtag array
        $body = $post['body'];
        $likes = $post['likes'];
        preg_match_all("/#(\\w+)/",$body,$matches);
        $hashtags = $matches[0];
        foreach($hashtags as $hashtag){
            //check if this hashtag is already in the hashtags array
            $validate_flag = false;
            if(count($hashtags_array) >= 0){
                foreach($hashtags_array as &$el){
                    if($el[0] == $hashtag){
                        $templikes = intval($el[1]);
                        $templikes = intval($likes) + $templikes;
                        $el[1] = strval($templikes);
                        $validate_flag = true;
                    }
                }
           }
           //if validate_flag == true it means the current hashtag is not included in the array
           //so we will add it
           if($validate_flag == false || count($hashtags_array) == 0){
           array_push($hashtags_array,array($hashtag,$likes));
           }
        }
   }
   function like_compare($element1, $element2) { 
    $like1 = intval($element1[1]); 
    $like2 = intval($element2[1]); 
    return $like1 - $like2; 
    }  
   usort($hashtags_array, 'like_compare'); 
   $hashtags_array = array_reverse($hashtags_array);
   $str = '';
   foreach($hashtags_array as $hashtag){
       $str .= "<span class='trend_data' style='color:white;font-size:1.15em;'>$hashtag[0]</span><br>";
   }
   echo $str;
}




?>