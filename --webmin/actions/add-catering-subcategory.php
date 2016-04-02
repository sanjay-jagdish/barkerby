<?php session_start();
	include '../config/config.php';

	
	$name=mysql_real_escape_string(strip_tags($_POST['name']));
	$desc=mysql_real_escape_string(strip_tags($_POST['desc']));
	$cat=mysql_real_escape_string(strip_tags($_POST['cat']));
	$number=mysql_real_escape_string(strip_tags($_POST['number']));
	
	$q=mysql_query("select id from catering_subcategory where name='".$name."' and catering_category_id='".$cat."' and deleted=0") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		echo 'Invalid';
	}
	else{
		mysql_query("insert into catering_subcategory(catering_category_id,name,description,number_selected) values('".$cat."','".$name."','".$desc."','".$number."')") or die(mysql_error());
		
		mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Added a catering subcategory(".$name.")',now(),'".get_client_ip()."')");
	}
	
?>