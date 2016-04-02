<?php include_once('redirect.php'); ?>
<div class="page edit-subcategory-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                	echo 'Redigera kategori';
				?>
            </h2>
        </div>
      	<!-- end .page-header-left -->
    </div>
    <!-- end .page-header -->
    <div class="clear"></div>
    
    <div class="page-content"> 
    	<?php
        	$q=mysql_query("select name, id, category_id from sub_category where id=".$_GET['id']);
			$row=mysql_fetch_assoc($q);
		?>
    	<table>
            <tr>
            	<td>Meny :</td>
                <td>
                	<select class="subcategory-category">
                	<?php
                    	$q=mysql_query("select name, id from category where deleted=0");
						while($r=mysql_fetch_assoc($q)){
					?>
                    	<option value="<?php echo $r['id']; ?>" <?php if($r['id']==$row['category_id']) echo 'selected="selected"';?>><?php echo $r['name']; ?></option>
                    <?php		
						}
					?>
                    </select>
                </td>
            </tr>
            <tr>
            	<td>Kategori :</td>
                <td><input type="text" class="subcategory-name txt" value="<?php echo $row['name']; ?>"></td>
            </tr>
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn edit-subcategory-btn" value="Utför" data-rel="<?php echo $_GET['id']; ?>" data-id="<?php echo $_GET['nav']; ?>"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->
