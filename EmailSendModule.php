<?php

require_once('function.php');

if(isset($_GET['createtemp']))
{
  $id = $_SESSION['UserID'];
  $RelatedTo2 = $db->prepare("SELECT * FROM `EmailTempleate` WHERE createdfk IN ( select id from users where id=:id or adminid=:id or sid=:id ) ");
  $RelatedTo2->bindValue(":id",$id);
  $RelatedTo2->execute();
  $all_Templeate=$RelatedTo2->fetchAll();
  if($RelatedTo2)
  {
    echo json_encode(["response"=>$all_Templeate]);
  }
  die();

}

?>
<!-- EmailSendModule.php -->
<div id="composeModal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="max-width: 950px">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Send Mail</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="NewMail" method="post">
          <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
          <div class="form-group">
            <label>From *</label>
            <input type="text" name="From" id="From" class="form-control" placeholder="From" value="<?php echo $mailFrom; ?>">
          </div>
          <div class="form-group">
            <label>To *</label>
            <!-- <input type="text" name="To" id="To" class="form-control" placeholder="To" value=""> -->
            <select class="select2 m-b-10 select2-multiple form-control" data-placeholder="Contact Email" id="To" name="To[]" multiple data-style="form-control tn-secondary">
              <?php 
              $UserID = $_SESSION['UserID'];
              $total_clients = $db->prepare("select * from clients where createdfk in (Select DISTINCT(u2.id) from users u1 join users u2 on u1.id=u2.id or u1.adminid=u2.id or u1.id=u2.adminid where u1.id IN (:id) ) order by clients.FirstName");
              $total_clients->bindParam(':id', $UserID, PDO::PARAM_INT);
              $total_clients->execute();
              $all_client=$total_clients->fetchAll(PDO::FETCH_ASSOC);
              foreach($all_client as $row)
              {
                ?>
                <option value="<?php echo $row['email'].','.$row['id']?>"><?php echo $row['FirstName']." ".$row['LastName']; ?></option>
                <?php
              }
              ?>
            </select> 
            <input type="hidden" name="ccid" id="ccid" value="">
            <input type="hidden" name="type" id="type" value="email">
          </div>

          <div class="form-group">
            <label>Template *</label>
            <select class="Templeate form-control select2"  id="Templeate" name="Templeate">
             <option value="">Select Email Template</option>
             <?php 
             $UserID = $_SESSION['UserID'];
             $RelatedTo2 = $db->prepare("SELECT * FROM `EmailTempleate` WHERE createdfk IN ( select id from users where id=:id or adminid=:id or sid=:id )");
             $RelatedTo2->bindValue(":id",$UserID,PDO::PARAM_INT);
             $RelatedTo2->execute();
             $all_Templeate=$RelatedTo2->fetchAll(PDO::FETCH_ASSOC);
             foreach($all_Templeate as $row)
             {
              ?>
              <option value="<?php echo $row['id']?>"><?php echo $row['Name']; ?></option>
              <?php
            }
            ?>
          </select> 
          <span style="float: right;margin-top: 10px;" ><a href="javascript(0)"  id="createtem" data-toggle="modal" data-target="#myModal_emailtemp">Create Template</a></span>

        </div>

        <div class="form-group">
          <label>Subject *</label>
          <input type="text" name="Subject" id="Subject" class="form-control" placeholder="Subject" value="" maxlength="30">
        </div>
        <div class="form-group">
          <div style="display: none;position: absolute; right: 20px;" class="alert alert-success alert-dismissible copyalert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          </div>
          <label>Message *</label>
          <textarea class="textarea_editor form-control" rows="10" placeholder="Enter Message ..." id="Message" name="Message"></textarea>
        </div>
        <div class="Loader"></div>
        <div class="form-group">
         <button type="submit" name="send" class="btn waves-effect waves-light btn-info m-r-10" id="send"><i class="fa fa-check"></i>Send</button>
         <button type="button" class="btn btn-secondary" data-dismiss="modal"> Cancel</button>
       </div>
     </form>
   </div>
 </div>
