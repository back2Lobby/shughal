<?php
require 'PHPMailer-master/PHPMailerAutoload.php';
include('dbCon.php');

if(isset($_REQUEST['forgot_pass'])){
$email = $_REQUEST['forgot_email'];

    // validation for email
    $email = strip_tags($email); //remove html tags
    $email = str_replace(' ','',$email); //remove spaces from email
    $email = strtolower($email); //lowercase all letters

    if(FILTER_var($email, FILTER_VALIDATE_EMAIL)){
        //get the filtered email
        $email = FILTER_var($email, FILTER_VALIDATE_EMAIL);

        //check for email in the database
        $check_email = $con->prepare("SELECT first_name,last_name,password FROM users WHERE email='$email'");
        $check_email->execute();

        if($check_email->rowCount() == 1){

            $full_name = $check_email->fetch(\PDO::FETCH_ASSOC);
            $password = $full_name['password'];
            $full_name = $full_name['first_name']. " ". $full_name['last_name'];

            //get vkey
            $get_vkey = $con->prepare("SELECT vkey FROM forgot_password WHERE email_addr='$email'");
            $get_vkey->execute();
            if($get_vkey->rowCount() > 0){
            $vkey = $get_vkey->fetch(\PDO::FETCH_ASSOC);
            $vkey = $vkey['vkey'];
            }
        }else{
            echo "error";
            die();
        }

$mail = new PHPMailer();
  
  //Enable SMTP debugging.
  $mail->SMTPDebug = 1;
  //Set PHPMailer to use SMTP.
  $mail->isSMTP();
  //Set SMTP host name
  $mail->Host = "smtp.gmail.com";
  $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
  //Set this to true if SMTP host requires authentication to send email
  $mail->SMTPAuth = TRUE;
  //Provide username and password
  $mail->Username = "use your email address here";
  $mail->Password = "use your email password";
  //If SMTP requires TLS encryption then set it
  $mail->SMTPSecure = "false";
  $mail->Port = 587;
  //Set TCP port to connect to
  
  $mail->From = "use your email here";
  $mail->FromName = "Shughal Offical";
  
  $mail->addAddress($email);
  
  $mail->isHTML(true);
 
  if(isset($full_name) == false){
      $full_name = "Shughal's User";
  }
  if(isset($vkey) == false){
    $vkey = "#";
}


  $mail->Subject = "Forgot Your Password";
  $mail->Body = "<div><img src='https://lh3.googleusercontent.com/AZhXWrcC2ybrjrsOprIiajDf_7Mh24rNRldujVMm9gZgyj36j05Rrxlo9jiWYYSlEanVYE30oQujS8jAiluVhFSNM8O6-PvcpiWEZusdQ0U09mmHACQ4bwBho0QtjcBxjGI7LU3DUjfsQ3R8RisUeO01E2uyXhNOtJ2UtpjL0hei1kM8DepWmjlkBjBYQrD5poYJNV9T9GdRfmCOFWr5Yxz0UY3SWx2BvkvZHustKwYVmr5gNuRWarqxep4x96NozVhEgrwjbEPH4TNjMCTwNv82R_yDnRueE8pqU6j4tgifKSXsCUiX_hc_AKRkRnJN2tM_M2aIgfeodD1mhwiKdZo_UtIWeN-QRTxkjBNh2wObiPA64e4rl-eqID-RjzD-FRdLyKXVvQhnhka8fg8gRJeNT2EWKcBVWJ4L-dHvGoffEGsLhUmIXemORoBwQYF88F_XtTJCBva9RKdtjnQ7WXAN56JTzOU40E8VhtLRSDFXFuXpBXBU0rj_JLeCnW-qEeqNZaKD8MWINsIcKmhguFuu5ZNaLbs3ic0ztm-1NnUL4yWigciPY1B00Oeosext5QAjYQ6mmJnZBIbvUXQbtS_rZQeBdQQIran7g6aq-0-zludiKlMGsh78VA-xrAzjC_n-i56poPm3Op5MdfrxvndegYjGupaug8cLPdSWkDM3BTbaNukK3WLhch808YLumtqb=w1366-h576-ft' style='width:100%;min-height:60px;'></div><br>Hi $full_name,<br><p>You can reset you password by pressing the button below:</p><div style='display:flex;justify-content:center;align-items:center;'><a href='https://localhost/Shughal/econtrol.php?vkey=$vkey'><button type='button' style='background-color:#007bff;font-size:1.4em;border:none;border-radius:4px;'>Reset Password</button></a></div>";
  $mail->AltBody = "This is the plain text version of the email content";
   echo "sent";
   $mail->send();
  }
 }
?>