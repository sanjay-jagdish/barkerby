<?php session_start();
	include '../config/config.php';
	
	//for the sound
	$qr=mysql_query("select id from reservation where deleted=0 and reservation_type_id in (2,3) and viewed=0 and date<>''");
	$val =  mysql_num_rows($qr);

	$sql=mysql_query("SELECT r.viewed AS view, r.payment_mode as payment_mode, r.kco_payment as kco_payment FROM reservation as r, account as a where r.viewed = 0 AND r.reservation_type_id in (2,3) AND r.deleted = 0 AND a.id = r.account_id AND a.deleted = 0 ");

	$view = 0;

	while($row = mysql_fetch_assoc($sql)){
		
		$payment_mode = $row['payment_mode'];
		$kco_payment = $row['kco_payment'];
		
		if($payment_mode=='klarna' && $kco_payment==0){
		}
		else{
			$view++;
		}
		
	}
mysql_close($con);
	echo $view;
?>