<?php
 include_once('redirect.php'); 

function create_drop_down( $td_id,  $sub_id, $menu_id, $allview ){

    $select = "";
    $list = 0;
    // $selected = "";

    $query = mysql_query("SELECT count(name) AS menu_name FROM `menu` WHERE sub_category_id = '".$sub_id."' and deleted = 0  and type<>'2' ");

    while($row = mysql_fetch_assoc($query)){
        $list = $row['menu_name'];
    }
	
		$selectname = 'dropdown-';
		
		if($allview==1){
			$selectname = 'adropdown-';
		}

        $select .= '<select name="order_list" class="'.$selectname.$menu_id.'" onChange="update_menu_order('.$menu_id.', '.$allview.')" >';

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

        $select .= '<select name="order_list" class="cdropdown-'.$cat_id.'" onChange="update_category_order('.$cat_id.')" >';

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

        $select .= '<select name="order_list" class="sdropdown-'.$sub_id.'" onChange="update_sub_category_order('.$sub_id.')" >';

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
		
		
		$('.parent-cat-menu').click(function(){
			$('.sub-cat').slideUp('fast');
			$('.parent-cat-menu').removeClass('open');
			var holder = $(this).attr('alt');
			if($("#"+holder).css("display") == "block"){
				//do nothing here
				if(holder == 'subcat-all'){
					$('.view-topscroll').hide();
				}
			}else{
				$('#'+holder).slideToggle( "fast" );
				$(this).toggleClass('open');
				
				if(holder == 'subcat-all'){
					$('.view-topscroll').show();
					showAllCatScroller();
				}  
			}
		});
		
		$('.sub-cat-menu').click(function(){
			$('.table-menu').slideUp('fast');
			$('.sub-cat-menu').removeClass('open');
			var holder = $(this).attr('alt');
			if($("#"+holder).css("display") == "block"){
				//do nothing here
			}else{
				$('#'+holder).slideToggle( "fast" );  
				$(this).toggleClass('open'); 
			}
		});
		
		$('.main-cat table').each(function(index, element) {
            var id = $(this).attr('id');
			jQuery('#'+id).dataTable( {
				"aaSorting": [[ 0, "asc" ]],
				"iDisplayLength" : 100,
				"oLanguage": {
						"sUrl": "scripts/datatable-swedish.txt"
				}
			} );
        });
	
	});
</script>

<style>
	.parent-cat-menu,
	.sub-cat-menu{
		background:#e5e5e5;
		padding:5px 10PX;
		cursor:pointer;
		border:1px solid #ddd;
		margin:0;
		color:#555;
		font-weight:normal;
		clear:both;
	}
	.parent-cat-menu{
		font-size:15px;	
	}
	.sub-cat-menu{
		font-size:12px;
		border:0;
		border-bottom:1px solid #fff;	
	}
	.parent-cat-menu:before,
	.sub-cat-menu:before{
		content:'+';
		display:inline-block;
		color:#e67e22;
		padding:5px;
		min-width:24px;
		font-size:24px;
		text-align:center;
	}
	
	.parent-cat-menu.open:before{
		content:'-';
		color:#fff;
	}
	.sub-cat-menu.open:before{
		content:'-';
		color:#e67e22;
	}
	.parent-cat-menu.open{
		color:#fff;
		background:#e67e22;
	}
	.sub-cat-menu{
		background:#f2f2f2;
		color:#e67e22;
	}
	.sub-cat-menu.open{
		background:#e5e5e5;
		color:#555;
	}
	
	.sub-cat,
	.table-menu{
		display:none;
		margin-bottom:10px;
	}
	.table-menu{
		padding:20px 0 30px;
	}
	
	#subcat-all table tr td:nth-child(4),
	#subcat-all table.display thead th:nth-child(4),
	#subcat-all table.display tfoot th:nth-child(4),
	.ta2 table tr td:nth-child(2),
	.ta2 table.display thead th:nth-child(2),
	.ta2 table.display tfoot th:nth-child(2),
	.menutake table tr td:nth-child(2),
	.menutake table.display thead th:nth-child(2),
	.menutake table.display tfoot th:nth-child(2),
	.menulunch table tr td:nth-child(3),
	.menulunch table.display thead th:nth-child(3),
	.menulunch table.display tfoot th:nth-child(3),
	.ta3 table tr td:nth-child(3),
	.ta3 table.display thead th:nth-child(3),
	.ta3 table.display tfoot th:nth-child(3),
	.sub-cat table tr td:nth-child(2),
	.sub-cat table.display thead th:nth-child(2),
	.sub-cat table.display tfoot th:nth-child(2){
		font-size: 15px;
		font-weight: bold;
	}
	
	#subcat-all table tr td:nth-child(2),
	#subcat-all table.display thead th:nth-child(2),
	#subcat-all table.display tfoot th:nth-child(2){
		font-size: 12px !important;
		font-weight: 100 !important;
	}
	
