<div id="myModal_appointmenthead" class="modal fade" role="dialog">
  <div class="modal-dialog">
  <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Select Service</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <!-- <p>Some text in the modal.</p> -->
        <div class="Loader"></div>
        <div class="form-group">
          <label for="listofcatagory">Select Service *</label>
          <select class="select2 m-b-10 select2-multiple" style="width: 100%"  data-placeholder="Choose Service" id="listofcatagory" name="listofcatagory">
            <option value="">Select Service</option>
            <?php 
              foreach($result_event2 as $row2)
              {
            ?>
              <option value="<?php echo $row2['id']; ?>"><?php echo $row2['ServiceName']; ?></option>
            <?php
              }
            ?>
          </select>
          <span style="color: red" id="listofcatagory_error"></span>
          <div class="col-sm-12" style="padding: 20px 0;">
            <div class="col-sm-6 pull-right">
              <input type="radio" id="Location_radio" name="Location_radio" value="Salon Location" class=""> Salon Location
            </div>
            <div class="col-sm-6 pull-right">
              <input type="radio" id="Location_radio" name="Location_radio" value="Customer Location" class=""> Customer Location
            </div>
          </div>
          <span style="color: red" id="listofcatagory_error_Location"></span>
          <div class="form-group serviceproviderblock" style="padding: 15px 0;">
            <select class="select2 m-b-10 select2-multiple" style="width: 100%"  data-placeholder="Choose Service Provider" id="listofcatagory3" name="listofcatagory3">
              <option value="">Select Provider</option>
            </select>
            <span style="color: red" id="serviceprovider_error"></span>
          </div>
        </div>
        <?php
          $db5 = new db();
          $id=$_SESSION['UserID'];
          $total_user = $db5->prepare("SELECT sid FROM `clients` WHERE `createdfk`=:id");
          $total_user->bindParam(':id', $id, PDO::PARAM_INT);
          $total_user->execute();
          $all=$total_user->fetch(PDO::FETCH_ASSOC);
          $mysid=$all['sid'];

          if($mysid!=0)
          {
             $db5 = new db();
             $id=$_SESSION['UserID'];
             $total_user2 = $db5->prepare("SELECT * FROM `clients` WHERE `sid`=:mysid");
             $total_user2->bindParam(':mysid', $mysid, PDO::PARAM_INT);
             $total_user2->execute();
             $number_of_users = $total_user2->rowCount();
          }

          if($ClientsLimit=='full')
          {
        ?>
        <button type="button" class="btn waves-effect waves-light btn-secondary myModal_new" onclick="$('#NewEvent input[name=\'id\']').val('new');" data-toggle="modal" data-target="#myModal_new"><?php echo $B132x; ?></button>           
        <?php
          }
          else
          {
            if(@$number_of_users >= @$ClientsLimit)
            {
        ?>
        <button type="button" class="btn waves-effect waves-light btn-secondary ClientLimit" onclick="$('#NewEvent input[name=\'id\']').val('new');" data-toggle="" data-target=""><?php echo $B132x; ?></button>
        <?php
            }
            else
            {
        ?>
        <button type="button" class="btn waves-effect waves-light btn-primary myModal_new" onclick="$('#NewEvent input[name=\'id\']').val('new');" data-toggle="modal" data-target="#myModal_new"><?php echo $B132x; ?></button>
        <?php
            }
          }
        ?>
        <button type="submit" onclick="$('#NewEvent input[name=\'id\']').val('new');" class="btn waves-effect waves-light btn-primary" id="exit-client"><?php echo $B142x; ?></button>
        <?php 
          if($result_noofserveiv<=0)
          {
        ?>
        <a href=" <?php echo base_url;  ?>/viewService.php" id="addUser" class="btn btn-info m-r-10 ">Add New Service</a>
        <?php
          }
        ?>
        <div style="padding: 5px 0;">
        </div>
        <div class="listofclientdiv">
          <div class="form-group">
            <select class="form-control" id="listofclient" name="listofclient" class="listofclient">
            </select>
          </div> 
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



  <div class="modal fade" id="myModal_exit2head"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 1100px;">
      <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Book Appointment with</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form class="form-horizontal" autocomplete="off" id="NewEventhead" method="post">
        <input type="hidden" name="id" class="id" id="id" value="new">
        <input type="hidden" name="Location_radio_value" id="evnet_Location_radio" value="">
        <input type="hidden" name="UserID" id="UserID"  value="<?php echo $_SESSION['UserID']; ?>">
        <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
        <input type="hidden" name="title" id="title" class="form-control" value="" placeholder="Appointment Title" autocomplete="nope" maxlength="20">
        <input type="hidden" name="cid" id="cid" value="">
        <input type="hidden" name="FirstName" id="FirstName" class="form-control" value="" placeholder="First Name" autocomplete="nope" maxlength="10">
        <input type="hidden" name="LastName" id="LastName" class="form-control" value="" placeholder="Last Name" autocomplete="nope" maxlength="10">
        <input type="hidden" name="Phone" id="Phone" class="form-control" value="" autocomplete="nope" placeholder="(123) 456-7890">
        <input type="hidden" id="Email" name="Email" class="form-control" value="" autocomplete="nope" placeholder="example@gmail.com" maxlength="30">
        <input type="hidden" name="Address" id="Address" class="form-control" value="" autocomplete="nope" placeholder="Enter your Address">
        <input type="hidden" name="Zip" id="Zip" class="form-control" autocomplete="nope" placeholder="Zip" value="" maxlength="10">
        <input type="hidden" name="country" id="newcountry" class="form-control" autocomplete="nope" placeholder="country" value="">
        <input type="hidden" name="State" id="State"  autocomplete="nope" class="form-control" placeholder="State" value="">
        <input type="hidden" name="City" id="City" autocomplete="nope" class="form-control" placeholder="City" value="">
        <input type="hidden" name="ServiceName" class="form-control" placeholder="ServiceName" id="ServiceName" autocomplete="nope" value="">
        <input type="hidden" name="ServiceProvider" class="form-control" placeholder="ServiceProvider" id="ServiceProvider" autocomplete="nope" value="">
        <input type="hidden" name="wdayshidden" id="wdayshidden" value="">
        <input type="hidden" name="wdateshidden" id="wdateshidden" value="">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4 col-sm-12">
              <div class="form-group">
                <label for="listofcatagory" id="servicewith">Service with *</label>
                  <select class="select2 m-b-10 select2-multiple" style="width: 100%"  data-placeholder="Choose Service" id="newlistofcatagory">
                    <option value="">Select Service</option>
                    <?php 
                      foreach($result_event2 as $row2)
                        {
                    ?>
                    <option value="<?php echo $row2['id']; ?>"><?php echo $row2['ServiceName']; ?></option>
                    <?php } ?>
                  </select>
              </div>
              <div class="form-group">
                <label for="example-email">Appointment Date/Time  *<span class="help"></span></label>
                <p id="datepairExample">
                  <input type="text" class="date start form-control" placeholder="Start Date" name="sd" autocomplete="nope"  id="eventstardate" />
                  <input type="text" placeholder="Start Time" class= "time start form-control" name="st" autocomplete="nope" id="eventstartime" />

                  <input type="hidden" class="time end form-control" placeholder="End Time" name="et" autocomplete="nope" id="eventendtime"  />
                  <input type="hidden" class="date start form-control" placeholder="End Date" name="ed" autocomplete="nope"  id="eventenddate" />
                </p>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Cost of Service: * </label> 
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                      </div>
                      <input type="text" name="CostOfService" id="CostOfService" class="form-control" autocomplete="nope" placeholder="" value="" >
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Duration: </label> 
                    <div class="input-group mb-3">
                      <input type="text" id="duration" readonly="true" class="form-control">
                      <div class="input-group-prepend">
                        <span class="input-group-text minhour"></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Appointment Status  * </label>
                <select name="eventstatus" id="eventstatus" class="form-control" >
                  <option value=""> Select Appointment current Status </option>
                  <option value="pending"> Pending </option>
                  <option value="confirmed"> Confirmed </option>
                  <option value="canceled"> Canceled </option>
                  <option value="no-show"> No-Show </option>
                  <option value="in-progress"> In Progress </option>
                  <option value="completed"> Completed </option>
                </select>
              </div>
            </div>
            <div class="col-md-4 col-sm-12">
              <div class="form-group">
                <label>Appointment Note  * </label>
                  <textarea class="textarea_editor form-control" rows="4" placeholder="Enter note here ..." id="EmailInstruction" autocomplete="nope" name="EmailInstruction"></textarea>
              </div>
              <div class="Loader"></div>
              
              <hr>
              <span id="editspan" style="display: none"></span>
              <div id="repeatdiv">
                <label>Repeat :</label>
                <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist" >
                  <li class="nav-item">
                    <a class="repeat nav-link active show" id="offrepeat" data-toggle="pill" href="#pills-off" role="tab" aria-controls="pills-home" aria-selected="true">Off</a>
                  </li>
                  <li class="nav-item">
                    <a class="repeat nav-link" id="daily" data-toggle="pill" href="#pills-daily" role="tab" aria-controls="pills-profile" aria-selected="false">Daily</a>
                  </li>
                  <li class="nav-item">
                    <a class="repeat nav-link" id="weekly" data-toggle="pill" href="#pills-weekly" role="tab" aria-controls="pills-contact" aria-selected="false">Weekly</a>
                  </li>
                  <li class="nav-item">
                    <a class="repeat nav-link" id="monthly" data-toggle="pill" href="#pills-monthly" role="tab" aria-controls="pills-contact" aria-selected="false">Monthly</a>
                  </li>
                  <li class="nav-item">
                    <a class="repeat nav-link" id="yearly" data-toggle="pill" href="#pills-yearly" role="tab" aria-controls="pills-contact" aria-selected="false">Yearly</a>
                  </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                  <div class="tab-pane fade active show" id="pills-off" role="tabpanel" aria-labelledby="pills-home-tab"></div>
                  <div class="tab-pane fade" id="pills-daily" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="dendate">End Date *</label>
                          <input type="text" class="datepicker form-control" placeholder="End Date" name="dendate" autocomplete="nope"  id="dendate" />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="pills-weekly" role="tabpanel" aria-labelledby="pills-contact-tab">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-gorup">
                          <label for="wevery">Every: *</label>
                            <input type="number" class="form-control" id="every" value="1" min="1" max="100">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="dendate">End Date: *</label>
                          <input type="text" class="datepicker form-control" placeholder="End Date" name="wendate" autocomplete="nope"  id="wendate" />
                        </div>
                      </div>
                    </div>
                      <div class="form-group">
                        <label for="">Days: *</label>
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="border: 1px solid #dee0e4;border-radius: 5px;">
                          <li class="nav-item text-center" style="width:14.28%">
                            <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-top-right-radius:0px;border-bottom-right-radius: 0px;display: block;">Sun</a>
                          </li>
                          <li class="nav-item text-center" style="width:14.28%">
                            <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-radius: 0px;display: block;">Mon</a>
                          </li>
                          <li class="nav-item text-center" style="width:14.28%">
                            <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-radius: 0px;display: block;">Tue</a>
                          </li>
                          <li class="nav-item text-center" style="width:14.28%">
                            <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-radius: 0px;display: block;">Wed</a>
                          </li>
                          <li class="nav-item text-center" style="width:14.28%">
                            <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-radius: 0px;display: block;">Thu</a>
                          </li>
                          <li class="nav-item text-center" style="width:14.28%">
                            <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-radius: 0px;display: block;">Fri</a>
                          </li>
                          <li class="nav-item text-center" style="width:14.28%">
                            <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-top-left-radius: 0px;border-bottom-left-radius: 0px;display: block;">Sat</a>
                          </li>
                        </ul>
                      </div>
                  </div>
                  <div class="tab-pane fade" id="pills-monthly" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-gorup">
                          <label for="mday">Day: *</label> 
                            <input type="number" class="form-control" name="mday" id="mday" value="1" min="1" max="31">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="mendate">End Date: *</label>
                          <input type="text" class="datepicker form-control" placeholder="End Date" name="mendate" autocomplete="nope"  id="mendate" />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="pills-yearly" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-gorup">
                          <label for="ymonth">Month:*</label>
                            <select name="ymonth" class="form-control" id="ymonth" style="width:100%;padding-left:2px" tabindex="0">
                              <option value="1">Jan</option>
                              <option value="2">Feb</option>
                              <option value="3">Mar</option>
                              <option value="4">Apr</option>
                              <option value="5">May</option>
                              <option value="6">Jun</option>
                              <option value="7">Jul</option>
                              <option value="8">Aug</option>
                              <option value="9">Sep</option>
                              <option value="10">Oct</option>
                              <option value="11">Nov</option>
                              <option value="12">Dec</option>
                            </select>
                        </div>
                      </div>
                       <div class="col-md-3">
                        <div class="form-gorup">
                          <label for="ydate">Day : *</label>
                            <input type="number" class="form-control" value="1" min="1" max="31" maxlength="2" name="ydate" id="ydate">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="yendate">End Date: *</label>
                          <input type="text" class="datepicker form-control" placeholder="End Date" name="yendate" autocomplete="nope"  id="yendate" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-sm-12">
              <div class="form-group">
                <label for="customer">Customer : </label> <button style="background-color: transparent;border: none;cursor: pointer;" class="pull-right" id="editcustomer"><b>Edit</b></button>
                <select class="select2 m-b-10 select2-multiple" style="width: 100%"  data-placeholder="Choose Service" id="newlistofclient">
                </select>
              </div>
              <div class="form-group">
                <label for="emaillabel" id="newemail" style="text-decoration: underline;"></label>
              </div>
              <div class="row" id="clinetdetails">
                <div class="col-md-4 col-lg-4 col-sm-12 col-xs-4">
                  <img src="" alt="No Image" id="clientimage" height="100px" width="100px"> 
                </div>
  
                <div class="col-md-8 col-lg-8 col-sm-12 col-xs-8">
                  <br>
                  <label id="newname"></label><br>
                  <label id="newphone"></label>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12">
              <div class="alert alert-success" id="resonseAddApp" style="display: none;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonseAddAppemsg"></p>
              </div>
              <div class="alert alert-danger" id="csrf_error2" style="display: none;">
                <button type="button" data-dismiss="alert" class="close"> <span aria-hidden="true">&times;</span> </button>
                <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="csrf_errormsg2"></p>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <div class="form-group pull-right" style="margin-bottom: 0px;">
            <button type="submit" class="btn waves-effect waves-light btn-info m-r-10"><i class="fa fa-check"></i> Book</button>
            <button type="button" class="btn waves-effect waves-light btn-danger" id="cancelappp" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>  
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>