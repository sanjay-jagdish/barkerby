<?php include_once('redirect.php'); ?>
<div class="page add-catering-page">
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
            	<td>Name :</td>
                <td><input type="text" class="catering-name txt"></td>
            </tr>
            <tr>
            	<td>Description :</td>
                <td><textarea class="catering-desc txt"></textarea>
                	<!--<br><span style="font-size:10px;"><font style="color:red;">Note:</font> When adding a title in the description field, <br> kindly use ( h4 tag), for the paragraph ( p tag ), <br> for the bold ones ( strong tag)</span>-->
                </td>
            </tr>
            <tr>
            	<td>Price :</td>
                <td><input type="text" class="catering-price txt"></td>
            </tr>
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn add-catering-btn" value="Utför"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->
