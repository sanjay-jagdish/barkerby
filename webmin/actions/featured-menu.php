<?php session_start();
	include '../config/config.php';
	
	$val=mysql_real_escape_string(strip_tags($_POST['val']));
	$id=strip_tags($_POST['id']);
	
	mysql_query("update menu set featured=".$val." where id=".$id);
	
?>