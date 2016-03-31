<?php
	include 'config.php';

	function GetAsapStatus($tablename){ 	
		
	$currentDates = date("m/d/Y");
	$dayName=date('D', strtotime($currentDates));
	//get the current time
	$t=time();
	$currentTime=date("Hi",$t);
	$q=mysql_query("select id, if(".$currentTime.">(replace(end_time,':','')), (replace(end_time,':',''))+2400, (replace(end_time,':',''))) as theendtime,advance_time from ".$tablename." where DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d') >= DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') and DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d') <= DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') and ".$currentTime.">=(replace(start_time,':','')) and ".$currentTime."<=if(".$currentTime.">(replace(end_time-advance_time,':','')), (replace(end_time,':',''))+2400, (replace(end_time,':',''))) and days like '%".$dayName."%' and deleted=0 limit 1") or die(mysql_error());
	$rs=mysql_fetch_assoc($q);
	$advancetime=$rs['advance_time'];
	$endTime = strtotime($rs['theendtime']);
	$newtm = $t+($advancetime*60);

	if($endTime  >= $newtm){
		  return 1;
		}else{
			return 0;
		}
   }
