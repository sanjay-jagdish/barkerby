<?php session_start();
	include '../config/config.php';
	
	$img=mysql_real_escape_string(strip_tags($_POST['img']));
	
	mysql_query("update settings set var_value='".$img."' where var_name='floor_plan'");
	
?>