</style>

<div class="page menu-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                	
					echo 'Menyinställningar - À la carte';
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
            <span id="menudine" <?php if($_GET['tab']=='huvudmeny' || !isset($_GET['tab'])){ echo 'class="active"'; }?>>Alla rätter</span>
            <span id="menutake" <?php if($_GET['tab']=='menyer'){ echo 'class="active"'; }?>>Menyer</span>
            <span id="menulunch" <?php if($_GET['tab']=='kategorier'){ echo 'class="active"'; }?>>Kategorier</span>
        </div>
        
        <div class="menudine menutypes" <?php if($_GET['tab']!='huvudmeny' && isset($_GET['tab'])){ echo 'style="display:none"'; }?>>
        
        	<div class="newadd">
            	<input type="button" class="btn" value="Uppdatera" onclick="javascript:window.location.reload();">
            	<a href="?page=menu&subpage=add-menu&nav=menu">Skapa ny rätt</a>
            </div>
        	<div class="clear"></div>
            	<?php
				
				//Edited by kirby
				if(empty($_GET['fromall'])){
					if(!empty($_GET['editedmenucatid'])){
						if($_GET['editedmenucattype']=='subcat'){
							$query = mysql_query("SELECT `category_id` FROM `sub_category` WHERE id =".$_GET['editedmenucatid']);
							$row = mysql_fetch_assoc($query) or die(mysql_error());
							$editedcatid = $row['category_id'];
						}else{
							$editedcatid = $_GET['editedmenucatid'];
						}
					}
				}
					
                	$qcategory = mysql_query("SELECT * FROM `category` where deleted = 0") or die('qcategory: '.mysql_error());
					echo '<div class="main-cat">';
					if(mysql_num_rows($qcategory)>0){
						while($rcat = mysql_fetch_assoc($qcategory) ){
							
							//AFTER EDIT A MENU
							$tcatid = $editedcatid;
							
							if($tcatid == $rcat['id']){$spoiler = 'open'; $display = 'block';}
							else{$spoiler = ''; $display = '';}
							
							echo '<h1 alt="subcat-'.$rcat['id'].'" class="parent-cat-menu '.$spoiler.'">'.$rcat['name'].'</h1>';	
							echo '<div id="subcat-'.$rcat['id'].'" class="sub-cat" style="display: '.$display.'">';
								
								////START MENU WITHOUT SUB CATEGORY
								
							$qc=mysql_query("select c.name, m.name, m.description, m.image, m.price, m.id, cu.shortname, m.featured, m.type, c.id, m.discount, m.discount_unit, m.takeaway_price from category as c, menu as m, currency as cu where c.id=m.cat_id and m.deleted=0 and cu.id=m.currency_id and m.cat_id = $rcat[id] and m.type<>'2' order by LENGTH(m.order), m.order asc") or die(mysql_error());
							
							if(mysql_num_rows($qc)>0){
							echo '<br><br><div>';
							?>
							   <table cellpadding="0" cellspacing="0" border="0" class="display" id="datatable-<?php echo $rcat['id']?>">
									<thead>
										<tr>
											<th>#</th>
											<!--<th>Meny</th>
											<th>Kategori</th>    -->                        
											<th>Rätt</th>
											<th>Beskrivning</th>
											<th>Bild</th>
											<th>Pris</th>
											<!--<th>Take away rabatt</th>-->
											<th>Take away pris</th>
											<th>Visa</th>
											<th>Typ</th>
											<th>Ordningsföljd</th>
											<th>Åtgärd</th>
										</tr>
									</thead>
									<tbody>
										<?php
								
											$count=0;
											while($r=mysql_fetch_array($qc)){
												$count++;
												if($count%2==0){
													$theclass='even';
												}
												else{
													$theclass='odd';
												}
										?>
											<tr class="gradeX gradeX-<?php echo $r[5].' '.$theclass;?>" align="center">
												<td><?php echo $count; ?></td>
												 <?php /*?><td><?php echo getCategoryName($r[9]);?></td>
												<td><?php echo $r[0];?></td>      <?php */?>                         
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
												<td><?php echo $r[4]." ".strtolower($r[6]);?></td>
												<!--<td><?php /*echo $r[10]; echo ($r[11]=='percent')?'%':' '.strtolower($r[6]);*/?></td>-->
                                                <td>
                                                    <?php
                                                        /*$price='';
                                                      //  if(strlen($r[8])>1){                                                   
                                                            if($r[11]=='fix'){
                                                                $discount = $r[10];
                                                                $price=($r[4]-$discount);
                                                            }else{
                                                                $discount=$r[10]/100;
                                                                $price=$r[4]-($r[4]*$discount);
                                                            }                                                      
                                                            $price= $price." ".$r[6];
                                                        //}         
                                                        echo strtolower($price);*/
														
														echo $r[12];
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
												<td><?php echo create_drop_down( $count, $r[9], $r[5],0); ?></td>
												<td><a href="?page=menu&subpage=edit-menu&id=<?php echo $r[5]; ?>&nav=menu" class="edit-menu" title="Redigera rätt"><img src="images/edit.png" alt="Redigera rätt"></a> <?php if($_SESSION['login']['type']<3){ ?><a href="javascript:void" class="delete-menu" title="Radera rätt" data-rel="<?php echo $r[5]; ?>"><img src="images/delete.png" alt="Radera rätt"></a><?php } ?></td>
											</tr>
										<?php		
											}
										?>
									</tbody>
									<tfoot>
										<tr>
											<th>#</th>
											<!--<th>Meny</th>
											<th>Kategori</th>    -->                        
											<th>Rätt</th>
											<th>Beskrivning</th>
											<th>Bild</th>
											<th>Pris</th>
											<!--<th>Take away rabatt</th>-->
											<th>Take away pris</th>
											<th>Visa</th>
											<th>Typ</th>
											<th>Ordningsföljd</th>
											<th>Åtgärd</th>
										</tr>
									</tfoot>
								</table>
								<?php
                            echo '</div><br><br><br>';
											}
							///END MENU WITHOUT SUB CATEGORY
							
								$qscategory = mysql_query("SELECT * FROM `sub_category` where category_id = $rcat[id] and deleted = 0") or die('qcategory: '.mysql_error());
								if(mysql_num_rows($qscategory)>0){
									while($rscat = mysql_fetch_assoc($qscategory) ){
										
										
										if($_GET['editedmenucatid'] == $rscat['id']){$spoiler1 = 'open'; $display1 = 'block';}
										else{$spoiler1 = ''; $display1 = '';}
										
										echo '<h2 class="sub-cat-menu '.$spoiler1.'" alt="target-'.$rscat['id'].'">'.$rscat['name'].'</h2>';
										echo '<div class="table-menu" id="target-'.$rscat['id'].'" style="display: '.$display1.'">';
										?>
               <table cellpadding="0" cellspacing="0" border="0" class="display" id="datatable-<?php echo $rscat['id']?>">
                    <thead>
                        <tr>
                            <th>#</th>
                            <!--<th>Meny</th>
                            <th>Kategori</th>    -->                        
                            <th>Rätt</th>
                            <th>Beskrivning</th>
                            <th>Bild</th>
                            <th>Pris</th>
                            <!--<th>Take away rabatt</th>-->
                        	<th>Take away pris</th>
                            <th>Visa</th>
                            <th>Typ</th>
                            <th>Ordningsföljd</th>
                            <th>Åtgärd</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
							$q=mysql_query("select c.name, m.name, m.description, m.image, m.price, m.id, cu.shortname, m.featured, m.type, c.id, m.discount, m.discount_unit, m.takeaway_price from sub_category as c, menu as m, currency as cu where c.id=m.sub_category_id and m.deleted=0 and cu.id=m.currency_id and m.sub_category_id = $rscat[id] and m.type<>'2' order by LENGTH(m.order), m.order asc") or die(mysql_error());
                            
                            $count=0;
                            while($r=mysql_fetch_array($q)){
                                $count++;
								if($count%2==0){
									$theclass='even';
								}
								else{
									$theclass='odd';
								}
                        ?>
                            <tr class="gradeX gradeX-<?php echo $r[5].' '.$theclass;?>" align="center">
                                <td><?php echo $count; ?></td>
                                 <?php /*?><td><?php echo getCategoryName($r[9]);?></td>
                                <td><?php echo $r[0];?></td>      <?php */?>                         
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
                                <td><?php echo $r[4]." ".strtolower($r[6]);?></td>
                                <!--<td><?php /*echo $r[10]; echo ($r[11]=='percent')?'%':' '.strtolower($r[6]);*/?></td>-->
                                <td>
                                    <?php
                                        /*$price='';
                                        //if(strlen($r[8])>1){                                          
                                            if($r[11]=='fix'){
                                                $discount = $r[10];
                                                $price=($r[4]-$discount);
                                            }else{
                                                $discount=$r[10]/100;
                                                $price=$r[4]-($r[4]*$discount);
                                            }                                     
                                            $price= $price." ".$r[6];
                                        //}                                     
                                        echo strtolower($price);*/
										
										echo $r[12];
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
                                <td><?php echo create_drop_down( $count, $r[9], $r[5],0); ?></td>
                                <td><a href="?page=menu&subpage=edit-menu&id=<?php echo $r[5]; ?>&nav=menu" class="edit-menu" title="Redigera rätt"><img src="images/edit.png" alt="Redigera rätt"></a> <?php if($_SESSION['login']['type']<3){ ?><a href="javascript:void" class="delete-menu" title="Radera rätt" data-rel="<?php echo $r[5]; ?>"><img src="images/delete.png" alt="Radera rätt"></a><?php } ?></td>
                            </tr>
                        <?php		
                            }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <!--<th>Meny</th>
                            <th>Kategori</th>    -->                        
                            <th>Rätt</th>
                            <th>Beskrivning</th>
                            <th>Bild</th>
                            <th>Pris</th>
                            <!--<th>Take away rabatt</th>-->
                        	<th>Take away pris</th>
                            <th>Visa</th>
                            <th>Typ</th>
                            <th>Ordningsföljd</th>
                            <th>Åtgärd</th>
                        </tr>
                    </tfoot>
                </table>
                                        <?php
									echo '</div>';
									}
								}else{
									echo '<p style="padding:10px; background:#ccc;">Tabellen innehåller ingen data</p>';
								}
								echo '</div>';
						}
						
					if($_GET['fromall']=='true'){$spoiler2 = 'open'; $display2 = 'block';}
					else{$spoiler2 = ''; $display2 = '';}
					
					echo '<h1 alt="subcat-all" class="parent-cat-menu '.$spoiler2.'" style="text-transform:uppercase;">visa alla rätter</h1>';
					echo '<div class="view-topscroll" style="display:none"><div class="scroll-div"></div></div>';
					echo '<div id="subcat-all" class="sub-cat table-menu" style="display: '.$display2.'">';
					?>
                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="datatable-all">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Meny</th>
                            <th>Kategori</th>                            
                            <th>Rätt</th>
                            <th>Beskrivning</th>
                            <th>Bild</th>
                            <th>Pris</th>
                            <!--<th>Take away rabatt</th>-->
                        	<th>Take away pris</th>
                            <th>Visa</th>
                            <th>Typ</th>
                            <th>Ordningsföljd</th>
                            <th>Åtgärd</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
						$checkq=mysql_query("Select count(id) from sub_category") or die(mysql_error());
						$checkrow = mysql_fetch_assoc($checkq);
						
						if($checkrow['count(id)'] != 0){
							 $q=mysql_query("select c.name, m.name, m.description, m.image, m.price, m.id, cu.shortname, m.featured, m.type, IF( m.sub_category_id=0, 0, c.id), m.discount, cat.id, m.discount_unit, m.takeaway_price from sub_category as c, menu as m, currency as cu, category as cat where ((cat.id=m.cat_id and m.cat_id<>0) or (c.id=m.sub_category_id and m.sub_category_id<>0)) and m.deleted=0 and cu.id=m.currency_id and m.type<>'2' group by m.id order by m.cat_id, m.sub_category_id, m.order") or die(mysql_error());
						}else{
							$q=mysql_query("select '', m.name, m.description, m.image, m.price, m.id, cu.shortname, m.featured, m.type, '', m.discount, cat.id, m.discount_unit, m.takeaway_price from menu as m, currency as cu, category as cat where (cat.id=m.cat_id and m.cat_id<>0) and m.deleted=0 and cu.id=m.currency_id and m.type<>'2' group by m.id order by m.cat_id, m.sub_category_id, m.order") or die(mysql_error());	
						}
                            
                            $count=0;
                            while($r=mysql_fetch_array($q)){
                                $count++;
								if($count%2==0){
									$theclass='even';
								}
								else{
									$theclass='odd';
								}
                        ?>
                            <tr class="gradeX gradeX-<?php echo $r[5].' '.$theclass;?>" align="center">
                                <td><?php echo $count; ?></td>
                                <td><?php if($r[9]!=0){echo getCategoryName($r[9]);}else{ echo getCatName($r[11]);}?></td>
                                <td><?php echo getSubCatName($r[9]);?></td>                               
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
                                <td><?php echo $r[4]." ".strtolower($r[6]);?></td>
                                <!--<td><?php /*echo $r[10]; echo ($r[12]=='percent')?'%':' '.strtolower($r[6]);*/?></td>-->
                                <td>
                                    <?php
                                        /*$price='';
										$rawprice = $r[4];
                                        //if(strlen($r[8])>1){                                        
											if($r[12]=='fix'){
												$discount = $r[10];
												$price=($rawprice-$discount);
											}else{
												$discount=$r[10]/100;
												$price=$rawprice-($rawprice*$discount);
											}                                          
                                            $price= $price." ".$r[6];
                                        //}     
                                        echo strtolower($price);*/
										
										echo  $r[13];
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
                                <td><?php echo create_drop_down( $count, $r[9], $r[5],1); ?></td>
                                <td><a href="?page=menu&subpage=edit-menu&id=<?php echo $r[5]; ?>&nav=menu&fromall=true" class="edit-menu" title="Redigera rätt"><img src="images/edit.png" alt="Redigera rätt"></a> <?php if($_SESSION['login']['type']<3){ ?><a href="javascript:void" class="delete-menu" title="Radera rätt" data-rel="<?php echo $r[5]; ?>"><img src="images/delete.png" alt="Radera rätt"></a><?php } ?></td>
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
                            <th>Rätt</th>
                            <th>Beskrivning</th>
                            <th>Bild</th>
                            <th>Pris</th>
                            <!--<th>Take away rabatt</th>-->
                        	<th>Take away pris</th>
                            <th>Visa</th>
                            <th>Typ</th>
                            <th>Ordningsföljd</th>
                            <th>Åtgärd</th>
                        </tr>
                    </tfoot>
                </table>
                    <?php
					echo '</div>';
						echo '</div>';
					}else{
						echo '<p style="padding:10px; background:#ccc;">Tabellen innehåller ingen data</p>';
					}
					
				?>
            
        </div>
        <!-- end .menudine -->
        
        <div class="menutake menutypes" <?php if($_GET['tab']!='menyer' || !isset($_GET['tab'])){ echo 'style="display:none"'; }?>>
            <div class="newadd">
            	<a href="?page=category&subpage=add-category&parent=menu&nav=menu">Skapa ny meny</a>
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
                            <td><a href="?page=category&subpage=edit-category&id=<?php echo $r[1]; ?>&parent=menu&nav=menu" class="edit-category" title="Redigera meny"><img src="images/edit.png" alt="Redigera meny"></a> <?php if($_SESSION['login']['type']<3){ ?><a href="#" class="delete-category" title="Radera meny" data-rel="<?php echo $r[1]; ?>"><img src="images/delete.png" alt="Radera meny"></a><?php } ?></td>
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
        
        <div class="menulunch menutypes" <?php if($_GET['tab']!='kategorier'  || !isset($_GET['tab'])){ echo 'style="display:none"'; }?>>
	
    		<div class="newadd">
            	<a href="?page=subcategory&subpage=add-subcategory&parent=menu&nav=menu">Skapa ny kategori</a>
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
                            <td><a href="?page=subcategory&subpage=edit-subcategory&id=<?php echo $r['sid']; ?>&parent=menu&nav=menu" class="edit-subcategory" title="Redigera kategori"><img src="images/edit.png" alt="Redigera kategori"></a> <?php if($_SESSION['login']['type']<3){ ?><a href="#" class="delete-subcategory" title="Radera kategori" data-rel="<?php echo $r['sid']; ?>"><img src="images/delete.png" alt="Radera kategori"></a><?php } ?></td>
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


<script type="text/javascript">
	function showAllCatScroller(){
		var tWidth = jQuery('#subcat-all .display').width();
		console.log('tWidth '+tWidth);
		jQuery('.scroll-div').css('width' , tWidth);
		
		$(".view-topscroll").scroll(function(){
			$("#subcat-all").scrollLeft($(".view-topscroll").scrollLeft());
		});
		
		$("#subcat-all").scroll(function(){
			$(".view-topscroll").scrollLeft($("#subcat-all").scrollLeft());
		});
	}
</script>