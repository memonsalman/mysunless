<?php 
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].$SUB.'/function.php');

if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: ../index.php");die;
}

$title1= $db->prepare("SELECT TitleName FROM `PageTitle` where id='35'"); 
$title1->execute();
$all_title1 = $title1->fetch(PDO::FETCH_ASSOC);
$T1=$all_title1['TitleName'];


?>

<?php 
if(isset($_GET['action']) && $_GET['action']=='search'){
    require_once($_SERVER["DOCUMENT_ROOT"].$SUB.'/function.php');
    $db= new db();

    if(isset($_GET['q']) && $_GET['q']!=""){
        $keyword = explode(" ", $_GET['q']);

        $keyword_title = "faqTitle like '%".implode("%' or faqTitle like '%", $keyword)."%'";
        $keyword_des = "or faqDesc like '%".implode("%' or faqDesc like '%", $keyword)."%'";

        $search_faq = "SELECT faqCategory, id, faqTitle, faqDesc FROM `faqs` where ".$keyword_title." ".$keyword_des;
        
    }else{
        $search_faq = "SELECT faqCategory, id, faqTitle, faqDesc FROM `faqs`";
    }
    try {
        $stmt = $db->query($search_faq);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if(!empty($result)){

            $faqcat = array();
            $data = array();
            foreach ($result as $faq) {

                if(array_key_exists($faq['faqCategory'],$data)){

                    array_push($data[$faq['faqCategory']],array('id' => $faq['id'],'faqTitle' => $faq['faqTitle'],'faqDesc' => $faq['faqDesc']));
                }else{
                    $data[$faq['faqCategory']] = array(array('id' => $faq['id'],'faqTitle' => $faq['faqTitle'],'faqDesc' => $faq['faqDesc']));
                //array_push($data[$faq['faqCategory']],array('id' => $faq['id'],'faqTitle' => $faq['faqTitle'],'faqDesc' => $faq['faqDesc']));
                }
            }
            echo json_encode($data);die;

        }else{
           echo json_encode('false');die;
       }
   } catch (Exception $ex) {
    echo($ex->getMessage());die;
}

}
?>

<?php

if(isset($_GET['action']) && $_GET['action']=="enquiry"){
    $Username = $_POST['e_name'];
    $file_name = "";
    $headers = '';
    $message="Hi";
    $Subject = "Enquiry from ".$Username;


    // $Subject=stripslashes(strip_tags($_POST['RepName']));
    // $Message="Email: ".$_POST['e_email']."<br> Phone: ".$_POST['e_phone']."<br> Message: ".$_POST['e_msg'];
    $Message = "<tr> <td style='border: 1px solid black;padding: 2px;'>Email: </td><td style='border: 1px solid black;padding: 2px;'>".$_POST['e_email']."</td> </tr> <tr> <td style='border: 1px solid black;padding: 2px;'>Phone: </td><td style='border: 1px solid black;padding: 2px;'>".$_POST['e_phone']."</td> </tr> <tr> <td style='border: 1px solid black;padding: 2px;'>Message: </td><td style='border: 1px solid black;padding: 2px;'>".nl2br($_POST['e_msg'])."</td> </tr>";
    $other['--TITLE--'] = $Subject;
    $other['--MESSAGE--'] = $Message;
    $other['--USERNAME--'] = $Username;
    $reportsend = sendsmpleMailReport('help@mysunless.com',$Subject, "Enquiry.php", $message, $headers, $other,@$file_name);
    // $reportsend = sendsmpleMailReport('abhijeet.dds@gmail.com',$Subject, "Enquiry.php", $message, $headers, $other,@$file_name);

    // sendsmpleMailReport('mike@sjolieinc.com',$Subject, "Report.php", $message, $headers, $other,@$file_name);

    if(!empty($reportsend))
    {
        echo json_encode(['status'=>1,'response'=>'Issue has been reported successfully']);die;  
    }else{
        echo json_encode(['status'=>0,'response'=>'Something went wrong.']);die;  

    }
}

?>




<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
<style type="text/css">

    .links  {
        color: #212529;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
        border: 1px solid #ddd;
        background-color:  #fff;
    }
    .links:hover:not(.active) {
        background-color: #ddd;
        color: #fff;
        background-color: #038fcd;
        border-color: #0286c1;
    }
    .links.selected {
        background-color: #3cabe1;
        color: white;
        border: 1px solid #3cabe1;
    }


    .main-slider {
        background-image: url('./assets/images/bg-9.jpg');
        background-size: cover;
        padding: 150px;
        border-radius: 6px;
    }
    .search-container{
      width: 490px;
      display: block;
      margin: 0 auto;
  }
  input#search-bar:hover {
    box-shadow: none;
}

