<?php
	
include 'config.php';	

$dayName=date('D', strtotime($_POST['datetime']));

$date = date('Y-m-d', strtotime($_POST['datetime']));
$currentDate = date('Y-m-d');

$q=mysql_query("select id, start_time as starttime,end_time as endtime, advance_time from ".$_POST['tablename']." where days like '%".$dayName."%' and deleted=0 limit 1") or die(mysql_error());
$rs=mysql_fetch_assoc($q);
$advancetime=$rs['advance_time'];
$selected_start_time = $rs['starttime'];
$selected_end_time = $rs['endtime'];
// Validation for current date
if( $date == $currentDate ) 
{
	$start_time = $rs['starttime'];
	$current_time = date('H:i');
	$selected_start_time = strtotime($start_time) > strtotime($current_time) ? $start_time : $current_time;

	$date = new DateTime(date('Y-m-d').' '.$selected_start_time);
	$newdate = $date->add(new DateInterval("P0Y0DT0H".$advancetime."M"));
	$minutes = $newdate->format("i");
	$hour = $newdate->format("H");
	if( $minutes%15!=0 )
	{
		if( $minutes<15 ) {
			$minutes = 15;
		} else if( $minutes<30 ) {
			$minutes = 30;
		} else if( $minutes<45 ) {
			$minutes = 45;
		} else {
			$minutes = '00';
			$hour = $hour + 1;
		}
		$selected_start_time = $hour.':'.$minutes;
	} else {
		$selected_start_time = $hour.':'.$minutes;
	}
$selected_end_time = strtotime($rs['endtime']) >= strtotime($current_time) ? $rs['endtime'] : '';	
}
echo json_encode(array($selected_start_time, $selected_end_time, $rs['starttime']));

function makeTimesArray($time1, $time2)
{
	$date = date('Y-m-d');
	$datetime1 = $date.' '.$time1;
	$datetime2 = $date.' '.$time2;
	
	$date = new DateTime($datetime1);
	$count = 24 * 60 / 15;
	$loop = strtotime($datetime1) < strtotime($datetime2) ? true : false;
	$arr = array($time1); 
	while($loop == true) 
	{
		$temp = $date->add(new DateInterval("P0Y0DT0H60M"))->format("Y-m-d H:i");
		$arr[] = date('H:i', strtotime($temp));
		if(strtotime($temp) == strtotime($datetime2)){
			$loop = false;
		}
	}
	
	return $arr;
}
