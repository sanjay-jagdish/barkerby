<?php include_once('redirect.php'); 

 $q=mysql_query("select * from catering_category where id='".$_GET['id']."'");
 $r=mysql_fetch_assoc($q);

?>

<div class="page add-catering-category-page">
	<div class="page-header">
    	<div class="page-header-left">
            <h2>
            	<?php
					echo 'Redigera meny';
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
            	<td><font style="color:red; font-size:10px;">*</font>Namn :</td>
                <td><input type="text" class="catering-category-name txt" value="<?php echo $r['name']; ?>"></td>
            </tr>
            <tr>
            	<td>Beskrivning :</td>
                <td><textarea class="catering-category-desc txt" id="catering-category-desc"><?php echo $r['description']; ?></textarea>
                </td>
            </tr>
            <tr>
            	<td>Beskrivning för allergiker :</td>
                <td><textarea class="catering-category-added txt" id="catering-category-added"><?php echo $r['added_description']; ?></textarea>
                </td>
            </tr>
            <tr>
            	<td>Minsta beställning:</td>
                <td><input type="text" class="catering-minimum txt" value="<?php echo $r['minimum_order']; ?>"></td>
            </tr>
            
            <!--<tr>
            	<td colspan="2">If no subcategory,</td>
            </tr>-->
            <tr>
            	<td>Tillval antal (max):</td>
                <td><input type="text" class="catering-number txt" value="<?php echo $r['number_selected']; ?>"></td>
            </tr>
            <tr>
            	<td>Pris :</td>
                <td>
                
                <?php
                	$qq=mysql_query("select * from catering_category_price where catering_category_id='".$_GET['id']."' order by id limit 1");
					
					$rr=mysql_fetch_assoc($qq);
				?>
                
                	<span class="price1 prices">
                        <input type="text" class="catering-price txt" value="<?php echo $rr['price'];?>"> / 
                        <select class="catertype">
                            <option value="person" <?php if($rr['price_type']=='person') echo 'selected="selected"'; ?>>person</option>
                            <option value="set" <?php if($rr['price_type']=='set') echo 'selected="selected"'; ?>>set</option>
                            <option value="par" <?php if($rr['price_type']=='par') echo 'selected="selected"'; ?>>par</option>
                        </select>
                        <input type="text" class="catering-category-price-desc txt" placeholder="Prisbeskrivning" value="<?php echo $rr['price_description'];?>">
                    </span>
                    <img src="images/add-small.png" style="float:right; cursor:pointer; margin:3px 0 0;" class="addprice">
                </td>
            </tr>
            <tr>
            	<td></td>
            	<td>
                	<div class="addprices">
                    <?php
                    	$qqq=mysql_query("select * from catering_category_price where catering_category_id='".$_GET['id']."' and id<>'".$rr['id']."' order by id");
						
						$count = 1;
						while($rrr=mysql_fetch_assoc($qqq)){
							$count++;
					?>
                    <span class="price<?php echo $count;?> prices"><input type="text" class="catering-price txt" value="<?php echo $rrr['price']; ?>"> / <select class="catertype"><option value="person" <?php if($rrr['price_type']=='person') echo 'selected="selected"'; ?>>person</option><option value="set" <?php if($rrr['price_type']=='set') echo 'selected="selected"'; ?>>set</option></select>&nbsp;<input type="text" class="catering-category-price-desc txt" placeholder="Price Description" value="<?php echo $rrr['price_description'];?>"><img src="images/delete-small.png" style="float:right; cursor:pointer; margin:3px 0 0;" class="deleteprice" onclick="deletePrice('<?php echo $count;?>')"></span><span style="display:block;"></span>
                    <?php		
						}
					?>
                    </div>	
                </td>
            </tr>
            <tr>
            	<td>Tillämpa textrutan<br>”övriga önskemål” i kundvagnen:</td>
                <td><input type="checkbox" class="catering-category-notes" <?php if($r['has_notes']==1) echo 'checked="checked"';?>></td>
            </tr>
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn edit-catering-category-btn" value="Utför" data-rel="<?php echo $_GET['id']; ?>"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->

<script type="text/javascript">

	var counter = jQuery('.prices').length;

	function deletePrice(id){
		jQuery('.price'+id).fadeOut();	
		jQuery('.price'+id).remove();	
	}
	
	function replaceDot(val){
		
		return val.replace('.','');
		
	}
	
	function toNumeric(){
		jQuery('.addprices .catering-price').numeric();
	}

	jQuery(function(){
		
		jQuery('.catering-price, .catering-number').numeric();
		
		jQuery('.catering-number').keyup(function(){
			var val = jQuery(this).val();
			
			jQuery(this).val(replaceDot(val));
			
		});
		
		
		jQuery('.addprice').click(function(){
			
			counter+=1;
			
			jQuery('.addprices').append('<span class="price'+(counter)+' prices"><input type="text" class="catering-price txt" onfocus="toNumeric()"> / <select class="catertype"><option value="person">person</option><option value="set">set</option><option value="par">par</option></select>&nbsp;<input type="text" class="catering-category-price-desc txt" placeholder="Price Description"><img src="images/delete-small.png" style="float:right; cursor:pointer; margin:3px 0 0;" class="deleteprice" onclick="deletePrice(\''+(counter)+'\')"></span><span style="display:block;"></span>');
			
		});
		
		
		//add catering category
		jQuery('.edit-catering-category-btn').click(function(){
			var name = jQuery('.catering-category-name').val();
			var desc = tinymce.get('catering-category-desc').getContent();
			var added_desc = tinymce.get('catering-category-added').getContent();
			var number = jQuery('.catering-number').val();
			var minorder = Number(jQuery('.catering-minimum').val());
			var checknote = jQuery('.catering-category-notes').prop('checked');
			var prices = '';
			var hasnotes=0;
			
			var id = jQuery(this).attr('data-rel');
			
			jQuery('.displaymsg').fadeOut('slow');
			
			jQuery('.prices').each(function(){
				
				var theprice = jQuery(this).children('.catering-price').val();
				var thetype = jQuery(this).children('.catertype').val();
				var thedesc = jQuery(this).children('.catering-category-price-desc').val();
				
				
				if(theprice!=''){
					
					prices+=theprice+'^^'+thetype+'^^'+thedesc+'**';
					
				}
				
			});
			
			if(prices!=''){
				prices = prices.substr(0, prices.length-2);
			}
			
			
			if(name!=''){
				
				if(checknote){
					hasnotes=1;
				}
				
				jQuery.ajax({
					 url: "actions/edit-catering-category.php",
					 type: 'POST',
					 data: 'name='+encodeURIComponent(name)+'&desc='+encodeURIComponent(desc)+'&added_desc='+encodeURIComponent(added_desc)+'&prices='+encodeURIComponent(prices)+'&number='+encodeURIComponent(number)+'&id='+encodeURIComponent(id)+'&minorder='+encodeURIComponent(minorder)+'&hasnotes='+encodeURIComponent(hasnotes),
					 success: function(value){
						 
						if(value!='Invalid'){
							jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Catering Category successfully updated.');
							setTimeout("window.location='?page=catering-menu&tab=2'",2000);
							//setTimeout("window.location.reload();",2000);
						 }
						 else{
							jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Catering Category already exists.');	
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

