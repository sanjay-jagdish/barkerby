<?php session_start();
	include '../config/config.php';

	$starttime = mysql_real_escape_string(strip_tags($_POST['starttime']));
	$endtime = mysql_real_escape_string(strip_tags($_POST['endtime']));
	$days = mysql_real_escape_string(strip_tags($_POST['days']));
	$startdate = mysql_real_escape_string(strip_tags($_POST['startdate']));
	$enddate = mysql_real_escape_string(strip_tags($_POST['enddate']));
	$advance = mysql_real_escape_string(strip_tags($_POST['advance']));
	$id = mysql_real_escape_string(strip_tags($_POST['id']));
	
	mysql_query("update catering_settings set start_time='".$starttime."', end_time='".$endtime."', days='".$days."', start_date='".$startdate."', end_date='".$enddate."', minimum_number_days=".$advance." where id=".$id);
	
	
	mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Updated a Catering Setting.',now(),'".get_client_ip()."')");
		
	
?>