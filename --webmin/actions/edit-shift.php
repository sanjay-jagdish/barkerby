<?php
	session_start();
	
	include '../config/config.php';
	
	$a_id = $_SESSION['login']['id'];
	$dateTime = mysql_real_escape_string(strip_tags($_POST['dt']));
	$des = mysql_real_escape_string(strip_tags($_POST['des']));
	$id = strip_tags($_POST['id']);
	
	$result = mysql_query("update shift_request set account_id = ". $a_id .", description = '" . $des . "', date = '". $dateTime ."', time = '". $dateTime ."' where id = " . $id . ";");
	
	mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Modified a shift request.',now(),'".get_client_ip()."')");
	
	if(!$result) {
		echo 'Invalid' .mysql_error();
	}
?>