input#search-bar{
  margin: 0 auto;
  width: 100%;
  height: 65px;
  padding: 0 20px;
  font-size: 1rem;
  border: 0;
  border-radius: 4px;
  outline: none;
  box-shadow: 7px 5px 0px 0px #ddd;
  transition: 0.4s;
  &:focus{
    border: 1px solid #008ABF;
    transition: 0.35s ease;
    color: #008ABF;
    &::-webkit-input-placeholder{
      transition: opacity 0.45s ease; 
      opacity: 0;
  }
  &::-moz-placeholder {
      transition: opacity 0.45s ease; 
      opacity: 0;
  }
  &:-ms-placeholder {
   transition: opacity 0.45s ease; 
   opacity: 0;
}    
}
}

.search-icon{
    position: relative;
    float: left;
    width: 75px;
    height: 75px;
    top: -62px;
    right: -45px;
}
input#search-bar{
    padding-left: 60px;
}
.search-icon {
    position: relative;
    float: left;
    top: -41px;
    right: -19px;
    font-size: 17px;
}
.search-icon:hover{

    color: #00adff;
}

</style>
<body class="skin-default fixed-layout mysunlessA13">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">
                <?php echo $_SESSION['UserName']; ?></p>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Main wrapper - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <div id="main-wrapper">
            <!-- ============================================================== -->
            <!-- Topbar header - style you can find in pages.scss -->
            <!-- ============================================================== -->
            <header class="topbar">
                <?php include 'TopNavigation.php'; ?>
            </header>
            <!-- ============================================================== -->
            <!-- End Topbar header -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Left Sidebar - style you can find in sidebar.scss  -->
            <!-- ============================================================== -->
            <?php include 'LeftSidebar.php'; ?>
            <!-- ============================================================== -->
            <!-- End Left Sidebar - style you can find in sidebar.scss  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Page wrapper  -->
            <!-- ============================================================== -->
            <div class="page-wrapper">
                <!-- ============================================================== -->
                <!-- Container fluid  -->
                <!-- ============================================================== -->
                <div class="container-fluid">
                    <!-- ============================================================== -->
                    <!-- Bread crumb and right sidebar toggle -->
                    <!-- ============================================================== -->
                    <div class="row page-titles">
                        <div class="col-md-5 align-self-center">
                            <h4 class="text-themecolor">
                                <?php echo $T1; ?>
                            </h4>
                        </div>
                    </div>
                    <div class=" container-fluid p-1">
                        <div class="main-slider">

                         <div class="slider-heder text-center"> 
                            <h1>How Can We Help?</h1>
                        </div>
                        <div class="slider-serch mt-5">

                            <form class="search-container" id="faq_search">
                                <input type="text" id="search-bar" placeholder="Ask a question">
                                <i class="fas fa-search search-icon"></i>
                            </form>
                        </div>

                    </div>
                </div>

                <style>
                    #faq{
                        background-color: white;
                        padding: 30px 38px;
                        margin: 30px 0px;
                        border-radius: 8px;
                    }
                    #faq .row{
                        margin: 0px;
                    }
                    .nav-tabs{
                        border-bottom:0px;
                    }
                    #faq_cat{
                        box-shadow: 3px 0px 2px 0px #00000024;
                    }
                    #faq_cat .nav_link{
                        width: 100%;
                        text-decoration: none;
                        color: #9492a1;
                        padding: 5px 10px;
                        font-size: 16px; 
                        border-left: 3px solid transparent;   
                        font-weight: 600;
                    }
                    #faq_cat .nav_link.active{
                        transition: background-color .3s;
                        background-color: #f7f8fa;
                        border-left-color: #3cabe1;
                        color: #3cabe1;
                    }
                    #faq_cat .nav_link:hover{
                       transition: background-color .3s;
                       background-color: #f7f8fa;
                       border-left-color: #212529;
                       color: #212529;
                   }

                   .arrow-rotate {
                    color: #677f8a;
                    margin-right: 5px;
                    -moz-transition: all .5s linear;
                    -webkit-transition: all .5s linear;
                    transition: all .5s linear;
                }
                .arrow-rotate.down {
                    color: #3cabe1;
                    -moz-transform:rotate(90deg);
                    -webkit-transform:rotate(90deg);
                    transform:rotate(90deg);
                }
                .faq_des{
                    -moz-transition: all .5s linear;
                    -webkit-transition: all .5s linear;
                    transition: all .5s linear;  
                }
                .faq_title:hover {
                    color: #677f8a!important;
                }
                #cat_head{
                    margin-left: 5px;
                }
            </style>
            <?php 

            require_once($_SERVER["DOCUMENT_ROOT"].'/crm/function.php');
            $db= new db();
            try {
                $stmt = $db->prepare("SELECT DISTINCT faqCategory FROM `faqs`");
                $stmt->execute();
                $result = $stmt->fetchAll();

            } catch (Exception $ex) {
                echo($ex->getMessage());
            }

            ?>
            <div id="faq-top"></div>  <!-- /// position for on search  -->
            <div class="container-fluid" id="faq">
                <div class="row">
                    <h3 id="cat_head">Buying Product & Support</h3>
                </div>
                <div class="row" id="faq_list"></div>
            </div>

            <style>
                .tab-pane{
                    box-shadow: -3px 0px 2px 0px #00000024;
                }
                #contact{
                    align-items: center;
                    margin:0px;
                    background: white;
                    padding: 1.25rem;
                    border-radius: 10px;
                    margin-bottom: 20px;
                    width: 50%;
                }

                #contact .btn{
                    font-size: 12px;
                    font-weight: 500;
                    padding: 10px 15px;
                }
                #contact .btn:hover{
                    background: #4498c1!important;
                    border-color: #4498c1!important;
                }
                .btn-primary:hover{
                    background-color: #378db7;
                    border-color: #378db7;
                }

                @media only screen and (max-width: 768px) {
                
                .fixed_frame{
                    width: auto!important;
                    height: auto!important;
                }

                #contact {
                    width: 100%;
                }
                .main-slider{
                    padding: 12px;
                }
                .search-container{
                    width: auto;
                }
                .search-icon{
                    width: auto;
                    height: auto;
                }
            }

        </style>



        <div class="row" id="contact">

            <div class="col-md-8" style="text-align: left;color:#9492a1">
                <h3>Get in Touch</h3>
                <p>Ask any query regarding Mysunless.<br>We are here happy to help to you.</p>
                <p>Email: help@mysunless.com</p>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary" data-toggle="modal" data-target="#enquiry">SUBMIT A REQUEST</button>
            </div>
        </div>

        <!-- enquiry Modal -->
        <div class="modal fade" id="enquiry" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Send us your enquiries</h5>
                <button type="button" class="close close_enquiry" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
            <form id="enquiry_form">
                <fieldset>
                    <div class="form-group">
                      <label for="e_name">Name *</label>
                      <input type="text" name="e_name" class="form-control" id="e_name" placeholder="Enter your name" required>
                  </div>
                  <div class="form-group">
                      <label for="e_phone">Phone *</label>
                      <input id="phonenumber" type="text" name="e_phone" class="form-control" id="e_phone" placeholder="Enter your phone" required>
                  </div>
                  <div class="form-group">
                      <label for="e_email">Email address *</label>
                      <input type="email" name="e_email" class="form-control" id="e_email" aria-describedby="emailHelp" placeholder="Enter email" required>
                      <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                  </div>
                  <div class="form-group">
                      <label for="e_msg">Your Message *</label>
                      <textarea class="form-control" name="e_msg" id="e_msg" rows="5" required></textarea>
                  </div>

                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="button" class="close_enquiry btn btn-secondary" data-dismiss="modal">Close</button>
              </fieldset>
          </form>
      </div>
      <div class="modal-footer">
        <div id="enquiry_status" class="alert" role="alert" style="display: none;width: 100%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button><div id="enquiry_message"></div></div>
    </div>
