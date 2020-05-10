<?php 
include("dbCon.php");
if(isset($_REQUEST['vkey'])){
    //get info about this vkey
    $get_vkey_info = $con->prepare("SELECT * FROM forgot_password WHERE vkey = :vkey");
    $get_vkey_info->bindParam(':vkey',$vkey);
    $vkey = $_REQUEST['vkey'];
    $get_vkey_info->execute();
    if($get_vkey_info->rowCount() == 1){
        $vkey_row = $get_vkey_info->fetch(\PDO::FETCH_ASSOC);
        $frow_id = $vkey_row['id'];
        $frow_email = $vkey_row['email_addr'];
        $frow_time = $vkey_row['time_requested'];
    }else{
        header("Location: register.php/");
    }
}
if(isset($_REQUEST['newpass1']) == true && isset($_REQUEST['newpass2']) == true){
    //password change
    $newpass1 = $_REQUEST['newpass1'];
    $newpass2 = $_REQUEST['newpass2'];

    //password validation
    $newpass1 = strip_tags($newpass1);
    $newpass2 = strip_tags($newpass2);

    if($newpass1 !== "" && $newpass2 !== ""){
            if($newpass1 == $newpass2){

                $password = md5($newpass1); //encrypt password

                $update_password = $con->prepare("UPDATE users SET password='$password' WHERE email='$frow_email'");
                $update_password->execute();

                //remove this request
                $del_this_request = $con->prepare("DELETE FROM forgot_password WHERE id='$frow_id' AND email_addr='$frow_email'");
                $del_this_request->execute();

                echo "Password Updated";
            }else{
                echo "error";
            }
    }else{
        echo "error";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sughal eControl</title>
    <style>
        @import url("https://fonts.googleapis.com/css?family=Poppins:400,800&display=swap");

        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
        }

        body{
            min-height: 100vh;
            background-color:#2d2845;
        }

        header{
            width: 100%;
            min-height: 75px;
            display:flex;
            justify-content:center;
            align-items:center;
            background-color: #527ca2;
            font-size: 3em;
            font-weight: 600;
            color: #333333;
        }
        #main{
            position:absolute;
            background-color:#527ca2;
            min-height:200px;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            width:min(500px,100%);
            border-radius:3px;
        }
        #passreset_btn:hover{
            transform:scale(1.05);
        }
        #resetmessage{
            width: 100%;
            font-size: 0.7em;
            padding: 4px;
            background-color: lightgreen;
            display: none;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <header>
        Shughal
    </header>
    <div id="main">
        <form>
            <div style='text-align:center;font-size:1.4em;color:white;'>
                Enter Your New Password
            </div>
            <div style='text-align:center;color:white;'>
                <input type="password" id="password1" style='min-width:60%;border-radius:4px;padding:4px;border:none;'>
            </div>
            <div style='text-align:center;font-size:1.4em;color:white;'>
                Confirm Your Password
            </div>
            <div style='text-align:center;color:white;'>
                <input type="password" id="password2" style='min-width:60%;border-radius:4px;padding:4px;border:none;'>
            </div>
            <div style="text-align:center;">
            <button type="button" id="passreset_btn" style='margin:4px;width:60%;font-size:1.2em;background-color:#ffc107;border:none;color:#333333;cursor:pointer;'>Reset Password</button>
            </div>
            <div id="resetmessage"></div>
        </form>
    </div>

    <script>

        var resetbtn = document.getElementById('passreset_btn');
        resetbtn.addEventListener('click', ()=>{
            var pass1 = document.getElementById('password1').value;
            var pass2 = document.getElementById('password2').value;
            if(pass1 !== "" && pass2 !== "" && pass1 == pass2){
                debugger;
                var resetmessage = document.getElementById('resetmessage');
                fetch("econtrol.php?newpass1="+pass1+"&&newpass2="+pass2+"&&vkey=<?php echo $vkey;?>").then(res => res.text().then(data => {
                    setTimeout(()=>{
                            window.location.replace(location.host);
                        },2000);
                }));
            }
        });


    </script>

</body>
</html>
