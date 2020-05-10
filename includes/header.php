<?php 
//checks if user in logged in or not
if(isset($_SESSION['username'])){
    $userLoggedIn = $_SESSION['username'];
}else{
    header("Location: register.php");
}
$stmt = $con->prepare("SELECT * FROM users WHERE username='$userLoggedIn'");
$stmt->execute();
$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);if (isset($_SESSION['username'])) {
    $userLoggedIn = $_SESSION['username'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shughal</title>


    <!-- add jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <!-- add popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <!-- add bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <script src="assets/js/bootstrap.js"></script>

    <!-- croppie cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.4/croppie.min.js" integrity="sha256-bQTfUf1lSu0N421HV2ITHiSjpZ6/5aS6mUNlojIGGWg=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.4/croppie.min.css" integrity="sha256-/n6IXDwJAYIh7aLVfRBdduQfdrab96XZR+YjG42V398=" crossorigin="anonymous" />
    
    <!-- add font awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/js/all.min.js"></script>
    <!-- custom css -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<!-- nav header start-->
<header class="top_bar">
    <nav class="myNav navbar navbar-expand-lg navbar-light">
    <a href="http://localhost/Shughal" class="logo text-decoration-none navbar-brand">Shughal</a>

        <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent" style="z-index:9999;">
            <ul class="navbar-nav mr-auto" id='all_nav_links'>
                <li class="myNavItem0 nav-item">
                    <a href="<?php echo $userLoggedIn;?>" class="nav-link"><?php if (isset($result[0]['first_name'])) {echo $result[0]['first_name'];}?></a>
                </li>
                <li class="myNavItem1 nav-item active">
                    <a href="http://localhost/Shughal" class="nav-link"><i class="fas fa-home"></i></a>
                </li>
                <li class="myNavItem2 nav-item">
                    <a href="Inbox" class="nav-link"><i class="fas fa-envelope"></i></a>
                </li>
                <li class="myNavItem3 nav-item">
                    <a href="#" class="nav-link"><i class="fas fa-bell"></i></a>
                </li>
                <li class="myNavItem4 nav-item">
                    <a href="People&Friends" class="nav-link"><i class="fas fa-users"></i></a>
                </li>
                <li class="myNavItem5 nav-item">
                    <a href="Settings" class="nav-link"><i class="fas fa-cogs"></i></a>
                </li>
                <li class="myNavItem6 nav-item">
                    <a href="includes/handlers/logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i></a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="search_bar">
            <input class="search_input" id="search_input" placeholder="Search..." autocomplete="off"></input>
    </div>
    <script>
    var search_input = document.getElementById('search_input');
    search_input.addEventListener('input',search_func);

    function search_func(e) {
        e.preventDefault();
        var search_input = document.getElementById('search_input');
        localStorage.setItem('search_input', search_input.value);
        if(search_input.value !== ""){
        var sVal = search_input.value;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                    if(document.querySelector("#search_result") !== null){
                    document.querySelector("#search_result").parentNode.removeChild(document.querySelector("#search_result"));
                    document.querySelector('.top_bar').innerHTML += this.responseText;
                    var search_input = document.getElementById('search_input');
                    search_input.addEventListener('keyup',search_func);
                    search_input.focus();
                    var sVal = search_input.value;
                    search_input.value = localStorage.getItem('search_input');
                    }else{
                        document.querySelector('.top_bar').innerHTML += this.responseText;
                    var search_input = document.getElementById('search_input');
                    search_input.addEventListener('keyup',search_func);
                    search_input.focus();
                    var sVal = search_input.value;
                    search_input.value = localStorage.getItem('search_input');
                    }
            }
        };
        xhttp.open("POST","includes/handlers/search.php?sData="+search_input.value, true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send();
        var search_input = document.getElementById('search_input');
                    search_input.addEventListener('keyup',search_func);
                    search_input.focus();
                    search_input.value = localStorage.getItem('search_input');
        }else{
            document.querySelector("#search_result").parentNode.removeChild(document.querySelector("#search_"));
        }
    }
    document.body.addEventListener('click',(e)=>{
                if(document.getElementById('search_result') !== null){
                var search_result  = document.getElementById('search_result');
                var all_search_elems = [...document.querySelectorAll('#search_result,#search_result > *,#search_result > a > *,#search_result > a > .search_res > *, #search_result > a > .search_res > .sImage, #search_result > a > .search_res > .sImage > image, #search_result > a > .search_res > .sBio, #search_result > a > .search_res > .sBio, #search_result > a > .search_res > .sBio > sFullName, #search_result > a > .search_res > .sUsername,.search_bar,.search_input')];
                if(all_search_elems.every((el)=>{
                    return el !== e.toElement;
                }) == true){
                    search_result.parentNode.removeChild(search_result);
                    document.getElementById('search_input').value = "";
                }
                }
            },true);

    </script>
</header>
<!-- nav header end-->

<!-- notification box -->
<div id="userBox">
        <div style="width:100%;height:100%;min-height:30px;display:flex;place-items:center !important;text-align:center;">
            <img src="assets/images/icons/loading.gif" width="50" height="50" alt="">
        </div>
</div>