<?php include_once('redirect.php'); ?>
<style>
.legend_circle{
	border:#000 solid thin; 
	width:15px; 
	border-radius:10px;
	display:inline-table;
}
.hide{ display:none; }
</style>
<div class="page add-shift-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                	/*if(isset($_GET['page'])){
						if(isset($_GET['subpage'])){
							echo ucwords(removeDashTitle($_GET['subpage']));
						}
						else{
							echo ucwords(removeDashTitle($_GET['page']));
						}
					}*/
					echo 'Skapa ny';
				?>
            </h2>
        </div>
      	<!-- end .page-header-left -->
    </div>
    <!-- end .page-header -->
    <div class="clear"></div>
    
    <div class="page-content">
    	<div class="page-content-left">
         <strong>Förklaring: </strong><br />
  		 <div style="white-space:nowrap; margin-left:16px;">
             <div style="padding:2px;">
             	<div style="background-color:#CCC;" class="legend_circle">&nbsp;</div> Ej möjligt <em>(gäller tidigare datum, dagens datum eller ej schemalagd dag)</em>
             </div>
             <div style="padding:2px;">
             	<div style="background-color:#FF9;" class="legend_circle">&nbsp;</div> Lediga dagart <em></em>
             </div>
             <div style="padding:2px;">
             	<div style="background-color:#F96;" class="legend_circle">&nbsp;</div> Schemalagda skift<em> (byte måste godkännas) </em>
             </div>
             <div style="padding:2px;">
	             <div style="background-color:#F00;" class="legend_circle">&nbsp;</div>&nbsp;Vald skift för byte
                 
         	 </div>
         </div>
         <br />
         <form id="sched_switch">   
            <table cellpadding="2">
                
                <tr>
                    <td colspan="2"><div align="left"><font style="color:#e67e22;">*</font><strong>Schema:</strong>&nbsp; 
                        <select name="schedule_1" id="schedule_1">
                       	  <option value="0"> - Välj schema - </option>
						<?php
						if($_SESSION['login']['type']<=2){ //admin and manager
							  $s = "SELECT s.id, concat(a.lname,' ',a.fname), s.start_time, s.end_time, s.days, s.valid_from, s.valid_until, s.account_id 
									FROM schedule as s, account as a 
									WHERE a.id=s.account_id AND s.deleted=0 AND '".date('Y-m-d')."' BETWEEN STR_TO_DATE(s.valid_from, '%m/%d/%Y') AND STR_TO_DATE(s.valid_until, '%m/%d/%Y')";
						}else{
							  $s = "SELECT s.id, concat(a.lname,' ',a.fname), s.start_time, s.end_time, s.days, s.valid_from, s.valid_until, s.account_id 
									FROM schedule as s, account as a 
									WHERE a.id=s.account_id AND s.deleted=0 AND '".date('Y-m-d')."' BETWEEN STR_TO_DATE(s.valid_from, '%m/%d/%Y') AND STR_TO_DATE(s.valid_until, '%m/%d/%Y') 
									AND s.account_id=".$_SESSION['login']['id'];
						}
						
						$q=mysql_query($s);
					
					while($r=mysql_fetch_array($q)){
					?>
                        <option value="<?php echo $r[0];?>">
                          <?php echo $r[1];?> &raquo;
                          <?php echo date("m/d/Y",strtotime($r[5])); ?> till 
                          <?php echo date("m/d/Y",strtotime($r[6])); ?> @ 
                          <?php echo $r[2];?> to <?php echo $r[3];?> 
                          [<?php echo $r[4];?>]
                        </option>                    
                        
                        <?php
					}//looping thru schedules
					?>
                      </select>
                      <br />
                      <div id="original_days"></div>
                      <br />
                    </div></td>
                </tr>
                <tr class="hide" id="desired_request">
                    <td colspan="2"><div align="left">
                    <font style="color:#e67e22;">*</font>
                    <label for="swap">
                      <input type="radio" name="option" id="swap" value="swap" checked="checked" />
                   	  <strong>Vill byta med::&nbsp;</strong>  &nbsp;&nbsp;&nbsp;OR&nbsp;&nbsp;&nbsp; 
					</label>
					<label for="drop">	
                      <input type="radio" name="option" id="drop" value="drop" />
   	                  <strong>Droppa skift och erbjud andra den</strong>.
					</label>
                    <div id="desired_sched"></div>
                      <br />
                      <div id="new_days"></div>
                      <div id="other_employees" class="hide">*** Show Multi-Select of Employees *** </div>
                      <br />
                    </div></td>
                </tr>
              <tr class="hide" id="desired_remarks">
                    <td valign="top"><div align="left"><font style="color:#e67e22;">*</font><strong>Anledning</strong>:&nbsp;</div></td>
                    <td><textarea name="textarea" class="shift-desc txt"></textarea></td>
              </tr>
                <tr class="hide" id="desired_submit">
                    <td colspan="2" align="right"><input type="button" class="btn add-shift-btn" value="Utför"></td>
                </tr>
            </table>
          </form>
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

	$('#schedule_1').on('change', function(e) {

		e.preventDefault();
		e.stopPropagation();
		
		var schedid = ( this.value ); // or $(this).val()
		
		jQuery.ajax({
			 url: "pages/shift_days_current.php",
			 type: 'POST',
			 data: 'sched_id='+schedid,
			 success: function(value){
				jQuery('#original_days').fadeIn('slow').html(value);
				jQuery('#desired_sched').fadeOut('slow').html('');
				jQuery('#new_days').fadeOut('slow').html('');
			 }
		});	
	
	});  
	
	$("input[name='option']").change(function(){
		// Do something interesting here
		var option = this.value;
		
		if(option=='drop'){
			jQuery('#desired_sched').fadeOut('slow');
			jQuery('#new_days').fadeOut('slow');			
			jQuery('#other_employees').fadeIn('slow');			
			jQuery('#desired_remarks').fadeIn('slow');			
			jQuery('#desired_submit').fadeIn('slow');			
		}else{
			jQuery('#desired_sched').fadeIn('slow');
			jQuery('#new_days').fadeIn('slow');
			jQuery('#other_employees').fadeOut('slow');			
		}
		//alert(option);
	});	  
	
});

</script>

<div id="popup"></div>