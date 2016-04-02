<?php session_start();
	include '../config/config.php';
	
	$stat=mysql_real_escape_string(strip_tags($_POST['stat']));
	$type=mysql_real_escape_string(strip_tags($_POST['type']));
	$id=strip_tags($_POST['id']);
	
	$q=mysql_query("select id from status where description='".$stat."' and reservation_type_id=".$type." and deleted=0") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		
		$r=mysql_fetch_assoc($q);
		
		if($r['id']==$id){
			mysql_query("update status set description='".$stat."', reservation_type_id=".$type." where id=".$id) or die(mysql_error());
			mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Update a status description.',now(),'".get_client_ip()."')");
		}
		else{
			echo 'Invalid';
		}
	}
	else{
		mysql_query("update status set description='".$stat."', reservation_type_id=".$type." where id=".$id) or die(mysql_error());
		mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Update a status description.',now(),'".get_client_ip()."')");
	}
	
?>