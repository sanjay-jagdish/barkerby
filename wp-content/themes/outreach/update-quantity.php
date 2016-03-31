<?php
	include 'config.php';
	
	if($_POST['type']=='update'){
		mysql_query("update reservation_detail set quantity=(quantity-1) where id='".$_POST['id']."'");
	}
	else{
		mysql_query("delete from reservation_detail where id='".$_POST['id']."'");
	}
?>	