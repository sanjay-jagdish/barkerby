<?php include_once('redirect.php'); ?>
<div class="page add-customers-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
					echo 'Skapa ny';
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
                <td><input type="text" class="customer-email txt"></td>
            </tr>
            <tr>
            	<td><font style="color:#e67e22;">*</font>Lösenord :</td>
                <td><input type="password" class="customer-pass txt"></td>
            </tr>
            <tr>
            	<td><font style="color:#e67e22;">*</font>Upprepa lösenord :</td>
                <td><input type="password" class="customer-confirm txt"></td>
            </tr>
            <tr>
            	<td><font style="color:#e67e22;">*</font>Förnamn :</td>
                <td><input type="text" class="customer-fname txt"></td>
            </tr>
            <!--<tr>
            	<td>Middle Name :</td>
                <td><input type="text" class="user-mname txt"></td>
            </tr>-->
            <tr>
            	<td><font style="color:#e67e22;">*</font>Efternamn :</td>
                <td><input type="text" class="customer-lname txt"></td>
            </tr>
            <tr>
            	<td>Företag :</td>
                <td><input type="text" class="customer-company txt"></td>
            </tr>
            <tr align="left">
            	<td colspan="2"><strong>Address</strong></td>
            </tr>
            <tr>
            	<td>Gata :</td>
                <td><input type="text" class="customer-street txt"></td>
            </tr>
            <tr>
            	<td>Ort  :</td>
                <td><input type="text" class="customer-city txt"></td>
            </tr>
            <!--<tr>
            	<td><font style="color:#e67e22;">*</font>State :</td>
                <td><input type="text" class="customer-state txt"></td>
            </tr>-->
            <tr>
            	<td>Postnummer :</td>
                <td><input type="text" class="customer-zip txt"></td>
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
                <td><input type="text" class="customer-phone txt"></td>
            </tr>
            <!--<tr>
            	<td>Mobile Number :</td>
                <td><input type="text" class="customer-mobile txt"></td>
            </tr>-->
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn add-customer-btn" value="Utför"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
        
    </div>
    
</div>
<!-- end .page -->
