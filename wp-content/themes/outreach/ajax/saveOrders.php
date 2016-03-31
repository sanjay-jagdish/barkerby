<?php
include 'config.php';

$em= mysql_real_escape_string(strip_tags($_POST['em']));
$date= mysql_real_escape_string(strip_tags($_POST['date']));
$time= mysql_real_escape_string(strip_tags($_POST['time']));
$paymenttype= mysql_real_escape_string(strip_tags($_POST['paymenttype']));
$uniq= mysql_real_escape_string(strip_tags($_POST['uniq']));
$asap= mysql_real_escape_string(strip_tags($_POST['asap']));
$deliver= mysql_real_escape_string(strip_tags($_POST['deliver']));
$totalprice= mysql_real_escape_string(strip_tags($_POST['totalprice']));
$type= mysql_real_escape_string(strip_tags($_POST['type']));
if($date != '') {
	$date = date('Y-m-d', strtotime($date));
}

//2014-09-12 14:43 format
if($asap==1)
{
	$date = formatDate(date('Y-m-d'));
	$time = date('H:i');
}

$q=mysql_query("select id, confirmed from account where email='".$em."' and type_id=5 and deleted=0 order by id desc limit 1") or die(mysql_error());
if(mysql_num_rows($q) > 0)
{
	$r=mysql_fetch_assoc($q);
	$id=$r['id'];
	$confirmed=$r['confirmed'];
	if($confirmed==1)
	{
		// update phone and name if user is newly registered
		$query = "update account set fname='".$_POST['fullname']."', mobile_number='".$_POST['phone']."' where email='$em'";
		mysql_query( $query );
		
		//mysql_query("insert into reservation(reservation_type_id,account_id,date,time,date_time,payment_mode,approve) values(2,".$id.",'".$date."','".$time."',now(),'".$paymenttype."',8)");
		
		//$reservation_id=mysql_insert_id();
		$order_datetime = date('Y-m-d H:i:s');
		
		mysql_query("update reservation set account_id='".$id."', date='".$date."', time='".$time."', date_time='".$getnow."', payment_mode='".$paymenttype."', approve=8, asap='".$asap."', deliver='".$deliver."', total_price='".$totalprice."', date_time='".$order_datetime."' where uniqueid='".$uniq."'") or die(mysql_error());
		if ($type == "takeaway") {
			unset($_COOKIE['takeaway_id']);
			setcookie('takeaway_id', null, -1, '/');
			echo 'done';
		}else if($type == "lunch"){
			unset($_COOKIE['lunchmeny_id']);
			setcookie('lunchmeny_id', null, -1, '/');
			echo 'done';
		} else {
			echo 'error';
		}

	}
	else {
		echo 'Not';
	}
}
else {
	echo 'Invalid';
}
	
?>
