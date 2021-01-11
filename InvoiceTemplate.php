<?php 

require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].$SUB.'/function.php');

require_once($_SERVER["DOCUMENT_ROOT"].'/crm/mpdf/autoload.php');

if(empty($_SESSION["usertype"]) || $_SESSION['usertype']!="subscriber"){
 header("Location: ../index.php");die;
}

if(isset($_REQUEST['Download'])){ 

 $file = ($_REQUEST['Download']);

 $filetype=filetype($file);

 $filename=basename($file);

 header ("Content-Type: ".$filetype);

 header ("Content-Length: ".filesize($file));

 header ("Content-Disposition: attachment; filename=".$filename);

 readfile($file);

 unlink($_REQUEST['Download']);
}

if(isset($_REQUEST['TEST_INVOICE'])){

  if(isset($_REQUEST['InvoiceData']) && empty($_REQUEST['InvoiceData']) && isset($_REQUEST['CustomerMail']) && empty($_REQUEST['CustomerMail'])){
    echo  json_encode(["error"=>"Something went wrong. Please refresh your page"]);die;
  }
  $clientname = "";
  if(!empty($_REQUEST['clientname'])){
    $clientname = $_REQUEST['clientname'];
  }



  ob_start();

  echo $_REQUEST['InvoiceData'];

  $html = ob_get_contents();
  ob_end_clean();

  $filename = "Invoice_".date('YmdHis').".pdf";
  $filelocation = $_SERVER["DOCUMENT_ROOT"].'/crm/assets/pdf/'.$filename;

  $mpdf = new \Mpdf\Mpdf();
  $mpdf->WriteHTML($html);


  $mpdf->Output($filelocation, 'F');

  if(isset($_GET['download_pdf'])){
    echo  json_encode(["response"=>$filelocation]);die;
  }

  $other['--USERNAME--'] = 'To '.$clientname.',<br>Thank you for choosing us as your provider. Please find a detailed copy of your bill attached to this email.';
  $headers = '';
  $message="Hi ";

  if(file_exists($filelocation)){
    $sendinveosudd=sendInvoice('abhijeet.dds@gmail.com', "Order Invoice!", "Order.php", $message, $headers, $other,$filelocation);    

    if($sendinveosudd===true)
    {
      unlink($filelocation);
      echo  json_encode(["response"=>"Invoice successfully Send!"]);die;
    }else{
      echo  json_encode(["error"=>"Something went wrong. Please refresh your page."]);die;
    }
  }else{
    echo  json_encode(["error"=>"Something went wrong. Please refresh your page."]);die;

  }

  echo  json_encode(["resonse"=>"Message sent"]);die;

}


if(isset($_SESSION["UserID"]))
{
 @$userId = $_SESSION["UserID"];
}

if(isset($_REQUEST['addtemp'])){

  $query = $db->prepare("UPDATE users set order_note =:addtemp where id=:id");
  $query->bindValue(":addtemp",$_REQUEST['addtemp']);
  $query->bindValue(":id",$_SESSION["UserID"]);
  $queryfile=$query->execute();
  if($queryfile){
    echo json_encode(['response'=>'Data successfully updated.']);die;
  }else{
    echo json_encode(['error'=>'Something went wrong. Please try again.']);die;
  }

}

if(isset($_REQUEST['get_temp'])){
 $query = $db->prepare("SELECT users.order_note,CompanyInformation.CompanyName,CompanyInformation.compimg,users.mysign from users join CompanyInformation on users.id = CompanyInformation.createdfk where users.id =:id ");
 $query->bindValue(":id",$_SESSION["UserID"]);
 $query->execute();
 @$temp_data=$query->fetch(PDO::FETCH_ASSOC);
 echo json_encode(['response'=>$temp_data]);die;
}



?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
<link rel="stylesheet" href="assets/css/custom.css">

<body class="skin-default fixed-layout mysunlessM">
  <div class="preloader">
   <div class="loader">
    <div class="loader__figure">
    </div>
    <p class="loader__label">
     <?php echo $_SESSION['UserName']; ?>
   </p>
 </div>
</div>
<div id="main-wrapper">
 <header class="topbar">
  <?php include 'TopNavigation.php'; ?>
