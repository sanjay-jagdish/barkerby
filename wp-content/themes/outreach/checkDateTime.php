<?php include 'config.php';

	$currentDates = $_POST['date'];
	$dayName=date('D', strtotime($currentDates));
	
	$currentTime=$_POST['hour'].''.$_POST['minute'];
	
	$q=mysql_query("select id from takeaway_settings where DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d') >= DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') and DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d') <= DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') and ".$currentTime.">=(replace(start_time,':','')) and ".$currentTime."<=(replace(end_time,':','')) and days like '%".$dayName."%' and deleted=0 order by id desc limit 1") or die(mysql_error());
	
	$rs=mysql_fetch_assoc($q);
	$currentID=$rs['id'];
	
	if(mysql_num_rows($q)==0){
		
		$qq=mysql_query("select *, date_format(STR_TO_DATE(start_date, '%m/%d/%Y'),'%d %M %Y') as startdate, date_format(STR_TO_DATE(end_date, '%m/%d/%Y'),'%d %M %Y') as enddate from takeaway_settings where days like '%".$dayName."%' and deleted=0 and DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d') >= DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d')") or die(mysql_error());
		
		$available = '';
		while($rr=mysql_fetch_assoc($qq)){
			$available.= "&#9679; ".$rr['startdate'].' - '.$rr['enddate'].' '.$rr['days'].' klockan '.$rr['start_time'].' - '.$rr['end_time'].'<br>';
		}
		
		echo 'Invalid*'.$available;
	}
	else{
		echo $currentID;
	}

?>