<?php
	include 'config.php';
	
	if($_POST['val']>0){
		mysql_query("update reservation_detail set quantity='".$_POST['val']."' where id='".$_POST['id']."'");
		
		$q=mysql_query("select id from reservation_menu_option where reservation_unique_id='".$_POST['uniq']."' and menu_id='".$_POST['menu_id']."' group by dish_num") or die(mysql_error());
		$potion_count = mysql_num_rows($q);
		
		if($potion_count > $_POST['val']){
		
				mysql_query("delete from reservation_menu_option where reservation_unique_id='".$_POST['uniq']."' and menu_id='".$_POST['menu_id']."' and dish_num>'".$_POST['val']."'") or die(mysql_error());
	
		}
		else{
			 
			if($_POST['val'] > $potion_count){
				$to_add = ($_POST['val']-$potion_count);
				$dishnum = mysql_num_rows($q);
				
				$q = mysql_query("select mo.id as id, mo.name as name, mo.price as price, md.single_option as single_option, if(mo.order_by=0,'A', mo.order_by) as theorderby from menu_options as mo, menu_option_details as md where md.id=mo.menu_option_detail_id and mo.menu_id = '".$_POST['menu_id']."' order by theorderby desc, mo.id desc limit 1") or die(mysql_error());
				
				while($r=mysql_fetch_assoc($q)){
					$menu_option_id = $r['id'];
					$name = $r['name'];
					$price = $r['price'];
					$single_option = $r['single_option'];
				}
				
				if($potion_count){
				
					for($i=0;$i<$to_add;$i++){
						$dishnum++;
						
						mysql_query("insert into reservation_menu_option(reservation_unique_id, menu_option_id, menu_id, name, price, dish_num) values('".$_POST['uniq']."', '$menu_option_id', '".$_POST['menu_id']."', '$name', '$price', '$dishnum')") or die(mysql_error());
						
					}
				
				}
				
			}
		}
		
	}
	else{
		mysql_query("delete from reservation_detail where id='".$_POST['id']."'");
		mysql_query("delete from reservation_menu_option where reservation_unique_id='".$_POST['uniq']."' and menu_id='".$_POST['menu_id']."'");
	}
		

?>	