</header>
<?php include 'LeftSidebar.php'; ?>
<div class="page-wrapper">
  <div class="container-fluid">
   <div class="row page-titles">
    <div class="col-md-5 align-self-center">
      <h4 class="text-themecolor">
        Invoice Template 
      </h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
     <div class="d-flex justify-content-end align-items-center">
     </div>
   </div>
 </div>
 <div class="row">
  <div class="col-md-12">
   <div class="card">
    <div class="card-body">
      <a href="" id="viewInvoice" title="Preview Invoice" class="btn btn-info m-r-10"  data-toggle="modal" data-target="#myModal">Preview</a>
      <br>
      <form class="form-horizontal" action="" method="post"  autocomplete="off" novalidate="novalidate">
       <div class="form-group">
         <label>Invoice Notes</label>
         <textarea class="" rows="10" placeholder="Enter text ..." name="OrderNotes" id="OrderNotes"></textarea>
       </div> 
       <div class="form-group">
         <label>Invoice Footer</label>
         <textarea class="" rows="5" placeholder="Enter text ..." name="InvoiceFooter" id="InvoiceFooter"></textarea>
       </div> 
       <input class="btn btn-info NewTempSubmit" type="submit" name="submit" value="Submit">
     </form>
   </div>
 </div>
</div>
</div>
</div>
</div>
</div>

