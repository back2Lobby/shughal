<?php 

class Post{
    private $user_obj;
    private $con;
    
    public function __construct($con,$user){
        $this->con = $con;
        $this->user_obj = new User($con,$user);

    }
    public function submitPost($body,$user_to){
        $body = strip_tags($body);
        function escape_String_Remover($value){
            $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
            $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");
        
            return str_replace($search, $replace, $value);
        }
        $body = escape_String_Remover($body);
        $check_empty = preg_replace('/\s+/', '', $body); //deletes the combined extra spaces
        if($check_empty != ""){

            //Current Date & Time
            $date_added = date("Y-m-d H:i:s");
            //Get username
            $added_by = $this->user_obj->getUsername();

            //if user is on own profile, user_to is 'none'
            if($user_to == $added_by){
                $user_to == "none";
            }

            //Insert Post
            $stmt = $this->con->prepare("INSERT INTO posts VALUES('','$body','$added_by','$user_to','$date_added','no','no','0')");
            $stmt->execute();

            //upload image
            if(isset($_FILES['upload_image_post']) == true && $_FILES['upload_image_post']["name"] !== "" && $_FILES['upload_image_post']["type"] !== ""){
                $target_dir = "assets/images/uploads/";
                $returned_id = $this->con->lastInsertId();
                $file_extension = explode(".",basename($_FILES["upload_image_post"]["name"]));
                $file_extension = $file_extension[1];
                $target_file = $target_dir.$returned_id.".".$file_extension;
                // Check if image file is a actual image or fake image
                if(isset($_POST["post"])) {
                    $check = getimagesize($_FILES["upload_image_post"]["tmp_name"]);
                    if($check !== false) {
                        move_uploaded_file($_FILES["upload_image_post"]["tmp_name"], $target_file);

                        //insert info in database
                        $insert_photo_upload = $this->con->prepare("INSERT INTO post_photo_uploads VALUES('','$target_file',$returned_id)" );
                        $insert_photo_upload->execute();
                    }
                }
        
            }




            //Insert Notification

            //Update Post Count For User
            $num_posts = $this->user_obj->getNumPosts();
            $num_posts++;
            $update_post_nums = $this->con->prepare("UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");
            $update_post_nums->execute();
        }
    }
    public function submitMessage($body,$friend_name){
        $body = strip_tags($body);
        function escape_String_Remover_new($value)
        {
            $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
            $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");
        
            return str_replace($search, $replace, $value);
        }
        $body = escape_String_Remover_new($body);
        $check_empty = preg_replace('/\s+/', '', $body); //deletes the combined extra spaces
        if($check_empty != ""){
            //Get username
            $added_by = $this->user_obj->getUsername();

            //if user is on own profile, user_to is 'none'
            if($friend_name == $added_by){
                $friend_name == "none";
            }
            //Insert Post
            $stmt = $this->con->prepare("INSERT INTO messages VALUES('','$body','$added_by','$friend_name',CURRENT_TIMESTAMP,'no')");
            $stmt->execute();
        }
    }
    public function loadPostsFriends($data,$limit){
        if(isset($_SESSION['turnCount']) == false){
            $_SESSION['turnCount'] = 1;
        }
        $page = $data['page'];
        $page = intval($page);
        $userLoggedIn = $this->user_obj->getUsername();
        $totalRows = 0;
        //get friend array list
        $friend_array_query = $this->con->prepare("SELECT friend_array FROM users WHERE username = '$userLoggedIn'");
        $friend_array_query->execute();
        $friend_array_temp = $friend_array_query->fetch(\PDO::FETCH_ASSOC);
        $friends_array = preg_split("/\,/",$friend_array_temp['friend_array']);
        $friends_array = array_splice($friends_array,1,count($friends_array),"");
        if($friends_array[count($friends_array)-1] == ""){
            array_pop($friends_array);
        }

        foreach($friends_array as $friend_adder){

        //get the posts numbers by friends
        $posts_by_friends = $this->con->prepare("SELECT COUNT(*) FROM posts WHERE deleted='no' AND added_by='$friend_adder'");
        $posts_by_friends->execute();
        $temp_rows_num = $posts_by_friends->fetch();
        $totalRows = intval($temp_rows_num[0]) + $totalRows; 
        }
        //add the row by current user
        $posts_by_cUser = $this->con->prepare("SELECT COUNT(*) FROM posts WHERE deleted='no' AND added_by='$userLoggedIn'");
        $posts_by_cUser->execute();
        $temp_rows_num = $posts_by_cUser->fetch();
        $totalRows = intval($temp_rows_num[0]) + $totalRows;

        if($totalRows == 0){
        $totalRowsQuery = $this->con->prepare("SELECT * FROM posts WHERE deleted='no'");
        $totalRowsQuery->execute();
        $totalRows = $totalRowsQuery->rowCount();
        }
        $rowsLogic = $totalRows / 10;
        $rowsLogic = strval($rowsLogic);
        $totalPostsNum = preg_split("/\./",$rowsLogic);
        if(count($totalPostsNum)>1){
            $turns = $totalPostsNum[0] + 1;
        }else{
            $turns = $totalPostsNum[0];
        }
        if($page == 0){
            $_SESSION['turnCount'] = 1;
            if($totalRows > 9){
                $limit = 10;
            }
        }else{
            $page = strval($page)."0";
            $page = intval($page);
        }
        $friend_string = 'none';
        $friend_string = implode(",",$friends_array);
        $friend_string = preg_replace("/,/","','",$friend_string);
        $str = ''; //String to return
        $data_query = $this->con->prepare("SELECT * FROM posts WHERE added_by IN ('$friend_string') OR added_by='$userLoggedIn' ORDER BY id DESC LIMIT $page,$limit");
        $data_query->execute();
        if($data_query->rowCount() > 0){
        while($row = $data_query->fetch(\PDO::FETCH_ASSOC)){
            $id = $row['id'];
            $body = $row['body'];
            $added_by = $row['added_by'];
            $date_time = $row['date_added'];
            $get_comment_nums = $this->con->prepare("SELECT * FROM comments WHERE post_id='$id' ORDER BY id ASC");
            $get_comment_nums->execute();
            $comments_count = $get_comment_nums->rowCount();

            //Prepare user_to string so it can be included even if not posted to a user
            if($row['user_to'] == "none"){
                $user_to = "";
            }else{
                $user_to_obj = new User($this->con,$row['user_to']);
                $user_to_name = $user_to_obj->getFistAndLastName();
                $user_to = "to <a href='".$row['user_to']."'>".$user_to_name."</a>";
            }
            //Check if user who posted, has their account closed
            $added_by_obj = new User($this->con,$added_by);
            if($added_by_obj->is_closed()){
                continue;
            }
            //only showing posts of friends
            $user_logged_obj = new User($this->con,$userLoggedIn);
            if($user_logged_obj->isFriend($added_by)){

            $get_user_details = $this->con->prepare("SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
            $get_user_details->execute();
            $user_details = $get_user_details->fetch(\PDO::FETCH_ASSOC);

            $first_name = $user_details["first_name"];
            $last_name = $user_details["last_name"];
            $profile_pic = $user_details["profile_pic"];

                ?>

                <script>
                    console.log('test');
                    var post<?php echo $id;?> = document.querySelector('#toggleTest<?php echo $id;?>');
                    post<?php echo $id;?>.addEventListener('click', function (){
                    var element = document.getElementById('toggleComment<?php echo $id;?>');
                    if(element.style.display == "block"){
                        element.style.display = "none";
                    }else{
                        element.style.display = "block";
                    }
                });

                </script>

                <?php

            //getting likes system
            $get_total_likes = $this->con->prepare("SELECT likes FROM posts WHERE id='$id'");
            $get_total_likes->execute();
            $total_likes = $get_total_likes->fetch(\PDO::FETCH_ASSOC);
            $total_likes = $total_likes['likes'];
            $user_liked = false;
                 //check if user has already liked this post
                 $check_if_liked = $this->con->prepare("SELECT * FROM likes WHERE post_id ='$id' AND username='$userLoggedIn'");
                 $check_if_liked->execute();
                 $check_response = $check_if_liked->rowCount();
                 if($check_response == 1){
                     //already liked
                    $user_liked = true;
                 }else{
                     //if the user has not liked the post already
                    $user_liked = false;
                 }


            //Timeframe
            $date_time_now = date("Y-m-d H:i:s");
            $start_date = new DateTime($date_time); //Time of post
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
                    $time_message = $interval->m . " month ".$days;
                }else{
                    $time_message = $interval->m . " months ".$days;
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
            $body = preg_replace( "/#([^\s]+)/", "<span style='font-weight: bold;color:lightblue;'>#$1</span>", $body );
            $str .= "<div class='status_post' id='post_$id'>
                        <div class='post_profile'>
                            <img src='$profile_pic' width='50'>
                        </div>
                        <div class='post_content'>
                            <div class='posted_by' style='color:#ACACAC;'>
                                <a href='$added_by'>$first_name $last_name </a> $user_to &nbsp;&nbsp;<span style='font-size:12px;'>$time_message</span>
                            </div>
                            <div id='post_body' style='color:white;'>
                            $body
                            <br>
                        </div>
                    </div>
                    </div>
                    <div id='pic_with_posts'>
                    ";

                    //check if this post has a pic attached
                    $check_pic_with_post = $this->con->prepare("SELECT * FROM post_photo_uploads WHERE post_id=$id");
                    $check_pic_with_post->execute();
                    if($check_pic_with_post->rowCount() == 1){
                        $image_path = $check_pic_with_post->fetch(\PDO::FETCH_ASSOC);
                        $image_path = $image_path['pic_path'];
                        $str .= "<img src='$image_path' alt='Photo Can't Be Loaded' height='400px'> ";
                    }

             $str .="</div>
                    <div class='reactBox'>
                    <button type='button' class='commentToggleButton' style='z-index:999;min-width:50px;' id='toggleTest$id' onclick='javascript:(function(){if (document.readyState === ".'"complete"'." 
                        || document.readyState === ".'"loaded"'." 
                        || document.readyState === ".'"interactive"'.") {var post$id = document.getElementById(".'"toggleTest'."$id".'"'.");
                        var element = document.getElementById(".'"toggleComment'."$id".'"'.");
                        if(element.style.display == ".'"block"'."){
                            element.style.display = ".'"none"'.";
                        }else{
                            element.style.display = ".'"block"'.";
                        }
                        var iWin$id = document.querySelector(".'"#comment_iframe'.$id."\").contentWindow;
                        iWin$id.postMessage(".'"showcomment",'.'"*"'.");}
                    })();'><i class='far fa-comment'></i>
                    <span>$comments_count</span>
                    </button>
                    <button type='button' style='z-index:999;min-width:50px;' class='likeToggleButton' id='likePost$id' onClick='javascript:(async function(){var likeBtn$id = document.querySelector(".'"#likePost'."$id".'"'.");if(likeBtn$id.children[0].children[0].dataset.prefix == ".'"far"'."){
                        likeBtn$id.children[0].children[0].dataset.prefix = ".'"fas"'."; likeBtn$id.children[0].children[0].style.color = ".'"#e0245e"'.";
                        fetch(".'"like.php?val=like&&cUser='.$userLoggedIn.'&&id='.$id.'"'.").then(res => {res.text().then(text => {text = text.split(".'","'.");if(text[1] == ".'"successfully added"'."){document.querySelector(".'"#totalLikes'.$id.'"'.").innerHTML=".'"&nbsp;"'."+text[0];}else{likeBtn$id.children[0].children[0].dataset.prefix = ".'"far"'.";likeBtn$id.children[0].children[0].style.color = ".'"white"'.";}});});
                    }else{
                        likeBtn$id.children[0].children[0].dataset.prefix = ".'"far"'.";likeBtn$id.children[0].children[0].style.color = ".'"white"'.";
                        fetch(".'"like.php?val=dislike&&cUser='.$userLoggedIn.'&&id='.$id.'"'.").then(res => {res.text().then(text => {text = text.split(".'","'.");if(text[1] == ".'"successfully removed"'."){document.querySelector(".'"#totalLikes'.$id.'"'.").innerHTML= ".'"&nbsp;"'."+text[0];}else{                        likeBtn$id.children[0].children[0].dataset.prefix = ".'"fas"'."; likeBtn$id.children[0].children[0].style.color = ".'"#e0245e"'.";}})})}})();'><span>";
                    if($user_liked == false){
                        $str .= "<i class='far fa-heart' style='color:white;'></i>";
                    }else{
                        $str .= "<i class='fas fa-heart' style='color:#e0245e;'></i>";
                    }
                    $str .="</span><span id='totalLikes$id'> $total_likes<span></button>
                    </div>
                    <div class='post_comment' id='toggleComment$id'style='display:none;'>
                        <iframe src='comment_frame.php?post_id=$id' scrollin='no' name='comments_box$id' class='iframePack' id='comment_iframe$id' frameborder='0'></iframe>
                    </div>
                    <hr style='border-color:grey;'>";
           }
        }
        $_SESSION['turnCount'] = $_SESSION['turnCount'] + 1;
        if($_SESSION['turnCount'] <= $turns){
            if($page > 0){
                $page = strval($page);
                $page = preg_replace("/[0]/","",$page);
                $page = intval($page);
            }
            $str .= "<div id='show_more_posts_box' style='display:flex;justify-content:center;align-items:center;margin-bottom:10px;'><button type='button' class='btn btn-info' id='show_more_posts'>Show More Posts</button></div><input type='hidden' class='nextPage' value='".($page+1)."'>"
                    ."<input type='hidden' class='noMorePosts' value='false'>";
        }else{
            $str .= "<input type='hidden' class='noMorePosts' value='true'><p class='text-center text-white'>No More Posts To Show</p>";
        }
    }
        echo $str;
    }
    ////////////////////////////// Function for each profile's posts
    public function loadProfilePosts($data,$limit,$userLoggedIn){
        if(isset($_SESSION['turnCountPost']) == false){
            $_SESSION['turnCountPost'] = 1;
        }


        $page = $data['page'];
        $page = intval($page);
        if($page == 0){
            $_SESSION['turnCountPost'] = 1;
        }
        $profile_username = $this->user_obj->getUsername();
        $totalRows = 0;

        if($totalRows == 0){
        $totalRowsQuery = $this->con->prepare("SELECT * FROM posts WHERE deleted='no'");
        $totalRowsQuery->execute();
        $totalRows = $totalRowsQuery->rowCount();
        }
        $rowsLogic = $totalRows / 10;
        $rowsLogic = strval($rowsLogic);
        $totalPostsNum = preg_split("/\./",$rowsLogic);
        if(count($totalPostsNum)>1){
            $turns = $totalPostsNum[0] + 1;
        }else{
            $turns = $totalPostsNum[0];
        }
        if($page == 0){
            $_SESSION['turnCountPost'] = 1;
            if($totalRows > 9){
                $limit = 10;
            }
        }else{
            $page = strval($page)."0";
            $page = intval($page);
        }
        $str = ''; //String to return
        $data_query = $this->con->prepare("SELECT * FROM posts WHERE deleted='no' AND added_by='$profile_username' ORDER BY id DESC LIMIT $page,$limit");
        $data_query->execute();
        if($data_query->rowCount() > 0 && $_SESSION['turnCountPost'] <= $turns){

    
        $get_user_details = $this->con->prepare("SELECT first_name, last_name, profile_pic FROM users WHERE username='$profile_username'");
        $get_user_details->execute();
        $user_details = $get_user_details->fetch(\PDO::FETCH_ASSOC);

        $first_name = $user_details["first_name"];
        $last_name = $user_details["last_name"];
        $profile_pic = $user_details["profile_pic"];    


        while($row = $data_query->fetch(\PDO::FETCH_ASSOC)){
            $id = $row['id'];
            $body = $row['body'];
            $added_by = $row['added_by'];
            $date_time = $row['date_added'];
            $get_comment_nums = $this->con->prepare("SELECT * FROM comments WHERE post_id='$id' ORDER BY id ASC");
            $get_comment_nums->execute();
            $comments_count = $get_comment_nums->rowCount();

            //Prepare user_to string so it can be included even if not posted to a user
            if($row['user_to'] == "none"){
                $user_to = "";
            }else{
                $user_to_obj = new User($this->con,$row['user_to']);
                $user_to_name = $user_to_obj->getFistAndLastName();
                $user_to = "to <a href='".$row['user_to']."'>".$user_to_name."</a>";
            }
            //Check if user who posted, has their account closed
            $added_by_obj = new User($this->con,$added_by);
            if($added_by_obj->is_closed()){
                continue;
            }
                ?>

                <script>
                    var post<?php echo $id;?> = document.querySelector('#toggleTest<?php echo $id;?>');
                    post<?php echo $id;?>.addEventListener('click', function (){
                    var element = document.getElementById('toggleComment<?php echo $id;?>');
                    if(element.style.display == "block"){
                        element.style.display = "none";
                    }else{
                        element.style.display = "block";
                    }
                });

                </script>

                <?php

            //getting likes system
            $get_total_likes = $this->con->prepare("SELECT likes FROM posts WHERE id='$id'");
            $get_total_likes->execute();
            $total_likes = $get_total_likes->fetch(\PDO::FETCH_ASSOC);
            $total_likes = $total_likes['likes'];
            $user_liked = false;
                 //check if user has already liked this post
                 $check_if_liked = $this->con->prepare("SELECT * FROM likes WHERE post_id ='$id' AND username='$userLoggedIn'");
                 $check_if_liked->execute();
                 $check_response = $check_if_liked->rowCount();
                 if($check_response == 1){
                     //already liked
                    $user_liked = true;
                 }else{
                     //if the user has not liked the post already
                    $user_liked = false;
                 }


            //Timeframe
            $date_time_now = date("Y-m-d H:i:s");
            $start_date = new DateTime($date_time); //Time of post
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
            $body = preg_replace( "/#([^\s]+)/", "<span style='font-weight: bold;color:lightblue;'>#$1</span>", $body );
            $str .= "<div class='status_post' id='post_$id'>
                        <div class='post_profile'>
                            <img src='$profile_pic' width='50'>
                        </div>
                        <div class='post_content'>
                            <div class='posted_by' style='color:#ACACAC;'>
                                <a href='$added_by'>$first_name $last_name </a> $user_to &nbsp;&nbsp;<span style='font-size:12px;'>$time_message</span>
                            </div>
                            <div id='post_body' style='color:white;'>
                            $body
                            <br>
                        </div>
                    </div>
                    </div>
                    <div id='pic_with_posts'>
                    ";

                    //check if this post has a pic attached
                    $check_pic_with_post = $this->con->prepare("SELECT * FROM post_photo_uploads WHERE post_id=$id");
                    $check_pic_with_post->execute();
                    if($check_pic_with_post->rowCount() == 1){
                        $image_path = $check_pic_with_post->fetch(\PDO::FETCH_ASSOC);
                        $image_path = $image_path['pic_path'];
                        $str .= "<img src='$image_path' alt='Photo Can't Be Loaded' height='400px'> ";
                    }

             $str .="</div>
                    <div class='reactBox'>
                    <button type='button' class='commentToggleButton' style='z-index:999;min-width:50px;' id='toggleTest$id' onclick='javascript:(function(){if (document.readyState === ".'"complete"'." 
                        || document.readyState === ".'"loaded"'." 
                        || document.readyState === ".'"interactive"'.") {var post$id = document.getElementById(".'"toggleTest'."$id".'"'.");
                        var element = document.getElementById(".'"toggleComment'."$id".'"'.");
                        if(element.style.display == ".'"block"'."){
                            element.style.display = ".'"none"'.";
                        }else{
                            element.style.display = ".'"block"'.";
                        }
                        var iWin$id = document.querySelector(".'"#comment_iframe'.$id."\").contentWindow;
                        iWin$id.postMessage(".'"showcomment",'.'"*"'.");}
                    })();'><i class='far fa-comment'></i>
                    <span>$comments_count</span>
                    </button>
                    <button type='button' style='z-index:999;min-width:50px;' class='likeToggleButton' id='likePost$id' onClick='javascript:(async function(){var likeBtn$id = document.querySelector(".'"#likePost'."$id".'"'.");if(likeBtn$id.children[0].children[0].dataset.prefix == ".'"far"'."){
                        likeBtn$id.children[0].children[0].dataset.prefix = ".'"fas"'."; likeBtn$id.children[0].children[0].style.color = ".'"#e0245e"'.";
                        fetch(".'"like.php?val=like&&cUser='.$profile_username.'&&id='.$id.'"'.").then(res => {res.text().then(text => {text = text.split(".'","'.");if(text[1] == ".'"successfully added"'."){document.querySelector(".'"#totalLikes'.$id.'"'.").innerHTML=".'"&nbsp;"'."+text[0];}else{likeBtn$id.children[0].children[0].dataset.prefix = ".'"far"'.";likeBtn$id.children[0].children[0].style.color = ".'"white"'.";}});});
                    }else{
                        likeBtn$id.children[0].children[0].dataset.prefix = ".'"far"'.";likeBtn$id.children[0].children[0].style.color = ".'"white"'.";
                        fetch(".'"like.php?val=dislike&&cUser='.$profile_username.'&&id='.$id.'"'.").then(res => {res.text().then(text => {text = text.split(".'","'.");if(text[1] == ".'"successfully removed"'."){document.querySelector(".'"#totalLikes'.$id.'"'.").innerHTML= ".'"&nbsp;"'."+text[0];}else{                        likeBtn$id.children[0].children[0].dataset.prefix = ".'"fas"'."; likeBtn$id.children[0].children[0].style.color = ".'"#e0245e"'.";}})})}})();'><span>";
                    if($user_liked == false){
                        $str .= "<i class='far fa-heart' style='color:white;'></i>";
                    }else{
                        $str .= "<i class='fas fa-heart' style='color:#e0245e;'></i>";
                    }
                    $str .="</span><span id='totalLikes$id'> $total_likes<span></button>
                    </div>
                    <div class='post_comment' id='toggleComment$id'style='display:none;'>
                        <iframe src='comment_frame.php?post_id=$id' scrollin='no' name='comments_box$id' class='iframePack' id='comment_iframe$id' frameborder='0'></iframe>
                    </div>
                    <hr style='border-color:grey;'>";
        }
        $_SESSION['turnCountPost'] = $_SESSION['turnCountPost'] + 1;
        if($_SESSION['turnCountPost'] <= $turns){
            if($page > 0){
                $page = strval($page);
                $page = preg_replace("/[0]/","",$page);
                $page = intval($page);
            }
            $str .= "<input type='hidden' class='nextProfilePostsPage' value='".($page+1)."'>"
                    ."<input type='hidden' class='noMoreProfliePosts' value='false'>";
        }else{
            $str .= "<input type='hidden' class='noMoreProfilePosts' value='true'><p class='text-center text-white'>No More Posts To Show</p>";
        }
    }
        echo $str;

    }



    // Returns the posts liked by the user whose profile is opened
    public function loadLikedPosts($data,$limit,$userLoggedIn){
        if(isset($_SESSION['turnCountLikedPost']) == false){
            $_SESSION['turnCountLikedPost'] = 1;
        }


        $page = $data['page'];
        $page = intval($page);
        if($page == 0){
            $_SESSION['turnCountLikedPost'] = 1;
        }
        $profile_username = $this->user_obj->getUsername();
        $totalRows = 0;

        if($totalRows == 0){
        $totalRowsQuery = $this->con->prepare("SELECT * FROM posts WHERE deleted='no'");
        $totalRowsQuery->execute();
        $totalRows = $totalRowsQuery->rowCount();
        }
        $rowsLogic = $totalRows / 10;
        $rowsLogic = strval($rowsLogic);
        $totalPostsNum = preg_split("/\./",$rowsLogic);
        if(count($totalPostsNum)>1){
            $turns = $totalPostsNum[0] + 1;
        }else{
            $turns = $totalPostsNum[0];
        }
        if($page == 0){
            $_SESSION['turnCountLikedPost'] = 1;
            if($totalRows > 9){
                $limit = 10;
            }
        }else{
            $page = strval($page)."0";
            $page = intval($page);
        }

        //delete all rows in liked with 0 likes
        $get_all_posts_with_zero_likes = $this->con->prepare("SELECT * FROM posts WHERE likes='0'");
        $get_all_posts_with_zero_likes->execute();
        $posts_with_zero_likes = $get_all_posts_with_zero_likes->fetchAll(\PDO::FETCH_ASSOC);
        if($get_all_posts_with_zero_likes->rowCount() > 0){
            foreach($posts_with_zero_likes as $post){
                $delID = $post['likes'];
                $del_post_likes = $this->con->prepare("DELETE FROM likes WHERE post_id='$delID'");
                $del_post_likes->execute();
            }
        }


        //get all rows ids of posts liked by user
        $get_posts_ids = $this->con->prepare("SELECT post_id FROM likes WHERE username='$profile_username'");
        $get_posts_ids->execute();
        $posts_ids = $get_posts_ids->fetchAll(\PDO::FETCH_ASSOC);
        $ids_array = [];        
        foreach($posts_ids as $temp_post_id){
            array_push($ids_array,$temp_post_id['post_id']);
        }
        $ids_string = implode(",",$ids_array);
        $str = ''; //String to return
        if(count($ids_array) > 0){
        $data_query = $this->con->prepare("SELECT * FROM posts WHERE deleted='no' AND id IN ($ids_string) ORDER BY id DESC LIMIT $page,$limit");
        $data_query->execute();
        if($data_query->rowCount() > 0 && $_SESSION['turnCountLikedPost'] <= $turns){

    
        $get_user_details = $this->con->prepare("SELECT first_name, last_name, profile_pic FROM users WHERE username='$profile_username'");
        $get_user_details->execute();
        $user_details = $get_user_details->fetch(\PDO::FETCH_ASSOC);

        $first_name = $user_details["first_name"];
        $last_name = $user_details["last_name"];
        $profile_pic = $user_details["profile_pic"];    


        while($row = $data_query->fetch(\PDO::FETCH_ASSOC)){
            $id = $row['id'];
            $body = $row['body'];
            $added_by = $row['added_by'];
            $date_time = $row['date_added'];
            $get_comment_nums = $this->con->prepare("SELECT * FROM comments WHERE post_id='$id' ORDER BY id ASC");
            $get_comment_nums->execute();
            $comments_count = $get_comment_nums->rowCount();

            //Prepare user_to string so it can be included even if not posted to a user
            if($row['user_to'] == "none"){
                $user_to = "";
            }else{
                $user_to_obj = new User($this->con,$row['user_to']);
                $user_to_name = $user_to_obj->getFistAndLastName();
                $user_to = "to <a href='".$row['user_to']."'>".$user_to_name."</a>";
            }
            //Check if user who posted, has their account closed
            $added_by_obj = new User($this->con,$added_by);
            if($added_by_obj->is_closed()){
                continue;
            }
                ?>

                <script>
                    var post<?php echo $id;?> = document.querySelector('#toggleTest<?php echo $id;?>');
                    post<?php echo $id;?>.addEventListener('click', function (){
                    var element = document.getElementById('toggleComment<?php echo $id;?>');
                    if(element.style.display == "block"){
                        element.style.display = "none";
                    }else{
                        element.style.display = "block";
                    }
                });

                </script>

                <?php

            //getting likes system
            $get_total_likes = $this->con->prepare("SELECT likes FROM posts WHERE id='$id'");
            $get_total_likes->execute();
            $total_likes = $get_total_likes->fetch(\PDO::FETCH_ASSOC);
            $total_likes = $total_likes['likes'];
            $user_liked = false;

                 //check if user has already liked this post
                 $check_if_liked = $this->con->prepare("SELECT * FROM likes WHERE post_id ='$id' AND username='$userLoggedIn'");
                 $check_if_liked->execute();
                 $check_response = $check_if_liked->rowCount();

                 if($check_response == 1){
                     //already liked
                    $user_liked = true;
                 }else{
                     //if the user has not liked the post already
                    $user_liked = false;
                 }


            //Timeframe
            $date_time_now = date("Y-m-d H:i:s");
            $start_date = new DateTime($date_time); //Time of post
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
                    $time_message = $interval->m . " month ".$days;
                }else{
                    $time_message = $interval->m . " months ".$days;
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
            $body = preg_replace( "/#([^\s]+)/", "<span style='font-weight: bold;color:lightblue;'>#$1</span>", $body );
            $str .= "<div class='status_post' id='post_$id'>
                        <div class='post_profile'>
                            <img src='$profile_pic' width='50'>
                        </div>
                        <div class='post_content'>
                            <div class='posted_by' style='color:#ACACAC;'>
                                <a href='$added_by'>$first_name $last_name </a> $user_to &nbsp;&nbsp;<span style='font-size:12px;'>$time_message</span>
                            </div>
                            <div id='post_body' style='color:white;'>
                            $body
                            <br>
                        </div>
                    </div>
                    </div>
                    <div id='pic_with_posts'>
                    ";

                    //check if this post has a pic attached
                    $check_pic_with_post = $this->con->prepare("SELECT * FROM post_photo_uploads WHERE post_id=$id");
                    $check_pic_with_post->execute();
                    if($check_pic_with_post->rowCount() == 1){
                        $image_path = $check_pic_with_post->fetch(\PDO::FETCH_ASSOC);
                        $image_path = $image_path['pic_path'];
                        $str .= "<img src='$image_path' alt='Photo Can't Be Loaded' height='400px'> ";
                    }

             $str .="</div>
                    <div class='reactBox'>
                    <button type='button' class='commentToggleButton' style='z-index:999;min-width:50px;' id='toggleTest$id' onclick='javascript:(function(){if (document.readyState === ".'"complete"'." 
                        || document.readyState === ".'"loaded"'." 
                        || document.readyState === ".'"interactive"'.") {var post$id = document.getElementById(".'"toggleTest'."$id".'"'.");
                        var element = document.getElementById(".'"toggleComment'."$id".'"'.");
                        if(element.style.display == ".'"block"'."){
                            element.style.display = ".'"none"'.";
                        }else{
                            element.style.display = ".'"block"'.";
                        }
                        var iWin$id = document.querySelector(".'"#comment_iframe'.$id."\").contentWindow;
                        iWin$id.postMessage(".'"showcomment",'.'"*"'.");}
                    })();'><i class='far fa-comment'></i>
                    <span>$comments_count</span>
                    </button>
                    <button type='button' style='z-index:999;min-width:50px;' class='likeToggleButton' id='likedByProfile$id' onClick='javascript:(async function(){var likedProfileBtn$id = document.querySelector(".'"#likedByProfile'."$id".'"'.");if(likedProfileBtn$id.children[0].children[0].dataset.prefix == ".'"far"'."){
                        likedProfileBtn$id.children[0].children[0].dataset.prefix = ".'"fas"'."; likedProfileBtn$id.children[0].children[0].style.color = ".'"#e0245e"'.";
                        fetch(".'"like.php?val=like&&cUser='.$profile_username.'&&id='.$id.'"'.").then(res => {res.text().then(text => {text = text.split(".'","'.");if(text[1] == ".'"successfully added"'."){document.querySelector(".'"#totalLikes'.$id.'"'.").innerHTML=".'"&nbsp;"'."+text[0];}else{likedProfileBtn$id.children[0].children[0].dataset.prefix = ".'"far"'.";likedProfileBtn$id.children[0].children[0].style.color = ".'"white"'.";}});});
                    }else{
                        likedProfileBtn$id.children[0].children[0].dataset.prefix = ".'"far"'.";likedProfileBtn$id.children[0].children[0].style.color = ".'"white"'.";
                        fetch(".'"like.php?val=dislike&&cUser='.$profile_username.'&&id='.$id.'"'.").then(res => {res.text().then(text => {text = text.split(".'","'.");if(text[1] == ".'"successfully removed"'."){document.querySelector(".'"#totalLikes'.$id.'"'.").innerHTML= ".'"&nbsp;"'."+text[0];}else{                        likedProfileBtn$id.children[0].children[0].dataset.prefix = ".'"fas"'."; likedProfileBtn$id.children[0].children[0].style.color = ".'"#e0245e"'.";}})})}})();'><span>";
                    if($user_liked == false){
                        $str .= "<i class='far fa-heart' style='color:white;'></i>";
                    }else{
                        $str .= "<i class='fas fa-heart' style='color:#e0245e;'></i>";
                    }
                    $str .="</span><span id='totalLikes$id'> $total_likes<span></button>
                    </div>
                    <div class='post_comment' id='toggleComment$id'style='display:none;'>
                        <iframe src='comment_frame.php?post_id=$id' scrollin='no' name='comments_box$id' class='iframePack' id='comment_iframe$id' frameborder='0'></iframe>
                    </div>
                    <hr style='border-color:grey;'>";
        }
        $_SESSION['turnCountLikedPost'] = $_SESSION['turnCountLikedPost'] + 1;
        if($_SESSION['turnCountLikedPost'] <= $turns){
            if($page > 0){
                $page = strval($page);
                $page = preg_replace("/[0]/","",$page);
                $page = intval($page);
            }
            $str .= "<input type='hidden' class='nextProfilePostsPage' value='".($page+1)."'>"
                    ."<input type='hidden' class='noMoreProfliePosts' value='false'>";
        }else{
            $str .= "<input type='hidden' class='noMoreProfilePosts' value='true'><p class='text-center text-white'>No More Posts To Show</p>";
        }
    }
   }
        echo $str;

    }


}
?>