<?php
	include 'config.php';
	
	
	$em= mysql_real_escape_string(strip_tags($_POST['em']));
	$pw= mysql_real_escape_string(strip_tags($_POST['pw']));
	$datetime= mysql_real_escape_string(strip_tags($_POST['datetime']));
	$paymenttype= mysql_real_escape_string(strip_tags($_POST['paymenttype']));
	$uniq= mysql_real_escape_string(strip_tags($_POST['uniq']));
	$asap= mysql_real_escape_string(strip_tags($_POST['asap']));
	$deliver= mysql_real_escape_string(strip_tags($_POST['deliver']));
	$totalprice= mysql_real_escape_string(strip_tags($_POST['totalprice']));
	$cart_opt = $_POST['cart_opt'];
	
	$getnow = date('Y-m-d H:i:s');
	
	$date='';
	$time='';
	if($datetime!=''){
		
		$str=explode(' ',$datetime);
		
		$date=formatDate($str[0]);
		$time=$str[1];
	}
	
	//2014-09-12 14:43 format
	
	if($asap==1){
		$date = formatDate(date('Y-m-d'));
		$time = date('H:i');
	}
	
	
	$q=mysql_query("select id, confirmed from account where email='".$em."' and password='".md5($pw)."' and type_id=5 and deleted=0 order by id desc limit 1") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		
		$r=mysql_fetch_assoc($q);
		
		$id=$r['id'];
		$confirmed=$r['confirmed'];
		
		 if($confirmed==1){
		
			//mysql_query("insert into reservation(reservation_type_id,account_id,date,time,date_time,payment_mode,approve) values(2,".$id.",'".$date."','".$time."',now(),'".$paymenttype."',8)");
			
			//$reservation_id=mysql_insert_id();
			
			
			mysql_query("update reservation set account_id='".$id."', date='".$date."', time='".$time."', date_time='".$getnow."', payment_mode='".$paymenttype."', approve=8, asap='".$asap."', deliver='".$deliver."', total_price='".$totalprice."' where uniqueid='".$uniq."'") or die(mysql_error());
			
			if($paymenttype=='invoice'){
				mysql_query("insert into invoice set 
						reservation_unique_id = '".$uniq."',
						business = '".$_COOKIE['inv_business']."',
						org_number = '".$_COOKIE['inv_orgnum']."',
						address = '".$_COOKIE['inv_address']."',
						zip = '".$_COOKIE['inv_zip']."', 
						location = '".$_COOKIE['inv_location']."'
					") or die(mysql_error());
			}
			if($deliver==1){
				mysql_query("insert into reservation_delivery set 
						reservation_unique_id = '".$uniq."',
						d_name = '".$_COOKIE['d_name']."',
						d_mobile = '".$_COOKIE['d_mobile']."',
						d_address = '".$_COOKIE['d_address']."',
						d_buzz = '".$_COOKIE['d_buzz']."', 
						d_other = '".$_COOKIE['d_other']."'
					") or die(mysql_error());
			}
			if($cart_opt!=''){
				echo $str = str_replace('opt_','',$cart_opt);
				//mysql_query("insert into reservation_menu_option set name = '$str'");
				$opt_arr = explode(',',$str);
				foreach($opt_arr as $val){
					$option = explode('-',$val);
					$dish_num = $option[0];
					$opt_id = $option[1];
					$opt_q = mysql_query("select * from menu_options where id = $option[1]");
					$opt_r = mysql_fetch_assoc($opt_q);
					
					if(mysql_num_rows($opt_r) > 0){
						mysql_query("insert into reservation_menu_option set menu_option_id = '$opt_id', reservation_unique_id = '$uniq',name = '$opt_r[name]', menu_id = $opt_r[menu_id], price = $opt_r[price], dish_num = $option[0]") or die(mysql_error());
					}
					
				}
			}
			/*
			$qq=mysql_query("select id, DATE_FORMAT(STR_TO_DATE(date, '%m/%d/%Y'),'%b %d, %Y') as thedate, DATE_FORMAT(time,'%k:%i') as time from reservation where uniqueid='".$uniq."'");
			$rr=mysql_fetch_assoc($qq);
			
			//added for mailing
			
			$formsg='';
			$qr=mysql_query("select m.name as menu, rd.quantity as quantity, rd.notes as notes from reservation_detail as rd, menu as m where m.id=rd.menu_id and rd.reservation_id='".$rr['id']."'");
			while($rq=mysql_fetch_assoc($qr)){
				
				$formsg.='Maträtt: '.$rq['menu'].' <br> 
						  Antal portioner: '.$rq['quantity'].' <br> 
						  Övriga önskemål: '.$rq['notes'].' <br><br>';
				
			}
			
			
			$q=mysql_query("select fname,lname from account where id='".$id."'");
			$r=mysql_fetch_assoc($q);
			
			require 'PHPMailer/PHPMailerAutoload.php';	
				
			$to = $em;
				
			$name=$r['fname'].' '.$r['lname'];
						
			$subject = 'Bekräftelse - Take away';
				
			// message
			$message = "
				<html>
					<body>
						Hej ".$r['fname']."! <br><br>
 
						Tack för din beställning. Nedan följer en sammanställning av din order. <br><br>
						
						".$formsg."
						
						Klar för avhämtning: ".$rr['thedate'].' '.$rr['time']." <br><br>
						 
						Välkommen till Limone Ristorante Italiano! <br><br>
						 
						Stora gatan 4 <br>
						021-417560 <br>
					</body>
				</html>
			";
				
				
			$mail = new PHPMailer();
			$mail->isSMTP();
			$mail->SMTPDebug = 0;
			$mail->Host = $garcon_settings['smtp_host'];
			$mail->Port = $garcon_settings['smtp_port'];
			$mail->SMTPSecure = $garcon_settings['smtp_security'];
			$mail->SMTPAuth = true;
			$mail->Username = $garcon_settings['smtp_user'];
			$mail->Password = $garcon_settings['smtp_pass'];
			$mail->setFrom($garcon_settings['smtp_user'], $garcon_settings['smtp_from']);
			$mail->Subject = $subject;
			$mail->msgHTML($message);
		
			$mail->Subject = $subject;
			
			$mail->msgHTML($message);
			
			$mail->AddAddress($to, $name);
			$mail->send();*/
			
			//email to be sent to the user
			
			/*$q=mysql_query("select fname,lname from account where id='".$id."'");
			$r=mysql_fetch_assoc($q);*/
			
			//require 'PHPMailer/PHPMailerAutoload.php';	
				
			/*$to = $em;
				
			$name=$r['fname'].' '.$r['lname'];
						
			$subject = 'Bekräftelse';
				
			// message
			$message = "
				<html>
					<body>
						Hej ".$r['fname'].' '.$r['lname'].", <br><br>
 
						Vi har nu tagit emot din beställning. Du kommer inom kort att få ett nytt mail med beräknad tid för när din beställning är klar för avhämtning.<br><br>
						 
						Välkommen till Limone Ristorante Italiano! <br><br>
						 
						Stora gatan 4 <br>
						021-417560 <br>
					</body>
				</html>
			";
				
				
			 $mail = new PHPMailer();
			 $mail->CharSet = 'UTF-8';
			 $mail->isSMTP();
			 $mail->SMTPDebug = 0;
			 $mail->Host = $garcon_settings['smtp_host'];
			 $mail->Port = $garcon_settings['smtp_port'];
			 $mail->SMTPSecure = $garcon_settings['smtp_security'];
			 $mail->SMTPAuth = true;
			 $mail->Username = $garcon_settings['smtp_user'];
			 $mail->Password = $garcon_settings['smtp_pass'];
			 $mail->setFrom($garcon_settings['smtp_user'], $garcon_settings['smtp_from']);
			 $mail->Subject = $subject;
			 $mail->msgHTML($message);
		
			 $mail->Subject = $subject;
			
			 $mail->msgHTML($message);
			
			 $mail->AddAddress($to, $name);
			 $mail->send();*/
			 
			 
			
			
			//email for the admin
			
			
			require 'PHPMailer/PHPMailerAutoload.php';	
			
			 function theOrderType($id){
				$q=mysql_query("select if(rd.lunchmeny=0,' (À la carte)', if(rd.lunchmeny=2, ' (Frukost)', ' (Lunch)') ) as thetype from reservation as r, reservation_detail as rd where r.id=rd.reservation_id and rd.reservation_id='".$id."'") or die(mysql_error());
				$r=mysql_fetch_assoc($q);
				
				return $r['thetype'];
			}
	
				$qi=mysql_query("select id from reservation where uniqueid='".$uniq."' and deleted=0");
				$ri=mysql_fetch_assoc($qi) or die(mysql_error());
	
				$id=$ri['id'];
				
				
				$q=mysql_query("select concat(a.fname,' ',a.mname,' ',a.lname) as name, rt.description as descrip, r.approve as approve, r.date as date, r.time as time, r.number_people as numpeople, r.number_table as numtable, r.approve_by as appby, r.note as note, r.date_time as datetime, r.reservation_type_id as resid, r.uniqueid as uniqueid, r.reason as reason, a.email as email, a.mobile_number as mob, a.phone_number as phone, a.street_name as street, r.lead_time as lead, r.acknowledged as ack,a.city as city,  r.deliver as deliver, a.state as state, a.zip as zip, a.country as country, r.asap as asap, r.payment_mode as payment, datediff(STR_TO_DATE(concat(r.date,r.time), '%m/%d/%Y %H:%i'), now()) as count_days, r.asap_datetime as asapdatetime from reservation as r, reservation_type as rt, account as a where a.id=r.account_id and rt.id=r.reservation_type_id and r.id=".$id) or die(mysql_error());
	
	
				$r=mysql_fetch_assoc($q);
				
				$customer=$r['name'];
				$type=$r['descrip'];
				$status=$r['approve'];
				$date=$r['date'];
				$time=$r['time'];
				$num_people=$r['numpeople'];
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
					
					$address.=$comma.$city;
				}
				if($state!=''){
					
					$comma='';
										
					if($address!=''){
						$comma=',';
					}
					
					$address.=$comma.$state;
				}
				if($zip!=''){
					$comma='';
										
					if($address!=''){
						$comma=',';
					}
					
					$address.=$comma.$zip;
				}
				if($country!=''){
					$comma='';
										
					if($address!=''){
						$comma=',';
					}
					
					$address.=$comma.$country;
				}
			
			?>
            <?php	
			$themsg='<style>
				table tr td p{
					padding:0 0 15px;
					margin:0;
				}
				table#table-1{
					float:left;
					width:100%;	
					font-size:16px;
				}
				table#table-2{
					border-top:1px solid #000;
					height:300px;
				}
				table#table-2 tr td{
					padding:5px;
				}
			</style>';
			
			?>
			<?php
            	$themsg.='<table id="table-1" style="float:left; width:100%;">
					<tr>
						<td style="width:48%;" valign="top">';
					?>			
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
								
								$convertdayname=array('mon'=>'Mån','tue'=>'Tis','wed'=>'Ons','thu'=>'Tor','fri'=>'Fre','sat'=>'Lör','sun'=>'Sön');
								
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
								
								$thedayname = $convertdayname[strtolower(date("D",strtotime($date)))];
								
								$mm = $swedish_months[$mm-1];
								
								$dateonly = $dd.' '.$mm.' '.$yy;
							?>
							 <?php //$themsg.='<p>Typ av bokning:'; ?> 
								<?php
									/*if($r['deliver']==1){
										$themsg.='Utkörnig'.theOrderType($id);
									}
									else{
										$themsg.=$type.theOrderType($id);
									}*/
								?>
							 <?php //$themsg.='</p>'; ?>
                             
							 <?php $themsg.='<p>Namn: '.$customer.'</p>'; ?>
						 <?php $themsg.='<p>E-post: '.$email.'</p>'; ?>
						 <?php $themsg.='<p>Mobilnummer: '.$number.'</p>'; ?>
						  <?php if($typeID==1){ ?>
						 <?php $themsg.='
						 <p>
							Datum :'.$dateonly.'<br />
							Tid :'.$time.'<br />
							Antal personer :'.$num_people.'<br />
							Antal bord :'.$num_table.'
						 </p>';
						 ?>
						  <?php }else{?>
						 
						 <?php $themsg.='<p><strong>Klar för avhämtning: '; ?>
						 
						 <?php 
							if($date!=''){
								
								$themsg.=$thedayname.', '.$dateonly;
								
							}
							else{
								$themsg.='';
							}
						
							if($time=='00:00:00'){
								$themsg.='';
							}
							else{
								
								
								$thetimes = $thetime;
								
								/*if($asapdatetime!=''){
									$thetime = $asap_date_time;
								}*/
								
								if($asap==1){
									//if($asapdatetime!=''){
									$themsg.= ' klockan '.date("G:i",strtotime($thetimes)); 
									//}	
									/*else{
										//$themsg.='Snarast';
										$themsg.= ' klockan '.date("G:i",strtotime($thetime));
									}*/
								}else{
									$time1 = date("G:i",strtotime($thetimes));
									$themsg.= ' klockan '.$time1;
								}
								
							}
							
							$themsg.='<br />';
							
							$q=mysql_query("select description from status where deleted=0 and id='".$status."'") or die(mysql_error());
							$r=mysql_fetch_assoc($q);
							
							if($r['description'] == 'Cancel'){
								$themsg.='Status: Ej godkänd beställning';
							}
						  ?>
						  
						  <?php $themsg.='</strong></p>'; ?>
						  
						  
						  <?php } ?>
					
					<?php $themsg.='</td>
					<td style="width:48%;" valign="top">'; ?>
					
						<?php $removemsg2 ='<p>Beställningen gjordes: '.$datetime.'</p>'; 
							$themsg.= $removemsg2;
						?>
						
						<?php $themsg.='<p><table><tr><td valign="top">Adress: </td><td>'.$address.'</td></tr></table></p>'; ?>
						<?php $themsg.='<p>Betalsätt: '; ?>
						
						<?php 
							if($payment=='cash'){
								$themsg.='Kort/Kontant';
							}
							else if($payment=='invoice'){
								$themsg.='Faktura<br />';
								$inv_query = mysql_query("select * from invoice where reservation_unique_id = '$uniqueid'") or die(mysql_error());
								if($inv = mysql_fetch_assoc($inv_query)){
									$themsg.='Företag: '.$inv['business'].'<br />';
									$themsg.='Orgnr: '.$inv['org_number'].'<br />';
									$themsg.='Adress: '.$inv['address'].'<br />';
									$themsg.='Postnr/Ort: '.$inv['zip'].'<br />';
									$themsg.='Ref/Idnummer: '.$inv['location'].'';
								}
							}
							else if($payment=='klarna'){
								$themsg.='Klarna Checkout';
							}
					   ?>
					   <?php $themsg.='</p>'; ?>
                       
                      	<?php 
							if($payment=='klarna'){
								$themsg.='<p>Paid: ';
								$themsg .= ($kco==1) ? 'Yes' : 'No';			
								$themsg.='</p>';					
							}
						?>
                       
						<?php $themsg.='<p>'; ?>
						<?php 
							$d_query = mysql_query("select * from reservation_delivery where reservation_unique_id = '$uniqueid'") or die(mysql_error());
							if(mysql_num_rows($d_query)>0){
									$themsg.='Utkörniginformation<br />';
									if($delivery = mysql_fetch_assoc($d_query)){
										$themsg.='Namn: '.$delivery['d_name'].'<br />';
										$themsg.='Mobiltelefon: '.$delivery['d_mobile'].'<br />';
										$themsg.='Adress: '.$delivery['d_address'].'<br />';
										$themsg.='Portkod: '.$delivery['d_buzz'].'<br />';
										$themsg.='Övrig: '.$delivery['d_other'].'<br />';
									}
						} ?>
						<?php $themsg.='</p>
					</td>
				</tr>
			</table>
			<br /><br />'; ?>
			
			<?php if($typeID==2){ ?>
			
			<?php $themsg.='<table id="table-2" cellspacing="0" cellpadding="5" style="min-height:300 !important;">
				<tr>
					<td style="border-bottom:1px solid #000;">Rätt</td>
					<td style="border-bottom:1px solid #000;" width="100">Tillval</td>
					<td style="border-bottom:1px solid #000;" width="105">Önskemål</td>
					<td style="border-bottom:1px solid #000;" width="30">Antal</td>
					<td style="border-bottom:1px solid #000;" width="50">Styckpris</td>
					<td style="border-bottom:1px solid #000;">Totalt</td>
				</tr>'; ?>
				
				<?php
					
					$q=mysql_query("select m.name as menu, m.image as img, rd.price as price, rd.quantity as quantity, cu.shortname as currency, rd.notes as notes, m.type as type, m.discount as discount,m.id as menu_id, rd.lunchmeny as lunchmeny, m.discount_unit as unit from reservation_detail as rd, menu as m, currency as cu where m.id=rd.menu_id and cu.id=m.currency_id and m.deleted=0 and rd.reservation_id=$id and rd.lunchmeny=0") or die(mysql_error());
									
					
					if(mysql_num_rows($q)==0){
					
					$q=mysql_query("select m.name as menu, m.image as img, rd.price as price, rd.quantity as quantity, cu.shortname as currency, rd.notes as notes, m.type as type, m.discount as discount,m.id as menu_id, rd.lunchmeny as lunchmeny from reservation_detail as rd, menu_lunch_items as m, currency as cu where m.id=rd.menu_id and cu.id=m.currency_id and m.deleted=0 and rd.reservation_id=$id and rd.lunchmeny=1") or die(mysql_error());
					
						if(mysql_num_rows($q)==0){
						
							$q=mysql_query("select m.name as menu, m.image as img, rd.price as price, rd.quantity as quantity, cu.shortname as currency, rd.notes as notes, m.type as type, m.discount as discount,m.id as menu_id, rd.lunchmeny as lunchmeny, m.discount_unit as unit, rd.dagenslunch as dagens from reservation_detail as rd, breakfast_menu as m, currency as cu where m.id=rd.menu_id and cu.id=m.currency_id and m.deleted=0 and rd.reservation_id=$id and rd.lunchmeny=2") or die(mysql_error());
						
						}
					
					}
									
					$count=0;
					$total=0;
					$currency='';
					$opt_tot_all = 0;
					while($r=mysql_fetch_assoc($q)){
						$count++;
						$opt_tot = 0;
						$price=$r['price'];
						
						
						$images='../../images/no-photo-available.jpg';
						if($r['img']!=''){
							$images='../../uploads/'.$r['img'];
						}
						
						$subtotal=$price*$r['quantity'];
						
				?>
				
				<?php $themsg.='<tr>
					<td valign="top" width="120">'; ?>
						<?php 
							$themenuname = $r['menu'];
							if($r['dagens']==1){
								$themenuname = 'Dagens Lunch';
							}
							$themsg.= $themenuname; 
						?>
					
					<?php $themsg.='</td>
					<td valign="top" style="" width="120">'; ?>
					
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
												$themsg.='<br />';
											}
											$themsg.='Portion #'.$counter.'<br />';
											
										}
										if($opt['price']==0){
											$opt_price = '0 Kr';
										}else{
											$opt_price = number_format($opt['price'],0).' kr';
										}
										$themsg.='&nbsp;'.$opt['name'].' '.$opt_price.'<br />';
									}
							}
							$subtotal +=$opt_tot;
					?>
					
				<?php  $themsg.='</td>
					<td valign="top" width="120" align="left">'.$r['notes'].'</td>
					<td valign="top" align="center">'.$r['quantity'].'</td>
					<td valign="top">'.$price.' kr'.'</td>
					<td valign="top">'.$subtotal.' kr'.'</td>
				</tr>'; ?>	
				
				<?php		
						$total+=$subtotal;
						$currency='kr';
					}
				?>
				
				<?php $themsg.='<tr style="font-size:16px;">
					<td colspan="5" align="right" style="border-top:1px solid #000;"><strong>Att betala :</strong></td>
					<td style="border-top:1px solid #000;"><strong>'.($total+$opt_tot_all).' '.$currency.'</strong></td>
				</tr>
			</table>'; ?>
                
				<?php } ?>	
			<?php
			
			$qe=mysql_query("select var_value from settings where var_name='copy_email'") or die(mysql_error());
			$re=mysql_fetch_assoc($qe);
			$to = 'grekiska.test@gmail.com';
		   if($re['var_value']!=''){
				$to = $re['var_value'];
			}
			
			//get copy name
			$qn=mysql_query("select var_value from settings where var_name='copy_name'") or die(mysql_error());
			$rn=mysql_fetch_assoc($qn);
				
			$name='';
			
			if($rn['var_value']!=''){
				$name=$rn['var_value'];
			}

						
			$subject = 'Ny beställning';
			
			
			 $mail = new PHPMailer();
			 $mail->CharSet = 'UTF-8';
			 $mail->isSMTP();
			 $mail->SMTPDebug = 0;
			 $mail->Host = $garcon_settings['smtp_host'];
			 $mail->Port = $garcon_settings['smtp_port'];
			 $mail->SMTPSecure = $garcon_settings['smtp_security'];
			 $mail->SMTPAuth = true;
			 $mail->Username = $garcon_settings['smtp_user'];
			 $mail->Password = $garcon_settings['smtp_pass'];
			 $mail->setFrom($garcon_settings['smtp_user'], $garcon_settings['smtp_from']);
			 $mail->Subject = $subject;
			 $mail->msgHTML($themsg);
		
			 $mail->Subject = $subject;
			
			 $mail->msgHTML($themsg);
			
			 $mail->AddAddress($to, $name);
			 $mail->send();
			
			
			//end for admin email	
		
		 }
		 else{
		 	echo 'Not';
		 }
		
		
	}
	else{
		echo 'Invalid';
	}
	
?>
