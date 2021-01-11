<?php
require_once('global.php');

require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/function.php");


if(isset($_SESSION["UserID"]) && !empty($_SESSION["UserID"])){
    header("Location: dashboard.php");die;
}

if(isset($_GET['find_user'])){
    if($_POST['usertype']=='subscriber'){
        $query = $db->prepare("SELECT * FROM `users` WHERE `usertype` ='subscriber' and email=:email");
        $query->bindValue(":email",$_POST['email']);
    }else{
        $query = $db->prepare("SELECT * FROM `users` WHERE `usertype` ='employee' and email=:email and adminid=:adminid");
        $query->bindValue(":email",$_POST['email']);
        $query->bindValue(":adminid",$_POST['subscriber']);
    }
    
    $query->execute();
    
    if($query->rowCount()>0){
        $result=$query->fetch();
        echo json_encode(['response'=>$result]);die;
    }else{
        echo json_encode(['error'=>'Now such user found, please try again']);die;
    }

}


?>
<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from eliteadmin.themedesigner.in/demos/bt4/material/pages-register2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 07 May 2018 05:33:17 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url; ?>/assets/images/favicon.png">
    <title>
        MySunLess
    </title>
    <!-- page css -->

    <link href="<?php echo base_url; ?>/assets/mystyle.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url; ?>/dist/css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url; ?>/assets/css/bootstrap-datetimepicker.min.css">
    <link href="<?php echo base_url; ?>/assets/custom-bootstrap.css" rel="stylesheet">

    <link href="<?php echo base_url; ?>/assets/css/dropify.min.css" rel="stylesheet">
    <link href="<?php echo base_url ?>/assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@9.10.9/dist/sweetalert2.css">
    <link rel='stylesheet' type='text/css'href='<?php echo base_url ?>/assets/css/timepicki.css' />
    <link href="<?php echo  base_url ?>/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">

</head>

<body>
    <style>
        #wrapper {
          width: 100%;
      }
      .timeinput{
        width: 50%;
    }

    .register-box {
      max-width: 600px;
      margin: 0 auto;
      padding-top: 2%; 
  }

  .register-box a {
    word-break: break-word;
    display: block; 
}

.step-register {
  position: absolute;
  height: 100%; 
}

@media (max-width: 767px) {
  #loginForm fieldset,#forgetpassword fieldset,
  .login-register,
  .step-register {
    position: relative; 
} 

.timeinput{
    width: 100%;
}
}

#loginForm,#forgetpassword {
    max-width: 600px;
    margin: 20px auto;
    text-align: center;
    position: relative;
}

#loginForm fieldset, #forgetpassword fieldset {
    background: white;
    border: 0 none;
    border-radius: 3px;
    box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.1);
    padding: 20px 30px;
    box-sizing: border-box;
    width: 90%;
    margin: 0 5%;
}

#loginForm fieldset:not(:first-of-type),#forgetpassword fieldset:not(:first-of-type){
    display: none;
}

#loginForm input, #loginForm textarea, #loginForm .select2-container,#forgetpassword input {
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 3px;
    margin-bottom: 18px;
    width: 100%;
    box-sizing: border-box;
    text-align: left;
    color: #2C3E50;
    font-size: 13px;
}

#loginForm .action-button {
    width: 100px;
    text-align: center;
    background: #03a9f3;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 1px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px;
}

#loginForm .action-button:hover, #loginForm .action-button:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px #01c0c8;
}

.fs-title {
    font-size: 18px;
    text-transform: uppercase;
    color: #2C3E50;
    margin-bottom: 10px;
}

.fs-subtitle {
    font-weight: normal;
    font-size: 13px;
    color: #666;
    margin-bottom: 20px;
}

#eliteregister {
    margin-bottom: 30px;
    overflow: hidden;
    padding-left: 0px;
    counter-reset: step;
}

#eliteregister li {
    list-style-type: none;
    color: #686868;
    font-size: 13px;
    width: 24.33%;
    float: left;
    position: relative;
}

#eliteregister li:before {
    content: counter(step);
    counter-increment: step;
    width: 40px;
    line-height: 40px;
    display: block;
    font-size: 10px;
    color: #fff;
    background: #686868;
    border-radius: 100%;
    position: relative;
    z-index: 10;
    margin: 0 auto 5px auto;
}

