<?php
require('../config/config.php');
ini_set('display_errors',0);
$res_id = trim($_POST['res_id']);
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

<!--<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="tablechart/js/jquery-ui-timepicker-0.3.3/jquery.ui.timepicker.js"></script>

<script src="tablechart/js/jQuerytypeahead/typeahead.js"></script>
<link href="tablechart/js/jQuerytypeahead/examples.css" rel="stylesheet" type="text/css" />-->

<script>


jQuery('#time_start, #time_end').timepicker({
    showPeriodLabels: false,
});

/*
jQuery( "#time_start" ).change(function() {
  // Check input( $( this ).val() ) for validity here
	var start_time = jQuery( "#time_start" ).val();
	var end_time = jQuery( "#time_end" ).val();
	
	confirm('Table selection will be reset.  Do you want to proceed?');
		
	if(start_time==''){
		
		jQuery('#duration').html('<font color="red">Invalid Time</font>');
		return false;
	}

	jQuery.ajax({
			 url: "tablechart/timeDiffMins.php",
			 type: 'POST',
			 data: 'start='+start_time+'&end='+end_time,
			 success: function(value){
				jQuery('#duration').html(value);
			 }
	});		
	
}); */


jQuery( "#time_start, #time_end" ).change(function() {
  // Check input( $( this ).val() ) for validity here
	var start_time = jQuery( "#time_start" ).val();
	var end_time = jQuery( "#time_end" ).val();

	if(end_time==''){
		jQuery('#duration').html('<font color="red">Invalid Time</font>');
		return false;
	}

	confirm('Changing the Time will reset pre-selected tables.  Do you want to proceed?');

	//clear tables
	jQuery('#selected_tables').html('0');
	jQuery('#selected_seats').html('0');
	jQuery('#seats_difference').html('');

	jQuery.ajax({
			 url: "tablechart/timeDiffMins.php",
			 type: 'POST',
			 data: 'start='+start_time+'&end='+end_time,
			 success: function(value){
				jQuery('#duration').html(value);
			 }
	});		
	
	reloadTables();
	
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

	
//jQuery('#reload_tables').click(function() {
function reloadTables(){

var res_id = jQuery('#res_det_id').val();
var time_start = jQuery('#time_start').val();
var time_end = jQuery('#time_end').val();
var pax = jQuery('#pax').val();

//clear tables
jQuery('#selected_tables').html('0');
jQuery('#selected_seats').html('0');
jQuery('#seats_difference').html('');

		jQuery.ajax({
				 url: "tablechart/edit_table_list_reload.php",
				 type: 'POST',
				 data: 'res_id='+res_id+'&time_start='+time_start+'&time_end='+time_start+'&pax='+pax,
				 success: function(value){
						jQuery('#table_list').html(value);					
				 }
		});			
}; 


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

	$('#swap_tables').click(function(e){
		
		e.preventDefault();
		var res_id = jQuery('#res_det_id').val();
		
		jQuery('.fadediv, .loaddiv').fadeIn();
		jQuery.ajax({
				 url: "tablechart/swap_booking.php",
				 type: 'POST',
				 data: 'res_id='+res_id,
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
    <h3 style="margin:0px; padding:0px;">Redigera Bokning #<?php echo $res_id; ?>
      <input type="hidden" name="res_det_id" id="res_det_id" value="<?php echo $res_id; ?>" />
    </h3>
</div>

<div style="float:right; width:250px; text-align:right; vertical-align:bottom;">
	Aktuell tid och datum: <?php echo date('H:i:s - d M Y'); ?>
</div>

<div style="float:right; margin-bottom:8px;">
    <a href="#" id="cancel_booking" data-rel="<?php echo $res_id; ?>">Cancel Booking</a> | 
    <a href="#" id="swap_tables" data-rel="<?php echo $res_id; ?>">Swap Tables</a>
</div>


<table width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td align="right" valign="top"> Gäst </td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5"><strong><?php echo $res_details_res['fname'] .' '.$res_details_res['lname'].' ['.$res_details_res['phone_number'].']'; ?></strong></td>
  </tr>
  <tr>
    <td align="right" valign="middle">Datum&nbsp; </td>
    <td align="center" valign="middle">:&nbsp;</td>
    <td valign="middle">
    	<strong><?php echo date('D M d, Y',strtotime($res_details_res['date'])); ?></strong>
    	<input type="hidden" name="date_selected" id="date_selected" value="<?php echo $res_details_res['date']; ?>" />
    </td>
    <td valign="middle">&nbsp;</td>
    <td align="right" valign="middle">Från </td>
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
		<!--select name="time" id="time_start" style="width:80px;">	
			<?php echo $time_options; ?>
		</select -->
        <input type="hidden" value="<?php echo substr($min_start_time,0,5); ?>" id="orig_time_start" style="width:50px;" />	
        <input type="text" name="time" value="<?php echo substr($min_start_time,0,5); ?>" id="time_start" style="width:50px;" />
    <?php
		}
	?>
	</td>
  </tr>
	<tr>
	  <td align="right" valign="middle">Antal personer&nbsp; </td>
	  <td align="center" valign="middle">:&nbsp;</td>
	  <td valign="middle">
	  <input type="text" name="pax" id="pax" style="width:60px;" value="<?php echo $res_details_res['pax']; ?>" />
		</td>
    	<td valign="middle">&nbsp;</td>
        <td align="right" valign="middle">Till </td>
        <td align="center" valign="middle">:&nbsp;</td>
        <td valign="middle">
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
        <input type="text" name="time_end" value="<?php echo substr($ideal_end_time,0,5); ?>" id="time_end" style="width:50px;" />

        </td>
    </tr>
	<tr>
	  <td colspan="3" align="right">
      	<div align="center"><input type="button" id="reload_tables" value=" Refresh Table List&nbsp; " /></div>
      </td>
	  <td align="left">&nbsp;</td>
	  <td align="right">Längd</td>
	  <td align="center">:</td>
	  <td align="left">
      	<div id="duration">
		<?php 
		$hrs = gmdate("H", ($rs['dine_interval'] * 60));
		$mins = gmdate("i", ($rs['dine_interval'] * 60));
		echo 'Tim: <strong>'.$hrs.'</strong> &nbsp;Min: <strong>'.$mins.'</strong>';
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
        Välj bord:
   	    <div style="overflow:auto; width:250px !important; height:150px !important; border:solid thin #ccc;" id="table_list">
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
                        <th bgcolor="#CCCCCC" style="width:25px !important;">&nbsp;</th>
                        <th width="83%" bgcolor="#CCCCCC">Table</th>
                    </tr>
                </thead>
                <tbody>
			<?php
			while($seats_tables_res = mysql_fetch_assoc($seats_tables_qry)){
			?>
                <tr>
	                <td align="center"><?php echo $seats_tables_res['max_pax']; ?></td>
                    <td align="center"><input type="checkbox" data-rel="<?php echo $seats_tables_res['max_pax']; ?>" id="<?php echo $table_det['id']; ?>" 
                    					class="table_option" name="tables[]" value="<?php echo $seats_tables_res['id']; ?>" checked="checked" /></td>
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
	
					echo '<b>'.$tables_stats_res['seats'].'</b> seats out of <b>'.$tables_stats_res['tables'].'</b> tables.';
				?>
					<thead>
						<tr>
						  <th width="10%" bgcolor="#CCCCCC">Seats</th>
							<th bgcolor="#CCCCCC" style="width:25px !important;">&nbsp;</th>
							<th width="83%" bgcolor="#CCCCCC">Table</th>
						</tr>
					</thead>
					<tbody>
				<?php
				while($table_det = mysql_fetch_assoc($tables_qry)){
				?>
					<tr>
						<td align="center"><?php echo $table_det['max_pax']; ?></td>
						<td align="center"><input type="checkbox" data-rel="<?php echo $table_det['max_pax']; ?>" id="<?php echo $table_det['id']; ?>" class="table_option" name="tables[]" value="<?php echo $table_det['id']; ?>" /></td>
						<td align="center" nowrap="nowrap"><?php echo $table_det['table_name']; ?></td>
					</tr>            
		
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
	            </tbody>
            </table>
			<br />
        </div>
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
    <td align="right" valign="top">Övrig information</td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <textarea name="notes" id="notes" style="width:300px; height:50px;"><?php echo trim($res_details_res['note']); ?></textarea>
    </td>
  </tr>
  <tr>
    <td align="right" valign="top">&nbsp;</td>
    <td align="right" valign="top">&nbsp;</td>
    <td colspan="5">
      <input type="button" id="save_booking" value="Spara" style="padding:4px;" /></td>
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