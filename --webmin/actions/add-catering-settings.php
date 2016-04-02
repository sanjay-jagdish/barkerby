<?php session_start();
	include '../config/config.php';

	$starttime = mysql_real_escape_string(strip_tags($_POST['starttime']));
	$endtime = mysql_real_escape_string(strip_tags($_POST['endtime']));
	$days = mysql_real_escape_string(strip_tags($_POST['days']));
	$startdate = mysql_real_escape_string(strip_tags($_POST['startdate']));
	$enddate = mysql_real_escape_string(strip_tags($_POST['enddate']));
	$advance = mysql_real_escape_string(strip_tags($_POST['advance']));
	
	mysql_query("insert into catering_settings(days,start_time,end_time,start_date,end_date,minimum_number_days) values('".$days."', '".$starttime."', '".$endtime."','".$startdate."','".$enddate."',".$advance.")");
	
	
	mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Added a Catering Setting.',now(),'".get_client_ip()."')");
		
	
?>