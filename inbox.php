<?php 
include("dbCon.php");
include('includes/header.php');
include('includes/classes/User.php');
?>

<main id="inbox_page">
    <section id="inbox_container">
        <div class="friends_inbox_side">
            <div class="friends_part">
            <div class="friends_status inbox_friends_status">
                            <span style='text-align:center;font-size:1.4em;'>Friends</span>
                            <hr>
                        </div>
                        <script>
                        function inbox_friends_status_func(){
                            fetch("includes/handlers/friends_status.php?call=get_friends_status&&inbox_friends=yes").then(res=>{
                                res.text().then(data =>{
                                    if(document.querySelector('.friends_status') !== null){
                                    document.querySelector('.friends_status').innerHTML = "<span style='text-align:center;'>Friends</span><hr>" + data;
                                    }
                                });
                            });
                        // set the event listener again
                        var f_statuses = document.querySelectorAll('.f_status');
                        f_statuses.forEach((el)=>{
                            el.addEventListener('click',()=>{
                                //makes the hidden value to the current friend
                                document.querySelector('#friend_agent').value = el.lastElementChild.value;
                                //main request + response
                                fetch("includes/handlers/messages.php?friend_name="+el.lastElementChild.value).then(res => res.text().then(data => {
                                    //change the conversation title to the current friend name
                                    document.querySelector('.conversation > h3').innerText = "";
                                    $(el.firstElementChild).clone().appendTo(".conversation > h3");
                                    $(el.firstElementChild.nextElementSibling).clone().appendTo(".conversation > h3");
                                }));
                            })
                        });
                        //event listener end
                        }
                        inbox_friends_status_func();
                        setInterval(()=>{
                            inbox_friends_status_func();
                            },8000);
                        </script>
            </div>
        </div>
        <div class="conversation">
        <h3>Conversations</h3>
            <hr style="margin-top:0;">
            <div class="peopleBox">
                <div style='width:100%;margin:auto;height:100%;min-height:40vh !important; display:flex;justify-content:center;align-items:center;'>
                    <h3>No Messages To Show</h3>
                </div>
            </div>
            <hr>
            <div class="message_post_box">
                    <textarea id='conv-input' name='conv-input' maxlength='280' type='textarea'></textarea>
                    <input type="hidden" id='friend_agent' name="friend_username" value="none">
                    <button type="submit" class="btn btn-warning messageBtn" value="Send" name="submitMessage">Send</button>
            </div>
            <script>
                
                document.querySelector("#upload_image_post_btn").addEventListener("click",()=>{
                document.querySelector('#upload_image_post').click();
                var mytempInterval = setInterval(()=>{
                    if(document.querySelector("#upload_image_post").value !== ""){
                        document.querySelector("#upload_image_post_btn").style.color = "lightblue";
                        clearInterval(mytempInterval);
                    }
                },600);
                setTimeout(()=>{
                    clearInterval(mytempInterval);
                   },18000);
                });
                </script>
            </script>
        </div>
    </section>
</main>

<script>

//menu underline animation
document.querySelector('.myNavItem0').classList.remove('active');
document.querySelector('.myNavItem1').classList.remove('active');
document.querySelector('.myNavItem4').classList.remove('active');
document.querySelector('.myNavItem5').classList.remove('active');
document.querySelector('.myNavItem2').classList.add('active');

