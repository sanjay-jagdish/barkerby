<?php session_start();
	include '../config/config.php';

	$starttime = mysql_real_escape_string(strip_tags($_POST['starttime']));
	$endtime = mysql_real_escape_string(strip_tags($_POST['endtime']));
	$days = mysql_real_escape_string(strip_tags($_POST['days']));
	$startdate = mysql_real_escape_string(strip_tags($_POST['startdate']));
	$enddate = mysql_real_escape_string(strip_tags($_POST['enddate']));
	$id = mysql_real_escape_string(strip_tags($_POST['id']));
	$schedid = mysql_real_escape_string(strip_tags($_POST['schedid']));
	
	mysql_query("update schedule set account_id=".$id.", start_time='".$starttime."', end_time='".$endtime."', days='".$days."', valid_from='".$startdate."', valid_until='".$enddate."' where id=".$schedid);
	
	mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Modified Employee Schedule.',now(),'".get_client_ip()."')");
		
	
?>