</div>
</div>
</div>



<script>

    $(document).ready(function(){

        serach_faq();

        function serach_faq(){

            q = $("#search-bar").val();
            $.ajax({
                url:"?action=search&q="+q,
                type:'get',

                success:function(data){
                    data = JSON.parse(data);
                    if(data!='false'){

                        $("#faq_list").html('<div class="col-md-4 card-body" style="padding-left: 5px;"> <div class="nav nav-tabs customtab" id="faq_cat" role="tablist"></div></div><div class="col-md-8 card-body tab-content"></div>');

                        var cat_count = 0

                        for(cat in data){

                            cat_count++;
                            category = cat_count;
                            $("#faq_cat").append('<a class="nav_link" data-toggle="tab" href="#category_'+category+'" role="tab" aria-selected="true">'+cat+'</a>');

                            $('.tab-content').append('<div class="tab-pane panel" id="category_'+category+'" role="tabpanel"></div>');


                            for(i=0; i<data[cat].length;i++){
                                $("#category_"+category).append('<div class="card m-b-0"> <div class="card-header faq_title collapsed" role="tab" data-toggle="collapse" data-parent="#accordion1" href="#'+data[cat][i].id+'"aria-expanded="false" aria-controls="collapseOne"> <h5 class="mb-0"> <a class="link"> <i class="fas fa-angle-double-right arrow-rotate"></i>'+data[cat][i].faqTitle+'</a> </h5> </div> <div id="'+data[cat][i].id+'" class="collapse faq_des" role="tabpanel" aria-labelledby="headingOne1"> <div class="card-body">'+data[cat][i].faqDesc+'</div> </div> </div>');
                            }

                        }

                        $(".nav_link:first-child").addClass('active');
                        $(".nav_link:first-child").addClass('show');
                        var cat_head = $(".nav_link:first-child").text();
                        $("#cat_head").text(cat_head);
                        $(".tab-pane:first-child").addClass('active');
                        $(".tab-pane:first-child").addClass('show');

                    }else{
                        $("#faq_list").html('');
                        $("#cat_head").html('<h4>No result found.</h4>');
                    }

                }
            });

        }

        $("#faq_search").submit(function(e){
            e.preventDefault();
            serach_faq();

            $('html, body').animate({
                scrollTop: $("#faq-top").offset().top
            }, 2000);

        });


    });

    $('#phonenumber').keyup(function(e){
        var ph = this.value.replace(/\D/g,'').substring(0,10);
          // Backspace and Delete keys
          var deleteKey = (e.keyCode == 8 || e.keyCode == 46);
          var len = ph.length;
          if(len==0){
              ph=ph;
          }else if(len<3){
              ph='('+ph;
          }else if(len==3){
              ph = '('+ph + (deleteKey ? '' : ') ');
          }else if(len<6){
              ph='('+ph.substring(0,3)+') '+ph.substring(3,6);
          }else if(len==6){
              ph='('+ph.substring(0,3)+') '+ph.substring(3,6)+ (deleteKey ? '' : '-');
          }else{
              ph='('+ph.substring(0,3)+') '+ph.substring(3,6)+'-'+ph.substring(6,10);
          }
          this.value = ph;
      });

    $("#enquiry .close_enquiry").click(function(){
        $("#enquiry_form")[0].reset();
        $("#enquiry_status").hide();
    });

    $("#enquiry_form").submit(function(e){
        e.preventDefault();
        //console.log('run');
        $(".preloader").show();

        $.ajax({
            type:"POST",
            url:"?action=enquiry",
            data:$(this).serialize(),//only input
            success: function(response){
                response = JSON.parse(response);
                if(response.status){

                    $('#enquiry_form').each(function(){
                       this.reset();
                   });
                    $("#enquiry_status").addClass("alert-success");
                    $("#enquiry_status #enquiry_message").text(response.response);
                    $("#enquiry_status").show();
                    $(".preloader").hide();

                }
            }
        });
    });

    $(document).on("click",".faq_title",function(){
        $(this).find(".arrow-rotate").toggleClass("down");
    });

    $(document).on("click",".nav_link",function(){
        cat_head = $(this).text();
        $("#cat_head").text(cat_head);
    });


