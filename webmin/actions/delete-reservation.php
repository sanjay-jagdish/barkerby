<?php session_start();
	include '../config/config.php';
	
	$id=strip_tags($_POST['id']);
	$reason=strip_tags($_POST['reason']);
	
	mysql_query("UPDATE reservation SET deleted=1, delete_time=NOW(), deleted_by=".$_SESSION['login']['id'].", reason='".$reason."' WHERE id=".$id) or die(mysql_error());
	mysql_query("INSER INTO log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Deleted/Cancelled reservation # ".$id.".',now(),'".get_client_ip()."')");
	echo $id;
?>