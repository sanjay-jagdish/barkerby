<?php session_start();
	include '../config/config.php';

	$id=strip_tags($_POST['id']);
	
?>	

<div class="menu-selected">
	
	<span class="title">Beställda rätter</span>
	<table width="100%">
    	<tr style="background: #ddd;">
        	<td>Typ</td>
        	<td style="padding: 8px 0;">Rätt</td>
            <td>Tillval</td>
            <td>Bild</td>
            <td>Pris</td>
            <td>Antal</td>
            <td>Totalt</td>
            <td>Önskemål</td>
        </tr>
        <?php
        	$q=mysql_query("select m.name as menu, m.image as img, m.price as price, rd.quantity as quantity, cu.shortname as currency, rd.notes as notes, m.type as type, m.discount as discount,m.id as menu_id, rd.lunchmeny as lunchmeny from reservation_detail as rd, menu as m, currency as cu where m.id=rd.menu_id and cu.id=m.currency_id and m.deleted=0 and rd.reservation_id='".$id."' and rd.lunchmeny=0") or die(mysql_error());
			$count=0;
			$total=0;
			$currency='';
			$opt_tot_all = 0;
			while($r=mysql_fetch_assoc($q)){
				$count++;
				$opt_tot = 0;
				$price=$r['price'];
				if(strlen($r['type'])>1){
					
					$discount=$r['discount']/100;
					$price=$price-($price*$discount);
				}
				
				
				$images='src="images/no-photo-available.png" width="30"';
				if($r['img']!=''){
					$images='src="uploads/'.$r['img'].'" width="60"';
				}
				
				$subtotal=$price*$r['quantity'];
				
		?>
        <tr style="background:<?php if($count%2==0) echo '#ddd'; else echo '#f8f8f8';?>" class="menuvalues">
        	<td><?php 
				$lunchmeny = 'Take away';
				if($r['lunchmeny']==1){
					$lunchmeny = 'Lunch Meny';
				}
				echo strip_tags($lunchmeny);?></td>
        	<td width="200px" align="left"><?php echo '<div style="padding:0 10px;">'.$r['menu'].'</div>'; ?></td>
            <td>
            <?php
            	
					$opt_sql = "select a.name as name,a.price as price, dish_num from reservation_menu_option as a where reservation_unique_id = '$uniqueid' and a.menu_id=$r[menu_id] order by dish_num";
					
					$opt_query = mysql_query($opt_sql) or die(mysql_error());
					if(mysql_num_rows($opt_query)>0){
						echo '<div style="text-align:left; padding:10px 5px 10px; font-size:12px;">';
						$counter = 0;
						while($opt = mysql_fetch_assoc($opt_query)){
								$opt_tot += $opt['price'];
								if($counter<$opt['dish_num']){
									$counter = $counter+1;
									if($counter > 1){
										echo '<br />';
									}
									echo 'Portion #'.$counter;
									
								}
								if($opt['price']==0){
									$opt_price = '0 kr';
								}else{
									$opt_price = number_format($opt['price'],0).' kr';
								}
								echo '<div class="invoice-info" style="padding-top:5px;"><em>';
								echo '<div><span>'.$opt['name'].'</span><label>'.$opt_price.'</label></div>';
								echo '</em></div>';
							}
						echo '</div>';
					}
					$subtotal +=$opt_tot;
			?>
            </td>
            <?php /*?><td><?php echo $r['sub'].' - '.getCategoryName($r['cid']); ?></td><?php */?>
            <td><img <?php echo $images; ?> ></td>
            <td><?php echo $price.' kr'; ?></td>
            <td><?php echo $r['quantity']; ?></td>
            <td><?php echo $subtotal.' kr'; ?></td>
            <td width="150" align="left"><?php echo $r['notes']; ?></td>
        </tr>	
        <?php		
				$total+=$subtotal;
				$currency= 'kr';
			}
			
			//for lunch meny
			
			$qm=mysql_query("select m.name as menu, m.image as img, rd.price as price, rd.quantity as quantity, cu.shortname as currency, rd.notes as notes, m.type as type, m.discount as discount,m.id as menu_id, rd.lunchmeny as lunchmeny from reservation_detail as rd, menu_lunch_items as m, currency as cu where m.id=rd.menu_id and cu.id=m.currency_id and m.deleted=0 and rd.reservation_id='".$id."' and rd.lunchmeny=1") or die(mysql_error());
			
			while($r=mysql_fetch_assoc($qm)){
				$count++;
				$opt_tot = 0;
				$price=$r['price'];
				if(strlen($r['type'])>1){
					
					$discount=$r['discount']/100;
					$price=$price-($price*$discount);
				}
				
				
				$images='src="images/no-photo-available.png" width="30"';
				if($r['img']!=''){
					$images='src="uploads/'.$r['img'].'" width="60"';
				}
				
				$subtotal=$price*$r['quantity'];
				
		?>
        <tr style="background:<?php if($count%2==0) echo '#ddd'; else echo '#f8f8f8';?>" class="menuvalues">
        	<td><?php 
				$lunchmeny = 'Take away';
				if($r['lunchmeny']==1){
					$lunchmeny = 'Lunch Meny'; 
				}
				echo strip_tags($lunchmeny);?></td>
        	<td width="200px" align="left"><?php echo '<div style="padding:0 10px;">'.strip_tags($r['menu']).'</div>'; ?></td>
            <td>
            <?php
            	
					$opt_sql = "select a.name as name,a.price as price, dish_num from reservation_menu_option as a where reservation_unique_id = '$uniqueid' and a.menu_id=$r[menu_id] order by dish_num";
					
					$opt_query = mysql_query($opt_sql) or die(mysql_error());
					if(mysql_num_rows($opt_query)>0){
						echo '<div style="text-align:left; padding:10px 5px 10px; font-size:12px;">';
						$counter = 0;
						while($opt = mysql_fetch_assoc($opt_query)){
								$opt_tot += $opt['price'];
								if($counter<$opt['dish_num']){
									$counter = $counter+1;
									if($counter > 1){
										echo '<br />';
									}
									echo 'Portion #'.$counter;
									
								}
								if($opt['price']==0){
									$opt_price = '0 kr';
								}else{
									$opt_price = number_format($opt['price'],0).' kr';
								}
								echo '<div class="invoice-info" style="padding-top:5px;"><em>';
								echo '<div><span>'.$opt['name'].'</span><label>'.$opt_price.'</label></div>';
								echo '</em></div>';
							}
						echo '</div>';
					}
					$subtotal +=$opt_tot;
			?>
            </td>
            <?php /*?><td><?php echo $r['sub'].' - '.getCategoryName($r['cid']); ?></td><?php */?>
            <td><img <?php echo $images; ?> ></td>
            <td><?php echo $price.' kr'; ?></td>
            <td><?php echo $r['quantity']; ?></td>
            <td><?php echo $subtotal.' kr'; ?></td>
            <td width="150" align="left"><?php echo $r['notes']; ?></td>
        </tr>	
        <?php		
				$total+=$subtotal;
				$currency= 'kr';
			}
			
			
		?>
        <tr style="font-weight:bold; color:#00000c;">
        	<td colspan="6" style="padding:8px 5px; text-align:right;">Slutsumma :</td>
            <td><?php echo $total+$opt_tot_all.' '.$currency; ?></td>
        </tr>
    </table>
</div>