<?php
require('../config/config.php');

$date_selected = $_POST['date'];
//get restaurant details e.g. start/end time with regards to current date
$res_det_sql = "SELECT id, start_time, end_time, time_interval FROM restaurant_detail 
				WHERE '".$date_selected."' BETWEEN STR_TO_DATE(start_date,'%m/%d/%Y') AND STR_TO_DATE(end_date,'%m/%d/%Y') 
				AND deleted=0 
				ORDER BY id DESC LIMIT 1";
$res_det_qry = mysql_query($res_det_sql);
$res_det_num = mysql_num_rows($res_det_qry);
$res_det = mysql_fetch_assoc($res_det_qry);

if($res_det_num ==1){	
	$store_hrs['open']  = $res_det['start_time'];
	$store_hrs['close'] = $res_det['end_time'];
	
	$time_increments = $res_det['time_interval'];

	$max_tables_sql = "SELECT COUNT(id) AS max_tables FROM table_detail WHERE restaurant_detail_id=".$res_det['id'];
	$max_tables_qry = mysql_query($max_tables_sql);
	$max_tables_res = mysql_fetch_assoc($max_tables_qry);
	$max_table = $max_tables_res['max_tables'];

?>
<strong><u>Operation Details</u></strong><br><br>
Date Selected: <b><?php echo date('D m/d/Y',strtotime($date_selected)); ?></b><br>
Open Time: <b><?php echo $res_det['start_time']; ?></b><br>
Close Time: <b><?php echo $res_det['end_time']; ?></b><br>
Interval: <b><?php echo $res_det['time_interval']; ?> mins.</b><br>
Max Tables: <strong><?php echo $max_table; ?></strong>
<?php
}else{
	echo '<br><br><br><br><font color="red">There is no open/close times available for '.date('D m/d/Y',strtotime($date_selected)).'.</font>';
}
?>
