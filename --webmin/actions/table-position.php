<?php session_start();
	include '../config/config.php';
	
	$pos=mysql_real_escape_string(strip_tags($_POST['pos']));
	
	$val=explode('^',$pos);
	
	for($i=0;$i<count($val);$i++){
		
		$hold=explode('*',$val[$i]);
		$id=$hold[0];
		$holder=explode('/',$hold[1]);
		$top=$holder[0];
		$left=$holder[1];
		
		mysql_query("update table_detail set position_top='".$top."', position_left='".$left."' where id=".$id);
		
	}
	
	mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Updated the floor plan.',now(),'".get_client_ip()."')");
?>