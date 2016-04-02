<?php session_start();
	include '../config/config.php';
	
	require_once( 'PHPMailer/PHPMailerAutoload.php' );
	
	$subject = strip_tags($_POST['subject']);
	$message = $_POST['message'];
	$to = strip_tags($_POST['to']);
	$name = strip_tags($_POST['name']);
	$message2 = $_POST['message2'];
	
	
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
	$mail->msgHTML($message2);

	$mail->Subject = $subject;
	
	$mail->msgHTML($message2);
	
	$mail->AddAddress($to, $name);
	$mail->send();
	
	//email for admin
		
	//$to = 'david.rasti@gmail.com';
			
	//$name='David Rasti';
	
	//get copy email
	$qe=mysql_query("select var_value from settings where var_name='copy_email'");
	$re=mysql_fetch_assoc($qe);
	
	$to = 'grekiska.test@gmail.com';
	
	if($re['var_value']!=''){
		$to = $re['var_value'];
	}
	
	
	//get copy name
	$qn=mysql_query("select var_value from settings where var_name='copy_name'");
	$rn=mysql_fetch_assoc($qn);
		
	$name='';
	
	if($rn['var_value']!=''){
		$name=$rn['var_value'];
	}
	
	$subject = 'Bekräftelse';
	
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
	$mail->msgHTML($message2);

	$mail->Subject = $subject;
	
	$mail->msgHTML($message2);
	
	$mail->AddAddress($to, $name);
	$mail->send();
	
	//end email for admin
mysql_close($con);
?>