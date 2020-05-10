<?php 
session_start();
        if(isset($_SESSION['username'])){
            $userLoggedIn = $_SESSION['username'];
            if(isset($_REQUEST['username'])){
                $profile_username = $_REQUEST['username'];
            }
        }else{
            header("Location: register.php");
        }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- add jquery -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <!-- add popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <!-- add bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <script src="assets/js/bootstrap.js"></script>
    <!-- add font awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/js/all.min.js"></script>
    <!-- custom css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body{
            background-color:#2d2846;
        }
    </style>
    <!-- <script>
        if(localStorage.getItem("timeThrottle") !== null){localStorage.removeItem("timeThrottle")};
    </script> -->
</head>
<body>
<div class="profile_posts">
                                    <img src="assets/images/icons/loading.gif" style="margin:auto;" id="loadingProfilePosts" alt="">
</div>
<script id="profileScript" type="text/javascript">
                            var page = "";
                            var noMorePosts = "";
                            var userLoggedIn = "<?php echo $profile_username; ?>";
                            loadAjaxProfilePosts();
                            function loadAjaxProfilePosts(){
                                document.querySelector("#loadingProfilePosts").style.display = "block";

                                //Original ajax request for loading first posts
                                var xhttp = new XMLHttpRequest();

                                xhttp.onreadystatechange = function(){
                                    if(this.readyState == 4 && this.status == 200){
                                        document.querySelector("#loadingProfilePosts").style.display = "none";
                                        document.querySelector(".profile_posts").innerHTML = this.responseText;
                                        page = document.querySelector(".nextProfilePostsPage").value;
                                        page = parseInt(page);

                                        window.parent.postMessage({winHeight:document.body.offsetHeight,winName: window.name},"*");
                                    }
                                }


                                xhttp.open("POST","includes/handlers/fetch_profile_posts.php?page=0&profile_username=<?php echo $profile_username;?>",true);
                                xhttp.setRequestHeader("Cache-Control","no-store");
                                xhttp.send();
                            }
                            //loading remaining posts on scroll

                            window.addEventListener('message',(e)=>{
                                if(e.data.isscrolled == "yes"){
                                if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                                    var tempVar = document.querySelector(".profile_posts").lastElementChild.className;
                                    if(toString(tempVar) == toString("noMoreProfilePosts")){
                                        var noMorePosts = document.querySelector(".profile_posts").lastElementChild.value;
                                    }
                                    if(noMorePosts == "false"){
                                        if(document.querySelector("#loadingProfilePosts") == null){
                                        document.querySelector(".profile_posts").innerHTML += "<img src='assets/images/icons/loading.gif' id='loadingProfilePosts' style='margin-left:33vw;' alt=''>";
                                        window.parent.postMessage({winHeight:document.body.offsetHeight,winName: window.name},"*"); 
                                        }
                                        //Original ajax request for loading first posts
                                        var xhttp = new XMLHttpRequest();

                                        xhttp.onreadystatechange = function(){
                                            if(this.readyState == 4 && this.status == 200){
                                                if(document.querySelector(".nextProfilePostsPage") !== null){
                                                    var tempArr = document.querySelectorAll(".profile_posts > input");
                                                    var noMorePosts = tempArr[1];
                                                    var profileNextPage = tempArr[0];
                                                    if(noMorePosts.value == "false"){
                                                document.querySelector(".profile_posts").removeChild(profileNextPage);
                                                var x = document.querySelector(".profile_posts");
                                                document.querySelector(".profile_posts").removeChild(noMorePosts);

                                                document.querySelector("#loadingProfilePosts").style.display = "none";
                                                document.querySelector(".profile_posts").innerHTML += this.responseText;
                                                window.parent.postMessage({winHeight:document.body.offsetHeight,winName: window.name},"*");
                                                    }
                                                }
                                            }
                                        }
                                        page = document.querySelector(".nextProfilePostsPage").value;
                                        page = parseInt(page);
                                        xhttp.open("POST","includes/handlers/fetch_profile_posts.php?page=" + page + "&profile_username=<?php echo $profile_username;?>",true);
                                        xhttp.setRequestHeader("Cache-Control","no-store");
                                        xhttp.send();
                                    }
                                }
                                return false;
                        }else if(e.data.rHeight !== null && e.data.rHeight == "true"){
                            window.parent.postMessage({winHeight:document.body.offsetHeight,winName: window.name},"*");
                        }else if(e.data.winHeight !== null){
                            document.getElementsByName(`${e.data.winName}`)[0].height = e.data.winHeight + 10;
                            window.parent.postMessage({winHeight:document.body.offsetHeight,winName: window.name},"*");
                        }

                    });
                    </script>
</body>
</html>