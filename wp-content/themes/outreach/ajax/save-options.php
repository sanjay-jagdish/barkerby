<?php
	include 'config.php';
	
	$menu_id = $_POST['menu_id'];
	$uniq = $_POST['uniq'];
	$notes = $_POST['notes'];
	$res_detail_id = $_POST['res_detail_id'];
	$addons = explode('^',$_POST['addons']);
	$thetemptillval = $_POST['thetemptillval'];
	$check = $_POST['check'];
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
	if($_POST['check'] == 'check'){
	function getOptionTotal($menu_id){
		$total=0;
	
		$q=mysql_query("select sum(price) as total from reservation_menu_option where menu_id='".$menu_id."' and reservation_unique_id='".$_COOKIE['takeaway_id']."'");
		$r=mysql_fetch_assoc($q);
		
		if(mysql_num_rows($q)>0){
			$total = $r['total'];
		}
		
		return $total;
	}
	$q=mysql_query("select * from reservation_detail where reservation_id=(select id from reservation where deleted=0 and uniqueid='".$_POST['uniq']."' order by id desc limit 1)") or die(mysql_error());
	if(mysql_num_rows($q)>0){
			 
				while($r=mysql_fetch_assoc($q)){
			//for the mandatory tillvals
				$qtill = mysql_query("select id from menu_option_details where menu_id='".$r['menu_id']."' and single_option=1") or die(mysql_error());
				$count_tillvals = mysql_num_rows($qtill);
		                 if($r['menu_id'] == $_POST['menu_id']){
							$subTotal =  (($r['price']*$r['quantity'])+getOptionTotal($r['menu_id'],$_POST['uniq']));
							
						  }
						 $theprice=(($r['price']*$r['quantity'])+getOptionTotal($r['menu_id'],$_POST['uniq']));
						 $total+=$theprice;
						 
						 $count++;
					}
				$total = $total.''.getCurrentCurrency();	
				$subTotal = $subTotal.''.getCurrentCurrency();
              }  
			   $out = array("subtotal"=>$subTotal,"total"=>$total,"count"=>$count);
               print_r(json_encode($out));	
		   }
?>
