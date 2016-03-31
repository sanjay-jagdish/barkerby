<?php
     include_once('redirect.php'); 
$q=mysql_query("select * from account where id=".$_GET['id']);
	$row=mysql_fetch_assoc($q);
?>

<div class="page edit-users-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                    echo 'Redigera användare';
				?>
            </h2>
        </div>
      	<!-- end .page-header-left -->
    </div>
    <!-- end .page-header -->
    <div class="clear"></div>
    
    <div class="page-content">
    
    	<table>
        	<tr>
            	<td>Roll :</td>
                <td>
                	<select class="user-type txt">
                    	<?php
                        	$q=mysql_query("select id,description from type where id<>1 and id<>5") or die(mysql_error());
							while($r=mysql_fetch_assoc($q)){
						?>
                        <option value="<?php echo $r['id'];?>" <?php if( $r['id']==$row['type_id']) echo 'selected="selected"';?>><?php echo $r['description'];?></option>
                        <?php		
							}
						?>
                    </select>
                </td>
            </tr>
            <tr>
            	<td><font style="color:#e67e22;">*</font>E-post :</td>
                <td><input type="text" class="user-email txt" value="<?php echo $row['email'];?>"></td>
            </tr>
            
            <tr>
            	<td>Lösenord :</td>
                <td><input type="password" class="user-pass txt test"></td>
            </tr>
            <tr>
            	<td>Upprepa lösenord :</td>
                <td><input type="password" class="user-confirm txt"></td>
            </tr>
            
            <!--<tr>
            	<td>Lösenord :</td>
                <td><a href="#" class="edit-password" data-rel="<?php //echo $_GET['id']; ?>">[ Redigera lösenord ]</a></td>
            </tr>-->
            <tr>
            	<td><font style="color:#e67e22;">*</font>Förnamn :</td>
                <td><input type="text" class="user-fname txt" value="<?php echo $row['fname'];?>"></td>
            </tr>
            <!--<tr>
            	<td>Middle Name :</td>
                <td><input type="text" class="user-mname txt" value="<?php //echo $row['mname'];?>"></td>
            </tr>-->
            <tr>
            	<td><font style="color:#e67e22;">*</font>Efternamn :</td>
                <td><input type="text" class="user-lname txt" value="<?php echo $row['lname'];?>"></td>
            </tr>
            <tr>
            	<td>Mobilnummer :</td>
                <td><input type="text" class="user-phone txt" value="<?php echo $row['phone_number'];?>"></td>
            </tr>
           <!--  <tr>
            	<td>Mobile Number :</td>
                <td><input type="text" class="user-mobile txt" value="<?php //echo $row['mobile_number'];?>"></td>
            </tr> -->
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn edit-user-btn" value="Utför" data-rel="<?php echo $_GET['id']?>"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->

<div class="fade"></div>
<div class="edit-password-box modalbox">
	<h2>Update Password<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
   		<input type="password" class="current-password" placeholder="Current Password">
        <input type="password" class="new-password" placeholder="New Password">
        <input type="password" class="confirm-new-password" placeholder="Confirm New Password">
        <input type="button" value="Update">
        <div class="displaymsg"></div>
    </div>
</div>
