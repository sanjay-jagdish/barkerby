<?php

include 'config.php';	

$dayName = date('D', strtotime($_POST['datetime']));

$date = date('Y-m-d', strtotime($_POST['datetime']));
$currentDate = date('Y-m-d');

$query = "select id, start_time as starttime,end_time as endtime, advance_time from ".$_POST['tablename']." where days like '%".$dayName."%' and deleted=0 limit 1";
$rs = mysql_fetch_assoc( mysql_query( $query ) );
$times = array('can_order' => 0);

if( $rs )
{
	$opening_time = $rs['starttime'];
	$closing_time = $rs['endtime'];
	$advance_time = $rs['advance_time'];
	$current_time = date('H:i');

	$current_time = date('H:i', strtotime('+1 minutes'));

	$order_start_time = $opening_time;
	$order_end_time = $closing_time;

	// Validation for current date
	if( $date == $currentDate ) 
	{
		$datetime = new DateTime(date('Y-m-d').' '.$opening_time);
		$newdatetime = $datetime->sub(new DateInterval("P0Y0DT0H".$advance_time."M"));
		if(strtotime($current_time) <= strtotime($newdatetime->format('H:i'))) {
			$order_start_time = $opening_time;
		} else {
			$timeToAdd = strtotime($current_time) + $advance_time*60;
			$order_start_time = date('H:i', $timeToAdd);
		}

		$order_start_time = roundToNextQuarter($order_start_time);
		if(strtotime($order_start_time) <= strtotime($closing_time)) {
			$times['can_order'] = 1;
		}
	} else {
		$times['can_order'] = 1;
	}
	
	$times = array_merge($times, array(
		'opening_time' => $opening_time,
		'closing_time' => $closing_time,
		'order_start_time' => $order_start_time,
		'order_end_time' => $order_end_time,
		'current_time' => $current_time
	));
}

echo json_encode($times);

function roundToNextQuarter($time)
{
	$date = new DateTime(date('Y-m-d').' '.$time);
	$minutes = $date->format("i");
	$hour = $date->format("H");
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
		$time = $hour.':'.$minutes;
	} else {
		$time = $hour.':'.$minutes;
	}

	return $time;
}

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