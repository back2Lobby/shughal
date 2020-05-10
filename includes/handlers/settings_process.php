<?php 
include('../../dbCon.php');
include('../classes/User.php');

if(isset($_SESSION['username'])){
    $userLoggedIn = $_SESSION['username'];
    $user_obj = new User($con,$userLoggedIn);
}else{
    header("Location: register.php");
}

if(isset($_REQUEST['image'])){
    $data = $_POST['image'];
    $image_array_1 = explode(";",$data);
    $image_array_2 = explode(",",$image_array_1[1]);
    

    $data = base64_decode($image_array_2[1]);
    
    $imageName = time() . '.png';
    file_put_contents('../../assets/images/profile_pics/'.$imageName,$data);


    //get previous profile pic
    $prev_profile_pic = $user_obj->getProfilePic();


    //upload image to database
    $upload_image = $con->prepare("UPDATE users SET profile_pic='assets/images/profile_pics/$imageName' WHERE username='$userLoggedIn'");
    $upload_image->execute();
    //remove previous profile picture from server
    unlink("../../".$prev_profile_pic);
    echo 'assets/images/profile_pics/'.$imageName;
}
if(isset($_REQUEST['first_name']) == true && isset($_REQUEST['last_name']) == true && isset($_REQUEST['email']) == true ){
    //new data
    $new_first_name = $_REQUEST['first_name'];
    $new_last_name = $_REQUEST['last_name'];
    $new_email = $_REQUEST['email'];

    //current data
    $get_user_data = $con->prepare("SELECT first_name,last_name,email FROM users WHERE username='$userLoggedIn'");
    $get_user_data->execute();

    $user_data = $get_user_data->fetch(\PDO::FETCH_ASSOC);
    $old_first_name = $user_data['first_name'];
    $old_last_name = $user_data['last_name'];
    $old_email = $user_data['email'];

    if($new_first_name !== $old_first_name){
        $first_name = $new_first_name;

        //validation for first name
        $first_name = strip_tags($first_name); //remove html tags
        $first_name = str_replace(' ','',$first_name); //remove spaces from first name
        $first_name = ucfirst(strtolower($first_name)); //lowercase all except 1st char

        //update first name in database
        $update_first_name = $con->prepare("UPDATE users SET first_name='$first_name' WHERE username='$userLoggedIn'");
        $update_first_name->execute();
    }
    if($new_last_name !== $old_last_name){
        $last_name = $new_last_name;

        //validation for last name
        $last_name = strip_tags($last_name); //remove html tags
        $last_name = str_replace(' ','',$last_name); //remove spaces from first name
        $last_name = ucfirst(strtolower($last_name)); //lowercase all except 1st char
        
        //update last name in database
        $update_last_name = $con->prepare("UPDATE users SET last_name='$last_name' WHERE username='$userLoggedIn'");
        $update_last_name->execute();
    }
    if($new_email !== $old_email){
        $email = $new_email;


        //validation for email
        $email = strip_tags($email); //remove html tags
        $email = str_replace(' ','',$email); //remove spaces from first name
        $email = strtolower($email); //lowercase all except 1st char

        if(FILTER_var($email, FILTER_VALIDATE_EMAIL)){
        $email = FILTER_var($email, FILTER_VALIDATE_EMAIL);
        //check if email already exists
        $e_check = $con->prepare("SELECT email FROM users WHERE email = :em");
        $e_check->bindParam(':em',$email);
        $e_check->execute();
        if($e_check->rowCount() > 0){
            echo "Email Already Exists";
            die();
        }

        //up email in database
        $update_email = $con->prepare("UPDATE users SET email='$email' WHERE username='$userLoggedIn'");
        $update_email->execute();
        }else{
            echo "Email Not Valid";
            die();
        }
    }

    echo "profile updated";
}
//password change
if(isset($_REQUEST['oldpass']) == true){
    $oldpass = $_REQUEST['oldpass'];
    $newpass1 = $_REQUEST['newpass1'];
    $newpass2 = $_REQUEST['newpass2'];

    //password validation
    $newpass1 = strip_tags($newpass1);
    $newpass2 = strip_tags($newpass2);

    if($oldpass !== "" && $newpass1 !== "" && $newpass2 !== ""){
        if($oldpass !== $newpass1 && $oldpass !== $newpass2){
            if($newpass1 == $newpass2){

                $password = md5($newpass1); //encrypt password

                $update_password = $con->prepare("UPDATE users SET password='$password' WHERE username='$userLoggedIn'");
                $update_password->execute();

                echo "Password Updated";
            }else{
                echo "error";
            }
        }else{
            echo "error";
        }
    }else{
        echo "error";
    }


}

?>