<div class="modal fade" id="myModal" role="dialog" style="display: none; padding-left: 0px;">
  <div class="modal-dialog" style="max-width: 800px;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">
          View Invoice
        </h4>
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <div id="Invoice" class="modal-body" style="padding: 50px;">
        <div style="height:90%;padding: 10px;background: #f7f7f7;">
          <div style="width:100%">
            <div id="InvoiceHead" class="BackgroundTheme" style="margin-bottom: 20px;background: #2196f3;padding: 5px;">
              <table style="width: 100%;">
                <tr>
                  <td id="InvoiceHeadImg" style="width: 100px;text-align: center;">
                    <img src="https://mysunless.com/crm/assets/images/logo.png" title="Company Logo" style="width: 100px;height: 100px;object-fit:unset;">
                  </td>
                  <td id="InvoiceHeadText" style="text-align: left;font-size: 50px;font-weight: bold;vertical-align: bottom;line-height: 1;padding: 0 5px;">Salon</td>
                </tr>
              </table>
            </div>

            <div style="width:40%; float: left; padding-bottom:25px;">

              <div id="to" style="width:100%;">
                <span>
                  <h5 class="BackgroundTheme" style="background: #2196f3;color: white; padding: 5px ;margin-top: 0;">To:</h5>
                </span>
              </div>
              <div><span class="CustomerName1">Morgan Aguirre</span></div>
              <div><span class="CustomerMail1">test@mysunless.com</span></div>
              <div><span class="CustomerAdders1">408 S College St</span></div>
              <div><span class="CustomerAdders21">Brandon,39042 United States</span></div>
              <div><span class="Customerphone1">7600303368</span></div>
            </div>
            <div style="width:60%; float: right;">
              <div class="orderprimery" style="float: right;">
                <table>
                  <tbody>
                    <tr>
                      <td>Order Date:</td>
                      <td class="orderdata1" style="text-align:right">2020-01-01</td>
                    </tr>
                    <tr>
                      <td>Invoice Number:</td>
                      <td class="orderinvoicenumber1" style="text-align:right">1892020091101136</td>
                    </tr>

                    <tr id="Transactioniddiv"> <td>Transaction ID:</td> <td id="Transactionid" style="text-align:right">p2xcMfK703VoWOuYudGVFLB99yLZx</td> </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="clerfix" style="clear: both;"></div>
          <div style="margin-bottom: 20px;">
            <table id="carttable" border="1" width="100%" style="border-collapse: collapse;text-align: center;border: 1px solid #c3c3c3;">
             <tbody><tr id="order_popup" class="order_popup BackgroundTheme" style="background: #2196f3;">
              <td style="color: white;padding: 5px 10px;font-size: 15px;text-transform: UPPERCASE;">Item</td>
              <td style="color: white;padding: 5px 10px;font-size: 15px;text-transform: UPPERCASE;">Qty</td>
              <td style="color: white;padding: 5px 10px;font-size: 15px;text-transform: UPPERCASE;">Price</td>
              <td style="color: white;padding: 5px 10px;font-size: 15px;text-transform: UPPERCASE;">Discount</td>
              <td style="color: white;padding: 5px 10px;font-size: 15px;text-transform: UPPERCASE;"> % </td>
              <td style="color: white;padding: 5px 10px;font-size: 15px;text-transform: UPPERCASE;">Total Price</td>
            </tr>

            <tr class="child order_popup" id=""><td>Hair Cut (SIlver)</td><td id="">1</td><td id="">$0</td><td id="">$0</td><td id="">0.00%</td><td id="">$0</td></tr><tr class="child order_popup" id=""><td>Hair Spa</td><td id=""> - </td><td id="">$50</td><td id="">$10</td><td id="">20.00%</td><td id="">$40</td></tr><tr class="child order_popup" id=""><td>Giftcard</td><td id=""> - </td><td id="">$30</td><td id="">$0</td><td id=""></td><td id="">$30</td></tr><tr class="child order_popup" id=""><td>Gold</td><td id=""> - </td><td id="">$120</td><td id="">$0</td><td id=""></td><td id="">$120</td></tr><tr class="child order_popup" id=""><td>Combo Hair Product</td><td id="">1</td><td id="">$150</td><td id="">$50</td><td id="">33.33%</td><td id="">$100</td></tr><tr class="child order_popup" id=""><td>TRESemme` Shampoo 550ml</td><td id="">1</td><td id="">$70</td><td id="">$0</td><td id=""></td><td id="">$70</td></tr>

          </tbody>
        </table>
      </div>
      <div class="clerfix" style="clear: both;"></div>
      <div style="width:100%;">
        <div style="width:40%; float: right; padding-left: 10px;">
          <h5 class="BackgroundTheme" style="background: #2196f3;color: white;padding: 5px;">Summary</h5>
          <table style="width: 100%">
            <tbody>
              <tr>
                <td>Service :</td>
                <td class="serivetoaltprice1" style="text-align:right">$40</td>
              </tr>
              <tr>
                <td>Giftcard :</td>
                <td class="giftcardtotal1" style="text-align:right">$30</td>
              </tr>
              <tr>
                <td>Product :</td>
                <td class="producttotalprice1" style="text-align:right">$170</td>
              </tr>
              <tr>
                <td>Membership :</td>
                <td class="membershiptotalprice1" style="text-align:right">$120</td>
              </tr>
              <tr>
                <td>Sales Tax :</td>
                <td class="salestax1" style="text-align:right">$0</td>
              </tr>
              <tr>
                <td>Tips :</td>
                <td class="tips1" style="text-align:right">$54</td>
              </tr>
              <tr>
                <td>Gift Applied :</td>
                <td class="giftapp1" style="text-align:right">-$5</td>
              </tr>
              <tr>
                <td class="BackgroundTheme" style="background: #2196f3;color: white;padding: 5px;">Sub Total :</td>
                <td class="toatalprice1" style="text-align:right;font-weight: bold;font-size: 20px;">$409</td>
              </tr>
            </tbody>
          </table>
          <hr>
          <div class="Signature" style="padding: 10px;background: white;text-align: right;">
            <img id="InvoiceSign" width="50%" src="https://mysunless.com/crm/assets/sing/no_sign.png" title="Sign of a company" alt="Sign of a company"> 
            <br>
            <span>Signature by <span id="UserFullname">Company Owner</span></span>
          </div>
        </div>
        <div style="width:55%;float: left;">
          <div class="notes" id="notes">
            <h5 class="BackgroundTheme" style="background: #2196f3;color: white; padding: 5px ;">Other Notes</h5>
          </div>
          <div class="notelist">
            <ol>
             <li>
              This is auto computer printed invoice.
            </li>
            <li>
              If you have any problem with this invoice please contact with admin.
            </li>
            <li>
              The goods sold will not be returned.
            </li>
          </ol>
        </div>
      </div>
    </div>
    <div class="clerfix" style="clear: both;"></div>
  </div>
  <div style="width: 100%">
    <hr>
    <div class="last" style="text-align: center;">
      <h4>
        Thank you MySunless.
      </h4>
    </div>
  </div>

</div>
<div class="modal-footer">
 <input class="btn btn-info NewTempSubmit" type="button" value="Update">
 <button class="btn btn-warning hidden-print" onclick="PrintInvoice();"><span class="glyphicon glyphicon-print" aria-hidden="true"></span>Print</button>
 <button type="submit" id="invoicedownload" class="btn btn-success hidden-print" onclick="">Download</button>
 <button type="submit" id="sendinvoice_pre" class="btn btn-primary hidden-print" onclick=""><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>Send</button>
 <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

<div class="Loader" style="display: none"></div>
<!-- ------------ -->

<div class="modal fade" id="InvoiceModel_changemail" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">
          Email
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="example-email">Email * <span class="help"></span></label>
          <input type="email" id="customernewmail" name="customernewmail" class="form-control" placeholder="Email" value="" autocomplete="nope" maxlength="30">
        </div>
        <div class="form-group">
          <button type="submit" id="sendinvoice" class="btn btn-primary hidden-print" onclick=""><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Send</button>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .InvoiceFlexbox{
    display: flex!important;
    align-items: center;
  }
  .InvoiceHeadImgIcon{
    width: 16px;
    height: 16px;
    display: inline-block;
    cursor: pointer;
    margin-right: 5px; 
  }

  .InvoiceHeadImgIcon:hover{
    background-color: #d8d8d8;
  }

  #InvoiceHeadImgIcons, #InvoiceHeadTextIcons{
    position: absolute;
    z-index: 10000;
    display: none;
    background: white;
    padding: 5px;
    line-height: 1px;
    box-shadow: 0px 1px 7px 3px #1f1f1fb3;
  }
  .InvoiceInputField{
    padding: 0;
    width: 64px;
    height: 20px;
    margin-right: 5px;
    font-size: 10px;
  }
  input.InvoiceInputField::placeholder {
    font-size: 10px;
    text-align: center;
  }



</style>

<span id="InvoiceHeadImgIcons" class="InvoiceWidget">
  <span id="InvoiceHeadImg_AlignLeft" title="Image Align Left" class="InvoiceHeadImgIcon" style="background-image:url('https://mysunless.com/crm/assets/ckeditor/plugins/icons.png?t=KA9B');background-position:0 -1080px;background-size:auto;">&nbsp;</span>

  <span id="InvoiceHeadImg_AlignCenter" title="Image Align Center"  class="InvoiceHeadImgIcon" style="background-image:url('https://mysunless.com/crm/assets/ckeditor/plugins/icons.png?t=KA9B');background-position:0 -1056px;background-size:auto;">&nbsp;</span>

  <span id="InvoiceHeadImg_AlignRight" title="Image Align Right"  class="InvoiceHeadImgIcon" style="background-image:url('https://mysunless.com/crm/assets/ckeditor/plugins/icons.png?t=KA9B');background-position:0 -1104px;background-size:auto;">&nbsp;</span>

  <a href="<?= base_url?>/CompanyInformation#SetCompanyImage" target="_blank" title="Set Image" ><span class="InvoiceHeadImgIcon" style="background-image:url('https://mysunless.com/crm/assets/ckeditor/plugins/icons.png?t=KA9B');background-position:0 -1200px;background-size:auto;">&nbsp;</span></a>

  <input id="InvoiceHeadImg_Width" type="number" placeholder="width" class="InvoiceInputField" title="Image Width" >

  <input id="InvoiceHeadImg_Height" type="number" placeholder="height" class="InvoiceInputField" title="Image Height" >

  <span id="InvoiceHeadImgIconsReset" title="Reset Image" class="InvoiceHeadImgIcon" style="background-image:url('https://mysunless.com/crm/assets/ckeditor/plugins/icons.png?t=KA9B');background-position:0 -1008px;background-size:auto;">&nbsp;</span>

</span>

<span id="InvoiceHeadTextIcons" class="InvoiceWidget">
  <span id="InvoiceHeadText_AlignLeft" title="Text Align Left" class="InvoiceHeadImgIcon" style="background-image:url('https://mysunless.com/crm/assets/ckeditor/plugins/icons.png?t=KA9B');background-position:0 -456px;background-size:auto;">&nbsp;</span>

  <span id="InvoiceHeadText_AlignCenter" title="Text Align Center" class="InvoiceHeadImgIcon" style="background-image:url('https://mysunless.com/crm/assets/ckeditor/plugins/icons.png?t=KA9B');background-position:0 -1056px;background-size:auto;">&nbsp;</span>

  <span id="InvoiceHeadText_AlignRight" title="Text Align Right" class="InvoiceHeadImgIcon" style="background-image:url('https://mysunless.com/crm/assets/ckeditor/plugins/icons.png?t=KA9B');background-position:0 -408px;background-size:auto;">&nbsp;</span>

  <span id="InvoiceHeadText_AlignTop" title="Text Align Vertical" class="InvoiceHeadImgIcon" style="background-image:url('https://mysunless.com/crm/assets/ckeditor/plugins/icons.png?t=KA9B');background-position:0 -336px;background-size:auto;">&nbsp;</span>

  <a href="<?= base_url?>/CompanyInformation" target="_blank" title="Set Company Name" ><span class="InvoiceHeadImgIcon" style="background-image:url('https://mysunless.com/crm/assets/ckeditor/plugins/icons.png?t=KA9B');background-position:0 -888px;background-size:auto;">&nbsp;</span></a>

  <input id="InvoiceHeadText_AlignSize" title="Text Size" type="number" placeholder="Size" class="InvoiceInputField" >

  <select id="InvoiceHeadText_AlignWeight" class="InvoiceInputField" title="Text Weight">
    <option>Weight</option>
    <option value="100">100</option>
    <option value="200">200</option>
    <option value="300">300</option>
    <option value="400">400</option>
    <option value="500">500</option>
    <option value="600">600</option>
    <option value="700">700</option>
    <option value="800">800</option>
    <option value="900">900</option>
    <option value="bold">bold</option>
    <option value="bolder">bolder</option>
    <option value="lighter">lighter</option>
    <option value="normal">normal</option>
  </select> 

  <input id="InvoiceHeadText_Color" type="color" value="white" class="InvoiceInputField" title="Text Color"> 

  <input id="InvoiceHeadText_BgColor" type="color" value="#0062ff" class="InvoiceInputField" title="Background Color"> 

  <span id="InvoiceHeadTextIconsReset" title="Reset Text" class="InvoiceHeadImgIcon" style="background-image:url('https://mysunless.com/crm/assets/ckeditor/plugins/icons.png?t=KA9B');background-position:0 -1008px;background-size:auto;">&nbsp;</span>

</span>


<?php include 'scripts.php'; ?>


<script>
 $(document).ready(function() {

  $("#myModal").on('scroll click',function() {
    InvoiceHead = $("#InvoiceHeadImg").offset();
    $("#InvoiceHeadImgIcons").css({'top':InvoiceHead.top-25,'left':InvoiceHead.left});

    InvoiceHead = $("#InvoiceHeadText").offset();
    $("#InvoiceHeadTextIcons").css({'top':InvoiceHead.top-25,'left':InvoiceHead.left});
  });

  $(document).on('click','#InvoiceHeadText',function(){
    $("#InvoiceHeadTextIcons").toggleClass('InvoiceFlexbox');
  });

  $(document).on('click',"#InvoiceHeadImg",function(){
    $("#InvoiceHeadImgIcons").toggleClass('InvoiceFlexbox');
  });

  $("#myModal").on('hide.bs.modal', function() { 
    $(".InvoiceWidget").removeClass('InvoiceFlexbox');
  });


  $(document).on('keyup',"#InvoiceHeadImg_Width",function(){
    val = $(this).val();
    if(val){
      $("#InvoiceHeadImg").css("width",val+"px");
      $("#InvoiceHeadImg img").css("width",val+"px");
    }else{
      $("#InvoiceHeadImg").css("width","100px");
      $("#InvoiceHeadImg img").css("width","100px");
    }
  });

  $(document).on('keyup',"#InvoiceHeadImg_Height",function(){
    val = $(this).val();
    if(val){
      $("#InvoiceHeadImg img").css("height",val+"px")
    }else{
      $("#InvoiceHeadImg img").css("height","100px")
    }
    
  });

  $(document).on('keyup',"#InvoiceHeadText_AlignSize",function(){
    val = $(this).val();
    if(val){
      $("#InvoiceHeadText").css("font-size",val+"px")
    }else{
      $("#InvoiceHeadText").css("font-size","50px")
    }
  });

  $(document).on('change',"#InvoiceHeadText_AlignWeight",function(){
    val = $(this).val();
    if(val){
      $("#InvoiceHeadText").css("font-weight",val)
    }else{
      $("#InvoiceHeadText").css("font-size","50px")
    }
    
  });

  $(document).on('change',"#InvoiceHeadText_BgColor",function(){
    val = $(this).val();
    // alert('on color change'+val);
    $(".BackgroundTheme").css("background-color",val)
  });

  $(document).on('change',"#InvoiceHeadText_Color",function(){
    val = $(this).val();
    // alert(val);
    $("#InvoiceHeadText").css("color",val)
  });
  

  $(document).on('click',"#InvoiceHeadText_AlignRight",function(){
    $("#InvoiceHeadText").css("text-align","right")
  });

  $(document).on('click',"#InvoiceHeadText_AlignLeft",function(){
    $("#InvoiceHeadText").css("text-align","left")
  });

  $(document).on('click',"#InvoiceHeadText_AlignCenter",function(){
    $("#InvoiceHeadText").css("text-align","Center")
  });

  var alignCount = 0;
  $(document).on('click',"#InvoiceHeadText_AlignTop",function(){
    align = ['top','middle','bottom'];
    $("#InvoiceHeadText").css("vertical-align",align[alignCount]);
    alignCount++;
    if(alignCount==3){
      alignCount = 0;
    }

  });

  $(document).on('click',"#InvoiceHeadImg_AlignLeft",function(){
    ImageTD = $("#InvoiceHeadImg")[0].outerHTML;
    TextTD = $("#InvoiceHeadText")[0].outerHTML;
    $("#InvoiceHead table").html("<tr>"+ImageTD+TextTD+"</tr>");
    $("#InvoiceHeadImg").attr('data-align','left');
  });

  $(document).on('click',"#InvoiceHeadImg_AlignRight",function(){
    ImageTD = $("#InvoiceHeadImg")[0].outerHTML;
    TextTD = $("#InvoiceHeadText")[0].outerHTML;
    $("#InvoiceHead table").html("<tr>"+TextTD+ImageTD+"</tr>");
    $("#InvoiceHeadImg").attr('data-align','right');
  });

  $(document).on('click',"#InvoiceHeadImg_AlignCenter",function(){
    ImageTD = $("#InvoiceHeadImg")[0].outerHTML;
    TextTD = $("#InvoiceHeadText")[0].outerHTML;
    $("#InvoiceHead table").html("<tr>"+ImageTD+"</tr><tr>"+TextTD+"</tr>");
    $("#InvoiceHeadImg").attr('data-align','center');
  });

  var a=0;
  $(document).on('click',"#InvoiceHeadTextIconsReset",function(){    
    $("#InvoiceHeadText").attr("style","text-align: left;font-size: 50px;font-weight: bold;vertical-align: bottom;line-height: 1;padding: 0 5px;");
      $(".BackgroundTheme").css("background-color","#2196f3");      
       a=1;
  });

  $(document).on('click',"#InvoiceHeadImgIconsReset",function(){
    $("#InvoiceHeadImg_AlignLeft").trigger('click');
    $("#InvoiceHeadImg").attr("style","width: 100px;text-align: center;");
    $("#InvoiceHeadImg img").attr("style","width: 100px;height: 100px;object-fit:unset;");
  });

  function SetCSS(css){
    $("#InvoiceHead").attr("style",css.InvoiceHead);
    $("#InvoiceHeadImg").attr("style",css.InvoiceHeadImg);
    $("#InvoiceHeadImg img").attr("style",css.InvoiceHeadImg_Img);
    $("#InvoiceHeadText").attr("style",css.InvoiceHeadText);
    $(".BackgroundTheme").css("background-color",css.BackgroundTheme);
    

    $("#InvoiceHeadText_BgColor").val(css.BackgroundTheme);
    $("#InvoiceHeadText_AlignSize").val($("#InvoiceHeadText").css("font-size").replace("px",''));
    $("#InvoiceHeadText_AlignWeight").val($("#InvoiceHeadText").css("font-weight"));

    $("#InvoiceHeadImg_Width").val($("#InvoiceHeadImg img").css("width").replace("px",''));
    $("#InvoiceHeadImg_Height").val($("#InvoiceHeadImg img").css("height").replace("px",''));

    if(css.InvoiceHeadImgAlign=='left'){
      $("#InvoiceHeadImg_AlignLeft").trigger('click');
    }else if(css.InvoiceHeadImgAlign=='right'){
      $("#InvoiceHeadImg_AlignRight").trigger('click');
    }else if(css.InvoiceHeadImgAlign=='center'){
      $("#InvoiceHeadImg_AlignCenter").trigger('click');
    }

  }

  function GetCSS(){

    InvoiceHead = $("#InvoiceHead").attr("style");
    InvoiceHeadImg = $("#InvoiceHeadImg").attr("style");
    InvoiceHeadImg_Img = $("#InvoiceHeadImg img").attr("style");
    InvoiceHeadImgAlign = $("#InvoiceHeadImg").attr('data-align');
    InvoiceHeadText = $("#InvoiceHeadText").attr("style");
    BackgroundTheme = $(".BackgroundTheme").css("background-color");

    return {'InvoiceHead':InvoiceHead,'InvoiceHeadImg':InvoiceHeadImg,'InvoiceHeadImg_Img':InvoiceHeadImg_Img,'InvoiceHeadText':InvoiceHeadText,'InvoiceHeadImgAlign':InvoiceHeadImgAlign, 'BackgroundTheme':BackgroundTheme};

  }




  $("#OrderNotes").ckeditor();

  $("#viewInvoice").click(function(){

    if($("#OrderNotes").val()){
      $(".notelist").html($("#OrderNotes").val());
    }else{
      $(".notelist").html('<ol> <li> This is auto computer printed invoice. </li> <li> If you have any problem with this invoice please contact with admin. </li> <li> The goods sold will not be returned. </li> </ol>');
    }

    if($("#InvoiceFooter").val()){
      $(".last").html($("#InvoiceFooter").val());
    }else{
      $(".last").html('<h4> Thank you MySunless. </h4>');
    }

  });

  $("#InvoiceFooter").ckeditor(function(){

   $.ajax({
     url:'?get_temp=get_temp',
     dataType: 'JSON',
     success: function(data)
     {

      if(data.response.order_note){
        var Temp = JSON.parse(data.response.order_note);

        if(Temp.note){
          $("#OrderNotes").val(Temp.note);
        }
        if(Temp.footer){
          $("#InvoiceFooter").val(Temp.footer);
        }
        if(Temp.css){
          SetCSS(Temp.css);
        }
      }

        if(data.response.CompanyName){
          $("#InvoiceHeadText").text(data.response.CompanyName);
        }

        if(data.response.compimg){
          $("#InvoiceHeadImg img").attr('src','<?= base_url?>/assets/companyimage/'+data.response.compimg);
        }

        if(data.response.mysign){
          $("#InvoiceSign").attr('src','<?= base_url?>/assets/sing/'+data.response.mysign);
        }

      
    }
  });

 });

  $(".NewTempSubmit").click(function(e){
    e.preventDefault();
    var data = {note:$("#OrderNotes").val(),footer:$("#InvoiceFooter").val(),css:GetCSS()};
    $.ajax({
     dataType:"json",
     type:"post",
     data: {addtemp:JSON.stringify(data)},
     url:'?action=addtemp',
     success: function(data)
     {
      if(data.response){
        swal("",data.response,"success");
      }else{
        swal("",data.error,"error");
      }
      setTimeout(function () { window.location.reload(); }, 3000);
    }
  });
  });

  $('#invoicedownload').click(function(){

    var data = $("#Invoice").html();
    $(".Loader").show();
    $.ajax({
      dataType:"json",
      type:"post",
      data: {InvoiceData:data,CustomerMail:'test@MySunless.com'},
      url:'?TEST_INVOICE&download_pdf',
      success: function(data)
      {
        $(".Loader").hide();
        window.open('<?= base_url?>/InvoiceTemplate?Download='+data.response, '_blank');
      }
    });
    
  });

  $(document).on('click','#sendinvoice_pre,#sendinvoice',function(ec){
    ec.preventDefault();

    if(ec.target.id=='sendinvoice_pre'){
      $("#InvoiceModel_changemail").modal('show');
      return false;
    }else{
      var CustomerMail = $('#customernewmail').val();

      var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      if(!regex.test(CustomerMail)) {
        swal("Format Invalid of selected Email for Invoice.");
        return false;
      }   


      $(".Loader").show();
      $("#InvoiceModel_changemail").modal('hide');
      var data = $("#Invoice").html();
      $.ajax({
        dataType:"json",
        type:"post",
        data: {InvoiceData:data,CustomerMail:CustomerMail},
        url:'?TEST_INVOICE',
        success: function(data)
        {
          if(data.response){
            swal("Send",data.response, "success");
          }else{
            swal("",data.error, "error");
          }
          $(".Loader").hide();
        }
      });
    }
  });


});
function PrintInvoice() {
  var divToPrint = document.getElementById('Invoice');
  var popupWin = window.open('', '_blank', 'width=950,height=600');
  popupWin.document.open();
  popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
  popupWin.document.close();
}
</script>

</body>
</html>