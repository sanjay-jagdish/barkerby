<?php include 'config.php';

	$currentDates = date("m/d/Y");
	$dayName=date('D', strtotime($currentDates));
	
	//get the current time
	$t=time();
	$currentTime=date("Hi",$t);
	
	$q=mysql_query("select id from takeaway_settings where DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d') >= DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') and DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d') <= DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') and ".$currentTime.">=(replace(start_time,':','')) and ".$currentTime."<=(replace(end_time,':','')) and days like '%".$dayName."%' and deleted=0 order by id desc limit 1");
	
	$rs=mysql_fetch_assoc($q);
	$currentID=$rs['id'];
	
	
	//get the available days...
	$thedays='';
	$qr=mysql_query("select days, end_date, start_time, end_time, start_date from takeaway_settings where id=".$currentID);
	$rq=mysql_fetch_assoc($qr);
	
	$days=explode(',',$rq['days']);
	
	$theend_date = $rq['end_date'];	
	$thestart_date = $rq['start_date'];	
	$current_starttime = $rq['start_time'];	
	$current_endtime = $rq['end_time'];	
	
	$daynum=array('Mon'=>1,'Tue'=>2,'Wed'=>3,'Thu'=>4,'Fri'=>5,'Sat'=>6,'Sun'=>0);
	
	for($i=0;$i<count($days);$i++){
		$thedays.="'".$daynum[trim($days[$i])]."',";
	}
	
	$thedays=substr($thedays,0,strlen($thedays)-1);
	
	echo $thedays;

?>