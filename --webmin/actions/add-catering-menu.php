<?php session_start();
	include '../config/config.php';

	
	$name=mysql_real_escape_string(strip_tags($_POST['name']));
	$desc=mysql_real_escape_string(strip_tags($_POST['desc']));
	$cat=mysql_real_escape_string(strip_tags($_POST['cat']));
	$prices=mysql_real_escape_string(strip_tags($_POST['prices']));
	$subcat=mysql_real_escape_string(strip_tags($_POST['subcat']));
	
	$q=mysql_query("select id from catering_menu where name='".$name."' and catering_category_id='".$cat."' and catering_subcategory_id='".$subcat."' and deleted=0") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		echo 'Invalid';
	}
	else{
		mysql_query("insert into catering_menu(catering_category_id,catering_subcategory_id,name,description) values('".$cat."','".$subcat."','".$name."','".$desc."')") or die(mysql_error());
		
		$id = mysql_insert_id();
		
		$perprice = explode('**', $prices);
		
		for($i=0;$i<count($perprice); $i++){
			
			$pricing = explode('^^',$perprice[$i]);
			
			$theprice = $pricing[0];
			$thetype = $pricing[1];
			$thedesc = $pricing[2];
			
			if($theprice!=''){
			
				mysql_query("insert into catering_menu_price(catering_menu_id,price,price_type,price_description) values('".$id."','".$theprice."','".$thetype."','".$thedesc."')") or die(mysql_error());
			
			}
			
		}
		
		
		mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Added a Catering Menu(".$name.")',now(),'".get_client_ip()."')");
	}
	
?>