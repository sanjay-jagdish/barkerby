<?php
	include 'config.php';
	
	$order=explode('***',$_POST['order']);  //catid***quantity***price
	$order_detail=explode('^',$_POST['order_detail']);
	$uniqueid=$_POST['unique_id'];
	$theid='';
	
	$q=mysql_query("select id from catering_detail where uniqueid='".$uniqueid."' and deleted=0 and status=0") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		$r=mysql_fetch_assoc($q);
		$theid = $r['id'];	
	}
	else{
		
		mysql_query("insert into catering_detail(uniqueid) values('".$uniqueid."')") or die(mysql_error());
		$theid = mysql_insert_id();
	}
	
	mysql_query("insert into catering_order(catering_detail_id,catering_category_id,quantity,price) values('".$theid."','".$order[0]."','".$order[1]."','".$order[2]."')") or die(mysql_error());
	
	$orderid = mysql_insert_id();
	
	for($i=0;$i<count($order_detail);$i++){
		
		$odetail=explode('***',$order_detail[$i]); //menuid***quantity***price
		
		mysql_query("insert into catering_order_detail(catering_order_id,catering_menu_id,quantity,price) values('".$orderid."','".$odetail[0]."','".$odetail[1]."','".$odetail[2]."')") or die(mysql_error());
	}
	
	
	//count items
	
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
	
	echo $counter;
	
	
	
?>