<?php

	function getPrice($id){
		
		$q=mysql_query("select * from catering_menu_price where catering_menu_id='".$id."' order by id") or die(mysql_error());
		
		if(mysql_num_rows($q) > 0){
			
			
			$theprice = '';
			$newline ='';
			while($r=mysql_fetch_assoc($q)){
				
				$price_desc='';
			
				$price = $r['price'];	
				$price_type = $r['price_type'];	
				
				if($r['price_description']!='')
					$price_desc = ' - '.$r['price_description'];
				
				if(mysql_num_rows($q) > 1)
					$newline = "<br>";
				
				$theprice.=$price.' / '.$price_type.''.$price_desc.''.$newline;
				
			}
			
			return $theprice;
				
		}
		
	}
	
	function getParent($id,$table){
		
		$val=0;
		if($id!=''){
			$q=mysql_query("select name from $table where id=".$id) or die(mysql_error());
			
			if(mysql_num_rows($q) > 0){
			
				$val=1;
				
				$r=mysql_fetch_assoc($q);
				
				return $r['name'];
			
			}
			
		}
		
		if($val==0){
			return '-';
		}
		
	}
	
	function getCatPrice($id){
		
		$q=mysql_query("select * from catering_category_price where catering_category_id='".$id."' order by id") or die(mysql_error());
		
		if(mysql_num_rows($q) > 0){
			
			
			$theprice = '';
			$newline ='';
			while($r=mysql_fetch_assoc($q)){
				
				$price_desc='';
			
				$price = $r['price'];	
				$price_type = $r['price_type'];	
				
				if($r['price_description']!='')
					$price_desc = ' - '.$r['price_description'];
				
				if(mysql_num_rows($q) > 1)
					$newline = "<br>";
				
				$theprice.=$price.' / '.$price_type.''.$price_desc.''.$newline;
				
			}
			
			return $theprice;
				
		}
		
	}

?>

