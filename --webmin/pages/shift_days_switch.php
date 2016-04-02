<?php
 include_once('redirect.php'); 
session_start();
include '../config/config.php';
$sched_id = $_POST['sched_id'];

if($sched_id>0){
	
	$q=mysql_query("SELECT account_id, start_time, end_time, days, valid_from, valid_until
					FROM schedule
					WHERE id=".$sched_id);
	$r=mysql_fetch_assoc($q);
	
	$start_date = strtotime($r['valid_from']);
	$end_date = strtotime($r['valid_until']);
	
	$days_of_week = array(1=>'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
	
	$sched_days = explode(', ', $r['days']);
	
	foreach($sched_days as $k => $day){
		$valid_days[] = array_search($day, $days_of_week);
	}

	?>
    
    <style>
	.legend_circle_2{
		border:#000 solid thin; 
		width:15px; 
		border-radius:10px;
		display:inline-table;
		line-height:11px;
	}
	</style>
  
	<b>Välj det datum du vill byta till.</b> &nbsp;(endast ett datum)<br />
	
         <strong><br />
         Förklaring: </strong><br />
  		 <div style="white-space:nowrap; margin-left:16px;">
             <div style="padding:2px;">
             	<div style="background-color:#CCC;" class="legend_circle_2">&nbsp;</div> Ej möjligt <em>(gäller tidigare datum, dagens datum eller ej schemalagd dag)</em>
             </div>
             <div style="padding:2px;">
             	<div style="background-color:#6F9;" class="legend_circle_2">&nbsp;</div> Lediga dagart <em></em>
             </div>
             <div style="padding:2px;">
             	<div style="background-color:#F96;" class="legend_circle_2">&nbsp;</div> Schemalagda skift<em> (byte måste godkännas) </em>
             </div>
             <div style="padding:2px;">
	             <div style="background-color:#F00;" class="legend_circle_2">&nbsp;</div>&nbsp;Vald skift för byte
                 
         	 </div>
         </div>
    
    <br />
	<input type="hidden" id="account_id_2" value="<?php echo $r['account_id']; ?>" />
		
	<table cellpadding="4" border="1" cellspacing="0" style="border-collapse:collapse;" bgcolor="#99FF99">
		<tr>
	<?php
		foreach($days_of_week as $d => $day){
			echo '<td align="center" class="cal_header"><i>'.$day.'</i></td>';
		}
	?>
		<tr>
	</tr>	
	<tr>
	<?php
	$cnt=0;
	while($start_date<=$end_date){
		
		$class = 'ok2'; $readonly = '';
		
		//DISABLE if the day is today and earlier
		if($start_date<=strtotime(date('Y-m-d'))){ $class = 'not_ok2';  $readonly = 'disabled="disabled"'; }

		//DISBALE if the day does not fall on the scheduled days
		if(!in_array(date("N",$start_date), $valid_days)){ $class = 'not_ok2';  $readonly = 'disabled="disabled"';}

		//DISABLE  if the specific day has been swapped by a Shift Request (approved request)
		//1st on employee's request made
		$sr_s = "SELECT id FROM shift_request WHERE account_id_1=".$r['account_id']." AND date_1='".date('Y-m-d',$start_date)."' AND approve=1";		
		$sr_q = mysql_query($sr_s);
		$sr_n = mysql_num_rows($sr_q);
		if($sr_n>0){
			$sr_r = mysql_fetch_assoc($sr_q);		
			$class = 'sr_tag';
			$readonly = 'disabled="disabled"';
		}

		//2nd as requested by other employee
		$sr_s = "SELECT id FROM shift_request WHERE account_id_2=".$r['account_id']." AND date_2='".date('Y-m-d',$start_date)."' AND approve=1";			
		$sr_q = mysql_query($sr_s);
		$sr_n = mysql_num_rows($sr_q);
		if($sr_n>0){
			$sr_r = mysql_fetch_assoc($sr_q);		
			$class = 'sr_tag';
			$readonly = 'disabled="disabled"';
		}		
		
		if($cnt==0 && date("N",$start_date)>1){
			$pad = date("N",$start_date) -1;
			while($pad>0){
				echo '<td class="'.$class.'">&nbsp;</td>';
				$pad--;
			}
		}
	
		?>
		<td align="center" class="<?php echo $class; ?>" id="tds<?php echo date('Ymd',$start_date);?>">

			<label for="s<?php echo date('Ymd',$start_date);?>">
            <div style="float:left; display:none;">
                <input type="radio" name="day_switch" class="day_switch" id="s<?php echo date('Ymd',$start_date);?>" value="<?php echo date('Y-m-d',$start_date);?>" <?php echo $readonly; ?> /><br />
			</div>
 			<div>
                <div class="cal_day"><?php echo date("d",$start_date); ?></div>
                <div class="call_month"><?php echo date("F",$start_date); ?></div>
            </div>
	
    		</label>

		</td>       
		<?php
		
		if(date("N",$start_date)==7){ 
			echo '</tr><tr>';
		}
		$start_date = strtotime(date("Y-m-d",$start_date)." +1 day");
		
		$cnt++;
	}//looping thru schedules
	?>
	</table>
	<script>
    
	jQuery(document).ready(function() { 
		
		$('.day_switch').change(function(e) {
			
			e.preventDefault();
			e.stopPropagation();
	
    		var sel_day2_id = $(this).attr('id');			
			var date = ( this.value ); // or $(this).val()
			var account_id = $('#account_id_2').val(); 

			$('.ok2').removeAttr('style');
			$('#td'+sel_day2_id).css({backgroundColor: 'red'});
			$('#td'+sel_day2_id).css({color: 'white'});
			
			//alert(date+' '+account_id);
			/*		
			jQuery.ajax({
				 url: "pages/shift_desired_schedule.php",
				 type: 'POST',
				 data: 'sched_id='+date+'&account_id='+account_id,
				 success: function(value){
					jQuery('#desired_sched').fadeIn('slow').html(value);
				 }
			});	
			*/
		});    
	
	});    	
	</script>
<?php
}
?>    