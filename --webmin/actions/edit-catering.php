<?php session_start();
	include '../config/config.php';
	
	$name=mysql_real_escape_string(strip_tags($_POST['name']));
	$desc=mysql_real_escape_string($_POST['desc']);
	$price=mysql_real_escape_string(strip_tags($_POST['price']));
	$id=strip_tags($_POST['id']);
	
	$q=mysql_query("select id from catering where name='".$name."' and deleted=0") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		
		$r=mysql_fetch_assoc($q);
		
		if($id==$r['id']){
			mysql_query("update catering set name='".$name."', description='".$desc."', price='".$price."' where id=".$id) or die(mysql_error());
			mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Modified a category package.',now(),'".get_client_ip()."')");
		}
		else{
			echo 'Invalid';
		}
		
	}
	else{
		mysql_query("update catering set name='".$name."', description='".$desc."', price='".$price."' where id=".$id) or die(mysql_error());
		mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Modified a category package.',now(),'".get_client_ip()."')");
	}
	
?>