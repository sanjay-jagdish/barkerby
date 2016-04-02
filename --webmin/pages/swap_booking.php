<?php
 include_once('redirect.php'); 
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

<script>


jQuery('#time_start, #time_end').timepicker({
    showPeriodLabels: false,
});


jQuery( "#res_client_id_B" ).change(function() {
  // Check input( $( this ).val() ) for validity here
  // xxx //
	var res_id = jQuery('#res_client_id_B').val();
	
	jQuery.ajax({
			 url: "pages/booking_details.php",
			 type: 'POST',
			 data: 'res_id='+res_id,
			 success: function(value){
				if(value!=''){
					var data = value.split('|');
					//distribute/place values to its corresponding location/area
					jQuery('#res_id_B').html('<b>'+data[1]+'</b>');
					jQuery('#res_client_B').html('<b>'+data[2]+'</b>');
					jQuery('#res_time_B').html('<b>'+data[3]+'</b>');
					jQuery('#res_duration_B').html('<b>'+data[4]+'</b>');
					jQuery('#res_guests_B').html('<b>'+data[5]+'</b>');
					jQuery('#res_tables_B').html('<b>'+data[0]+'</b>');
					/*jQuery('#res_').html('<b>'+data[]+'</b>');*/
				}else{ 
					alert('An error has occurred. Please try again.');
				}
			 }
	});		
	
});



jQuery( "#save_booking" ).click(function() {
	
	//alert('On-going development.');
	//return false;
	
	var pax = parseInt(jQuery('#pax').val());
	var seats_sel = parseInt(jQuery('#selected_seats').html());
			
	//check if the number of guests is provided
	if(pax==0 || isNaN(pax)){
		alert('Please enter the number of guests.');
		return false;
	}
	
	//check if number of seats is enough
	if(seats_sel>0){
		if(pax>seats_sel){
			alert('Seats not enough. Select more seats.');
			return false;
		}
	}else{
		alert('You have not selected a table yet to accommodate the guests.');
		return false;
	}
	

	var value = '';
	
	var table_id = $('input:checkbox:checked.table_option').map(function () {
	  return this.value;
	}).get();
      
	var customer_id = jQuery('#customer_id').val();	
 
  	if(customer_id>0){
	  		
		if(seats_sel>pax){
			var extra_seats = seats_sel-pax;
			confirm(extra_seats+' extra seat(s) detected.  Do you want to proceed and update?');		
		}
				
			var res_det_id = jQuery('#res_det_id').val();
			var date_selected = jQuery('#date_selected').val();	
			var time_start = jQuery('#time_start').val();	
			var time_end= jQuery('#time_end').val();	
			var pax = jQuery('#pax').val();
			//var table_id = jQuery('#table_id').val();	
			var notes = jQuery('#notes').val();
	
			jQuery.ajax({
					 url: "tablechart/updateBooking.php",
					 type: 'POST',
					 data: 'res_id='+res_det_id+'&date_selected='+date_selected+'&time_start='+time_start+'&time_end='+time_end+'&table_id='+table_id+'&pax='+pax+'&notes='+notes+'&res_det_id='+res_det_id+'&customer_id='+customer_id,
					 success: function(value){
						if(jQuery.isNumeric(value)){	
							modal.open({content: '<font color="green">SUCCESS!</font> Reservation <b>No. '+value+'</b> has been updated.'});
							setTimeout(function(){
								 location.reload();
							}, 3000);
						
						}else{
							alert(value);
						}
					 }
			});		


  }
  
});

jQuery('.table_option').click(function() {
	var guests = parseInt(jQuery('#pax').val());
	var data = parseInt(jQuery(this).attr('data-rel'));
	
	var selected_tables = parseInt(jQuery('#selected_tables').html()); 
	var selected_seats = parseInt(jQuery('#selected_seats').html()); 
	
	if(jQuery(this).is(':checked')) {
		var new_tables = selected_tables + 1;
		var new_seats = selected_seats + data;
		jQuery('#selected_tables').html(new_tables)
		jQuery('#selected_seats').html(new_seats)
	}else{
		var new_tables = selected_tables - 1;
		var new_seats = selected_seats - data;
		jQuery('#selected_tables').html(new_tables)
		jQuery('#selected_seats').html(new_seats)
	}
	
	//determine whether seats selected is enough or not #seats_difference
	var diff = guests - new_seats;
	if(diff==0){
		//do nothing -- all is good
		jQuery('#seats_difference').hide();
	}else{
		if(diff>0){
			jQuery('#seats_difference').html('You need to select '+diff+' seat/s more.' );
			jQuery('#seats_difference').css({"color":"red"})
			jQuery('#seats_difference').show();
			//short of seats
		}else{
			diff = diff *-1;
			jQuery('#seats_difference').html(diff+' extra seat/s.');
			jQuery('#seats_difference').css({"color":"green"})
			jQuery('#seats_difference').show();
			//over of seats	
		}	
	}
	
});

