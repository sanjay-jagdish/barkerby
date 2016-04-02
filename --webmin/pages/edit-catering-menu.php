<?php include_once('redirect.php'); 

$qq=mysql_query("select * from catering_menu where id='".$_GET['id']."'");
$row=mysql_fetch_assoc($qq);

?>

<script type="text/javascript">

	var counter = jQuery('.prices').length+1;

	function deletePrice(id){
		jQuery('.price'+id).fadeOut();	
		jQuery('.price'+id).remove();	
	}
	
	function replaceDot(val){
		
		return val.replace('.','');
		
	}
	
	function toNumeric(){
		jQuery('.addprices .catering-menu-price').numeric();
	}

	jQuery(function(){
		
		jQuery('.catering-menu-price').numeric();
		
		
		jQuery('.addprice').click(function(){
			
			counter+=1;
			
			jQuery('.addprices').append('<span class="price'+(counter)+' prices"><input type="text" class="catering-menu-price txt" onfocus="toNumeric()"> / <select class="catering-menu-type"><option value="person">person</option><option value="set">set</option><option value="par">par</option></select>&nbsp;<input type="text" class="catering-menu-price-desc txt" placeholder="Price Description"><img src="images/delete-small.png" style="float:right; cursor:pointer; margin:3px 0 0;" class="deleteprice" onclick="deletePrice(\''+(counter)+'\')"></span><span style="display:block;"></span>');
			
		});
		
		
		//add catering category
		jQuery('.edit-catering-menu-btn').click(function(){
			
			var cat = jQuery('.catering-menu-category').val();
			var subcat = jQuery('.catering-menu-subcategory').val();
			var name = jQuery('.catering-menu-name').val();
			var desc = tinymce.get('catering-menu-desc').getContent();
			var id = jQuery(this).attr('data-rel');
			var prices = '';
			
			jQuery('.displaymsg').fadeOut('slow');
			
			jQuery('.prices').each(function(){
				
				var theprice = jQuery(this).children('.catering-menu-price').val();
				var thetype = jQuery(this).children('.catering-menu-type').val();
				var thedesc = jQuery(this).children('.catering-menu-price-desc').val();
				
				
				if(theprice!=''){
					
					prices+=theprice+'^^'+thetype+'^^'+thedesc+'**';
					
				}
				
			});
			
			if(prices!=''){
				prices = prices.substr(0, prices.length-2);
			}
			
			
			if(name!=''){
				
				jQuery.ajax({
					 url: "actions/edit-catering-menu.php",
					 type: 'POST',
					 data: 'name='+encodeURIComponent(name)+'&desc='+encodeURIComponent(desc)+'&cat='+encodeURIComponent(cat)+'&prices='+encodeURIComponent(prices)+'&subcat='+encodeURIComponent(subcat)+'&id='+encodeURIComponent(id),
					 success: function(value){
						 
						if(value!='Invalid'){
							jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Catering Menu successfully modified.');
							setTimeout("window.location='?page=catering-menu'",2000);
							//setTimeout("window.location.reload();",2000);
						 }
						 else{
							jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Catering Menu already exists.');	
						 }
						 
					 }
				});
				
			}
			 else{
					jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Name and Category are required fields.');	
		      }
			
			
		});
		
		jQuery('.catering-menu-category').change(function(){
		
			var val = jQuery(this).val();
			
			loadingBox();
			jQuery.ajax({
				 url: "actions/get-catering-subcategory.php",
				 type: 'POST',
				 data: 'cat='+encodeURIComponent(val),
				 success: function(value){
					 
					jQuery('.catering-menu-subcategory').html(value);
					jQuery('.fade, .loadingbox').fadeOut(); 
				 }
			});
		
		});
		
	});
	
	function loadingBox(){
	
		jQuery.fn.center = function ()
		{
			this.css("position","fixed");
			this.css("top", ((jQuery(window).height() / 2) - (this.outerHeight() / 2))+50);
			return this;
		}
		
		jQuery('.fade, .loadingbox').fadeIn();
		jQuery('.loadingbox').center();
	
	
	}
	
</script>

