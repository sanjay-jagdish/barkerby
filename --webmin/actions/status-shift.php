<?php
	session_start();
	
	include '../config/config.php';
	require 'PHPMailer/PHPMailerAutoload.php';	
	
	
	$val = $_POST['val'];
	$id = $_POST['id'];
	
	mysql_query("update shift_request set approve = " . $val . " where id = " . $id);

	if($val==1){
		$status_txt = 'APPROVED';
	}else{
		$status_txt = 'DISAPPROVED';
	}
	
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
    
    Shift Rquest No. <?php echo $id; ?> has been <?php echo $status_txt; ?> with you as part of it.  Below are the details:<br /><br />
	
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
							WHERE sr.deleted=0 AND id=".$id) or die(mysql_error());
			
			while($r=mysql_fetch_assoc($q)){
				
				$conflict = array();

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
          		    if($r['approve']==2 && $r['option']==2){
          		    	mysql_query('UPDATE shift_request SET account_id_2=0 WHERE id='.$id);
          		    }
          
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
	$subject = 'Shift Request '.$id.' has been '.$status_txt.'.';
	
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
	    //$mail->addAddress($to, str_replace('&nbsp;',' ',$name);
	    $mail2 = clone $mail;
	    $mail2->AddAddress($to, str_replace('&nbsp;',' ',$name));
	    $mail2->send();
	}		
?>