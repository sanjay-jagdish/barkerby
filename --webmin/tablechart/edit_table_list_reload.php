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
	$res_id = $_POST['res_id'];

	//retreive reservation detail
	$res_detail_sql = "SELECT * FROM reservation WHERE id=".$res_id;
	$res_detail_qry = mysql_query($res_detail_sql);
	$res_detail_res = mysql_fetch_assoc($res_detail_qry);

	$currentDate=date('Y-m-d',strtotime($res_detail_res['date']));
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
		$q=mysql_query("select id, start_time, end_time, time_interval, dine_interval, between_interval from restaurant_detail 
						where '".$currentDate."' >= STR_TO_DATE(start_date, '%m/%d/%Y') and '".$currentDate."' <= STR_TO_DATE(end_date, '%m/%d/%Y') and days like '%".$dayName."%' order by id desc limit 1") 
					or die(mysql_error());
		$rs=mysql_fetch_assoc($q);	
		
			
		$seats_tables_sql = "SELECT r.id AS rtid, rt.table_detail_id AS id, td.max_pax, td.table_name  
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
			$reserved_seats=0; $reserved_tables=0;
			while($seats_tables_res = mysql_fetch_assoc($seats_tables_qry)){
			
				//double_check if previously reserved table is still available with the new time
				$table_check_sql = "SELECT rt.id FROM reservation_table rt, reservation r 
									WHERE rt.reservation_id=r.id AND AND r.deleted=0 AND r.status<3 AND r.id<>".$seats_tables_res['rtid']." AND rt.table_detail_id=".$seats_tables_res['id']." 
										AND STR_TO_DATE(r.date, '%m/%d/%Y')='".date('Y-m-d',strtotime($res_detail_res['date']))."' AND (TIME(r.time)>=TIME('".$time_start."') 
												OR 
											ADDTIME(TIME(r.time), SEC_TO_TIME(r.duration*60))<=TIME('".$time_end."'))";
				$table_check_qry = mysql_query($table_check_sql);
				$table_check_num = mysql_num_rows($table_check_qry);
				
				//echo $table_check_sql.'<br>';
				
				if($table_check_num==0){
				?>
					<tr>
						<td align="center"><?php echo $seats_tables_res['max_pax']; ?></td>
						<td align="center"><input type="checkbox" data-rel="<?php echo $seats_tables_res['max_pax']; ?>" id="<?php echo $table_det['id']; ?>" 
											class="table_option" name="tables[]" value="<?php echo $seats_tables_res['id']; ?>" checked="checked" /></td>
						<td align="center" nowrap="nowrap"><?php echo $seats_tables_res['table_name']; ?></td>
					</tr>                
				<?php
					$reserved_seats += $seats_tables_res['max_pax'];
					$reserved_tables++;			
				}
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
	
											( '".$time_start."' BETWEEN ADDTIME( TIME(r.time), -SEC_TO_TIME((duration+".$rs['between_interval'].")*60) ) 
																	AND ADDTIME( TIME(r.time),  SEC_TO_TIME((duration+".$rs['between_interval'].")*60) ) )  
												OR 
											( '".$time_end."' BETWEEN ADDTIME( TIME(r.time), -SEC_TO_TIME((duration+".$rs['between_interval'].")*60) ) 
																  AND ADDTIME( TIME(r.time),  SEC_TO_TIME((duration+".$rs['between_interval'].")*60) ) )
								  		)
								  )
							ORDER BY td.max_pax ASC";
															
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
                <script>
					jQuery('#selected_seats').html('<?php echo $reserved_seats; ?>'); 
					jQuery('#selected_tables').html('<?php echo $reserved_tables; ?>'); 
					jQuery('#seats_difference').html(''); 

				</script>
                
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
