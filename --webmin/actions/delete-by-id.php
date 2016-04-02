<?php session_start();
	
	include '../config/config.php';
	
	$table = strip_tags($_POST['table']);
	$id = strip_tags($_POST['id']);
	
	//mysql_query("delete from " . $table . " where id = " . $id . ";");
	mysql_query("update ".$table." set deleted=1 where id=".$id);
	
	mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Deleted a shift request.',now(),'".get_client_ip()."')");
	
?>