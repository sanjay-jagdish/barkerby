<?php include_once('redirect.php'); ?>
<div class="page add-currency-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                	/*if(isset($_GET['page'])){
						if(isset($_GET['subpage'])){
							echo ucwords(removeDashTitle($_GET['subpage']));
						}
						else{
							echo ucwords(removeDashTitle($_GET['page']));
						}
					}*/
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
            	<td>Valuta :</td>
                <td><input type="text" class="currency-name txt"></td>
            </tr>
            <tr>
            	<td>Förkortning :</td>
                <td><input type="text" class="abbr-name txt"></td>
            </tr>
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn add-currency-btn" value="Utför"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->
