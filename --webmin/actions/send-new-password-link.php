<?php 

include '../config/config.php';

$email=mysql_real_escape_string(strip_tags($_POST['em']));

$query=mysql_query("select id, concat( fname ,' ', lname) as name from account where email='".$email."' and type_id<>5 and deleted=0") or die(mysql_error());
$res = mysql_fetch_assoc($query);


if(empty($res['id'])){
	
	echo 'invalid';
	
}else{
	
	//added for mailing
	$dname = $_SERVER['SERVER_NAME'];
	 require 'PHPMailer/PHPMailerAutoload.php';	
	
	 $to = $email;
	
	 $name= $res['name'];
			
	 $subject = 'Password Reset';
	
	 // message
	 $message = "
	 <html>
	 <head>
	   <title>Password Reset</title>
	 </head>
	 <body>
			<div>Du har begärt att ändra lösenord för följande konto:</div>
			<div>". $dname  ."</div>
			<div>E-post: ". $email ."</div>
			<div>Klicka på länken nedan för att återställa ditt lösenord:</div>
			<div>
				<a href='http://". $_SERVER['HTTP_HOST'] ."/webmin/?log_action=2&id=". encrypt_decrypt('encrypt',$res['id'])."'>
				    http://". $_SERVER['HTTP_HOST'] ."/webmin/?log_action=2&id=".encrypt_decrypt('encrypt',$res['id']). "
				</a>
			</div>
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
	 //$mail->AddAddress('kirby.aldeon@gmail.com', 'Kirby Aldeon');
	 //$mail->AddAddress('megeh_09@yahoo.com', 'Mikho Malto');
	 $mail->send();
}

?>