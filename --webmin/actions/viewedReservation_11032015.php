<?php 
	include '../config/config.php';

	$sql=mysql_query("SELECT COUNT(r.viewed) AS view FROM reservation as r, account as a where r.viewed = 0 AND r.reservation_type_id = 2 AND r.deleted = 0 AND a.id = r.account_id AND a.deleted = 0 ");

	while($row = mysql_fetch_assoc($sql)){
		$view = $row['view'];
	}

	echo $view;

	?>