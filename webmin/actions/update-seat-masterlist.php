<?php session_start();
	include '../config/config.php';
	
	$id=mysql_real_escape_string(strip_tags($_POST['id']));
	$val=mysql_real_escape_string(strip_tags($_POST['val']));
	
	mysql_query("update table_masterlist set seats=".$val." where id=".$id);
	
?>