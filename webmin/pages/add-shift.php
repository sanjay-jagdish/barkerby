<?php include_once('redirect.php'); ?>
<div class="page add-shift-page">
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
            <table>
                <tr>
                    <td><font style="color:#e67e22;">*</font>Date & Time :</td>
                    <td><input type="text" id="shift-datetime" class="shift-datetime txt" name="shift-datetime"></td>
                </tr>
                <tr>
                    <td><font style="color:#e67e22;">*</font>Description :</td>
                    <td><textarea class="shift-desc txt"></textarea></td>
                </tr>
                <tr>
                    <td colspan="2" align="right"><input type="button" class="btn add-shift-btn" value="Add Shift"></td>
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
