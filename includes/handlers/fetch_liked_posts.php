<?php 
include("../../dbCon.php");
include("../classes/User.php");
include("../classes/Post.php");

if(isset($_SESSION['username'])){
    $userLoggedIn = $_SESSION['username'];
}else{
    header("Location: register.php");
}


$limit = 10; //Number of Posts To Be Loaded Per Call

$posts = new Post($con,$_REQUEST['profile_username']);
$posts->loadLikedPosts($_REQUEST,$limit,$userLoggedIn);

?>