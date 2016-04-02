<?php session_start();
	include '../config/config.php';
	include '../actions/PHPMailer/PHPMailerAutoload.php';

$due_notification_sql = "SELECT * FROM notification WHERE `status`=1 AND `date`='".date('Y-m-d')."'"; 
$due_notification_qry = mysql_query($due_notification_sql);
$due_notification_num = mysql_num_rows($due_notification_qry);

if($due_notification_num>0){
	
	//$recipients = array('viceer@gmail.com' => 'Victor Erames', 'viceer@yahoo.com' => 'Victor Erames', 'percival.carino@gmail.com' => 'Sir Val');
	$recipients_sql = "SELECT CONCAT(fname, ' ', lname) AS name, email FROM account WHERE deleted=0 AND type_id=5";
	$recipients_qry = mysql_query($recipients_sql);
	
	while($recipients_res = mysql_fetch_assoc($recipients_qry)){
		$recipients[$recipients_res['email']] = $recipients_res['name']; 
	}
	
	while($res = mysql_fetch_assoc($due_notification_qry)){	
		
		$subject = stripslashes($res['subject']);
		$content = stripslashes($res['content']);
		
		echo '<br><br><b><u>'.$subject.'</u></b><br>';
		
		$subject = $subject;
		
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
		
		$mail->Subject = $subject;
		
		$mail->msgHTML($message);
		
		foreach($recipients as $to => $name){
		    //$mail->addAddress($to, $name);
		    $mail2 = clone $mail;
		    $mail2->AddAddress($to, $name);
		    $mail2->send();
		    $cnt++;
		    if($cnt==1){
  			mysql_query("UPDATE notification SET `status`=2, delivered=NOW() WHERE id=".$res['id'])or die(mysql_error());	
		    }
		}


		/*
		foreach($recipients as $to => $name){
			$mail->addBCC($to, $name);
		}
		//Set the subject line
		$mail->Subject = $subject;
		
		$mail->msgHTML($message);
		
		if ($mail->send()) {
			echo 'Successfully sent Notification: '.$subject.'<br>';		
		}else{
			echo '<font color="red">Failed Sending Notification: '.$subject.'</font><br>';
		}
		
		//update the status to PUSHED (2) and the push time
		mysql_query("UPDATE notification SET `status`=2, delivered=NOW() WHERE id=".$res['id'])or die(mysql_error());	
	
		echo '<br>'."UPDATE notification SET `status`=2, delivered=NOW() WHERE id=".$res['id'];
		*/
	}
	
}//if there are due notification for pushing
else{
	echo '<br><br><font color="red"><b>'.date('Y-m-d').'</b>There are no notifications for pushing.</font>';
}
?>