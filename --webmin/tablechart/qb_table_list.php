<?php

require('../config/config.php');
	
	function removeColon($str){
		$val=explode(':',$str);
		return $val[0].$val[1];
	}
	
	function timeFormat($str){
		$first=substr($str,0,strlen($str)-2);
		$second=substr($str,strlen($str)-2,strlen($str));
		
		if(strlen($first)==1){
			$first='0'.$first;
		}
		
		return $first.":".$second;
	}
	
	function addZero($str){
		if(strlen($str)==1){
			return '0'.$str;
		}
		else{
			return $str;
		}
	}
	
	$numguest=$_POST['pax']+0;
	$currentDate=$_POST['date_selected'];
	$dayName=date('D', strtotime($currentDate));
	$time_start = $_POST['time_start'];
	$time_end = $_POST['time_end'];
	
	
	if($numguest==0){ die('You should have at least one (1) guest/person for the table reservation.'); }
	
?>
<script type="text/javascript">
	function setTheTime(str,btn){
		jQuery('.home-middle-3 .reservation-btn').attr('data-rel',btn);
		jQuery('.restime').val(str);
		jQuery('.reservation-fade, .time-container').fadeOut();
	}
	
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

jQuery('#time_start').change(function() {
	//reset dynamic data/info
	jQuery('#selected_seats').html(''); 
	jQuery('#selected_tables').html(''); 
	jQuery('#seats_difference').html(''); 
	jQuery('#table_list').html('Reservation time has changed.  You need to click the "Refresh Table List to retrieve an up-to-date data.'); 
	
});


</script>
<?php	
	
	
	$disabled_time=array();
		
	$q=mysql_query("select id, start_time, end_time, time_interval, dine_interval, between_interval from restaurant_detail 
					where '".$currentDate."' >= STR_TO_DATE(start_date, '%m/%d/%Y') and '".$currentDate."' <= STR_TO_DATE(end_date, '%m/%d/%Y') and days like '%".$dayName."%' order by id desc limit 1") 
				or die(mysql_error());
	$rs=mysql_fetch_assoc($q);
	
	
	if(mysql_num_rows($q) > 0){
		
		$currentID=$rs['id'];
		
		/* trapping the time */
		
		//list down tables available
		$tables_sql = "SELECT td.id, td.table_name, td.max_pax
					   FROM table_detail td
					   WHERE td.restaurant_detail_id=".$rs['id']." 
					   		 AND id NOT IN 
								 (SELECT rt.table_detail_id 
								  FROM reservation_table rt, reservation r 
								  WHERE r.id=rt.reservation_id AND r.deleted=0 AND r.status<3 AND r.date='".date('m/d/Y',strtotime($currentDate))."' 
									AND (
	
											( '".$time_start."' BETWEEN ADDTIME( TIME(r.time), -SEC_TO_TIME((duration+".$rs['between_interval'].")*60) ) 
																	AND ADDTIME( TIME(r.time),  SEC_TO_TIME((duration+".$rs['between_interval'].")*60) ) )  
												OR 
											( '".$time_end."' BETWEEN ADDTIME( TIME(r.time), -SEC_TO_TIME((duration+".$rs['between_interval'].")*60) ) 
																  AND ADDTIME( TIME(r.time),  SEC_TO_TIME((duration+".$rs['between_interval'].")*60) ) )
								  		)
								  )
					    ORDER BY td.max_pax ASC";

/*
												( '".date('H:i',strtotime($time_start.' -'.($rs['between_interval']).' minutes'))."' BETWEEN r.time AND ADDTIME(TIME(r.time), SEC_TO_TIME(duration*60)) ) 
													OR 
												( '".date('H:i',strtotime($time_end.' +'.($rs['between_interval']).' minutes'))."' BETWEEN r.time AND ADDTIME(TIME(r.time), SEC_TO_TIME(duration*60)) ) 
*/					   						
		
		$tables_qry = mysql_query($tables_sql) or die(mysql_error());

				
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
									AND (
	
											( '".$time_start."' BETWEEN ADDTIME( TIME(r.time), -SEC_TO_TIME((duration+".$rs['between_interval'].")*60) ) 
																	AND ADDTIME( TIME(r.time),  SEC_TO_TIME((duration+".$rs['between_interval'].")*60) ) )  
												OR 
											( '".$time_end."' BETWEEN ADDTIME( TIME(r.time), -SEC_TO_TIME((duration+".$rs['between_interval'].")*60) ) 
																  AND ADDTIME( TIME(r.time),  SEC_TO_TIME((duration+".$rs['between_interval'].")*60) ) )
								  		)
								  )
							   ";
				
				
						
				$tables_stats_qry = mysql_query($tables_stats_sql) or die(mysql_error());
				$tables_stats_res = mysql_fetch_assoc($tables_stats_qry);

				echo '<b>'.$tables_stats_res['seats'].'</b> seats out of <b>'.$tables_stats_res['tables'].'</b> tables.';
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
            </table>
            <?php	
		}
		else{
			echo "There are no available tables for the selected date (".date('d M Y',strtotime($currentDate)).").";
		}
		
	}
	else{
		echo "There's no schedule set for the date(".$currentDate.") you've selected.";
	}
	
?>