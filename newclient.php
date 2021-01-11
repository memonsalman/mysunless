 
 <div class="modal fade" id="myModal_addclientx" role="dialog" style="overflow-y:auto;z-index: 1041;">
    <div class="modal-dialog">
    
      <!-- ========================Add client============================== -->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Customer Details</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
              <div class="Loader"></div>
              <form class="form-horizontal" action="" autocomplete="off" method="post" id="NewClientx">
                  <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                  <input type="hidden" name="id" id="id" value="new">
                  <input type="hidden" name="clinetid" id="" value="">
                  <?php 
                  if($usertype=='subscriber')
                  {
                  ?>
                  <input type="hidden" name="sid" id="sid" value="<?php echo $_SESSION['UserID'];?>">
                  <?php
                  }
                  else
                  {
                  ?>
                  <input type="hidden" name="sid" id="sid" value="<?php echo $sid;?>">
                  <?php
                  }
                  ?>

        <div class="form-group">
        <label><span class="help"> First Name *</span></label>
        <input type="text" name="FirstName" id="FirstName" class="form-control" placeholder="First Name" autocomplete="nope" value="" maxlength="10">
        </div> 

        <div class="form-group">
        <label><span class="help"> Last Name *</span></label>
        <input type="text" name="LastName" id="LastName" class="form-control" autocomplete="nope" value="" placeholder="Last Name" maxlength="10">
        </div>

        <div class="form-group">
        <label><span class="help"> Phone Number *</span></label> 
      <input type="text" autocomplete="nope"  name="Phone" id="phonenumberx"  class="form-control" value="" placeholder="(123) 456-7890">
        </div>
          
        <div class="form-group">
        <label for="example-email">Email * <span class="help"></span></label>
      <input type="email" id="example-email" name="email" class="form-control" placeholder="Email" value="" autocomplete="nope" placeholder="exaple@gmial.com" maxlength="30">
        </div>

        <div class="form-group">
        <label for="example-email">Profile Photo (jpg/jpeg)<span class="help"></span></label>
        <div class="card">
        <div class="card-body">
<input type="file" id="ProfileImg" name="ProfileImg" class="dropify" >
<input type="hidden" name="ProfileImg"id="oldimage" value="">
<input type="hidden" id="ProfileImg2" name="ProfileImg2" class="">
<input type="hidden" id="ProfileImg3" name="ProfileImg3" class="">
        </div>
        </div>
         </div>
  <!-- <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  
      <img src="http://lorempixel.com/75/50/abstract/">Select Avtar<span class="glyphicon glyphicon-chevron-down"></span></button> -->
   <button type="button" class="btn btn-default" id="addcusomimagebutton" style="width: 45%; margin-bottom: 20px; float: left; margin-right: 10px;"> Upload Image</button>


<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 45%;margin-bottom: 20px; margin-left: 10px;"> Select Avtar<span class="glyphicon glyphicon-chevron-down"></span></button>


<div class="dropdown-menu" style="width: 96%;">
<?php
  $stmta= $db->prepare("SELECT * FROM `listofavtar`"); 
  $stmta->execute();
  $stmtall = $stmta->fetchAll(PDO::FETCH_ASSOC);
foreach($stmtall as $row)
{
  ?>
<label style="padding: 5px;">
  <input type="radio" name="ProfileImg" class="avtr" value="<?php echo $row['Name']; ?>">
  <img src="<?php echo base_url.'/assets/ProfileImages/'.$row['Name'];?>" width= "50px" height="50px">
</label>
<?php

}
?>
</div>

        <div class="form-group">
        <label for="example-email">Street Address *</label>
      <input autocomplete="nope" id="autocompletex"  placeholder="Enter your address"  class="form-control" name="Address"  onFocus="geolocate1()" type="text" value=""></input>
        <input type="hidden" value="" placeholder="Enter your address"   id="street_number" disabled="true"></input>
        <input type="hidden" value="" placeholder="Enter your address" id="route" disabled="true"></input>  
        </div>

        <div class="form-group">
        <label for="example-email">Zip Code *</label>
        <input type="text"  id="postal_code" name="Zip" value="" class="form-control" autocomplete="nope" placeholder="0123456"></input>
        </div>

        <div class="form-group">
        <label for="country">Country *</label>
        <select class="form-control" id="country" autocomplete="nope" name="Country">
        <option value="">Select a Country</option>
        <?php
          foreach($countryList as $value)
          {
          echo "<option value='".$value['countries_name']."'>".$value['countries_name']."</option>";
          }
          
          ?>
          </select>
          </div>

          <div class="form-group">
          <label><span class="help">State *</span></label>
          <select class="form-control" id="administrative_area_level_1" autocomplete="nope" name="State">
          <option value="">Select a State</option>
          <?php
          foreach($stateList as $value)
          {
          echo "<option value='".$value['name']."'>".$value['name']."</option>";
          }
          
          ?>
          </select>
          </div>
          
          <div class="form-group">
          <label for="example-email">City *</label>
          <input  id="locality" name="City" value="" class="form-control" autocomplete="nope" placeholder="City"></input>
          </div>


          <div class="form-group">
  <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" autocomplete="nope" name="add-client" id="add-client"><i class="fa fa-check"></i> Add Client</button>
          </div>
              </form>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
<!-- ========================Add client============================== -->

<!-- ========================Crop image============================== -->
<div id="uploadimageModalx" class="modal" role="dialog" style="z-index: 1042;">
  <div class="modal-dialog">
    <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title">Upload & Crop Image</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
            <div class="col-md-12 text-center">
              <div id="image_demo"></div>
            </div>
            <div class="col-md-12" style="text-align: center;">
              <br />
              <br />
              <br/>
              <button class="btn btn-success crop_image">Crop & Upload Image</button>
              <button type="button" class="btn btn-default" data-dismiss="modal"> Skip </button>
          </div>
        </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
      </div>
    </div>
</div>