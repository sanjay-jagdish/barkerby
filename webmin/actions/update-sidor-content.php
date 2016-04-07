<?php session_start();
	include '../config/config.php';
	
	$content=mysql_real_escape_string($_POST['content']);
	$id=strip_tags($_POST['id']);
	
	if(mysql_query("update custom_content set content='".$content."' where id=".$id)){
		echo '1';
	}else{
		echo '0';
	}
?>