<?php session_start();
	include '../config/config.php';
	
	$utype=mysql_real_escape_string(strip_tags($_POST['ut']));
	$uemail=mysql_real_escape_string(strip_tags($_POST['ue']));
	$upass=mysql_real_escape_string(strip_tags($_POST['up']));
	$ufname=mysql_real_escape_string(strip_tags($_POST['uf']));
	$umname='';
	$ulname=mysql_real_escape_string(strip_tags($_POST['ul']));
	$uphone=mysql_real_escape_string(strip_tags($_POST['uph']));
	$umobile=mysql_real_escape_string(strip_tags($_POST['umo']));
	
	$q=mysql_query("select id from account where email='".$uemail."' and deleted=0") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		echo 'Invalid';
	}
	else{
		mysql_query("insert into account(type_id,email,password,fname,mname,lname,phone_number,mobile_number,readable,date_created) values($utype,'".$uemail."','".md5($upass)."','".$ufname."','".$umname."','".$ulname."','".$uphone."','".$umobile."','".$upass."',now())") or die(mysql_error());
		
		mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Added a user(".$ufname.' '.$ulname.")',now(),'".get_client_ip()."')");
		
		
		//added for mailing
		
		require 'PHPMailer/PHPMailerAutoload.php';	
		
		$to = $uemail;
		
		$name=$ufname." ".$ulname;
				
		$subject = 'Mise en Place Account';
		
		// message
		$message = "
		<html>
		<head>
		  <title>Mise en Place Account</title>
		</head>
		<body>
		  <p>Congratulations ".$ufname." ".$ulname."!<br>
		     You are now authorized to access the backend.<br>
			 Please find login details below :<br>
			 <strong>Email</strong> : ".$uemail."<br>
			 <strong>Password</strong> : ".$upass."<br>
			 <strong>Link</strong> : <a href='http://www.limoneristorante.se/webmin/' target='_blank'>http://www.limoneristorante.se/webmin/</a>
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