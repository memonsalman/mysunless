<div class="modal fade" id="myModal_addprobrand" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Product Brand</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="Loader"></div>
            
                      <form class="form-horizontal" autocomplete="off" id="NewBrand" method="post">
                                                    <input type="hidden" name="id" id="id" value="">
                                                    <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
                                                    <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                                                    <div class="form-group">
                                                        <label><span class="help">Brand Name  *</span></label>
                         <input type="text" name="Brand" id="Brand" value="" class="form-control" maxlength="30">
                                                    </div> 
                                                    <div class="Loader"></div>
                                                      <div class="form-group">
                                                            
 <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add-client"> <i class="fa fa-check"></i> Submit Brand</button>
                                                              
 <a data-dismiss="modal" class="btn waves-effect waves-light btn-danger"><i class="fa fa-times"></i> Cancel Brand</a>
                                                    </div>
                                            </form>
                     


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>