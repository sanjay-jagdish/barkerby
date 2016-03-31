<?php session_start();
	include '../config/config.php';
	
	$id=strip_tags($_POST['id']);
	
	mysql_query("update currency set set_default=0");
	mysql_query("update currency set set_default=1 where id=".$id);
	
	$q=mysql_query("select name,shortname from currency where id=".$id);
	$r=mysql_fetch_array($q);
	
	mysql_query("update settings set var_value='".$r[1]." - ".$r[0]."' where var_name='default_currency'");
	
	mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Set default currency to ".$r[0]." (".$r[1].")"."',now(),'".get_client_ip()."')");
	
?>