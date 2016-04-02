<?php
 include_once('redirect.php'); 
$s = "SELECT * FROM announcement WHERE id=".$_GET['id'];
$q = mysql_query($s);
$res = mysql_fetch_assoc($q);
?>
<script>
  jQuery(function() {
    var availableTags = [<?php echo $recipient; ?>];
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    jQuery( "#recipient" )
      // don't navigate away from the field on tab when selecting an item
      .bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).data( "ui-autocomplete" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          // delegate back to autocomplete, but extract the last term
          response( $.ui.autocomplete.filter(
            availableTags, extractLast( request.term ) ) );
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
  });
  </script>

<div class="page add-announcement-page">
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
	
    <style>
	.hide{ display:none; }
	</style>

   	<div class="page-content-left" style="width:100%">
    <form id="edit-annoucement-form">
    <h2>
    <?php echo stripslashes($res['subject']); ?>
    </h2>
    
    <?php
	if($res['status']==1){
			echo '<br><br><font color="red">You are no longer allowed to edit a sent announcement.</font>';
	}else{
	?>
           <table>
           	   <tr>
               		<td valign="top" align="right"><font style="color:#e67e22;">*</font>Mottagare :</td>
                    <td>
                    <input type="hidden" name="edit_id" value="<?php echo $res['id']; ?>" />
                    <label for="all">
	                    <input type="radio" name="send_option" value="all" id="all" <?php if($res['recipient']==''){ ?> checked="checked" <?php } ?> />Skickas till alla
                    </label> 
                    <br />
                    <label for="selective">
                        <input type="radio" name="send_option" value="sel" id="selective" <?php if($res['recipient']!=''){ ?> checked="checked" <?php } ?> />Skickas endast till:<br />
                    </label>
                    <div id="selective_options" <?php if($res['recipient']==''){ ?> class="hide" <?php } ?>> 
                        <?php 
                        /*	<input id="recipient" class="announcement-recipient txt" style="width:350px;"> */ 
                        $rec_recipients = explode(',',$res['recipient']);
						
						$sql = "SELECT id, CONCAT(TRIM(BOTH ' ' FROM fname),' ',TRIM(BOTH ' ' FROM lname)) AS name, email FROM account WHERE type_id<=4 AND deleted=0 
								ORDER BY CONCAT(TRIM(BOTH ' ' FROM fname),' ',TRIM(BOTH ' ' FROM lname)) ASC";
                        $qry = mysql_query($sql) or die(mysql_error());					
                        ?>
                        <select multiple="multiple" name="recipients" id="recipients">
                        <?php
                        while($r = mysql_fetch_assoc($qry)){
                        ?>
                            <option value="<?php echo $r['id'] ?>" <?php if(in_array($r['id'],$rec_recipients)){ ?> selected="selected" <?php } ?> ><?php echo $r['name'].' &lt;'.$r['email'].'&gt; ' ?></option>
                        <?php
                        }
                        ?>
                        </select>                    
                    <br>
                    	<span style="font-size:11px;"><font style="color:red;">
                        	OBS:</font> Håll Ctrl tangenten (Windows) eller Command tangenten (Mac) för att välja flera mottagare. </span>    
                    </div>
                    </td>	
               </tr>	
           	   <tr>
               		<td valign="middle" align="right"><font style="color:#e67e22;">*</font>Ämne :</td>
                    <td>
                     <input type="text" class="announcement-subj" name="subject" style="width:380px;" value="<?php echo stripslashes($res['subject']); ?>" />
                    </td>
			   </tr>
               <tr>
                   <td valign="top" align="right"><font style="color:#e67e22;">*</font>Meddelande :</td>
                   <td><textarea class="announcement-desc txt" style="max-width:350px; width:350px;"><?php echo stripslashes($res['content']); ?></textarea></td>
               </tr>
               <tr>
                   <td colspan="2" align="right">
                   	<label for="draft">
                    	<input type="radio" name="save_option" class="save_option" value="draft" id="draft" <?php if($res['status']==0){ echo 'checked="checked"'; } ?> /> Spara som utkast
                    </label>
                    &nbsp;&nbsp;&nbsp;
					<label for="send_now">
                    	<input type="radio" name="save_option" class="save_option" value="send" id="send_now"  <?php if($res['status']==1){ echo 'checked="checked"'; } ?>/>Skicka nu
                    </label>
                    &nbsp;&nbsp;&nbsp;
					<input type="button" class="btn edit-announcement-btn" value=" CONTINUE "></td>
               </tr>
           </table>
       </form> 
       <?php
		}//if edit is allowed
	   ?>   
       </div>
       <div class="page-content-right">
       	<div id='preview'></div>
       </div>
           
       <div class="clear"></div>
       <div class="displaymsg"></div>
   </div>
   
</div>
<!-- end .page -->

<script>
jQuery(document).ready(function() { 

//	alert('document ready');
/*
$('#recipients').val().length;
$("option:selected").map(function(){ return this.value }).get().join(",");

*/
	$("input[name='send_option']").change(function(){
		// Do something interesting here
		var option = this.value;
				
		if(option=='all'){
			jQuery('#selective_options').fadeOut('slow');
		}else{
			jQuery('#selective_options').fadeIn('slow');
		}
		//alert(option);
	});	  
	
});    	
</script>