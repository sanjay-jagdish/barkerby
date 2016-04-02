<?php include_once('redirect.php'); ?>
<div class="page add-subcategory-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
					echo 'Skapa ny kategori';
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
            	<td>Meny :</td>
                <td>
                	<select class="subcategory-category">
                	<?php
                    	$q=mysql_query("select name, id from category where deleted=0");
						while($r=mysql_fetch_assoc($q)){
					?>
                    	<option value="<?php echo $r['id']; ?>"><?php echo $r['name']; ?></option>
                    <?php		
						}
					?>
                    </select>
                </td>
            </tr>
            <tr>
            	<td><font style="color:#e67e22;">*</font>Kategori :</td>
                <td><input type="text" class="subcategory-name txt"></td>
            </tr>
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn add-subcategory-btn" value="Utför" data-id="<?php echo $_GET['nav']; ?>"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->
