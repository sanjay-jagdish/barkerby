<?php
session_start();
include_once('redirect.php'); 

require('../config/config.php');
ini_set('display_errors',0);
$res_id = trim($_POST['parameters']);
$date = strip_tags(mysql_real_escape_string($_POST['date']));

?>


<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">

<style>
	tr:hover td{ background:none !important; }
	td{ padding:2px; }
	.new_customer{ display:none; }
	#duration{  }
	#selected_tables, #selected_seats{ font-weight:bold; }
</style>
<div style="width:450px;">

<script>
jQuery( "#cancel_booking" ).click(function() {
				
		var res_det_id = jQuery('#res_det_id').val();
		var reason = jQuery('#reason').val();
		var date_selected = jQuery('#date_selected').val();

		jQuery.ajax({
				 url: "actions/delete-reservation.php",
				 type: 'POST',
				 data: 'id='+res_det_id+'&reason='+reason,
				 success: function(value){
					if(jQuery.isNumeric(value)){	
						modal.open({content: '<font color="green">SUCCESS!</font> Reservation <b>No. '+value+'</b> has been cancelled.'});

							setTimeout(function(){
								 // location.reload();
								  jQuery('#modal, #overlay').fadeOut();
							}, 3000);
							
							jQuery.ajax({

						  			// call calendar-data.php to refresh content on page
						  			// should include the reservation added after load

										url: "tablechart/calendar-data.php",
										type: 'GET',
										data: 'date='+date_selected,
										success: function(value){
											jQuery('.fadediv, .loaddiv').fadeOut();
											jQuery('#calendar-wrapper').html(value);
											// alert(value);
										}
									});				
										
					}else{
						alert(value);
					}
				 }
		});		

  
});
</script>

<?php


$res_details_sql = "SELECT r.account_id AS id, r.date, r.time, DATE_FORMAT(DATE_ADD(TIME(r.time), INTERVAL r.duration MINUTE),'%H:%i') AS end, r.duration, a.fname, a.lname, a.phone_number, 
						r.number_people AS pax, r.note 
						FROM reservation r 
						LEFT JOIN account a ON a.id=r.account_id
						WHERE r.approve=2 AND r.id=".$res_id;
$res_details_qry = mysql_query($res_details_sql);

$res_details_res = mysql_fetch_assoc($res_details_qry);

	$numguest=$res_details_res['number_people'];
	$currentDate=date('Y-m-d',strtotime($res_details_res['date']));
	$dayName=date('D', strtotime($currentDate));
	$time_start = $res_details_res['time'];
	$time_end = $res_details_res['end'];	

