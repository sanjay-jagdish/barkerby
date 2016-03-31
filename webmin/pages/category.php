
<?php
 include_once('redirect.php'); 

function create_drop_down( $td_id, $cat_id ){

    $select = "";
    $list = 0;
    // $selected = "";

    $query = mysql_query("SELECT count(name) AS cat FROM category where deleted=0 ");

    while($row = mysql_fetch_assoc($query)){
        $list = $row['cat'];
    }

        $select .= '<select name="order_list" class="dropdown-'.$td_id.'" onChange="update_category_order('.$td_id.')" >';

            $select .= '<option '.$selected.' value=" -'.$cat_id.'">--</option>';

            for ($i = 0; $i < $list; $i++) {

                $selected = "";

                $query_selected = mysql_query("SELECT * FROM `category` WHERE id = '".$cat_id."' ");

                while($r = mysql_fetch_assoc($query_selected)){
                    $order_number = $r['order'];
                }

                if($order_number == ($i+1)){
                    $selected = "selected";
                }

                $select .= '<option '.$selected.' value="'.($i+1).'-'.$cat_id.'">'.($i+1).'</option>';
            }

        $select .= '</select>';

    return $select;

}


?>

<div class="page category-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
					echo 'Meny';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
        <div class="page-header-right">
        	<a href="?page=category&subpage=add-category&parent=menu" class="add-category">Skapa ny</a>
        </div>
        <!-- end .page-header-right -->
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
    	<table cellpadding="0" cellspacing="0" border="0" class="display" id="thecategories">
            <thead>
                <tr>
                	<th>#</th>
                    <th>Meny</th>
                    <th>Beskrivning</th>
                    <th>Ordningsföljd</th>
                    <th>Åtgärd</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $q=mysql_query("select name,id,description from category where deleted=0 order by id desc") or die(mysql_error());
                    
					$count=0;
                    while($r=mysql_fetch_array($q)){
                		$count++;
				?>
                    <tr class="gradeX gradeX-<?php echo $r[1];?>" align="center">
                    	<td><?php echo $count; ?></td>
                        <td><?php echo ucwords($r[0]);?></td>
                        <td><?php echo $r[2];?></td>
                        <td><?php echo create_drop_down( $count, $r['id'] ); ?></td>
                        <td><a href="?page=category&subpage=edit-category&id=<?php echo $r[1]; ?>&parent=menu" class="edit-category" title="Redigera kategori"><img src="images/edit.png" alt="Redigera kategori"></a> <?php if($_SESSION['login']['type']<3){ ?><a href="#" class="delete-category" title="Radera kategori" data-rel="<?php echo $r[1]; ?>"><img src="images/delete.png" alt="Radera kategori"></a><?php } ?></td>
                    </tr>
                <?php		
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                	<th>#</th>
                    <th>Meny</th>
                    <th>Beskrivning</th>
                    <th>Ordningsföljd</th>
                    <th>Åtgärd</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="fade"></div>
<div class="delete-category-box modalbox">
	<h2>Bekräfta borttagning<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Vill du fortsätta?</p>
        <input type="button" value="Utför">
    </div>
</div>