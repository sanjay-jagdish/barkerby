<?php session_start();
	include '../config/config.php';
	
	$stat=mysql_real_escape_string(strip_tags($_POST['stat']));
	$type=mysql_real_escape_string(strip_tags($_POST['type']));
	
	$q=mysql_query("select id from status where description='".$stat."' and reservation_type_id=".$type." and deleted=0") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		echo 'Invalid';
	}
	else{
		mysql_query("insert into status(description,reservation_type_id) values('".$stat."',".$type.")") or die(mysql_error());
		mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Added a status(".$stat.")',now(),'".get_client_ip()."')");
	}
	
?>