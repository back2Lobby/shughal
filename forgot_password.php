<?php 
include('dbCon.php');

if(isset($_SESSION['username'])){
    header("Location: register.php");
}
if(isset($_REQUEST['femail'])){

            //validation for email
            $email = strip_tags($_REQUEST['femail']); //remove html tags
            $email = str_replace(' ','',$email); //remove spaces from first name
            $email = strtolower($email); //lowercase all except 1st char
    
            
        if(FILTER_var($email, FILTER_VALIDATE_EMAIL)){
            $email = FILTER_var($email, FILTER_VALIDATE_EMAIL);

        //check for email in the database
        $check_email = $con->prepare("SELECT id FROM users WHERE email='$email'");
        $check_email->execute();

        if($check_email->rowCount() == 1){


            //check if there is already a request for this email
            $check_already = $con->prepare("SELECT * FROM forgot_password WHERE email_addr='$email'");
            $check_already->execute();
            if($check_already->rowCount() == 0){


            //make vkey for email security
            $vkey = md5(time().$email);

            $forgot_password = $con->prepare("INSERT INTO forgot_password VALUES('','$email','$vkey',CURRENT_TIMESTAMP)");
            $forgot_password->execute();
            
          }
        }
                
        }
}   

?>