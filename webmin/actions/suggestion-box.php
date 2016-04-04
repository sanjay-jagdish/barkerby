<?php 
include '../config/config.php';

$dname = mysql_real_escape_string(strip_tags($_POST['domainname']));
$user = mysql_real_escape_string(strip_tags($_POST['user']));
$subj = mysql_real_escape_string($_POST['subject']);
$msg = mysql_real_escape_string($_POST['msg']);

//added for mailing
		
		 require 'PHPMailer/PHPMailerAutoload.php';	
		
		 $to = 'victor@e2f.se';
		
		 $name= 'Grekiska Headquarter';
				
		 $subject = $subj .' - '. $user;
		
		 // message
		 $message = "
		 <html>
		 <head>
		   <title>Meddela huvudkonteret</title>
		 </head>
		 <body>
				". $subj . "<br /><br />
				". $msg  ."
		 </body>
		 </html>
		 ";
		
		 // Please click the link below to confirm your registration.<br>
			//  <a href='".$garcon_settings['site_url']."thank-you/?str=".encrypt_decrypt('encrypt', $account_id)."' target='_blank'>".$garcon_settings['site_url']."thank-you/?str=".encrypt_decrypt('encrypt', $account_id)."</a>
		
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
		 $mail->AddAddress('david@e2f.se', 'David');
		 //$mail->AddAddress('megeh_09@yahoo.com', 'Mikho Malto');
		 $mail->send();

?>