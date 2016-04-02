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
		jQuery('.addprices .catering-price').numeric();
	}

	jQuery(function(){
		
		jQuery('.catering-price, .catering-number, .catering-minimum').numeric();
		
		jQuery('.catering-number').keyup(function(){
			var val = jQuery(this).val();
			
			jQuery(this).val(replaceDot(val));
			
		});
		
		
		jQuery('.addprice').click(function(){
			
			counter+=1;
			
			jQuery('.addprices').append('<span class="price'+(counter)+' prices"><input type="text" class="catering-price txt" onfocus="toNumeric()"> / <select class="catertype"><option value="person">person</option><option value="set">set</option><option value="par">par</option></select>&nbsp;<input type="text" class="catering-category-price-desc txt" placeholder="Price Description"><img src="images/delete-small.png" style="float:right; cursor:pointer; margin:3px 0 0;" class="deleteprice" onclick="deletePrice(\''+(counter)+'\')"></span><span style="display:block;"></span>');
			
		});
		
		
		//add catering category
		jQuery('.add-catering-category-btn').click(function(){
			var name = jQuery('.catering-category-name').val();
			var desc = tinymce.get('catering-category-desc').getContent();
			var added_desc = tinymce.get('catering-category-added').getContent();
			var number = jQuery('.catering-number').val();
			var minorder = Number(jQuery('.catering-minimum').val());
			var checknote = jQuery('.catering-category-notes').prop('checked');
			var prices = '';
			var hasnotes=0;
			
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
					 url: "actions/add-catering-category.php",
					 type: 'POST',
					 data: 'name='+encodeURIComponent(name)+'&desc='+encodeURIComponent(desc)+'&added_desc='+encodeURIComponent(added_desc)+'&prices='+encodeURIComponent(prices)+'&number='+encodeURIComponent(number)+'&minorder='+encodeURIComponent(minorder)+'&hasnotes='+encodeURIComponent(hasnotes),
					 success: function(value){
						 
						if(value!='Invalid'){
							jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Catering Category successfully added.');
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

<div class="page add-catering-category-page">
	<div class="page-header">
    	<div class="page-header-left">
            <h2>
            	<?php
					echo 'Skapa ny meny';
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
                <td><input type="text" class="catering-category-name txt"></td>
            </tr>
            <tr>
            	<td>Beskrivning :</td>
                <td><textarea class="catering-category-desc txt" id="catering-category-desc"></textarea>
                </td>
            </tr>
            <tr>
            	<td>Beskrivning för allergiker :</td>
                <td><textarea class="catering-category-added txt" id="catering-category-added"></textarea>
                </td>
            </tr>
            <tr>
            	<td>Minsta beställning :</td>
                <td><input type="text" class="catering-minimum txt"></td>
            </tr>
            
            <!--<tr>
            	<td colspan="2">If no subcategory,</td>
            </tr>-->
            <tr>
            	<td>Tillval antal (max) :</td>
                <td><input type="text" class="catering-number txt"></td>
            </tr>
            <tr>
            	<td>Pris :</td>
                <td>
                	<span class="price1 prices">
                        <input type="text" class="catering-price txt"> / 
                        <select class="catertype">
                            <option value="person">person</option>
                            <option value="set">set</option>
                            <option value="par">par</option>
                        </select>
                        <input type="text" class="catering-category-price-desc txt" placeholder="Prisbeskrivning">
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
            	<td>Tillämpa textrutan<br>”övriga önskemål” i kundvagnen :</td>
                <td><input type="checkbox" class="catering-category-notes" checked="checked"></td>
            </tr>
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn add-catering-category-btn" value="Utför"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->
