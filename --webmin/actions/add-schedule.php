<?php session_start();
	include '../config/config.php';

	$starttime = mysql_real_escape_string(strip_tags($_POST['starttime']));
	$endtime = mysql_real_escape_string(strip_tags($_POST['endtime']));
	$days = mysql_real_escape_string(strip_tags($_POST['days']));
	$startdate = mysql_real_escape_string(strip_tags($_POST['startdate']));
	$enddate = mysql_real_escape_string(strip_tags($_POST['enddate']));
	$id = mysql_real_escape_string(strip_tags($_POST['id']));
	
	mysql_query("insert into schedule(account_id, start_time, end_time, days, valid_from, valid_until) values(".$id.",'".$starttime."', '".$endtime."', '".$days."', '".$startdate."','".$enddate."')");
	
	mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Added Employee Schedule.',now(),'".get_client_ip()."')");
		
	
?>