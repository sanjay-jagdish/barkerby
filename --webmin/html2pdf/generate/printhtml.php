<page style="font-size: 12px; box-sizing:border-box;" backtop="0mm" backbottom="5mm" backleft="2mm" backright="2mm">
<?php session_start();
	include '../../config/config.php';
	session_start();
	
	function processedBy($id){
		if($id!=''){
			
			$q=mysql_query("select concat(fname,' ',mname,' ',lname) from account where id=".$id);
			$r=mysql_fetch_array($q);
			
			return $r[0];
			
		}
		else{
			return '';
		}
	}
	
	function theOrderType($id){
		$q=mysql_query("select if(rd.lunchmeny=0,' (À la carte)',' (Lunch)') as thetype from reservation as r, reservation_detail as rd where r.id=rd.reservation_id and rd.reservation_id='".$id."'");
		$r=mysql_fetch_assoc($q);
		
		return $r['thetype'];
	}
	
	
	$id=strip_tags($_GET['id']);
	$qry = mysql_query(" UPDATE reservation SET `viewed` = '1' WHERE  `id` = ".$id);
	
	$q=mysql_query("select concat(a.fname,' ',a.mname,' ',a.lname) as name, a.fname as fname, a.lname as lname, rt.description as descrip, r.approve as approve, r.date as date, r.time as time, r.number_people as numpeople, r.number_table as numtable, r.approve_by as appby, r.note as note, r.date_time as datetime, r.reservation_type_id as resid, r.uniqueid as uniqueid, r.reason as reason, a.email as email, a.mobile_number as mob, a.phone_number as phone, a.street_name as street, r.lead_time as lead, r.acknowledged as ack,a.city as city,  r.deliver as deliver, a.state as state, a.zip as zip, a.country as country, r.asap as asap, r.payment_mode as payment, datediff(STR_TO_DATE(concat(r.date,r.time), '%m/%d/%Y %H:%i'), now()) as count_days, r.asap_datetime as asapdatetime from reservation as r, reservation_type as rt, account as a where a.id=r.account_id and rt.id=r.reservation_type_id and r.id=".$id) or die(mysql_error());
	
	
	$r=mysql_fetch_assoc($q);
	
	$customer=$r['name'];
	$fname = $r['fname'];
	$lname = $r['lname'];
	$type=$r['descrip'];
	$status=$r['approve'];
	$date=$r['date'];
	$time=$r['time'];
	$num_people=$r['numpeople'];
	$num_table=numberTable($id);
	$accountID=$r['appby'];
	$note=$r['note'];
	$date_time=$r['datetime'];
	$typeID=$r['resid'];
	$reason=$r['reason'];
	$email=$r['email'];
	$mobile=$r['mob'];
	$phone=$r['phone'];
	$customtime=$r['lead'];
	$acknowledged=$r['ack'];
	$street=$r['street'];
	$city=$r['city'];
	$state=$r['state'];
	$zip=$r['zip'];
	$country=$r['country'];
	$asap=$r['asap'];
	$payment=$r['payment'];
	$uniqueid = $r['uniqueid'];
	$countdays = $r['count_days'];
	$asapdatetime = $r['asapdatetime'];
	
	
	$number='';
	if($mobile!='' & $phone!=''){
		$number.=$mobile.' / '.$phone;
	}
	else{
		if($mobile!=''){
			$number=$mobile;
		}
		else if($phone!=''){
			$number=$phone;
		}
	}
	
	$address='';
						
	if($street!=''){
		$address.=$street;
	}
	if($city!=''){
		
		$comma='';
							
		if($address!=''){
			$comma=',';
		}
		
		$address.=$comma.'<br>'.$city;
	}
	if($state!=''){
		$comma='';
							
		if($address!=''){
			$comma=',';
		}
		
		$address.=$comma.'<br>'.$state;
	}
	if($zip!=''){
		$comma='';
							
		if($address!=''){
			$comma=',';
		}
		
		$address.=$comma.'<br>'.$zip;
	}
	if($country!=''){
		$comma='';
							
		if($address!=''){
			$comma=',';
		}
		
		$address.=$comma.'<br>'.$country;
	}
	
