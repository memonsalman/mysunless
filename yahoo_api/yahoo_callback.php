<?php 

ob_start();

require_once($_SERVER["DOCUMENT_ROOT"].'/function.php');



?>

<!DOCTYPE html>

<html lang="EN">

<?php

  include $_SERVER["DOCUMENT_ROOT"].'/head.php';

?>

<style type="text/css">

  img.light-logo {display: inline-block !important; }

  .modal2 {display:none;position:fixed; z-index:1000; top:0; left:0; height:100%;width:100%;background: rgba( 255, 255, 255, .8) 

                url('<?php echo base_url; ?>/assets/images/ajax-loader.gif') 50% 50% no-repeat;}

  body.loading .modal2 {overflow: hidden;}

  body.loading .modal2 {display: block;}

  img#img { height: 60px;width: 60px; }

  img.dark-logo { display: none; }

  table#gmail_contact_table{ text-align: center; }

</style>





<body>

  <!-- ============================================================== -->

    <!-- Preloader - style you can find in spinners.css -->

    <!-- ============================================================== -->

    <div class="preloader">

        <div class="loader">

            <div class="loader__figure"></div>

            <p class="loader__label"><?php echo $_SESSION['UserName']; ?></p>

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

            <?php include $_SERVER["DOCUMENT_ROOT"].'/TopNavigation.php'; ?>

        </header>

        <!-- ============================================================== -->

        <!-- End Topbar header -->

        <!-- ============================================================== -->

        <!-- ============================================================== -->

        <!-- Left Sidebar - style you can find in sidebar.scss  -->

        <!-- ============================================================== -->

        <?php include $_SERVER["DOCUMENT_ROOT"].'/LeftSidebar.php'; ?>

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

                        <h4 class="text-themecolor">Yahoo Contact List</h4> 

                    </div>

                </div>

               

                <div class="row">

                    <div class="col-md-12">

                        <div class="card">

                            <div class="card-body">

                                <div class="col-lg-12">



                                    <div class=" col-md-12">

                                      <?php

                        

                        ini_set("display_errors", "1");

error_reporting(E_ALL);



require_once('globals.php');

require_once('oauth_helper.php'); 

// Fill in the next 3 variables. 

$request_token           =   $_SESSION['request_token'];

$request_token_secret   =   $_SESSION['request_token_secret'];

@$oauth_verifier        =   $_GET['oauth_verifier']; 

  // Get the access token using HTTP GET and HMAC-SHA1 signature 
  
  // echo OAUTH_CONSUMER_KEY." ".OAUTH_CONSUMER_SECRET;
  // echo "request token".$request_token;
  // echo "token secret".$request_token_secret;
  // echo "oauth verifier".$oauth_verifier;
  // die;

  @$retarr = get_access_token_yahoo(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET, $request_token, $request_token_secret, $oauth_verifier, false, true, true); 

  if (!empty($retarr)) { 

  list($info, $headers, $body, $body_parsed) = $retarr;

  if ($info['http_code'] == 200 && !empty($body)) { 

  //   print "Use oauth_token as the token for all of your API calls:\n" . 

  //      rfc3986_decode($body_parsed['oauth_token']) . "\n"; 

  // Fill in the next 3 variables. 

  $guid    = @$body_parsed['xoauth_yahoo_guid'];

   $access_token  = rfc3986_decode(@$body_parsed['oauth_token']) ;

    $access_token_secret  = @$body_parsed['oauth_token_secret']; 

  // Call Contact API 

  $retarrs = callcontact_yahoo(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET, $guid, $access_token, $access_token_secret, false, true);

    // echo "<pre/>";

    //  print_r($retarrs);



   echo "<a href='https://mysunless.com/crm/ImportWizard.php'><button  class='btn btn-danger m-r-10'><i class='fa fa-sign-out-alt'></i> Sign out</button></a><br><br>";

                          echo '<div class="table-responsive m-t-40">';

                            echo '<table class="table table-striped table-bordered" id="gmail_contact_table">';

                              echo '<thead>' ;

                                    echo '<tr>' ;

                                          

                                          echo '<th>Name</th>' ;

                                          echo '<th>Email Address</th>' ;

                                          echo '<th>Save Contact </th>' ;

                                      echo '</tr>' ;

                                  echo '</thead>' ;

                                  echo '<tbody>' ;



                              foreach ($retarrs as $contact)

                               {

                                echo '<tr>';



                                if(!empty($contact['name']))

                                {

                                echo '<td class="gmail_name">'.@$contact['name'].'</td>';  

                                }

                                else

                                {

                                 echo '<td class="gmail_name">N/A</td>';   

                                }



                                echo '<td class="gmail_email">'.@$contact['email'].'</td>'; 



                                echo '<td><button class="save btn btn-info">Save</button></td>';

                                  echo '</tr>';

                              }

                              echo '</tbody>';

                            echo '</table>';

                          echo '</div>';







}}

                                

                      ?>

                      <br>

                      <div class="container">

                        <div class="row">

                          <div class="modal2"></div>



                          <div class="col-lg-12 col-md-12">

                                            <div class="alert alert-success" id="resonse" style="display: none;">

                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>

                                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonsemsg"></p>

                                            </div>

                                            <div class="alert alert-danger" id="error" style="display: none;">

                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>

                                                <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Information</h3><p id="errormsg"></p>

                                            </div>

                                            

                                        </div>

                        </div>

                      </div>

                                       

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

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

        <?php include $_SERVER["DOCUMENT_ROOT"].'/footer.php'; ?>

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

    <?php include $_SERVER["DOCUMENT_ROOT"].'/crm/scripts.php'; ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>



<script type="text/javascript">

  $(".save").click(function(){

  $(".modal2").show();

  var image = $(this).closest('tr').find('td img').attr('src');

  var name = $(this).closest('tr').find('.gmail_name').text();

    var res = name.split(" ");

    var FirstName = res[0];

    var LastName = res[1];

    

    var email = $(this).closest('tr').find('.gmail_email').text();

      

    jQuery.ajax({



                   dataType:"json",

                   type:"post",

                    data:{google_name:name,google_email:email,FirstName:FirstName,LastName:LastName,image:image},

                    "url" : "<?php echo EXEC; ?>Exec_Edit_Client.php?googledata",

                    success: function(data)

                {

                        if(data.resonse)

                {

                    

                    $("#resonse").show();

                    

                      $('#resonsemsg').html('<span>'+data.resonse+'</span>');

                    

                    $( '#add-gmail' ).each(function(){

                         this.reset();

                         });



                    $(".modal2").hide();

                    

                    

                }

                else if(data.error)

                {

                    $("#error").show();

                    

                      $('#errormsg').html('<span>'+data.error+'</span>');

                    

                    $(".modal2").hide();

                

                }

               

                        

                     }   

                

                    });

    



});

</script>



<script type="text/javascript">

    $(".close").click(function(){

      $("#error").hide();

      $("#resonse").hide();

      

  });

</script>

<script src="<?php echo base_url; ?>/assets/node_modules/datatables/jquery.dataTables.min.js" type="text/javascript"></script>

<script type="text/javascript">

  $(document).ready(function() {

      $('#gmail_contact_table').DataTable() ;

  });

</script>



</body>



</html>



<?php 

if (isset($_REQUEST['logout'])) {

unset($_SESSION['access_token']);
$client->revokeToken();
echo "<script>close();</script>";

}

?>