</div>
</div>

<div class="modal fade" id="myModal_emailtemp" role="dialog">
  <div class="modal-dialog" style="max-width: 950px">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Email Template</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" autocomplete="off" id="NewEmailTemp" method="post">
          <input type="hidden" name="id" id="id" value="new">
          <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
          <div class="form-group">
            <label>Name  * </label>
            <input type="text" name="Name" id="Name" class="form-control" placeholder="Enter Name" value="" maxlength="30">
          </div>
          <div class="form-group">
            <label>Subject  * </label> 
            <input type="text" name="Subject" id="Subject" class="form-control" placeholder="Subject" value="" maxlength="30">
          </div>
          <div class="form-group">
            <div style="display: none;position: absolute; right: 20px;" class="alert alert-success alert-dismissible copyalert">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            </div>
            <label>Message  * </label>
            <textarea class="textarea_editor form-control" rows="15" id="TextMassage" name="TextMassage"  placeholder="Enter text ..."></textarea>
          </div>
          <div class="Loader"></div>
          <div class="form-group">
            <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add-client"><i class="fa fa-check"></i> Create Template </button>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  var communicationTable;
  $(document).ready(function() {
    $(document).on("click", "*[data-target='#myModal_emailtemp']", function () {
      $("#NewEmailTemp #id").val('new');
      $("#TextMassage").val('');
      $( '#NewEmailTemp' ).each(function(){
        this.reset();
      });
    });


    $("#NewMail").validate({
      ignore: ":hidden:not(textarea)",
      rules: {                
        From: {required: true,},
        "To[]": {required: true,},
        Subject: {required: true,},
        Message: {required: true,},
      },
      messages: {    
        From: {required: "Please enter your email id"},
        "To[]": {required: "Please select at least one recipient."},
        Subject: {required: "Please enter email subject"},
        Message: {required: "Please enter email message"},
      },
      errorPlacement: function( label, element ) {
        if( element.attr( "name" ) === "Message" || element.attr( "name" ) === "To[]" ) {
          element.parent().append( label );
        } else {
         label.insertAfter( element );
       }
     },
     submitHandler: function() {
      var data = $("#NewMail").serialize();
      jQuery.ajax({
       dataType:"json",
       type:"post",
       data:data,
       url:'<?php echo EXEC; ?>Exec_AllMail',
       beforeSend: function() {
        $(".Loader").show();
      },
      success: function(data)
      {   
        $(".Loader").hide();
        $( '#NewMail' ).each(function(){
          this.reset();
        });
        $("#composeModal").modal('hide');
        if(data.resonse_mail)
        {
          swal("",data.resonse_mail,"success");
        }
        else if(data.error_mail)
        {
          swal("",data.error_mail,"error");
        }
        $(".communicationTableRefresh").trigger("click");
      }
    });
    }           
  });

    $("#NewEmailTemp").validate({
      ignore: ":hidden:not(textarea)",
      rules: {                
        Name: {required: true,},
        Subject: {required: true,},
        TextMassage: {required: true,}
      },
      messages: {             
        Name: {required: "Please enter templeate name"},
        Subject: {required: "Please enter subject"},
        TextMassage: {required: "Please enter message"}
      },
      errorPlacement: function( label, element ) {
        if( element.attr( "name" ) === "TextMassage" ) {
          element.parent().append( label );
        } else {
         label.insertAfter( element );
       }
     },
     submitHandler: function() {
      $(".Loader").show();

      var data = $("#NewEmailTemp").serialize();
      data= data + "&LoginAction=Login";
      jQuery.ajax({
        dataType:"json",
        type:"post",
        data:data,
        url:'<?php echo EXEC; ?>Exec_Edit_EmailTemp',
        success: function(data)
        {
          if(data.resonse)
          {
            swal("",data.resonse,"success");
            $( '#NewEmailTemp' ).each(function(){
              this.reset();
            });
            $(".Loader").hide();

            $('#myModal_emailtemp').modal('hide');
            addalltemp();
            $("#refreshTemplate").trigger("click");          
          }
          else if(data.error)
          {
            swal("",data.error,"error");
            $(".Loader").hide();
          }
          else if(data.csrf_error)
          {
            swal("",data.csrf_error,"info");
            $(".Loader").hide();
            setTimeout(function () { window.location.reload() }, 2000)
          }
        }
      });
    }           
  });

    $('#Templeate').on('change',function(){
     $(".Loader").show();
     tid=$(this).val();
     $.ajax({
       dataType:"json",
       type:"post",
       data: {'tid':tid},
       url:'?action=editfile',
       success: function(data)
       {
        if(data)
        {
          $(".Loader").hide();
          $('#Subject').val(data.resonse.Subject);
          $("#Message").val(data.resonse.TextMassage);       
        }
        else if(data.error)
        {
          swal('',data.error,'error');  
        }
      }
    })
   });

    $("#Message").ckeditor();
    $("#TextMassage").ckeditor();


    function copyToClipboard(element) {
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(element).text()).select();
      document.execCommand("copy");
      $temp.remove();

      $(".copyalert").show();
      $(".copyalert").text('COPIED! '+$(element).text());
      $(".copyalert").delay(2000).fadeOut();
    }

    CKEDITOR.instances.Message.on("instanceReady", function(event)
    {


      $("#cke_1_toolbox").append('<span class="dropdown" style="float:left; margin:5px;cursor:pointer"> <a class="dropdown-toggle" data-toggle="dropdown" href="#">ShortCode&nbsp;<b class="caret"></b></a> <ul style="width: auto;background: white;padding:10px" class="dropdown-menu"> <li class="dropdown shortcode"><a class="btn" id="insertshortcode1" title="Customer&apos;s First Name">{{ first_name }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcode2" title="Customer&apos;s Last Name">{{ last_name }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcode3" title="Customer&apos;s Email">{{ customer_email }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcode4" title="Customer&apos;s Phone">{{ phone }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcode5" title="Customer&apos;s Street">{{ location.street }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcode6" title="Customer&apos;s City">{{ location.city }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcode7" title="Customer&apos;s State">{{ location.state }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcode8" title="Customer&apos;s Country">{{ location.country }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcode9" title="Customer&apos;s Zip">{{ location.zip }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcode11" title="Insert ShortCode">{{ owner.signature }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcode10" title="Current Date">{{ current_date }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcode12" title="Company Phone">{{ company_phone }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcode13" title="Comapany Name">{{ company_name }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcode14" title="Company Address">{{ company_address }}</a></li><li class="dropdown shortcode"><a class="btn" id="insertshortcode15" title="Company Email">{{ company_email }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcode16" title="Booking URL">{{ user_booking_url }}</a></li> </ul> </span>');


      $("#insertshortcode1").click(function(e){
        e.preventDefault();
        copyToClipboard("#"+$(this).attr('id'));
        $('#Message').ckeditor().editor.insertText('{{ first_name }}');
      });

      $("#insertshortcode2").click(function(e){
        e.preventDefault();
        copyToClipboard("#"+$(this).attr('id'));
        $('#Message').ckeditor().editor.insertText('{{ last_name }}');
      });

      $("#insertshortcode3").click(function(e){
        e.preventDefault();
        copyToClipboard("#"+$(this).attr('id'));
        $('#Message').ckeditor().editor.insertText('{{ customer_email }}');
      });

      $("#insertshortcode4").click(function(e){
        e.preventDefault();
        copyToClipboard("#"+$(this).attr('id'));
        $('#Message').ckeditor().editor.insertText('{{ phone }}');
      });

      $("#insertshortcode5").click(function(e){
        e.preventDefault();
        copyToClipboard("#"+$(this).attr('id'));
        $('#Message').ckeditor().editor.insertText('{{ location.street }}');
      });


      $("#insertshortcode6").click(function(e){
        e.preventDefault();
        copyToClipboard("#"+$(this).attr('id'));
        $('#Message').ckeditor().editor.insertText('{{ location.city }}');
      });

      $("#insertshortcode7").click(function(e){
        e.preventDefault();
        copyToClipboard("#"+$(this).attr('id'));
        $('#Message').ckeditor().editor.insertText('{{ location.state }}');
      });

      $("#insertshortcode8").click(function(e){
        e.preventDefault();
        copyToClipboard("#"+$(this).attr('id'));
        $('#Message').ckeditor().editor.insertText('{{ location.country }}');
      });

      $("#insertshortcode9").click(function(e){
        e.preventDefault();
        copyToClipboard("#"+$(this).attr('id'));
        $('#Message').ckeditor().editor.insertText('{{ location.zip }}');
      });

      $("#insertshortcode10").click(function(e){
        e.preventDefault();
        copyToClipboard("#"+$(this).attr('id'));
        $('#Message').ckeditor().editor.insertText('{{ current_date }}');
      });

      $("#insertshortcode11").click(function(e){
        e.preventDefault();
        copyToClipboard("#"+$(this).attr('id'));
        $('#Message').ckeditor().editor.insertText('{{ owner.signature }}');
      });

      $("#insertshortcode12").click(function(e){
        e.preventDefault();
        copyToClipboard("#"+$(this).attr('id'));
        $('#Message').ckeditor().editor.insertText('{{ company_phone }}');
      });

      $("#insertshortcode13").click(function(e){
        e.preventDefault();
        copyToClipboard("#"+$(this).attr('id'));
        $('#Message').ckeditor().editor.insertText('{{ company_name }}');
      });

      $("#insertshortcode14").click(function(e){
        e.preventDefault();
        copyToClipboard("#"+$(this).attr('id'));
        $('#Message').ckeditor().editor.insertText('{{ company_address }}');
      });

      $("#insertshortcode15").click(function(e){
        e.preventDefault();
        copyToClipboard("#"+$(this).attr('id'));
        $('#Message').ckeditor().editor.insertText('{{ company_email }}');
      });
      $("#insertshortcode16").click(function(e){
        e.preventDefault();
        copyToClipboard("#"+$(this).attr('id'));
        $('#Message').ckeditor().editor.insertText('{{ user_booking_url }}');
      });

    });

CKEDITOR.instances.TextMassage.on("instanceReady", function(event)
{


  $("#cke_2_toolbox").append('<span class="dropdown"> <a style="padding:10px" class="btn dropdown-toggle" data-toggle="dropdown" href="#">ShortCode&nbsp;<b class="caret"></b></a> <ul style="width: auto;background: white;padding:10px" class="dropdown-menu"> <li class="dropdown shortcode"><a class="btn" id="insertshortcodet1" title="Customer&apos;s First Name">{{ first_name }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcodet2" title="Customer&apos;s Last Name">{{ last_name }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcodet3" title="Customer&apos;s Email">{{ customer_email }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcodet4" title="Customer&apos;s Phone">{{ phone }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcodet5" title="Customer&apos;s Street">{{ location.street }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcodet6" title="Customer&apos;s City">{{ location.city }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcodet7" title="Customer&apos;s State">{{ location.state }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcodet8" title="Customer&apos;s Country">{{ location.country }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcodet9" title="Customer&apos;s Zip">{{ location.zip }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcodet11" title="Insert ShortCode">{{ owner.signature }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcodet10" title="Current Date">{{ current_date }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcodet12" title="Company Phone">{{ company_phone }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcodet13" title="Comapany Name">{{ company_name }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcodet14" title="Company Address">{{ company_address }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcodet15" title="Company Email">{{ company_email }}</a></li> <li class="dropdown shortcode"><a class="btn" id="insertshortcodet16" title="Booking URL">{{ user_booking_url }}</a></li></ul> </span>');


  $("#insertshortcodet1").click(function(e){
    e.preventDefault();
    copyToClipboard("#"+$(this).attr('id'));
    $('#TextMassage').ckeditor().editor.insertText('{{ first_name }}');
  });

  $("#insertshortcodet2").click(function(e){
    e.preventDefault();
    copyToClipboard("#"+$(this).attr('id'));
    $('#TextMassage').ckeditor().editor.insertText('{{ last_name }}');
  });

  $("#insertshortcodet3").click(function(e){
    e.preventDefault();
    copyToClipboard("#"+$(this).attr('id'));
    $('#TextMassage').ckeditor().editor.insertText('{{ customer_email }}');
  });

  $("#insertshortcodet4").click(function(e){
    e.preventDefault();
    copyToClipboard("#"+$(this).attr('id'));
    $('#TextMassage').ckeditor().editor.insertText('{{ phone }}');
  });

  $("#insertshortcodet5").click(function(e){
    e.preventDefault();
    copyToClipboard("#"+$(this).attr('id'));
    $('#TextMassage').ckeditor().editor.insertText('{{ location.street }}');
  });


  $("#insertshortcodet6").click(function(e){
    e.preventDefault();
    copyToClipboard("#"+$(this).attr('id'));
    $('#TextMassage').ckeditor().editor.insertText('{{ location.city }}');
  });

  $("#insertshortcodet7").click(function(e){
    e.preventDefault();
    copyToClipboard("#"+$(this).attr('id'));
    $('#TextMassage').ckeditor().editor.insertText('{{ location.state }}');
  });

  $("#insertshortcodet8").click(function(e){
    e.preventDefault();
    copyToClipboard("#"+$(this).attr('id'));
    $('#TextMassage').ckeditor().editor.insertText('{{ location.country }}');
  });

  $("#insertshortcodet9").click(function(e){
    e.preventDefault();
    copyToClipboard("#"+$(this).attr('id'));
    $('#TextMassage').ckeditor().editor.insertText('{{ location.zip }}');
  });

  $("#insertshortcodet10").click(function(e){
    e.preventDefault();
    copyToClipboard("#"+$(this).attr('id'));
    $('#TextMassage').ckeditor().editor.insertText('{{ current_date }}');
  });

  $("#insertshortcodet11").click(function(e){
    e.preventDefault();
    copyToClipboard("#"+$(this).attr('id'));
    $('#TextMassage').ckeditor().editor.insertText('{{ owner.signature }}');
  });

  $("#insertshortcodet12").click(function(e){
    e.preventDefault();
    copyToClipboard("#"+$(this).attr('id'));
    $('#TextMassage').ckeditor().editor.insertText('{{ company_phone }}');
  });

  $("#insertshortcodet13").click(function(e){
    e.preventDefault();
    copyToClipboard("#"+$(this).attr('id'));
    $('#TextMassage').ckeditor().editor.insertText('{{ company_name }}');
  });

  $("#insertshortcodet14").click(function(e){
    e.preventDefault();
    copyToClipboard("#"+$(this).attr('id'));
    $('#TextMassage').ckeditor().editor.insertText('{{ company_address }}');
  });
  $("#insertshortcodet15").click(function(e){
    e.preventDefault();
    copyToClipboard("#"+$(this).attr('id'));
    $('#TextMassage').ckeditor().editor.insertText('{{ company_email }}');
  });
  $("#insertshortcodet16").click(function(e){
    e.preventDefault();
    copyToClipboard("#"+$(this).attr('id'));
    $('#TextMassage').ckeditor().editor.insertText('{{ user_booking_url }}');
  });

});



});

addalltemp();
function addalltemp()
{
  $.ajax({
   dataType:"json",
   url:'EmailSendModule?createtemp',
   success: function(data)
   {
    $('#Templeate').html('<option >Select Template</option>');
    if(data.response)
    {
      $.each(data.response, function (key, val) 
      {

       $('#Templeate').append('<option  value="'+val.id+'">'+ val.Name +'</option>');
     });
    }
  }
});
}
</script>
