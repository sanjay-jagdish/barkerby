<?php
	include 'config.php';
	
	$menu_id = $_POST['menu_id'];
	$uniq = $_POST['uniq'];
	$notes = $_POST['notes'];
	$res_detail_id = $_POST['res_detail_id'];
	$addons = explode('^',$_POST['addons']);
	$thetemptillval = $_POST['thetemptillval'];
	
	//save notes
	/*if($thetemptillval){	
		mysql_query("update reservation_detail set notes='".$notes."', temp_tillval=0, quantity = (quantity+1) where id='".$res_detail_id."'") or die(mysql_error());
		
	}
	else{*/
		mysql_query("update reservation_detail set notes='".$notes."', temp_tillval=0 where id='".$res_detail_id."'") or die(mysql_error());
	//}
	
	
	
	//delete first the reservation_menu_option
	
	mysql_query("delete from reservation_menu_option where reservation_unique_id='".$uniq."' and menu_id='".$menu_id."'") or die(mysql_error());
	
	//save menu options
	for($i=0;$i<count($addons);$i++){
		
		$adds=explode('*',str_replace('opt_','',$addons[$i]));
		
		$str = explode('-',$adds[0]);
		
		$price = $adds[1];
		$name = $adds[2];
		$dishnum = $str[0];
		$menu_option_id = $str[1];
		
		mysql_query("insert into reservation_menu_option(reservation_unique_id, menu_option_id, menu_id, name, price, dish_num) values('".$uniq."', '".$menu_option_id."', '".$menu_id."', '".$name."', '".$price."', '".$dishnum."')") or die(mysql_error());
		
	}
  $total=0;
  $count=0;
  $subTotal=0;
	function getOptionTotal($menu_id,$chkqnkid){
		$total=0;
		
		$q=mysql_query("select sum(price) as total from reservation_menu_option where menu_id='".$menu_id."' and reservation_unique_id='".$chkqnkid."'");
		$r=mysql_fetch_assoc($q);
		
		if(mysql_num_rows($q)>0){
			$total = $r['total'];
		}
		
		return $total;
	}
	
?>
