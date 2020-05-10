<?php
include('dbCon.php');
include('includes/form_handlers/register_handler.php');
include('includes/form_handlers/login_handler.php');


if(isset($_REQUEST['clearerr']) == true){
    if($_REQUEST['clearerr'] == 'yes'){
        $_SESSION['message'] = "";
        $_SESSION['reg_fname'] = "";
        $_SESSION['reg_lname'] = "";
        $_SESSION['reg_email'] = "";
        $_SESSION['reg_email2'] = "";
    }
}

//delete outdated forgot password requests //max of 30 minutes
$get_requests = $con->prepare("SELECT * FROM forgot_password");
$get_requests->execute();
$forgot_requests = $get_requests->fetchAll(\PDO::FETCH_ASSOC);
foreach ($forgot_requests as $request){
    $id = $request['id'];
    $request_email = $request['email_addr'];
    $vkey  = $request['vkey'];
    $time_requested = $request['time_requested'];
    $time_requested = strtotime($time_requested);
    if(time() - $time_requested > 1800){
        $del_this_request = $con->prepare("DELETE FROM forgot_password WHERE id='$id'");
        $del_this_request->execute();
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shughal</title>
    <link rel="stylesheet" type="text/css" href="assets/css/register_style.css">
</head>
<body>
    <main class="main">
    <div id="forgot_box">
        <div class="fbox_body" style="display:flex;justify-content:center;align-items:center;">
            <span id="forgot_cross">&#10006</span>
            <form>
                <div style="display:flex;justify-content:center;align-items:center;flex-direction:column;width:100%;">
                    <h4 style="color:white;">Enter your Email Address</h4>
                    <small style="font-size:16px;color:#dddddd;">Your Password Will Be Sent To Your Email</small>
                    <hr style="width:70%;border-top:2px solid #333333;border-bottom:none;">
                </div>

                <div style="display:flex;justify-content:center;align-items:center;">
                    <input type="email" name="email" id="forgot_email" style="width:100%;background-color:#ffffff !important;">
                </div>
                <button type="button" id="forgot_send" style="width:99%;background-color:#333333;color:white;border:none;font-size:1.2em;border-radius:4px;">Send Email</button>
                <div class="forgot_message"></div>
            </form>
        </div>
    </div>
    <!-- header start -->
    <header class="header">
    <h1>Shughal</h1>
    </header>
    <!-- header end -->
    <!-- container start -->
    <div class="container">
    <!-- login and register form will show here dynamically -->
    <div class="logRegOptions" style="margin-top:6px;">
    <span id="forgot_password">
    <a>    Forgot Password?</a>
    </span>
    <span class="switcher">
    <a>Sign Up For A New Account!</a>
    </span>
    </div>
    </div>
    <!-- contianer end -->

    </main>
    <script>
    var message = document.createElement('div');
    var loginForm = `<!-- login form -->
    <h1 class="headText" style="font-weight:900;color:white;width:44%;font-size:1.8em;text-align:center;margin-bottom:20px;">Login Now</h1>
    <form action="" class="loginForm" method="POST" width:"54%;">
    <?php 
        if(in_array("<span style='color:red'>Email Or Password Was Incorrect.</span><br>",$error_array)){echo "<span class='message'>Email Or Password Was Incorrect.</span><br>";}  ?>
        <input type="email" name="log_email" placeholder="Email Address">
        <br>
        <input type="password" name="log_password" placeholder="Password"><br>
        <input type="submit" name="login_button" value="Login">
        <br>
    </form>
    `;
    var regForm = `<!-- registration form -->
    <form action="" class="regForm" method="POST">

        <input type="text" name="reg_fname" placeholder="First Name" value="<?php if(isset($_SESSION['reg_fname'])){echo $_SESSION['reg_fname'];}?>" required>
        <br>
        <!-- shows error related to first name -->
        <?php if(in_array("Your first name must be between 2 and 25 characters.<br>",$error_array)){$_SESSION['message']="Your first name must be between 2 and 25 characters.";} ?>
        <input type="text" name="reg_lname" placeholder="Last Name" value="<?php if(isset($_SESSION['reg_lname'])){echo $_SESSION['reg_lname'];}?>" required>
        <br>
        <!-- shows error related to last name -->
        <?php if(in_array("Your last name must be between 2 and 25 characters.<br>",$error_array)){$_SESSION['message']="Your last name must be between 2 and 25 characters.";} ?>
        <input type="email" name="reg_email" placeholder="Email" value="<?php if(isset($_SESSION['reg_email'])){echo $_SESSION['reg_email'];}?>" required>
        <br>
        <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php if(isset($_SESSION['reg_email2'])){echo $_SESSION['reg_email2'];}?>" required>
        <br>
        <!-- shows error related to email -->        
        <?php if(in_array("Invalid Email Format<br>",$error_array)){ $_SESSION['message']="Invalid Email Format";}
        if(in_array("Emails Don't Match<br>",$error_array)){$_SESSION['message']="Emails Don't Match";}
        if(in_array("Email Already Exists.<br>",$error_array)){$_SESSION['message']="Email Already Exists.";} ?>
        <input type="password" name="reg_password" placeholder="Password" required>
        <br>
        <input type="password" name="reg_password2" placeholder="Confirm Password" required>
        <br>
        <?php if(in_array("Your passwords don't match.<br>",$error_array)){$_SESSION['message']="Your passwords don't match.";} 
        if(in_array("Password can only contain letters and numbers.<br>",$error_array)){$_SESSION['message']="Password can only contain letters and numbers.";} 
        if(in_array("Your Password can only have length between 5 and 30.<br>",$error_array)){$_SESSION['message']="Your Password can only have length between 5 and 30.";} 
        
        ?>
        <input type="submit" name="register_button" value="Register">
        <br>
        <?php
        if(in_array("<span style='color:#14C800;'>Account Registered Successfully, Go ahead and login!</span><br>",$error_array)){$_SESSION['message']="Account Registered Successfully, Go ahead and login";}?>
        </form>`;

        loginFormAdder();

        function loginFormAdder(){
        var container = document.querySelector('.container');
        if(container.firstElementChild.tagName == "FORM"){
            container.removeChild(container.firstElementChild);
        }


        var tempContainer = container.innerHTML;
        container.innerHTML = loginForm;
        container.innerHTML += tempContainer;
        var logRegOptions = document.querySelector('.logRegOptions');
        logRegOptions.lastElementChild.firstElementChild.innerHTML = "Sign Up For A New Account!";




        //event listener
        document.querySelector('.switcher').addEventListener('click',()=>{
            var xhr = new XMLHttpRequest();
                xhr.open('GET','register.php?clearerr=yes',true);
                xhr.send();
                debugger;
            if(document.querySelector('input[type="submit"]').value == "Login"){
                if(document.querySelector('.container').firstElementChild.tagName == "DIV" || document.querySelector('.container').firstElementChild.tagName == "H1"){
                    container.removeChild(container.firstElementChild);
                }
                regFormAdder();
            }else if(document.querySelector('input[type="submit"]').value == "Register"){
                if(document.querySelector('.container').firstElementChild.tagName == "DIV" || document.querySelector('.container').firstElementChild.tagName == "H1"){
                    container.removeChild(container.firstElementChild);
                }
                loginFormAdder();
            }

        }); 
        }

        function regFormAdder(){
        var container = document.querySelector('.container');
        if(container.firstElementChild.tagName == "FORM"){
            container.removeChild(container.firstElementChild);
        }
        var tempContainer = container.innerHTML;
        container.innerHTML = regForm;
        container.innerHTML += tempContainer;
        var logRegOptions = document.querySelector('.logRegOptions');
        logRegOptions.lastElementChild.firstElementChild.innerHTML = "Already Have Account?";

        //event listener
        document.querySelector('.switcher').addEventListener('click',()=>{
            var xhr = new XMLHttpRequest();
                xhr.open('GET','register.php?clearerr=yes',true);
                xhr.send();
                debugger;
            if(document.querySelector('input[type="submit"]').value == "Login"){
                if(document.querySelector('.container').firstElementChild.tagName == "DIV" || document.querySelector('.container').firstElementChild.tagName == "H1"){
                    container.removeChild(container.firstElementChild);
                }
                regFormAdder();
            }else if(document.querySelector('input[type="submit"]').value == "Register"){
                if(document.querySelector('.container').firstElementChild.tagName == "DIV" || document.querySelector('.container').firstElementChild.tagName == "H1"){
                    container.removeChild(container.firstElementChild);
                }
                loginFormAdder();
            }

        });  

        }    
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            if(this.responseText !== null && this.responseText !== ""){
                setTimeout(()=>{
                    if(this.responseText !== "Account Registered Successfully, Go ahead and login"){
            document.querySelector('.switcher').click();
                    }
            document.querySelector('.container').prepend(message);
            message.classList.add('message');
            message.innerText = this.responseText;
            if(this.responseText == "Account Registered Successfully, Go ahead and login"){
                message.style.backgroundColor = "lightgreen";
            }
            var rst = new XMLHttpRequest();
                rst.open("GET","errorDisp.php?msg=off",true);
                rst.send();
            setTimeout(()=>{
                message.style.display = "none";
            },5000);
            },0);
            }
        }
    }    
        
    xhr.open("GET","errorDisp.php?err=yes",true);
    xhr.send();
    


    //forgot_password
    var forgot_password = document.getElementById('forgot_password');
    document.getElementById('forgot_box').style.display = "none";
    forgot_password.addEventListener('click',()=>{
        if(document.getElementById('forgot_box').style.display == "none"){
            var forgot_box = document.getElementById('forgot_box');
            forgot_box.style.display = "grid";
            var forgot_send = document.getElementById('forgot_send');
            forgot_send.addEventListener('click',()=>{
                var forgot_email = document.getElementById('forgot_email').value;
                document.querySelector('.forgot_message').style.display = 'flex';
                document.querySelector('.forgot_message').innerHTML = "<img src='assets/images/icons/loading_new.gif' height='60px'>";
                document.querySelector('.forgot_message').style.backgroundColor = "transparent";
                //send request for forgot password
                setTimeout(()=>{
                fetch("forgot_password.php?femail="+forgot_email).then(res => res.text().then(data => {console.log(data)}));;
                //send email
                fetch("mail.php?forgot_pass=yes&&forgot_email="+forgot_email).then(res => res.text().then(data => {
                    if("error" == data){
                        document.querySelector('.forgot_message').style.display = 'flex';
                        document.querySelector('.forgot_message').innerHTML = "There was an error sending the email, Please try again later";
                        document.querySelector('.forgot_message').style.backgroundColor = "#dc3545";
                        setTimeout(()=>{
                            document.querySelector('.forgot_message').style.display = "none";
                            document.querySelector('.forgot_message').innerHTML = "";
                        },3000);
                    }else{
                        document.getElementById('forgot_email').value = "";
                        document.querySelector('.forgot_message').style.display = 'flex';
                        document.querySelector('.forgot_message').innerHTML = "Email Sent Successfully";
                        document.querySelector('.forgot_message').style.backgroundColor = "lightgreen";
                        setTimeout(()=>{
                            document.querySelector('.forgot_message').style.display = "none";
                            document.querySelector('.forgot_message').innerHTML = "";
                        },3000);
                    }
                }));
               },0)
            });
        }
        document.querySelector("#forgot_cross").addEventListener('click', ()=>{
                var forgot_box = document.getElementById('forgot_box');
                forgot_box.style.display = "none";
            });
    });




    if(window.history.replaceState){
        window.history.replaceState(null,null,window.location.history);
    }
    </script>
</body>
</html>