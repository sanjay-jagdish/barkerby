<script type="text/javascript">
jQuery(function(){
	
	jQuery('.delete-catering-subcategory-box').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .modalbox').fadeIn();
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
	
});
</script>

<div class="page menu-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                	echo 'Catering Subkategori';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
        <div class="page-header-right">
        	<a href="?page=catering&subpage=add-catering-subcategory" class="add-catering-subcategory">Skapa ny</a>
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
                    <th>Category Name</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Number of Menu to be Selected</th>
                    <th>Action</th>
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
                    <th>Category Name</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Number of Menu to be Selected</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>    
        
    </div>
</div>

<div class="fade"></div>
<div class="delete-catering-subcategory-box modalbox">
	<h2>Bekräfta borttagning<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Vill du fortsätta?</p>
        <input type="button" value="Utför">
    </div>
</div>