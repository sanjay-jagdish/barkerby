<?php include_once('redirect.php'); ?>
<div class="page edit-currency-page">
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
    	<?php
        	$q=mysql_query("select name,shortname from currency where id=".$_GET['id']);
			$row=mysql_fetch_assoc($q);
		?>
    	<table>
            <tr>
            	<td>Currency Name :</td>
                <td><input type="text" class="currency-name txt" value="<?php echo $row['name'];?>"></td>
            </tr>
            <tr>
            	<td>Abbreviation :</td>
                <td><input type="text" class="abbr-name txt" value="<?php echo $row['shortname'];?>"></td>
            </tr>
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn edit-currency-btn" value="Edit Currency" data-rel="<?php echo $_GET['id']; ?>"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->
