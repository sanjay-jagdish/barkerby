<?php
	include 'config.php';

	$tablename = $_POST['table'];
	$calendarDate = $_POST['date'];
	$currentDates = $_POST['curDate'];
	$hour = $_POST['hour'];
	$mins = $_POST['mins'];
	$isToday = $_POST['isToday'];
	$dayname = $_POST['dayname']-1;
	$thour = $_POST['thour'];
	
	$daystr=array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
	$thedayToday =  $daystr[$dayname];
	
	/*$daynum=array('Mon'=>1,'Tue'=>2,'Wed'=>3,'Thu'=>4,'Fri'=>5,'Sat'=>6,'Sun'=>0);
	$days_sql = "select days from ".$tablename." where deleted=0";
	$qdate=mysql_query($days_sql) or die(mysql_error());
	$availableDays = '';
	while($rdate = mysql_fetch_assoc($qdate)){
		$av_dayz=explode(',',$rdate['days']);
		for($i=0;$i<count($av_dayz);$i++){
			$availableDays .=$daynum[trim($av_dayz[$i])].",";
		}
	}
	$availableDays = $availableDays=substr($availableDays,0,strlen($availableDays)-1);
	$array_days = explode(',',$availableDays);
	$av_unique = array_unique($array_days);
	$availableDays = implode(',',$av_unique);*/
	
	//echo $availableDays;
	
	$timestamp = strtotime($calendarDate);
	$day = date('D', $timestamp);
	
	$nowTime = $hour.':'.$mins;
	$thour = $thour.'00';
	if($isToday == 1){
		$where = " and ((CAST(start_time As Time) <= CAST('$nowTime' As Time) and CAST(end_time As Time) >= CAST('$nowTime' As Time)) or (CAST(start_time As Time))+(CAST('1:00' As Time)) >= (CAST('$thour' As Time))) ";
	}
	
	
	$query = "Select start_time,end_time,advance_time from $tablename 
	where deleted = 0 
	and DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') <= '$calendarDate'
	and DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') >= '$calendarDate'
	and days like '%$day%'
	$where
	order by start_time limit 1";
	
	//echo $query;
	$sql = mysql_query($query) or die(mysql_error());
	
	//echo $query;
	
	//echo 'rows: '.mysql_num_rows($sql).' ';
	while($row = mysql_fetch_assoc($sql)){
		echo $row['start_time'].'='.$row['end_time'].'='.$row['advance_time'];	
	}