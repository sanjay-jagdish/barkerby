<?php
include '../config/config.php';

$res_id = mysql_real_escape_string(trim($_POST['res_det_id']));
$table_ids= explode(',',mysql_real_escape_string(strip_tags($_POST['table_id'])));
$date= mysql_real_escape_string(strip_tags($_POST['date_selected']));
$time= mysql_real_escape_string(strip_tags($_POST['time_start']));
$end= mysql_real_escape_string(strip_tags($_POST['time_end']));
$res_detail_id= mysql_real_escape_string(strip_tags($_POST['res_det_id']));
$guest= mysql_real_escape_string(strip_tags($_POST['pax']));
$notes= mysql_real_escape_string(strip_tags($_POST['notes']));
$customer_id= mysql_real_escape_string(strip_tags($_POST['customer_id']));

function totalTimeDiffMins($start, $end){
// returns difference between two 
// mysql timestamps as minutes

    $unix_timestamp1 = strtotime($end);
    $unix_timestamp2 = strtotime($start);

    // get difference between the two in seconds
    $time_period = ($unix_timestamp1 - $unix_timestamp2);
    
    if($time_period > 0){
        $time_in_mins = ($time_period / 60);
        return $time_in_mins;
    }else{
        return "DATE ERROR";
    }
} 

$duration = totalTimeDiffMins($time, $end) + 0;

mysql_query("UPDATE reservation SET 
			 time='".$time."', number_people=".$guest.", note='".$notes."', duration=".$duration." 
			 WHERE id=".$res_id) or die(mysql_error());

//fetch all tables on this reservation and mark deleted those not found in the submitted array of tables
$res_tables_sql = "SELECT table_detail_id FROM reservation_table WHERE deleted=0 AND reservation_id=".$res_id;
$res_tables_qry = mysql_query($res_tables_sql);
while($res_tables_res = mysql_fetch_assoc($res_tables_qry)){
	//if fetched table id is not in the submitted array of tables; mark as DELETED
	if(!in_array($res_tables_res['table_detail_id'],$table_ids)){
		mysql_query('UPDATE reservation_table SET deleted=1, del_time=NOW() WHERE reservation_id='.$res_id.' AND table_detail_id='.$res_tables_res['table_detail_id']);
	}
}

foreach($table_ids as $key => $table_id){
	//only insert/add table id if not existing
	$res_tables_sql = "SELECT id FROM reservation_table WHERE deleted=0 AND reservation_id=".$res_id." AND table_detail_id=".$table_id;
	$res_tables_qry = mysql_query($res_tables_sql) or die($res_tables_sql);
	$res_tables_num = mysql_num_rows($res_tables_qry);
	if($res_tables_num==0){
		mysql_query("insert into reservation_table(reservation_id, table_detail_id) values(".$res_id.",".$table_id.")") or die(mysql_error());
	}
}

echo $res_id;	
?>