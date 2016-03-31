<?php

	function getPrice($id){
		
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
	
	jQuery('.delete-catering-category-box').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .modalbox').fadeIn();
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
	
});
</script>

<div class="page menu-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                	echo 'Catering Kategori';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
        <div class="page-header-right">
        	<a href="?page=catering&subpage=add-catering-category" class="add-catering-category">Skapa ny</a>
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
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Added Description</th>
                    <th>Number of Menu to be Selected</th>
                    <th>Minimum Number of Orders</th>
                    <th>Prices</th>
                    <th>Action</th>
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
                        <td><?php echo getPrice($r['id']); ?></td>
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
                    <th>Name</th>
                    <th>Description</th>
                    <th>Added Description</th>
                    <th>Number of Menu to be Selected</th>
                    <th>Minimum Number of Orders</th>
                    <th>Prices</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>    
        
    </div>
</div>

<div class="fade"></div>
<div class="delete-catering-category-box modalbox">
	<h2>Bekräfta borttagning<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Vill du fortsätta?</p>
        <input type="button" value="Utför">
    </div>
</div>