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
	$dineinterval = mysql_real_escape_string(strip_tags($_POST['dineinterval']));
	$betweeninterval = mysql_real_escape_string(strip_tags($_POST['betweeninterval']));
	$numseats = mysql_real_escape_string(strip_tags($_POST['numseats']));
	
	mysql_query("insert into restaurant_detail(name,days,start_time,end_time,start_date,end_date,time_interval,dine_interval,between_interval, allowed_seats) values('".$name."','".$days."', '".$starttime."', '".$endtime."','".$startdate."','".$enddate."',".$interval.",".$dineinterval.",".$betweeninterval.",".$numseats.")");
	
	
	$q=mysql_query("select id from restaurant_detail where deleted=0 order by id desc limit 1");
	$r=mysql_fetch_assoc($q);
	
	$maxpax=explode("*",$pax);
	
	for($i=0;$i<count($maxpax);$i++){
		
		$val=explode("^",$maxpax[$i]);
		
		mysql_query("insert into table_detail(restaurant_detail_id,table_name,max_pax) values(".$r['id'].",'".$val[0]."',".$val[1].")");
	}
	
	mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Added Restaurant Schedule.',now(),'".get_client_ip()."')");
		
	
?>