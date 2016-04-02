<?php session_start();
	include '../config/config.php';
	require 'PHPMailer/PHPMailerAutoload.php';	
	
	function getNameofSignee($id){
		$q=mysql_query("select concat(fname,' ',lname) as name from account where id=(select signatory from reservation_status_history where reservation_id='".$id."' order by id desc limit 1)") or die(mysql_error());
		$r=mysql_fetch_assoc($q);
		
		if(mysql_num_rows($q) > 0){
			return $r['name'];
		}
		else{
			return '';
		}	
	}
	
	function paymentMode($id){
		$q=mysql_query("select payment_mode from reservation where id='".$id."'");
		$r=mysql_fetch_assoc($q);
		
		if($r['payment_mode']=='cash'){
			return 'Kort/Kontant';
		}
		else{
			return 'Faktura';
		}
	}
	
	
	$id=strip_tags($_POST['id']);
	$signatory=strip_tags($_POST['signed']);
	$status=mysql_real_escape_string(strip_tags($_POST['status']));
	
	$default=0;
	$thereason=mysql_real_escape_string(strip_tags($_POST['reason']));
	
	if(isset($thereason)){
		mysql_query("update reservation set approve=".$status.", reason='".$thereason."', approve_by=".$_SESSION['login']['id'].", acknowledged=1 where id=".$id) or die(mysql_error());
	}
	else{
		$default=1;
	}
	
	$default2=0;
	$customtime=mysql_real_escape_string(strip_tags($_POST['customtime']));
	
	if(isset($customtime)){
		
		$ctime=explode(" ",$customtime);
		
		mysql_query("update reservation set approve=".$status.", lead_time='".$ctime[0]."', approve_by=".$_SESSION['login']['id'].", acknowledged=1 where id=".$id) or die(mysql_error());
	}
	else{
		$default2=1;
	}
	
	if($default==1 & $default2==1){
		mysql_query("update reservation set approve=".$status.", reason='', lead_time=0, approve_by=".$_SESSION['login']['id'].", acknowledged=1 where id=".$id) or die(mysql_error());
	}
	mysql_query("INSERT INTO reservation_status_history(date_time,reservation_id,status,account_id,signatory) 
								values(NOW(), ".$id.",".$status.",".$_SESSION['login']['id'].",".$signatory.")");
	mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Set order-status of an order as ".orderStatus($status).".',now(),'".get_client_ip()."')");
	
	//get status minutes
	
	$qy=mysql_query("select minutes from status where id='".$status."'");
	$ry=mysql_fetch_assoc($qy);
	
	$minutes = $ry['minutes'];
	
	if($minutes==0){
		
		$qqy=mysql_query("select lead_time from reservation where id='".$id."'");
		$rry=mysql_fetch_assoc($qqy);
		
		$minutes = $rry['lead_time'];
		
	}
	
	
	if($status==14 ){
		
		$q=mysql_query("select fname,lname, email from account where id=(select account_id from reservation where id='".$id."')");
		$r=mysql_fetch_assoc($q);
		
		$to = $r['email'];
			
		$name=$r['fname'].' '.$r['lname'];
		
		$subject = 'Status - Take away';
		
		// message
		$message = "
			<html>
				<body>
					<div style='font-size:13px;'>
						Hej ".trim($r['fname'])."! <br><br>
	
						Din beställning har avbokats. Hör av dig till oss om du har några frågor. <br>
						Din order togs emot av: ".getNameofSignee($id)."<br><br>
						 
						Ristorante Limone Italiano<br />
						Stora gatan 4<br />
						021-417560<br />
					</div>
				</body>
			</html>
		";
		
		$qc=mysql_query("select id, DATE_FORMAT(STR_TO_DATE(date, '%m/%d/%Y'),'%Y-%m-%d') as thedate, DATE_FORMAT(time,'%H:%i:%s') as time, account_id as accountid from reservation where id='".$id."'") or die(mysql_error());
		
		$rc=mysql_fetch_assoc($qc);
		$thedate = $rc['thedate'];
		$thetime = $rc['time'];
		
		//updating the asap time
		$asap_datetime = $thedate.' '.$thetime;
		mysql_query("update reservation set asap_datetime='".$asap_datetime."' where id='".$id."'") or die(mysql_error());
		
	}
	
	else{
	//for mail
	
	//SELECT ADDTIME(time, SEC_TO_TIME($minutes*60)), time FROM `reservation` order by id asc
		//check if the order is asap
		
		$qry=mysql_query("select asap from reservation where deleted=0 and id='".$id."'") or die(mysql_error());
		$rqy=mysql_fetch_assoc($qry);
		if($rqy['asap']==1){
			//if asap orders
			
			//the format 2014-11-18 03:15:26
			$getnow = date('Y-m-d H:i:s');
			
			$qq=mysql_query("select id, DATE_FORMAT(curdate(),'%Y-%m-%d') as thedate, DATE_FORMAT(ADDTIME('".$getnow."', SEC_TO_TIME(".$minutes."*60)),'%H:%i:%s') as time, account_id as accountid from reservation where id='".$id."'") or die(mysql_error()); 
			
			//$qq=mysql_query("select id, DATE_FORMAT(curdate(),'%b %d, %Y') as thedate, DATE_FORMAT(ADDTIME(now(), SEC_TO_TIME(".$minutes."*60)),'%H:%i') as time, account_id as accountid from reservation where id='".$id."'"); 
			
		}
		else{
			
			$qq=mysql_query("select id, DATE_FORMAT(STR_TO_DATE(date, '%m/%d/%Y'),'%Y-%m-%d') as thedate, DATE_FORMAT(time,'%H:%i:%s') as time, account_id as accountid from reservation where id='".$id."'") or die(mysql_error());
			
			//$qq=mysql_query("select id, DATE_FORMAT(STR_TO_DATE(date, '%m/%d/%Y'),'%b %d, %Y') as thedate, DATE_FORMAT(time,'%k:%i') as time, account_id as accountid from reservation where id='".$id."'"); 
			
		}
		
		
		$rr=mysql_fetch_assoc($qq);
		$thedate = $rr['thedate'];
		$thetime = $rr['time'];
		
		
		
		//added for mailing
		
		//new email format
		function theOrderType($id){
			$q=mysql_query("select if(rd.lunchmeny=0,' (À la carte)', if(rd.lunchmeny=2, ' (Frukost)', ' (Lunch)') ) as thetype from reservation as r, reservation_detail as rd where r.id=rd.reservation_id and rd.reservation_id='".$id."'");
			$r=mysql_fetch_assoc($q);
			
			return $r['thetype'];
		}
		
		$id=$rr['id'];
		
		$q=mysql_query("select concat(a.fname,' ',a.mname,' ',a.lname) as name, rt.description as descrip, r.approve as approve, r.date as date, r.time as time, r.number_people as numpeople, r.number_table as numtable, r.approve_by as appby, r.note as note, r.date_time as datetime, r.reservation_type_id as resid, r.uniqueid as uniqueid, r.reason as reason, a.email as email, a.mobile_number as mob, a.phone_number as phone, a.street_name as street, r.lead_time as lead, r.acknowledged as ack,a.city as city,  r.deliver as deliver, a.state as state, a.zip as zip, a.country as country, r.asap as asap, r.payment_mode as payment, datediff(STR_TO_DATE(concat(r.date,r.time), '%m/%d/%Y %H:%i'), now()) as count_days, r.asap_datetime as asapdatetime, r.kco_payment as kco from reservation as r, reservation_type as rt, account as a where a.id=r.account_id and rt.id=r.reservation_type_id and r.id=".$id) or die(mysql_error());
		
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
		$asap_date_time = $thedate.' '.$thetime;
		$kco = $r['kco'];
		
		
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
		
		$themsg='<style>
				table tr td p{
					padding:0 0 15px;
					margin:0;
				}
				table#table-1{
					float:left;
					width:100%;	
				}
				table#table-2{
					border-top:1px solid #000;
					height:300px;
				}
				table#table-2 tr td{
					padding:5px;
				}
			</style>';
			
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
							
							$removemsg1 = '';
						?>
						 <?php $removemsg1.='<p>Typ av bokning:'; ?> 
							<?php
								if($r['deliver']==1){
									$removemsg1.='Utkörnig'.theOrderType($id);
								}
								else{
									$removemsg1.=$type.theOrderType($id);
								}
							?>
						 <?php $removemsg1.='</p>'; 
						 
						 	$themsg.=$removemsg1;
						 ?>
						 
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
			
			<?php }	
		//end new email format
		
		
		$q=mysql_query("select fname,lname, email from account where id='".$rr['accountid']."'") or die(mysql_error());
		$r=mysql_fetch_assoc($q);
		
		
		$to = $r['email'];
			
		$name=$r['fname'].' '.$r['lname'];
					
		$subject = 'Bekräftelse';
			
		// message
		$message = "
			<html>
				<body>
					<div style='font-size:16px;'>".$themsg."
						<br><br>
						 
						<strong>Välkomna till Grekiska Kolgrillsbaren Barkarby!</strong><br><br>
		   
					    Stora gatan 4<br>
					    021-417560
					</div>
				</body>
			</html>
		";
		
		//updating the asap time
		$asap_datetime = $thedate.' '.$thetime;
		mysql_query("update reservation set asap_datetime='".$asap_datetime."' where id='".$id."'") or die(mysql_error());
		
		//Insert order details to portal--------->HERE<----------------
		?>
        
        <script language="javascript">
        
			 jQuery.ajax({
				url: "actions/post-order-to-portal.php",
				type: 'POST',
				data: 'res_id='+encodeURIComponent('<?php echo $id; ?>')+'&login_userid='+encodeURIComponent('<?php echo $_SESSION['login']['id']; ?>'),
				success: function(value){
					console.log('Insert to portal res: '+ value);
				}
			});
		
        </script>
        
        <?php
		
	}
	
	$messageforuser = str_replace($removemsg1,'',$message);
	$messageforuser = str_replace($removemsg2,'',$messageforuser);
		
	
	echo $_SESSION['login']['name'].'**'.$subject.'**'.$message.'**'.$to.'**'.$name.'**'.$messageforuser;
	
?>