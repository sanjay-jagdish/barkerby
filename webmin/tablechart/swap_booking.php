<?php
require('../config/config.php');
ini_set('display_errors',0);
$res_id = trim($_POST['res_id']);
?>

<link rel="stylesheet" href="css/jquery-ui.css">

<style>
	td{ padding:2px; }
	.new_customer{ display:none; }
	#duration{  }
	#selected_tables, #selected_seats{ font-weight:bold; }
	
	.table_swapping table{
		border-color:#CCC;
		border:solid thin #CCC;
	}	
	
	.table_swapping td{
		padding:4px;
	}
</style>
<div style="width:650px;">


<?php


$res_details_sql = "SELECT r.account_id AS id, r.id AS reservation_id, r.date, r.time, 
						ADDTIME(TIME(r.time), SEC_TO_TIME(r.duration*60)) AS end, r.duration, a.fname, a.lname, a.phone_number, 
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

//echo '<pre>';
//print_r($res_details_res);
//echo '</pre>';

$q=mysql_query("select id, start_time, end_time, time_interval, dine_interval, between_interval from restaurant_detail 
				where '".$currentDate."' >= STR_TO_DATE(start_date, '%m/%d/%Y') and '".$currentDate."' <= STR_TO_DATE(end_date, '%m/%d/%Y') and days like '%".$dayName."%' order by id desc limit 1") 
			or die(mysql_error());
$rs=mysql_fetch_assoc($q);
?>

<div style="float:left; width:200px;">
    <h3 style="margin:0px; padding:0px;">Swap Tables <input type="hidden" id="date_selected" value="<?php echo date('y-m-d',strtotime($currentDate)); ?>" /></h3>
</div>

<div style="float:right; width:250px; text-align:right; vertical-align:bottom;">
	Current Time: <?php echo date('H:i:s - d M Y'); ?>
</div>

<div style="clear:both; margin-bottom:8px; text-align:right;">
<?php

$reservations_sql = "SELECT r.id, r.date, r.time, ADDTIME(TIME(r.time), SEC_TO_TIME(r.duration*60)) AS end, r.duration, a.fname, a.lname, a.phone_number, 
						r.number_people AS pax, r.note 
						FROM reservation r, account a 
						WHERE r.deleted=0 AND STR_TO_DATE(date, '%m/%d/%Y')='".date('Y-m-d',strtotime($res_details_res['date']))."' 
						AND TIME(time)>=TIME('".date('H:i',strtotime('now'))."') AND r.account_id<>".$res_details_res['id']."
						AND r.account_id=a.id AND number_people=".$res_details_res['pax'];


$reservations_sql = "SELECT r.id, r.date, r.time, ADDTIME(TIME(r.time), SEC_TO_TIME(r.duration*60)) AS end, r.duration, a.fname, a.lname, a.phone_number, 
							r.number_people AS pax, r.note 
							FROM reservation r
							LEFT JOIN account a ON a.id=r.account_id
							WHERE r.id<>".$res_id." AND r.deleted=0 AND STR_TO_DATE(date, '%m/%d/%Y')='".date('Y-m-d',strtotime($res_details_res['date']))."' 
							AND TIME(time)>=TIME('".date('H:i',strtotime('now'))."') AND number_people=".$res_details_res['pax'];


$reservations_qry = mysql_query($reservations_sql);
$reservations_num = mysql_num_rows($reservations_qry);

$selection_reservation_list='';

if($reservations_num>0){
	
	while($reservations_res = mysql_fetch_assoc($reservations_qry)){
		
		//check if tables on Reservation B can accommodate TIME Reservation A
		$tables_sql = "SELECT table_detail_id AS table_id FROM reservation_table WHERE reservation_id=".$reservations_res['id'];
		$tables_qry = mysql_query($tables_sql);
		
		//echo $tables_sql.'<br><br>';
		
		$show = 1;
		
		while($tables_res = mysql_fetch_assoc($tables_qry)){
			//check if particular table is available for Reservation Time of "A"
			$table_available_sql = "SELECT r.id FROM reservation_table rt, reservation r 
									WHERE rt.reservation_id=r.id AND rt.reservation_id<>".$reservations_res['id']." 
										AND rt.table_detail_id=".$tables_res['table_id']." 
										AND STR_TO_DATE(r.date, '%m/%d/%Y')='".date('Y-m-d',strtotime($res_details_res['date']))."'
										AND (
											 TIME(r.time)>=TIME('".$reservations_res['time']."') 
												OR 
											 ADDTIME(TIME(r.time), SEC_TO_TIME(r.duration*60))<=TIME('".$reservations_res['end']."') 
											 )
									";
			
			//echo 'Table OK on Resv A?<br>'.$table_available_sql.'<br><br>';
			$table_available_qry = mysql_query($table_available_sql);
			$table_available_num = mysql_num_rows($table_available_qry);
			
			if($table_available_num>1){
			
				$show = 0;
				break;
			
			}else{
				
				//check if tables on Reservation B can accommodate TIME Reservation A
				$tables_sql = "SELECT table_detail_id AS table_id FROM reservation_table WHERE reservation_id=".$res_id;
				$tables_qry = mysql_query($tables_sql);
				
				//echo $tables_sql.'<br><br>';
				
				$show = 1;
				
				while($tables_res = mysql_fetch_assoc($tables_qry)){
						
					//check if tables on Reservation A can be accommodated on B
					$table_available_sql = "SELECT r.id FROM reservation_table rt, reservation r 
											WHERE rt.reservation_id=r.id AND r.id<>".$res_id." 
												AND rt.table_detail_id=".$tables_res['table_id']." 
												AND STR_TO_DATE(r.date, '%m/%d/%Y')='".date('Y-m-d',strtotime($res_details_res['date']))."'
												AND (
													 TIME(r.time)>=TIME('".$reservations_res['time']."') 
														OR 
													 ADDTIME(TIME(r.time), SEC_TO_TIME(r.duration*60))<=TIME('".$reservations_res['end']."') 
													 )
											";
					//echo $table_available_sql.'<br><br>';
					$table_available_qry = mysql_query($table_available_sql);
					$table_available_num = mysql_num_rows($table_available_qry);
					
					if($table_available_num>1){
					
						$show = 0;
						break;
					
					}			
				}
			
			}
			
			
		}
		
		if(trim($reservations_res['fname'].$reservations_res['lname'])!=''){
			$client_name = $reservations_res['fname'].' '.$reservations_res['lname'];
		}else{
			$client_name = '***Walk-In***';
		}
	
		if($show==1){
			$selection_reservation_list .= '<option value="'.$reservations_res['id'].'">#'.$reservations_res['id'].' ('.date('H:i',strtotime($reservations_res['time'])).
											'&rarr;'.date('H:i',strtotime($reservations_res['end'])).') '.$client_name.'</option>';
		}
		
	}//while loop

}//if there are reservations


if($reservations_num>0){

	if(trim($selection_reservation_list)!=''){
	?>
	
	Select the booking/reservation to swap with.<br />
	
	  <select name="select" id="res_client_id_B">
		<option>-- select a reservation --</option>
		<?php echo $selection_reservation_list; ?>
	  </select>
	<?php
	
	}else{
		echo '<font color="red">There are no reservation available for swapping with this reservation.</font>';
	}

}else{
	echo '<font color="red">There are no other reservation to swap with.<br>Please try picking other table as swapping option is not possible at the moment.</font>';
}
	
?>  
  
  <div id="swap_now"></div>
  
</div>

<table width="100%" cellspacing="2" cellpadding="2" class="table_swapping">
	<tr>
	  <td width="100%" align="right">
      	<div align="center">
        <table width="100%" style="background-color:#CCC; border-collapse:collapse;" cellspacing="3" cellpadding="2" border="1">
        	<tr>
            	<td width="50%" align="center" bgcolor="#FFFFFF"><strong><?php echo $res_id; ?><span style="margin:0px; padding:0px;">
           	    <input type="hidden" name="res_det_id" id="res_det_id_A" value="<?php echo $res_id; ?>" />
           	    <input type="hidden" name="res_det_id" id="res_client_id_A" value="<?php echo $res_details_res['id']; ?>" />
            	</span></strong></td>
                <td align="center">ID</td>
                <td width="50%" align="center" bgcolor="#FFFFFF" id="res_id_B"><strong><span style="margin:0px; padding:0px;">
                  <input type="hidden" name="res_det_id_B" id="res_det_id_B" value="<?php echo $res_details_res['id']; ?>" />
                </span></strong></td>
            </tr>
        	<tr>
            	<td width="50%" align="center" bgcolor="#FFFFFF"><strong>
				<?php
				if(trim($res_details_res['fname'].$res_details_res['lname'])!=''){ 
					echo $res_details_res['fname'] .' '.$res_details_res['lname'].' ['.$res_details_res['phone_number'].']'; 
				}else{
					echo '***Walk-In***';
				}
				?>
                </strong></td>
                <td align="center">Customer</td>
                <td width="50%" align="center" bgcolor="#FFFFFF" id="res_client_B">&nbsp;</td>
            </tr>
        	<tr>
        	  <td align="center" bgcolor="#FFFFFF"><?php

			
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
                <!--select name="time" id="time_start" style="width:80px;">	
			<?php echo $time_options; ?>
		</select -->
                <input type="hidden" value="<?php echo substr($min_start_time,0,5); ?>" id="orig_time_start" style="width:50px;" />
                <strong><?php echo substr($min_start_time,0,5); ?></strong>
                <?php
		}
	?>
