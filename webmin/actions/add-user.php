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
	
	$q=mysql_query("select id from account where email='".$uemail."' and deleted=0 and type_id<>5") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		echo 'Invalid';
	}
	else{
		mysql_query("insert into account(type_id,email,password,fname,mname,lname,phone_number,mobile_number,readable,date_created) values($utype,'".$uemail."','".md5($upass)."','".$ufname."','".$umname."','".$ulname."','".$uphone."','".$umobile."','".$upass."',now())") or die(mysql_error());
		
		mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Added a user(".$ufname.' '.$ulname.")',now(),'".get_client_ip()."')");
		
		//add user to OSTicket System
		//ost_user
		mysql_query("INSERT INTO ost_user (org_id, name, created) 
					 VALUES (0, '".$ufname." ".$umname." ".$ulname."', NOW())
					") or die(mysql_error());
		$last_user_id = mysql_insert_id();

		if($last_user_id==0){
			$last_id_qry = mysql_query("SELECT MAX(id) FROM ost_user");	
			$last_id_res = mysql_fetch_array($last_id_qry);
			$last_user_id = $last_id_res[0];
		}
		
		//ost_user_account
		mysql_query("INSERT INTO ost_user_account (user_id, status, passwd, registered) 
					 VALUES (".$last_user_id.", 1, md5('".$upass."'), NOW())
					") or die(mysql_error());
		
		//ost_user_email
		mysql_query("INSERT INTO ost_user_email(user_id, address) 
					 VALUES (".$last_user_id.", '".$uemail."')
					") or die(mysql_error());
		
		$last_user_email_id = mysql_insert_id();

		if($last_user_email_id==0){
			$last_email_id_qry = mysql_query("SELECT MAX(id) FROM ost_user_email");	
			$last_email_id_res = mysql_fetch_array($last_email_id_qry);
			$last_user_email_id = $last_email_id_res[0];
		}
		
		//UPDATE ost_user >default_email_id using the last inserted id of ost_email_account
		mysql_query("UPDATE ost_user SET default_email_id=".$last_user_email_id." WHERE id=".$last_user_id);
		
		
		//added for mailing
		
		require 'PHPMailer/PHPMailerAutoload.php';	
		
		$to = $uemail;
		
		$name=$ufname." ".$ulname;
				
		$subject = 'Dina kontouppgifter';
		
		// message
		$message = "
		<html>
		<head></head>
		<body>
		  <p>Hej ".$ufname." ".$ulname.", Ett nytt konto har skapats för dig och nedan hittar du dina inloggningsuppgifter.<br>
			 <strong>E-post</strong> : ".$uemail."<br>
			 <strong>Lösenord</strong> : ".$upass."<br>
			 <strong>Länk</strong> : <a href='http://limone.icington.com/webmin/' target='_blank'>http://limone.icington.com/webmin/</a>
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
	
?>