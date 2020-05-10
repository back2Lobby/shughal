<?php 
if(isset($_POST['login_button'])){
    //velidate the email
    $email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL);
    $_SESSION['log_email'] = $email; //store email to session var
    $password = md5($_POST['log_password']);

    $stmt = $con->prepare("SELECT * FROM users WHERE email='$email' AND password='$password'");
    $stmt->execute();
    $log_result = $stmt->rowCount();
    if($log_result == 1){
        $row = $stmt->fetch();
        $username = $row['username'];
        $email = $row['email'];

        $stmt = $con->query("SELECT * FROM users WHERE email='$email' AND user_closed='yes'");
        if($stmt->rowCount() == 1){
            //if account is closed it should reopen it
            $reopen_account = $con->query("UPDATE users SET user_closed='no' WHERE email='$email'");
        }

        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    }else{
        array_push($error_array,"<span style='color:red'>Email Or Password Was Incorrect.</span><br>");
    }
}   
 ?>