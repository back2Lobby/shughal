<?php 
include("dbCon.php");
include('includes/header.php');
include('includes/classes/User.php');

$user_obj = new User($con,$userLoggedIn);
$profile_pic = $user_obj->getProfilePic();
?>
    <main id="settings_container">
        <div id="profile_settings">
            <h3>Profile Settings</h3>
            <hr style="margin-top:0;">
            <form id='profile_settings_main'>
             <div class='form-group' id="profile_pic_upload" style='text-align:left;' title="Change Profile Picture">
                <span id="pic_camera" onClick="document.querySelector('#upload_image').click();">
                    <i class="fas fa-camera"></i>
                </span>
                <img src="<?php if(isset($profile_pic)){echo $profile_pic;}?>" alt="" id='uploaded_image' style="margin-left:12px;border-radius:50%;">
              </div>  
              <div class='form-group' id='first_name_set'>
                <label for="first_name_setting">First Name : </label>
                <input type="text" class="form-control" name="first_name" id="first_name_setting" autocomplete="off" value="<?php if(isset($result[0]['first_name'])) echo $result[0]['first_name'];?>">
              </div>
              <div class='form-group' id='first_name_set'>
                <label for="last_name_setting">Last Name : </label>
                <input type="text" class="form-control" name="last_name" id="last_name_setting" autocomplete="off" value="<?php if(isset($result[0]['last_name'])) echo $result[0]['last_name'];?>">
              </div>
              <div class='form-group' id='email_1'>
                <label for="email_setting">Email Address: </label>
                <input type="email" class="form-control" name="last_name" id="email_setting" autocomplete="off" value="<?php if(isset($result[0]['email'])) echo $result[0]['email'];?>">
              </div>
              <button type="button" class="btn btn-warning" id="update-profile-btn">Update</button>

              <div class="form-group profilemessage">
                  
              </div>

                <!-- upload image -->
                <input type="file" name="upload_image" id="upload_image" style="width:0px !important;height:0px !important;">
            </form>
            <div id="uploadimageModal" class="modal" role="dialog">
                <div class="modal-dialog">
                <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">Upload & Crop Image</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                        <div class="row">
                    <div class="col-md-8 text-center">
                        <div id="image_demo" style="width:350px; margin-top:30px"></div>
                    </div>
                    <div class="col-md-4" style="padding-top:30px;">
                        <br />
                        <br />
                        <br/>
                        <button class="btn btn-success crop_image">Crop & Upload Image</button>
                    </div>
                    </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    </div>
            </div>

        </div>



        <div id="other_settings">
            <h3>Other Settings</h3>
            <hr style="margin-top:0;">
            <form id="other_settings_main">
                <div style="display:flex;justify-content:center;align-items:center;color:white;">
                    <small>You Can Change Your Password From Here</small>
                </div>
                <hr style="width:50%;">
                <div class="form-group">
                    <label for="oldpasswordsetting">Old Password :</label>
                    <input type="password" name="oldpasswordsetting" id="oldpasswordsetting" class="form-control" placeholder="Enter Your Old Password Here">
                </div>
                <div class="form-group">
                    <label for="newpasswordsetting1">New Password :</label>
                    <input type="password" name="newpasswordsetting1" id="newpasswordsetting1" class="form-control" placeholder="Enter Your New Password Here">
                </div>
                <div class="form-group">
                    <label for="newpasswordsetting2">New Password :</label>
                    <input type="password" name="newpasswordsetting2" id="newpasswordsetting2" class="form-control" placeholder="Enter Your New Password Here">
                </div>
                <button type="button" class="btn btn-warning" id="pass_update_btn">Change Password</button>
                <div class="form-group passmessage">
            </form> 
        </div>
    </main>

