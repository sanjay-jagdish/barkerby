
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
	
	});
</script>

<div class="page menu-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                	
					echo 'Menyer';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
        <div class="page-header-right">
        	<a href="?page=menu&subpage=add-menu" class="add-menu">Skapa ny</a>
        </div>
        <!-- end .page-header-right -->
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
    	<div class="typeselection">
        	<span id="menuall" class="active">Alla rätter</span>
            <span id="menudine">À la carte</span>
            <span id="menutake">Take Away</span>
        </div>
        
        <div class="menuall menutypes">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="themenus">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Underkategori</th>
                        <th>Rätt</th>
                        <th>Beskrivning</th>
                        <th>Bild</th>
                        <th>Pris</th>
                        <th>Presentera</th>
                        <th>Typ</th>
                        <th>Rabatt</th>
                        <th>Order</th>
                        <th>Åtgärd</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
					
					
                        $q=mysql_query("select c.name, m.name, m.description, m.image, m.price, m.id, cu.shortname, m.featured, m.type, c.id, m.discount from sub_category as c, menu as m, currency as cu where c.id=m.sub_category_id and m.deleted=0 and cu.id=m.currency_id order by c.id desc") or die(mysql_error());
                        
                        $count=0;
                        while($r=mysql_fetch_array($q)){
                            $count++;
                    ?>
                        <tr class="gradeX gradeX-<?php echo $r[5];?>" align="center">
                            <td><?php echo $count; ?></td>
                            <td><?php echo $r[0].' - '.getCategoryName($r[9]);?></td>
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
                            <td>
                            	<?php 
									if(strlen($r[8])>1){
                                		echo $r[10].'%';	
                                	}
									else echo '-';
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
                        <th>Rätt</th>
                        <th>Beskrivning</th>
                        <th>Bild</th>
                        <th>Pris</th>
                        <th>Presentera</th>
                        <th>Typ</th>
                        <th>Rabatt</th>
                        <th>Order</th>
                        <th>Åtgärd</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- end .menuall -->
        
        <div class="menudine menutypes" style="display:none;">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="themenusdine">
                <thead>
                    <tr>
                         <th>#</th>
                        <th>Underkategori</th>
                        <th>Rätt</th>
                        <th>Beskrivning</th>
                        <th>Bild</th>
                        <th>Pris</th>
                        <th>Presentera</th>
                        <th>Typ</th>
                        <th>Order</th>
                        <th>Åtgärd</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $q=mysql_query("select c.name, m.name, m.description, m.image, m.price, m.id, cu.shortname, m.featured, m.type, c.id from sub_category as c, menu as m, currency as cu where c.id=m.sub_category_id and m.deleted=0 and cu.id=m.currency_id and m.type<>'2' order by m.id desc") or die(mysql_error());
                        
                        $count=0;
                        while($r=mysql_fetch_array($q)){
                            $count++;
                    ?>
                        <tr class="gradeX gradeX-<?php echo $r[5];?>" align="center">
                            <td><?php echo $count; ?></td>
                            <td><?php echo $r[0].' - '.getCategoryName($r[9]);?></td>
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
                        <th>Rätt</th>
                        <th>Beskrivning</th>
                        <th>Bild</th>
                        <th>Pris</th>
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
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="themenustake">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Underkategori</th>
                        <th>Rätt</th>
                        <th>Beskrivning</th>
                        <th>Bild</th>
                        <th>Pris</th>
                        <th>Presentera</th>
                        <th>Typ</th>
                        <th>Order</th>
                        <th>Åtgärd</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $q=mysql_query("select c.name, m.name, m.description, m.image, m.price, m.id, cu.shortname, m.featured, m.type, c.id from sub_category as c, menu as m, currency as cu where c.id=m.sub_category_id and m.deleted=0 and cu.id=m.currency_id  and m.type<>'1' order by m.id desc") or die(mysql_error());
                        
                        $count=0;
                        while($r=mysql_fetch_array($q)){
                            $count++;
                    ?>
                        <tr class="gradeX gradeX-<?php echo $r[5];?>" align="center">
                            <td><?php echo $count; ?></td>
                            <td><?php echo $r[0].' - '.getCategoryName($r[9]);?></td>
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
                        <th>Rätt</th>
                        <th>Beskrivning</th>
                        <th>Bild</th>
                        <th>Pris</th>
                        <th>Presentera</th>
                        <th>Typ</th>
                        <th>Order</th>
                        <th>Åtgärd</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- end .menutake -->
        
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