jQuery('#pax').keyup(function() {
		
	var guests = parseInt(jQuery('#pax').val()); 
	var selected_seats = parseInt(jQuery('#selected_seats').html()); 
		
	//determine whether seats selected is enough or not #seats_difference
	var diff = guests - selected_seats;
	if(diff==0){
		//do nothing -- all is good
		jQuery('#seats_difference').hide();
	}else{
		if(diff>0){
			jQuery('#seats_difference').html('You need to select '+diff+' seat/s more.' );
			jQuery('#seats_difference').css({"color":"red"})
			jQuery('#seats_difference').show();
			//short of seats
		}else{
			diff = diff *-1;
			jQuery('#seats_difference').html(diff+' extra seat/s.');
			jQuery('#seats_difference').css({"color":"green"})
			jQuery('#seats_difference').show();
			//over of seats	
		}	
	}

});


$(document).ready(function(){

	$('#cancel_booking').click(function(e){
		
		e.preventDefault();
		var parameters = jQuery(this).attr('data-rel');
		
		jQuery('.fadediv, .loaddiv').fadeIn();
		jQuery.ajax({
				 url: "pages/cancel-reservation.php",
				 type: 'POST',
				 data: 'parameters='+parameters,
				 success: function(value){
					jQuery('.fadediv, .loaddiv').fadeOut();
					modal.open({content: value});

				 }
		});			
		
	});	
	
});
</script>

<?php


$res_details_sql = "SELECT r.account_id AS id, r.date, r.time, DATE_FORMAT(DATE_ADD(TIME(r.time), INTERVAL r.duration MINUTE),'%H:%i') AS end, r.duration, a.fname, a.lname, a.phone_number, 
						r.number_people AS pax, r.note 
						FROM reservation r, account a
						WHERE a.id=r.account_id AND r.approve=2 AND r.id=".$res_id;
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

<div style="float:left; width:200px;">
    <h3 style="margin:0px; padding:0px;">Swap Tables</h3>
</div>

<div style="float:right; width:250px; text-align:right; vertical-align:bottom;">
	Current Time: <?php echo date('H:i:s - d M Y'); ?>
</div>

<div style="clear:both; margin-bottom:8px; text-align:right;">
Select the booking/reservation to swap with.
  <select name="select" id="res_client_id_B">
  	<option>-- select a reservation --</option>
<?php
$reservations_sql = "SELECT r.id, r.date, r.time, DATE_FORMAT(DATE_ADD(TIME(r.time), INTERVAL r.duration MINUTE),'%H:%i') AS end, r.duration, a.fname, a.lname, a.phone_number, 
						r.number_people AS pax, r.note 
						FROM reservation r, account a 
						WHERE STR_TO_DATE(date, '%m/%d/%Y')='".date('Y-m-d',strtotime($res_details_res['date']))."' 
						AND time='".date('H:i',strtotime($res_details_res['time']))."' AND r.id<>".$res_id."
						AND r.account_id=a.id";
$reservations_qry = mysql_query($reservations_sql);
while($reservations_res = mysql_fetch_assoc($reservations_qry)){
?>
    <option value="<?php echo $reservations_res['id']; ?>"><?php echo $reservations_res['id'].' &rarr; '.$reservations_res['fname'].' '.$reservations_res['lname']; ?> </option>
<?php
}
?>
  </select>
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
            	<td width="50%" align="center" bgcolor="#FFFFFF"><strong><?php echo $res_details_res['fname'] .' '.$res_details_res['lname'].' ['.$res_details_res['phone_number'].']'; ?></strong></td>
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
	  <td align="center">&nbsp;</td>
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