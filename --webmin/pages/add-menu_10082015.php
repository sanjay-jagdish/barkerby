<?php include_once('redirect.php'); ?>
<script type="text/javascript" >
	
 function removeImg(){
	jQuery('#photoimg').val('');
	jQuery('#preview').html('No image available.');
 }	


 jQuery(document).ready(function() { 
		var clck = 0;
            jQuery('#photoimg').on('change', function(){
				 
				jQuery("#preview").html('');
				jQuery("#preview").html('<img src="scripts/ajaximage/loader.gif" alt="Uploading...."/>');
				jQuery("#imageform").ajaxForm({
					target: '#preview',
					success: function(val){
						var v=val.indexOf("img");
						
						if(v==1){
							jQuery('#preview').append('<a href="javascript:void(0)" class="aremoveimg aorange" onclick="removeImg()">Ta bort bild</a>');
						}
						
					}
				}).submit();
		
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
						pr = formatNumber(pr)	
					}
					field = '<p><input type="text" value="'+name+'" class="txt" id="optname-'+clck+'" name="opt-name" />&nbsp;<input style="width:50px; text-align:right;" name="opt-price" type="text" class="txt" id="optpr-'+clck+'" value="'+pr+'" />&nbsp;<a href="javascript:void(0)" onclick="remove_opt('+clck+')"><img src="images/delete-small.png" style="position: relative; top: 8px;"></a></p>';
					
					$('.opt-holder').prepend('<div class="opt-item" id="'+clck+'">'+field+'</div>');
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
			// Ensure that it is a number and stop the keypress
			if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				e.preventDefault();
			}
		});
  }); 
  function remove_opt(id){
	  $('.opt-item#'+id).slideUp();
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
		margin: 10px 0 0;
		display: inline-block;
	}
	
	#preview{
		color:#555 !important;
	}
</style>
<div class="page add-menu-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
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
    	<div class="page-content-left">
            <table>
                <tr>
                    <td width="80px">Kategori :</td>
                    <td>
                        <select class="menu-category txt">
                        <?php
                            $q=mysql_query("select id, name from category where deleted=0 order by name");
							
                            while($r=mysql_fetch_assoc($q)){ ?>         
                            	<option value="<?php echo $r['id'];?>*maincat"><?php echo $r['name'];?></option>                         	
								<?php	
                                $q1=mysql_query("select id,name from sub_category where deleted = 0 and category_id = '" . $r['id'] ."' order by name");
                                
                                while($r1=mysql_fetch_assoc($q1)){ ?>
								<option value="<?php echo $r1['id'];?>*subcat"><?php echo $r['name'] . " - " . $r1['name'];?></option>
							<?php
								}
                            }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><font style="color:#e67e22;">*</font>Rätt :</td>
                    <td><input type="text" class="menu-name txt"></td>
                </tr>
                <tr>
                    <td><font style="color:#e67e22;">*</font>Beskrivning :</td>
                    <td><textarea class="menu-desc txt"></textarea></td>
                </tr>
                <tr>
                    <td>Valuta :</td>
                    <td>
                    	<!--<select class="menu-currency txt">
                    	<?php
                        	//$q=mysql_query("select name,shortname,id, set_default from currency where deleted=0");
							//while($r=mysql_fetch_array($q)){
						?>
                        <option value="<?php //echo $r[2]; ?>" <?php //if($r[3]==1) echo 'selected="selected"';?>><?php //echo ucwords($r[0])." - ".strtoupper($r[1]); ?></option>
                        <?php		
							//}
						?>
                        </select>-->
                        <?php
                        	$q=mysql_query("select name,shortname,id from currency where set_default=1");
							$r=mysql_fetch_assoc($q);
						?>
                        <input type="text" class="menu-currency txt" readonly="readonly" value="<?php echo $r['name'].' - '.strtolower($r['shortname']);?>" data-rel="<?php echo $r['id'];?>">
                    </td>
                </tr>
                <tr>
                    <td><font style="color:#e67e22;">*</font>Pris :</td>
                    <td><input type="text" class="menu-price txt"></td>
                </tr>
                <tr>
                    <td>Visa :</td>
                    <td><input type="checkbox" class="menu-featured"></td>
                </tr>
                <tr>
                    <td><font style="color:#e67e22;">*</font>Typ :</td>
                    <td>
                    	<input type="checkbox" value="1" id="dinetype" class="menutype"> <label for="dinetype">Á la carte</label><br>
                        <input type="checkbox" value="2" id="taketype" class="menutype"> <label for="taketype">Take Away</label>
                    </td>
                </tr>
                <tr>
                    <td>Take away rabatt :</td>
                     <td><input type="text" class="menu-discount txt" style="width:190px !important;" value="">
                     <select class="menu_discount_unit">
                     	<option value="fix">kr</option>
                        <option value="percent">%</option>
                     </select></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <form id="imageform" method="post" enctype="multipart/form-data" action='scripts/ajaximage/ajaximage.php'>
                            Ladda upp bild &nbsp; <input type="file" name="photoimg" id="photoimg" class="txt" />
                        </form>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><br /><br />
                        <p><strong>Alternativ</strong></p><br />
                         <div class="opt-msg" style="padding:10px 20px; display:none;"></div>
                        <p><input type="text" class="txt" name="opt-name" placeholder="Alternativ namn" id="opt-name" /> <input style="width:50px; text-align:right" class="txt" name="opt-price" type="text" id="opt-price" placeholder="0.00" />  <a href="#" class="add-opt"><img src="images/add-small.png" style="position: relative; top: 8px;"></a></p>
                        <br />
                        <div class="opt-holder"></div>   <br />                  
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="right"><input type="button" class="btn add-menu-btn" value="Utför" data-id="<?php echo $_GET['nav']; ?>"></td>
                </tr>
            </table>
        </div>
        <div class="page-content-right">
        	<div id='preview'></div>
        </div>
            
        <div class="clear"></div>
        <div class="displaymsg"></div>
    </div>
    
</div>
<!-- end .page -->