?>
<div style="text-align:center; margin-bottom:5px;"><img src="miseenplace.png" width="150" style="margin-left:-8px;"/></div>
<div style="text-align:center;"><img src="http://www.limoneristorante.se/wp-content/themes/crisp_version3/images/limone.png" /></div><br /><br />
<style>
table tr td p{
	padding:0 0 15px;
	margin:0;
}
table#table-1{
	float:left;
	width:100%;	
}
table#table-2{
	height:300px;
}
table#table-2 tr td{
	padding:5px;
}
</style>
<table id="table-1" style="float:left; width:100%;">
	<tr>
    	<td style="width:100%;" valign="top">
        	<?php
				function sum_the_time($time1, $time2) {
				  $times = array($time1, $time2);
				  $seconds = 0;
				  foreach ($times as $time)
				  {
					list($hour,$minute,$second) = explode(':', $time);
					$seconds += $hour*3600;
					$seconds += $minute*60;
					$seconds += $second;
				  }
				  $hours = floor($seconds/3600);
				  $seconds -= $hours*3600;
				  $minutes  = floor($seconds/60);
				  $seconds -= $minutes*60;
				  if($seconds < 9)
				  {
				  $seconds = "0".$seconds;
				  }
				  if($minutes < 9)
				  {
				  $minutes = "0".$minutes;
				  }
					if($hours < 9)
				  {
				  $hours = "0".$hours;
				  }
				  //{$hours}:{$minutes}:{$seconds}
				  return "{$hours}:{$minutes}";
				}
				$swedish_months = array('Januari','Februari','Mars','April','Maj','Juni','Juli','Augusti','September','Oktober','november','December');
				
				$dd = date("d",strtotime($date_time));
				$mm = date("n",strtotime($date_time));
				$yy = date("Y",strtotime($date_time));
				$hrmn = date("G:i",strtotime($date_time));
				$mm = $swedish_months[$mm-1];
				
				$datetime = $dd.' '.$mm.' '.$yy.' '.$hrmn;
				
				$dd = date("d",strtotime($date));
				$mm = date("n",strtotime($date));
				$yy = date("Y",strtotime($date));
				$hrmn = date("G:i",strtotime($date));
				
				$mm = $swedish_months[$mm-1];
				
				$dateonly = $dd.' '.$mm.' '.$yy;
			?>
             <?php /*?><p>Typ av bokning: 
			 	<?php
                	if($r['deliver']==1){
						echo 'Utkörnig'.theOrderType($id);
					}
					else{
						echo $type.theOrderType($id);
					}
				?>
             </p><?php */?>
             <?php /*?><p>Namn: <?php echo $customer;?></p>
             <p>E-post: <?php echo $email;?></p>
             <p>Mobilnummer: <?php echo $number;?></p><?php */?>
              <?php if($typeID==1){ ?>
             <?php /*?><p>
             	Datum :<?php echo $dateonly; ?><br />
                Tid :<?php echo $time; ?><br />
                Antal personer :<?php echo $num_people; ?><br />
                Antal bord :<?php echo $num_table; ?>
             </p><?php */?>
              <?php }else{?>
             <p style="font-size:16px;"><?php 
				if($date!=''){
					echo $dateonly;
				}
				else{
					echo '-';
				}
			?>
            <?php 
			
				if($time=='00:00:00'){
					echo '-';
				}
				else{
					
					if($countdays==0){
						$prefx='Idag';
					}
					else if($countdays==1){
						$prefx='Imorgon';
					}
					else if($countdays<0){
						if($countdays==-1){
							$prefx='Igår';
						}
						else{
							//$prefx='Senaste '.abs($countdays).' dagar';
							$prefx='Passerat datum';
						}
					}
					else{
						$prefx='Om '.$countdays.' dagar';
					}
					
					
					
					$thetime = $time;
					if($asapdatetime!=''){
						$thetime = $asapdatetime;
					}
					
					if($asap==1){
						if($asapdatetime!=''){
							//$prefx.', '.
							echo date("G:i",strtotime($thetime)); 
						}	
						else{
							echo 'Snarast';
						}
					}else{
						//echo $prefx.', '.date("G:i",strtotime($thetime)); 
						echo $time1 = date("G:i",strtotime($thetime));
						//$time2 = '00:30';
						//echo sum_the_time($time1,$time2);
					}
					
				}
			  ?></p>
              <?php }?>
             <?php /*?><p>Bearbetad av: <?php echo processedBy($accountID); ?></p><?php */?>
      <!--  </td>
        <td style="width:48%;" valign="top">-->
        	<?php /*?><p>Bokningen mottagen: <?php echo $datetime;?></p>
            <p><table><tr><td valign="top">Adress:</td><td><?php echo $address;?></td></tr></table></p><?php */?>
            <?php /*?><p>Betalsätt:
            <?php 
				if($payment=='cash'){
					echo 'Kort/Kontant';
				}
				else if($payment=='invoice'){
					echo 'Faktura<br />';
					$inv_query = mysql_query("select * from invoice where reservation_unique_id = '$uniqueid'") or die(mysql_error());
					if($inv = mysql_fetch_assoc($inv_query)){
						echo 'Företag: '.$inv['business'].'<br />';
						echo 'Orgnr: '.$inv['org_number'].'<br />';
						echo 'Adress: '.$inv['address'].'<br />';
						echo 'Postnr/Ort: '.$inv['zip'].'<br />';
						echo 'Ref/Idnummer: '.$inv['location'].'';
					}
				}
		   ?></p><?php */?>
            <?php /*?><p>
            <?php 
				$d_query = mysql_query("select * from reservation_delivery where reservation_unique_id = '$uniqueid'") or die(mysql_error());
				if(mysql_num_rows($d_query)>0){
						echo 'Utkörniginformation<br />';
						if($delivery = mysql_fetch_assoc($d_query)){
							echo 'Namn: '.$delivery['d_name'].'<br />';
							echo 'Mobiltelefon: '.$delivery['d_mobile'].'<br />';
							echo 'Adress: '.$delivery['d_address'].'<br />';
							echo 'Portkod: '.$delivery['d_buzz'].'<br />';
							echo 'Övrig: '.$delivery['d_other'].'<br />';
						}
			} ?>
    		</p><?php */?>
        </td>
    </tr>
