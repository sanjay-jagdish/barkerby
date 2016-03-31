<?php
	include 'config.php';
	
		
	if($_POST['val']>0){
		mysql_query("update reservation_detail set quantity='".$_POST['val']."' where id='".$_POST['id']."'");
		
		$q=mysql_query("select id from reservation_menu_option where reservation_unique_id='".$_POST['uniq']."' and menu_id='".$_POST['menu_id']."' group by dish_num") or die(mysql_error());
		
		if(mysql_num_rows($q)>=$_POST['val']){
		
				mysql_query("delete from reservation_menu_option where reservation_unique_id='".$_POST['uniq']."' and menu_id='".$_POST['menu_id']."' and dish_num>'".$_POST['val']."'") or die(mysql_error());
	
		}
		
	}
	else{
		mysql_query("delete from reservation_detail where id='".$_POST['id']."'");
		mysql_query("delete from reservation_menu_option where reservation_unique_id='".$_POST['uniq']."' and menu_id='".$_POST['menu_id']."'");
	}
		

?>	