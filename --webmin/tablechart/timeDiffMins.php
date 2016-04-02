<?php
$date_selected = strtotime($_POST['date_selected']);
$datetime1 = strtotime($_POST['start']);
$datetime2 = strtotime($_POST['end']);
$elapsed  = ($datetime2-$datetime1)/60;
//echo $_POST['start'].' to '.$_POST['end'].' == '.$elapsed;

$now = strtotime(date('H:i',strtotime('now')));

if( ($datetime1<=$datetime2 && $now<$datetime1 && $date_selected==strtotime(date('Y-m-d'))) || $date_selected>strtotime(date('Y-m-d')) ){
	$hrs = gmdate("H", ($elapsed * 60));
	$mins = gmdate("i", ($elapsed * 60));
	echo 'Hrs: <b>'.$hrs.'</b>&nbsp; Mins: <b>'.$mins.'</b>';
}else{
	echo '<font color="Red">Invalid Time</font>';
}

?>