#eliteregister li:after {
    content: '';
    width: 100%;
    height: 2px;
    background:#dadada;
    position: absolute;
    left: -50%;
    top: 19px;
    z-index:1;
}

#eliteregister li:first-child:after {
    content: none;
}

#eliteregister li.active:before, #eliteregister li.active:after {
    background: #03a9f3;
    color: #fff;
}

.disabled{
    background-color: #b0c2ca!important;
}

#loginForm input.fieldinvalid, #loginForm textarea.fieldinvalid, #forgetpassword input.fieldinvalid, #forgetpassword textarea.fieldinvalid
{
    background-color: #f443365c;
    border-color: red;
}

label.fieldinvalid{
    color: red;
}

.blob {
    height: 50px;
    width: 50px;
    color: #ffcc00;
    position: absolute;
    top: 45%;
    left: 45%;
    z-index: 1;
    font-size: 30px;
    display: none;  
}

.timeinput span.form-colon{
    transform: translate(36px, -60px)!important;
}

#password_block{
    position: relative;
}
#password_block span{
    position: absolute;
    right: 0px;
    padding: 15px;
    background: #F44336;
    color: white;
    cursor: pointer;
    border-radius: 0px 3px 3px 0px;
}



.load-wrapp {
    width: 100%;
    margin: 5px 0;
    border-radius: 5px;
    text-align: center;
    background-color: #d8d8d8;
}

.load-3{
    padding: 8px 16px;
    line-height: 2;
}

.line {
    display: inline-block;
    width: 15px;
    height: 15px;
    border-radius: 15px;
    background-color: #4b9cdb;
}

.load-3 .line:nth-last-child(1) {animation: loadingC .6s .1s linear infinite;}
.load-3 .line:nth-last-child(2) {animation: loadingC .6s .2s linear infinite;}
.load-3 .line:nth-last-child(3) {animation: loadingC .6s .3s linear infinite;}

@keyframes loadingC {
    0 {transform: translate(0,0);}
    50% {transform: translate(0,15px);}
    100% {transform: translate(0,0);}
}

.next, .previous{
    cursor: pointer;
}
#SubscriberList{
    display: none;
}
.select2-container {
    width: 100%!important;
}
#clientimage {
    height: 150px;
    width: 150px;
    padding: 2px;
    border: 3px outset #607D8B;
    border-radius: 50%;
    object-fit: cover;
}

.save_user_image {
    height: 40px;
    width: 40px;
    object-fit: contain;
    border-radius: 100%;
    margin: 0 10px;
}

.save_user_body{
    display: flex;
    justify-content: center;
    align-items: center;
    background: aliceblue;
    padding: 6px 40px;
    border-radius: 35px;
    margin-bottom: 10px;
}

.save_user_body:hover{
    background: #e4e4e4;
}

.save_card_username:hover{
    font-weight: 500;
    cursor: pointer;
}

.save_user_delete{
    padding: 4px 9px;
    border-radius: 100%;
}

.save_user_delete:hover{
    background: #403f3f;
    color: white;
}

.separation{
    position: relative;
    top: -26px;
    font-size: 16px;
    line-height: 1px;
    background: #eaeaea;
    border-radius: 100%;
    padding: 10px;
    color: #909090;
    font-weight: 600;
}

</style>