<script>
$(document).ready(function(){
    //menu underline animation
    document.querySelector('.myNavItem0').classList.remove('active');
    document.querySelector('.myNavItem1').classList.remove('active');
    document.querySelector('.myNavItem4').classList.remove('active');
    document.querySelector('.myNavItem2').classList.remove('active');
    document.querySelector('.myNavItem5').classList.add('active');



    $image_crop = $('#image_demo').croppie({
        enableExif:true,
        viewport:{
            width:126,
            height:126,
            type:'square'
        },
        boundary:{
            width:300,
            height:300
        }
    });

    $("#upload_image").on('change',function(){
        var reader = new FileReader();
        reader.onload = function(event){
            $image_crop.croppie('bind',{
                url:event.target.result
            }).then(function(){
                console.log("jQuery Bind complete");
            })
        }
        reader.readAsDataURL(this.files[0]);
        $('#uploadimageModal').modal('show');
    });

    $('.crop_image').click(function(event){
        $image_crop.croppie('result',{
            type:'canvas',
            size:'viewport'
        }).then(function(response){
            $.ajax({
                url:"includes/handlers/settings_process.php",
                type:"POST",
                data:{"image":response},
                success:function(data){
                    $('#uploadimageModal').modal('hide');
                    document.querySelector('#uploaded_image').src = data;
                }
            })
        });
    })

    var profile_update = document.querySelector('#update-profile-btn');
    profile_update.addEventListener('click',()=>{
        first_name = document.querySelector('#first_name_setting').value;
        last_name = document.querySelector('#last_name_setting').value;
        email_addr = document.querySelector('#email_setting').value;
        if("<?php echo $result[0]['first_name'];?>" !== first_name || "<?php echo $result[0]['last_name'];?>" !== last_name || "<?php echo $result[0]['email'];?>" !== email_addr){
            fetch("includes/handlers/settings_process.php?first_name="+first_name+"&last_name="+last_name+"&email="+email_addr).then(res => res.text().then(data => {
                if("profile updated" == data){
                    document.querySelector('.profilemessage').style.display = "flex";
                    document.querySelector('.profilemessage').classList.add("bg-success");
                    document.querySelector('.profilemessage').innerHTML = "Updated Successfully";
                    setTimeout(function() {
                    document.querySelector('.profilemessage').style.display = "none";
                    document.querySelector('.profilemessage').classList.remove("bg-success");
                    },3000);   
                }else if("Email Already Exists" == data){
                        document.querySelector('.profilemessage').style.display = "flex";
                    document.querySelector('.profilemessage').classList.add("bg-danger");
                    document.querySelector('.profilemessage').innerHTML = "Email Already Exists"; 
                    setTimeout(function() {
                    document.querySelector('.profilemessage').style.display = "none";
                    document.querySelector('.profilemessage').classList.remove("bg-danger");
                    },3000); 
                }else{
                    document.querySelector('.profilemessage').style.display = "flex";
                    document.querySelector('.profilemessage').classList.add("bg-danger");
                    document.querySelector('.profilemessage').innerHTML = "An Error Occured, Please Try Again Later"; 
                    setTimeout(function() {
                    document.querySelector('.profilemessage').style.display = "none";
                    document.querySelector('.profilemessage').classList.remove("bg-danger");
                    },3000);   
                }           
            }));
        }else{
            document.querySelector('.profilemessage').style.display = "flex";
            document.querySelector('.profilemessage').classList.add("bg-info");
            document.querySelector('.profilemessage').innerHTML = "Nothing To Update";
            setTimeout(function() {
                document.querySelector('.profilemessage').style.display = "none";
            document.querySelector('.profilemessage').classList.remove("bg-info");
            },3000);
        }
    });

    var pass_update_btn = document.querySelector('#pass_update_btn');
    pass_update_btn.addEventListener('click', ()=>{
        var oldpass = document.querySelector('#oldpasswordsetting').value;
        var newpass1 = document.querySelector('#newpasswordsetting1').value;
        var newpass2 = document.querySelector('#newpasswordsetting2').value;
        if(oldpass !== "" && newpass1 !== "" && newpass2 !== ""){
            if(oldpass !== newpass1 && oldpass !== newpass2){

                if(newpass1 == newpass2){

                    fetch("includes/handlers/settings_process.php?oldpass="+oldpass+"&&newpass1="+newpass1+"&&newpass2="+newpass2).then(res => res.text().then(data => {
                        if("Password Updated" == data){
                            document.querySelector('.passmessage').style.display = "flex";
                            document.querySelector('.passmessage').classList.add("bg-success");
                            document.querySelector('.passmessage').innerHTML = "Password Updated Successfully";

                            //reset form values
                            oldpass = "";
                            newpass1 = "";
                            newpass2 = "";

                            setTimeout(function() {
                                document.querySelector('.passmessage').style.display = "none";
                                document.querySelector('.passmessage').classList.remove("bg-success");
                            },3000);
                        }else{
                            document.querySelector('.passmessage').style.display = "flex";
                            document.querySelector('.passmessage').classList.add("bg-danger");
                            document.querySelector('.passmessage').innerHTML = "Error While Updating Password";
                            setTimeout(function() {
                                document.querySelector('.passmessage').style.display = "none";
                                document.querySelector('.passmessage').classList.remove("bg-danger");
                            },3000);
                        }
                    }));

                }else{
                    document.querySelector('.passmessage').style.display = "flex";
                    document.querySelector('.passmessage').classList.add("bg-info");
                    document.querySelector('.passmessage').innerHTML = "New Passwords Does Not Match";
                    setTimeout(function() {
                        document.querySelector('.passmessage').style.display = "none";
                        document.querySelector('.passmessage').classList.remove("bg-info");
                    },3000);
                }

            }else{
                document.querySelector('.passmessage').style.display = "flex";
                document.querySelector('.passmessage').classList.add("bg-info");
                document.querySelector('.passmessage').innerHTML = "New Password Can Not Be Same As Old Password";
                setTimeout(function() {
                    document.querySelector('.passmessage').style.display = "none";
                document.querySelector('.passmessage').classList.remove("bg-info");
                },3000);
            }

        }else{
            document.querySelector('.passmessage').style.display = "flex";
            document.querySelector('.passmessage').classList.add("bg-info");
            document.querySelector('.passmessage').innerHTML = "Password Field Cannot Be Empty";
            setTimeout(function() {
                document.querySelector('.passmessage').style.display = "none";
            document.querySelector('.passmessage').classList.remove("bg-info");
            },3000);
        }
    });

});
</script>



<?php include("includes/footer.php");?>
