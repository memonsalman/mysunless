<div class="modal fade" id="myModal_servicehead" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Service</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body">

                                <form class="form-horizontal " autocomplete="off" id="NewServie" method="post">
                                        <input type="hidden" name="id" id="id" value="">
                                        <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
                                        <div class="form-group">
                                            <label><span class="help">Service Name  *</span></label>
<input type="text" name="ServiceName" id="ServiceName" class="form-control" value="" placeholder="Service Name" maxlength="30">
                                        </div> 
                                        <div class="form-group">
                                            <label><span class="help">Service Price  *</span></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
<input type="text" name="Price" id="Price" class="form-control" value="" placeholder="Service Price">
                                                </div>
                                        </div> 
                                        <div class="form-group">
                                            <label><span class="help">Duration  *</span></label>
                                            <select name="Duration" id="Duration" class="form-control valid">
                                                <option value="">Select Service Duration</option>
                                                <option value="30 Min">30 Min</option>
                                                <option value="1 h">1 h</option>
                                                <option value="2 h">2 h</option>
                                                <option value="3 h">3 h</option>
                                                <option value="4 h">4 h</option>
                                                <option value="5 h">5 h</option>
                                                <option value="6 h">6 h</option>
                                                <option value="7 h">7 h</option>
                                                <option value="8 h">8 h</option>
                                                <option value="9 h">9 h</option>
                                                <option value="10 h">10 h</option>
                                                <option value="10 h">11 h</option>
                                                <option value="10 h">12 h</option>
                                            </select>
                                        </div> 
                                        <div id="startime" style="display: none;">
                                        <div class="form-group">
                                            <label><span class="help">Star and End Time  *</span></label>
                                            <div>
<div class="timeinput"><input class="form-control" id="starttime" placeholder="Start time" name="starttime" value=""></div>
<div class="timeinput"><input class="form-control" id="endtime" placeholder="End time" name="endtime" value=""></div>
                                        </div>
                                        </div> 
                                        </div>
                                        <div class="form-group">
                                            <label><span class="help">Category  *</span></label>
                                            <select name="Category" id="Category" class="form-control valid">
                                            <option value="">Select Category</option>
                                                <?php
                                                foreach($allCategoryx as $row)
                                                {
                                                    ?>
                                                      <option  value="<?php echo $row['Category']; ?>"><?php echo $row['Category']; ?></option>  
                                                    <?php
                                                
                                                }
                                                ?>
                                            </select>
                                        </div> 


                                              <div class="form-group">
                                            <label><span class="help">User  *</span></label>
                                <select class="select2 m-b-10 select2-multiple form-control" data-placeholder="Select Users" id="Users" name="Users[]" multiple data-style="form-control btn-secondary">
                                            <option value="">Select Category</option>
                                                <?php
                                                foreach($alluserx as $row)
                                                {
                                                    ?>
                                                      <option  value="<?php echo $row['id']; ?>"><?php echo $row['username']; ?></option>  
                                                    <?php
                                                
                                                }
                                                ?>
                                            </select>
                                        </div>  
                                        
                                            <div class="form-group">
                                            </div>
                                        <div class="form-group">
                                            <label><span class="help">Type  *</span></label>
                                            <select name="Type" id="Type" class="form-control valid">
                                                <option value="">Select Type</option>
                                                <option value="Normal">Normal</option>
                                                <option value="Silver">Silver</option>
                                                <option value="Gold">Gold</option>
                                                <option value="Dimoand">Dimoand</option>
                                            </select>
                                        </div>
                                        <div id="startime">
                                        <div class="form-group">
                                            <label><span class="help">Customer Limit  *</span></label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="timeinput"><input class="form-control" type="number" min="1" name="cusmerlimt" placeholder="Enter Customer Limit" id="cusmerlimt"></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="timeinput">
                                                        <select name="asper" id="asper" class="form-control valid">
                                                            <option value="">OFF</option>
                                                            <option value="per day">per day</option>
                                                            <option value="per week">per week</option>
                                                            <option value="per month">per month</option>
                                                            <option value="per year">per year</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                        </div><br><br>
                                        <div class="form-group">
                                            <label for="example-email">Info  *</label>
<textarea id="Info" class="textarea_editor form-control" rows="5" name="Info" placeholder="Enter your Info..." class="form-control"></textarea>
                                            </div> 
                                        <div class="modal"></div>
                                 <div class="form-group">
                                   
<button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add-client"> <i class="fa fa-check"></i> Submit Service</button>
                                        
 <button type="button" data-dismiss="modal" class="btn waves-effect waves-light btn-danger"><i class="fa fa-times"></i> Cancel Service</a>
                                        </div>
                            </form>

                             </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>