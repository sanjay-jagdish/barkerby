<?php session_start();
	include '../config/config.php';
	
	$id=strip_tags($_POST['id']);
	
	mysql_query("update category set deleted=1 where id=".$id);
	mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Deleted a category.',now(),'".get_client_ip()."')");
	
?>