<div class="page add-catering-menu-page">
	<div class="page-header">
    	<div class="page-header-left">
            <h2>
            	<?php
					echo 'Redigera rätt';
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
                    <select class="catering-menu-category">
                    	<option value="">Välj</option>
						<?php
                            $q=mysql_query("select id,name from catering_category where deleted=0");
							while($r=mysql_fetch_assoc($q)){
						?>
                        <option value="<?php echo $r['id']; ?>" <?php if($r['id']==$row['catering_category_id']) echo 'selected="selected"';?>><?php echo $r['name']; ?></option>
                        <?php		
							}
                        ?>
                    </select>	
                </td>
            </tr>
            <tr>
            	<td>Kategori :</td>
                <td>
                    <select class="catering-menu-subcategory">
						<option value="">Välj</option>
                        <?php
                        	$qw=mysql_query("select s.id as id, s.name as name from catering_subcategory as s, catering_category as c where c.id=s.catering_category_id and s.catering_category_id='".$row['catering_category_id']."' and c.deleted=0 and s.deleted=0") or die(mysql_error());
							while($rw=mysql_fetch_assoc($qw)){
						?>
                        	<option value="<?php echo $rw['id'];?>" <?php if($rw['id']==$row['catering_subcategory_id']) echo 'selected="selected"';?>><?php echo $rw['name'];?></option>
                        <?php		
							}
						?>
                    </select>	
                </td>
            </tr>
            <tr>
            	<td>Rätt :</td>
                <td><input type="text" class="catering-menu-name txt" value="<?php echo $row['name'];?>"></td>
            </tr>
            <tr>
            	<td>Beskrivning :</td>
                <td><textarea class="catering-category-desc txt" id="catering-menu-desc"><?php echo $row['description'];?></textarea>
                </td>
            </tr>
            <tr>
            	<td>Pris :</td>
                <td>
                
                <?php
                	$qq=mysql_query("select * from catering_menu_price where catering_menu_id='".$_GET['id']."' order by id limit 1");
					
					$rr=mysql_fetch_assoc($qq);
				?>
                
                	<span class="price1 prices">
                        <input type="text" class="catering-menu-price txt" value="<?php echo $rr['price'];?>"> / 
                        <select class="catering-menu-type">
                            <option value="person" <?php if($rr['price_type']=='person') echo 'selected="selected"'; ?>>person</option>
                            <option value="set" <?php if($rr['price_type']=='set') echo 'selected="selected"'; ?>>set</option>
                            <option value="par" <?php if($rr['price_type']=='par') echo 'selected="selected"'; ?>>par</option>
                        </select>
                        <input type="text" class="catering-menu-price-desc txt" placeholder="Prisbeskrivning" value="<?php echo $rr['price_description'];?>">
                    </span>
                    <img src="images/add-small.png" style="float:right; cursor:pointer; margin:3px 0 0;" class="addprice">
                </td>
            </tr>
            <tr>
            	<td></td>
            	<td>
                	<div class="addprices">
                    <?php
                    	$qqq=mysql_query("select * from catering_menu_price where catering_menu_id='".$_GET['id']."' and id<>'".$rr['id']."' order by id");
						
						$count = 1;
						while($rrr=mysql_fetch_assoc($qqq)){
							$count++;
					?>
                    <span class="price<?php echo $count;?> prices"><input type="text" class="catering-menu-price txt" value="<?php echo $rrr['price']; ?>"> / <select class="catering-menu-type"><option value="person" <?php if($rrr['price_type']=='person') echo 'selected="selected"'; ?>>person</option><option value="set" <?php if($rrr['price_type']=='set') echo 'selected="selected"'; ?>>set</option></select>&nbsp;<input type="text" class="catering-menu-price-desc txt" placeholder="Price Description" value="<?php echo $rrr['price_description'];?>"><img src="images/delete-small.png" style="float:right; cursor:pointer; margin:3px 0 0;" class="deleteprice" onclick="deletePrice('<?php echo $count;?>')"></span><span style="display:block;"></span>
                    <?php		
						}
					?>
                    </div>	
                </td>
            </tr>
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn edit-catering-menu-btn" value="Utför" data-rel="<?php echo $_GET['id']; ?>"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->

<div class="fade"></div>
<div class="loadingbox">
	<h2>Loading...Please wait.</h2>
</div>
