<?php
	session_start();
	ini_set('display_errors',0);
	
	include '../config/config.php';
	require 'PHPMailer/PHPMailerAutoload.php';	
	
	$option = $_POST['option'];//$_SESSION['login']['id'];
	$acct_1 = $_POST['acct_1'];//$_SESSION['login']['id'];
	$acct_2 = $_POST['acct_2'];
	$schedule_1 = $_POST['sched_1'];
	$schedule_2 = $_POST['sched_2'];
	$other_emp = $_POST['other_employees'];
	$day_current = mysql_real_escape_string(strip_tags($_POST['day_current']));
	$day_switch = mysql_real_escape_string(strip_tags($_POST['day_switch']));
	$des = mysql_real_escape_string(strip_tags($_POST['des']));	
	
	if($option=='swap'){
		$sql_statement = "INSERT INTO shift_request(account_id_1, account_id_2, sched_01, sched_02, date_1, date_2, approve, description) 
							  			          VALUES(". $acct_1 .", ". $acct_2 .", ". $schedule_1 .", ". $schedule_2 .", '". $day_current ."', '". $day_switch ."', 0, '" . $des . "')";
	}else{
		$sql_statement = "INSERT INTO shift_request(`option`, account_id_1, sched_01, date_1, approve, description, other_employee) 
						                          VALUES(2, ". $acct_1 .", ". $schedule_1 .", '". $day_current ."',  0, '" . $des . "', '".$other_emp."')";
	}

	$result = mysql_query($sql_statement);
	$last_id = mysql_insert_id();
	
if($last_id>0){	
ob_start();
?>
<style>
	div, th, td{
		font-family:Helvetica;
		font-size:11px;
		padding:5px;
	}
	th{ 
		background-color:#CCC; 
		padding:5px;
	}
</style>
	Hi,<br /><br />
    A Shift Rquest has been created with you as part of it.  Below are the details:<br /><br />
	
    <table cellpadding="4" cellspacing="0" border="1" style="border-collapse:collapse;">
      <thead>
        <tr>
          <th>SR</th>
          <th>Requesting Employee</th>
          <th>Sched.<br />
            Time</th>
          <th>Type</th>
          <th>Requested Employee</th>
          <th>Sched.<br />
            Time</th>
          <th>Remarks</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php
			$q=mysql_query("SELECT id, account_id_1, account_id_2, description, remarks, sched_01, sched_02,  date_1, date_2, `option`, other_employee, approve 
							FROM shift_request sr 
							WHERE sr.deleted=0 AND id=".$last_id) or die(mysql_error());
			
			while($r=mysql_fetch_assoc($q)){
				
				$status = "Pending";
				
				if (intval($r['approve']) == 1) {
					$status = "Approved";
				} else if (intval($r['approve']) == 2) {
					$status = "Disapproved";
				}
		?>
        <tr class="gradeX gradeX-<?php echo $r['id'];?> <?php if($r['approve']==1){ echo 'approved'; }?>" align="center">
          <td align="center" valign="top"><?php echo $r['id'];?></td>
          <td valign="top"><?php 
                            $acct_det1 = acctid_name_email($r['account_id_1']);
                            echo $acct_det1['name'];
							$recipients[$acct_det1['email']] = $acct_det1['name'];
                            ?></td>
          <td valign="top"><?php 
                                echo date('M. d, Y',strtotime($r['date_1']));
                                $time = schedid_time($r['sched_01']);
                                echo '<br>'.$time['start_time'].'&rarr;'.$time['end_time'];
                            ?></td>
          <td valign="top"><?php 
                            switch($r['option']){
                                case 1:
                                    $txt = 'swap';
                                    break;
                                case 2:
                                    $txt = 'drop';
                                    break;
                            }
                            
                            echo ucwords($txt);
                            ?></td>
          <td valign="top"><?php 
                            if($r['option']==1 || $r['approve']==1){
                                $acct_det2 = acctid_name_email($r['account_id_2']);
                                echo $acct_det2['name'];
								$recipients[$acct_det2['email']] = $acct_det2['name'];
                            }else{
                                $emp = explode(',',$r['other_employee']);
                                $names='';
                                foreach($emp as $k => $eid){
                                    $acct_det = acctid_name_email($eid);
                                    $names .= $acct_det['name'].'<br>';
                                	$recipients[$acct_det['email']] = $acct_det['name'];
								}
                                echo $names;
                            ?>
            <div style="text-align:left;"></div>
            <?php
                            }
                            ?></td>
          <td valign="top"><?php 
                            if($r['option']==1){						
                                echo date('M. d, Y',strtotime($r['date_2']));
                                $time = schedid_time($r['sched_02']);
                                echo '<br>'.$time['start_time'].'&rarr;'.$time['end_time'];						
                            }else{
                            
                            }
                            ?></td>
          <td valign="top"><?php echo $r['description'];?></td>
          <td valign="top"><?php echo $status;?></td>
        </tr>
        <?php		
                        }
                    ?>
      </tbody>
    </table><br />
You may contact your manager/admin for inquiries.
<?php
$content = ob_get_contents();
ob_end_clean();

	//phpMailer
	$subject = 'Shift Request '.$last_id.' has been created.';
	
	$message = $content;
	
	$mail = new PHPMailer();
	$mail->isSMTP();
	$mail->SMTPDebug = 1;
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
	
	mysql_query("INSERT INTO log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Added a shift request #".$last_id.".',now(),'".get_client_ip()."')");
}
?>