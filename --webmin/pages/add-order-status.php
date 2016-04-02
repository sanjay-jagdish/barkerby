<?php include_once('redirect.php'); ?>
<div class="page add-order-status-page">
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
            	<td>Status :</td>
                <td><input type="text" class="status-name txt"></td>
            </tr>
            <tr style="display:none;">
            	<td>Bokning :</td>
                <td>
                	<select class="status-type txt">
                    	<?php
                        	$q=mysql_query("select id,description from reservation_type where id = 2");
							while($r=mysql_fetch_array($q)){
						?>
                      	<option value="<?php echo $r[0]; ?>"><?php echo $r[1]; ?></option>  
                        <?php		
							}
						?>
                    </select>
                </td>
            </tr>
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn add-order-status-btn" value="Utför"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->
