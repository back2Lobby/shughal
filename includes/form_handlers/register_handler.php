<?php if(!$con){
    echo "Database connection failed";
}else{
    $fname = "";
    $lname = "";
    $em = "";
    $em2 = "";
    $password = "";
    $password2 = "";
    $date = "";
    $error_array = array(); //holds the error message

    // Action on submit button
    if(isset($_POST['register_button'])){
        //validation for first name
        $fname = strip_tags($_POST['reg_fname']); //remove html tags
        $fname = str_replace(' ','',$fname); //remove spaces from first name
        $fname = ucfirst(strtolower($fname)); //lowercase all except 1st char
        $_SESSION['reg_fname'] = $fname; //store in session variable

        // validation for last name
        $lname = strip_tags($_POST['reg_lname']); //remove html tags 
        $lname = str_replace(' ','',$lname); //remove spaces from first name
        $lname = ucfirst(strtolower($lname)); //lowercase all except 1st char
        $_SESSION['reg_lname'] = $lname; //store in session variable
        

        // validation for email
        $em = strip_tags($_POST['reg_email']); //remove html tags
        $em = str_replace(' ','',$em); //remove spaces from email
        $em = strtolower($em); //lowercase all letters
        $_SESSION['reg_email'] = $em;//store in session variable


        // validation for email confirmation
        $em2 = strip_tags($_POST['reg_email2']);
        $em2 = str_replace(' ','',$em2); //remove spaces from email
        $em2 = strtolower($em2); //lowercase all letters
        $_SESSION['reg_email2'] = $em2;//store in session variable

        // validation for password
        $password = strip_tags($_POST['reg_password']);

        // validation for password confirmation
        $password2 = strip_tags($_POST['reg_password2']);

        $date = date("Y-m-d"); //gets the current date

        //check the both emails match or not
        if($em == $em2){
            //validate email format
            if(FILTER_var($em, FILTER_VALIDATE_EMAIL)){
                //get the filtered email
                $em = FILTER_var($em, FILTER_VALIDATE_EMAIL);

                //check if email already exists
                $e_check = $con->prepare("SELECT email FROM users WHERE email = :em");
                $e_check->bindParam(':em',$em);
                $e_check->execute();
                if($e_check->rowCount() > 0){
                    array_push($error_array, "Email Already Exists.<br>");
                }
            }else{
                array_push($error_array,"Invalid Email Format<br>");
            }
        }else{
            array_push($error_array,"Emails Don't Match<br>");
        }

        if(strlen($fname) > 25 || strlen($fname) < 2){
            array_push($error_array,"Your first name must be between 2 and 25 characters.<br>");
        }
        if(strlen($lname) > 25 || strlen($lname) < 2){
            array_push($error_array,"Your last name must be between 2 and 25 characters.<br>");
        }
        if($password !== $password2){
            array_push($error_array,"Your passwords don't match.<br>");
        }else{
            if(preg_match('/[^A-Za-z0-9]/',$password)){
                array_push($error_array,"Password can only contain letters and numbers.<br>");
            }
        }
        if(strlen($password) > 30 || strlen($password) < 5){
            array_push($error_array,"Your Password can only have length between 5 and 30.<br>");
        }

        if(empty($error_array)){
            $password = md5($password); //encrypt password

            //generate username by concatenating the first and last name
            $username = strtolower($fname . "_" . $lname);

            function nameVerifier($username,$con){
                $stmt = $con->prepare("SELECT username FROM users WHERE username='$username'");
                $stmt->execute();
                $result = $stmt->rowCount();
                if($result > 0){
                    //that means the username already exists
                    return true;
                }else if($result >= 1){
                    //if the username doesn't exists already
                    return false;
                }
            }
            if(nameVerifier($username,$con) == true){
                while(nameVerifier($username,$con) == true){
                    //if the username is already registered then it will generate a random number and put it at the end of username
                    $username = $username . mt_rand(0,100);//generate random number between 0 and 100
                }
            }

            //Profile Picture Assignment
            $rand = mt_rand(1,16);
            $all_profile_pics = ["assets/images/profile_pics/defaults/head_alizarin.png","assets/images/profile_pics/defaults/head_amethyst.png","assets/images/profile_pics/defaults/head_belize_hole.png","assets/images/profile_pics/defaults/head_carrot.png","assets/images/profile_pics/defaults/head_deep_blue.png","assets/images/profile_pics/defaults/head_emerald.png","assets/images/profile_pics/defaults/head_green_sea.png","assets/images/profile_pics/defaults/head_nephritis.png","assets/images/profile_pics/defaults/head_pete_river.png","assets/images/profile_pics/defaults/head_pomegranate.png","assets/images/profile_pics/defaults/head_pumpkin.png","assets/images/profile_pics/defaults/head_red.png","assets/images/profile_pics/defaults/head_sun_flower.png","assets/images/profile_pics/defaults/head_turqoise.png","assets/images/profile_pics/defaults/head_wet_asphalt.png","assets/images/profile_pics/defaults/head_wisteria.png"];
            $profile_pic = $all_profile_pics[$rand];

            $stmt = $con->prepare("INSERT INTO users VALUES(NULL,'$fname','$lname','$username','$em','$password','$date','$profile_pic','0','0','no',',')");
            $stmt->execute();

            array_push($error_array, "<span style='color:#14C800;'>Account Registered Successfully, Go ahead and login!</span><br>");
            //clear session variable on successfull registeration
            $_SESSION['reg_fname']= "";
            $_SESSION['reg_lname']= "";
            $_SESSION['reg_email']= "";
            $_SESSION['reg_email2']= "";
            }
    }

}
 ?>