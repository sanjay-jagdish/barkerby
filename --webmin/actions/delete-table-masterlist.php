<?php session_start();
	include '../config/config.php';
	
	$id=strip_tags($_POST['id']);
	
	mysql_query("update table_masterlist set deleted=1 where id=".$id);
	
?>