&rarr;
<?php 
		if($res_det_num ==1){	
			
			$time_increments = $rs['time_interval'];
			$time = strtotime($res_det['start_time']);
			$end = strtotime($res_det['end_time']);

			$increments = $time_increments;
		
			while($time<=$end){
	 			$time = strtotime(date('H:i:s',$time).' + '.$time_increments.' mins');	
	           	if($ideal_end_time==date('H:i:s',$time)){ $sel='selected="selected"'; }else{ $sel=''; }
				$time_options .= '<option value="'.date('H:i:s',$time).'" '.$sel.'>'.date('H:i',$time).'</option>';
			}
		
		?>
<!--select name="time_end" id="time_end" style="width:80px;">
		<?php echo $time_options; ?>
        </select -->
<?php
		}//only if the store is open
		?>
<input type="hidden" value="<?php echo substr($ideal_end_time,0,5); ?>" id="orig_time_end" style="width:50px;" />
<strong><?php echo substr($ideal_end_time,0,5); ?></strong></td>
        	  <td align="center">Time</td>
        	  <td align="center" bgcolor="#FFFFFF" id="res_time_B">&nbsp;</td>
      	  </tr>
        	<tr>
        	  <td align="center" bgcolor="#FFFFFF"><?php 
		$hrs = gmdate("H", ($res_details_res['duration'] * 60));
		$mins = gmdate("i", ($res_details_res['duration'] * 60));
		echo 'Hrs: <strong>'.$hrs.'</strong> &nbsp;Mins: <strong>'.$mins.'</strong>';
		//echo $garcon_settings['dine_interval']; 
		
		$seats_tables_sql = "SELECT COUNT(rt.id) AS tables, SUM(td.max_pax) AS seats 
							 FROM reservation r, reservation_table rt, table_detail td 
							 WHERE r.id=".$res_id." AND r.id=rt.reservation_id AND rt.table_detail_id=td.id";
		$seats_tables_qry = mysql_query($seats_tables_sql);
		$seats_tables_res = mysql_fetch_assoc($seats_tables_qry);
		?></td>
        	  <td align="center">Duration</td>
        	  <td align="center" bgcolor="#FFFFFF" id="res_duration_B">&nbsp;</td>
      	  </tr>
        	<tr>
        	  <td align="center" bgcolor="#FFFFFF"><strong><?php echo $res_details_res['pax']; ?></strong></td>
        	  <td align="center">Guests</td>
        	  <td align="center" bgcolor="#FFFFFF" id="res_guests_B">&nbsp;</td>
      	  </tr>
        	<tr>
        	  <td align="center" bgcolor="#FFFFFF">
              <div id="seats_difference"></div>
              <div style="overflow:auto; width:250px !important;" id="table_list">
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
			
			/* trapping the time */
			
			//list down tables available
			$tables_sql = "SELECT td.id, td.table_name, td.max_pax
						   FROM table_detail td
						   WHERE td.restaurant_detail_id=".$rs['id']." 
								 AND id NOT IN 
									 (SELECT rt.table_detail_id 
									  FROM reservation_table rt, reservation r 
									  WHERE r.id=rt.reservation_id AND r.date='".date('m/d/Y',strtotime($currentDate))."' 
											AND (
		
													( '".$time_start."' BETWEEN r.time AND ADDTIME(TIME(r.time), SEC_TO_TIME(duration*60)) ) 
														OR 
													( '".$time_end."' BETWEEN r.time AND ADDTIME(TIME(r.time), SEC_TO_TIME(duration*60)) ) 
																							
												)
									  )
							ORDER BY td.max_pax ASC";
												
			$tables_qry = mysql_query($tables_sql) or die($tables_sql.'<br>'.mysql_error());
	
					
			//$q=mysql_query("select id from table_detail where restaurant_detail_id=".$currentID." and max_pax>=".$numguest);
			$totaltables=mysql_num_rows($tables_qry);
			
			if($totaltables>0){			
	
					$tables_stats_sql = "SELECT COUNT(td.id) AS tables, SUM(td.max_pax) AS seats
										   FROM table_detail td
										   WHERE td.restaurant_detail_id=".$rs['id']." 
												 AND id NOT IN 
													 (SELECT rt.table_detail_id 
													  FROM reservation_table rt, reservation r 
													  WHERE r.id=rt.reservation_id AND r.date='".date('m/d/Y',strtotime($currentDate))."' 
															AND (r.time >= '".$time_start."' OR DATE_FORMAT(DATE_ADD(TIME(r.time), INTERVAL 60 MINUTE),'%H:%i') <= '".$time_end."' )
													  )
								   ";
							
					$tables_stats_qry = mysql_query($tables_stats_sql) or die(mysql_error());
					$tables_stats_res = mysql_fetch_assoc($tables_stats_qry);
	
				?>
                  </tbody>
                  <tbody>
                    <?php
				while($table_det = mysql_fetch_assoc($tables_qry)){
				?>
                    <?php
				}		
				?>
                  </tbody>
                  <?php	
			}
			else{
				echo "There are no available tables for the selected date (".date('d M Y',strtotime($currentDate)).").";
			}			
			
			?>
                </table>
                <br />
              </div></td>
        	  <td align="center">Seats/Tables</td>
        	  <td align="center" bgcolor="#FFFFFF" id="res_tables_B">&nbsp;</td>
      	  </tr>
        </table>
        </div>
      </td>
    </tr>
	<tr>
	  <td align="center" class="results">&nbsp;</td>
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

