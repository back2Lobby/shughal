    
<script>
    window.onload = function(){
//this adds the name to the navbar menus on small screens
function mediaFunc(x){
    if(x.matches){
        //For Home
        var text = document.createTextNode(" Home");
        document.querySelector('.myNavItem1 > a').appendChild(text);
        //For Inbox
        var text = document.createTextNode(" Inbox");
        document.querySelector('.myNavItem2 > a').appendChild(text);
        //For Notifications
        var text = document.createTextNode(" Notification");
        document.querySelector('.myNavItem3 > a').appendChild(text);
        //For Friends Requests
        var text = document.createTextNode(" People & Friends");
        document.querySelector('.myNavItem4 > a').appendChild(text);
        //For Settings
        var text = document.createTextNode(" Settings");
        document.querySelector('.myNavItem5 > a').appendChild(text);
        //For Logout
        var text = document.createTextNode(" Logout");
        document.querySelector('.myNavItem6 > a').appendChild(text);

    if(document.querySelector('.meAndOnlineFriends') !== null){
        // for the left panel and right panel animation
        var leftPanel = document.querySelector('.meAndOnlineFriends');
        var rightPanel = document.querySelector('.popularOnShughal');
        var user_btn = document.querySelector('.user_btn');
        var home_btn = document.querySelector('.home_btn');
        var trend_btn = document.querySelector('.trend_btn');
        user_btn.addEventListener('click', function(){
            leftPanel.classList.add('leftAnim');
            leftPanel.classList.remove('leftAnimBack');
            if(rightPanel.className.includes('rightAnim')){
                rightPanel.classList.remove('rightAnim');
                rightPanel.classList.add('rightAnimBack');
            }
            setTimeout(function(){
            rightPanel.classList.remove('rightAnimBack');
        },1000);
        });
        trend_btn.addEventListener('click', function(){
            rightPanel.classList.add('rightAnim');
            leftPanel.classList.remove('rightAnimBack');
            if(leftPanel.className.includes('leftAnim')){
                leftPanel.classList.remove('leftAnim');
                leftPanel.classList.add('leftAnimBack');
            }
            setTimeout(function(){
            leftPanel.classList.remove('leftAnimBack');
        },1000);
        });
        home_btn.addEventListener('click',function(){
            if(rightPanel.className.includes('rightAnim')){
                rightPanel.classList.remove('rightAnim');
                rightPanel.classList.add('rightAnimBack');
            }

            if(leftPanel.className.includes('leftAnim')){
                leftPanel.classList.remove('leftAnim');
                leftPanel.classList.add('leftAnimBack');
            }
            setTimeout(function(){
            rightPanel.classList.remove('rightAnimBack');
            leftPanel.classList.remove('leftAnimBack');
        },1000);
        });
      }  
    }else{
        var navs = document.querySelectorAll('[class^="myNavItem"]');
        navs.forEach((el) => {
            el = el.firstElementChild;
            if(el.parentNode.className.includes('myNavItem0') == false){
                    el.lastChild.data = "";
            }
        });
    }
}
document.querySelector('#userBox').style.display = "none";
        //hide navbar on clicking any item
            let winCheck = window.matchMedia('(max-width:768px)');
            if(winCheck.matches == true){
            document.querySelector('.navbar-toggler').addEventListener('click',()=>{
            var all_nav_links = document.querySelector("#all_nav_links").childNodes;
                all_nav_links.forEach((el)=>{
                    el.addEventListener('click', function(){
                        document.querySelector('.navbar-toggler').click();
                    })
                });
            });
            }
    var notifBtn = document.querySelector('.myNavItem3');
    if(notifBtn.offsetHeight == 0){
        document.querySelector('.navbar-toggler').addEventListener('click',()=>{
            notifBtn.addEventListener('click',()=>{
                document.querySelector('#userBox').style.display = "block";
            });
        })
    }
    notifBtn.addEventListener('click', ()=>{
        var userBox = "";
        var tempProcess = document.querySelectorAll('body > div');
        tempProcess.forEach(el => {
            if(el.id == "userBox"){
                userBox = el;
            }
        });

        //remove new notifiction circle
        let winWidthCheck = window.matchMedia('(max-width:768px)');
                    if(winWidthCheck.matches == false){
                        if(document.querySelector('.myNavItem3 > span.new_notif') !== null){
                            document.querySelector('.myNavItem3').removeChild(document.querySelector('.myNavItem3 > span.new_notif'));
                        }
                    }else{
                        if(document.querySelector('.myNavItem3 > span.new_notif_left') !== null){
                            document.querySelector('.myNavItem3').removeChild(document.querySelector('.myNavItem3 > span.new_notif_left'));
                        }
                        if(document.querySelector('.navbar-toggler-icon > span.new_notif') !== null){
                        document.querySelector('.navbar-toggler-icon').removeChild(document.querySelector('.navbar-toggler-icon > span.new_notif'));
                        }
                    }

        // notifications fetch
        if(userBox.style.display == "none"){
            document.querySelector('#userBox').style.display = "block";
            fetch("includes/handlers/notifications.php?userLoggedIn=<?php echo $userLoggedIn;?>").then(res => {
                res.text().then(data => {
                    userBox.innerHTML = data;
                    if(data == ""){
                        userBox.innerHTML = "No New Notifications Found For You";
                    }
                });
            });

            <?php 
            if(isset($profile_username)){
            ?>

            //check if the current profile page is friend or not
            if(document.querySelector('#profile_page') !== null){
                fetch('includes/handlers/friends.php?call=checkfriend&&profiletarget=<?php if(isset($profile_username)){echo $profile_username;}?>').then(res => res.text().then(data => {
                    if("true" == data){
                        document.querySelector('.friend_option').innerHTML = "Friends <i class='fas fa-user-check'></i>"
                    }
                }));
            }

        <?php }?>

            //on focus out set display to none
            document.body.addEventListener('click',(e)=>{
                var all_user_elems = [...document.querySelectorAll('#userBox,#userBox > *,#userBox > * > *,#userBox > * > * > *,#userBox > * > * > * > *,#userBox > * > * > * > * > *,.myNavItem3,.myNavItem3>*,.myNavItem3>*>*,.myNavItem3>*>*>*')];
                if(all_user_elems.every((el)=>{
                    return el !== e.toElement;
                }) == true){
                    userBox.style.display = "none";
                }
            },true);


            //notification delete
            setTimeout(()=>{
                if(document.querySelectorAll('.notif_del') !== null){                    
                    var notif_all = document.querySelectorAll('.notif_del');
                    notif_all.forEach((singleN)=>{
                        singleN.addEventListener('click',()=>{
                            var currentId = singleN.id;
                            currentId = currentId.replace("notif_del","");
                            fetch(`includes/handlers/del_notifs.php?delNotif=${currentId}`);
                            if(singleN.parentNode.parentNode.childElementCount > 2){
                                //remove hr and the notification
                            singleN.parentNode.parentNode.removeChild(document.querySelector(`#notification${currentId}`).nextElementSibling);
                            singleN.parentNode.parentNode.removeChild(document.querySelector(`#notification${currentId}`));
                            }else{
                                singleN.parentNode.parentNode.innerHTML = "No New Notifications Found For You";
                            }
                        });
                    });
                }
            },2000);
            }else if(userBox.style.display == "block"){
            document.querySelector('#userBox').style.display = "none";
        }
    });

//check new notification on startup
if(userBox.style.display == "none"){
        fetch("includes/handlers/notifications.php?get_new_notif=yes&&startup=true").then(res => {
            res.text().then(data => {
                if(data == "new notifications found"){
                    let winQuery = window.matchMedia('(max-width:768px)');
                    if(winQuery.matches == false){
                        if(document.querySelector('.myNavItem3 > span.new_notif') == null){
                        document.querySelector('.myNavItem3').innerHTML += '<span class="new_notif"><i class="fas fa-circle"></i></span>';
                        }
                    }else{
                        if(document.querySelector('.navbar-toggler-icon > span.new_notif') == null){
                        document.querySelector('.navbar-toggler-icon').innerHTML += '<span class="new_notif"><i class="fas fa-circle"></i></span>';
                        }
                        if(document.querySelector('.myNavItem3 > span.new_notif_left') == null){
                        document.querySelector('.myNavItem3').innerHTML += '<span class="new_notif_left"><i class="fas fa-circle"></i></span>';
                        }
                    }
                }
            });
        });
    }
//check new notifications after every 30 seconds
setInterval(()=>{
    if(userBox.style.display == "none"){
        fetch("includes/handlers/notifications.php?get_new_notif=yes").then(res => {
            res.text().then(data => {
                if(data == "new notifications found"){
                    let winQuery = window.matchMedia('(max-width:768px)');
                    if(winQuery.matches == false){
                        if(document.querySelector('.myNavItem3 > span.new_notif') == null){
                        document.querySelector('.myNavItem3').innerHTML += '<span class="new_notif"><i class="fas fa-circle"></i></span>';
                        }
                                    <?php 
                        if(isset($profile_username)){
                        ?>
                        //check if the current profile page is friend or not
                        if(document.querySelector('#profile_page') !== null){
                            fetch('includes/handlers/friends.php?call=check_friend&&profile_target=<?php echo $profile_username;?>').then(res => res.text().then(data => {
                                if("true" == data){
                                    document.querySelector('.friend_option').innerHTML = "Friends <i class='fas fa-user-check'></i>"
                                }
                            }))
                        }
                        
                     <?php }?>
                    }else{
                        if(document.querySelector('.navbar-toggler-icon > span.new_notif') == null){
                        document.querySelector('.navbar-toggler-icon').innerHTML += '<span class="new_notif"><i class="fas fa-circle"></i></span>';
                        }
                        if(document.querySelector('.myNavItem3 > span.new_notif_left') == null){
                        document.querySelector('.myNavItem3').innerHTML += '<span class="new_notif_left"><i class="fas fa-circle"></i></span>';
                        }
                                    <?php 
                        if(isset($profile_username)){
                        ?>
                        //check if the current profile page is friend or not
                        if(document.querySelector('#profile_page') !== null){
                            fetch('includes/handlers/friends.php?call=check_friend&&profile_target=<?php echo $profile_username;?>').then(res => res.text().then(data => {
                                if("true" == data){
                                    document.querySelector('.friend_option').innerHTML = "Friends <i class='fas fa-user-check'></i>"
                                }
                            }))
                        }
                        <?php }?>
                    }
                }
            });
        });
    }
},30000);




var winMedia = window.matchMedia("(max-width:768px)");
mediaFunc(winMedia); //calls the function at runtime
winMedia.addEventListener('change',mediaFunc);//runs when stat changes

setTimeout(()=>{
    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() == $(document).height()) {
            var profiler = location.pathname.substring(location.pathname.lastIndexOf('/')+1);
                if(profiler !== "shughal" && profiler !== "Shughal"){
                    if(typeof throttle !== "undefined")
                    throttle(ScrollBodyHeight,100);
                }else{
                    if(localStorage.getItem("timeThrottle") !== null){localStorage.removeItem("timeThrottle");}
                }
        }
    });
},1000);

function ScrollBodyHeight(){
    if(document.querySelector('#profile_iframe_id') !== null)
        document.querySelector('#profile_iframe_id').contentWindow.postMessage({isscrolled:"yes"},"*");

}

//avoid resubmission
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
} 

    window.addEventListener('message',(e)=>{
        document.getElementsByName(`${e.data.winName}`)[0].height = e.data.winHeight + 10;
    });
}

///////////////////////below is practice text , it have to be deleted

</script>
</body>
</html>