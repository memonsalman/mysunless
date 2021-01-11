<?php 

require_once('global.php');
require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/function.php");

  
// require_once $_SERVER["DOCUMENT_ROOT"].'/ImportGooglecontacts/google-api-php-client/src/Google/autoload.php';// or wherever autoload.php is located
// $google_client_id = '570162898749-7puntleqa461a7n6lif2u9bj15chf7f5.apps.googleusercontent.com';
// $google_client_secret = 'U_R2sbMzsJ9MqYMghuUYE_62';
// $google_redirect_uri = 'https://mysunless.com/ImportGooglecontacts/index.php';
// //setup new google client
// $client = new Google_Client();
// $client -> setApplicationName('My Appliction');
// $client -> setClientid($google_client_id);
// $client -> setClientSecret($google_client_secret);
// $client -> setRedirectUri($google_redirect_uri);
// $client -> setAccessType('online');
// $client -> setScopes('https://www.google.com/m8/feeds');
// $googleImportUrl = $client -> createAuthUrl();
/*echo  $encodeed =  EncodeId(15);
echo "<br>".DecodeId($encodeed)."<br>";die;*/

if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"]) && !isset($_GET['ref'])){
    header("Location: ../index.php");die;
}

    if(isset($_SESSION['UserID']))
    {
        $subFolderPath = "/crm";

           $id=$_SESSION['UserID'];
           $stmt= $db->prepare("SELECT * FROM `users` WHERE id=:id"); 
           $stmt->bindParam(':id', $id, PDO::PARAM_INT);
           $stmt->execute();
           $result = $stmt->fetch(PDO::FETCH_ASSOC);
           @$clientcreateprmistion=$result['ClientCreate'];
           $ClientsLimit=$result['ClientsLimit'];
           @$mailFrom=$result['email'];
             $sid=$result['sid'];
      $usertype=$_SESSION['usertype'];
    }
    $total_clients = $db->prepare("SELECT * FROM `clients` WHERE `createdfk`=:id");
    $total_clients->bindParam(':id', $id, PDO::PARAM_INT);
    $total_clients->execute();
    $number_of_clients = $total_clients->rowCount();
    $all_client=$total_clients->fetchAll(PDO::FETCH_ASSOC);
    $RelatedTo2 = $db->prepare("SELECT * FROM `EmailTempleate` WHERE createdfk=:id");
    $RelatedTo2->bindValue(":id",$id,PDO::PARAM_INT);
    $RelatedTo2->execute();
    $all_Templeate=$RelatedTo2->fetchAll(PDO::FETCH_ASSOC);
// if($clientcreateprmistion==0){
//      unset($_SESSION['UserID']);
//      echo "<script>alert('You Don\'t have a permission to create client..please contact admin');</script>";
//      echo "<script>location.href='index.php';</script>";die;
//  }    
    

   


    if(isset($_REQUEST['customersid2']))
    {
       $customersid2=$_POST['customersid2']; 
      $eidtClient2 = $db->prepare("select * from `clients` where id=:customersid2");
      $eidtClient2->bindValue(":customersid2",$customersid2,PDO::PARAM_INT);
      $editfile2=$eidtClient2->execute();
      $all2=$eidtClient2->fetch(PDO::FETCH_ASSOC);
      if($editfile2)
      {
          echo  json_encode(["resonse"=>$all2]);die;
      }
      
    }

    $statement=$db->prepare("SELECT * FROM `countries` ");
   $statement->execute();
   $countryList = $statement->fetchAll(PDO::FETCH_ASSOC);
   $statement2=$db->prepare("SELECT * FROM `provinces` ");
   $statement2->execute();
   $stateList = $statement2->fetchAll(PDO::FETCH_ASSOC);

   // $defulimag=rand(1,40);


    // $title1= $db->prepare("SELECT TitleName FROM `PageTitle` where id='2'"); 
    // $title1->execute();
    // $all_title1 = $title1->fetch(PDO::FETCH_ASSOC);
    // $T1=$all_title1['TitleName'];
?>

<?php

   $length = 32;
   $_SESSION['csrf'] = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length);
   

    $button13x= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C13'"); 
    $button13x->execute();
    $all_button13x = $button13x->fetch(PDO::FETCH_ASSOC);
    $B13x=$all_button13x['button_name'];


    $button14x= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C14'"); 
    $button14x->execute();
    $all_button14x = $button14x->fetch(PDO::FETCH_ASSOC);
    $B14x=$all_button14x['button_name'];

    $button15x= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C15'"); 
    $button15x->execute();
    $all_button15x = $button15x->fetch(PDO::FETCH_ASSOC);
    $B15x=$all_button15x['button_name'];

    $button16x= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C16'"); 
    $button16x->execute();
    $all_button16x = $button16x->fetch(PDO::FETCH_ASSOC);
    $B16x=$all_button16x['button_name'];

    $button17x= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C17'"); 
    $button17x->execute();
    $all_button17x = $button17x->fetch(PDO::FETCH_ASSOC);
    $B17x=$all_button17x['button_name'];

