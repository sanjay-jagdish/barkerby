<?php session_start();
	include '../config/config.php';

	$currentDate = date("m/d/Y");
	$dayName=date('D', strtotime($currentDate));

	//get the current time
	$t=time();
	$currentTime=date("Hi",$t);

	//$q=mysql_query("select id from takeaway_settings where DATE_FORMAT(STR_TO_DATE('".$currentDate."', '%m/%d/%Y'), '%Y-%m-%d') >= DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') and DATE_FORMAT(STR_TO_DATE('".$currentDate."', '%m/%d/%Y'), '%Y-%m-%d') <= DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') and days like '%".$dayName."%' and deleted=0 order by id desc limit 1") or die(mysql_error());
	
	$q=mysql_query("select id from takeaway_settings where DATE_FORMAT(STR_TO_DATE('".$currentDate."', '%m/%d/%Y'), '%Y-%m-%d') >= DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') and DATE_FORMAT(STR_TO_DATE('".$currentDate."', '%m/%d/%Y'), '%Y-%m-%d') <= DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') and days like '%".$dayName."%' and deleted=0 and ".$currentTime.">=(replace(start_time,':','')) and ".$currentTime."<=(replace(end_time,':','')) order by id desc limit 1") or die(mysql_error());

	$rs=mysql_fetch_assoc($q);
	echo $rs['id']; //the current schedule ID

?>