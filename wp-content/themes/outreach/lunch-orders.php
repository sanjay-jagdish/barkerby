<?php

	include 'config.php';
	
	$uniq = $_POST['uniq'];
	$menu_id = $_POST['menu_id'];
	$quan = $_POST['quan'];
	$price = $_POST['price'];
	$lunchmeny = $_POST['lunchmeny'];
	
	$tillval_count = $_POST['tillval_count'];
	
	$theresid = 0;

	$q=mysql_query("select id from reservation where uniqueid='".$uniq."' and deleted=0 and reservation_type_id=3") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		$r=mysql_fetch_assoc($q);
		$reservation_id = $r['id'];
	}
	else{
		mysql_query("insert into reservation(reservation_type_id,uniqueid) values(3,'".$uniq."')") or die(mysql_error());
		$reservation_id = mysql_insert_id();
	}
	
	$qm=mysql_query("select id, menu_id from reservation_detail where menu_id='".$menu_id."' and reservation_id = '".$reservation_id."' and lunchmeny=1") or die(mysql_error());
	if(mysql_num_rows($qm) > 0){
		$mid=mysql_fetch_assoc($qm);
		$menu_id = $mid['menu_id'];
		$res_id = $mid['id'];
		
		$addedqry = '';
		
		if($tillval_count>0){
			$addedqry = ', temp_tillval=1';
		}

		mysql_query("update reservation_detail set quantity = (quantity+$quan) $addedqry where  menu_id='".$menu_id."' and reservation_id = '".$reservation_id."' and lunchmeny=1") or die(mysql_error());
		
		$theresid = $res_id;
		
	}else{
		if(!isset($lunchmeny)){ // 0 is for takeaway
		
			if($tillval_count>0){
				mysql_query("insert into reservation_detail(reservation_id,menu_id,quantity,price,status,lunchmeny,temp_tillval) values('".$reservation_id."','".$menu_id."','".$quan."','".$price."',0,1,1)") or die(mysql_error());
			}
			else{
				mysql_query("insert into reservation_detail(reservation_id,menu_id,quantity,price,status,lunchmeny) values('".$reservation_id."','".$menu_id."','".$quan."','".$price."',0,1)") or die(mysql_error());
			}
			
			$theresid = mysql_insert_id();
			
		}
		else{
			
			if($lunchmeny==2){ // 2 is for breakfast
			
				if($tillval_count>0){
					mysql_query("insert into reservation_detail(reservation_id,menu_id,quantity,price,status,lunchmeny,temp_tillval) values('".$reservation_id."','".$menu_id."','".$quan."','".$price."',0,1,1)") or die(mysql_error());
				}
				else{
					mysql_query("insert into reservation_detail(reservation_id,menu_id,quantity,price,status,lunchmeny) values('".$reservation_id."','".$menu_id."','".$quan."','".$price."',0,1)") or die(mysql_error());
				}
				
			}
			else{ // 1 is for lunchmeny
				if($tillval_count>0){
					mysql_query("insert into reservation_detail(reservation_id,menu_id,quantity,price,status,lunchmeny,temp_tillval) values('".$reservation_id."','".$menu_id."','".$quan."','".$price."',0,1,1)") or die(mysql_error());
				}
				else{
					mysql_query("insert into reservation_detail(reservation_id,menu_id,quantity,price,status,lunchmeny) values('".$reservation_id."','".$menu_id."','".$quan."','".$price."',0,1)") or die(mysql_error());
				}
			}
		}
	}
	/*//counting the ordered items
	
	$qi=mysql_query("select id from reservation_detail where reservation_id=(select id from reservation where uniqueid='".$uniq."')") or die(mysql_error());
	
	echo mysql_num_rows($qi);*/
	echo $theresid;
	mysql_close($con);	
	
?>
