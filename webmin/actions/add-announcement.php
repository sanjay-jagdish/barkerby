                                <?php session_start();
	include '../config/config.php';

	$a_id = $_SESSION['login']['id'];
	$sbj = mysql_real_escape_string(strip_tags($_POST['sbj']));
	$msg = mysql_real_escape_string(strip_tags($_POST['msg']));
	$rec = mysql_real_escape_string(strip_tags($_POST['rec']));
	$send_option = $_POST['opt'];
	$send_all = $_POST['all'];
	
	$status=0;
	$delivered = "'0000-00-00 00:00:00'";
	
	if($send_all==1){
		$rec = '';
	}
	
	if($rec!=''){
		
		if($send_option==1){
					
			if($send_all==1){
				$q=mysql_query("SELECT email, CONCAT(fname,' ',lname) AS name from account WHERE deleted=0 AND type_id IN (SELECT id FROM `type` WHERE staff=1)") or die(mysql_error());
			}else{
			
				$ids=explode(",",$rec);
	
				foreach($ids as $k => $id){
					$q=mysql_query("SELECT email FROM account WHERE id=".$id);				
					$r=mysql_fetch_assoc($q);
					$recipients[$r['email']] = $r['name'];
				}
			}

			//SEND EMAIL

			require 'PHPMailer/PHPMailerAutoload.php';	
			
			$content = $msg;
			$subject = $sbj;
			
			$message = '<b>'.$subject.'</b><br><br>'.stripslashes(str_replace('\n','<br />',nl2br($content)));
			
			$mail = new PHPMailer();
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
			
			foreach($recipients as $to => $name){
				//$mail->addAddress($to, $name);
				$mail2 = clone $mail;
				$mail2->AddAddress($to, $name);
				$mail2->send();
			}				
			
			//END-OF SEND EMAIL
			
			$status=1;
			$delivered = 'NOW()';
		}
				
	}
					
	mysql_query("INSERT INTO announcement SET 
			     account_id=".$a_id.", 
				 subject='".$sbj."', 
				 content='".$msg."',
				 date=curdate(), 
				 time=curtime(), 
				 recipient='".$rec."', 
				 status=".$status.",
				 delivered=".$delivered."
				 ");
		
	mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Added an announcement.',now(),'".get_client_ip()."')");
		
	
?>
                            