<script type="text/javascript">
jQuery(function(){
	
	jQuery('.menu-page .typeselection span').click(function(){
			var id=this.id;
			
			jQuery('.typeselection span').removeClass('active');
			
			jQuery(this).addClass('active');
			
			jQuery('.tas').hide();
			
			jQuery('.'+id).fadeIn();
			
		});
	
	
	jQuery('.delete-catering-menu-box').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .delete-catering-menu-box').fadeIn();
		gotoModal();
		
		var id=jQuery(this).attr('data-rel');
		jQuery('.delete-catering-menu-box input').attr('data-rel',id);
		
		
	});
	
	jQuery('.delete-catering-menu-box input[type=button]').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		var id=jQuery(this).attr('data-rel');
	
		jQuery.ajax({
			 url: "actions/delete-catering-menu.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){
				 
				 jQuery('.fade, .modalbox, .gradeX-'+id).fadeOut();
				 //setTimeout("window.location.reload()",1000);
			 }
		});	
		
	});
	
	//for category
	jQuery('.delete-catering-category-box').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .delete-catering-category-box').fadeIn();
		gotoModal();
		
		var id=jQuery(this).attr('data-rel');
		jQuery('.delete-catering-category-box input').attr('data-rel',id);
		
		
	});
	
	jQuery('.delete-catering-category-box input[type=button]').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		var id=jQuery(this).attr('data-rel');
	
		jQuery.ajax({
			 url: "actions/delete-catering-category.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){
				 
				 jQuery('.fade, .modalbox, .gradeX-'+id).fadeOut();
				 //setTimeout("window.location.reload()",1000);
			 }
		});	
		
	});
	
	//for data-table * catering menus
		/*jQuery('#thecateringmenus').dataTable( {
			"aaSorting": [[ 0, "asc" ]],
			"iDisplayLength" : 100,
			"oLanguage": {
					"sUrl": "scripts/datatable-swedish.txt"
			}
		} );*/
	
	//for data-table * catering sub menus
		jQuery('#thecateringsubcat').dataTable( {
			"aaSorting": [[ 0, "asc" ]],
			"iDisplayLength" : 100,
			"oLanguage": {
					"sUrl": "scripts/datatable-swedish.txt"
			}
		} );	
		
		
	jQuery('.delete-catering-subcategory-box').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .delete-catering-subcategory-box').fadeIn();
		gotoModal();
		
		var id=jQuery(this).attr('data-rel');
		jQuery('.delete-catering-subcategory-box input').attr('data-rel',id);
		
		
	});
	
	jQuery('.delete-catering-subcategory-box input[type=button]').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		var id=jQuery(this).attr('data-rel');
	
		jQuery.ajax({
			 url: "actions/delete-catering-subcategory.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){
				 
				 jQuery('.fade, .modalbox, .gradeX-'+id).fadeOut();
				 //setTimeout("window.location.reload()",1000);
			 }
		});	
		
	});	
	
		$('.parent-cat-menu').click(function(){
			$('.sub-cat').slideUp('fast');
			$('.parent-cat-menu').removeClass('open');
			var holder = $(this).attr('alt');
			if($("#"+holder).css("display") == "block"){
				//do nothing here
			}else{
				$('#'+holder).slideToggle( "fast" );
				$(this).toggleClass('open');  
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
</style>

<div class="page menu-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                	echo 'Menyinställningar - Catering';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
        <!--<div class="page-header-right">
        	<a href="?page=catering&subpage=add-catering-menu" class="add-catering-menu">Skapa ny</a>
        </div>-->
        <!-- end .page-header-right -->
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
    
    	<div class="typeselection">
        	<span id="ta1" <?php if(!isset($_GET['tab'])){ echo 'class="active"'; }?>>Huvudmeny</span>
            <span id="ta2" <?php if($_GET['tab']==2){ echo 'class="active"'; }?>>Menyer</span>
            <span id="ta3" <?php if($_GET['tab']==3){ echo 'class="active"'; }?>>Kategorier</span>
        </div>
        
    	<div class="ta1 tas" <?php if(isset($_GET['tab'])){ echo 'style="display:none"'; }?>>
        
            <div class="newadd">
                <a href="?page=catering&subpage=add-catering-menu">Skapa ny rätt</a>
            </div>
        	<div class="clear"></div>
            <?php
                	$qcategory = mysql_query("SELECT * FROM `catering_category` where deleted = 0") or die('qcategory: '.mysql_error());
					echo '<div class="main-cat">';
					
					if(mysql_num_rows($qcategory)>0){
						while($rcat = mysql_fetch_assoc($qcategory) ){
							echo '<h1 alt="subcat-'.$rcat['id'].'" class="parent-cat-menu">'.$rcat['name'].'</h1>';
								echo '<div id="subcat-'.$rcat['id'].'" class="sub-cat">';
								$qscategory = mysql_query("SELECT * FROM `catering_subcategory` where catering_category_id = $rcat[id] and deleted = 0") or die('qcategory: '.mysql_error());
								if(mysql_num_rows($qscategory)>0){
									while($rscat = mysql_fetch_assoc($qscategory) ){
										echo '<h2 class="sub-cat-menu" alt="target-'.$rscat['id'].'">'.$rscat['name'].'</h2>';
										echo '<div class="table-menu" id="target-'.$rscat['id'].'">';
										?>
                                        <table cellpadding="0" cellspacing="0" border="0" class="display" id="ctable-<?php echo $rscat['id'];?>">
            <thead>
                <tr>
                	<th>#</th>
                    <!--<th>Meny</th>
                    <th>Kategori</th>-->
                    <th>Rätt</th>
                    <th>Beskrivning</th>
                    <th>Pris</th>
                    <th>Åtgärd</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $q=mysql_query("select * from catering_menu where deleted=0 and catering_category_id=$rcat[id] and catering_subcategory_id=$rscat[id] order by id desc") or die(mysql_error());
                    
					$count=0;
                    while($r=mysql_fetch_assoc($q)){
                		$count++;
				?>
                    <tr class="gradeX gradeX-<?php echo $r['id'];?>" align="center">
                    	<td><?php echo $count; ?></td>
                        <?php /*?><td><?php echo getParent($r['catering_category_id'],'catering_category');?></td>
                        <td><?php echo getParent($r['catering_subcategory_id'],'catering_subcategory');?></td><?php */?>
                        <td><?php echo $r['name'];?></td>
                        <td><?php echo $r['description']; ?></td>
                        <td><?php echo getPrice($r['id']); ?></td>
                        <td><a href="?page=catering-menu&subpage=edit-catering-menu&id=<?php echo $r['id']; ?>" class="edit-catering-menu" title="Redigera"><img src="images/edit.png" alt="Redigera"></a> <?php if($_SESSION['login']['type']<3){ ?><a href="#" class="delete-catering-menu-box" title="Radera" data-rel="<?php echo $r['id']; ?>"><img src="images/delete.png" alt="Radera"></a><?php } ?></td>
                    </tr>
                <?php		
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                	<th>#</th>
                    <!--<th>Meny</th>
                    <th>Kategori</th>-->
                    <th>Rätt</th>
                    <th>Beskrivning</th>
                    <th>Pris</th>
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
						echo '<h1 alt="subcat-all" class="parent-cat-menu" style="text-transform:uppercase;">visa alla rätter</h1>';
					echo '<div id="subcat-all" class="sub-cat table-menu">';
					?>
                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="thecateringmenus">
            <thead>
                <tr>
                	<th>#</th>
                    <th>Meny</th>
                    <th>Kategori</th>
                    <th>Rätt</th>
                    <th>Beskrivning</th>
                    <th>Pris</th>
                    <th>Åtgärd</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $q=mysql_query("select * from catering_menu where deleted=0 order by id desc") or die(mysql_error());
                    
					$count=0;
                    while($r=mysql_fetch_assoc($q)){
                		$count++;
				?>
                    <tr class="gradeX gradeX-<?php echo $r['id'];?>" align="center">
                    	<td><?php echo $count; ?></td>
                        <td><?php echo getParent($r['catering_category_id'],'catering_category');?></td>
                        <td><?php echo getParent($r['catering_subcategory_id'],'catering_subcategory');?></td>
                        <td><?php echo $r['name'];?></td>
                        <td><?php echo $r['description']; ?></td>
                        <td><?php echo getPrice($r['id']); ?></td>
                        <td><a href="?page=catering-menu&subpage=edit-catering-menu&id=<?php echo $r['id']; ?>" class="edit-catering-menu" title="Redigera"><img src="images/edit.png" alt="Redigera"></a> <?php if($_SESSION['login']['type']<3){ ?><a href="#" class="delete-catering-menu-box" title="Radera" data-rel="<?php echo $r['id']; ?>"><img src="images/delete.png" alt="Radera"></a><?php } ?></td>
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
                    <th>Pris</th>
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
        <!-- end .ta1 -->
        
        <div class="ta2 tas" <?php if($_GET['tab']!=2 || !isset($_GET['tab'])){ echo 'style="display:none"'; }?>>
        
        <div class="newadd">
            <a href="?page=catering&subpage=add-catering-category">Skapa ny meny</a>
        </div>
        <div class="clear"></div>
        
        	<table cellpadding="0" cellspacing="0" border="0" class="display" id="thecategories">
            <thead>
                <tr>
                	<th>#</th>
                    <th>ID</th>
                    <th>Namn</th>
                    <th>Beskrivning</th>
                    <th>Beskrivning för allergiker</th>
                    <th>Tillval antal (max)</th>
                    <th>Minsta beställning</th>
                    <th>Pris</th>
                    <th>Åtgärd</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $q=mysql_query("select * from catering_category where deleted=0 order by id desc") or die(mysql_error());
                    
					$count=0;
                    while($r=mysql_fetch_assoc($q)){
                		$count++;
				?>
                    <tr class="gradeX gradeX-<?php echo $r['id'];?>" align="center">
                    	<td><?php echo $count; ?></td>
                        <td><?php echo $r['id']; ?></td>
                        <td><?php echo ucwords($r['name']);?></td>
                        <td><?php echo $r['description'];?></td>
                        <td><?php echo $r['added_description'];?></td>
                        <td><?php echo $r['number_selected']; ?></td>
                        <td><?php echo $r['minimum_order']; ?></td>
                        <td><?php echo getCatPrice($r['id']); ?></td>
                        <td><a href="?page=catering-category&subpage=edit-catering-category&id=<?php echo $r['id']; ?>" class="edit-catering-category" title="Redigera"><img src="images/edit.png" alt="Redigera"></a> <?php if($_SESSION['login']['type']<3){ ?><a href="#" class="delete-catering-category-box" title="Radera" data-rel="<?php echo $r['id']; ?>"><img src="images/delete.png" alt="Radera"></a><?php } ?></td>
                    </tr>
                <?php		
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                	<th>#</th>
                    <th>ID</th>
                    <th>Namn</th>
                    <th>Beskrivning</th>
                    <th>Beskrivning för allergiker</th>
                    <th>Tillval antal (max)</th>
                    <th>Minsta beställning</th>
                    <th>Pris</th>
                    <th>Åtgärd</th>
                </tr>
            </tfoot>
        </table>   
        </div>
        <!-- end .ta2 -->
        
        <div class="ta3 tas" <?php if($_GET['tab']!=3 || !isset($_GET['tab'])){ echo 'style="display:none"'; }?>>
        
        <div class="newadd">
            <a href="?page=catering&subpage=add-catering-subcategory">Skapa ny kategori</a>
        </div>
        <div class="clear"></div>
        
        	<table cellpadding="0" cellspacing="0" border="0" class="display" id="thecateringsubcat">
            <thead>
                <tr>
                	<th>#</th>
                    <th>Meny</th>
                    <th>Namn</th>
                    <th>Beskrivning</th>
                    <th>Tillval antal (max)</th>
                    <th>Åtgärd</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $q=mysql_query("select s.id as id, s.name as name, s.description as description, s.number_selected as number_selected, c.name as cname from catering_subcategory as s, catering_category as c where c.id=s.catering_category_id and s.deleted=0 order by s.id desc") or die(mysql_error());
                    
					$count=0;
                    while($r=mysql_fetch_assoc($q)){
                		$count++;
				?>
                    <tr class="gradeX gradeX-<?php echo $r['id'];?>" align="center">
                    	<td><?php echo $count; ?></td>
                        <td><?php echo $r['cname'];?></td>
                        <td><?php echo ucwords($r['name']);?></td>
                        <td><?php echo $r['description'];?></td>
                        <td><?php echo $r['number_selected']; ?></td>
                        <td><a href="?page=catering-subcategory&subpage=edit-catering-subcategory&id=<?php echo $r['id']; ?>" class="edit-catering-subcategory" title="Redigera"><img src="images/edit.png" alt="Redigera"></a> <?php if($_SESSION['login']['type']<3){ ?><a href="#" class="delete-catering-subcategory-box" title="Radera" data-rel="<?php echo $r['id']; ?>"><img src="images/delete.png" alt="Radera"></a><?php } ?></td>
                    </tr>
                <?php		
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                	<th>#</th>
                    <th>Meny</th>
                    <th>Namn</th>
                    <th>Beskrivning</th>
                    <th>Tillval antal (max)</th>
                    <th>Åtgärd</th>
                </tr>
            </tfoot>
        </table>    
        
        </div>
        <!-- end .ta3 -->
        
    </div>
</div>

<div class="fade"></div>
<div class="delete-catering-menu-box modalbox">
	<h2>Bekräfta borttagning<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Vill du fortsätta?</p>
        <input type="button" value="Utför">
    </div>
</div>

<div class="delete-catering-category-box modalbox">
	<h2>Bekräfta borttagning<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Vill du fortsätta?</p>
        <input type="button" value="Utför">
    </div>
</div>

<div class="delete-catering-subcategory-box modalbox">
	<h2>Bekräfta borttagning<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Vill du fortsätta?</p>
        <input type="button" value="Utför">
    </div>
</div>

<div class="delete-catering-box modalbox">
	<h2>Bekräfta borttagning<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Vill du fortsätta?</p>
        <input type="button" value="Utför">
    </div>
</div>