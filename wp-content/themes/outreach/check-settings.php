<?php
	include 'config.php';

	$tablename = $_POST['tablename'];
	
	$q=mysql_query("select var_value from settings where var_name='week_starts'");
	$row=mysql_fetch_assoc($q);
	
	$currentDates = date("m/d/Y");
	$dayName=date('D', strtotime($currentDates));
	
	//get the current time
	$t=time();
	$currentTime=date("Hi",$t);
	
	$q=mysql_query("select id, if(".$currentTime.">(replace(end_time,':','')), (replace(end_time,':',''))+2400, (replace(end_time,':',''))) as theendtime from ".$tablename." where DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d') >= DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') and DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d') <= DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') and ".$currentTime.">=(replace(start_time,':','')) and ".$currentTime."<=if(".$currentTime.">(replace(end_time,':','')), (replace(end_time,':',''))+2400, (replace(end_time,':',''))) and days like '%".$dayName."%' and deleted=0 limit 1") or die(mysql_error());
	
	$rs=mysql_fetch_assoc($q);
	$currentID=$rs['id'];
	
	
	//get the available days...
	$thedays='';
	$qr=mysql_query("select days, end_date, start_time, end_time, start_date, date_format(STR_TO_DATE(start_date, '%m/%d/%Y'),'%d %M %Y') as formatted_startdate, date_format(STR_TO_DATE(end_date, '%m/%d/%Y'),'%d %M %Y') as formatted_enddate, advance_time as advancetime  from ".$tablename." where id=".$currentID);
	$rq=mysql_fetch_assoc($qr);
	
	$days=explode(',',$rq['days']);
	
	$theend_date = $rq['end_date'];	
	$thestart_date = $rq['start_date'];	
	$current_starttime = $rq['start_time'];	
	$current_endtime = $rq['end_time'];
	$theactivedays = $rq['days'];
	$formatted_startdate = $rq['formatted_startdate'];
	$formatted_enddate = $rq['formatted_enddate'];
	$advancetime = $rq['advancetime'];
	
	$daynum=array('Mon'=>1,'Tue'=>2,'Wed'=>3,'Thu'=>4,'Fri'=>5,'Sat'=>6,'Sun'=>0);
	
	for($i=0;$i<count($days);$i++){
		$thedays.="'".$daynum[trim($days[$i])]."',";
		//$availableDays .=$daynum[trim($days[$i])].",";
	}
	//echo 'Active Days';
	//$availableDays = $availableDays=substr($availableDays,0,strlen($availableDays)-1);
	
	$thedays=substr($thedays,0,strlen($thedays)-1);
	
	
	$themonth = date("m");
	$theday = date("d");
	$theyear = date("Y");
	$thehour = date("H");
	$themin = date("i");
	
	
	//Start getting available days from takeaway-settings c/o jk
	
	
	if($sql_count>1){
	$query = "select days from ".$tablename." where deleted=0 and end_date >=date_format(STR_TO_DATE('".$currentDates."', '%Y-%m-%d'),'%m/%d/%Y')";
	}else{
		$query = "select days from ".$tablename." where deleted=0";
	}
	
	//ako sa giusob jeck :D
	
	//$query = "select days from ".$tablename." where id='".$currentID."'";
	
	$qdate=mysql_query($query) or die(mysql_error());
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
	$availableDays = implode(',',$av_unique);
	
	$sql_counts = mysql_query("SELECT id FROM ".$tablename." where deleted = 0");
	//$sql_count = mysql_fetch_assoc($sql_count);
	$sql_count = mysql_num_rows($sql_counts);
	
	$nowTime = date("H:i");
	$hour = date("H");
	$mins = date("i");
	
	if($sql_count>1){
		
		$maxDate=mysql_query("select min(DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d')) as stdate, max(end_date) as date, max(DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d')) as endtime, min(start_time) as sttime, DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d') as curdate,advance_time as advancetime, if(DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') < DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d'),1,0) as checker from ".$tablename." where deleted = 0 and DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') >=DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d')") or die(mysql_error());
	
	}else{
		
		$maxDate=mysql_query("select DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') as stdate, DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') as date, end_time  as endtime, start_time  as sttime, DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d') as curdate,advance_time as advancetime, if(DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') < DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d'),1,0) as checker from ".$tablename." where deleted = 0") or die(mysql_error());
		
	}
	
	
	$noSettings = mysql_num_rows($maxDate);
	
	$maxDate = mysql_fetch_assoc($maxDate);
	
	$advancetime = $maxDate['advancetime'];
	$checker = $maxDate['checker'];
	$strtdate= $maxDate['stdate'];
	
	$endDate = $maxDate['date'];
	$today = date("Y-m-d");
	
	$endTime = $maxDate['endtime'];
	$endH = explode(':',$endTime);
	$endHour = $endH[0];
	$endMinutes = $endH[1];
	
	if($endDate == $today){
		
		$ttempmins = 0;
		$thour = $hour;
		if($advance>=60){
			$thour=$hour+1;
		}else{
			$ttempmins = $mins+$advance;
			if($ttempmins >=60){
				$ttempmins = $ttempmins-60;
				$thour=$hour+1;
			}
		}
		//echo ''.$thour.'=='.$endHour.' && '.$endMinutes.' > '.$ttempmins;
		if($thour==$endHour && $endMinutes > $ttempmins){
			echo 0;
		}else{
			if($thour<$endHour){
				echo 0;
			}else{
				echo 1;
			}
		}
	}else{
		if($checker==1){
			$noSettings=0;
		}
		  
		if($noSettings>0){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	
?>