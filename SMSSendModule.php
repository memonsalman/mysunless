<div id="sendSmsModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Send SMS
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="Loader"></div>
                <form class="form-horizontal" id="NewSms" autocomplete="off" method="post">
                    <!-- <input type="hidden" name="UserID" id="UserID" value="<?php echo $Createdfk; ?>"> -->
                    <!-- <input type="hidden" name="cid[]" value=""> -->
                    <!-- <input type="hidden" name="FirstName" value="<?php echo $FirstName.' '.$LastName; ?>"> -->
                    <div class="form-group">
                        <label>To *</label>
                        <select class="select2 m-b-10 select2-multiple form-control" data-placeholder="Contact Email" id="to" name="to[]" multiple data-style="form-control tn-secondary">
                          <?php 
                          $UserID = $_SESSION['UserID'];
                          $total_clients = $db->prepare("select * from clients where createdfk in (Select DISTINCT(u2.id) from users u1 join users u2 on u1.id=u2.id or u1.adminid=u2.id or u1.id=u2.adminid where u1.id IN (:id) ) order by clients.FirstName");
                          $total_clients->bindParam(':id', $UserID);
                          $total_clients->execute();
                          $all_client=$total_clients->fetchAll(PDO::FETCH_ASSOC);
                          foreach($all_client as $row)
                          {
                            ?>
                            <option value="<?php echo $row['Phone'].','.$row['id'] ?>"  ><?php echo $row['FirstName']." ".$row['LastName']." - ".$row['Phone'] ?></option>
                            <?php
                        }
                        ?>
                    </select> 
                    <!-- <input type="text" name="to" id="smsTo" class="form-control" placeholder="To" value="<?php echo $Phone ;?>" readonly> -->
                </div>
                <div class="form-group">
                    <label>Message *</label>
                    <textarea class="form-control" rows="10" placeholder="Enter Message ..." id="message" name="message"></textarea>
                </div>
                <div class="Loader">
                </div>
                <div class="form-group">
                    <button type="submit" name="smsSend" class="btn waves-effect waves-light btn-info m-r-10" id="smsSend"><i class="fa fa-check">
                    </i> Send</button>
                    <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal"><i class="fa fa-times">
                    </i> Cancel</button>
                </div>
            </form>
        </div>
        <div class="modal-footer">
        </div>
        <div class="col-lg-12 col-md-12">
            <div class="alert alert-success" id="resonse_sms" style="display: none;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                <h3 class="text-success">
                    <i class="fa fa-check-circle">
                    </i>
                    Success
                </h3>
                <p id="resonsemsg_sms">
                </p>
            </div>
            <div class="alert alert-danger" id="error_sms" style="display: none;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                <h3 class="text-danger">
                    <i class="fa fa-exclamation-circle">
                    </i>
                    Errors
                </h3>
                <p id="errormsg_sms">
                </p>
            </div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#NewSms").validate({
            ignore: ":hidden:not(textarea)",
            rules: {
                to: {
                    required: true,}
                    ,
                    message: {
                        required: true,}
                        ,
                    }
                    ,           
                    messages: {
                        to: {
                            required: "Please select at least one recipient."}
                            ,
                            message: {
                                required: "Please enter sms decription"}
                                ,               
                            }
                            ,
                            errorPlacement: function( label, element ) {
                                if( element.attr( "name" ) === "message" ) {
                                    element.parent().append( label );
                                }
                                else {
                                    label.insertAfter( element );
                                }
                            }
                            ,
                            submitHandler: function() {
                                $(".Loader").show();
                                var data = $("#NewSms").serialize();
                                data= data ;
                                jQuery.ajax({
                                    dataType:"json",
                                    type:"post",
                                    data:data,
                                    url:'<?php echo EXEC; ?>Exec_Send_SMS',
                                    success: function(data)
                                    {
                                        $(".Loader").hide();
                                        if(data.response)
                                        {
                                            swal('',data.response,'success');
                                            $( '#NewSms' ).each(function(){
                                                this.reset();
                                            });
                                            $('#sendSmsModal').modal('toggle');
                                            
                                            $(".communicationTableRefresh").trigger("click");
                                        }
                                        else if(data.error){
                                            swal('',data.error,'error');
                                        }
                                        else if(data.resonse_phone)
                                        {
                                            swal('',data.resonse_phone,'warning')
                                        }
                                        else if(data.TwilioSetup)
                                        {
                                            swal({
                                                icon: "success",
                                                text:data.TwilioSetup,
                                                buttons: {set_sms:"Set Now", ok:"Later"},
                                            }).then((value) => {
                                                switch (value) {

                                                    case "set_sms":
                                                    window.open('https://mysunless.com/crm/SmsSendSetting', '_blank');
                                                    break;

                                                    case "ok":
                                                    break;
                                                }
                                            });
                                            
                                        }
                                    }
                                }
                                );
                            }
                        });

});

</script>
