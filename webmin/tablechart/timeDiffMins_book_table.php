                                <?php
$datetime1 = strtotime(substr($_POST['start'],0,5));
$datetime2 = strtotime(substr($_POST['end'],0,5));
$elapsed  = ($datetime2-$datetime1)/60;
$table_id = $_POST['table_id'];
$date_selected = $_POST['date_selected'];
$res_id = $_POST['res_id'];
//echo $_POST['start'].' to '.$_POST['end'].' == '.$elapsed;
require('../config/config.php');

$now = strtotime(date('H:i',strtotime('now')));

if($datetime1<$datetime2 && $now<$datetime1){
	$hrs = gmdate("H", ($elapsed * 60));
	$mins = gmdate("i", ($elapsed * 60));

//get restaurant details e.g. start/end time with regards to current date
$res_det_sql = "SELECT id, start_time, end_time, time_interval, dine_interval, between_interval FROM restaurant_detail 
				WHERE id=".$res_id."
				AND deleted=0";

$res_det_qry = mysql_query($res_det_sql);
$res_det = mysql_fetch_assoc($res_det_qry);

	//double-check if table is available for the selected time until the set dine_interval less and plus between_interval
/*
	$table_check_sql = "SELECT r.id FROM reservation r, reservation_table rt 
						WHERE r.id=rt.reservation_id AND r.status<3 AND r.deleted=0 AND rt.deleted=0 AND rt.table_detail_id=".$table_id." AND
						STR_TO_DATE(r.date,'%m/%d/%Y')='".$date_selected."' 
						AND 
						(
							r.time BETWEEN '".date('H:i',strtotime(date('H:i',$datetime1).' -'.($res_det['between_interval']-1).' minutes')).":00' 
										AND '".date('H:i',strtotime(date('H:i',$datetime2).' +'.($res_det['between_interval']-1).' minutes')).":00' 	
										
							OR
							
							ADDTIME(TIME(r.time), SEC_TO_TIME(duration*60)) BETWEEN '".date('H:i',strtotime(date('H:i',$datetime1).' -'.($res_det['between_interval']-1).' minutes')).":00' 
										AND '".date('H:i',strtotime(date('H:i',$datetime2).' +'.($res_det['between_interval']-1).' minutes')).":00' 						)";

*/

		$table_check_sql = "SELECT r.id FROM reservation r, reservation_table rt 
						WHERE r.id=rt.reservation_id AND r.status<3 AND r.deleted=0 AND rt.deleted=0 AND rt.table_detail_id=".$table_id." AND
						STR_TO_DATE(r.date,'%m/%d/%Y')='".$date_selected."' 
						AND 
						(
							r.time BETWEEN '".date('H:i',strtotime(date('H:i',strtotime(substr($_POST['start'],0,5))).' -'.$res_det['between_interval'].' minutes')).":00' 
										AND '".date('H:i',strtotime(date('H:i',strtotime(substr($_POST['end'],0,5))).' +'.($res_det['between_interval']-1).' minutes')).":00' 	
										
							OR
							
							ADDTIME(TIME(r.time), SEC_TO_TIME(duration*60)) BETWEEN '".date('H:i',strtotime(date('H:i',strtotime(substr($_POST['start'],0,5))).' -'.($res_det['between_interval']-1).' minutes')).":00' 
										AND '".date('H:i',strtotime(date('H:i',strtotime(substr($_POST['end'],0,5))).'+'.($res_det['between_interval']-1).' minutes')).":00' 
						)";



//echo $table_check_sql.'<br><br>';

	$table_check_num = mysql_num_rows(mysql_query($table_check_sql));
	if($table_check_num<=0)
		echo 'Hrs: <strong>'.$hrs.'</strong> &nbsp;Mins: <strong>'.$mins.'</strong><input id="time_error" value="0" type="hidden" />';		
	else
		echo '<font color="red">Selected table is not available<br>up to the end time set.</font><input id="time_error" value="1" type="hidden" />';

}else{
	echo '<font color="Red">Invalid Time</font>';
}

?>
                            