<section id="wrapper" class="step-register">
    <div class="register-box">
        <div class="">

            <a href="javascript:void(0)" id="sunless_logo" class="text-center m-b-40">
                <?php
                $stmt = $db->prepare("SELECT Maintenance FROM `users` where usertype='Admin' ");
                $stmt->execute();
                $MaintenanceResult = $stmt->fetch();
                if($MaintenanceResult['Maintenance']==1){
                    echo '<img src="'.base_url.'/assets/images/Maintenance.jpg" width="20%" alt="">';
                }else{
                    echo '<img src="'.base_url.'/assets/images/mysunless_logo.png" width="20%" alt="">';
                }
                ?>
            </a>

                <form id="loginForm" enctype="multipart/form-data" method="post">
                    <fieldset id="login_form">
                        <h2 class="fs-title">Login your account</h2>

                        <input type="text" id="UserName"  name="UserName" placeholder="Email/Username" autocomplete="nope" value="<?php if(isset($_COOKIE["userName"])) { echo $_COOKIE["userName"]; } ?>" >

                        <div id="password_block">
                            <span>
                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                            </span>
                            <input type="password" id="password" name="password" placeholder="Password" autocomplete="nope" value="<?php if(isset($_COOKIE["password"])) { echo $_COOKIE["password"]; } ?>" >
                        </div>

                        <div class="pull-left">
                            <input style="width: auto;" type="checkbox" name="RememberUser" id="customCheck1" <?php if(isset($_COOKIE["userName"])) { ?> checked <?php } ?> >
                            <label for="customCheck1">Remember me</label>
                        </div>

                        <label class="text-dark pull-right next"><i class="fa fa-lock m-r-5"></i>Forgot Password?</label>
                        <br>

                        <button class="btn btn-info btn-block text-uppercase btn-rounded myclassbtn" type="submit">Log In</button>

                        <div class="load-wrapp submit_loader" style="display: none;">
                            <div class="load-3">
                                <div class="line"></div>
                                <div class="line"></div>
                                <div class="line"></div>
                            </div>
                        </div>
                        <br>

                        <?php 
                        if(isset($_COOKIE['REMEMBERSAVEDUSERS']) && isset($_COOKIE['RememberToken']) && $_COOKIE['RememberToken']==1){

                            $saved_users = json_decode($_COOKIE["REMEMBERSAVEDUSERS"],true);

                            $TokenID = $saved_users['TokenID'];
                            

                            $stmt = $db->prepare("Select * from users where id = (select UserId from ActiveUser where id=:id) ");
                            $stmt->bindParam(":id",$TokenID);
                            $stmt->execute();
                            $UserData = $stmt->fetch();

                            if($stmt->rowCount()>0){

                                if($UserData["userimg"]){
                                    $img = base_url."/assets/userimage/".$UserData['userimg'];
                                }else{
                                    $img = base_url."/assets/images/noimage.png";
                                }

                         ?>
                         <span class="save_user_above">
                         <hr>
                         <span class="separation">OR</span>
                        </span>
                        <div class="save_user_body">
                           <img src="<?= $img; ?>" alt="user" class="save_user_image">
                           <span class="LoginToken save_card_username" title="Login"><?= $UserData["username"]; ?></span>
                           <span type="button" class="close ml-3 save_user_delete">×</span>   
                        </div>

                            <?php }


                        }
                        ?>

                        <div class="alert alert-success" id="resonse" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$('.alert').fadeOut().delay(1000);" > <span aria-hidden="true">&times;</span> </button>
                            <h3 class="text-success">
                                <i class="fa fa-check-circle">
                                </i>
                                Success
                            </h3>
                            <p id="resonsemsg">
                            </p>
                        </div>

                        <div class="alert alert-danger" id="error" style="display: none;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$('.alert').fadeOut().delay(1000);" > <span aria-hidden="true">&times;</span> </button>
                            <h3 class="text-danger">
                                <i class="fa fa-exclamation-circle">
                                </i>
                                Errors
                            </h3>
                            <p id="errormsg">
                            </p>
                        </div>

                        <hr>
                        <label>Don't have an account?</label>
                        <a href="<?php echo base_url; ?>/Register" class="btn btn-warning btn-sm text-uppercase btn-rounded" style="display: inline!important;">Register</a>

                    </fieldset>
                </form>

                <form autocomplete="off" id="forgetpassword" method="post" style="display: none;">
                    <fieldset>
                        <h2 class="fs-title">Forget Password</h2>

                        <div class="form-group">
                          <label for="exampleSelect1">Select Usertype</label>
                          <select class="form-control" id="usertype" name="usertype">
                            <option value="subscriber">Subscriber</option>
                            <option value="employee">Employee</option>
                        </select>
                    </div>

                    <div class="form-group" id="SubscriberList">
                      <label for="exampleSelect1">Select your Subscriber</label>
                      <select class="form-control select2" id="exampleSelect1" name="subscriber">
                        <?php 
                        $query = $db->prepare("SELECT * FROM `users` WHERE `usertype` ='subscriber'");
                        $query->execute();
                        $result=$query->fetchAll();

                        foreach ($result as $key => $value) {
                            echo ' <option value="'.$value["id"].'">'.$value['username'].' - '.$value['firstname'].' '.$value['lastname'].'</option>';
                        }
                        ?>
                        <option value="subscriber">Subscriber</option>
                        <option value="employee">Employee</option>
                    </select>
                </div>

                <input class="form-control" type="email" id="email" name="email" placeholder="Email">
                <p class="text-muted">
                    Enter your Email and instructions will be sent to you! 
                </p>

                <div id="profile" style="display: none;">
                  <h2 class="fs-title">Profile</h2>
                  <hr>
                  <p>
                     <img src="" alt="No Image" id="clientimage"> 
                 </p>
                 <h2 id="ProfileName"></h2>
                 <small>Are you sure ?</small>
                 <button id="send" class="btn btn-success">Yes</button>
                 <button id="notsend" class="btn btn-cancel">No</button>
                 <hr>
             </div>

             <label class="text-dark pull-right previous"><i class="fa fa-lock m-r-5"></i>Back to login?</label>

             <button class="btn btn-info btn-block text-uppercase btn-rounded text-uppercase myclassbtn mb-2" id="btn" type="submit">Verify</button>

             <div class="load-wrapp submit_loader" style="display: none;">
                <div class="load-3">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                </div>
            </div>
            <br>

            <div class="alert alert-success" id="f_resonse" style="display: none;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                <h3 class="text-success">
                    <i class="fa fa-check-circle">
                    </i>
                    Success
                </h3>
                <p id="f_resonsemsg">
                </p>
            </div>

            <div class="alert alert-danger" id="f_error" style="display: none;">
             <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
             <h3 class="text-danger">
                <i class="fa fa-exclamation-circle">
                </i>
                Errors
            </h3>
            <p id="f_errormsg">
            </p>
        </div>

    </fieldset>
