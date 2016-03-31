<?php session_start();
	include '../config/config.php';
	
	$id=$_POST['id'];
	
	mysql_query("update menu set image='' where id='".$id."'") or die(mysql_error());
	
	echo $id;
	
?>