<?php 
include 'config.php';

$fname = mysql_real_escape_string(strip_tags($_POST['fname']));
$email = mysql_real_escape_string(strip_tags($_POST['email']));
$lname = mysql_real_escape_string(strip_tags($_POST['lname']));
$phone = mysql_real_escape_string(strip_tags($_POST['phone']));
$giftnum = mysql_real_escape_string(strip_tags($_POST['giftnum']));
$giftprice = mysql_real_escape_string(strip_tags($_POST['giftprice']));
$postas = mysql_real_escape_string(strip_tags($_POST['postas']));
$messagetext = mysql_real_escape_string($_POST['message']);

//added for mailing
		
		 require 'PHPMailer/PHPMailerAutoload.php';	
		
		 $to = $email;
		
		 $name=$fname." ".$lname;
				
		 $subject = 'Beställning av presentkort';
		
		 // message
		 $message = "
		 <html>
		 <head>
		   <title>Beställning av presentkort</title>
		 </head>
		 <body>
				<p>Namn: " . $name . "</p>
				<p>E-post: " . $to . "</p>
				<p>Mobiletelefon: " . $phone . "</p>
				<p>Antal presentkort: " . $giftnum . "</p>
				<p>Värde per presentkort: " . $giftprice . "</p>
				<p>Om &quot;Postas&quot;, vänligen uppge leverans- och fakturaadress samt org.nr/pers.nr: " . $postas . "</p>
				<table>
					<tr>
						<td style='vertical-align: top;'>Övrig information:</td>
						<td>" . $messagetext . "</td>
					</tr>
				</table>
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
		 $mail->AddAddress('bokning.limone@hotmail.com', 'Limone');
		 //$mail->AddAddress('kirby.aldeon@gmail.com', 'Kirby Aldeon');
		 //$mail->AddAddress('megeh_09@yahoo.com', 'Mikho Malto');
		 $mail->send();

?>