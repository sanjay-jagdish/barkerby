<?php
	include 'config.php';
	
	$date= mysql_real_escape_string(strip_tags($_POST['date']));
	$time= mysql_real_escape_string(strip_tags($_POST['time']));
	$guest= mysql_real_escape_string(strip_tags($_POST['guest']));
	$email= mysql_real_escape_string(strip_tags($_POST['email']));
	$notes= mysql_real_escape_string(strip_tags($_POST['notes']));
	$res_detail_id= mysql_real_escape_string(strip_tags($_POST['res_detail_id']));
	$account_id= mysql_real_escape_string(strip_tags($_POST['account_id']));
	$endtime= mysql_real_escape_string(strip_tags($_POST['endtime']));
	
	$q=mysql_query("select dine_interval from restaurant_detail where id=".$res_detail_id);
	$r=mysql_fetch_assoc($q);
	
	$duration=$r['dine_interval'];
	
	mysql_query("insert into reservation(reservation_type_id, account_id, date, time, number_people, note, date_time, duration, approve) values(1, ".$account_id.", '".$date."', '".$time."', ".$guest.", '".$notes."', now(), ".$duration.",2)");
	
	$reservation_id=mysql_insert_id();
	
	$notincluded='';
	$q=mysql_query("select rt.table_detail_id from reservation_table as rt, reservation as r where r.checkout=0 and r.id=rt.reservation_id and r.date='".$date."'") or die(mysql_error());
	while($r=mysql_fetch_array($q)){
		$notincluded.='and id<>'.$r[0].' ';
	}
	
	$q=mysql_query("select id, table_name from table_detail where restaurant_detail_id=".$res_detail_id." and max_pax>=".$guest." $notincluded order by max_pax, id limit 1") or die(mysql_error());
	
	$r=mysql_fetch_assoc($q);
	
	if(mysql_num_rows($q) > 0){
		
		$table_id=$r['id'];
		
		mysql_query("insert into reservation_table(reservation_id, table_detail_id) values(".$reservation_id.",".$table_id.")");
		
		
		
		/* for mailing*/
		
		//for order-detail
	$q=mysql_query("select concat(a.fname,' ',a.mname,' ',a.lname) as name, rt.description as descrip, r.approve as approve, r.date as date, r.time as time, r.number_people as numguest, r.number_table as numtable, r.approve_by as approveby, r.note as note, r.date_time as datetime, r.reservation_type_id as restypeid, a.email as email, r.reason as reason, a.mobile_number as mobile,a.phone_number as phone, a.street_name as street, r.lead_time as leadtime, a.city as city, a.state as state, a.zip as zip, a.country as country from reservation as r, reservation_type as rt, account as a where a.id=r.account_id and rt.id=r.reservation_type_id and r.id=".$reservation_id);
	
	$r=mysql_fetch_assoc($q);
	
	$customer=$r['name'];
	$type=$r['descrip'];
	$status=$r['approve'];
	$date=$r['date'];
	$time=$r['time'];
	$num_people=$r['numguest'];
	$num_table=numberTable($reservation_id);
	$accountID=$r['approveby'];
	$note=$r['note'];
	$date_time=$r['datetime'];
	$typeID=$r['restypeid'];
	$email=$r['email'];
	$reason=$r['reason'];
	
	$mobile=$r['mobile'];
	$phone=$r['phone'];
	$thecustom=$r['leadtime'];
	$street=$r['street'];
	$city=$r['city'];
	$state=$r['state'];
	$zip=$r['zip'];
	$country=$r['country'];
	
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
		$address.=$street.',';
	}
	if($city!=''){
		$address.=$city;
	}
	if($state!=''){
		$address.=','.$state;
	}
	if($zip!=''){
		$address.=','.$zip;
	}
	if($country!=''){
		$address.=','.$country;
	}
	
	//end
	
	
	
	//added for mailing
			
		require 'PHPMailer/PHPMailerAutoload.php';	
			
		$to = $email;
			
		$name=$customer;
					
		$subject = 'Status Update';
			
		// message
		$message = "
		<html>
		<head>
		  <title>Order Status Update</title>
		</head>
		<body>
		  <p><strong>Order ID : ".$reservation_id."</strong>
		     <h2>Order Details:</h2>
		  </p>
		  
		  <table width='100%'>
		     <tr align='left'>
				<td width='180px'>Date and Time of Order :</td>
				<td>".date("M d, Y G:i",strtotime($date_time))."</td>
			</tr>
			<tr align='left'>
				<td>Customer Name :</td>
				<td>".$customer."</td>
			</tr>
			<tr align='left'>
				<td>Customer Email :</td>
				<td>".$email."</td>
			</tr>
			<tr align='left'>
				<td>Customer Phone Number :</td>
				<td>".$number."</td>
			</tr>
			<tr align='left'>
				<td>Customer's Address :</td>
				<td>".$address."</td>
			</tr>
			<tr align='left'>
				<td>Order Type :</td>
				<td>".$type."</td>
			</tr>";
			
			if($typeID==1){
				
			$message.="	
            <tr align='left'>
                <td>Reservation Date :</td>
                <td>".date('M d, Y',strtotime($date))."</td>
            </tr>
            <tr align='left'>
                <td>Reservation Time :</td>
                <td>".$time.' - '.$endtime.':00'."</td>
            </tr>
            <tr align='left'>
                <td>Number of People :</td>
                <td>".$num_people."</td>
            </tr>
            <tr align='left'>
                <td>Number of Table :</td>
                <td>".$num_table."</td>
            </tr>";
			
           	}
			
			$message.="
			<tr align='left'>
				<td>Note :</td>
				<td>".$note."</td>
			</tr>	
			<tr align='left'>
				<td>Status :</td>
				<td>".orderStatus($status);
			
				if($reason!=''){
					$message .=' - '.$reason;
				}
				if($thecustom!='' & $thecustom>0){
					$message .=' - '.$thecustom." min";
				}
			$message .="</td>	
			</tr>
			<tr align='left'>
				<td>Processed By :</td>
				<td>".processedBy($accountID)."</td>
			</tr>
			"; 
			
			$message .="
		  </table>";
		  
		  if($typeID==2){
			 $message.="  
			<table width='100%' border='1' style='border-collapse:collapse; border-color:#ddd; text-align:center;'>
				<tr style='background: #ddd; font-weight:bold;'>
					<td style='padding: 8px 5px;'>Menu</td>
					<td style='padding: 8px 5px;'>Category</td>
					<td style='padding: 8px 5px;'>Price</td>
					<td style='padding: 8px 5px;'>Quantity</td>
					<td style='padding: 8px 5px;'>Subtotal</td>
				</tr>
				";
					$q=mysql_query("select m.name, c.name, m.image, m.price, rd.quantity, (rd.quantity*m.price), cu.shortname from reservation_detail as rd, menu as m, category as c, currency as cu where m.id=rd.menu_id and c.id=m.category_id and cu.id=m.currency_id and rd.reservation_id=".$reservation_id) or die(mysql_error());
					$count=0;
					$total=0;
					$currency='';
					while($r=mysql_fetch_array($q)){
						$count++;
				
					$message.="
					<tr>
						<td width='200px' style='padding: 8px 5px;'>".$r[0]."</td>
						<td style='padding: 8px 5px;'>".$r[1]."</td>
						<td style='padding: 8px 5px;'>".$r[3].' '.$r[6]."</td>
						<td style='padding: 8px 5px;'>".$r[4]."</td>
						<td style='padding: 8px 5px;'>".$r[5].' '.$r[6]."</td>
					</tr>";	
					
						$total+=$r[5];
						$currency=$r[6];
					}
				
				$message.="
					<tr style='font-weight:bold;'>
					<td colspan='4' style='padding:5px; text-align:right;'>Total :</td>
					<td>".$total.' '.$currency."</td>
				</tr>
			</table>";
				
		} 
		  
		  $message.="
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
		$mail->send();
			
		/*end*/
		
		echo $r['table_name']."**".date("d M, Y",strtotime($date))."**".date("G:i",strtotime($time)).'-'.date("G:i",strtotime($endtime)).' '.dayName(date("D",strtotime($date)))."**".$guest;
		
	}
	else{
		echo 'not';
	}
	
	
?>