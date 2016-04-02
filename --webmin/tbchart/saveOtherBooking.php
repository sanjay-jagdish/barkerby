<?php
include '../config/config.php';

//$table_id= mysql_real_escape_string(strip_tags($_POST['table_id']));
$table_ids= explode(',',mysql_real_escape_string(strip_tags($_POST['table_id'])));
$date= mysql_real_escape_string(strip_tags($_POST['date_selected']));
$time= mysql_real_escape_string(strip_tags($_POST['time_start']));
$end= mysql_real_escape_string(strip_tags($_POST['time_end']));
$res_detail_id= mysql_real_escape_string(strip_tags($_POST['res_det_id']));
$guest= mysql_real_escape_string(strip_tags($_POST['pax']));
$notes= mysql_real_escape_string(strip_tags($_POST['notes']));
$fname= mysql_real_escape_string(strip_tags($_POST['first_name']));
$lname= mysql_real_escape_string(strip_tags($_POST['last_name']));
$phone= mysql_real_escape_string(strip_tags($_POST['phone_number']));
$email= mysql_real_escape_string(strip_tags($_POST['email_address']));
$street= mysql_real_escape_string(strip_tags($_POST['street']));
$city= mysql_real_escape_string(strip_tags($_POST['city']));
$zip= mysql_real_escape_string(strip_tags($_POST['zip']));
$country= mysql_real_escape_string(strip_tags($_POST['country']));


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

mysql_query("INSERT INTO account(type_id,fname,lname,phone_number,date_created,email,street_name,city,zip,country) values
			(5,'".$fname."', '".$lname."', '".$phone."', now(),'".$email."','".$street."','".$city."','".$zip."','".$country."')") 
			or die(mysql_error());

$account_id=mysql_insert_id();

mysql_query("insert into reservation(reservation_type_id, account_id, date, time, number_people, note, date_time, duration, approve) 
			 values(1, ".$account_id.", '".date('m/d/Y', strtotime($date))."', '".$time."', ".$guest.", '".$notes."', now(), ".$duration.", 2)") or die(mysql_error());

$reservation_id=mysql_insert_id();

//mysql_query("insert into reservation_table(reservation_id, table_detail_id) values(".$reservation_id.",".$table_id.")") or die(mysql_error());

foreach($table_ids as $key => $table_id){
	mysql_query("insert into reservation_table(reservation_id, table_detail_id) values(".$reservation_id.",".$table_id.")") or die(mysql_error());
}


echo $reservation_id;	
?>