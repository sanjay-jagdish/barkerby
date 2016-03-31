<?php session_start();
	include '../config/config.php';
	$popup = $_POST['popup'];
	$q=mysql_query("select time_to_sec(timediff(now(),last_update)) from reservation order by last_update desc limit 1");
	if(mysql_num_rows($q)>0){
	$r=mysql_fetch_array($q);
		if($r[0]<=2 || $popup=='open'){?>
<div class="bongs">
<?php //need actions
	
	$qq=mysql_query("select rt.description as descrip, concat(a.fname,' ',a.lname) as name, r.payment_mode as payment_mode, r.kco_payment as kco_payment, DATE_FORMAT(STR_TO_DATE(r.date, '%m/%d/%Y'),'%b %d, %Y') as date, DATE_FORMAT(r.time,'%k:%i') as time, r.number_people as numpeople, r.number_table as numtable, r.approve as approve, r.id as rid, rt.id as rtid, r.lead_time as lead, r.acknowledged as ack, r.viewed as viewed, r.date_time as datetime, r.asap as asap, r.asap_datetime as asaptime, r.deliver as deliver,r.uniqueid, r.reservation_type_id as resid,r.bongs_status as bongs, datediff(STR_TO_DATE(concat(r.date,r.time), '%m/%d/%Y %H:%i'), now()) as count_days from reservation_type as rt, account as a, reservation as r where rt.id=r.reservation_type_id and a.id=r.account_id and r.deleted=0 and r.acknowledged = 0 and rt.id in (2,3) and r.processed=0 and a.deleted=0 order by r.viewed asc, r.date asc ,r.time asc") or die(mysql_error());
	
	$count=0;
	$rowcount = 0;
	while($r=mysql_fetch_assoc($qq)){
	
		if($r['payment_mode']=='klarna' && $r['kco_payment']==0){
			//do not include for display		
		}else{
			$count++;
			
			if($count>3){
				$count = 1;
				$rowcount++;
			}
			
			$uniqueid = $r['uniqueid'];
			$bongs = $r['bongs'];
			$countdays = $r['count_days'];
			
			
			$q=mysql_query("select description from status where deleted=0 and id='".$r['approve']."'");
			$rst=mysql_fetch_assoc($q);
			
			if($rst['description']=="Cancel"){
				$class = 'cancel-process';
			}else{
				$class = 'on-processed';
			}
			if(!empty($r['asaptime'])){
				$thetime = $r['asaptime'];
			}else{
				$thetime = date('H:i',strtotime($r['time']));
			}
							
			$d1 = date('y-m-d',  strtotime($r['date'])).' '.$thetime;
			$d2 = date('y-m-d H:i');
						 
			if($d1 > $d2){
				$glomthamta = '';
				$glom = false;
			}else{
				$glomthamta = ' glomthamta';
				$glom = true;
			}
	 ?>
		
			<div class="<?php echo 'bong grp-'.$rowcount;?> <?php echo ($rst['description']=="Cancel")? 'order-cancel ':''; echo ($bongs==1) ? 'on-progress ':'pending '; echo ($r['approve']!=8) ? ' has-time ': ' no-time '; echo ($r['viewed']==0) ? ' new-blink ' : 'viewed'; ?> gradeX gradeX-<?php echo $r['rid']; echo ($countdays<0) ? ' glomthamta':' agoy'; echo $glomthamta;?>">
				
				<?php if($r['approve']!=8){?>
					<div class="left">
						<label class="checkbox">Startad<br>
						<input type="checkbox" onclick="proCheck(<?php echo $r['rid'];?>)" data-rel="<?php echo $r['rid'];?>" <?php echo ($bongs==1) ? 'checked ':''?> <?php echo ($r['viewed']==0) ? 'disabled="disabled"' : ''; ?> class="<?php echo $class;?> chck-<?php echo $r['rid'];?>">
						<span data-rel="<?php echo $r['rid'];?>"  class="<?php echo $class;?>"></span></label>
					</div>
					<div class="right">
						<label class="checkbox">Spättat<br>
						<input type="checkbox" <?php echo (($bongs==0) && ($rst['description']!="Cancel")) ? 'disabled="disabled"' : ''; ?> onclick="puttoarchive(<?php echo $r['rid']; ?>)" class="toachive-<?php echo $r['rid'];?>">
						<span></span>
						</label>
					</div>
				<?php }else{ ?>
					<div class="left"><label class="checkbox">Startad<br><input type="checkbox" disabled="disabled"><span></span></label></div>
					<div class="right"><label class="checkbox">Spättat<br><input type="checkbox" disabled="disabled"><span></span></label></div> 
			   
				<?php }?>
				
				<div class="pointer" onclick="showDetail('<?php echo $r['rid'];?>','<?php echo $r['rtid'];?>')">
					<div class="pickuptime" >
						<?php
							$days = array( 'Sön','Mån', 'Tis', 'Ons', 'Tor', 'Fre', 'Lör');
							$integer = idate('w',  strtotime($r['date']));
							$swday = $days[$integer];
							
							if(!$glom){
								if($countdays==0){
									$prefx='<h4>Idag</h4>'.$swday.', <span>'.$thetime.'</span>'; 
								}else if($countdays==1){
									$prefx='<h4>Imorgon</h4>'.$swday.', <span>'.$thetime.'</span>'; 
								}else if($countdays<0){
									/*if($countdays==-1){
										$prefx='<h4>Igår</h4>'.$swday.', <span>'.$r['asaptime'].'</span>'; 
									}
									else{*/
										//$prefx='Senaste '.abs($countdays).' dagar';
										$prefx='<h4></h4>'.date('d M',  strtotime($r['date'])).', <span>'.$thetime.'</span>';
										/*Glömt hämta?
										26 Juni [Day + Month], 19:03 [Time]*/
									//}
								}else{
									$prefx='<h4>Om '.$countdays.' dagar</h4>'.date('d M',  strtotime($r['date'])).', <span>'.$thetime.'</span>'; 
								}
							}else{
								$prefx='<h4></h4>'.date('d M',  strtotime($r['date'])).', <span>'.$thetime.'</span>';	
							}
							
							
							if($r['approve']!=8){
								if($r['asap']==0){
									echo $prefx;
								}
								else{
									//$asaptime = explode(' ',$r['asaptime']);
									//$time = explode(':',$asaptime[1]);
									echo $prefx;
								}
							}
							else{
								
								if($r['asap']==0){
									
									echo $prefx;
								}
								else{
									echo '<h4>Snarast</h4>';
								}
								
							}
						?>
					</div>
					<div class="table-scroll">
					<table width="100%">
						<?php
							$q=mysql_query("select m.name as menu, m.image as img, m.price as price, rd.quantity as quantity, cu.shortname as currency, rd.notes as notes, m.type as type, m.discount as discount,m.id as menu_id, rd.lunchmeny as lunchmeny from reservation_detail as rd, menu as m, currency as cu where m.id=rd.menu_id and cu.id=m.currency_id and m.deleted=0 and rd.reservation_id=$r[rid] and rd.lunchmeny=0") or die(mysql_error());
							
							
							if(mysql_num_rows($q)==0){
							
							$q=mysql_query("select m.name as menu, m.image as img, rd.price as price, rd.quantity as quantity, cu.shortname as currency, rd.notes as notes, m.type as type, m.discount as discount,m.id as menu_id, rd.lunchmeny as lunchmeny from reservation_detail as rd, menu_lunch_items as m, currency as cu where m.id=rd.menu_id and cu.id=m.currency_id and m.deleted=0 and rd.reservation_id=$r[rid] and rd.lunchmeny=1") or die(mysql_error());
							}
							
							while($rd=mysql_fetch_assoc($q)){
						?>
						
						<tr class="menuvalues">
							<td class="quantity"><?php echo $rd['quantity']; ?></td>
							<td>x</td>
							<td width="200px" align="left"><?php echo $rd['menu']; ?></td>
							<td width="150" align="left" class="notes">
								<?php
								
									$opt_sql = "select a.name as name,a.price as price, dish_num from reservation_menu_option as a where reservation_unique_id = '$uniqueid' and a.menu_id=$rd[menu_id] order by dish_num";
									
									$opt_query = mysql_query($opt_sql) or die(mysql_error());
									if(mysql_num_rows($opt_query)>0){
										echo '<div style="text-align:left; padding:10px 5px 10px;">';
										$counter = 0;
										while($opt = mysql_fetch_assoc($opt_query)){
												$opt_tot += $opt['price'];
												if($counter<$opt['dish_num']){
													$counter = $counter+1;
													if($counter >=2){
														echo '<br />';
													}
													if($counter>1 || $rd['quantity']>1){echo 'Portion #'.$counter;}
													
												}
												echo '<div class="invoice-info" style="padding:5px 0;"><em>';
												echo ''.$opt['name'].'</em></div>';
											}
										echo '</div>';
									}
							?>
							</td>
						</tr>
						<tr><td style="border-bottom:1px solid #ccc; padding-bottom:10px;" colspan="4" align="left" class="notes"><?php echo $rd['notes']; ?></td></tr>
						<?php
							}
						?>
					</table>
					</div>
				</div>
			</div>
	 <?php 
			}
 	}  //end while
 
 ?>
<?php // already had an action
	$qq=mysql_query("select rt.description as descrip, concat(a.fname,' ',a.lname) as name, DATE_FORMAT(STR_TO_DATE(r.date, '%m/%d/%Y'),'%b %d, %Y') as date, date_format(r.time,'%H:%i') as time, r.number_people as numpeople, r.number_table as numtable, r.approve as approve, r.id as rid, rt.id as rtid, r.lead_time as lead, r.acknowledged as ack, r.viewed as viewed, r.date_time as datetime, r.asap as asap, date_format(r.asap_datetime,'%H:%i') as asaptime, r.deliver as deliver,r.uniqueid, r.reservation_type_id as resid,r.bongs_status as bongs, datediff(STR_TO_DATE(concat(r.date,r.time), '%m/%d/%Y %H:%i'), now()) as count_days, r.payment_mode as payment_mode, r.kco_payment as kco_payment from reservation_type as rt, account as a, reservation as r where rt.id=r.reservation_type_id and a.id=r.account_id and r.deleted=0 and r.acknowledged = 1 and rt.id in (2,3) and r.processed=0 and a.deleted=0 order by r.date asc,r.time asc,  r.asap_datetime asc") or die(mysql_error());
	
	//$count=0;
	//$rowcount = 0;
	while($r=mysql_fetch_assoc($qq)){
		
		
		if($r['payment_mode']=='klarna' && $r['kco_payment']==0){
			//do not include for display		
		}else{
		
		$count++;
		
		if($count>3){
			$count = 1;
			$rowcount++;
		}
		
		$uniqueid = $r['uniqueid'];
		$bongs = $r['bongs'];
		$countdays = $r['count_days'];
		
		
		$q=mysql_query("select description from status where deleted=0 and id='".$r['approve']."'");
		$rst=mysql_fetch_assoc($q);
		
		if($rst['description']=="Cancel"){
			$class = 'cancel-process';
		}else{
			$class = 'on-processed';
		}
		if(!empty($r['asaptime'])){
			$thetime = $r['asaptime'];
		}else{
			$thetime = date('H:i',strtotime($r['time']));
		}
						
		$d1 = date('y-m-d',  strtotime($r['date'])).' '.$thetime;
		$d2 = date('y-m-d H:i');
					 
		if($d1 > $d2){
			$glomthamta = '';
			$glom = false;
		}else{
			$glomthamta = ' glomthamta';
			$glom = true;
		}
 ?>
	
		<div class="<?php echo 'bong grp-'.$rowcount;?> <?php echo ($rst['description']=="Cancel")? 'order-cancel ':''; echo ($bongs==1) ? 'on-progress ':'pending '; echo ($r['approve']!=8) ? ' has-time ': ' no-time '; echo ($r['viewed']==0) ? ' new-blink ' : 'viewed'; ?> gradeX gradeX-<?php echo $r['rid']; echo ($countdays<0) ? ' glomthamta':' agoy'; echo $glomthamta;?>">
			
			<?php if($r['approve']!=8){?>
				<div class="left">
					<label class="checkbox">Startad<br>
					<input type="checkbox" onclick="proCheck(<?php echo $r['rid'];?>)" data-rel="<?php echo $r['rid'];?>" <?php echo ($bongs==1) ? 'checked ':''?> <?php echo ($r['viewed']==0) ? 'disabled="disabled"' : ''; ?> class="<?php echo $class;?> chck-<?php echo $r['rid'];?>">
					<span data-rel="<?php echo $r['rid'];?>"  class="<?php echo $class;?>"></span></label>
				</div>
				<div class="right">
					<label class="checkbox">Spättat<br>
					<input type="checkbox" <?php echo (($bongs==0) && ($rst['description']!="Cancel")) ? 'disabled="disabled"' : ''; ?> onclick="puttoarchive(<?php echo $r['rid']; ?>)" class="toachive-<?php echo $r['rid'];?>">
					<span></span>
					</label>
				</div>
			<?php }else{ ?>
				<div class="left"><label class="checkbox">Startad<br><input type="checkbox" disabled="disabled"><span></span></label></div>
				<div class="right"><label class="checkbox">Spättat<br><input type="checkbox" disabled="disabled"><span></span></label></div> 
		   
			<?php }?>
			
			<div class="pointer" onclick="showDetail('<?php echo $r['rid'];?>','<?php echo $r['rtid'];?>')">
				<div class="pickuptime" >
					<?php
						$days = array( 'Sön','Mån', 'Tis', 'Ons', 'Tor', 'Fre', 'Lör');
						$integer = idate('w',  strtotime($r['date']));
						$swday = $days[$integer];
						
						if(!$glom){
							if($countdays==0){
								$prefx='<h4>Idag</h4>'.$swday.', <span>'.$thetime.'</span>'; 
							}else if($countdays==1){
								$prefx='<h4>Imorgon</h4>'.$swday.', <span>'.$thetime.'</span>'; 
							}else if($countdays<0){
								/*if($countdays==-1){
									$prefx='<h4>Igår</h4>'.$swday.', <span>'.$r['asaptime'].'</span>'; 
								}
								else{*/
									//$prefx='Senaste '.abs($countdays).' dagar';
									$prefx='<h4></h4>'.date('d M',  strtotime($r['date'])).', <span>'.$thetime.'</span>';
									/*Glömt hämta?
									26 Juni [Day + Month], 19:03 [Time]*/
								//}
							}else{
								$prefx='<h4>Om '.$countdays.' dagar</h4>'.date('d M',  strtotime($r['date'])).', <span>'.$thetime.'</span>'; 
							}
						}else{
							$prefx='<h4></h4>'.date('d M',  strtotime($r['date'])).', <span>'.$thetime.'</span>';	
						}
						
						
						if($r['approve']!=8){
							if($r['asap']==0){
								echo $prefx;
							}
							else{
								//$asaptime = explode(' ',$r['asaptime']);
								//$time = explode(':',$asaptime[1]);
								echo $prefx;
							}
						}
						else{
							
							if($r['asap']==0){
								
								echo $prefx;
							}
							else{
								echo '<h4>Snarast</h4>';
							}
							
						}
					?>
				</div>
				<div class="table-scroll">
				<table width="100%">
					<?php
						$q=mysql_query("select m.name as menu, m.image as img, m.price as price, rd.quantity as quantity, cu.shortname as currency, rd.notes as notes, m.type as type, m.discount as discount,m.id as menu_id, rd.lunchmeny as lunchmeny from reservation_detail as rd, menu as m, currency as cu where m.id=rd.menu_id and cu.id=m.currency_id and m.deleted=0 and rd.reservation_id=$r[rid] and rd.lunchmeny=0") or die(mysql_error());
						
						
						if(mysql_num_rows($q)==0){
						
						$q=mysql_query("select m.name as menu, m.image as img, rd.price as price, rd.quantity as quantity, cu.shortname as currency, rd.notes as notes, m.type as type, m.discount as discount,m.id as menu_id, rd.lunchmeny as lunchmeny from reservation_detail as rd, menu_lunch_items as m, currency as cu where m.id=rd.menu_id and cu.id=m.currency_id and m.deleted=0 and rd.reservation_id=$r[rid] and rd.lunchmeny=1") or die(mysql_error());
						}
						
						while($rd=mysql_fetch_assoc($q)){
					?>
					
					<tr class="menuvalues">
						<td class="quantity"><?php echo $rd['quantity']; ?></td>
						<td>x</td>
						<td width="200px" align="left"><?php echo $rd['menu']; ?></td>
						<td width="150" align="left" class="notes">
							<?php
							
								$opt_sql = "select a.name as name,a.price as price, dish_num from reservation_menu_option as a where reservation_unique_id = '$uniqueid' and a.menu_id=$rd[menu_id] order by dish_num";
								
								$opt_query = mysql_query($opt_sql) or die(mysql_error());
								if(mysql_num_rows($opt_query)>0){
									echo '<div style="text-align:left; padding:10px 5px 10px;">';
									$counter = 0;
									while($opt = mysql_fetch_assoc($opt_query)){
											$opt_tot += $opt['price'];
											if($counter<$opt['dish_num']){
												$counter = $counter+1;
												if($counter >=2){
													echo '<br />';
												}
												if($counter>1 || $rd['quantity']>1){echo 'Portion #'.$counter;}
												
											}
											echo '<div class="invoice-info" style="padding:5px 0;"><em>';
											echo ''.$opt['name'].'</em></div>';
										}
									echo '</div>';
								}
						?>
						</td>
					</tr>
					<tr><td style="border-bottom:1px solid #ccc; padding-bottom:10px;" colspan="4" align="left" class="notes"><?php echo $rd['notes']; ?></td></tr>
					<?php
						}
					?>
				</table>
				</div>
			</div>
		</div>
 <?php } 
	} //end while
 
 ?>
</div>
<?php		
	}
	else{
		echo 'test';	
	}
}else{
	echo 'test';	
}
?>