</form>

</div>

<?php include_once('MaintenanceTimer.php');?>
<?php if($MaintenanceResult['Maintenance']==2){ ?>
    <div class="MaintenanceTime" style="text-align: center;color: red;font-size: 30px;">0:00:00</div>
    <div class="MaintenanceText" style="text-align: center;color: red;font-size: 20px;"></div>
<?php } ?>

</div>
</section>
<script src="<?php echo base_url; ?>/assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
<script src="<?php echo base_url; ?>/dist/js/jquery.validate.js"></script>
<script src="<?php echo base_url; ?>/assets/js/jquery.easing.min.js"></script>
<script src="<?php echo base_url; ?>/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $(".select2").select2();

        $.ajax({
            url:'MaintenanceTimer?Timer',
            dataType:'json',
            success:function(data){
                if(data.response){
                    MaintenanceTimer(data.Post);
                }
            }
        });

        function MaintenanceTimer(Post){

          var EndTime = moment.utc(Post.PostDate).local().format("DD/MM/YYYY HH:mm:ss");

          var Timer = setInterval(function(){

            var ms = moment(EndTime,"DD/MM/YYYY HH:mm:ss").diff(moment());
            var d = moment.duration(ms);
            var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");

            if(s<='0:00:00'){
                $.ajax({
                    url:'Login_Check?SetMaintenance=1',
                    dataType:'json',
                    success:function(data){
                        if(data.response){
                            clearInterval(Timer);
                            location.reload();
                        }
                    }
                });
            }

            $(".MaintenanceTime").text(s);
            $(".MaintenanceText").html(Post.PostDec);
        },1000);

      }


      $("#usertype").change(function(){
        var value = $(this).val();
        if(value=='subscriber'){
            $("#SubscriberList").hide();
        }else{
            $("#SubscriberList").show();
        }
    });

      $("#password_block span").on("click",function(){

        if($(this).find("i").hasClass('fa-eye-slash')){           
          $(this).find("i").removeClass('fa-eye-slash');
          $(this).find("i").addClass('fa-eye');
          $(this).css("background","#1cc71c");          
          $('#password').attr('type','text');
          $('#password').focus();
      }else{         
          $(this).find("i").removeClass('fa-eye');          
          $(this).find("i").addClass('fa-eye-slash');  
          $(this).css("background","#F44336");          
          $('#password').attr('type','password');
          $('#password').focus();
      }
  });
          //   $('#password_block').hover(function(){

          //   if($(this).find("i").hasClass('fa-eye-slash')){           
          //         $(this).find("i").removeClass('fa-eye-slash');
          //         $(this).find("i").addClass('fa-eye');
          //         $(this).css("background","#1cc71c");          
          //         $('#password').attr('type','text');        
          //     }else{         
          //         $(this).find("i").removeClass('fa-eye');          
          //         $(this).find("i").addClass('fa-eye-slash');  
          //         $(this).css("background","#F44336");          
          //         $('#password').attr('type','password');
          //     }
          // });

          $("#loginForm").validate({
            rules: {
                UserName: {
                    required: true,
                    maxlength: 100

                }
                ,
                password: {
                    required: true,
                    maxlength: 50
                }
            }
            ,
            messages: {
                UserName: {
                    required: "Please enter username"
                }
                ,
                Password: {
                    required: "Please enter your password"
                }
            },
            errorClass: "fieldinvalid"
            ,submitHandler: function() {

                $(".submit_loader").show();
                $(".myclassbtn").hide();
                $("#resonse").hide();
                $("#error").hide();

                var data = $("#loginForm").serialize();
                data= data + "&LoginAction=Login";
                jQuery.ajax({
                    dataType:"json",
                    type:"post",
                    data:data,
                    url:'<?= base_url ?>/Login_Check',
                    success: function(data)
                    {
                        if(data.response){

                            LoginResponse = data.response;
                            ActiveID = data.id;
                            CheckVerificationAccount(ActiveID,LoginResponse);

                        }else if(data.error){
                            $("#error").show();
                            $('#errormsg').html('<span>'+data.error+'</span>');
                            
                        }
                        $(".submit_loader").hide();
                        $(".myclassbtn").show();

                    }
                }
                );
            }
        }
        );
      });


