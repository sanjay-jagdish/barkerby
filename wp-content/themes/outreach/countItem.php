<?php
	include 'config.php';
	
	$uniqueid=$_POST['unique_id'];
	
	//for takeaway
	$uniq=$_POST['uniq'];
	
	if(isset($_POST['del']) && $_POST['del']){ 
		//delete unused orders
		
		$qqry = mysql_query("select rd.id, rd.quantity from reservation_detail as rd, reservation as r where r.id=rd.reservation_id and rd.temp_tillval=1 and r.uniqueid='".$uniq."'") or die(mysql_error());
		if(mysql_num_rows($qqry)>0){
			while(list($rd_id, $quantity) = mysql_fetch_array($qqry)){
				
				if($quantity>1){
					mysql_query("update reservation_detail set quantity = (quantity-1), temp_tillval=0 where id='".$rd_id."'") or die(mysql_error());
				}
				else{
					mysql_query("delete from reservation_detail where id='".$rd_id."'") or die(mysql_error());
				}
				
			}
		}
		
	}
	
	if($uniqueid!='undefined' || trim($uniqueid)!=''){
		$q=mysql_query("select co.catering_category_id as catid, co.id as coid from catering_order as co, catering_detail as cd where cd.id=co.catering_detail_id and cd.uniqueid='".$uniqueid."' and cd.deleted=0") or die(mysql_error());
		
		$counter = 0;
		while($r=mysql_fetch_assoc($q)){
			
			$qq=mysql_query("select id from catering_order_detail where catering_order_id='".$r['coid']."'");
			
			if(mysql_num_rows($qq) > 0){
				$counter+=1;
			}
			else{
				
				$qqq=mysql_query("select sum(price) as sum from catering_category_price where catering_category_id='".$r['catid']."' and deleted=0");
				$rrr=mysql_fetch_assoc($qqq);
				
				if($rrr['sum']>0){
					$counter+=1;
				}
				
			}
			
		}
	}
	else{
		$counter=0;
	}
	
	//for takeaway
	
	if($uniq!='undefined' || trim($uniq)!=''){
		$qq=mysql_query("select id from reservation where uniqueid='".$uniq."'");
		$rr=mysql_fetch_assoc($qq);
		
		$reservation_id = $rr['id'];
		
		/*$q=mysql_query("select id from reservation_detail where reservation_id='".$reservation_id."'") or die(mysql_error());
		
		$takecount=mysql_num_rows($q);*/
		
		$q=mysql_query("select sum(quantity) as quantity from reservation_detail where reservation_id='".$reservation_id."'") or die(mysql_error());
		$r=mysql_fetch_assoc($q);
		
		$takecount=$r['quantity'];
	}
	else{
		$takecount=0;
	}
	
	
	//for breakfast
	
	$bfuniq=$_POST['bfuniq'];
	
	if($bfuniq!='undefined' || trim($bfuniq)!=''){
		$qq=mysql_query("select id from reservation where uniqueid='".$bfuniq."'");
		$rr=mysql_fetch_assoc($qq);
		
		$reservation_id = $rr['id'];
		
		$q=mysql_query("select id from reservation_detail where reservation_id='".$reservation_id."'") or die(mysql_error());
		
		$breakfastcount=mysql_num_rows($q);
	}
	else{
		$breakfastcount=0;
	}
	
	
	//for lunchmeny
	
	$lmuniq=$_POST['lmuniq'];
	
	if($lmuniq!='undefined' || trim($lmuniq)!=''){
		$qq=mysql_query("select id from reservation where uniqueid='".$lmuniq."'");
		$rr=mysql_fetch_assoc($qq);
		
		$reservation_id = $rr['id'];
		
		$q=mysql_query("select sum(quantity) as quantity from reservation_detail where reservation_id='".$reservation_id."'") or die(mysql_error());
		$r=mysql_fetch_assoc($q);
		$lunchmenycount=$r['quantity'];
	}
	else{
		$lunchmenycount=0;
	}
	
	echo $counter.'*'.$takecount.'*'.$lunchmenycount.'*'.$breakfastcount.'*del='.$_POST['del'];
	
?>
