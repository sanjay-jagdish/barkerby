<?php 
include_once('redirect.php'); 
ini_set('display_errors',1);
$qry1 = "SELECT id, content FROM custom_content WHERE short_code='".htmlspecialchars(trim($_GET['code']))."'";
$res1 = mysql_query($qry1) or die("Error " . mysql_error());
$row1 = mysql_fetch_array($res1);
?>
<div class="page add-subcategory-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
					echo 'Sidor - '.ucwords(htmlspecialchars(trim($_GET['code'])));
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
            <tr>
            	<td>Content :</td>
                <td>
                <textarea class="sidor-content txt"><?php echo $row1['content'];?></textarea>
                </td>
            </tr>
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn update-sidor-content" value="Utför" data-id="<?php echo $row1['id']; ?>"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->
