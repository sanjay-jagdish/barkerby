<?php session_start();
	include '../config/config.php';
	
	$name=mysql_real_escape_string(strip_tags($_POST['name']));
	$desc=mysql_real_escape_string($_POST['desc']);
	$price=mysql_real_escape_string(strip_tags($_POST['price']));
	
	$q=mysql_query("select id from catering where name='".$name."' and deleted=0") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		echo 'Invalid';
	}
	else{
		mysql_query("insert into catering(name,description,price) values('".$name."','".$desc."',".$price.")") or die(mysql_error());
		
		mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Added a catering package(".$name.")',now(),'".get_client_ip()."')");
	}
	
?>