</script>



                <!-- <div class="row">
                    <div class="card-body">
                        <div id="result_Faqs">
                            <div id="result_Faqs_message" class="col-lg-12 col-md-12">
                            </div>
                        </div>
                    </div>
                </div> -->


            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <?php include 'footer.php'; ?>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <?php include 'scripts.php'; ?>
    <script src="<?php echo base_url; ?>/assets/js/dropify.min.js"></script>
    <script type="text/javascript">


       $('.dropify').dropify();
        // Translated
        $('.dropify-fr').dropify({
          messages: {
            default: 'Glissez-déposez un fichier ici ou cliquez',
            replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
            remove: 'Supprimer',
            error: 'Désolé, le fichier trop volumineux'
        }
    });
        // Used events
        var drEvent = $('#input-file-events').dropify();
        drEvent.on('dropify.beforeClear', function(event, element) {
          return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
      });
        drEvent.on('dropify.afterClear', function(event, element) {
          alert('File deleted');
      });
        drEvent.on('dropify.errors', function(event, element) {

        });
        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
          e.preventDefault();
          if (drDestroy.isDropified()) {
            drDestroy.destroy();
        } else {
            drDestroy.init();
        }
    })


</script>
    <!-- <script>
        $(document).ready(function (){
            $("#myaccdrop").trigger("click");
            setInterval(function(){$(".gethelp").addClass("active");}, 10);
        });
        displayRecords(10, 1);
        function displayRecords(limit, start){
            $.ajax({
                url:"<?php echo EXEC; ?>Exec_Faqs_Pagination.php",
                method:"POST",
                data:"limit="+limit+"&start="+start,
                cache:false,
                // beforeSend: function(data) {  
                // $(".modal").show();
                // },
                success:function(data)
                {
                    // $(".modal").hide();
                    if(data == '')
                    {
                        $('#result_Faqs_message').html("<button type='button' disabled class='btn btn-secondary' > No more FAQS Found </button>");
                    }
                    else
                    {
                        $('#result_Faqs').html(data);
                    }
                }
            }
                  );
        }
    </script> -->
</body>
</html>