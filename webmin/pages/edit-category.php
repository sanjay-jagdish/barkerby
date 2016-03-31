<?php include_once('redirect.php'); ?>
<div class="page edit-category-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                	echo 'Redigera meny';
				?>
            </h2>
        </div>
      	<!-- end .page-header-left -->
    </div>
    <!-- end .page-header -->
    <div class="clear"></div>
    
    <div class="page-content">
    	<?php
        	$q=mysql_query("select name,description from category where id=".$_GET['id']);
			$row=mysql_fetch_assoc($q);
		?>
    	<table>
            <tr>
            	<td>Meny :</td>
                <td><input type="text" class="category-name txt" value="<?php echo $row['name']; ?>"></td>
            </tr>
            <tr>
            	<td>Beskrivning :</td>
                <td><textarea class="category-desc txt"><?php echo $row['description']; ?></textarea></td>
            </tr>
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn edit-category-btn" value="Utför" data-rel="<?php echo $_GET['id']; ?>" data-id="<?php echo $_GET['nav']; ?>"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->
