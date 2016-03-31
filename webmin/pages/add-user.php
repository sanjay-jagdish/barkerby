                                <?php include_once('redirect.php'); ?>
<div class="page add-users-page">
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
            	<td>Roll :</td>
                <td>
                	<select class="user-type txt">
                    	<?php
                        	$q=mysql_query("select id,description from type where id<>1 and id<>5") or die(mysql_error());
							while($r=mysql_fetch_assoc($q)){
						?>
                        <option value="<?php echo $r['id'];?>"><?php echo $r['description'];?></option>
                        <?php		
							}
						?>
                    </select>
                </td>
            </tr>
            <tr>
            	<td><font style="color:#e67e22;">*</font>E-post :</td>
                <td><input type="text" class="user-email txt"></td>
            </tr>
            <tr>
            	<td><font style="color:#e67e22;">*</font>Lösenord :</td>
                <td><input type="password" class="user-pass txt"></td>
            </tr>
            <tr>
            	<td><font style="color:#e67e22;">*</font>Upprepa lösenord :</td>
                <td><input type="password" class="user-confirm txt"></td>
            </tr>
            <tr>
            	<td><font style="color:#e67e22;">*</font>Förnamn :</td>
                <td><input type="text" class="user-fname txt"></td>
            </tr>
            <!--<tr>
            	<td>Middle Name :</td>
                <td><input type="text" class="user-mname txt"></td>
            </tr>-->
            <tr>
            	<td><font style="color:#e67e22;">*</font>Efternamn :</td>
                <td><input type="text" class="user-lname txt"></td>
            </tr>
            <tr>
            	<td>Mobilnummer :</td>
                <td><input type="text" class="user-phone txt"></td>
            </tr>
            <!--<tr>
            	<td>Mobile Number :</td>
                <td><input type="text" class="user-mobile txt"></td>
            </tr>-->
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn add-user-btn" value="Utför"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->

                            