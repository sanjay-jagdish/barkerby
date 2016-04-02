
<?php
 include_once('redirect.php'); 

function create_drop_down( $td_id, $cat_id, $sub_id ){

    $select = "";
    $list = 0;
    // $selected = "";

    $query = mysql_query("SELECT count(name) AS subcat FROM sub_category WHERE category_id = '".$cat_id."' and deleted = 0 ");

    while($row = mysql_fetch_assoc($query)){
        $list = $row['subcat'];
    }

        $select .= '<select name="order_list" class="dropdown-'.$td_id.'" onChange="update_sub_category_order('.$td_id.')" >';

            $select .= '<option '.$selected.' value=" -'.$sub_id.'">--</option>';

            for ($i = 0; $i < $list; $i++) {

            	$selected = "";

            	$query_selected = mysql_query("SELECT * FROM `sub_category` WHERE id =".$sub_id);

            	while($r = mysql_fetch_assoc($query_selected)){
			        $order_number = $r['order'];
			    }

			    if($order_number == ($i+1)){
			    	$selected = "selected";
			    }

                $select .= '<option '.$selected.' value="'.($i+1).'-'.$sub_id.'">'.($i+1).'</option>';
            }

        $select .= '</select>';

    return $select;

}


?>

<div class="page subcategory-page">
    <div class="page-header">
        <div class="page-header-left">
            <span>&nbsp;</span>
            <h2>
                <?php
                    echo 'Kategori';
                ?>
            </h2>
        </div>
        <!-- end .page-header-left -->
        <div class="page-header-right">
            <a href="?page=subcategory&subpage=add-subcategory&parent=menu" class="add-subcategory">Skapa ny</a>
        </div>
        <!-- end .page-header-right -->
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="thesubcategories">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Meny</th>
                    <th>Kategori</th>
                    <th>Ordningsföljd</th>
                    <th>Åtgärd</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $q=mysql_query("select s.name as sub,s.id as sid,c.name as cat, c.id as cat_id from category as c, sub_category as s where s.deleted=0 and c.deleted=0 and c.id=s.category_id order by c.id desc") or die(mysql_error());
                    
                    $count=0;
                    while($r=mysql_fetch_assoc($q)){
                        $count++;
                ?>
                    <tr class="gradeX gradeX-<?php echo $r['sid'];?>" align="center">
                        <td><?php echo $count; ?></td>
                        <td><?php echo ucwords($r['cat']);?></td>
                        <td><?php echo ucwords($r['sub']);?></td>
                        <td><?php echo create_drop_down( $count, $r['cat_id'], $r['sid']); ?></td>
                        <td><a href="?page=subcategory&subpage=edit-subcategory&id=<?php echo $r['sid']; ?>&parent=menu" class="edit-subcategory" title="Redigera Sub Kategori"><img src="images/edit.png" alt="Redigera Sub Kategori"></a> <?php if($_SESSION['login']['type']<3){ ?><a href="#" class="delete-subcategory" title="Radera Sub Kategori" data-rel="<?php echo $r['sid']; ?>"><img src="images/delete.png" alt="Radera Sub Kategori"></a><?php } ?></td>
                    </tr>
                <?php       
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Meny</th>
                    <th>Kategori</th>
                    <th>Ordningsföljd</th>
                    <th>Åtgärd</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="fade"></div>
<div class="delete-subcategory-box modalbox">
    <h2>Bekräfta borttagning<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Vill du fortsätta?</p>
        <input type="button" value="Utför">
    </div>
</div>