<?php session_start();
	include '../config/config.php';
	
	$id=strip_tags($_POST['id']);
	
	if(mysql_query("UPDATE wait_list SET deleted=1, delete_time=NOW(), deleted_by=".$_SESSION['login']['id']." WHERE id=".$id)){

		mysql_query("INSER INTO log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Deleted/Cancelled waitlist # ".$id.".',now(),'".get_client_ip()."')");
	
		echo '<font color="green">Waitlist Request #'.$id.' has been cancelled.</font>';
	}else{
		echo '<font color="red">Failed to cancel waitlist request #'.$id.'.</font>';	
	}
?>