setTimeout(()=>{
var f_statuses = document.querySelectorAll('.f_status');
f_statuses.forEach((el)=>{
    el.addEventListener('click',()=>{
        //makes the hidden value to the current friend
        document.querySelector('#friend_agent').value = el.lastElementChild.value;
        //main request + response
        document.querySelector('.peopleBox').innerHTML = "<div style='width:100%;margin:auto;height:100%;min-height:40vh !important; display:flex;justify-content:center;align-items:center;'><img src='assets/images/icons/loading.gif' alt='Messages are loading ...'></div>";
        fetch("includes/handlers/messages.php?call=get_all_messages&&friend_name="+el.lastElementChild.value).then(res => res.text().then(data => {
            document.querySelector('.message_post_box').style.display = "block";
            document.querySelector('.peopleBox').innerHTML = data;
            //change the conversation title to the current friend name
            document.querySelector('.conversation > h3').innerText = "";
            $(el.firstElementChild).clone().appendTo(".conversation > h3");
            $(el.firstElementChild.nextElementSibling).clone().appendTo(".conversation > h3");
            //adjust the height of messages
            var message_box_carry = document.querySelectorAll(".msg_carry");
            message_box_carry.forEach(el => {
                el.style.height = el.scrollHeight + "px";
            });


            if(message_updates !== null){
                clearInterval(message_updates);
            setTimeout(()=>{
                //check for new messages after every 3 seconds
                var message_updates = setInterval(()=>{
                    
                    var date_stamp = document.querySelectorAll('.date_stamp');
                    if(date_stamp.length > 0){
                        
                        date_stamp = date_stamp[date_stamp.length-1];
                    }

                fetch("includes/handlers/messages.php?new_friend_messages=yes&&last_date="+date_stamp.innerText+"&&friend_name="+document.getElementById('friend_agent').value).then(res => res.text().then(data => {
                    if("no new message" !== data){
                    var peopleBox = document.querySelector('.peopleBox');
                    var beforeHeight = peopleBox.scrollHeight;
                    document.querySelector('.peopleBox').innerHTML += data;
                        //scroll to bottom of div on new message
                        var autoScrollHack = setInterval(()=>{
                            if(peopleBox.scrollHeight > beforeHeight){
                            peopleBox.scrollTop = peopleBox.scrollHeight;

                            //adjust the height of message
                            var message_box_carry = document.querySelectorAll(".msg_carry");
                                message_box_carry.forEach(el => {
                                    el.style.height = el.scrollHeight + "px";
                                });

                            clearInterval(autoScrollHack);
                            }
                        },1000);
                        }
                }));
                },1000);
            },2000);
            }
        }));
    })
})
},800);
//on message send/post btn click
var messageBtn = document.querySelector('.messageBtn').addEventListener('click',()=>{

    var upload_image_message = document.querySelector('#upload_image_post');
    fetch("includes/handlers/messages.php?call=message_post&&friend_name="+document.getElementById('friend_agent').value+"&&message_body="+document.getElementById('conv-input').value).then(res => res.text().then(data => {
        //reset the textarea
        document.querySelector('#conv-input').value = "";
    }))
});

//check for new messages after every 3 seconds
if(document.getElementById('friend_agent').value !== "none"){

    var message_updates = setInterval(()=>{
    var date_stamp = document.querySelectorAll('.date_stamp');
    if(date_stamp.length > 0){
    
        date_stamp = date_stamp[date_stamp.length-1];
    }

    fetch("includes/handlers/messages.php?new_friend_messages=yes&&last_date="+date_stamp.innerText+"&&friend_name="+document.getElementById('friend_agent').value).then(res => res.text().then(data => {
        if("no new message" !== data){
        var peopleBox = document.querySelector('.peopleBox');
        var beforeHeight = peopleBox.scrollHeight;
        document.querySelector('.peopleBox').innerHTML += data;

        //scroll to bottom of div on new message
        var autoScrollHack = setInterval(()=>{
                            if(peopleBox.scrollHeight > beforeHeight){
                            peopleBox.scrollTop = peopleBox.scrollHeight;

                            //adjust the height of message
                            var message_box_carry = document.querySelectorAll(".msg_carry");
                                message_box_carry.forEach(el => {
                                    el.style.height = el.scrollHeight + "px";
                                });

                            clearInterval(autoScrollHack);
                            }
                        },1000);
        }
    }));
    },1000);
}




























</script>


<?php include("includes/footer.php");?>