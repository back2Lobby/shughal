<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home - Shughal</title>

    <script>
    if(sessionStorage.getItem("username") == ""){
        window.open("index.php","_self");
    }
</script>

</head>

<style>
        @import url('https://fonts.googleapis.com/css?family=Poppins&display=swap');

    *{
        margin: 0;
        padding: 0;
        box-sizing:border-box;
        font-family: 'Poppins', sans-serif;
    }
/* 
    ::-webkit-scrollbar {
  width: 12px;
}
::-webkit-scrollbar-thumb {
  background: linear-gradient(transparent, #c0a146);
  border-radius: 6px;
}
::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(transparent, #00c6ff);
} */

body{
    max-width:1366px;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background-color:#2D2845;

}
main{
    max-width:900px;
    width:700px;
    display:flex;
    flex-direction:column;
}
nav{
    background-color:#527ca2;
    width:100%;
    display:flex;
    flex-direction:row;
    justify-content:space-between;
    border-radius:3px;
}
nav .logoSection{
    margin:10px;
    color:white;
    font-size:1.4em;
    user-select:none;
    cursor:pointer;
}
nav .navbar ul {
    display:flex;
    flex-direction:row;
}
nav .navbar {
    display:flex;
    justify-content:center;
    align-items:center;
}


nav .navbar ul li{
    list-style-type:none;
    min-width:100px;
    height:100%;
    user-select:none;
    cursor:pointer;
    transition:0.4s;
    text-align:center;
}

nav .navbar ul li:hover{
    color:white;
}

.wpost {
    resize:none;
    width:100%;
    padding:10px;
    height:100px;
    margin-top:15px;
    border-radius:4px;
    background-color:#95AFBE;
}
.subPost{
    width:100%;
    padding:6px;
    font-size:1.3em;
    cursor:pointer;
    user-select:none;
    background-color:#C1A145;
    border-style:none;
    transition:0.2s;
    border-radius:4px;
}
.subPost:hover{
    background-color:#c0a146;
    box-shadow:4px 4px 8px #3f3f3f;
    color:white;
}
.postSection{
    max-width:100%;
    margin-bottom:100px;
}

.post{
    width:100%;
    display:flex;
    flex-direction:row;
    margin-top:10px;
}
.post .postSender{
    max-width:15%;
}
.post .postSender .upic{
    margin:auto;
    width:50%;
}
.post .postSender .upic img{
    width:100%;
}
.post .postSender .uname{
    max-width:100%;
    text-align:center;
}
.postBody{
    width:85%;
    background-color:#95AFBE;
    border-radius:4px;
    padding:4px;
    font-size:0.90em;
    max-height:92px;
    overflow:auto;
    }
hr{
    margin:10px auto;
    width:100%;
    height:9px;
    border-style:none;
    background-color:#1b182a;
    border-radius:5px;
}

</style>
<body>
<!-- navbar for home -->
<main>
 <nav>
    <div class="logoSection" onclick='location.href = "homepage.php" '>
        <h1>Shughal</h1>
    </div>
    <div class="navbar">
        <ul>
            <li class="nUser">username</li>
            <li class="logout">Logout</li>
        </ul>
    </div>
 </nav>   
 <!-- main dynamic section for navabar -->
<div class="writePost">
<textarea name="wpost" class="wpost" rows="6" placeholder="What's happening?" maxlength="120"></textarea>
<button type="button" name="subPost" class="subPost">Post</button>
</div>

<hr>

<div class="postSection">

<!-- <div class="post">

    <div class="postSender">
        <div class="upic">
            <img src="user.png" alt="User Pic Not Found">
        </div>
        <div class="uname">
        <p>
         </p>
        </div>
    </div>

    <div class="postBody">

    </div>
</div> -->



</div>


<script>
document.addEventListener('DOMContentLoaded',()=>{
    debugger;
    console.log(sessionStorage.getItem('username'));
    var xhttp = new XMLHttpRequest();
            xhttp.open('POST',"postprocess.php",true);
            xhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    var postCollection = this.responseText.split(',');
                    postCollection.forEach((post)=>{
                        var postData = post.split(':');
                        if(postData[1] !== undefined){
                        document.querySelector('.postSection').innerHTML += `<div class="post">

<div class="postSender">
    <div class="upic">
        <img src="user.png" alt="User Pic Not Found">
    </div>
    <div class="uname">
    <p>${postData[0]}
     </p>
    </div>
</div>

<div class="postBody">
${postData[1]}
</div>
</div>`
debugger;}
                    });
                }
            }
            xhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
            let usname = sessionStorage.getItem("username");
            xhttp.send(`ins=loadposts&&user=${usname}`);
//logout button
document.querySelector('.logout').addEventListener('click', ()=>{
    sessionStorage.setItem("username","");
    window.open("index.php","_self");
})
//show current user
document.querySelector('.nUser').innerHTML = sessionStorage.getItem("username");
document.querySelector('.nUser').style.textTransform = "capitalize";

//post button
document.querySelector('.subPost').addEventListener('click',()=>{

var wpost = document.querySelector('.wpost').value;



function npCheck(val){
    if(val == ''){
        return false;
    }
    val = val.split('');
        return val.every((ch)=>{
        //checks if the username is only alphabets and numbers
        if((ch.charCodeAt(0) >= 65 && ch.charCodeAt(0) <= 90) || (ch.charCodeAt(0) >= 97 && ch.charCodeAt(0) <= 122) || (ch.charCodeAt(0) >= 48 && ch.charCodeAt(0) <= 57) || ch.charCodeAt(0) == 32){
          return true;
        }else{
            return false;
        }
    });
    }

 (function() {
     debugger;
        if(npCheck(wpost)){
            var xhr = new XMLHttpRequest();
            xhr.open('POST',"postprocess.php",true);
            xhr.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    if(this.responseText == "ok"){
                        postit();
                    }
                }
            }
            let usname = sessionStorage.getItem("username");
            xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
            xhr.send(`pbody=${wpost}&&ins=noload&&user=${usname}`);
          }
        })();
        function postit(){
            debugger;
            document.querySelector('.postSection').innerHTML += `<div class="post">

<div class="postSender">
    <div class="upic">
        <img src="user.png" alt="User Pic Not Found">
    </div>
    <div class="uname">
    <p>${sessionStorage.getItem('username')}
     </p>
    </div>
</div>

<div class="postBody">
${wpost}
</div>
</div>`
        }

    });


});
</script>
 </main>
</body>
</html>