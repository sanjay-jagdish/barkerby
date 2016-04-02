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
	
	//changed NOW() to date() -> Viber 2015-02-07 12:24:00
	//$DTapprove = date('Y-m-d H:i:s');
	
	/*mysql_query("INSERT INTO reservation_status_history(date_time,reservation_id,status,account_id,signatory) 
								values('".$DTapprove."', ".$id.",".$status.",".$_SESSION['login']['id'].",".$signatory.")");*/
	
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
	
	
	if($status==14){
		
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
		
		$formsg='';
		$qr=mysql_query("select m.name as menu, rd.quantity as quantity, rd.notes as notes, m.id as menuid, r.uniqueid as uniqueid from reservation_detail as rd, menu as m, reservation as r where m.id=rd.menu_id and r.id=rd.reservation_id and rd.reservation_id='".$rr['id']."' and rd.lunchmeny=0") or die(mysql_error());
		$formsg.='<table width="100%">';
		while($rq=mysql_fetch_assoc($qr)){
			
			//for optional menus
			
			$tillval='';
			
			$opt_sql = "select a.name as name,a.price as price, dish_num from reservation_menu_option as a where reservation_unique_id = '".$rq['uniqueid']."' and a.menu_id=".$rq['menuid']." order by dish_num";
					
			$opt_query = mysql_query($opt_sql) or die(mysql_error());
			if(mysql_num_rows($opt_query)>0){
				//$tillval.='<div style="text-align:left; padding:10px 5px 10px; font-size:12px;">';
				$counter = 0;
				while($opt = mysql_fetch_assoc($opt_query)){
						$opt_tot += $opt['price'];
						if($counter<$opt['dish_num']){
							$counter = $counter+1;
							if($counter > 1){
								$tillval.='<br />';
							}
							$tillval.='Portion #'.$counter;
							
						}
						if($opt['price']==0){
							$opt_price = '0 kr';
						}else{
							$opt_price = number_format($opt['price'],0).' kr';
						}
						$tillval.='<div style="padding-top:5px;"><em>';
						$tillval.='<div><span>'.$opt['name'].'</span></div>';
						$tillval.='</em></div>';
					}
				//$tillval.='</div>';
			}
		
			//end
			
			$formsg.='
					<tr>
						<td width="22%">Maträtt:</td>
						<td align="left">'.strip_tags($rq['menu']).'</td>
					</tr>
					<tr>
						<td>Antal portioner:</td>
						<td align="left">'.$rq['quantity'].'</td>
					</tr>
					<tr>
						<td>Tillval:</td>
						<td align="left">'.$tillval.'</td>
					</tr>
					<tr>
						<td>Specialla önskemål:</td>
						<td align="left">'.strip_tags($rq['notes']).'</td>
					</tr>';
			
		}
		
		
		//for lunchmeny
		
		$qs=mysql_query("select ml.name as menu, rd.quantity as quantity, rd.notes as notes, rd.menu_id as menuid, r.uniqueid as uniqueid from reservation_detail as rd, menu_lunch_items as ml, reservation as r where ml.id=rd.menu_id and r.id=rd.reservation_id and rd.reservation_id='".$rr['id']."' and rd.lunchmeny=1") or die(mysql_error());
		
		while($rqs=mysql_fetch_assoc($qs)){
			
			//for optional menus
			
			$tillvals='';
			
			$formsg.='
					<tr>
						<td width="22%">Maträtt:</td>
						<td align="left">'.strip_tags($rqs['menu']).'</td>
					</tr>
					<tr>
						<td>Antal portioner:</td>
						<td align="left">'.$rqs['quantity'].'</td>
					</tr>
					<tr>
						<td>Tillval:</td>
						<td align="left">'.$tillvals.'</td>
					</tr>
					<tr>
						<td>Specialla önskemål:</td>
						<td align="left">'.strip_tags($rqs['notes']).'</td>
					</tr>';
			
		}
		
		
		if(getNameofSignee($rr['id'])!=''){
			$formsg.='<tr>
						<td>Din order togs emot av:</td>
						<td align="left">'.getNameofSignee($rr['id']).'</td>
					</tr>';
		}
		
		$formsg.='<tr>
						<td>Betalsätt:</td>
						<td align="left">'.paymentMode($rr['id']).'</td>
					</tr>';
		
		$formsg.='</table><br>';
		
		$q=mysql_query("select fname,lname, email from account where id='".$rr['accountid']."'") or die(mysql_error());
		$r=mysql_fetch_assoc($q);
		
		
		$delivermsg='Ungefärlig leveranstidpunkt:';
		
		$qd=mysql_query("select deliver from reservation where id='".$rr['id']."'");
		$rd=mysql_fetch_assoc($qd);
		
		if($rd['deliver']==0){
			$delivermsg='Klar för avhämtning:';
		}
			
		$to = $r['email'];
			
		$name=$r['fname'].' '.$r['lname'];
					
		$subject = 'Din beställning';
			
		// message
		$message = "
			<html>
				<body>
					<div style='font-size:13px;'>
						Hej ".trim($r['fname'])."! <br><br>
	
						Tack för din beställning. Nedan följer en sammanställning av din order. <br><br>
						
						<div>".$formsg."</div>
						<div style='clear:both'></div>
						".$delivermsg." ".date('d F Y',strtotime($thedate)).', klockan '.date('H:i',strtotime($thetime))." <br><br>
						 
						Välkommen till Limone Ristorante Italiano! <br><br>
						 
						Stora gatan 4 <br>
						021-417560 <br>
					</div>
				</body>
			</html>
		";
		
		//updating the asap time
		$asap_datetime = $thedate.' '.$thetime;
		mysql_query("update reservation set asap_datetime='".$asap_datetime."' where id='".$id."'") or die(mysql_error());
		
		
		//by Viber - inserting take-away entry to portal DB
		$getReservation = mysql_query("SELECT * FROM reservation WHERE id='".$id."' AND reservation_type_id=2 AND (approve=13 OR approve=9)") or die(mysql_error());
		$ReservationRow = mysql_fetch_assoc($getReservation);
		$checkResult = mysql_num_rows($getReservation);
		
		if($checkResult > 0){
			
			$getSettings = mysql_query("SELECT * FROM settings") or die(mysql_error());
			while($SiteSettings = mysql_fetch_assoc($getSettings)){
				if($SiteSettings['var_name'] == 'site_url'){
					$siteURL = $SiteSettings['var_value'];
				}
				
				if($SiteSettings['var_name'] == 'smtp_from'){
					$siteName = $SiteSettings['var_value'];
				}
				
				if($SiteSettings['var_name'] == 'takeaway_content'){
					$siteDesc = $SiteSettings['var_value'];
				}
			}
			
			$derivedTotalAmount = 0;
			$getTotalAmount = mysql_query("SELECT * FROM reservation_detail WHERE reservation_id=".$id) or die(mysql_error());
			while($ResTotalAmount = mysql_fetch_assoc($getTotalAmount)){
				$derivedTotalAmount = $derivedTotalAmount+(($ResTotalAmount['quantity'])*($ResTotalAmount['price']));
			}
		
			$ActiveFee = 0;
			//$dateObj = new DateTime();
			//$dateCurrnt = $dateObj->format('Y-m-d H:i:s');
			$dateCurrnt = $ReservationRow['asap_datetime'];
			$dateApproved = new DateTime($dateCurrnt);
			$dateReservation = new DateTime($ReservationRow['date_time']);
			$AdminResponseTime = $dateReservation->diff($dateApproved);
			
			if( ($AdminResponseTime->i) <= 10 ){ 
				$PortalDB = new mysqli(PORTAL_HOST,PORTAL_USER,PORTAL_PASSWORD,PORTAL_DBNAME);
				
				// Check connection
				if ($PortalDB->connect_errno) {
					die('Connect Error: ' . $PortalDB->connect_errno);
				} else {
					
					$CheckPortal = "SELECT * FROM portal WHERE siteURL='".$siteURL."'";
					$CheckPortalExec = $PortalDB->query($CheckPortal);
					$PortalInfo = $CheckPortalExec->fetch_assoc();
					
					if( ($CheckPortalExec->num_rows) >= 1 ){
						$portalID = $PortalInfo['id'];
					
						$CheckFee = "SELECT * FROM fixedFee WHERE portal_id=".$portalID;
						$CheckFeeExec = $mysqli->query($CheckFee);
						if( ($CheckFeeExec->num_rows) >= 1 ){
							$SiteActiveFee = $CheckFeeExec->fetch_assoc();
							$ActiveFee = $SiteActiveFee['fixedFeeID'];
						}
					} else {
						$InsertToPortalTableQuery = "INSERT INTO portal(siteURL,siteName,status) VALUES('".$siteURL."','".$siteName."',1)";
						$InsertEntryToPortalTable = $PortalDB->query($InsertToPortalTableQuery);
						$portalID = $PortalDB->insert_id;
					}
					
					$InsertEntryToPortalQuery = "INSERT INTO transtakeaway(MEPTakeAwayID,portal_id,fixedfee_id,amount,description,respondTime,approved,MEPUserID,MEPTransDate,MEPOrderDate) VALUES(".$id.",".$portalID.",".$ActiveFee.",".number_format($derivedTotalAmount,2).",'".$siteDesc."','".($AdminResponseTime->h.':'.$AdminResponseTime->i.':'.$AdminResponseTime->s)."',1,".$_SESSION['login']['id'].",'".$dateCurrnt."','".$ReservationRow['date_time']."')";
					$InsertEntryToPortal = $PortalDB->query($InsertEntryToPortalQuery);
					
					if($InsertEntryToPortal){
							mysql_query("INSERT INTO portal_entry(portalID,MEPTakeAwayID,MEPUserID,MEPTransDate) VALUES(".$portalID.",".$id.",".$_SESSION['login']['id'].",'".$dateCurrnt."')", $con) or die(mysql_error());
					}
					
					$PortalDB->close();
				}
				
			}// ($AdminResponseTime->i) <= 10 end
		
		}// $checkResult end
	
	} // else end - if order is not cancelled - comment added by Viber
	
			
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
		$mail->send();
		
		//email for admin
		
		/*$to = 'ekonomi.limone@hotmail.se';
				
		$name='Stefano Basagni';
		
		$subject = 'Bekräftelse - Take away';
		
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
		$mail->send(); */
		
		//end email for admin
		
	
	echo $_SESSION['login']['name'];
?> 