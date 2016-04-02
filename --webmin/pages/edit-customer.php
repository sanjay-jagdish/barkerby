<?php
	 include_once('redirect.php'); 

		$id=$_GET['id'];
		
		$q=mysql_query("select * from account where id=".$id) or die(mysql_error());
		
		$r=mysql_fetch_assoc($q);
		
?>

<div class="page edit-customers-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                	if(isset($_GET['page'])){
						if(isset($_GET['subpage'])){
							echo ucwords(removeDashTitle($_GET['subpage']));
						}
						else{
							echo ucwords(removeDashTitle($_GET['page']));
						}
					}
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
            	<td><font style="color:#e67e22;">*</font>E-post :</td>
                <td><input type="text" class="customer-email txt" value="<?php echo $r['email'];?>"></td>
            </tr>
            <tr>
            	<td>Lösenord :</td>
                <td><input type="password" class="customer-pass txt"></td>
            </tr>
            <tr>
            	<td>Upprepa lösenord :</td>
                <td><input type="password" class="customer-confirm txt"></td>
            </tr>
            <tr>
            	<td><font style="color:#e67e22;">*</font>Förnamn :</td>
                <td><input type="text" class="customer-fname txt" value="<?php echo $r['fname'];?>"></td>
            </tr>
            <!--<tr>
            	<td>Middle Name :</td>
                <td><input type="text" class="user-mname txt"></td>
            </tr>-->
            <tr>
            	<td><font style="color:#e67e22;">*</font>Efternamn :</td>
                <td><input type="text" class="customer-lname txt" value="<?php echo $r['lname'];?>"></td>
            </tr>
            <tr>
            	<td>Företag :</td>
                <td><input type="text" class="customer-company txt" value="<?php echo $r['company'];?>"></td>
            </tr>
            <tr align="left">
            	<td colspan="2"><strong>Address</strong></td>
            </tr>
            <tr>
            	<td>Gata :</td>
                <td><input type="text" class="customer-street txt" value="<?php echo $r['street_name'];?>"></td>
            </tr>
            <tr>
            	<td>Ort  :</td>
                <td><input type="text" class="customer-city txt" value="<?php echo $r['city'];?>"></td>
            </tr>
            <!--<tr>
            	<td><font style="color:#e67e22;">*</font>State :</td>
                <td><input type="text" class="customer-state txt"></td>
            </tr>-->
            <tr>
            	<td>Postnummer :</td>
                <td><input type="text" class="customer-zip txt" value="<?php echo $r['zip'];?>"></td>
            </tr>
            <tr>
            	<td><font style="color:#e67e22;">*</font>Land :</td>
                <td>
                	<select class="customer-country">
                    	<?php
        					for($i=0;$i<count($countries);$i++){
						?>
                        <option value="<?php echo $countries[$i];?>" <?php if($countries[$i]==$garcon_settings['default_country']){ echo 'selected="selected"'; }?>><?php echo $countries[$i];?></option>
                        <?php		
							}	
						?>
                    </select>
                </td>
            </tr>
            <tr>
            	<td>Mobilnummer :</td>
                <td><input type="text" class="customer-phone txt" value="<?php echo $r['phone_number'];?>"></td>
            </tr>
            <!--<tr>
            	<td>Mobile Number :</td>
                <td><input type="text" class="customer-mobile txt"></td>
            </tr>-->
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn edit-customer-btn" value="Submit" data-rel="<?php echo $id; ?>"></td>
            </tr>
        </table>
    
        <div class="displaymsg"></div>
        
    </div>
    
</div>
<!-- end .page -->
