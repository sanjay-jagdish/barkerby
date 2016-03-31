<?php include_once('redirect.php'); ?>
<div class="page shift-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
					$a_type = $_SESSION['login']['type'];
					$a_id = $_SESSION['login']['id'];
					
                	if(isset($_GET['page'])){
						if(isset($_GET['subpage'])){
							echo ucwords(removeDashTitle($_GET['subpage']));
						}
						else{
							echo "General Settings";
						}
					}
				?>
            </h2>
        </div>
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">

<?php
if(isset($_POST['save_gen_settings'])){
	
	echo '<div style="padding:12px; background-color:#ff9;">';
	
	$to_update=0; $updated=0;
	
	foreach($_POST as $field => $value){
		if(trim($value)!=''){
			$to_update++;
			if(mysql_query("UPDATE settings SET var_value='".trim($value)."' WHERE var_name='".$field."'")){
				$updated++;
			}	
		}
	}

	if($updated==0){
		echo '<font color="red"><strong>SAVE FAILED</strong>. &nbsp;An error has occurred while trying to save the details.</font>';
	}else{
		if($updated==$to_update){
			echo '<font color="green"><strong>SETTINGS UPDATED</strong>. &nbsp;Settings has been successfully updated.</font>';
		}else{
			echo '<font color="orange"><strong>NOT ALL SETTINGS WERE UPDATED</strong>. &nbsp;Kindly review the details below as some data were not successfuly saved.</font>';
		}	
	}
	echo '</div><br>';
}

$settings_sql = "SELECT * FROM settings";
$settings_qry = mysql_query($settings_sql);

while($settings_res = mysql_fetch_assoc($settings_qry)){
	$settings[$settings_res['var_name']] = $settings_res['var_value'];
}

require 'actions/PHPMailer/PHPMailerAutoload.php';	
				
$subject = 'SMTP Successfully Updated '.$settings['smtp_user'].' => '.$settings['smtp_from'];

$message = '<b>'.$subject.'</b>';

$mail = new PHPMailer();
$mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->Host = $settings['smtp_host'];//'smtp.gmail.com';
$mail->Port = $settings['smtp_port'];
$mail->SMTPSecure = $settings['smtp_security'];
$mail->SMTPAuth = true;
$mail->Username = $settings['smtp_user'];
$mail->Password = $settings['smtp_pass'];
$mail->setFrom($settings['smtp_user'], $settings['smtp_from']);
$mail->Subject = $subject;
$mail->msgHTML($message);	
$mail->AddAddress('garcon.test.email@gmail.com', 'Garcon Test Mail');
if(!$mail->send()){
	echo '<div style="padding:12px; background-color:#ff9; border:red solid thin;">';
	echo '<font color="red"><strong>SMTP Connection Failed:</strong> &nbsp;Something is wrong with the SMTP Settings.  &nbsp;
			Kindly correct this otherwise, the Garcon App will not be able to send out email notifications.</font>';
	echo '</div><br>';
}
	
?>
		<style>
		fieldset { 
			border:1px solid #e67e22; 
			padding:24px;
		}
		
		legend {
		  padding: 0.2em 0.5em;
		  border:1px solid e67e22;
		  color:#eee;
		  font-size:14px;;
		  text-align:left;
		  background-color:#e67e22;
		  }		
          
          .left_name{ 
		  	display: inline-block;
			width:100px;
			float:left;
			text-align:right;
			padding: 4px 12px 0px 0px;
		  }
		  
		  input{
			  width:250px;
		  }
          </style>
    
    <form method="post">
		<fieldset>
        	<legend>
            	Maximum Guests
            </legend>
            
            <div class="left_name">Max:</div>
        	<div class="right_input">
            	<input type="text" name="max_guest" value="<?php echo $settings['max_guest']; ?>" style="width:30px; text-align:center;" /> <font color="#999999">Maximum number of guests per booking.</font>
            </div>
        </fieldset>


		<fieldset>
        	<legend>
            	Site URL
            </legend>
            
            <div class="left_name">URL:</div>
        	<div class="right_input">
            	<input type="text" name="site_url" value="<?php echo $settings['site_url']; ?>" /> <br />
            	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <font color="#999999">Domain Name: <?php echo $_SERVER['HTTP_HOST']; ?></font>
            </div>
        </fieldset>

		<fieldset>
        	<legend>
            	Localization
            </legend>
            
            <div class="left_name">Time Zone:</div>
        	<div class="right_input">
            	<input type="text" value="<?php echo $settings['timezone']; ?>" readonly="readonly" disabled="disabled" /> 
            	<font color="#999999"><strong>Readonly</strong> &nbsp;Contact support to change your Time Zone.</font>
            </div>

            <div class="left_name">Default Country:</div>
        	<div class="right_input">
            	<select name="default_country">
                	<?php
					foreach($countries as $kc => $country){
					?>
                    	<option value="<?php echo $country; ?>" <?php if($settings['default_country']==$country){ echo 'selected="selected"'; } ?>><?php echo $country; ?></option>
                    <?php
					}
					?>
                </select>          
            </div>

            <div class="left_name">Week Starts On :</div>
        	<div class="right_input">
            	<select name="week_starts">
                	<?php
					$days = array(1=>'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
					foreach($days as $key => $day){
					?>
                    	<option value="<?php echo $key; ?>" <?php if($settings['week_starts']==$key){ echo 'selected="selected"'; } ?>><?php echo $day; ?></option>
                    <?php
					}
					?>
                </select>
            </div>
       
       
       </fieldset>
		<br />

		<fieldset>
        	<legend>
            	SMTP Settings
            </legend>
	
    		<p><strong>WARNING:</strong> Only change/update the details below only if you know what you are dealing with.</p>
			<br />
            
            <div class="left_name">Host:</div>
       	 	<div class="right_input"><input type="text" name="smtp_host" value="<?php echo $settings['smtp_host']; ?>" /> 
       	    <font color="#999999">e.g. smtp.gmail.com</font></div>
 
            <div class="left_name">Port:</div>
       	 	<div class="right_input"><input type="text" name="smtp_port" value="<?php echo $settings['smtp_port']; ?>" style="width:48px;" /> 
       	    <font color="#999999">e.g. 465</font></div>

            <div class="left_name">Security:</div>
       	 	<div class="right_input">
            	<select name="smtp_security">
                	<option value="ssl" <?php if($settings['smtp_security']=='ssl'){ echo'selected="selected"'; } ?>>SSL</option>
                    <option value="tls" <?php if($settings['smtp_security']=='tls'){ echo'selected="selected"'; } ?>>TLS</option>
                </select> 
       	    </div>
 
            <div class="left_name">Username:</div>
       	 	<div class="right_input"><input type="text" name="smtp_user" value="<?php echo $settings['smtp_user']; ?>" /> 
       	    <font color="#999999">e.g. user@domain.com</font></div>

            <div class="left_name">Password:</div>
       	 	<div class="right_input"><input type="password" name="smtp_pass" value="" /> <font color="#999999">Only supply if you wish to change the password.</font></div> 

            <div class="left_name">From Name:</div>
   	 	  <div class="right_input"><input type="text" name="smtp_from" value="<?php echo $settings['smtp_from']; ?>" /> 
       	    <font color="#999999"><em>The name to appear in the email's FROM field.</em></font></div>
    
  		
      </fieldset>
	  <input type="submit" name="save_gen_settings" value="SAVE SETTINGS" style="width:100px; margin:20px;" />
      </form>
	</div>

</div>