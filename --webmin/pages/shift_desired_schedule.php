<?php
session_start();
 include_once('redirect.php'); 
include '../config/config.php';

//get schedule details like time and day to exclude other staff's schedule matching the selected time and day
$sql = "SELECT start_time, end_time, days FROM schedule WHERE id=".$_POST['sched_id'];
$qry = mysql_query($sql);
$res = mysql_fetch_assoc($qry);

if($_SESSION['login']['type']<=2){ //admin and manager

	$sql_str = "SELECT s.id, concat(a.lname,' ',a.fname), s.start_time, s.end_time, s.days, s.valid_from, s.valid_until, s.account_id 
				FROM schedule as s, account as a WHERE a.id=s.account_id and s.deleted=0 AND s.account_id<>".$_POST['account_id']." AND
				'".date('Y-m-d')."' BETWEEN STR_TO_DATE(s.valid_from, '%m/%d/%Y') AND STR_TO_DATE(s.valid_until, '%m/%d/%Y') 
				AND a.type_id IN (SELECT type_id FROM account WHERE id=".$_POST['account_id'].") 
				AND NOT ('".$res['start_time']."' BETWEEN start_time AND end_time AND '".$res['end_time']."' BETWEEN start_time AND end_time AND days LIKE '%".date('D',strtotime($_POST['date']))."%')";
}else{
	$sql_str = "SELECT s.id, concat(a.lname,' ',a.fname), s.start_time, s.end_time, s.days, s.valid_from, s.valid_until, s.account_id 
				FROM schedule as s, account as a WHERE a.id=s.account_id and s.deleted=0 AND s.account_id<>".$_SESSION['login']['id']." AND
				'".date('Y-m-d')."' BETWEEN STR_TO_DATE(s.valid_from, '%m/%d/%Y') AND STR_TO_DATE(s.valid_until, '%m/%d/%Y')
				AND a.type_id IN (SELECT type_id FROM account WHERE id=".$_POST['account_id'].")  
				AND NOT ('".$res['start_time']."' BETWEEN start_time AND end_time AND '".$res['end_time']."' BETWEEN start_time AND end_time AND days LIKE '%".date('D',strtotime($_POST['date']))."%')";
}

$q=mysql_query($sql_str);

if(mysql_num_rows($q)>0){
?>
<select name="schedule_2" id="schedule_2">
    <option value="0"> - Välj schema - </option>
	<?php	
	while($r=mysql_fetch_array($q)){
	?>
	<option value="<?php echo $r[0];?>">
	  <?php echo $r[1];?> &raquo;
	  <?php echo date("m/d/Y",strtotime($r[5])); ?> to 
	  <?php echo date("m/d/Y",strtotime($r[6])); ?> @ 
	  <?php echo $r[2];?> till <?php echo $r[3];?> 
	  [<?php echo $r[4];?>]
	</option>                    
	<?php
	}//looping thru schedules
?>
</select>
<?php
}else{
	echo '<br><font color="red">&nbsp;&nbsp;&nbsp; No available scheduled staff to shift with.</font><br><br>';
}
?>
<script>
jQuery(document).ready(function() { 

	$('#schedule_2').on('change', function(e) {

		e.preventDefault();
		e.stopPropagation();
		
		var schedid = ( this.value ); // or $(this).val()
		
		jQuery.ajax({
			 url: "pages/shift_days_switch.php",
			 type: 'POST',
			 data: 'sched_id='+schedid,
			 success: function(value){
				jQuery('#new_days').fadeIn('slow').html(value);
				jQuery('#desired_remarks').fadeIn('slow');			
				jQuery('#desired_submit').fadeIn('slow');			
			 }
		});	
	
	});    
	
});

</script>
