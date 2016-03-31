<?php
	include 'config.php';
	
	$uniqueid= mysql_real_escape_string(strip_tags($_POST['unique_id']));
	$email= mysql_real_escape_string(strip_tags($_POST['email']));
	$fname= mysql_real_escape_string(strip_tags($_POST['fname']));
	$lname= mysql_real_escape_string(strip_tags($_POST['lname']));
	$phone= mysql_real_escape_string(strip_tags($_POST['phone']));
	$company= mysql_real_escape_string(strip_tags($_POST['company']));
	$street= mysql_real_escape_string(strip_tags($_POST['street']));
	$city= mysql_real_escape_string(strip_tags($_POST['city']));
	$zip= mysql_real_escape_string(strip_tags($_POST['zip']));
	$country= mysql_real_escape_string(strip_tags($_POST['country']));
	$pword= mysql_real_escape_string(strip_tags($_POST['pword']));

	$datetime= mysql_real_escape_string(strip_tags($_POST['datetime']));
	$paymenttype= mysql_real_escape_string(strip_tags($_POST['payment_type']));
	$total= mysql_real_escape_string(strip_tags($_POST['total']));
	
	$date='';
	$time='';
	if($datetime!=''){
		
		$str=explode(' ',$datetime);
		
		$date=formatDate($str[0]);
		$time=$str[1];
	}
	
	
	$q=mysql_query("select id from account where email='".$email."' and password='".md5($pword)."' and type_id=5 and deleted=0") or die(mysql_error());
	
	if(mysql_num_rows($q) == 0){
		
		mysql_query("insert into account(type_id,email,password,fname,lname,phone_number,street_name,city,zip,country,company,date_created,readable,confirmed) values(5,'".$email."','".md5($pword)."','".$fname."','".$lname."','".$phone."','".$street."','".$city."','".$zip."','".$country."','".$company."',now(),'".$pword."',1)") or die(mysql_error());
		
		$accountid=mysql_insert_id();
	
		$qq=mysql_query("select id from catering_detail where deleted=0 and uniqueid='".$uniqueid."'") or die(mysql_error());
		$rr=mysql_fetch_assoc($qq);
		
		if(mysql_num_rows($qq) > 0){
			$id = $rr['id'];
			
			mysql_query("update catering_detail set payment_mode='".$paymenttype."', total='".$total."', account_id='".$accountid."', date='".$date."', time='".$time."',status=1, date_time=now() where id='".$id."'") or die(mysql_error());
			
			//added for mailing
		
			 require 'PHPMailer/PHPMailerAutoload.php';	
			
			 $to = $email;
			
			 $name=$fname." ".$lname;
					
			 $subject = 'Välkommen till Limone';
			
			 // message
			 $message = "
			 <html>
			 <head>
			   <title>Välkommen till Limone</title>
			 </head>
			 <body>
			   <p>Hej ".$fname." ".$lname."!<br>
				 Följande uppgifter har nu registrerats:<br>
				 <strong>E-post</strong> : ".$email."<br>
				 <strong>Lösenord</strong> : ".$pword."<br>
				 <strong>Adress</strong> : ".$street."<br>
				 <strong>Ort</strong> : ".$city."<br>
				 <strong>Postnummer</strong> : ".$zip."<br>
				 <strong>Telefonnummer</strong> : ".$phone."<br><br><br>
				 Välkomna till Limone Ristorante Italiano!
			   </p>
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
			
		}
		else{
			echo 'not';
		}
	
	}
	else{
		echo 'invalid';
	}
	
	
?>