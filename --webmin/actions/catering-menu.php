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
			$q=mysql_query("select name from $table where id=".$id);
			
			if(mysql_num_rows($q) > 0){
			
				$val=1;
				
				$r=mysql_fetch_assoc($q);
				
				return $r['name'];
			
			}
			
		}
		
		if($val==0){
			echo '-';
		}
		
	}

?>

<script type="text/javascript">
jQuery(function(){
	
	jQuery('.delete-catering-menu-box').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .modalbox').fadeIn();
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
	
});
</script>

<div class="page menu-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                	echo 'Catering Menu';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
        <div class="page-header-right">
        	<a href="?page=catering&subpage=add-catering-menu" class="add-catering-menu">Skapa ny</a>
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
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Prices</th>
                    <th>Action</th>
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
                        <td><?php echo getParent($r['id'],'catering_category');?></td>
                        <td><?php echo getParent($r['id'],'catering_subcategory');?></td>
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
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Prices</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>    
        
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