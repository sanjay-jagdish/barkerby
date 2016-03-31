<?php
	include 'config.php';
	
	$email = strip_tags($_POST['email']);
	
	$q=mysql_query("select fname,lname,readable from account where email='".$email."' and type_id=5");
	
	if(mysql_num_rows($q) > 0){
		
	$r=mysql_fetch_assoc($q);

//added for mailing
		
		require 'PHPMailer/PHPMailerAutoload.php';	
		
		$to = $email;
		
		$name=$r['fname']." ".$r['lname'];
				
		$subject = 'Ditt lösenord till Grekiska Kolgrillsbaren Barkaby';
		
		// message
		$message = "
		<html>
		<head>
		  <title>Ditt lösenord till Grekiska Kolgrillsbaren Barkaby</title>
		</head>
		<body>
		  <p>Följande uppgifter finns registrerade hos oss :</p>
		  <table>
		  	<tr>
				<td><strong>E-post</strong> :<td><td>".$email."</td>
			</tr> 
			<tr> 
			 	<td><strong>Lösenord</strong> :<td><td>".$r['readable']."</td>
			</tr>	
		  </table>
		  <p>Välkommen till Grekiska Kolgrillsbaren Barkaby!<br><br>
			 Drottninggatan 29 <br>
			 08-10 70 67 </p>
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

?>