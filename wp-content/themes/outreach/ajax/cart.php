<?php
	function getOptionTotal($menu_id,$chkqnkid){
		$total=0;
		
		$q=mysql_query("select sum(price) as total from reservation_menu_option where menu_id='".$menu_id."' and reservation_unique_id='".$chkqnkid."'");
		$r=mysql_fetch_assoc($q);
		
		if(mysql_num_rows($q)>0){
			$total = $r['total'];
		}
		
		return $total;
	}
	
	function getMenuDescription($id){
		$q=mysql_query("select description from menu where id='".$id."'");
		$r=mysql_fetch_assoc($q);
		
		return $r['description'];
	}
	
	function checkUpdates($id,$menuid,$chkqnkid){
		$count=1;
		
		$q=mysql_query("select notes from reservation_detail where id='".$id."'");
		$r=mysql_fetch_assoc($q);
		
		if($r['notes']!=''){
			$count+=1;
		}
		
		$qq=mysql_query("select id from reservation_menu_option where menu_id='".$menuid."' and reservation_unique_id='".$chkqnkid."' and menu_option_id<>0");
		
		if(mysql_num_rows($qq)>0){
			$count+=1;
		}
		
		if($count!=1){
			return 1;
		}
		else{
			return '0.5';
		}
		
	}
	
	$q=mysql_query("select * from reservation_detail where reservation_id=(select id from reservation where deleted=0 and uniqueid='".$chkqnkid."' order by id desc limit 1)") or die(mysql_error());

	if(mysql_num_rows($q)>0){
			echo '<table class="table" id="main-table" data-rel="'.CHILD_URL.'">';	
				
			  $total=0;
				while($r=mysql_fetch_assoc($q)){
			//for the mandatory tillvals
				$qtill = mysql_query("select id from menu_option_details where menu_id='".$r['menu_id']."' and single_option=1") or die(mysql_error());
				$count_tillvals = mysql_num_rows($qtill);
			//end tillvals
		?>
						<tr id="item-<?php echo $r['id'];?>">
							<td>
								<button type="button" class="qtyplus qnty-btn add" field='quantity-<?php echo $r['id'];?>' data-id="<?php echo $r['id'];?>" data-rel="<?php echo $r['menu_id']; ?>" data-tillvals="<?php echo $count_tillvals; ?>">+</button>
						    <input type='text' name='quantity-<?php echo $r['id'];?>' value='<?php echo $r['quantity']; ?>' class='qty lmquantity-<?php echo $r['id'];?>' data-id="<?php echo $r['id'];?>" data-rel="<?php echo $r['menu_id']; ?>" readonly />
						    <button type="button" class="qtyminus qnty-btn less" field='quantity-<?php echo $r['id'];?>' data-id="<?php echo $r['id'];?>" data-rel="<?php echo $r['menu_id']; ?>">-</button>
							</td>
							<?php if($type == "takeaway" ){
								echo '<td>'.getMenuName($r['menu_id']).'</td>';
								 }else{
									echo '<td>'.getLunchMenuName($r['menu_id']).'</td>';
								} ?>
							<td id="subtotal-<?php echo $r['id'];?>"><?php echo $theprice=(($r['price']*$r['quantity'])+getOptionTotal($r['menu_id'],$chkqnkid)).' '.getCurrentCurrency();?></td>
							<td><button type="button" class="setting-btn btn_optional_dish-<?php echo $r['id'];?> active" data-id="<?php echo $r['id'];?>" data-price="<?php echo $r['price'];?>" data-rel="<?php echo getMenuName($r['menu_id']).'<span>'.getMenuDescription($r['menu_id']).'</span>';?>" style="opacity:<?php echo checkUpdates($r['id'],$r['menu_id'],$chkqnkid); ?>"><i class="flaticon-shape"></button></td>
						</tr>
						<?php $total+=$theprice; } ?> 
			</table>
			<h3>SLUTSUMMA<span class="total-amt"><?php echo $total.' '.getCurrentCurrency();?></span></h3>
			<input type="hidden" value="<?php echo $total; ?>" id="total-price">
<?php }  ?>