?>
<!-- head.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <style type="text/css">
    
        table#myTable{
            text-align: center;
        }
        .cubutfoma{margin: 3px;}

    </style>
    
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
    <!-- This page CSS -->
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Playfair+Display:400,700,700i,400i"/>
    <!--     <link href="
<?php echo base_url; ?>/assets/node_modules/morrisjs/morris.css" rel="stylesheet"> -->
    <link href="<?php echo base_url; ?>/assets/mystyle.css" rel="stylesheet">
    <!-- custom bootstrap -->
    <link href="<?php echo base_url; ?>/assets/custom-bootstrap.css" rel="stylesheet">
    <!--Toaster Popup message CSS -->
    <link href="<?php echo base_url; ?>/assets/node_modules/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url; ?>/dist/css/style.min.css" rel="stylesheet">
    <!-- Dashboard 1 Page CSS -->
    <link href="<?php echo base_url; ?>/dist/css/pages/dashboard1.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <!-- for text editor  -->
    <link rel="stylesheet" href="<?php echo base_url; ?>/assets/node_modules/html5-editor/bootstrap-wysihtml5.css" >
    <!-- material datetime picker  -->
    <link href="<?php echo base_url; ?>/assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/dropify.min.css">
    <link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/custom-new.css">
    <link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/custom-mobile.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
CSS TimePicker-->
    <link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/jquery.timepicker.css">
    <!-- <link href="<?php echo base_url; ?>/assets/intro/demo.css" rel="stylesheet"> -->
    <link href="<?php echo base_url; ?>/assets/intro/introjs.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/pagination.css">

    <link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/dropify.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    
    <link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>/assets/chartjs/Chart.css">

    <link rel='stylesheet' type='text/css'href='<?php echo base_url ?>/assets/css/timepicki.css' />
    
    <link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>/assets/css/bootstrap-toggle.min.css">

    <script type="text/javascript">
    var base_url = '<?php echo  base_url; ?>';

</script>
<style>
.collapsing, .modal, .modal-open, .progress{
  overflow: auto!important;
}
/*.modal2 {display:none;position:fixed; z-index:1000; top:0; left:0; height:100%;width:100%;background: rgba( 255, 255, 255, .8) 
                url('assets/images/ajax-loader.gif') 50% 50% no-repeat;}
body.loading .modal2 {overflow: hidden;}
body.loading .modal2 {display: block;}*/
li.token-search{width: 100%!important;}
button.dt-button.buttons-csv.buttons-html5 {cursor: pointer;position: absolute; top: 0; right: 0;border: #02a9f3; background: #02a9f3; height: 35px;}
button.dt-button.buttons-csv.buttons-html5:hover {background-color: #038fcd;}
div#home { position: relative;}
li.select2-selection__choice {color: white !important;}

/*.select2-container--default .select2-selection--multiple{
   border-bottom: 1px solid #e9ecef!important;
   border-top: 0!important;
   border-right: 0!important;
   border-left: 0!important;}*/
span.select2.select2-container.select2-container--default.select2-container--focus{
   width: 100%!important;
}
span.select2.select2-container.select2-container--default.select2-container{
   width: 100%!important;
}
input.select2-search__field{
   width: 100%!important;
}
div.fb-login-button.fb_iframe_widget span {
    height: 27px !important;
}
/*div.fb-login-button.fb_iframe_widget span iframe {
    height: 35px !important;
}*/
.table-striped tbody tr:nth-of-type(odd) {
    background: #a3d3ea1c!important;
}
.table-bordered td, .table-bordered th {
     border-bottom: 1px solid #dee2e6!important;
     border-top : 1px solid #dee2e6!important;
     border-left: 0!important;
     border-right:0!important;
}
input#deleteCheck{box-sizing: border-box;
    padding: 0;
    width: 20px!important;
    height: 20px!important;
    margin-top: 13px!important;}
._5h0d ._5h0i {
    height: 35px !important;
}
._5h0d ._5h0m {
    height: 16px !important;
  }
  .img-circle {
    object-fit: cover;
}

    #viecdetails,#deleteButton{display: inline-block!important;}
    
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      .lb-details{display: none!important;}
      .pac-container.pac-logo{z-index: 99999;}
      .lb-container{    position: absolute;    right: 0;    left: 0; }
      .lb-outerContainer{width: 50%!important;}
      img.lb-image{margin: 0 auto!important;}
      .lb-dataContainer{width: 75%!important; margin: unset!important;}
      .lightbox{top: 150px!important;}

      @media only screen and (max-width: 768px) 
      {
    .lightbox{top: 500px!important}
    .lb-outerContainer{width: 80%!important;}
    .lb-dataContainer{width: 92%!important; margin: unset!important;}
    }
      
    </style>

    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">

</style>
<link href="<?php echo base_url; ?>/assets/css/tokenize2.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url; ?>/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />    


<link rel="stylesheet" href="<?php echo base_url; ?>/upload-and-crop-image/croppie.css">
<link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/dropify.min.css">
<link rel="stylesheet" href="<?php echo base_url; ?>/dist/css/lightbox.min.css">

    <!--[endif]-->

  
</head>
