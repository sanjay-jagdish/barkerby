<?php
 include_once('redirect.php'); 

function create_drop_down( $td_id,  $sub_id, $menu_id ){

    $select = "";
    $list = 0;
    // $selected = "";

    $query = mysql_query("SELECT count(name) AS menu_name FROM `menu` WHERE sub_category_id = '".$sub_id."' and deleted = 0 ");

    while($row = mysql_fetch_assoc($query)){
        $list = $row['menu_name'];
    }

        $select .= '<select name="order_list" class="dropdown-'.$td_id.'" onChange="update_menu_order('.$td_id.')" >';

            $select .= '<option '.$selected.' value=" -'.$menu_id.'">--</option>';

            for ($i = 0; $i < $list; $i++) {

                $selected = "";

                $query_selected = mysql_query("SELECT * FROM `menu` WHERE id =".$menu_id);

                while($r = mysql_fetch_assoc($query_selected)){
                    $order_number = $r['order'];
                }

                if($order_number == ($i+1)){
                    $selected = "selected";
                }

                $select .= '<option '.$selected.' value="'.($i+1).'-'.$menu_id.'">'.($i+1).'</option>';
            }

        $select .= '</select>';

    return $select;

}

function create_drop_down_cat( $td_id, $cat_id ){

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

function create_drop_down_subcat( $td_id, $cat_id, $sub_id ){

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

<script type="text/javascript">
	jQuery(function(){
	
		jQuery('.menu-page .typeselection span').click(function(){
			var id=this.id;
			
			jQuery('.typeselection span').removeClass('active');
			
			jQuery(this).addClass('active');
			
			jQuery('.menutypes').hide();
			
			jQuery('.'+id).fadeIn();
			
		});
		
		
		//for data-table * catering menus
		jQuery('#themenucategories').dataTable( {
			"aaSorting": [[ 0, "asc" ]],
			"iDisplayLength" : 100,
			"oLanguage": {
					"sUrl": "scripts/datatable-swedish.txt"
			}
		} );
	
	});
</script>

<div class="page menu-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                	
					echo 'Menynställningar';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
       <!-- <div class="page-header-right">
        	<a href="?page=menu&subpage=add-menu" class="add-menu">Skapa ny</a>
        </div>-->
        <!-- end .page-header-right -->
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
    	<div class="typeselection">
            <span id="menudine" class="active">Huvudmeny</span>
            <span id="menutake">Menyer</span>
            <span id="menulunch">Kategorier</span>
        </div>
        
        <div class="menudine menutypes">
        
        	<div class="newadd">
            	<a href="?page=menu&subpage=add-menu">Skapa ny</a>
            </div>
        	<div class="clear"></div>
        
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="themenusdine">
                <thead>
                    <tr>
                         <th>#</th>
                        <th>Underkategori</th>
                        <th>Kategori</th>
                        <th>Rätt</th>
                        <th>Beskrivning</th>
                        <th>Bild</th>
                        <th>Pris</th>
                        <th>Discount</th>
                        <th>Discounted Pris</th>
                        <th>Presentera</th>
                        <th>Typ</th>
                        <th>Order</th>
                        <th>Åtgärd</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $q=mysql_query("select c.name, m.name, m.description, m.image, m.price, m.id, cu.shortname, m.featured, m.type, c.id, m.discount from sub_category as c, menu as m, currency as cu where c.id=m.sub_category_id and m.deleted=0 and cu.id=m.currency_id and m.type<>'2' order by m.id desc") or die(mysql_error());
                        
                        $count=0;
                        while($r=mysql_fetch_array($q)){
                            $count++;
                    ?>
                        <tr class="gradeX gradeX-<?php echo $r[5];?>" align="center">
                            <td><?php echo $count; ?></td>
                            <td><?php echo $r[0];?></td>
                            <td><?php echo getCategoryName($r[9]);?></td>
                            <td><?php echo $r[1];?></td>
                            <td>
                                <?php
                                    if(strlen($r[2])>150){
                                        echo substr($r[2],0,150)."...";
                                    }
                                    else{ 
                                        echo $r[2];
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                    $imglink="uploads/".$r[3];
                                    $imgtitle=$r[1]." &#8250; ".$r[4]." ".$r[6];
                                    
                                    if($r[3]==''){
                                        $imglink="images/no-photo-available.jpg";
                                        $imgtitle="No Photo Available"; 
                                    }
                                ?>
                                <a href="<?php echo $imglink; ?>" data-lightbox="menus" title="<?php echo $imgtitle; ?>"><img src="<?php echo $imglink; ?>" width="50px"></a>
                            </td>
                            <td><?php echo $r[4]." ".$r[6];?></td>
                            <td><?php echo $r[10];?>%</td>
                            <td>
                            	<?php
                                	$price='';
									if(strlen($r[8])>1){
										
										$discount=$r[10]/100;
										$price=$r[4]-($r[4]*$discount);
										$price= $price." ".$r[6];
									}
									
									
									echo $price;
								?>
                            </td>
                            <td><input type="checkbox" class="cboxs" id="cbox-<?php echo $r[5];?>" data-rel="<?php echo $r[5];?>" <?php if($r[7]==1) echo 'checked="checked"';?>></td>
                            <td>
                                <?php
                                
                                    if(strlen($r[8])>1){
                                        $thetype=explode(',',$r[8]);
                                        
                                        $type='';
                                        for($i=0;$i<count($thetype);$i++){
                                            $type.=$menu_type[$thetype[$i]].'<br>';
                                        }
                                        
                                        echo $type;
                                        
                                    }
                                    else{
                                        echo $menu_type[$r[8]];
                                    }
                                ?>
                            </td>
                            <td><?php echo create_drop_down( $count, $r[9], $r[5]); ?></td>
                            <td><a href="?page=menu&subpage=edit-menu&id=<?php echo $r[5]; ?>" class="edit-menu" title="Redigera Menyer"><img src="images/edit.png" alt="Redigera Menyer"></a> <?php if($_SESSION['login']['type']<3){ ?><a href="javascript:void" class="delete-menu" title="Radera Menyer" data-rel="<?php echo $r[5]; ?>"><img src="images/delete.png" alt="Radera Menyer"></a><?php } ?></td>
                        </tr>
                    <?php		
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Underkategori</th>
                        <th>Kategori</th>
                        <th>Rätt</th>
                        <th>Beskrivning</th>
                        <th>Bild</th>
                        <th>Pris</th>
                        <th>Discount</th>
                        <th>Discounted Pris</th>
                        <th>Presentera</th>
                        <th>Typ</th>
                        <th>Order</th>
                        <th>Åtgärd</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- end .menudine -->
        
        <div class="menutake menutypes" style="display:none;">
            <div class="newadd">
            	<a href="?page=category&subpage=add-category&parent=menu">Skapa ny</a>
            </div>
        	<div class="clear"></div>
            
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="themenucategories">
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
                            <td><?php echo create_drop_down_cat( $count, $r['id'] ); ?></td>
                            <td><a href="?page=category&subpage=edit-category&id=<?php echo $r[1]; ?>&parent=menu" class="edit-category" title="Redigera Kategori"><img src="images/edit.png" alt="Redigera Kategori"></a> <?php if($_SESSION['login']['type']<3){ ?><a href="#" class="delete-category" title="Radera Kategori" data-rel="<?php echo $r[1]; ?>"><img src="images/delete.png" alt="Radera Kategori"></a><?php } ?></td>
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
        <!-- end .menutake -->
        
        <!-- start .menulunch -->
        
        <div class="menulunch menutypes" style="display:none;">
	
    		<div class="newadd">
            	<a href="?page=subcategory&subpage=add-subcategory&parent=menu">Skapa ny</a>
            </div>
        	<div class="clear"></div>
     		
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
                            <td><?php echo create_drop_down_subcat( $count, $r['cat_id'], $r['sid']); ?></td>
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
        
        <!-- end .menulunch -->        
    </div>
</div>

<div class="fade"></div>
<div class="delete-menu-box modalbox">
	<h2>Bekräfta borttagning<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Vill du fortsätta?</p>
        <input type="button" value="Utför">
    </div>
</div>

<div class="delete-category-box modalbox">
	<h2>Bekräfta borttagning<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Vill du fortsätta?</p>
        <input type="button" value="Utför">
    </div>
</div>

<div class="delete-subcategory-box modalbox">
    <h2>Bekräfta borttagning<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Vill du fortsätta?</p>
        <input type="button" value="Utför">
    </div>
</div>