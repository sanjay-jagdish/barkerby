<?php session_start();
	include '../config/config.php';
	
	$id=mysql_real_escape_string(strip_tags($_POST['id']));
	$col=mysql_real_escape_string(strip_tags($_POST['col']));
	$val=mysql_real_escape_string(strip_tags($_POST['check']));
	
	mysql_query("update type set ".$col."=".$val." where id=".$id);
	
	mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Updated the account privilege.',now(),'".get_client_ip()."')");
	
?>