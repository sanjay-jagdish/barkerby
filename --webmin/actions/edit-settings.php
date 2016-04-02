<?php session_start();
	include '../config/config.php';

	$name = mysql_real_escape_string(strip_tags($_POST['name']));
	$starttime = mysql_real_escape_string(strip_tags($_POST['starttime']));
	$endtime = mysql_real_escape_string(strip_tags($_POST['endtime']));
	$pax = mysql_real_escape_string(strip_tags($_POST['pax']));
	$days = mysql_real_escape_string(strip_tags($_POST['days']));
	$startdate = mysql_real_escape_string(strip_tags($_POST['startdate']));
	$enddate = mysql_real_escape_string(strip_tags($_POST['enddate']));
	$interval = mysql_real_escape_string(strip_tags($_POST['interval']));
	$id = mysql_real_escape_string(strip_tags($_POST['id']));
	$dineinterval = mysql_real_escape_string(strip_tags($_POST['dineinterval']));
	$betweeninterval = mysql_real_escape_string(strip_tags($_POST['betweeninterval']));
	$numseats = mysql_real_escape_string(strip_tags($_POST['numseats']));
	
	mysql_query("update restaurant_detail set name='".$name."', days='".$days."', start_time='".$starttime."', end_time='".$endtime."', start_date='".$startdate."', end_date='".$enddate."', time_interval=".$interval.", dine_interval=".$dineinterval.", between_interval=".$betweeninterval.", allowed_seats=".$numseats." where id=".$id);
	
	mysql_query("delete from table_detail where restaurant_detail_id=".$id);
	
	$maxpax=explode("*",$pax);
	
	for($i=0;$i<count($maxpax);$i++){
		
		$val=explode("^",$maxpax[$i]);
		
		mysql_query("insert into table_detail(restaurant_detail_id,table_name,max_pax) values(".$id.",'".$val[0]."',".$val[1].")");
	}
	
	mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Modified Restaurant Schedule.',now(),'".get_client_ip()."')");
		
	
?>