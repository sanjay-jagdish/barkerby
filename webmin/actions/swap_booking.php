<?php
	session_start();
	
	include '../config/config.php';
	
	$id1 = $_POST['res_id_1'];
	$id2 = $_POST['res_id_2'];
		
	$sql = "SELECT id FROM reservation_table WHERE reservation_id=".$id1;
	$qry = mysql_query($sql);
	while($res = mysql_fetch_assoc($qry)){
		$tables_A[] = $res['id'];
	}

	$sql = "SELECT id FROM reservation_table WHERE reservation_id=".$id2;
	$qry = mysql_query($sql);
	while($res = mysql_fetch_assoc($qry)){
		$tables_B[] = $res['id'];
	}
	
	foreach($tables_A as $key => $res_table_id){
		mysql_query('UPDATE reservation_table SET reservation_id='.$id2.' WHERE id='.$res_table_id);
	}

	foreach($tables_B as $key => $res_table_id){
		mysql_query('UPDATE reservation_table SET reservation_id='.$id1.' WHERE id='.$res_table_id);
	}
	
	
?>