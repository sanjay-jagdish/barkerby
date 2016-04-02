<?php session_start();
	include '../config/config.php';
	
	//for the sound
	$qr=mysql_query("select id from reservation where deleted=0 and reservation_type_id=2 and viewed=0 and date<>''");
	$val =  mysql_num_rows($qr);

	$sql=mysql_query("SELECT COUNT(r.viewed) AS view FROM reservation as r, account as a where r.viewed = 0 AND r.reservation_type_id = 2 AND r.deleted = 0 AND a.id = r.account_id AND a.deleted = 0 ");

	while($row = mysql_fetch_assoc($sql)){
		$view = $row['view'];
	}

	echo $val."*".$view;
?>