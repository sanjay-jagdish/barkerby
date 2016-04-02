<?php session_start();
	include '../config/config.php';
	
	$id=strip_tags($_POST['id']);
	
	mysql_query("INSERT notification (account_id, subject, content, created, status) 
						SELECT '".$_SESSION['login']['id']."', CONCAT('Copy of: ',subject), content, NOW(), 0 FROM notification WHERE id=".$id);
	mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Copied notification #".$id.".',now(),'".get_client_ip()."')");
		
?>