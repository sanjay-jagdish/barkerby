<?php
	include 'config.php';
	
	if($_POST['val']>0){
		mysql_query("update reservation_detail set quantity='".$_POST['val']."' where id='".$_POST['id']."'");
		
		$q=mysql_query("select id from reservation_menu_option where reservation_unique_id='".$_POST['uniq']."' and menu_id='".$_POST['menu_id']."' group by dish_num") or die(mysql_error());
		
		if(mysql_num_rows($q) > $_POST['val']){
		
				mysql_query("delete from reservation_menu_option where reservation_unique_id='".$_POST['uniq']."' and menu_id='".$_POST['menu_id']."' and dish_num>'".$_POST['val']."'") or die(mysql_error());
	
		}
		else{
			 
			if($_POST['val'] > mysql_num_rows($q)){
				$to_add = ($_POST['val']-mysql_num_rows($q));
				$dishnum = mysql_num_rows($q);
				
				$q = mysql_query("select mo.id as id, mo.name as name, mo.price as price, md.single_option as single_option, if(mo.order_by=0,'A', mo.order_by) as theorderby from menu_options as mo, menu_option_details as md where md.id=mo.menu_option_detail_id and mo.menu_id = '".$_POST['menu_id']."' order by theorderby desc, mo.id desc limit 1") or die(mysql_error());
				
				while($r=mysql_fetch_assoc($q)){
					$menu_option_id = $r['id'];
					$name = $r['name'];
					$price = $r['price'];
					$single_option = $r['single_option'];
				}
				
				if($single_option){
				
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
if($_POST['check'] == 'check-plus'){
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
