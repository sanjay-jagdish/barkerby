<?php session_start();
	include '../config/config.php';
	
	$val = $_POST['val'];
	
	mysql_query("update settings set var_value='".$val."' where var_name='panic_value'");
	
?>