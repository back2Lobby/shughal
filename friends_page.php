<?php 
include("dbCon.php");
include('includes/header.php');
include('includes/classes/User.php');
?>

<main id="friends_page">
        <div id="search_box">
            <div class="search_bar_mobile">
                        <input class="search_input" id="search_input_mobile" placeholder="Search..." autocomplete="off"></input>
            </div>
        </div>
            <script>
            var search_input = document.getElementById('search_input_mobile');
            search_input.addEventListener('input',search_func);

            function search_func(e) {
                e.preventDefault();
                var search_input = document.getElementById('search_input_mobile');
                localStorage.setItem('search_input', search_input.value);
                if(search_input.value !== ""){
                var sVal = search_input.value;
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                            if(document.querySelector("#search_result_mobile") !== null){
                            document.querySelector("#search_result_mobile").parentNode.removeChild(document.querySelector("#search_result_mobile"));
                            document.querySelector('#search_box').innerHTML += this.responseText;
                            var search_input = document.getElementById('search_input_mobile');
                            search_input.addEventListener('keyup',search_func);
                            search_input.focus();
                            var sVal = search_input.value;
                            search_input.value = localStorage.getItem('search_input');
                            }else{
                                document.querySelector('#search_box').innerHTML += this.responseText;
                            var search_input = document.getElementById('search_input_mobile');
                            search_input.addEventListener('keyup',search_func);
                            search_input.focus();
                            var sVal = search_input.value;
                            search_input.value = localStorage.getItem('search_input');
                            }
                    }
                };
                xhttp.open("POST","includes/handlers/search.php?disp=mobile&&sData="+search_input.value, true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send();
                var search_input = document.getElementById('search_input_mobile');
                            search_input.addEventListener('keyup',search_func);
                            search_input.focus();
                            search_input.value = localStorage.getItem('search_input');
                }else{
                    document.querySelector("#search_result_mobile").parentNode.removeChild(document.querySelector("#search_"));
                }
            }
            document.body.addEventListener('click',(e)=>{
                        if(document.getElementById('search_result_mobile') !== null){
                        var search_result  = document.getElementById('search_result_mobile');
                        var all_search_elems = [...document.querySelectorAll('#search_result_mobile,#search_result_mobile > *,#search_result_mobile > a > *,#search_result_mobile > a > .search_res > *, #search_result_mobile > a > .search_res > .sImage, #search_result_mobile > a > .search_res > .sImage > image, #search_result_mobile > a > .search_res > .sBio, #search_result_mobile > a > .search_res > .sBio, #search_result_mobile > a > .search_res > .sBio > sFullName, #search_result_mobile > a > .search_res > .sUsername,.search_bar_mobile,.search_input')];
                        if(all_search_elems.every((el)=>{
                            return el !== e.toElement;
                        }) == true){
                            search_result.parentNode.removeChild(search_result);
                            document.getElementById('search_input_mobile').value = "";
                        }
                        }
                    },true);

            </script>
    <section id="friends_container">
        <div class="myFriends">
            <h3>Your Friends</h3>
            <hr style="margin-top:0;">
            <div class="friends_box">

            </div>
            <script>
                fetch("includes/handlers/friends.php?friends_list=yes").then(res => res.text().then(data =>{
                    document.querySelector('.friends_box').innerHTML = data;
                }));
                fetch("includes/handlers/friends.php?random_people=yes").then(res => res.text().then(data =>{
                    document.querySelector('.RandompeopleBox').innerHTML = data;
                }));
            </script>
        </div>
        <div class="people_you_may_know">
        <h3>People You May Know</h3>
            <hr style="margin-top:0;">
            <div class="RandompeopleBox">

            </div>
        </div>
    </section>
</main>
<script>
//menu underline animation
document.querySelector('.myNavItem1').classList.remove('active');
document.querySelector('.myNavItem0').classList.remove('active');
document.querySelector('.myNavItem2').classList.remove('active');
document.querySelector('.myNavItem5').classList.remove('active');
document.querySelector('.myNavItem4').classList.add('active');
</script>


<?php
include('includes/footer.php');
?>