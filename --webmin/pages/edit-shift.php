<?php include_once('redirect.php'); ?>
<div class="page edit-shift-page">
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
    	<div class="page-content-left">
        	<?php
				$q = mysql_query("select sr.id, sr.description, sr.date from shift_request sr where sr.id = " . $_GET['id'] . ";");
				$row = mysql_fetch_assoc($q);
			?>
            <table>
                <tr>
                    <td><font style="color:#e67e22;">*</font>Date & Time :</td>
                    <td><input type="text" id="shift-datetime" class="shift-datetime txt" name="shift-datetime" value="<?php echo $row['date'];?>"></td>
                </tr>
                <tr>
                    <td><font style="color:#e67e22;">*</font>Description :</td>
                    <td><textarea class="shift-desc txt"><?php echo $row['description']; ?></textarea></td>
                </tr>
                <tr>
                    <td colspan="2" align="right"><input type="button" class="btn edit-shift-btn" value="Edit Shift" data-rel="<?php echo $_GET['id']; ?>"></td>
                </tr>
            </table>
        </div>
        <div class="page-content-right">
        	<div id='preview'></div>
        </div>
            
        <div class="clear"></div>
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->