</table><hr />
<?php if($typeID==2 || $typeID==3){ ?>
<table id="table-2" cellspacing="0" cellpadding="1" style="min-height:300 !important;">
    <?php
        /*$q=mysql_query("select m.name as menu, c.name as sub, m.image as img, m.price as price, rd.quantity as quantity, cu.shortname as currency, rd.notes as notes, c.id as cid, m.type as type, m.discount as discount,m.id as menu_id from reservation_detail as rd, menu as m, sub_category as c, currency as cu where m.id=rd.menu_id and c.id=m.sub_category_id and cu.id=m.currency_id and rd.reservation_id=".$id) or die(mysql_error());*/
		
		$q=mysql_query("select m.name as menu, m.image as img, m.price as price, rd.quantity as quantity, cu.shortname as currency, rd.notes as notes, m.type as type, m.discount as discount,m.id as menu_id, rd.lunchmeny as lunchmeny, m.discount_unit as unit from reservation_detail as rd, menu as m, currency as cu where m.id=rd.menu_id and cu.id=m.currency_id and m.deleted=0 and rd.reservation_id=$id and rd.lunchmeny=0") or die(mysql_error());
						
		
		if(mysql_num_rows($q)==0){
		
		$q=mysql_query("select m.name as menu, m.image as img, rd.price as price, rd.quantity as quantity, cu.shortname as currency, rd.notes as notes, m.type as type, m.discount as discount,m.id as menu_id, rd.lunchmeny as lunchmeny from reservation_detail as rd, menu_lunch_items as m, currency as cu where m.id=rd.menu_id and cu.id=m.currency_id and m.deleted=0 and rd.reservation_id=$id and rd.lunchmeny=1") or die(mysql_error());
		}
						
        $count=0;
        $total=0;
        $currency='';
        $opt_tot_all = 0;
        while($r=mysql_fetch_assoc($q)){
            $count++;
            $opt_tot = 0;
            $price=$r['price'];
            if(strlen($r['type'])>1){
                if($r['unit']=='percent'){
					$discount=$r['discount']/100;
					$price=$price-($price*$discount);
				}else{
					$price=$price-$r['discount'];
				}
            }
            
            
            $images='../../images/no-photo-available.jpg';
            if($r['img']!=''){
                $images='../../uploads/'.$r['img'];
            }
            
            $subtotal=$price*$r['quantity'];
            
    ?>
    <tr>
    	<td valign="top" align="center" width="10" style="font-size:17px; font-weight:bold;"><?php echo $r['quantity']; ?>x</td>
        <td valign="top" width="120">
        	<div style="font-size:17px; font-weight:bold;"><?php echo $r['menu']; ?></div>
            <div style="font-size:14px;">
            <?php
               
                    $opt_sql = "select a.name as name,a.price as price, dish_num from reservation_menu_option as a where reservation_unique_id = '$uniqueid' and a.menu_id=$r[menu_id]";
                    
                    $opt_query = mysql_query($opt_sql) or die(mysql_error());
                    if(mysql_num_rows($opt_query)>0){
                        $counter = 0;
                        while($opt = mysql_fetch_assoc($opt_query)){
                                $opt_tot += $opt['price'];
                                if($counter<$opt['dish_num']){
                                    $counter = $counter+1;
                                    if($counter > 1){
                                        echo '<br />';
                                    }
                                    echo 'Portion #'.$counter.'<br />';
                                    
                                }
                               /* if($opt['price']==0){
                                    $opt_price = '0 Kr';
                                }else{
                                    $opt_price = number_format($opt['price'],0).' kr';
                                }*/
                               // echo '&nbsp;'.$opt['name'].' '.$opt_price.'<br />';
								echo '<div>&nbsp;'.$opt['name'].'</div>';
                            }
                    }
                    $subtotal +=$opt_tot;
            ?>
            </div>
        </td>
        <td valign="top" width="80" style="font-size:14px;"><?php echo $r['notes']; ?></td>
        
    </tr>
    <?php
        }
    ?>
</table>
<?php } ?>
<hr />
<table style="">
	<tr>
    	<td colspan="3" style="font-weight:bold; font-size:14px;">Namn: <?php echo $customer;?></td>
    </tr>
    <tr>
    	<td colspan="3" style="font-size:14px;">Mobilnummer: <?php echo $number;?></td>
    </tr>
</table>
</page>
<div style="padding-top:30px; font-size:10px;">-
</div>