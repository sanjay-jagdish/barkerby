<?php
	include 'config.php';
	
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
	
	
	$getnow = date('Y-m-d H:i:s');
	
	$q=mysql_query("select id from account where email='".$email."' and type_id=5 and deleted=0") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		echo 'Invalid';
	}
	else{
	
		mysql_query("insert into account(type_id,email,password,fname,lname,phone_number,street_name,city,zip,country,company,date_created,readable,confirmed) values(5,'".$email."','".md5($pword)."','".$fname."','".$lname."','".$phone."','".$street."','".$city."','".$zip."','".$country."','".$company."','".$getnow."','".$pword."',1)") or die(mysql_error());
		
		$account_id=mysql_insert_id();
		
		
		//added for mailing
		
		 require 'PHPMailer/PHPMailerAutoload.php';	
		
		 $to = $email;
		
		 $name=$fname." ".$lname;
				
		 $subject = 'Välkommen till Grekiska Kolgrillsbaren Barkaby';
		
		 // message
		 $message = "
		 <html>
		 <head>
		   <title>Välkommen till Grekiska Kolgrillsbaren Barkaby</title>
		 </head>
		 <body>
		   <p>Hej ".$fname." ".$lname."!<br>
		 	 Följande uppgifter har nu registrerats:<br><br>
		 	 E-post: ".$email."<br>
		 	 Lösenord: ".$pword."<br>
		 	 Adress:<br>
		 	 Gatuadress: ".$street."<br>
		 	 Ort: ".$city."<br>
		 	 Postnummer: ".$zip."<br>
		 	 Telefonnummer: ".$phone."
		   </p>
		   Välkomna till Grekiska Kolgrillsbaren Barkarby!<br><br>
		   
		   Barkarbyvägen 45 <br>
		   177 38 Järfälla<br>
		   08-511 600 30
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
?>