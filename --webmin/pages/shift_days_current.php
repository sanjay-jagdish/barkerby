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
	<br />
	<b>Välj den dag du önskar byta.</b> &nbsp;(endast ett datum)<br /><br />
	<input type="hidden" id="account_id_1" value="<?php echo $r['account_id']; ?>" />
	
	<style>
	.cal_header{ width:120px; background-color:#555; color:#CCC; }
	.cal_day{ font-weight:bold; }
	.not_ok1{ color:#999; background-color:#CCC; }
	.not_ok2{ color:#999; background-color:#CCC; }
	.sr_tag{ color:#333; background-color:#F96; }
	</style>
	
	<table cellpadding="4" border="1" cellspacing="0" style="border-collapse:collapse;" bgcolor="#FFFF99">
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
		
		$class = 'ok1'; $readonly = '';
		
		//DISABLE if the day is today or earlier
		if($start_date<=strtotime(date('Y-m-d'))){ $class = 'not_ok1'; $readonly = 'disabled="disabled"'; }
		
		//DISABLE if day does not fall on the scheduled days (i.e. Mon, Wed, Thu)
		if(!in_array(date("N",$start_date), $valid_days)){ $class = 'not_ok1'; $readonly = 'disabled="disabled"'; }
		
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
		<td align="center" class="<?php echo $class; ?>" id="td1c<?php echo date('Ymd',$start_date);?>" nowrap="nowrap" style="width:80px;">
			<label for="c<?php echo date('Ymd',$start_date);?>">
            <div style="float:left; display:none;">
            	<input type="radio" name="day_current" class="day_current" value="<?php echo date('Y-m-d',$start_date);?>" id="c<?php echo date('Ymd',$start_date);?>" <?php echo $readonly; ?> /><br />
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
		
		$('.day_current').change(function(e) {
			
			e.preventDefault();
			e.stopPropagation();
			
			var sel_day1_id = $(this).attr('id');
			var sched_id = $('#schedule_1').val(); 
			var date = ( this.value ); // or $(this).val()
			var account_id = $('#account_id_1').val(); 
			
			$('.ok1').removeAttr('style');
			$('#td1'+sel_day1_id).css({backgroundColor: 'red'});
			$('#td1'+sel_day1_id).css({color: 'white'});
			
			//alert('sched_id='+sched_id+'&date='+date+'&account_id='+account_id);
			//show list of schedule block per employee/staff		
			jQuery.ajax({
				 url: "pages/shift_desired_schedule.php",
				 type: 'POST',
				 data: 'sched_id='+sched_id+'&date='+date+'&account_id='+account_id,
				 success: function(value){
					jQuery('#desired_request').fadeIn('slow');
					jQuery('#desired_sched').fadeIn('slow').html(value);
					jQuery('#new_days').html('');
					jQuery('#other_employees').fadeOut('fast');
				 }
			});	

			//show other employees on off-duty
			jQuery.ajax({
				 url: "pages/shift_other_employees.php",
				 type: 'POST',
				 data: 'sched_id='+sched_id+'&date='+date+'&account_id='+account_id,
				 success: function(value){
					jQuery('#other_employees').html(value);
				 }
			});	

			
		});    
	
	});    	
	</script>
<?php
}
?>    