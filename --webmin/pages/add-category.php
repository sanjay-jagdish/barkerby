<?php include_once('redirect.php'); ?>
<div class="page add-category-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
					echo 'Skapa ny meny';
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
            	<td><font style="color:#e67e22;">*</font>Meny :</td>
                <td><input type="text" class="category-name txt"></td>
            </tr>
            <tr>
            	<td>Beskrivning :</td>
                <td><textarea class="category-desc txt"></textarea></td>
            </tr>
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn add-category-btn" value="Utför" data-id="<?php echo $_GET['nav']; ?>"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->
