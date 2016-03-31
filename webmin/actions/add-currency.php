<?php session_start();
	include '../config/config.php';
	
	$cname=mysql_real_escape_string(strip_tags($_POST['cname']));
	$sname=mysql_real_escape_string(strip_tags($_POST['sname']));
	
	$q=mysql_query("select id from currency where name='".$cname."' and shortname='".$sname."' and deleted=0") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		echo 'Invalid';
	}
	else{
		mysql_query("insert into currency(name,shortname) values('".$cname."','".$sname."')") or die(mysql_error());
		mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Added a currency(".$cname.")',now(),'".get_client_ip()."')");
	}
	
?>