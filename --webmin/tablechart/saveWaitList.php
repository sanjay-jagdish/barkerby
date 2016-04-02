<?php
include '../config/config.php';

$table_ids= mysql_real_escape_string(strip_tags($_POST['table_id']));
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

mysql_query("insert into wait_list(date_time, account_id, date, time, number_people, tables, notes, duration, entered_by) 
			 values(NOW(), ".$customer_id.", '".date('m/d/Y', strtotime($date))."', '".$time."', ".$guest.", '".$table_ids."', '".$notes."',  ".$duration.", ".$_SESSION['login']['id'].")")
		 or die(mysql_error());

$waitlist_id=mysql_insert_id();

echo $waitlist_id;	
?>