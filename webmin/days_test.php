<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Days of the Week Selection</title>
</head>

<body>
<?php
$days = array(1=>'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
$day_option = array();

$start_date = explode('/',$_POST['start']);
$end_date = explode('/',$_POST['end']);

if( checkdate($start_date[0], $start_date[1], $start_date[2])==TRUE && checkdate($end_date[0], $end_date[1], $end_date[2])==TRUE ){
	
	$start = strtotime(trim($_POST['start']));
	$end = strtotime(trim($_POST['end']));

	while( $start <= $end ){
		
		if(!in_array(date('N',$start),$day_option)){
			$day_option[] = date('N', $start);
			$results .= '<br>'.date('M j, Y',$start).' is a '.date('l',$start);
		}
		
		$start = strtotime( date('Y-m-d', $start).' +1 day' );
	}

}

//if(count($day_option)<=0){
//	$day_option = range(1,7);
//}

?>
<style>
	.days_option{ display:inline-block; }
	.strike_out{ color: #999; }
</style>
<form method="post">
    Start Date: <input type="text" name="start" placeholder="mm/dd/yyyy" value="<?php echo trim($_POST['start']); ?>" /><br />
    End Date: <input type="text" name="end" placeholder="mm/dd/yyyy" value="<?php echo trim($_POST['end']); ?>" />
    <input type="submit" value=" GO " />
	<br /><br />
	<?php	
	foreach($days as $d => $day_txt){
		$option = 'disabled';
		$div_class = 'days_option strike_out';
		if(in_array($d,$day_option)){ 
			$option = 'checked="checked"';
			$div_class = 'days_option';
		}
		echo '<div class="'.$div_class.'"><input type="checkbox" name="days[]" value="'.$d.'" '.$option.' /> '.$day_txt.'&nbsp;&nbsp;</div>';
	}
	?>

</form>
<?php
echo $results;
?>
</body>
</html>