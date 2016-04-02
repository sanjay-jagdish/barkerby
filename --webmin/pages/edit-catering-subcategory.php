<?php include_once('redirect.php'); 

	$query=mysql_query("select * from catering_subcategory where id='".$_GET['id']."'");
 	$row=mysql_fetch_assoc($query);

?>

<script type="text/javascript">

	function replaceDot(val){
		
		return val.replace('.','');
		
	}

	jQuery(function(){
		
		jQuery('.catering-subnumber').numeric();
		
		jQuery('.catering-subnumber').keyup(function(){
			var val = jQuery(this).val();
			
			jQuery(this).val(replaceDot(val));
			
		});
		
		
		
		//add catering category
		jQuery('.edit-catering-subcategory-btn').click(function(){
			var cat = jQuery('.catering-category-sub').val();
			var name = jQuery('.catering-subcategory-name').val();
			var desc = tinymce.get('catering-subcategory-desc').getContent();
			var number = jQuery('.catering-subnumber').val();
			var id = jQuery(this).attr('data-rel');
			
			jQuery('.displaymsg').fadeOut('slow');
			
			
			if(name!=''){
				
				jQuery.ajax({
					 url: "actions/edit-catering-subcategory.php",
					 type: 'POST',
					 data: 'name='+encodeURIComponent(name)+'&desc='+encodeURIComponent(desc)+'&number='+encodeURIComponent(number)+'&cat='+encodeURIComponent(cat)+'&id='+encodeURIComponent(id),
					 success: function(value){
						 
						if(value!='Invalid'){
							jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Catering Subcategory successfully modified.');
							setTimeout("window.location='?page=catering-menu&tab=3'",2000);
							//setTimeout("window.location.reload();",2000);
						 }
						 else{
							jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Catering Subcategory already exists.');	
						 }
						 
					 }
				});
				
			}
			 else{
					jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Name is required.');	
		      }
			
			
		});
		
	});
	
</script>

<div class="page edit-catering-subcategory-page">
	<div class="page-header">
    	<div class="page-header-left">
            <h2>
            	<?php
					echo 'Redigera Kategori';
				?>
            </h2>
        </div>
      	<!-- end .page-header-left -->
    </div>
    <!-- end .page-header -->
    <div class="clear"></div>
    
    <div class="page-content">
    	<table>
        	<tr>
            	<td>Meny :</td>
                <td>
                	<select class="catering-category-sub">
                        <?php
                        	$q=mysql_query("select id,name from catering_category where deleted=0");
							while($r=mysql_fetch_assoc($q)){
						?>
                        	<option value="<?php echo $r['id']; ?>" <?php if($r['id']==$row['catering_category_id']) echo 'selected="selected"'; ?>> <?php echo $r['name']; ?> </option>
                        <?php		
							}
						?>
                    </select>
                </td>
            </tr>
            <tr>
            	<td>Namn :</td>
                <td><input type="text" class="catering-subcategory-name txt" value="<?php echo $row['name']; ?>"></td>
            </tr>
            <tr>
            	<td>Beskrivning :</td>
                <td><textarea class="catering-category-desc txt" id="catering-subcategory-desc"><?php echo $row['description']; ?></textarea>
                </td>
            </tr>
            <tr>
            	<td>Tillval antal (max) :</td>
                <td><input type="text" class="catering-subnumber txt" value="<?php echo $row['number_selected']; ?>"></td>
            </tr>
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn edit-catering-subcategory-btn" value="Utför" data-rel="<?php echo $_GET['id']; ?>"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->
