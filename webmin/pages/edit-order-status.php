<?php include_once('redirect.php'); ?>
<div class="page edit-order-status-page">
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
        	$q=mysql_query("select description, reservation_type_id from status where id=".$_GET['id']);
			$row=mysql_fetch_assoc($q);
		?>
        <table>
            <tr>
            	<td>Status :</td>
                <td><input type="text" class="status-name txt" value="<?php echo $row['description'];?>"></td>
            </tr>
            <tr style="display:none;">
            	<td>Bokning :</td>
                <td>
                	<select class="status-type txt">
                    	<?php
                        	$q=mysql_query("select id,description from reservation_type whre id = 2");
							while($r=mysql_fetch_array($q)){
						?>
                      	<option value="<?php echo $r[0]; ?>" <?php if($r[0]==$row['reservation_type_id']) echo 'selected="selected"';?>><?php echo $r[1]; ?></option>  
                        <?php		
							}
						?>
                    </select>
                </td>
            </tr>
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn edit-order-status-btn" value="Edit Status" data-rel="<?php echo $_GET['id']; ?>"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->
