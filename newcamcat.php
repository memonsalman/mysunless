<div class="modal fade" id="myModal_addcamcat" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Campaigns Category</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>

        <div class="modal-body">
            
                   <form class="form-horizontal" autocomplete="off" id="NewCamCategory" method="post">
                                        <input type="hidden" name="id" id="id" value="">
                                        <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
                                                    <div class="form-group">
                                         <label><span class="help">Campaigns Category Name  *</span></label>
                 <input type="text" name="CampaignsCategory" id="CampaignsCategory" value="" class="form-control" maxlength="30">
                                                    </div>
                                                    <div class="modal">
                                                    </div>
                                                    <div class="form-group">
                                                        
                                <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add-client"> <i class="fa fa-check">
                                                            </i> Submit Category</button>
                                                        
                <a  data-dismiss="modal" class="btn waves-effect waves-light btn-danger"><i class="fa fa-times">
                                                            </i> Cancel Category</a>
                                                    </div>
                                                </form>
                                                <div class="col-lg-12 col-md-12" style="padding: 25px 0;">
                                                    <div class="alert alert-success" id="resonse" style="display: none;">
                   <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                        <h3 class="text-success">
                                                            <i class="fa fa-check-circle">
                                                            </i>
                                                            Success
                                                        </h3>
                                                        <p id="resonsemsg">
                                                        </p>
                                                    </div>
                                                    <div class="alert alert-danger" id="error" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                        <h3 class="text-danger">
                                                            <i class="fa fa-exclamation-circle">
                                                            </i>
                                                            Errors
                                                        </h3>
                                                        <p id="errormsg">
                                                        </p>
                                                    </div>
                                                </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>