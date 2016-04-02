<?php session_start();
	include '../config/config.php';
	
	$id=strip_tags($_POST['id']);
	$cp=mysql_real_escape_string(strip_tags($_POST['cp']));
	$np=mysql_real_escape_string(strip_tags($_POST['np']));
	
	$q=mysql_query("select id from account where password='".md5($cp)."' and id=".$id) or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		mysql_query("update account set password='".md5($np)."' where id=".$id) or die(mysql_error());
		mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Modified a password.',now(),'".get_client_ip()."')");
	}
	else{
		echo 'Invalid';
	}
	
?>