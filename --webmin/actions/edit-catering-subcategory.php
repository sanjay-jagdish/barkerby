<?php session_start();
	include '../config/config.php';

	
	$name=mysql_real_escape_string(strip_tags($_POST['name']));
	$desc=mysql_real_escape_string(strip_tags($_POST['desc']));
	$cat=mysql_real_escape_string(strip_tags($_POST['cat']));
	$number=mysql_real_escape_string(strip_tags($_POST['number']));
	$id=mysql_real_escape_string(strip_tags($_POST['id']));
	
	$proceed=0;
	
	$q=mysql_query("select id from catering_subcategory where name='".$name."' and catering_category_id='".$cat."' and deleted=0") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		
		$r=mysql_fetch_assoc($q);
		
		if($r['id']==$id){
			$proceed=1;
		}
		else{
			echo 'Invalid';
		}
	}
	else{
		$proceed=1;
	}
	
	if($proceed==1){
		
		mysql_query("update catering_subcategory set catering_category_id='".$cat."', name='".$name."', description='".$desc."', number_selected='".$number."' where id='".$id."'");

	}
	
	mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Updated a Catering Subcategory(".$name.")',now(),'".get_client_ip()."')");
	
?>