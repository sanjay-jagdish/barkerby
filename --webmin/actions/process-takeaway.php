<?php session_start();
	include '../config/config.php';
	
	$id=strip_tags($_POST['id']);
	
	mysql_query("update reservation set processed=1 where id='".$id."'");
	
	$q=mysql_query("select approve from reservation where id='".$id."'");
	$r=mysql_fetch_assoc($q);
	
	if($r['approve']==8){
		mysql_query("update reservation set approve=14, reason='', lead_time=0, approve_by=".$_SESSION['login']['id'].", acknowledged=1 where id='".$id."'");
	}
	
	mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Processed an order',now(),'".get_client_ip()."')");
	
?>