$q=mysql_query("select id, start_time, end_time, time_interval, dine_interval, between_interval from restaurant_detail 
				where '".$currentDate."' >= STR_TO_DATE(start_date, '%m/%d/%Y') and '".$currentDate."' <= STR_TO_DATE(end_date, '%m/%d/%Y') and days like '%".$dayName."%' order by id desc limit 1") 
			or die(mysql_error());
$rs=mysql_fetch_assoc($q);
?>

<div style="float:left;">
    <h3 style="margin:0px; padding:0px; color:#F00;">You are about to cancel booking #<?php echo $res_id; ?>
      <input type="hidden" name="res_det_id" id="res_det_id" value="<?php echo $res_id; ?>" />
    </h3>
</div>


<div style="clear:both; margin-bottom:8px;">
</div>


<table width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td align="right" valign="top"> Customer </td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    	<strong>
		<?php
		$client_name = $res_details_res['fname'] .' '.$res_details_res['lname'];
		 
		if(trim($client_name)!=''){ 
			echo $res_details_res['fname'] .' '.$res_details_res['lname'].' ['.$res_details_res['phone_number'].']'; 
		}else{
			echo '***Walk-In***';
		}
		?>
        </strong>
    </td>
  </tr>
  <tr>
    <td align="right" valign="middle">Date&nbsp; </td>
    <td align="center" valign="middle">:&nbsp;</td>
    <td valign="middle">
    	<strong><?php echo date('D M d, Y',strtotime($res_details_res['date'])); ?></strong>
    	<input type="hidden" name="date_selected" id="date_selected" value="<?php echo date('y-m-d',strtotime($res_details_res['date'])); ?>" />
    </td>
    <td valign="middle">&nbsp;</td>
    <td align="right" valign="middle">From </td>
    <td align="center" valign="middle">:&nbsp;</td>
    <td valign="middle">
	<?php

			
			$min_start_time = date('H:i:s',strtotime($res_details_res['time']));
			$min_start_time = roundToInterval($min_start_time,$rs['time_interval']);
			
			$ideal_end_time = date('H:i:00', strtotime($min_start_time.' +'.$rs['dine_interval'].' mins'));
			
			$time_increments = $rs['time_interval'];
			$time = strtotime($res_det['start_time']);
			$end = strtotime($res_det['end_time']);
			
			$seltag = 0;
			while($time<=$end){
				if($min_start_time==date('H:i:00',$time) && $seltag==0){ $sel=' selected="selected"'; $seltag=1; }else{ $sel=''; }
				$time_options .= '<option value="'.date('H:i:s',$time).'" '.$sel.'>'.date('H:i',$time).'</option>';
	            $time = strtotime(date('H:i:s',$time).' + '.$time_increments.' mins');	
		?>

		<strong><?php echo substr($min_start_time,0,5); ?></strong>
    <?php
		}
	?>
	</td>
  </tr>
	<tr>
	  <td align="right" valign="middle">Guests&nbsp; </td>
	  <td align="center" valign="middle">:&nbsp;</td>
	  <td valign="middle"><strong><?php echo $res_details_res['pax']; ?></strong></td>
    	<td valign="middle">&nbsp;</td>
        <td align="right" valign="middle">To </td>
        <td align="center" valign="middle">:&nbsp;</td>
        <td valign="middle"><strong><?php echo substr($ideal_end_time,0,5); ?></strong></td>
    </tr>
	<tr>
	  <td colspan="3" align="right">&nbsp;</td>
	  <td align="left">&nbsp;</td>
	  <td align="right">Duration</td>
	  <td align="center">:</td>
	  <td align="left">
      	<div id="duration">
		<?php 
		$hrs = gmdate("H", ($rs['dine_interval'] * 60));
		$mins = gmdate("i", ($rs['dine_interval'] * 60));
		echo 'Hrs: <strong>'.$hrs.'</strong> &nbsp;Mins: <strong>'.$mins.'</strong>';
		//echo $garcon_settings['dine_interval']; 
		
		$seats_tables_sql = "SELECT COUNT(rt.id) AS tables, SUM(td.max_pax) AS seats 
							 FROM reservation r, reservation_table rt, table_detail td 
							 WHERE r.id=".$res_id." AND r.id=rt.reservation_id AND rt.table_detail_id=td.id";
		$seats_tables_qry = mysql_query($seats_tables_sql);
		$seats_tables_res = mysql_fetch_assoc($seats_tables_qry);
		?>
        </div>
      </td>
    </tr>
	<tr>
	  <td align="right">Seats</td>
	  <td align="center">:</td>
	  <td><div id="selected_seats"><?php echo $seats_tables_res['seats']; ?></div></td>
	  <td>&nbsp;</td>
	  <td align="right">Tables</td>
	  <td align="center">:</td>
	  <td><div id="selected_tables"><?php echo $seats_tables_res['tables']; ?></div></td>
    </tr>
	<tr>
	  <td colspan="7" align="center">
      	<div id="seats_difference"></div>
        Selected Table(s):
   	    <div style="overflow:auto; width:250px !important; border:solid thin #ccc;" id="table_list">
		<?php
		$seats_tables_sql = "SELECT rt.table_detail_id AS id, td.max_pax, td.table_name  
							 FROM reservation r, reservation_table rt, table_detail td 
							 WHERE r.id=".$res_id." AND r.id=rt.reservation_id AND rt.table_detail_id=td.id AND rt.deleted=0";
		$seats_tables_qry = mysql_query($seats_tables_sql);
		?>
			<table border="1" style="border-collapse:collapse; border:#CCC;">
                <thead>
                    <tr>
                      <th width="10%" bgcolor="#CCCCCC">Seats</th>
                        <th width="83%" bgcolor="#CCCCCC">Table</th>
                    </tr>
                </thead>
                <tbody>
			<?php
			while($seats_tables_res = mysql_fetch_assoc($seats_tables_qry)){
			?>
                <tr>
	                <td align="center"><?php echo $seats_tables_res['max_pax']; ?></td>
                    <td align="center" nowrap="nowrap"><?php echo $seats_tables_res['table_name']; ?></td>
                </tr>            
    
            <?php
			}		
			?>
	            </tbody>
            </table>
			<br />
        </div>
      </td>
    </tr>
  <tr>
    <td align="right" valign="top">Notes/Remarks</td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <?php echo nl2br(trim($res_details_res['note'])); ?>
    </td>
  </tr>
	<tr>
	  <td align="right">&nbsp;</td>
	  <td align="center">&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td align="right">&nbsp;</td>
	  <td align="center">&nbsp;</td>
	  <td>&nbsp;</td>
    </tr>
  <tr>
    <td colspan="7" align="center">
      To confirm, state the reson below and click the "Cancel Booking" button.<br />
      <textarea style="width:200px; height:40px;" id="reason"></textarea>
      <br />      
      <input type="button" id="cancel_booking" value="CANCEL BOOKING" style="padding:4px;" /></td>
  </tr>
</table>

</div>

<input type="hidden" id="customer_id" value="<?php echo $res_details_res['id']; ?>">
<input type="hidden" id="thecustomers" value="<?php echo $thecustomers; ?>">

<?php
function roundToInterval($timestring, $interval){
    $minutes = date('i', strtotime($timestring));
    $minutes = $minutes - ($minutes % $interval);
	return date('H',strtotime($timestring)).':'.str_pad($minutes,2,0,STR_PAD_LEFT).':00';
}
?>