<script>

$( "#res_client_id_B" ).change(function() {
  // Check input( $( this ).val() ) for validity here
  // xxx //
	var res_id = $('#res_client_id_B').val();
	
	$.ajax({
			 url: "pages/booking_details.php",
			 type: 'POST',
			 data: 'res_id='+res_id,
			 success: function(value){
				if(value!=''){
					var data = value.split('|');
					//distribute/place values to its corresponding location/area
					if(data[1]>0){
						$('#swap_now').html('<input type="button" onclick="confirm_swap()" class="confirm_swap" data-rel="'+data[1]+'" style="padding:4px;" value="CONFIRM SWAP" />');
					}else{
						$('#swap_now').html('');	
					}
					
					$('#res_id_B').html('<b>'+data[1]+'</b>');
					$('#res_client_B').html('<b>'+data[2]+'</b>');
					$('#res_time_B').html('<b>'+data[3]+'</b>');
					$('#res_duration_B').html('<b>'+data[4]+'</b>');
					$('#res_guests_B').html('<b>'+data[5]+'</b>');
					$('#res_tables_B').html('<b>'+data[0]+'</b>');
					/*jQuery('#res_').html('<b>'+data[]+'</b>');*/
				}else{ 
					alert('An error has occurred. Please try again.');
				}
			 }
	});		
	
});




function confirm_swap(){
	var res_ID_1 = $('#res_det_id_A').val();
	var res_ID_2 = $('#res_client_id_B').val();
	var date_selected = $('#date_selected').val();
	
	$('#swap_now').html('<font color="orange">Processing...</font>');	
	
	$.ajax({
			 url: "actions/swap_booking.php",
			 type: 'POST',
			 data: 'res_id_1='+res_ID_1+'&res_id_2='+res_ID_2,
			 success: function(value){
					modal.open({content: '<font color="green">SUCCESS!</font> Table swapping completed.'});

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

			 }
	});
	
};
	
</script>