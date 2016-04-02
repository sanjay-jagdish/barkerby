                                <?php include_once('redirect.php'); ?>
<div class="page add-notification-page">
<div class="page-header">
   	<div class="page-header-left">
       	<span>&nbsp;</span>
           <h2>
           	<?php 
				echo 'Skapa ny';
			?>
           </h2>
       </div>
     	<!-- end .page-header-left -->
   </div>
   <!-- end .page-header -->
   <div class="clear"></div>
   
   <div class="page-content">
   	<div class="page-content-left" style="width:100%">
           <table width="100%" border="0" cellpadding="4">
               <tr>
               		<td align="right"><font style="color:#e67e22;">*</font>Ämne:</td>
               		<td><input type="text" class="notification-subject" style="width:100%" /></td>
               </tr>
               <tr>
                   <td width="3%" nowrap="nowrap" align="right" valign="top"><font style="color:#e67e22;">*</font>Text:</td>
                 <td width="93%" valign="top"><textarea name="textarea" class="notification-content" style="width:100% !important; height:120px;"></textarea></td>
                   <td width="4%">&nbsp;</td>
               </tr>
               <tr>
               		<td align="right">&nbsp;</td>
               		<td>
                    	<input type="radio" name="status" value="0" id="status_0" checked="checked" /><label for="status_0">Spara som utkast</label>  &nbsp; 
                        <input type="radio" name="status" value="2" id="status_2" /><label for="status_2">Skicka nu</label>  &nbsp;
                        <input type="radio" name="status" value="1" id="status_1" /><label for="status_1">
                        	Skickas<input type="text" name="send_on" class="date_time push-time" /></label> 
                    </td>
               </tr>
               <tr>
                   <td colspan="3" align="right"><input type="button" class="btn add-notification-btn" value="Utför" style="width:100%"></td>
               </tr>
           </table>
       </div>
       <div class="page-content-right">
       	<div id='preview'></div>
       </div>
           
       <div class="clear"></div>
       <div class="displaymsg"></div>
   </div>
   
</div>

<script type="text/javascript">
$('.date_time').datetimepicker();
$('.push_time').focusin(function(){
   $('#status_1').prop('checked',true);
});


/* added by VicEEr - For Notification Functionality */

jQuery('.add-notification-btn').click(function(e){

	/* Prevent default actions */
	e.preventDefault();
	e.stopPropagation();
	
	addNotification();
});


function addNotification() {    
   	
    var subject = jQuery('.notification-subject').val();

    tinyMCE.triggerSave();
    var content = jQuery('.notification-content').val();	
    
    var status = jQuery("input:radio[name=status]:checked").val();
    var pushtime = jQuery('.push-time').val();
    
    jQuery('.displaymsg').fadeOut('slow');
    	
    if(subject != '' && content != '') {
		
		if(status==1 && pushtime ==''){
        	jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Please set the date and time when to send the push.');
			return false;
		}        
				
		jQuery.ajax( {
            url: "actions/add-notification.php",
            type: 'POST',
            data: 'subject='+encodeURIComponent(subject)+'&content='+encodeURIComponent(content)+'&status='+encodeURIComponent(status)+'&push_time='+encodeURIComponent(pushtime),
            success: function(value) {
               
				jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Notification successfully added.');
				
				setTimeout("window.location='?page=notifications'", 2000);
              
            }
        
		});        
    } else {
        jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fyll i samtliga fält.');
    }    
}

/* end of added lines for the Notification Functionality */

</script>
<!-- end .page -->
                            