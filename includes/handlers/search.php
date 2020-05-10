<?php 
include('../../dbCon.php');
include('../classes/User.php');

if(isset($_SESSION['username'])){
    $userLoggedIn = $_SESSION['username'];
    $user_obj = new User($con,$userLoggedIn);
}else{
    header("Location: register.php");
}

if(isset($_REQUEST['sData'])){
    $search = $_REQUEST['sData'];
    $search = strip_tags($search); //strip the tags
    $search = str_replace(' ','',$search); //remove spaces from email
    $search = strtolower($search); //lowercase all letters

    $search_query = $con->prepare("SELECT first_name,last_name,username,profile_pic FROM users WHERE first_name LIKE '$search%' OR last_name LIKE '$search%'");
    $search_query->execute();
    if($search_query->rowCount() > 0){
        if(isset($_REQUEST['disp']) && $_REQUEST['disp'] == "mobile"){
            $str = '<div id='.'"search_result_mobile"'.'>';            
        }else{
    $str = '<div id='.'"search_result"'.'>';
        }
    $search_res = $search_query->fetchAll(\PDO::FETCH_ASSOC);
    foreach($search_res as $data){
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $username = $data['username'];
        $profile_pic = $data['profile_pic'];
        $str .= "<a href='$username'><div class='search_res'><div class='sImage'><image src='$profile_pic'></div><div class='sBio'><div class='sFullName'>$first_name $first_name</div><div class='sUsername'>$username</div></div></div></a><hr>";
        
    }
    $str .= '</div>';
    echo $str;
    }else{

        if(isset($_REQUEST['disp']) && $_REQUEST['disp'] == "mobile"){
            echo "<div id='search_result_mobile'>No users found</div>";           
        }else{
            echo "<div id='search_result'>No users found</div>";
        }
    }
}

?>