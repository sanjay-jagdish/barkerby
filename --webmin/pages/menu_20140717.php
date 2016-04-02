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
            <span id="menulunch">Lunch</span>
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
        
        <!-- start .menulunch -->
        
        <div class="menulunch menutypes" style="display:none;">
			
            <style>
				.weeks{
					width: 90px;
					padding:2px;
					text-align: center;
					display: inline-block;
					border-left:solid thin #CCC;	
					border-top:solid thin #CCC;	
					border-right:solid thin #CCC;	
					margin:0px 0px 0px 2px;
				}
				
				.current{
					background-color:#393;
					color:#FFF;	
				}
				
				.menu_main_box{
					border-top: solid 4px #393;
				}
				
				.menu_description{
					width:700px;
					float:left;	
				}
				
				.menu_items{ clear:both; }
				
				.menu_item{
					padding: 5px;
					background-color:#CCC;
					margin: 10px 0px;
					overflow:hidden;		
				}
				
				.menu_item_desc{
					width:800px !important;	 
					float: left;
					margin-right:15px;
				}
				
				.unit_price{ text-align:right; width:90px; }
				
				.menu_item_actions{ float:right; text-align:center; }
			
            	.btn{ padding:3px 5px; margin-right:3px; float:left; border-radius: 5px; text-transform:none; }
				.weekly{ color:#fff; background-color:#393; }
				.daily{ color:#fff; background-color:#36C; }
				.specific_date{ color:#fff; background-color:#F60; }
				.weekly:hover{ color:#000 !important; background-color:#F90 !important; }
				.daily:hover{ color:#000 !important; background-color:#3CF !important; }
				.specific_date:hover{ color:#000 !important; background-color:#F60; !important; }
			
            </style>
            
            	<div style="float:right; width:250px; text-align:right;">
                	<div class="btn weekly">Varje vecka</div>
                    <div class="btn daily">Dagligen</div>
                    <div class="btn specific_date">Specifik Datum</div>
                </div>
            
            	Text ovanför menyn: <input name="text" id="text_over_menu" value="" style="width:600px;" />
               
                <br /><br />
                <?php
				//current week
				$week_num = date("W");
				
				if($_GET){
					
				}
				
				//weeks list
				//$weeks = range(($week_num-2),($week_num+6));
				$weeks = range((date("W",strtotime("-2 weeks"))),($week_num+6));
				
				$currency_shortname = '';
				$currency_sql = "SELECT shortname FROM currency WHERE set_default=1";
				$currency_qry = mysql_query($currency_sql);
				$currency_res = mysql_fetch_assoc($currency_qry);
				?>
                
                Denna vecka <?php echo date("Y"); ?>: <b><?php echo $week_num; ?></b>
		
        		<?php
				$cw='';
				foreach($weeks as $k => $week_no){
					if($week_no==$week_num){ $cw='current'; }else{ $cw=''; }
					echo '<div class="weeks '.$cw.'"> v. '.$week_no.' </div>&nbsp;&nbsp;&nbsp;';
				}
				?>
                
                <div class="menu_main_box">
              	 	<div style="background-color:#FFC; padding:4px; overflow:hidden;">
                    	<h2 style="float:left;">OBS! Gäller hela vecka <?php echo $week_num; ?> (mån-fre)</h2>
						<div style="float:right;"><input type="checkbox" /> Gäller ala veckor.</div>
                	</div>
                   
                    <br />
                   
                    <div class="menu_description">
                        Menu Description:
                        <textarea></textarea>
                    </div>

                    <div style="float:right; width: 200px; text-align:right;">
                    	<input type="button" value="Spara allt" class="btn" style="padding:12px; float:right; margin-top:45px;" />
                    </div>
                    
                    <div class="menu_items">
                    	
                        <br /><br />
                    	Courses:
                        
                        <div class="menu_item">
                        
                        	<div class="menu_item_actions">
                            	<img src="images/delete.png" alt="Radera"><br />
                                Sort/Order<br />
                                <select>
                                	<option value="">---</option>
                                </select> 
                            </div>
                        
                        	<div class="menu_item_desc">
                            	<textarea></textarea>
                            </div>
                            Pris: 
                            <input type="text" class="unit_price" /> <?php echo $currency_res['shortname']; ?>
                        </div>

                        
                        <div id="menu_items_additional"></div>
                        
                        
                        + Add Course
                    
                    	
                        <br /><br /><br />
                        
		            	Text nedanför menyn: <input name="text" id="text_after_menu" value="" style="width:600px;" />
                                     
                    
                    </div>
                    
                </div>
        	<br /><br />
        </div>
        
        <!-- end .menulunch --?>        
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