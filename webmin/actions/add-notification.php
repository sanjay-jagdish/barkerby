<?php session_start();
	include '../config/config.php';

	$a_id = $_SESSION['login']['id'];
	$subject = mysql_real_escape_string(strip_tags($_POST['subject']));
	$content = mysql_real_escape_string(strip_tags($_POST['content']));
	$pushtime = mysql_real_escape_string(strip_tags($_POST['push_time']));
	$status = $_POST['status'];
	
	if(trim($pushtime)!=''){
		$push_date_time = explode(' ',$pushtime);
		$push_date = date("Y-m-d", strtotime($push_date_time[0]));
		$push_time = $push_date_time[1];
	}

	if($status==2){	
		mysql_query("INSERT INTO notification(account_id, subject, content, created, delivered, date, time, status) 
			     VALUES (".$a_id.", '".$subject."', '".$content."',  NOW(), NOW(), '".date('Y-m-d')."', '".date('H:i:s')."', ".$status.")") or die(mysql_error());	
	}else{
		mysql_query("INSERT INTO notification(account_id, subject, content, created, date, time, status) 
			     VALUES (".$a_id.", '".$subject."', '".$content."', NOW(), '".$push_date."', '".$push_time."', ".$status.")") or die(mysql_error());
	}
		
	$last_id = mysql_insert_id();
		
	mysql_query("INSERT INTO log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Added a Notification: [".$last_id."] ".$subject.".',now(),'".get_client_ip()."')");
		

if($status==2){	
	
	require 'PHPMailer/PHPMailerAutoload.php';	
	
	//$recipients = array('viceer@gmail.com' => 'Victor Erames', 'viceer@yahoo.com' => 'Victor Erames', 'percival.carino@gmail.com' => 'Sir Val');
	$recipients_sql = "SELECT CONCAT(fname, ' ', lname) AS name, email FROM account WHERE deleted=0 AND type_id=5";
	$recipients_qry = mysql_query($recipients_sql);
	
	while($recipients_res = mysql_fetch_assoc($recipients_qry)){
		$recipients[$recipients_res['email']] = $recipients_res['name']; 
	}
			
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
		mysql_query("UPDATE notification SET `status`=2, delivered=NOW() WHERE id=".$last_id)or die(mysql_error());	
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
		//successfully send
		//update the status to PUSHED (2) and the push time
		mysql_query("UPDATE notification SET `status`=2, delivered=NOW() WHERE id=".$last_id)or die(mysql_error());	
	}else{
		//not sent
		mysql_query("UPDATE notification SET `status`=1, delivered='0000-00-00 00:00:00', date='".$push_date."', time='".$push_time."' WHERE id=".$last_id)or die(mysql_error());	
	}
	*/

}
	
?>