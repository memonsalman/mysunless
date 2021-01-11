<div class="modal fade" id="myModal_membshipackagehead" role="dialog" style="z-index: 1045">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Membership package</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            
            <form class="form-horizontal " id="MembershipPackage" method="post">
                                <!--  echo $myevent;  -->
                                <input type="hidden" name="id" autocomplete="off" id="idhead" value="">
                                <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
                                <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                            <div class="form-group">
                                    <label for="Name"> Package Name *</label><br>
                                    <div class="Loader"></div>
    <input type="text" name= "Name" id="Name" placeholder="Enter Package Name" class="form-control" value="" maxlength="30">
                            </div>
                            <!-- <div class="form-group">
                                    <label for="Price">Price *</label> <span class="help"></span><br>
                                    <input type="number" name= "Price" id="Price" placeholder="Enter Price" class="form-control Price" value="<?php echo $Price ; ?>">
                            </div> -->
                            <div class="form-group">
                                   <label for="Price">Price * <span class="help"></span></label>
                                   <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
               <input type="text" id="Price" name="Price" class="form-control" id="Price" placeholder="Enter Price" value="" >
                                    </div>
                            </div>
                             <div class="form-group">
                                <label for="Tracking">Package Tracking * </label>
                                <select name="Tracking" id="Tracking" class="form-control valid">
                                    <option value="">Select Package Tracking</option>
                                    <option value="Weekly"> Weekly </option>
                                    <option value="Bi-Weekly"> Bi-Weekly </option>
                                    <option value="Monthly"> Monthly </option>
                                    <option value="Yearly"> Yearly </option>
                                </select>
                            </div> 
                            <div class="form-group">
                                <label for="Description"> Description *</label><br>
<textarea class="textarea_editor form-control" id="MPackageDescription" rows="10" placeholder="Enter Task Description...." name="Description"></textarea>
                            </div>
                            <div class="form-group">
                                
<button type="submit" name="MemberPackageSubmit" id="MemberPackageSubmit" class="btn btn-info m-r-10"><i class="fa fa-check"></i> Submit Package</button>
                                
<a href="" type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancel Package</a> 
                            </div>
                        </form>



                             <div class="col-lg-12 col-md-12">
                    <div class="alert alert-success" id="resonse" style="display: none;">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                        <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonsemsg"></p>
                    </div>
                    <div class="alert alert-danger" id="error" style="display: none;">
           <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                        <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="errormsg"></p>
                    </div>

                    <div class="alert alert-danger" id="csrf_error" style="display: none;">
                    <button type="button" class="close"> <span aria-hidden="true">&times;</span> </button>
                    <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="csrf_errormsg"></p>
                    </div>
                </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>