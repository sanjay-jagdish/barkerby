<?php include_once('redirect.php'); ?>
<script type="text/javascript" >
 jQuery(document).ready(function() { 
		var clck = 0;
            jQuery('#photoimg').on('change', function()			{ 
				jQuery("#preview").html('');
				jQuery("#preview").html('<img src="scripts/ajaximage/loader.gif" alt="Uploading...."/>');
				jQuery("#imageform").ajaxForm({
					target: '#preview'
				}).submit();
				jQuery(".aremoveimg").css('display', 'block');
				
			});
			
			jQuery('.aremoveimg').click(function(){
				var id = jQuery(this).attr('data-id');
				jQuery('#preview').html('<img src="images/no-photo-available.jpg" class="preview">');
				jQuery('#photoimg').val('');
				
				jQuery.ajax({
					 url: "actions/remove-image.php",
					 type: 'POST',
					 data: 'id='+encodeURIComponent(id),
					 success: function(value){
						 	jQuery('.aremoveimg').css('display', 'none');
						 }
				});
				
			});
			
			
			jQuery('.menu-discount').keyup(function(){
				var val=jQuery(this).val();
				
				
				if(val!=''){
					if(val<100){
						
						if(val<0){
							jQuery(this).val('');
						}
						
					}
					else{
						jQuery(this).val(100);
					}
				}
				else{
					jQuery(this).val('');
				}
						
			});
			
			$('.add-opt').click(function(e){
				
				$('.opt-msg').slideUp();
				$('#opt-price').css({'border-color':'#ccc'});
				$('#opt-name').css({'border-color':'#ccc'});
				clck++;
				e.preventDefault();
				name = $('#opt-name').val();
				pr = $('#opt-price').val();
				
				if(name!=''){
					if(pr==''){
						pr='';
					}else{
						pr = formatNumber(pr);	
					}
					field = '<p><input type="text" value="'+name+'" class="txt" readonly="readonly" id="optname-'+clck+'" name="opt-name" />&nbsp;<input style="width:50px; text-align:right;" name="opt-price" type="text" class="txt" readonly="readonly" id="optpr-'+clck+'" value="'+pr+'" />&nbsp;<a href="javascript:void(0)" onclick="remove_opt('+clck+')"><img src="images/delete-small.png" style="position: relative; top: 8px;"></a></p>';
					
					$('.opt-holder').prepend('<div class="opt-item new" id="'+clck+'">'+field+'</div>');
					$('#opt-price').val('');
					$('#opt-name').val('');
					return false;
				}else{
					$('.opt-msg').slideDown().addClass('errormsg').html('Alternativ namn krävs!');
					$('#opt-name').css({'border-color':'red'});
				}
			});
			
			/*$('.remove-opt img').click(function(e){
				e.preventDefault();
				optid = $(this).attr('rel');
				alert(optid);
				$('.opt-item#'+optid).fadeOut().remove();
				return false;
			});*/
		$("#opt-price").keydown(function (e) {
			// Allow: backspace, delete, tab, escape, enter and .
			if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 189]) !== -1 ||
				 // Allow: Ctrl+A
				(e.keyCode == 65 && e.ctrlKey === true) || 
				 // Allow: home, end, left, right
				(e.keyCode >= 35 && e.keyCode <= 39)) {
					 // let it happen, don't do anything
					 return;
			}
			console.log(e.keyCode);
			// Ensure that it is a number and stop the keypress
			if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				e.preventDefault();
			}
		});
  }); 
  function remove_opt(id){
	  $('.opt-item#'+id).slideUp();
	  $('#remove').addClass($('.opt-item#'+id).attr('alt'));
	  setTimeout(function(){
		   $('.opt-item#'+id).remove();
	  },500);
	  return false;
  }
  function formatNumber(num){    
		var n = num.toString();
		var nums = n.split('.');
		var newNum = "";
		if (nums.length > 1)
		{
			var dec = nums[1].substring(0,2);
			newNum = nums[0] + "." + dec;
		}
		else
		{
		newNum = num+'.00';
		}
		return newNum;
	}
</script>
<style>
	.opt-holder .opt-item input{
		border:0;
		padding:10px 0;
	}
	.opt-holder .opt-item{
		display:inline-block;
		border-bottom:1px solid #ccc;
		min-width:51%;
	}
	
	.aorange{
		text-decoration:none;
		color: #e67e22;
	}
	
	.aorange:hover{
		text-decoration:underline;
	}
	
	.aremoveimg{
		margin: 10px 0 0 55px;
		display: inline-block;
	}
	
	#preview{
		color:#555 !important;
	}
