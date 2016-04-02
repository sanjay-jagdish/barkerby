<?php include_once('redirect.php'); ?>

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
		jQuery('.add-catering-menu-btn').click(function(){
			
			var cat = jQuery('.catering-menu-category').val();
			var subcat = jQuery('.catering-menu-subcategory').val();
			var name = jQuery('.catering-menu-name').val();
			var desc = tinymce.get('catering-menu-desc').getContent();
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
					 url: "actions/add-catering-menu.php",
					 type: 'POST',
					 data: 'name='+encodeURIComponent(name)+'&desc='+encodeURIComponent(desc)+'&cat='+encodeURIComponent(cat)+'&prices='+encodeURIComponent(prices)+'&subcat='+encodeURIComponent(subcat),
					 success: function(value){
						 
						if(value!='Invalid'){
							jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Catering Menu successfully added.');
							
							setTimeout("window.location='?page=catering-menu'",2000);
						 
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
					echo 'Skapa ny rätt';
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
                        <option value="<?php echo $r['id']; ?>"><?php echo $r['name']; ?></option>
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
                    </select>	
                </td>
            </tr>
            <tr>
            	<td>Rätt :</td>
                <td><input type="text" class="catering-menu-name txt"></td>
            </tr>
            <tr>
            	<td>Beskrivning :</td>
                <td><textarea class="catering-category-desc txt" id="catering-menu-desc"></textarea>
                </td>
            </tr>
            <tr>
            	<td>Pris :</td>
                <td>
                	<span class="price1 prices">
                        <input type="text" class="catering-menu-price txt"> / 
                        <select class="catering-menu-type">
                            <option value="person">person</option>
                            <option value="set">set</option>
                            <option value="par">par</option>
                        </select>
                        <input type="text" class="catering-menu-price-desc txt" placeholder="Prisbeskrivning">
                    </span>
                    <img src="images/add-small.png" style="float:right; cursor:pointer; margin:3px 0 0;" class="addprice">
                </td>
            </tr>
            <tr>
            	<td></td>
            	<td>
                	<div class="addprices"></div>	
                </td>
            </tr>
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn add-catering-menu-btn" value="Utför"></td>
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
