<?php session_start();
	include '../config/config.php';
	
	$cat=mysql_real_escape_string(strip_tags($_POST['cat']));
	$subcat=mysql_real_escape_string(strip_tags($_POST['subcat']));
	$id=strip_tags($_POST['id']);
	
	$q=mysql_query("select id from sub_category where name='".$subcat."' and category_id=".$cat." and deleted=0") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		
		$r=mysql_fetch_assoc($q);
		
		if($id==$r['id']){
			mysql_query("update sub_category set name='".$subcat."', category_id=".$cat." where id=".$id) or die(mysql_error());
			mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Modified a Sub Category.',now(),'".get_client_ip()."')");
		}
		else{
			echo 'Invalid';
		}
		
	}
	else{
		mysql_query("update sub_category set name='".$subcat."', category_id=".$cat." where id=".$id) or die(mysql_error());
		mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Modified a Sub Category.',now(),'".get_client_ip()."')");
	}
	
?>