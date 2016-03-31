<?php session_start();
	include '../config/config.php';
	
	$main_cat = 0;
	$sub_cat = 0;
	$cat=mysql_real_escape_string(strip_tags($_POST['cat']));
	$cattype=mysql_real_escape_string(strip_tags($_POST['cattype']));
	$name=mysql_real_escape_string($_POST['name']);
	$des=mysql_real_escape_string($_POST['des']);
	$price=mysql_real_escape_string(strip_tags($_POST['price']));
	$currency=mysql_real_escape_string(strip_tags($_POST['currency']));
	$featured=mysql_real_escape_string(strip_tags($_POST['featured']));
	$img=mysql_real_escape_string(strip_tags($_POST['img']));
	$id=mysql_real_escape_string(strip_tags($_POST['id']));
	$menutype=mysql_real_escape_string(strip_tags($_POST['menutype']));
	$takeaway_price=mysql_real_escape_string(strip_tags($_POST['takeaway_price']));
	$discount_unit=mysql_real_escape_string(strip_tags($_POST['discount_unit']));
	$takeaway_tillval=mysql_real_escape_string(strip_tags($_POST['takeaway_tillval']));
	
	if($cattype == "maincat"){
		$main_cat = $cat;
		$sub_cat = 0;
		$q=mysql_query("select id from menu where name='".$name."' and cat_id=".$main_cat." and deleted=0") or die(mysql_error());
	}elseif($cattype == "subcat"){
		$main_cat = 0;
		$sub_cat = $cat;
		$q=mysql_query("select id from menu where name='".$name."' and sub_category_id=".$sub_cat." and deleted=0") or die(mysql_error());
	}
	
	if(mysql_num_rows($q) > 0){
	
		$r=mysql_fetch_assoc($q);
		
		if($id==$r['id']){
			
			if($img!=''){
			//mysql_query("update menu set sub_category_id=".$cat.", name='".$name."', description='".$des."', price='".$price."', currency_id=".$currency.", image='".$img."', featured=".$featured.", type='".$menutype."', discount=".$discount." where id=".$id);
			mysql_query("update menu set cat_id=".$main_cat.", sub_category_id=".$sub_cat.", name='".$name."', description='".$des."', price='".$price."', currency_id=".$currency.", image='".$img."', type='".$menutype."', takeaway_price='".$takeaway_price."', discount_unit='".$discount_unit."', featured=".$featured.", single_option='".$takeaway_tillval."' where id=".$id);	
			mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Modified a menu.',now(),'".get_client_ip()."')");
			}
			else{
				//mysql_query("update menu set sub_category_id=".$cat.", name='".$name."', description='".$des."', price='".$price."', currency_id=".$currency.", featured=".$featured.", type='".$menutype."', discount=".$discount." where id=".$id);
				mysql_query("update menu set cat_id=".$main_cat.", sub_category_id=".$sub_cat.", name='".$name."', description='".$des."', price='".$price."', currency_id=".$currency.", type='".$menutype."', takeaway_price='".$takeaway_price."', discount_unit='".$discount_unit."', featured=".$featured.", single_option='".$takeaway_tillval."' where id=".$id);
				mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Modified a menu.',now(),'".get_client_ip()."')");
			}
			echo $id;
		}	
		else{
			echo 'Invalid';
		}
		
		
	}
	else{
		
		if($img!=''){
			//mysql_query("update menu set sub_category_id=".$cat.", name='".$name."', description='".$des."', price='".$price."', currency_id=".$currency.", image='".$img."', featured=".$featured.", type='".$menutype."', discount=".$discount." where id=".$id);
			mysql_query("update menu set cat_id=".$main_cat.", sub_category_id=".$sub_cat.", name='".$name."', description='".$des."', price='".$price."', currency_id=".$currency.", image='".$img."', type='".$menutype."', takeaway_price='".$takeaway_price."', discount_unit='".$discount_unit."', featured=".$featured.", single_option='".$takeaway_tillval."' where id=".$id);
			mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Modified a menu.',now(),'".get_client_ip()."')");
		}
		else{
			//mysql_query("update menu set sub_category_id=".$cat.", name='".$name."', description='".$des."', price='".$price."', currency_id=".$currency.", featured=".$featured.", type='".$menutype."', discount=".$discount." where id=".$id);
			mysql_query("update menu set cat_id=".$main_cat.", sub_category_id=".$sub_cat.", name='".$name."', description='".$des."', price='".$price."', currency_id=".$currency.", type='".$menutype."', takeaway_price='".$takeaway_price."', discount_unit='".$discount_unit."', featured=".$featured.", single_option='".$takeaway_tillval."' where id=".$id);
			mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Modified a menu.',now(),'".get_client_ip()."')");
		}
	}
	
?>