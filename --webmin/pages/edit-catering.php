<?php include_once('redirect.php'); ?>
<div class="page edit-catering-page">
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
        	$q=mysql_query("select * from catering where id=".$_GET['id']);
			$row=mysql_fetch_assoc($q);
		?>
        <table>
            <tr>
            	<td>Name :</td>
                <td><input type="text" class="catering-name txt" value="<?php echo $row['name']; ?>"></td>
            </tr>
            <tr>
            	<td>Description :</td>
                <td><textarea class="catering-desc txt"><?php echo $row['description']; ?></textarea></td>
            </tr>
            <tr>
            	<td>Price :</td>
                <td><input type="text" class="catering-price txt" value="<?php echo $row['price']; ?>"></td>
            </tr>
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn edit-catering-btn" value="Edit Catering" data-rel="<?php echo $_GET['id']; ?>"></td>
            </tr>
        </table>
        
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->
