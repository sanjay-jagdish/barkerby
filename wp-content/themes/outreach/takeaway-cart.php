<?php
	include 'config.php';
	
	
	function getOptionTotal($menu_id){
		$total=0;
	
		$q=mysql_query("select sum(price) as total from reservation_menu_option where menu_id='".$menu_id."' and reservation_unique_id='".$_COOKIE['takeaway_id']."'");
		$r=mysql_fetch_assoc($q);
		
		if(mysql_num_rows($q)>0){
			$total = $r['total'];
		}
		
		return $total;
	}
	
	function getMenuDescription($id){
		$q=mysql_query("select description from menu where id='".$id."'");
		$r=mysql_fetch_assoc($q);
		
		return strip_tags($r['description']);
	}
	
	function checkUpdates($id,$menuid){
		$count=1;
		
		$q=mysql_query("select notes from reservation_detail where id='".$id."'");
		$r=mysql_fetch_assoc($q);
		
		if($r['notes']!=''){
			$count+=1;
		}
		
		$qq=mysql_query("select id from reservation_menu_option where menu_id='".$menuid."' and reservation_unique_id='".$_COOKIE['takeaway_id']."' and menu_option_id<>0");
		
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
	
 
	$q=mysql_query("select * from reservation_detail where reservation_id=(select id from reservation where deleted=0 and uniqueid='".$_COOKIE['takeaway_id']."' order by id desc limit 1)") or die(mysql_error());
	
	if(mysql_num_rows($q)>0){
?>
    <table>
        <?php
            
            $total=0;
        
            while($r=mysql_fetch_assoc($q)){
				
				//for the mandatory tillvals
				$qtill = mysql_query("select id from menu_option_details where menu_id='".$r['menu_id']."' and single_option=1") or die(mysql_error());
				$count_tillvals = mysql_num_rows($qtill);
				//end tillvals
				
        ?>
        <tr>
            <td>
                <span class="inputs">						
                    <input type="button" value="" class="addme_takeaway" data-id="<?php echo $r['id'];?>" data-rel="<?php echo $r['menu_id']; ?>" data-tillvals="<?php echo $count_tillvals; ?>">
                    <input type="text" class="quantity-takeaway quantity-<?php echo $r['id'];?>" placeholder="0" value="<?php echo $r['quantity']; ?>" data-id="<?php echo $r['id'];?>" data-rel="<?php echo $r['menu_id']; ?>">
                    <input type="button" value="" class="subtractme_takeaway" data-id="<?php echo $r['id'];?>" data-rel="<?php echo $r['menu_id']; ?>">
                </span>
            </td>
            <td><span class="takeaway_cart_dish_name"><?php echo getMenuName($r['menu_id']);?></span></td>
            <td><span class="takeaway_cart_dish_price"><?php echo $theprice=(($r['price']*$r['quantity'])+getOptionTotal($r['menu_id'])).' '.getCurrentCurrency();?></span></td>
            <td><input type="button" value="" class="btn_optional_dish btn_optional_dish-<?php echo $r['id'];?>" data-id="<?php echo $r['id'];?>" alt="Modifiera" title="Modifiera" data-rel="<?php echo getMenuName($r['menu_id']).'<span>'.getMenuDescription($r['menu_id']).'</span>';?>" style="opacity:<?php echo checkUpdates($r['id'],$r['menu_id']); ?>" data-price="<?php echo $r['price'];?>"></td>
        </tr>
        <?php
                $total+=$theprice;
            }
        ?>
        <tr>
            <td colspan="4" style="text-align:right;"><span class="total-price">Summa: <?php echo $total.' '.getCurrentCurrency();?></span></td>
        </tr>
        
         <tr>
            <td colspan="4"><a href="<?php echo $site_url; ?>/checkout?type=takeaway" class="btn_check_out" data-rel="<?php echo $total; ?>">Till Kassan </a></td>
        </tr>
        
    </table>
<?php
	}
	else{
		echo '<span style="margin: 0 0 0 5px; color:#fff;">Kundvagnen är tom</span>';
	}
	
?>
