<div id="divToPrint2" style="display:none;">
  <div style="position: relative;height: 90%">
   <div style="width: 100%;padding: 10px;">
    <div style="width: 40%; float: left">
      <div>
        <span><img src="<?php echo base_url;?>/assets/images/mylog.png" style="width: 90px;" alt="homepage" class="event_user_img"></span>
      </div>
      <div><span style="font-size: 20px;text-transform: capitalize;" id="event_Currentuser"></span></div>
      <div id="to">
        <span>
          <h5 style=" background: #4095c7;color: white;padding: 5px">
            To:
          </h5>
        </span>
      </div>
      <div>
        <span id="printserviewcutomer"></span>
      </div>
      <div>
        <span id="printeventEmail"></span>
      </div>
      <div>
        <span id="printeventphone"></span>
      </div>
    </div>
  </div>
  <div class="clerfix" style="clear: both;">
  </div>
  <div>
    <table border="0" style="width: 100%;margin:30px 0;border-style: none;" cellspacing="0">
      <tr>
        <td style="padding: 5px;text-align: center;">Appointment ID</td>
        <td id="printappid" style="padding: 5px;text-align: center;"></td>
      </tr>
      <tr style="background: #e2dfdf;">
        <td style="padding: 5px;text-align: center;">Date</td>
        <td id="printeventdate" style="padding: 5px;text-align: center;"></td>
      </tr>
      <tr>
        <td style="padding: 5px;text-align: center;">Status</td>
        <td id="printeventstauts" style="padding: 5px;text-align: center;"></td>
      </tr>
      <tr style="background: #e2dfdf;">
        <td style="padding: 5px;text-align: center;">Service</td>
        <td id="printserviewname" style="padding: 5px;text-align: center;"></td>
      </tr>
      <tr>
        <td style="padding: 5px;text-align: center;">Service Provider</td>
        <td class="printserviewprovider" style="padding: 5px;text-align: center;"></td>
      </tr>
      <tr style="background: #e2dfdf;">
        <td style="padding: 5px;text-align: center;">Price</td>
        <td id="printservicecost" style="padding: 5px;text-align: center;"></td>
      </tr>
      <tr>
        <td style="padding: 5px;text-align: center;">Location</td>
        <td id="printAddress" style="padding: 5px;text-align: center;"></td>
      </tr>
      <tr style="background: #e2dfdf;">
        <td style="padding: 5px;text-align: center;">Comment</td>
        <td id="printservicecomment" style="padding: 5px;text-align: center;"></td>
      </tr>
    </table>
  </div>
  <div class="clerfix" style="clear: both;">
  </div>

  <div class="Signature" style="float: right;width: 30%"><span>Signature </span><img class="event_compsign" width="50%" src="" title="Sign of a company" alt="Sign of a company"> </div>

  <div class="clerfix" style="clear: both;">
  </div>
</div>
<div style="width: 100%;">
  <hr>
  <div class="event_footer" style="text-align: center;">
  </div>
</div>
</div>

<script>
  
  $('#printappoiment').on('click',function(){


    var eveId = $(this).attr("data-id");



    $.ajax({
      dataType:"json",
      type:"post",
      "url" : '<?php echo base_url; ?>/Exec/Exec_Edit_Event?printdata='+eveId,
      success: function(data)
      {
        if(data)
        {

          $('#printappid').text(data[0].eid);
          $('#printserviewcutomer').text(data[0].customerName);
          $('#printeventphone').text(data[0].Phone);
          $('#printeventEmail').text(data[0].Email);
          $('#printeventdate').text(data[0].EventDate);
          $('#printeventstauts').text(data[0].eventstatus);
          $('#printserviewname').text(data[0].ServiceName);
          $("#event_Currentuser").text(data[0].username);
          $("#printAddress").text(data[0].address);

          $('.printserviewprovider').text(data[0].serviceProviderName);
          $('#printservicecost').text("$"+data[0].Price);

          if(data[0].mysign){
            $(".event_compsign").attr('src',"<?= base_url?>/assets/sing/"+data[0].mysign);
          }

          if(data[0].compimg){
            $(".event_user_img").attr("src","<?= base_url?>/assets/companyimage/"+data[0].compimg);
          }else{
            $(".event_user_img").attr("src","<?= base_url?>/assets/images/mylog.png");
          }

          if(data[0].order_note){
            var Temp = JSON.parse(data[0].order_note);
            if(Temp.footer){
              $(".event_footer").html(Temp.footer);
            }else{
              $(".event_footer").html('Thank You - MySunless');
            }
          }else{
            $(".event_footer").html('Thank You - MySunless');
          }

          var divToPrint = document.getElementById('divToPrint2');
          var popupWin = window.open('', '_blank', 'width=950,height=600');
          popupWin.document.open();
          popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
          popupWin.document.close();

        }

      }
    });


  });

</script>