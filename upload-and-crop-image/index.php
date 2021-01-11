<?php
require_once($_SERVER['DOCUMENT_ROOT']."/crm/function.php");
?>
<html>  
    <head>  
        <title>Make Price Range Slider using JQuery with PHP Ajax</title>  
		
		<script src="jquery.min.js"></script>  
		<script src="bootstrap.min.js"></script>
		<script src="croppie.js"></script>
		<link rel="stylesheet" href="bootstrap.min.css" />
		<link rel="stylesheet" href="croppie.css" />
    <link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/dropify.min.css">
    </head>  
    <body>  
        <div class="container">
          <br />
      <h3 align="center">Image Crop & Upload using JQuery with PHP Ajax</h3>
      <br />
      <br />
			<div class="panel panel-default">
  				<div class="panel-heading">Select Profile Image</div>
  				<div class="panel-body" align="center">
  					<!-- <input type="file" name="upload_image" id="upload_image" /> -->
            <input type="file" id="upload_image" name="userimg" class="dropify" data-default-file="<?php echo base_url; ?>/assets/images/noimage.png"/>
  					<br />
  					<div id="uploaded_image"></div>
  				</div>
  			</div>
  		</div>
    </body>  
</html>

<div id="uploadimageModal" class="modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		  <h4 class="modal-title">Upload & Crop Image</h4>
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

<script>  
$(document).ready(function(){

  $image_crop = $('#image_demo').croppie({
    enableExif: true,
    viewport: {
      width:200,
      height:200,
      type:'square' //circle
    },
    boundary:{
      width:300,
      height:300
    }
  });

  $('#upload_image').on('change', function(){
    var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop.croppie('bind', {
        url: event.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('show');
  });

  $('.crop_image').click(function(event){

      $image_crop.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function(response){
      $.ajax({
        url:"upload.php",
        type: "POST",
        data:{"image": response},
        dataType:"json",
        success:function(data)
        {
          
          $('#uploadimageModal').modal('hide');
          // $('#uploaded_image').html(data);
           $("#upload_image").attr("data-default-file", "<?php echo base_url; ?>/upload-and-crop-image/CustomerTep/"+data.resonse+"");
                            $('<img src="<?php echo base_url; ?>/upload-and-crop-image/CustomerTep/'+data.resonse+'" id="pImage">').appendTo(".dropify-render");
                            $( ".dropify-render img" ).first().css( "display", "none" );
                            $('.dropify-filename-inner').text(data.resonse)
        }
      });
    })
  });

});  
</script>
<script src="<?php echo base_url; ?>/assets/js/dropify.min.js"></script>
<script>
   $(document).ready(function() {
      // Basic
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
         console.log('Has Errors');
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
   });
</script>