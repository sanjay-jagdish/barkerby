<?php session_start();
	include '../config/config.php';
	
	$cat=mysql_real_escape_string(strip_tags($_POST['cat']));
	$subcat=mysql_real_escape_string(strip_tags($_POST['subcat']));
	
	$q=mysql_query("select id from sub_category where name='".$subcat."' and category_id=".$cat." and deleted=0") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		echo 'Invalid';
	}
	else{
		mysql_query("insert into sub_category(category_id,name) values(".$cat.",'".$subcat."')") or die(mysql_error());
		
		mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Added a Sub Category(".$cat.")',now(),'".get_client_ip()."')");
	}
	
?>