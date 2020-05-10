<?php 
include("../../dbCon.php");
include("../classes/User.php");
include("../classes/Post.php");

$limit = 10; //Number of Posts To Be Loaded Per Call

$posts = new Post($con,$_REQUEST['userLoggedIn']);
$posts->loadPostsFriends($_REQUEST,$limit);

?>