<?php
include('dbCon.php');
include "includes/header.php";
include "includes/classes/User.php";
include "includes/classes/Post.php";


if(isset($_POST['post'])){
    $post = new Post($con,$userLoggedIn);
    $post->submitPost($_POST['post_text'],'none');

}
?>
<script>
function throttle(fn, wait) {
    if(localStorage.getItem("timeThrottle") === null){
        localStorage.setItem("timeThrottle", `${Date.now()}`);
    }
    if (Date.now() >= parseInt(localStorage.getItem("timeThrottle"))+wait) {
      fn();
      localStorage.setItem("timeThrottle", `${Date.now()}`);
    }
}
</script>
<!-- main section start -->
<main class="mainCon" style="flex-grow:1;min-height:90.3vh;width:100%;z-index:1;">
    <div class="mainWrapper container-fluid" style="min-height:90.3vh;width:100vw;margin:0;padding:0;">
        <div class="row" style="min-height:90.36vh;margin:0">
        <!-- Me And Online Friends Section Start -->
            <div class="meAndOnlineFriends col-md-2">

                <div class="mydemoBio">
                    <div class="myProfileImage" style="background-image:url('<?php if (isset($result[0]['profile_pic'])) {echo $result[0]['profile_pic'];}?>')">
                    </div>
                    <div class="myStats">
                    <a href="<?php echo $userLoggedIn;?>" style="text-decoration:none;color:white;">
                        <span class="cUser">
                            <?php if (isset($result[0]['first_name']) == true && isset($result[0]['last_name']) == true) {echo $result[0]['first_name']." ".$result[0]['last_name'];}?>
                        </span>
                        </a>
                        <br>
                        <span class="userData">
                            Posts: <?php if(isset($result[0]['num_posts'])){echo $result[0]['num_posts'];} ?>
                            <br>

                            Friends: <?php $user_friend_counter = new User($con,$userLoggedIn);echo $user_friend_counter->totalFriends(); ?>
                        </span>
                        <hr>
                        <div class="friends_status">
                            <span style='text-align:center;'>Friends</span>
                            <hr>
                        </div>
                        <script>
                        function friends_status_func(){
                            fetch("includes/handlers/friends_status.php?call=get_friends_status").then(res=>{
                                res.text().then(data =>{
                                    document.querySelector('.friends_status').innerHTML = "<span style='text-align:center;'>Friends</span><hr>" + data;
                                });
                            });
                        }
                        friends_status_func();
                        setInterval(()=>{
                            friends_status_func();
                            },15000);
                        </script>
                    </div>
                </div>
            </div>
        <!-- Me And Online Friends Section End -->

        <!-- Main timeLine Start -->
        <div class="mainTimeline col-md-8">
            <?php 


            if(isset($_GET['profile_username'])){
                //reset the variable to make it reusable
                if(isset($_SESSION['turnCount'])){
                    $_SESSION['turnCount'] == 1;
                }
                if(isset($_GET['profile_username'])){
                    $profile_username = $_GET['profile_username'];
                    $profile_user_query = $con->prepare("SELECT * FROM users WHERE username = '$profile_username'");
                    $profile_user_query->execute();
                    $check = $profile_user_query->rowCount();
                    $profile_user_array = $profile_user_query->fetch(\PDO::FETCH_ASSOC);
                    ?>
                    <!-- profile page -->

                        <!-- go back to home button -->
                        <div class="backToIndex" onClick='javasript:(function(){var getUrl = window.location;
                        var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split("/")[1];location.href=baseUrl;if(localStorage.getItem("timeThrottle") !== null){localStorage.removeItem("timeThrottle");}})()'>
                        <i class="fas fa-arrow-left"></i>
                        &nbsp;&nbsp;Go Back
                        <hr>
                        </div>
                        <?php if($check !== 0){ ?>
                    <div id="profile_page">
                        <!-- cover and profile pic -->
                        <div class="profile_images">
                                <!-- profile's cover photo -->
                            <div class="cover_photo" style='background-image:url("assets/images/covers/default.png");'>
                                 <!-- profile's photo -->
                                <div class="profile_pic_user" style='background-image:url("<?php echo $profile_user_array['profile_pic']?>")'>
                                </div>
                                <!-- add/remove friend process -->
                                <div class='friend_option btn'><?php $user_friend_check = new User($con,$userLoggedIn);
                                if($userLoggedIn !== $profile_username){
                                    if($user_friend_check->check_friend_request($profile_username) == false){ 
                                        if($user_friend_check->isFriend($profile_username)){
                                            echo 'Friends <i class='.'"fas fa-user-check"'.'></i>';
                                        }else{
                                            echo 'Add Friend';
                                        }
                                    }else{
                                        if($user_friend_check->isFriend($profile_username)){
                                            echo 'Friends <i class='.'"fas fa-user-check"'.'></i>';
                                        }else{
                                            echo 'Request Sent <i class='.'"fas fa-user-check"'.'></i>';
                                        }
                                    }
                                }else{
                                    echo "Edit Profile";
                                }
                                ?></div>
                                <script>

                                //menu underline animation
                                document.querySelector('.myNavItem0').classList.add('active');
                                document.querySelector('.myNavItem1').classList.remove('active');
                                document.querySelector('.myNavItem2').classList.remove('active');
                                document.querySelector('.myNavItem4').classList.remove('active');
                                document.querySelector('.myNavItem5').classList.remove('active');

                                    var friendBtn = document.querySelector('.friend_option');
                                    if(friendBtn.innerHTML == "Edit Profile"){
                                        friendBtn.style.visibility = 'hidden';
                                    }
                                    friendBtn.addEventListener('click', ()=>{                                    
                                            fetch("includes/handlers/friends.php?friendBtn=yes&&target=<?php echo $profile_username; ?>").then(res=>res.text().then(data =>{
                                                if("already friends" == data){
                                                    friendBtn.style.visibility = 'visible';
                                                    friendBtn.innerHTML = 'Add Friend';
                                                }else if("friend request added" == data){
                                                    friendBtn.style.visibility = 'visible';
                                                    friendBtn.innerHTML = "Request Sent <i class='fas fa-user-check'></i>";
                                                }else if("own profile" == data){
                                                    friendBtn.style.visibility = 'hidden';
                                                }
                                            }));
                                    });
                                </script>
                            </div>
                        </div>
                        <!-- profile bio -->
                        <div class="profile_bio">
                            <div class="profile_username">
                                <h3 class="profile_user_fullname">
                                    <?php echo $profile_user_array['first_name']." ".$profile_user_array['last_name']; ?>
                                </h3>
                                <h5 class="profile_username_main">
                                    @<?php echo $profile_user_array['username']; ?>
                                </h5>
                            </div>
                            <div class="user_join_date">
                            <i class="far fa-calendar-alt"></i>
                            &nbsp;Joined <?php echo date('F, Y',strtotime($profile_user_array['signup_date'])); ?>
                            </div>
                            <div class="posts_and_friends">
                                <div class="p_f_stats" style='max-width:400px;'>
                                <b><?php echo $profile_user_array['num_posts']; ?> </b> Posts&nbsp;&nbsp;<b><?php $friends_num = 0;$temp_array = explode(",",$profile_user_array['friend_array']); foreach ($temp_array as $friend){if($friend !== "" && $friend !== "," && $friend !== " "){$friends_num++;}}echo $friends_num;?></b> Friends
                                </div>
                            </div>
                            <hr>
                        </div>
                            <!-- profile user's timeline -->
                            <ul class="profile_user_tabs">
                                <li class="posts_tab" onClick="postTab()"><b>Posts</b></li>
                                <li class="likes_tab" onClick="likeTab()"><b>Likes</b></li>
                            </ul>
                            <hr>

                            <script>
                                function postTab(){
                                if(document.querySelector('.posts_tab') !== null){
                                        var posts_tab = document.querySelector('.posts_tab');
                                        var likes_tab = document.querySelector('.likes_tab');
                                        var profile_posts = document.querySelector('.profile_posts');
                                        var profile_likes = document.querySelector('.profile_likes');    
                                            posts_tab.style.backgroundColor = "rgba(82, 124, 162, 0.3)";
                                            posts_tab.style.color = "white";
                                            likes_tab.style.backgroundColor = "transparent";
                                            likes_tab.style.color = "grey";
                                            profile_likes.style.display = "none";
                                            profile_posts.style.display = "block";
                                            document.querySelector('#profile_iframe_id').contentWindow.postMessage({isscrolled:"no",rHeight:"true"},"*");
                                            }
                                }
                                function likeTab(){
                                if(document.querySelector('.likes_tab') !== null){
                                        var posts_tab = document.querySelector('.posts_tab');
                                        var likes_tab = document.querySelector('.likes_tab');
                                        var profile_posts = document.querySelector('.profile_posts');
                                        var profile_likes = document.querySelector('.profile_likes');    
                                            likes_tab.style.backgroundColor = "rgba(82, 124, 162, 0.3)";
                                            likes_tab.style.color = "white";
                                            posts_tab.style.backgroundColor = "transparent";
                                            posts_tab.style.color = "grey";
                                            profile_likes.style.display = "block";
                                            profile_posts.style.display = "none";
                                    }
                                }
                            </script>

                            <div class='profile_timeline_main'>
                                <iframe name='profile_posts_iframe' id="profile_iframe_id" class='profile_posts' src="profilesPosts.php?username=<?php echo $profile_user_array['username']?>" frameborder="0" scrolling='no' style='width:100%;'></iframe>
                                <!-- profile's user's liked posts -->
                                <div class="profile_likes">
                                    <img src="assets/images/icons/loading.gif" style="margin:auto;" class='loadingLikedPosts' id="loadingLikedPosts" alt="">
                                </div>
                            <script>
                                var profileIframe = document.querySelector('.profile_posts');

                                var postsLoader = setInterval(function(){
                                    if(profileIframe.contentWindow.document.body !== null){
                                        profileIframeFunc();
                                    }
                                },100);
                                function profileIframeFunc(){
                                var profileIframe = document.querySelector('.profile_posts');
                                setTimeout(()=>{
                                profileIframe.height = profileIframe.contentWindow.document.body.offsetHeight;
                                clearInterval(postsLoader);
                                },500);
                                }
                            </script>
                        <script id="likedScript" type="text/javascript">

                            document.querySelector('.likes_tab').addEventListener('click',()=>{

                            var page = "";
                            var noMorePosts = "";
                            var userLoggedIn = "<?php echo $profile_user_array['username']; ?>";
                            (function loadAjaxProfilePosts(){
                                document.querySelector("#loadingLikedPosts").style.display = "block";

                                //Original ajax request for loading first posts
                                var xhttp = new XMLHttpRequest();

                                xhttp.onreadystatechange = function(){
                                    if(this.readyState == 4 && this.status == 200){
                                        document.querySelector("#loadingLikedPosts").style.display = "none";
                                        document.querySelector(".profile_likes").innerHTML = this.responseText;
                                        page = document.querySelector(".nextProfilePostsPage").value;
                                        page = parseInt(page);
                                    }
                                }


                                xhttp.open("POST","includes/handlers/fetch_liked_posts.php?page=0&profile_username=<?php echo $profile_user_array['username'];?>",true);
                                xhttp.setRequestHeader("Cache-Control","no-store");
                                xhttp.send();
                            })();
                            //loading remaining posts on scroll
                            window.onscroll = function(ev) {
                                setTimeout(()=>{
                                if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                                    var tempVar = document.querySelector(".profile_likes").lastElementChild.className;
                                    if(toString(tempVar) == toString("noMoreProfilePosts")){
                                        var noMorePosts = document.querySelector(".profile_likes").lastElementChild.value;
                                    }
                                    if(noMorePosts == "false"){
                                        if(document.querySelector("#loadinglikedPosts") == null){
                                        document.querySelector(".profile_likes").innerHTML += "<img src='assets/images/icons/loading.gif' id='loadingLikedPosts' class='loadingLikedPosts' style='margin-left:33vw;' alt=''>"; 
                                        }
                                        //Original ajax request for loading first posts
                                        var xhttp = new XMLHttpRequest();

                                        xhttp.onreadystatechange = function(){
                                            if(this.readyState == 4 && this.status == 200){
                                                if(document.querySelector(".nextProfilePostsPage") !== null){
                                                    var tempArr = document.querySelectorAll(".profile_likes > input");
                                                    var noMorePosts = tempArr[1];
                                                    var profileNextPage = tempArr[0];
                                                    if(noMorePosts.value == "false"){
                                                document.querySelector(".profile_likes").removeChild(profileNextPage);
                                                document.querySelector(".profile_likes").removeChild(noMorePosts);
                                                        if(document.querySelector("#loadingLikedPosts") !== null){
                                                var profileLikeVar = document.querySelector(".profile_likes");
                                                var profileLikeNodes = document.querySelectorAll(".profile_likes > img");
                                                profileLikeNodes.forEach(function(el){
                                                    profileLikeVar.removeChild(el);                                                    
                                                })
                                                        }
                                                document.querySelector(".profile_likes").innerHTML += this.responseText;
                                                    }
                                                }
                                            }
                                        }
                                        page = document.querySelector(".nextProfilePostsPage").value;
                                        page = parseInt(page);
                                        xhttp.open("POST","includes/handlers/fetch_liked_posts.php?page=" + page + "&profile_username=<?php echo $profile_user_array['username'];?>",true);
                                        xhttp.setRequestHeader("Cache-Control","no-store");
                                        xhttp.send();
                                    }
                                }
                                return false;
                            },1000);
                            };
                        });
                    </script>


                            </div>
                    </div>

                    <?php
                        }else{
                            ?>
                            
                            <div style='text-align:center;color:white;margin-top:100px;font-size:1.4em;'>
                                No user found with this name.
                            </div>
                            
                            <?php
                        }    
                }
            }else
            {
                ?>
                
                <?php
                // Main Timeline 
            echo '
                <form class="post_form" action="index.php" method="POST" enctype="multipart/form-data">
                    <button type="button" id="upload_image_post_btn"><i class="fas fa-image" title="Upload Image"></i></button>
                    <textarea id="post_text" name="post_text" placeholder="Got something to say?"></textarea>
                    <input type="file" name="upload_image_post" id="upload_image_post" accept="image/*">
                    <div id="post_image_uploaded"></div>
                    <input type="submit" name="post" id="post_button" value="Post">
                </form>
                <script>
                
                document.querySelector('.'"#upload_image_post_btn"'.').addEventListener('.'"click"'.',()=>{
                document.querySelector('."'#upload_image_post'".').click();
                var mytempInterval = setInterval(()=>{
                    if(document.querySelector('.'"#upload_image_post"'.').value !== ""){
                        document.querySelector('.'"#upload_image_post_btn"'.').style.color = '.'"lightblue"'.';
                        clearInterval(mytempInterval);
                    }
                },600);
                setTimeout(()=>{
                    clearInterval(mytempInterval);
                   },18000);
                });

                function readURL(input) {
                    if (input.files && input.files[0]) {
                      var reader = new FileReader();
                      
                      reader.onload = function(e) {
                          debugger;
                        document.querySelector('.'"#post_image_uploaded"'.').style.display = '.'"block"'.';
                        document.querySelector('.'"#post_image_uploaded"'.').style.backgroundImage = '.'`'.'url('.'${e.target.result}'.')'.'`'.';
                      }
                      
                      reader.readAsDataURL(input.files[0]); // convert to base64 string
                    }
                  }
                  
                  $("#upload_image_post").change(function() {
                    readURL(this);
                  });

                </script>
                <hr>
                <!-- all home posts loads here -->
                <div class="timeline_body">
                <img src="assets/images/icons/loading.gif" style="margin:auto;" id="loading" alt="">
                </div>
                <script>
                    var page = "";
                    var noMorePosts = "";
                    var userLoggedIn = "'.$userLoggedIn.'";
                    document.addEventListener("DOMContentLoaded",loadAjaxPosts);
                    function loadAjaxPosts(){
                        if(document.querySelector('.'"#loading"'.') !== null)
                        document.querySelector('.'"#loading"'.').style.display = "block";

                        //Original ajax request for loading first posts
                        var xhttp = new XMLHttpRequest();

                        xhttp.onreadystatechange = function(){
                            if(this.readyState == 4 && this.status == 200){
                                if(document.querySelector('.'"#loading"'.') !== null)
                                document.querySelector('.'"#loading"'.').parentNode.removeChild(document.querySelector('.'"#loading"'.'));
                                document.querySelector('.'".timeline_body"'.').innerHTML = this.responseText;
                                page = document.querySelector('.'".nextPage"'.').value;
                                page = parseInt(page);
                                noMorePosts = document.querySelector('.'".noMorePosts"'.').value;
                            }
                        }


                        xhttp.open("POST","includes/handlers/ajax_load_posts.php?page=0&userLoggedIn=" + userLoggedIn,true);
                        xhttp.setRequestHeader("Cache-Control","no-store");
                        xhttp.send();
                    }
                    loadAjaxPosts();
                    //loading remaining posts on button click
                    var tempInterval = setInterval(()=>{
                        

                    if(document.querySelector('.'"#show_more_posts"'.') !== null){
                        clearInterval(tempInterval); 

                        document.querySelector('.'"#show_more_posts"'.').addEventListener('.'"click"'.',()=>{
                        function show_more_posts_func(){
                        document.querySelector('.'"#show_more_posts"'.').parentNode.removeChild(document.querySelector('.'"#show_more_posts"'.'));
                        noMorePosts = document.querySelector('.'".noMorePosts"'.').value;
                        if(noMorePosts == "false"){
                            document.querySelector('.'".timeline_body"'.').innerHTML += '.'"<img src='."'assets/images/icons/loading.gif'".' id='."'loading'".' style='."'margin-left:33vw;'".' alt='."''".'>"'.'; 

                            //Original ajax request for loading first posts
                            var xhttp = new XMLHttpRequest();

                            xhttp.onreadystatechange = function(){
                                if(this.readyState == 4 && this.status == 200){
                                    if(document.querySelector('.'".nextPage"'.') !== null){
                                        noMorePosts = document.querySelector('.'".noMorePosts"'.').value;
                                        if(noMorePosts == "false"){
                                            
                                    document.querySelector('.'".timeline_body"'.').removeChild(document.querySelector('.'".nextPage"'.'));
                                    
                                    document.querySelector('.'".timeline_body"'.').removeChild(document.querySelector('.'".noMorePosts"'.'));

                                    if(document.querySelector('.'"#loading"'.') !== null)
                                    document.querySelector('.'"#loading"'.').parentNode.removeChild(document.querySelector('.'"#loading"'.'));
                                    document.querySelector('.'".timeline_body"'.').innerHTML += this.responseText;
                                    setTimeout(()=>{
                                    document.querySelector('.'"#show_more_posts"'.').addEventListener('.'"click"'.',()=>{
                                        show_more_posts_func();
                                    })
                                    },500);
                                        }
                                    }
                                }
                            }
                            page = document.querySelector('.'".nextPage"'.').value;
                            page = parseInt(page);
                            xhttp.open("POST","includes/handlers/ajax_load_posts.php?page=" + page + "&userLoggedIn=" + userLoggedIn,true);
                            xhttp.setRequestHeader("Cache-Control","no-store");
                            xhttp.send();
                        }
                      

                    }
                    show_more_posts_func();  
                });

                }   
            },1000);

                </script>
            ';
                }
            ?>
            </div>
        <!-- Main TimeLine End -->

        <!-- Popular On Shughal Start -->
        <div class="popularOnShughal col-md-2">
            <h4 style='color:white;font-weight: 600;text-align:center;padding:4px;'>Trending</h4>
            <hr style='margin-top:0;'>
                <div class="trends">
                    <div class='trend_load' style='width:100%;min-height:40vh;display:flex;justify-content:center;align-items:center;'>
                        <img src="assets/images/icons/loading.gif" alt="Loading...">
                    </div>
                </div>
                <script>
                    var trends = document.querySelector(".trends");
                        var fetch_trends = new XMLHttpRequest();
                        fetch_trends.onreadystatechange = function(){
                            if(this.readyState == 4 && this.status == 200){
                                document.querySelector('.trends').innerHTML = this.responseText;
                            }
                        }
                        fetch_trends.open("POST","includes/handlers/trends.php?call=trends",true);
                        fetch_trends.send();                   
                </script>
        </div>
        <!-- Popular On Shughal End -->
        </div>
    </div>
</main>

<!-- main section end -->

<!-- bottom menu -->
<div class="phone">
      <input type="radio" name="s" id="s1" />
      <input type="radio" name="s" id="s2" checked="checked" />
      <input type="radio" name="s" id="s3" />
      <label class="s_label user_btn" for="s1"
        ><img src="assets/images/bg/user_btn.png"></label>
      <label class="s_label home_btn" for="s2"
        ><img
          src="assets/images/bg/home_btn.png"
          alt=""
      /></label>
      <label class="s_label trend_btn" for="s3"
        ><img
          src="assets/images/bg/trend_btn.png"
          alt=""
      /></label>
      <div class="circle"></div>
      <div class="phone_content">
        <div class="phone_bottom">
          <span class="indicator"></span>
        </div>
      </div>
    </div>
    <?php include("includes/footer.php"); ?>