<?php include_once('redirect.php'); ?>
<div class="page shift-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
					$a_type = $_SESSION['login']['type'];
					$a_id = $_SESSION['login']['id'];
					
					echo 'Avancerade inställningar';
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
$mail->CharSet = 'UTF-8';
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
.setting-form{
	margin-bottom: 30px !important;
	margin: 0 20px;
	background-color: #f4f4f4;
	font-family: Roboto;
	font-weight: 100;
}
.legend{
	margin: 0 !important;
	font-size: 22px;
	color:#FFF;
	display:block;
	padding:5px 5px 5px 30px;
	font-weight: 100;
	background-color: #687174;	
}
.left_name,
.first_left_name{ 
	width: 150px;
	text-align: left;
	font-size: 17px;
	font-weight: 300;
	color: #505458;
	padding: 5px;
}
.first_left_name{
	width: 50px !important;
}
input[type="text"],
input[type="password"],
.first_right_input input{
	width: 350px;
	margin-right: 10px;
	font-size: 14px;
	padding: 8px;
	outline: none;
	font-weight: 300;
	font-family: Roboto;
	margin-bottom: 5px;
	color: #000;
	border: none;
	border-radius: 2px;
}
.first_right_input input{
	margin-right: 0 !important;
}
table.inputs{
	padding: 5px 50px;
}
.note{
   font-size: 14px;
   color: #a6a6a6;
}
.note strong{
	color: #7a7a7a;
	font-weight: 500;
}
input[name="save_gen_settings"]{
	background-color: #e67e22 !important;
	font-size:17px;
	font-family: 'Roboto';
	font-weight: 100;
	padding: 15px 35px;
	border-radius: 2px;
	margin-left: 20px;
}
div.select-container {
	background: url('./images/dropdown.png') no-repeat top right #FFF;
	border-radius: 2px;
	float:left; 
	margin-bottom: 5px;
}
.select-container select{
	-webkit-appearance: none;
	border: 0 !important;
	-moz-appearance: window;
	-o-appearance: none;
	appearance: none;
	font-size: 17px;
	text-transform: uppercase;
	box-shadow: none;
	height: 35px;
	outline: none;
	padding: 5px 10px;
	background: url('./images/dropdown.png') no-repeat top right #FFF;
	text-indent: 0.01px;
	text-overflow: "";
	font-weight: 300;
	font-family: Roboto;
}
select[name="default_country"]{
	width: 365px;
}
select[name="week_starts"]{
	width: 200px;
}
select[name="smtp_security"]{
	width: 120px;
}
input[disabled="disabled"] {
	color: #999999 !important;
}
</style>
    
    <form method="post">
		<!--<fieldset>
        	<legend>
            	Max Antal Personer
            </legend>
            
            <div class="left_name">Max:</div>
        	<div class="right_input">
            	<input type="text" name="max_guest" value="<?php //echo $settings['max_guest']; ?>" style="width:30px; text-align:center;" /> <font color="#999999">Max antal personer per boking via hemsida och app.</font>
            </div>
        </fieldset>-->
		<div class="setting-form">
        	<span class="legend">
            	Hemsida URL
            </span>
            
            <table class="inputs">
                <tr>
                    <td class="first_left_name">URL:</td>
                    <td class="first_right_input"><input type="text" name="site_url" value="<?php echo $settings['site_url']; ?>" /></td>
                </tr> 
                <tr>
                	<td colspan="2" style="text-align:right; font-style:italic; font-size:14px;"><font color="#999999">Domain Name: http://www.exempeldomän.se</font></td>
                </tr>
            </table>
        </div>
		<div class="setting-form">
        	<span class="legend">
            	Tid och Plats
            </span>
            
           <table class="inputs">
                <tr>
                    <td class="left_name">Tidszon:</td>
        			<td class="right_input"><input type="text" value="<?php echo $settings['timezone']; ?>" readonly="readonly" disabled="disabled"/></td>
            		<td class="note"><font color="#999999"><strong>Readonly</strong> &nbsp;Kontakta support om du vill ändra din tidszon.</font></td>
                </tr>
            	
                <tr>
            		<td class="left_name">Tidsskillnad:</td>
        			<td class="right_input"><input type="text" value="<?php echo $settings['time_offset']; ?>" readonly="readonly" disabled="disabled"/></td>
            		<td class="note"><font color="#999999"><strong>Readonly</strong> &nbsp;Kontakta support om du vill ändra din time offset.</font></td>
                </tr>
            	<tr>
                    <td class="left_name">Land:</td>
        			<td class="right_input">
                    	<div class="select-container">
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
            		</td>
                    <td class="note"></td>
            	</tr>
			
            	<tr>
            
                    <td class="left_name">Första veckodag :</td>
                    <td class="right_input">
                        <div class="select-container">
                            <select name="week_starts">
                                <?php
                                
                                $days = array(1=>'Måndag','Tisdag','Onsdag','Torsdag','Fredag','Lördag','Söndag');
                                foreach($days as $key => $day){
                                ?>
                                    <option value="<?php echo $key; ?>" <?php if($settings['week_starts']==$key){ echo 'selected="selected"'; } ?>><?php echo $day; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                     </td>
                     <td class="note"></td>
                 </tr>
             
             </table>       
       </div>
		
		<div class="setting-form">
        	<span class="legend">
            	SMTP inställningar
            </span>
            
            <table class="inputs">
            	<tr>
                	<td colspan="3" style="font-size:17px; padding: 20px 0; font-weight: 100;"><strong style="color:#8a6d3b; font-weight: 500;">VARNING:</strong> Gör inga ändringar om du inte vet vad all innebär.</td>
                </tr>
                
            	<tr>
                    <td class="left_name">Host:</td>
                    <td class="right_input"><input type="text" name="smtp_host" value="<?php echo $settings['smtp_host']; ?>" /></td>
                    <td class="note"><font color="#999999">e.g. smtp.gmail.com</font></td>
         		</tr>
                
                <tr>
                    <td class="left_name">Port:</td>
                    <td class="right_input"><input type="text" name="smtp_port" value="<?php echo $settings['smtp_port']; ?>" style="width:48px;" /></td>
                    <td class="note"><font color="#999999">e.g. 465</font></td>
        		</tr>
                
                <tr>
                    <td class="left_name">Security:</td>
                    <td class="right_input">
                    	<div class="select-container">
                            <select name="smtp_security">
                                <option value="ssl" <?php if($settings['smtp_security']=='ssl'){ echo'selected="selected"'; } ?>>SSL</option>
                                <option value="tls" <?php if($settings['smtp_security']=='tls'){ echo'selected="selected"'; } ?>>TLS</option>
                            </select> 
                        </div>
                    </td>
                    <td class="note"></td>
              	</tr>
                
                <tr>         
                    <td class="left_name">Användarnamn:</td>
                    <td class="right_input"><input type="text" name="smtp_user" value="<?php echo $settings['smtp_user']; ?>" /> </td>
                    <td class="note"><font color="#999999">e.g. user@domain.com</font></td>
        		</tr>
                
                <tr>
                    <td class="left_name">Lösenord:</td>
                    <td class="right_input"><input type="password" name="smtp_pass" value="" /></td>
                    <td class="note"><font color="#999999">Ifylles endast om du vill ändra lösenord.</font></td> 
        		</tr>
                
                <tr>
                    <td class="left_name">Avsändarnamn:</td>
                    <td class="right_input"><input type="text" name="smtp_from" value="<?php echo $settings['smtp_from']; ?>" /></td>
                    <td class="note"><font color="#999999"><em>Namnet på avsändaren.</em></font></td>
             	</tr>
                
           </table>
    
  		
        </div>
        
        
        <div class="setting-form">
            <span class="legend">
            	Klarna checkout
            </span>
            
            <table class="inputs">
                <tr>
                    <td class="left_name">Private key:</td>
                    <td class="right_input"><input type="text" name="private_key" value="<?php echo $settings['private_key']; ?>" /></td>
                    <td class="note"><font color="#999999">e.g. a1b2c3d4e5f6g7h8i9j10k11l12m13</font></td>
                </tr>                
            </table>  		
        </div>
        
      
		<div class="setting-form">
        	<span class="legend">
            	Valuta
            </span>
            
            <table class="inputs" style="padding: 30px 50px;">
            	<tr>
                    <td class="left_name">Förinställd valuta:</td>
                    <td class="right_input" style="margin-top:3px;"><b style="margin-right: 20px; font-size: 14px;"><?php echo lcfirst($settings['default_currency']); ?></b></td>
                    <td style="font-size: 14px;">Klicka <a href="http://limoneristorante.se/webmin/crisp.php?page=currency&parent=settings">här</a> om du vill ändra valuta.</td>
				</tr>
			</table>       
        </div>
        
       
        
        <!--<fieldset>
        	<legend>
            	Business Hours
            </legend>
            
            <div class="left_name">Start Time:</div>
        	<div class="right_input"><input type="text" name="business_start_time" value="<?php //echo $settings['business_start_time']; ?>" class="business_start_time" /></div> 
            <div class="left_name">End Time:</div>
        	<div class="right_input"><input type="text" name="business_end_time" value="<?php //echo $settings['business_end_time']; ?>" class="business_end_time" /></div> 
            
        </fieldset>-->
        <!--<fieldset>
            <legend>
                Book and Order Online Content
            </legend>
            
           
            <div class="right_input">
                <textarea class="new_content" rows="20" cols="100" name="new_content"><?php //echo $settings['new_content']; ?></textarea>
            </div> 
            
        </fieldset>-->
	  <input type="submit" class="btn" name="save_gen_settings" value="Spara inställningar" />
      </form>
	</div>
</div>