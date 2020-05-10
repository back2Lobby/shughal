<?php 
session_start();

if(isset($_REQUEST['msg']) == true){
    if($_REQUEST['msg'] = "off"){
        if(in_array("Account Registered Successfully, Go ahead and login",$_SESSION) == true){
            $_SESSION = array_diff($_SESSION,array("Account Registered Successfully, Go ahead and login"));
        }
    }
}


if(isset($_SESSION['message']) == true){
    if($_SESSION['message'] !== ""){
        if($_REQUEST['err'] == "yes"){
            echo $_SESSION['message'];
        }
    }
}
?>