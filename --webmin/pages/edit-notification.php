                                <?php
 include_once('redirect.php'); 
$notification_sql = "SELECT * FROM notification WHERE id=".$_GET['id'];
$notification_qry = mysql_query($notification_sql);
$notification_res = mysql_fetch_assoc($notification_qry);
?>
<div class="page add-notification-page">
<div class="page-header">
   	<div class="page-header-left">
       	<span>&nbsp;</span>
           <h2>
           	<?php
               	if(isset($_GET['page'])){
					if(isset($_GET['subpage'])){
						echo ucwords(removeDashTitle($_GET['subpage']));
					}
					else{
						echo ucwords(removeDashTitle($_GET['page']));
					}
				}
			?>
           </h2>
       </div>
     	<!-- end .page-header-left -->
   </div>
   <!-- end .page-header -->
   <div class="clear"></div>
   
   <div class="page-content">
   	<div class="page-content-left" style="width:100%">
           <h2><?php echo trim(stripslashes($notification_res['subject'])); ?></h2>
           
           <?php
           if($notification_res['status']>1){
           ?>
           <br>
           <div style="color:red;">
           You can no longer make any changes to this particular notification since it has already been pushed last <b><?php echo date('l d F Y H:i',strtotime($notification_res['delivered'])).'</b>.'; ?>
           </div>           
           <?php
           }else{
           ?>        
           
           <table width="100%" border="0" cellpadding="4">
               <tr>
               		<td align="right"><font style="color:#e67e22;">*</font>Ämne:</td>
               		<td><input type="text" class="notification-subject" style="width:100%" value="<?php echo stripslashes($notification_res['subject']); ?>" /></td>
               </tr>
               <tr>
                   <td width="3%" nowrap="nowrap" align="right" valign="top"><font style="color:#e67e22;">*</font>Text:</td>
                 <td width="93%" valign="top"><textarea name="textarea" class="notification-content" style="width:100% !important; height:120px;"><?php echo stripslashes($notification_res['content']); ?></textarea></td>
                   <td width="4%">&nbsp;</td>
               </tr>
               <tr>
               		<td align="right">&nbsp;</td>
               		<td>
                    	<input type="radio" name="status" value="0" id="status_0" <?php if($notification_res['status']==0){ ?>checked="checked" <?php } ?> /><label for="status_0">Spara som utkast</label>  &nbsp; 
                        <input type="radio" name="status" value="2" id="status_2" <?php if($notification_res['status']==2){ ?>checked="checked" <?php } ?> /><label for="status_2">Skicka nu</label>  &nbsp;
                        <input type="radio" name="status" value="1" id="status_1" <?php if($notification_res['status']==1){ ?>checked="checked" <?php } ?> /><label for="status_1">
                        <?php 
						if(trim($notification_res['date'])!='' && $notification_res['date']!='0000-00-00'){
							$push_time = date("m/d/Y",strtotime($notification_res['date'])).' '.$notification_res['time'];
						}
						?>	
                            Skickas <input type="text" name="send_on" class="date_time push-time" value="<?php echo $push_time; ?>" /></label> 
                    </td>
               </tr>
               <tr>
                   <td colspan="3" align="right">
                    <input type="hidden" class="edit_id" value="<?php echo $_GET['id']; ?>" />
                   	<input type="button" class="btn save-notification-btn" value="Utför" style="width:100%">
                   </td>
               </tr>
           </table>
           
           <?php
           }//only show form if editing is still allowed
           ?>
           
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

jQuery('.save-notification-btn').click(function(e){

	/* Prevent default actions */
	e.preventDefault();
	e.stopPropagation();
	
	saveNotification();
});


function saveNotification() {    

    tinyMCE.triggerSave();
   	
	var eid = jQuery('.edit_id').val();
	var subject = jQuery('.notification-subject').val();
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
            url: "actions/edit-notification.php",
            type: 'POST',
            data: 'id='+eid+'&subject='+encodeURIComponent(subject)+'&content='+encodeURIComponent(content)+'&status='+encodeURIComponent(status)+'&push_time='+encodeURIComponent(pushtime),
            success: function(value) {
               
				jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Notification successfully modified.');
				
				setTimeout("window.location='?page=notifications'", 2000);
              
            }
        
		});        
    } else {
        jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fields with asterisk are required.');
    }    
}


/* end of added lines for the Notification Functionality */

</script>
<!-- end .page -->
                            