</style>
<div class="page edit-menu-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
                Redigera rätt
            </h2>
        </div>
      	<!-- end .page-header-left -->
    </div>
    <!-- end .page-header -->
    <div class="clear"></div>
    
    <div class="page-content">
    	<div class="page-content-left">
        	<?php
				$q=mysql_query("select sub_category_id, name, currency_id, description, price, image, featured, type, discount,discount_unit, cat_id from menu where id=".$_GET['id']);
				$row=mysql_fetch_assoc($q);
			?>
            <table>
                <tr>
                    <td>Kategori :</td>
                    <td>
                        <select class="menu-category txt">
                        <?php
                            $q=mysql_query("select id, name from category where deleted=0 order by name");
							
                            while($r=mysql_fetch_assoc($q)){ ?>         
                            	<option value="<?php echo $r['id'];?>*maincat" <?php if($r['id']==$row['cat_id']) echo 'selected="selected"'?>><?php echo $r['name'];?></option>                         	
								<?php	
                                $q1=mysql_query("select id,name from sub_category where deleted = 0 and category_id = '" . $r['id'] ."' order by name");
                                
                                while($r1=mysql_fetch_assoc($q1)){ ?>
								<option value="<?php echo $r1['id'];?>*subcat" <?php if($r1['id']==$row['sub_category_id']) echo 'selected="selected"'?>><?php echo $r['name'] . " - " . $r1['name'];?></option>
							<?php
								}
                            }
                        ?>
                        </select>  <a href="crisp.php?page=subcategory&parent=menu" class="aorange">skapa ny kategori</a>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td><font style="color:#e67e22;">*</font>Rätt :</td>
                    <td><input type="text" class="menu-name txt" value="<?php echo $row['name'];?>"></td>
                </tr>
                <tr>
                    <td width="80"><font style="color:#e67e22;">*</font>Beskrivning :</td>
                    <td><textarea class="menu-desc txt"><?php echo $row['description']; ?></textarea></td>
                </tr>
                <tr>
                    <td>Valuta :</td>
                    <td>
                    	<?php
                        	$q=mysql_query("select name,shortname,id from currency where deleted=0 and set_default=1");	
							$r=mysql_fetch_array($q);
						?>
                    	<input type="text" readonly="readonly" class="menu-currency txt" value="<?php echo ucwords($r[0])." - ".strtolower($r[1]); ?>" data-rel="<?php echo $r[2]; ?>">
                    
                    </td>
                </tr>
                <tr>
                    <td><font style="color:#e67e22;">*</font>Pris :</td>
                    <td><input type="text" class="menu-price txt" value="<?php echo $row['price']; ?>"></td>
                </tr>
                <tr>
                    <td>Visa :</td>
                    <td><input type="checkbox" class="menu-featured" <?php if($row['featured']==1) echo 'checked="checked"';?>></td>
                </tr>
                <?php
                	if(strlen($row['type'])>1){
						$type=0;
					}
					else{
						if($row['type']==1){
							$type=1;
						}
						else{
							$type=2;
						}
					}
				?>
                <tr>
                    <td><font style="color:#e67e22;">*</font>Typ :</td>
                    <td>
                    	<input type="checkbox" value="1" <?php if($type==1 || $type==0) echo 'checked="checked"';?> id="dinetype" class="menutype"> <label for="dinetype">Á la carte</label><br>
                        <input type="checkbox" value="2" <?php if($type==2 || $type==0) echo 'checked="checked"';?> id="taketype" class="menutype"> <label for="taketype">Take Away</label>
                    </td>
                </tr>
                <tr>
                    <td>Take away rabatt :</td>
                     <td><input type="text" class="menu-discount txt" style="width:190px !important;" value="<?php 
					 		if($row['discount']>0){ echo $row['discount']; } ?>">
                            
                           <select class="menu_discount_unit">
                                <option value="fix" <?php echo ($row['discount_unit']=="fix") ? 'selected':''?>>kr</option>
                                <option value="percent"  <?php echo ($row['discount_unit']=="percent") ? 'selected':''?>>%</option>
                             </select>
                            </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <form id="imageform" method="post" enctype="multipart/form-data" action='scripts/ajaximage/ajaximage.php'>
                            Ladda upp bild <input type="file" name="photoimg" id="photoimg" class="txt" />
                        </form>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><br /><br />
                        <p><strong>Alternativ</strong></p><br />
                         <div class="opt-msg" style="padding:10px 20px; display:none;"></div>
                        <p><input type="text" class="txt" name="opt-name" placeholder="Alternativ namn" id="opt-name" /> <input style="width:50px; text-align:right" class="txt" name="opt-price" type="text" id="opt-price" placeholder="0.00" /> <a href="#" class="add-opt"><img src="images/add-small.png" style="position: relative; top: 8px;"></a></p>
                        <input type="hidden" id="remove" />
                        <br />
                        <div class="opt-holder">
							<?php 
								$opt_q = mysql_query("select * from menu_options where menu_id =".$_GET['id']);
								if(mysql_num_rows($opt_q)>0){
									while($option = mysql_fetch_assoc($opt_q)){
							?>                        	
                        	<div class="opt-item old" id="x<?php echo $option['id']?>" alt="<?php echo $option['id']?>">
                            <input type="text" readonly="readonly" value="<?php echo $option['name']?>" class="txt" id="optname-6" name="opt-name">&nbsp;<input style="width:50px; text-align:right;" readonly="readonly" name="opt-price" type="text" class="txt" id="optpr-6" value="<?php echo ($option['price']!=0)? number_format($option['price'],2) :''?>" />&nbsp;<a href="javascript:void(0)" onclick="remove_opt('x<?php echo $option['id'];?>')"><img src="images/delete-small.png" style="position: relative; top: 8px;"></a></div>
                            <?php 
								} 
							}?>
                        </div>   <br />                  
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="right"><input type="button" class="btn edit-menu-btn" value="Utför" data-rel="<?php echo $_GET['id']; ?>" data-id="<?php echo $_GET['nav']; ?>"></td>
                </tr>
            </table>
        </div>
        <div class="page-content-right">
        	<div id='preview'>
            	<?php
                	if($row['image']!=''){
						
						$imglink="uploads/".$row['image'];
				?>
                	<a href="<?php echo $imglink; ?>" data-lightbox="menus"><img src="uploads/<?php echo $row['image']; ?>" class='preview' title="<?php echo $row['image']; ?>"></a>
                <?php		
					}
					else{
				?>
                	<img src="images/no-photo-available.jpg" class='preview'>
                <?php	
					}
				?>
                
            </div>
            <?php
                if($row['image']==''){?>
            		<style>
                    	.aremoveimg{
							display: none;
						}
                    </style>
			<?php } ?>
            
            <a href="javascript:void(0)" class="aremoveimg aorange" data-id="<?php echo $_GET['id']; ?>">Ta bort bild</a>
        </div>
            
        <div class="clear"></div>
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->