function CheckVerificationAccount(ActiveID,LoginResponse){
    jQuery.ajax({
        dataType:"json",
        type:"post",
        data:{CheckVerificationAccount:ActiveID},
        url:'<?= base_url ?>/Login_Check',
        success: function(data)
        {
            console.log(data);
            $("#resonse").show();
            $('#resonsemsg').html('<span>'+LoginResponse+'</span>');
            window.location.href = "<?php echo base_url ?>/dashboard.php";

        }
    });
}

$(".LoginToken").click(function(e){
    e.preventDefault();
    jQuery.ajax({
        dataType:"json",
        type:"post",
        url:'<?= base_url ?>/Login_Check?LoginToken',
        success: function(data)
        {
            if(data.response){
                LoginResponse = data.response;
                ActiveID = data.id;
                CheckVerificationAccount(ActiveID,LoginResponse);
            }else if(data.error){
                setTimeout(function(){  location.reload(); },3000);
                $("#error").show();
                $('#errormsg').html('<span>'+data.error+'</span>');

            }
        }
    });

});

$(".save_user_delete").click(function(e){
    e.preventDefault();
    jQuery.ajax({
        dataType:"json",
        type:"post",
        url:'<?= base_url ?>/Login_Check?DeleteToken',
        success: function(data)
        {
            if(data.response){
                $(".save_user_above").fadeOut();
                $(".save_user_body").fadeOut().delay(1000);
            }
        }
    });

});



