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
				
				var theid = $(this).attr('data-id');
				
				add_opt(theid);
			});
			
			
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
		
			//adding new mopts
			$('.add-mopt').click(function(){
				var count = Number($('.mopts').last().find('.add-opt').attr('data-id'))+1;
				var counter = $('.mopts').length+1;
				
				var grporder = '<select class="grporder">';
				for(i=0;i<=counter;i++){
					var val=i;
					if(i==0){
						val='Ordningsföljd';
					}
					grporder+='<option value="'+i+'">'+val+'</option>';
				}
				grporder+='</select>';
				
				//add options
				$('.mopts').each(function(){
					$(this).find('.grporder').append('<option value="'+counter+'">'+counter+'</option>');
				});
				
				$('.new-mopts').append('<div class="mopts mopts'+count+'"><p><strong>Tillval</strong><a href="javascript:void(0)" class="remove-mopt" onclick="remove_mopt(\''+count+'\')"><img src="images/delete-small.png" style="position: relative; top: 8px; margin: 0 0 0 10px;"></a>'+grporder+'</p><br><input type="text" class="txt tillvalname" placeholder="Titel"><br><br /><select class="takeaway-tillval"><option value="0">Valfri</option><option value="1">Tvingande</option></select><br><br><div class="opt-msg" style="padding:10px 20px; display:none;"></div><p class="theopt theopt1" data-id="1"><input type="text" class="txt optname" name="opt-name" placeholder="Tillval" id="opt-name1" /> <input style="width:50px; text-align:right" class="txt optprice" name="opt-price" type="text" id="opt-price1" placeholder="0.00" />  <a href="javascript:void(0)" class="add-opt" data-id="'+count+'" onclick="add_opt(\''+count+'\')"><img src="images/add-small.png" style="position: relative; top: 8px;"></a><select class="optorder"><option value="0">Ordningsföljd</option><option value="1">1</option></select></p><div class="opt-holder"></div></div>');
			});
			
  }); 
  
  function add_opt(theid){
	 
	  var count = Number($('.mopts'+theid+' .theopt').last().attr('data-id'))+1;
	  var removeid = theid+','+count;
	  
	  var counter = $('.mopts'+theid+' .theopt').length+1;
	  var optorder = '<select class="optorder">';
		for(i=0;i<=counter;i++){
			var val=i;
			if(i==0){
				val='Ordningsföljd';
			}
			optorder+='<option value="'+i+'">'+val+'</option>';
		}
		optorder+='</select>';
		
		//add options
		$('.mopts'+theid+' .theopt').each(function(){
			$(this).find('.optorder').append('<option value="'+counter+'">'+counter+'</option>');
		});
	  
	  $('.mopts'+theid+' .opt-holder').append('<p class="theopt theopt'+count+'" data-id="'+count+'"><input type="text" class="txt optname" name="opt-name" placeholder="Tillval" id="opt-name'+count+'" /> <input style="width:50px; text-align:right" class="txt optprice" name="opt-price" type="text" id="opt-price1" placeholder="0.00" />  <a href="javascript:void(0)" class="add-opt" data-id="'+count+'" onclick="remove_opt(\''+removeid+'\')"><img src="images/delete-small.png" style="position: relative; top: 8px;"></a>'+optorder+'</p>');
	    
  } 
  
  function remove_mopt(id){
	  $('.mopts'+id).remove();
	  
	  //remove last option
	  $('.mopts').each(function(){
		 $(this).find('.grporder option').last().remove();
	  });
  }
  
  function remove_opt(id){
	  var id = id.split(',');
	  $('.mopts'+id[0]+' .theopt'+id[1]).remove();
	  
	   //remove last option
	  $('.mopts'+id[0]+' .theopt').each(function(){
		 $(this).find('.optorder option').last().remove();
	  });
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
				$q=mysql_query("select cat_id, sub_category_id, name, currency_id, description, price, takeaway_price, image, featured, type, discount,discount_unit, cat_id, single_option from menu where id=".$_GET['id']);
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
                    <td>Take away pris :</td>
                     <td>
                     	<input type="text" class="takeaway-price txt" value="<?php echo $row['takeaway_price']; ?>"/>
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
                    <td colspan="2">
                    	<br /><br />
                    	<?php
                        	$qry=mysql_query("select id, name, single_option, order_by, if(order_by=0,'A',order_by) as theorderby from menu_option_details where menu_id='".$_GET['id']."' order by theorderby, id");
							
							$detailcount = mysql_num_rows($qry);
							
							if($detailcount > 0){
								
								$count=0;
								while(list($id, $name, $single_option, $order_by)=mysql_fetch_array($qry)){
								
									$count++;
								?>
									<div class="mopts mopts<?php echo $count; ?>">
										<p><strong>Tillval</strong>
                                        
										<?php if($count==1){ ?>
                                        	<a href="javascript:void(0)" class="add-mopt"><img src="images/add-small.png" style="position: relative; top: 8px; margin: 0 0 0 10px;"></a>
                                        <?php }
											else{
										?>
                                        	<a href="javascript:void(0)" class="remove-mopt" onclick="remove_mopt('<?php echo $count; ?>')"><img src="images/delete-small.png" style="position: relative; top: 8px; margin: 0 0 0 10px;"></a>
                                        <?php	
											}
										?>
                                        
                                        <select class="grporder">
                                        	<?php
                                            	for($i=0;$i<=$detailcount;$i++){
													$val = $i;
														
													if($i==0){
														$val = 'Ordningsföljd';
													}
											?>
                                            <option value="<?php echo $i; ?>" <?php if($order_by==$i) echo 'selected="selected"'; ?>><?php echo $val; ?></option>
                                            <?php		
												}
											?>
                                        </select>
                                        
                                        </p><br><input type="text" class="txt tillvalname" placeholder="Titel" value="<?php echo $name; ?>"><br><br />
										<select class="takeaway-tillval">
											<option value="0" <?php if($single_option==0) echo 'selected="selected"'; ?>>Valfri</option>
											<option value="1" <?php if($single_option==1) echo 'selected="selected"'; ?>>Tvingande</option>
										</select><br><br>
                                        
                                       <?php
                                       		$qry1 = mysql_query("select name, price, order_by, if(order_by=0,'A',order_by) as theorderby from menu_options where menu_option_detail_id='".$id."' order by theorderby, id");
											$counter = 0;
											$optcount = mysql_num_rows($qry1); 
											
											while(list($thename, $theprice, $order_by) = mysql_fetch_array($qry1)){
									   			$counter++;
									   ?>
                                        
                                            <div class="opt-msg" style="padding:10px 20px; display:none;"></div>
                                            <p class="theopt<?php echo $counter; ?> theopt" data-id="<?php echo $counter; ?>">
                                            	<input type="text" class="txt optname" name="opt-name" placeholder="Tillval" id="opt-name<?php echo $counter; ?>" value="<?php echo $thename; ?>" />
                                                <input style="width:50px; text-align:right" class="txt optprice" name="opt-price" type="text" id="opt-price<?php echo $counter; ?>" placeholder="0.00" value="<?php echo $theprice; ?>" />  
                                             
                                            <?php if($counter==1){ ?>    
                                            	<a href="javascript:void(0)" class="add-opt" data-id="<?php echo $count; ?>"><img src="images/add-small.png" style="position: relative; top: 8px;"></a>
                                            <?php }
											else{?>
												<a href="javascript:void(0)" class="add-opt" data-id="<?php echo $counter; ?>" onclick="remove_opt('<?php echo $count.','.$counter; ?>')"><img src="images/delete-small.png" style="position: relative; top: 8px;"></a>
											<?php } ?>
                                            
                                            <select class="optorder">
												<?php
                                                    for($i=0;$i<=$optcount;$i++){
														$val = $i;
														
														if($i==0){
															$val = 'Ordningsföljd';
														}
                                                ?>
                                                <option value="<?php echo $i;?>" <?php if($order_by==$i) echo 'selected="selected"'; ?>><?php echo $val; ?></option>
                                                <?php		
                                                    }
                                                ?>
                                            </select>
                                            
                                            </p>
                                       <?php } ?> 
                                       
									 	<div class="opt-holder"></div>
									 </div>
									
                                     
                                <?php		
								
								
								}
								
							}
							else{
						?>
                            <div class="mopts mopts1">
                                <p><strong>Tillval</strong><a href="javascript:void(0)" class="add-mopt"><img src="images/add-small.png" style="position: relative; top: 8px; margin: 0 0 0 10px;"></a>
                                <select class="grporder">
									<option value="0">Ordningsföljd</option>
                                    <option value="1">1</option>
                                 </select>
                                </p><br><input type="text" class="txt tillvalname" placeholder="Titel"><br><br />
                                <select class="takeaway-tillval">
                                    <option value="0">Valfri</option>
                                    <option value="1">Tvingande</option>
                                </select><br><br>
                                 <div class="opt-msg" style="padding:10px 20px; display:none;"></div>
                                <p class="theopt1 theopt" data-id="1"><input type="text" class="txt optname" name="opt-name" placeholder="Tillval" id="opt-name1" /> <input style="width:50px; text-align:right" class="txt optprice" name="opt-price" type="text" id="opt-price1" placeholder="0.00" />  <a href="javascript:void(0)" class="add-opt" data-id="1"><img src="images/add-small.png" style="position: relative; top: 8px;"></a>
                                <select class="optorder">
									<option value="0">Ordningsföljd</option>
                                    <option value="1">1</option>
                                 </select>
                                </p>
                                <div class="opt-holder"></div>
                            </div>
                            	
                        <?php		
							}
							
						?>    
                        <div class="new-mopts"></div>
                        <br />               
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="right"><input type="button" class="btn edit-menu-btn" value="Utför" data-rel="<?php echo $_GET['id']; ?>" data-id="<?php echo $_GET['nav']; ?>"><input type="hidden" class="fromall-holder" value="<? echo ($_GET['fromall'] == '' ? 'false' : 'true');?>" /></td>
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
