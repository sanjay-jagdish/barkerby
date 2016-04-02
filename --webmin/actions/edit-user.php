<?php session_start();
	include '../config/config.php';
	
	$utype=mysql_real_escape_string(strip_tags($_POST['ut']));
	$uemail=mysql_real_escape_string(strip_tags($_POST['ue']));
	$ufname=mysql_real_escape_string(strip_tags($_POST['uf']));
	$umname='';
	$ulname=mysql_real_escape_string(strip_tags($_POST['ul']));
	$uphone=mysql_real_escape_string(strip_tags($_POST['uph']));
	$umobile=mysql_real_escape_string(strip_tags($_POST['umo']));
	$upass=mysql_real_escape_string(strip_tags($_POST['up']));
	$id=strip_tags($_POST['id']);
	
	$good=0;
	
	$q=mysql_query("select id from account where email='".$uemail."' and deleted=0 and type_id<>5") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		
		$r=mysql_fetch_assoc($q);
		$theid=$r['id'];
		
		if($id==$theid){
			
			$qr=mysql_query("select readable from account where id='".$id."' and deleted=0");
			$rq=mysql_fetch_assoc($qr);
			
			if($upass==''){
				$upass=$rq['readable'];
			}
			
			mysql_query("update account set type_id=".$utype.", email='".$uemail."', fname='".$ufname."', mname='".$umname."', lname='".$ulname."', phone_number='".$uphone."', mobile_number='".$umobile."', password='".md5($upass)."', readable='".$upass."' where id=".$id);
			
			mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Modified a user account.',now(),'".get_client_ip()."')");
			
			if($id==$_SESSION['login']['id']){
				$_SESSION['login']['name']=$ufname.' '.$ulname;
				$_SESSION['login']['type']=$utype;
			}
			
			$good=1;
			
		}
		else{
			echo 'Invalid';
		}
		
	}
	else{
		
		$qr=mysql_query("select readable from account where id='".$id."' and deleted=0");
		$rq=mysql_fetch_assoc($qr);
		
		if($upass==''){
			$upass=$rq['readable'];
		}
		
		mysql_query("update account set type_id=".$utype.", email='".$uemail."', fname='".$ufname."', mname='".$umname."', lname='".$ulname."', phone_number='".$uphone."', mobile_number='".$umobile."', password='".md5($upass)."', readable='".$upass."' where id=".$id);
		
		mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Modified a user account.',now(),'".get_client_ip()."')");
		
		if($id==$_SESSION['login']['id']){
			$_SESSION['login']['name']=$ufname.' '.$ulname;
		}
		
		$good=1;
		
	}
	
	if($good==1){
		
		//for email here
		
		
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
		  <p><strong>Namn</strong> : ".$ufname." ".$ulname."<br>
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