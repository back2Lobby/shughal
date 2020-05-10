<?php  
include('../../dbCon.php');

if(isset($_SESSION['username'])){
    $userLoggedIn = $_SESSION['username'];
}else{
    header("Location: register.php");
}


if(isset($_SESSION['username'])){
    if(isset($_REQUEST['delNotif'])){
        $notif_to_del = $_REQUEST['delNotif'];
        $deletion_query = $con->prepare("DELETE FROM notifications WHERE id='$notif_to_del'");
        $deletion_query->execute();
    }
}
?>