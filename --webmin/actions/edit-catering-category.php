<?php session_start();
	include '../config/config.php';

	
	$name=mysql_real_escape_string(strip_tags($_POST['name']));
	$desc=mysql_real_escape_string(strip_tags($_POST['desc']));
	$added_desc=mysql_real_escape_string(strip_tags($_POST['added_desc']));
	$prices=mysql_real_escape_string(strip_tags($_POST['prices']));
	$number=mysql_real_escape_string(strip_tags($_POST['number']));
	$id=mysql_real_escape_string(strip_tags($_POST['id']));
	$minorder=mysql_real_escape_string(strip_tags($_POST['minorder']));
	$hasnotes=mysql_real_escape_string(strip_tags($_POST['hasnotes']));
	
	$proceed=0;
	
	$q=mysql_query("select id from catering_category where name='".$name."' and deleted=0") or die(mysql_error());
	
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
		
		mysql_query("update catering_category set name='".$name."', description='".$desc."', added_description='".$added_desc."', number_selected='".$number."', minimum_order='".$minorder."', has_notes='".$hasnotes."' where id='".$id."'");
		
		$perprice = explode('**', $prices);
		
		mysql_query("delete from catering_category_price where catering_category_id='".$id."'") or die(mysql_error());
		
		for($i=0;$i<count($perprice); $i++){
			
			$pricing = explode('^^',$perprice[$i]);
			
			$theprice = $pricing[0];
			$thetype = $pricing[1];
			$thedesc = $pricing[2];
			
			mysql_query("insert into catering_category_price(catering_category_id,price,price_type,price_description) values('".$id."','".$theprice."','".$thetype."','".$thedesc."')") or die(mysql_error());
			
		}
	}
	
	mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Updated a catering category(".$name.")',now(),'".get_client_ip()."')");
	
?>