<?php session_start();
	include '../config/config.php';
	
	$id=strip_tags($_POST['id']);
	
	$q=mysql_query("select if(bongs_status=0,1,0) as bongs from reservation where id='".$id."'") or die(mysql_error());
	$r=mysql_fetch_assoc($q);
	
	mysql_query("update reservation set bongs_status='".$r['bongs']."' where id='".$id."'") or die(mysql_error()); 
	
	mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Set the status of an order to On Process',now(),'".get_client_ip()."')");
	
?>