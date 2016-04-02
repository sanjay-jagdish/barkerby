<?php 
require('../config/config.php');
ini_set('display_errors',0);

$date_selected = '2014-01-30';//$_POST['date'];
//get restaurant details e.g. start/end time with regards to current date
$res_det_sql = "SELECT id, start_time, end_time, time_interval FROM restaurant_detail 
				WHERE '".$date_selected."' BETWEEN STR_TO_DATE(start_date,'%m/%d/%Y') AND STR_TO_DATE(end_date,'%m/%d/%Y') 
				AND deleted=0 
				ORDER BY id DESC LIMIT 1";
$res_det_qry = mysql_query($res_det_sql);
$res_det_num = mysql_num_rows($res_det_qry);
$res_det = mysql_fetch_assoc($res_det_qry);


if($res_det_num==1){

	$store_hrs['open']  = $res_det['start_time'];
	$store_hrs['close'] = $res_det['end_time'];
	
	$time_increments = $res_det['time_interval'];

	$max_tables_sql = "SELECT COUNT(id) AS max_tables FROM table_detail WHERE restaurant_detail_id=".$res_det['id'];
	$max_tables_qry = mysql_query($max_tables_sql);
	$max_tables_res = mysql_fetch_assoc($max_tables_qry);
	$max_table = $max_tables_res['max_tables'];
?>

<p class="loading alert">Loading table...</p>

<div class="table-container">
	<table cellpadding="0" cellspacing="0" border="1" class="table table-condensed avails-table table-bordered">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
			<?php 
			$time = strtotime($store_hrs['open']);
			$end = strtotime($store_hrs['close']);
			
			while($time<=$end){
				?>
				<th style="width:70px;"><?php echo date('H:i',$time); ?></th>
				<?php
				$time = strtotime(date('H:i:s',$time).' + '.$time_increments.' mins');
			}
			?>	
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$length = strlen($max_table);
			
			$tables_sql = "SELECT id, table_name, max_pax FROM table_detail	WHERE restaurant_detail_id=".$res_det['id']." ORDER BY id ASC";
			$tables_qry = mysql_query($tables_sql);
			while($tables = mysql_fetch_assoc($tables_qry)){
			?>
			<tr>
				<td align="center"><?php echo $tables['table_name']; ?></td>
				<td class="first_time">&nbsp;</td>
			<?php
			$time = strtotime($store_hrs['open']);
			$end = strtotime($store_hrs['close']);
			
			$time_count=1;
			while($time<=$end){
				
				$table_block = '';
				if($time==strtotime('10:45:00') && $tbl_num == 6){
					$duration = 120;
					$block_ends = strtotime('10:45:00 + '.$duration.' mins');
					$time_blocks_width = $time_interval_width + ($duration/$time_increments) * $time_interval_width - $time_block_left_padding;
					$table_block = '<div class="table_block" style="width:'.$time_blocks_width.'px;" 
										title="Details of Table Resrvation Here" id="tbl6">
										Tbl#'.str_pad($tbl_num,$length,'0', STR_PAD_LEFT).' 10:45&rarr;'.date('H:i',$block_ends).' ['.$duration.'mins]
									</div>';
				}


				if($time==strtotime('12:00:00') && $tbl_num == 14){
					$duration = 45;
					$block_ends = strtotime('12:00:00 + '.$duration.' mins');
					$time_blocks_width = $time_interval_width + ($duration/$time_increments) * $time_interval_width - $time_block_left_padding;
					$table_block = '<div class="table_block" style="width:'.$time_blocks_width.'px;" 
										title="Details of Table Resrvation Here">
										Tbl#'.str_pad($tbl_num,$length,'0', STR_PAD_LEFT).' 12:00&rarr;'.date('H:i',$block_ends).' ['.$duration.'mins]
									</div>';
				}


				if($time==strtotime('08:30:00') && $tbl_num == 16){
					$duration = 45;
					$block_ends = strtotime('08:30:00 + '.$duration.' mins');
					$time_blocks_width = $time_interval_width + ($duration/$time_increments) * $time_interval_width - $time_block_left_padding;
					$table_block = '<div class="table_block" style="width:'.$time_blocks_width.'px;" 
										title="Details of Table Resrvation Here">
										Tbl#'.str_pad($tbl_num,$length,'0', STR_PAD_LEFT).' 08:30&rarr;'.date('H:i',$block_ends).' ['.$duration.'mins]
									</div>';
				}
				?>
				<td class="space_time" title="Tbl#<?php echo str_pad($tbl_num,$length,'0', STR_PAD_LEFT).' '.date('H:i',$time); ?>"><?php echo $table_block; ?></td>
				<?php
				$time = strtotime(date('H:i:s',$time).' + '.$time_increments.' mins');
				$time_count++;
			}
			?>	
				<td class="last_time">&nbsp;</td>
			</tr>	
			<?php	
			}
			?>
		</tbody>
	</table>
</div>
<?php
}else{
	echo '<div style="clear: both;"><br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="Red">There is no open/close times available for the selected date <b>'.date('d M Y',strtotime($date_selected)).'</b>.</font></div>';
}
?>