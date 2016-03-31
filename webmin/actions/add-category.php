<?php session_start();
	include '../config/config.php';
	
	$cat=mysql_real_escape_string(strip_tags($_POST['cat']));
	$desc=mysql_real_escape_string(strip_tags($_POST['desc']));
	
	$q=mysql_query("select id from category where name='".$cat."' and deleted=0") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		echo 'Invalid';
	}
	else{
		mysql_query("insert into category(name,description) values('".$cat."','".$desc."')") or die(mysql_error());
		
		mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Added a category(".$cat.")',now(),'".get_client_ip()."')");
	}
	
?>