<?php session_start();
	include '../config/config.php';
	
	$email=mysql_real_escape_string(strip_tags($_POST['ce']));
	$pass=mysql_real_escape_string(strip_tags($_POST['cp']));
	$fname=mysql_real_escape_string(strip_tags($_POST['cf']));
	$lname=mysql_real_escape_string(strip_tags($_POST['cl']));
	$phone=mysql_real_escape_string(strip_tags($_POST['cph']));
	$mobile=mysql_real_escape_string(strip_tags($_POST['cm']));
	$street=mysql_real_escape_string(strip_tags($_POST['cs']));
	$city=mysql_real_escape_string(strip_tags($_POST['ct']));
	$state=mysql_real_escape_string(strip_tags($_POST['cst']));
	$zip=mysql_real_escape_string(strip_tags($_POST['cz']));
	$country=mysql_real_escape_string(strip_tags($_POST['co']));
	$company=mysql_real_escape_string(strip_tags($_POST['comp']));
	
	$q=mysql_query("select id from account where email='".$email."' and deleted=0 and type_id=5") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		echo 'Invalid';
	}
	else{
		mysql_query("insert into account(type_id,email,password,fname,lname,phone_number,street_name,city,zip,country,readable,company,date_created) values(5,'".$email."','".md5($pass)."','".$fname."','".$lname."','".$phone."','".$street."','".$city."','".$zip."','".$country."','".$pass."','".$company."',now())") or die(mysql_error());
		
		mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Added a Customer(".$fname." ".$lname.")',now(),'".get_client_ip()."')") or die(mysql_error);
		
		
		
		//added for mailing
		
		require 'PHPMailer/PHPMailerAutoload.php';	
		
		$to=$email;
		
		$name=$fname." ".$lname;
				
		$subject = 'Mise en Place Customer Account';
		
		// message
		$message = "
		<html>
		<head>
		  <title>Mise en Place Customer Account</title>
		</head>
		<body>
		  <p>Welcome ".$fname." ".$lname."!<br>
			 Please find login details below :<br>
			 <strong>Email</strong> : ".$email."<br>
			 <strong>Password</strong> : ".$pass."<br>
			 <strong>Address</strong><br>
			 Street : ".$street."<br>
			 City : ".$city."<br>
			 Zip : ".$zip."<br>
			 <strong>Phone Number</strong> : ".$phone."			  
		  </p>
		</body>
		</html>
		";
		
		//Create a new PHPMailer instance
		$mail = new PHPMailer();
		//Tell PHPMailer to use SMTP
		$mail->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 1;
		//Ask for HTML-friendly debug output
		//$mail->Debugoutput = 'html';
		//Set the hostname of the mail server
		$mail->Host = $garcon_settings['smtp_host'];//'smtp.gmail.com';
		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		$mail->Port = $garcon_settings['smtp_port'];
		//Set the encryption system to use - ssl (deprecated) or tls
		$mail->SMTPSecure = $garcon_settings['smtp_security'];
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;
		//Username to use for SMTP authentication - use full email address for gmail
		$mail->Username = $garcon_settings['smtp_user'];
		//Password to use for SMTP authentication
		$mail->Password = $garcon_settings['smtp_pass'];
		//Set who the message is to be sent from
		$mail->setFrom($garcon_settings['smtp_user'],$garcon_settings['smtp_from']);
	
		$mail->Subject = $subject;
		
		$mail->msgHTML($message);
		
		$mail->AddAddress($to, $name);
		$mail->send();
		

	}
	
?>