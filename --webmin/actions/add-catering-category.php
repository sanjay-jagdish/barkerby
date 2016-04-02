<?php session_start();
	include '../config/config.php';

	
	$name=mysql_real_escape_string(strip_tags($_POST['name']));
	$desc=mysql_real_escape_string(strip_tags($_POST['desc']));
	$added_desc=mysql_real_escape_string(strip_tags($_POST['added_desc']));
	$prices=mysql_real_escape_string(strip_tags($_POST['prices']));
	$number=mysql_real_escape_string(strip_tags($_POST['number']));
	$minorder=mysql_real_escape_string(strip_tags($_POST['minorder']));
	$hasnotes=mysql_real_escape_string(strip_tags($_POST['hasnotes']));
	
	$q=mysql_query("select id from catering_category where name='".$name."' and deleted=0") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		echo 'Invalid';
	}
	else{
		mysql_query("insert into catering_category(name,description,added_description,number_selected,minimum_order,has_notes) values('".$name."','".$desc."','".$added_desc."','".$number."','".$minorder."','".$hasnotes."')") or die(mysql_error());
		
		$id = mysql_insert_id();
		
		$perprice = explode('**', $prices);
		
		for($i=0;$i<count($perprice); $i++){
			
			$pricing = explode('^^',$perprice[$i]);
			
			$theprice = $pricing[0];
			$thetype = $pricing[1];
			$thedesc = $pricing[2];
			
			if($theprice!=''){
				mysql_query("insert into catering_category_price(catering_category_id,price,price_type,price_description) values('".$id."','".$theprice."','".$thetype."','".$thedesc."')") or die(mysql_error());
			}
			
		}
		
		
		mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Added a Catering Category(".$name.")',now(),'".get_client_ip()."')");
	}
	
?>