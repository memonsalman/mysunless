 <div class="modal fade" id="myModal_todohead" role="dialog" style="z-index: 1045">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">To-Do</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body">
           <form class="form-horizontal " autocomplete="off" id="newtodo" method="post">
                                        <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                                        <div class="Loader"></div>
                                     <input type="hidden" name="id" id="idtodo" value="">
                                        <div class="form-group">
                                            <label for="todoTitle"><h2>
                                                Title 
                                                </h2>
                                            </label>
                                            <br>
 <input type="text" name= "todoTitle" id="todoTitle" placeholder="Enter Task Title...." class="form-control" value="">
                                        </div>
                                        <div class="form-group">
                                            <label for="todoDesc"><h2>
                                                Description 
                                                </h2></label>
                                            <br>
<textarea class="textarea_editor form-control" rows="10" placeholder="Enter Task Description..." id="todoDesc" name="todoDesc"></textarea>
                                        </div>
                                        <div class="form-group" id= "datetimepicker">
                                            <label for="dueDate"><h2>
                                                Due Date
                                                </h2></label>
                                            <br>
                                            <select name= "dueDate" class="form-control"  id="dueDate">
                                                <option value=""> Select due date </option>
                                                <option value="+1 day"> +1 Days </option>
                                                <option value="+2 day"> +2 Days </option>
                                                <option value="+3 day"> +3 Days </option>
                                                <option value="+4 day"> +4 Days </option>
                                                <option value="+5 day"> +5 Days </option>
                                                <option value="+6 day"> +6 Days </option>
                                                <option value="+7 day"> +7 Days </option>
                                                <option value="+8 day"> +8 Days </option>
                                                <option value="+9 day"> +9 Days </option>
                                                <option value="+10 day"> +10 Days </option>
                                                <option value="+11 day"> +11 Days </option>
                                                <option value="+12 day"> +12 Days </option>
                                                <option value="+13 day"> +13 Days </option>
                                                <option value="+14 day"> +14 Days </option>
                                                <option value="+15 day"> +15 Days </option>
                                                <option value="+16 day"> +16 Days </option>
                                                <option value="+17 day"> +17 Days </option>
                                                <option value="+18 day"> +18 Days </option>
                                                <option value="+19 day"> +19 Days </option>
                                                <option value="+20 day"> +20 Days </option>
                                                <option value="+21 day"> +21 Days </option>
                                                <option value="+22 day"> +22 Days </option>
                                                <option value="+23 day"> +23 Days </option>
                                                <option value="+24 day"> +24 Days </option>
                                                <option value="+25 day"> +25 Days </option>
                                                <option value="+26 day"> +26 Days </option>
                                                <option value="+27 day"> +27 Days </option>
                                                <option value="+28 day"> +28 Days </option>
                                                <option value="+29 day"> +29 Days </option>
                                                <option value="+30 day"> +30 Days </option>
                                                <option value="+31 day"> +31 Days </option>

                                            </select>
 <!-- <input type="text" name= "dueDate" placeholder=" Select Due Date...." class="form-control"  id="dueDate" readonly value="<?php echo $dueDate; ?>"> -->
                                        </div>
                                        <div class="form-group">
                                          
         <button type="submit" name="todoSub" id="todoSub" class="btn btn-info m-r-10"><i class="fa fa-check"></i> Submit Todo</button>
                                           
            <a href="" type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancel Todo</a>
                                        </div>
                                    </form>

                                           <div class="col-lg-12 col-md-12">
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