$("#forgetpassword").validate({
    rules: {
        email: {required: true}
    },
    messages: {
        email: {required: "Please enter  email"}
    },
    errorClass: "fieldinvalid",
    submitHandler: function() {
        var data = $("#forgetpassword").serialize();
        $(".submit_loader").show();
        $(".myclassbtn").hide();
        jQuery.ajax({
            dataType:"json",
            type:"post",
            data:data,
            url:'?find_user',
            success: function(data) 
            {
              if(data.response)
              {
                $("#profile").show();
                if(data.response.userimg){
                    $("#clientimage").attr("src","<?= base_url?>/assets/userimage/"+data.response.userimg);
                }else{
                    $("#clientimage").attr("src","<?= base_url?>/assets/images/noimage.png");
                }

                $("#ProfileName").text(data.response.firstname+" "+data.response.lastname+"( "+data.response.username+" )");
            }
            else if(data.error)
            {
                $("#profile").hide();
                $("#f_error").show().delay(5000).fadeOut();;
                $('#f_errormsg').html('<span>'+data.error+'</span>');
            }
            $(".submit_loader").hide();
            $(".myclassbtn").show();
        }

    });
    }
});

$("#send").click(function(e){
    e.preventDefault();
    forgetpassword();
});
$("#notsend").click(function(e){
    e.preventDefault();
    $("#profile").hide();
});

function forgetpassword(){
   $(".submit_loader").show();
   $(".myclassbtn").hide();
   $("#f_resonse").hide();
   $("#f_error").hide();
   var data = $("#forgetpassword").serialize();
   data= data + "&action3=forgetpassword";
   jQuery.ajax({
    dataType:"json",
    type:"post",
    data:data,
    url:'<?php echo EXEC; ?>exec-edit-profile.php',
    success: function(data) 
    {
        if(data.resonse)
        {
            $("#f_resonse").show().delay(5000).fadeOut();;
            $('#f_resonsemsg').html('<span>'+data.resonse+'</span>');
            $( '#forgetpassword' ).each(function(){
                this.reset();
            }
            );
            setTimeout(function () {
                window.location.href = "../index.php";
            }
            , 3000)
        }
        else if(data.error)
        {
            $("#f_error").show().delay(5000).fadeOut();;
            $('#f_errormsg').html('<span>'+data.error+'</span>');
        }

        $(".submit_loader").hide();
        $(".myclassbtn").show();
    }
})
}





//jQuery Next Slide
var current_fs, next_fs, previous_fs; //fieldsets
var left, opacity, scale; //fieldset properties which we will animate
var animating; //flag to prevent quick multi-click glitches

$(".next").click(function(){

    if(animating) return false;
    animating = true;
    
    // current_fs = next.parent();
    // next_fs = next.parent().next();
    current_fs = $("#loginForm");
    next_fs = $("#forgetpassword");
    
    //show the next fieldset
    next_fs.show(); 
    //hide the current fieldset with style
    current_fs.animate({opacity: 0}, {
        step: function(now, mx) {
            //as the opacity of current_fs reduces to 0 - stored in "now"
            //1. scale current_fs down to 80%
            scale = 1 - (1 - now) * 0.2;
            //2. bring next_fs from the right(50%)
            left = (now * 50)+"%";
            //3. increase opacity of next_fs to 1 as it moves in
            opacity = 1 - now;
            current_fs.css({'transform': 'scale('+scale+')'});
            next_fs.css({'left': left, 'opacity': opacity});
        }, 
        duration: 800, 
        complete: function(){
            current_fs.hide();
            animating = false;
        }, 
        //this comes from the custom easing plugin
        easing: 'easeInOutBack'
    });

});


$(".previous").click(function(){
    if(animating) return false;
    animating = true;
    
    // current_fs = $(this).parent();
    // previous_fs = $(this).parent().prev();

    previous_fs = $("#loginForm");
    current_fs = $("#forgetpassword");

    //show the previous fieldset
    previous_fs.show(); 
    //hide the current fieldset with style
    current_fs.animate({opacity: 0}, {
        step: function(now, mx) {
            //as the opacity of current_fs reduces to 0 - stored in "now"
            //1. scale previous_fs from 80% to 100%
            scale = 0.8 + (1 - now) * 0.2;
            //2. take current_fs to the right(50%) - from 0%
            left = ((1-now) * 50)+"%";
            //3. increase opacity of previous_fs to 1 as it moves in
            opacity = 1 - now;
            current_fs.css({'left': left});
            previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
        }, 
        duration: 800, 
        complete: function(){
            current_fs.hide();
            animating = false;
        }, 
        //this comes from the custom easing plugin
        easing: 'easeInOutBack'
    });
});
</script>



</body>
</html>