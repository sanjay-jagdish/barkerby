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
			// Ensure that it is a number and stop the keypress
			if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				e.preventDefault();
			}
		});
		
		//adding new mopts
		$('.add-mopt').click(function(){
			var count = Number($('.mopts').last().find('.add-opt').attr('data-id'))+1;
			$('.new-mopts').append('<div class="mopts mopts'+count+'"><p><strong>Tillval</strong><a href="javascript:void(0)" class="remove-mopt" onclick="remove_mopt(\''+count+'\')"><img src="images/delete-small.png" style="position: relative; top: 8px; margin: 0 0 0 10px;"></a></p><br><input type="text" class="txt tillvalname" placeholder="Titel"><br><br /><select class="takeaway-tillval"><option value="0">Valfri</option><option value="1">Tvingande</option></select><br><br><div class="opt-msg" style="padding:10px 20px; display:none;"></div><p class="theopt theopt1" data-id="1"><input type="text" class="txt optname" name="opt-name" placeholder="Tillval" id="opt-name1" /> <input style="width:50px; text-align:right" class="txt optprice" name="opt-price" type="text" id="opt-price1" placeholder="0.00" />  <a href="javascript:void(0)" class="add-opt" data-id="'+count+'" onclick="add_opt(\''+count+'\')"><img src="images/add-small.png" style="position: relative; top: 8px;"></a></p><div class="opt-holder"></div></div>');
		});
		
  }); 
  
  function add_opt(theid){
	 
	  var count = Number($('.mopts'+theid+' .theopt').last().attr('data-id'))+1;
	  var removeid = theid+','+count;
	  
	  $('.mopts'+theid+' .opt-holder').append('<p class="theopt theopt'+count+'" data-id="'+count+'"><input type="text" class="txt optname" name="opt-name" placeholder="Tillval" id="opt-name'+count+'" /> <input style="width:50px; text-align:right" class="txt optprice" name="opt-price" type="text" id="opt-price1" placeholder="0.00" />  <a href="javascript:void(0)" class="add-opt" data-id="'+count+'" onclick="remove_opt(\''+removeid+'\')"><img src="images/delete-small.png" style="position: relative; top: 8px;"></a></p>');
	    
  } 
  
  function remove_mopt(id){
	  $('.mopts'+id).remove();
  }
  
  function remove_opt(id){
	  var id = id.split(',');
	  $('.mopts'+id[0]+' .theopt'+id[1]).remove();
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
                    <td>Take away pris</td>
                     <td>
                     	<input type="text" class="takeaway-price txt">
                     </td>
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
                     	<div class="mopts mopts1">
                            <p><strong>Tillval</strong><a href="javascript:void(0)" class="add-mopt"><img src="images/add-small.png" style="position: relative; top: 8px; margin: 0 0 0 10px;"></a></p><br><input type="text" class="txt tillvalname" placeholder="Titel"><br><br />
                            <select class="takeaway-tillval">
                                <option value="0">Valfri</option>
                                <option value="1">Tvingande</option>
                            </select><br><br>
                             <div class="opt-msg" style="padding:10px 20px; display:none;"></div>
                            <p class="theopt1 theopt" data-id="1"><input type="text" class="txt optname" name="opt-name" placeholder="Tillval" id="opt-name1" /> <input style="width:50px; text-align:right" class="txt optprice" name="opt-price" type="text" id="opt-price1" placeholder="0.00" />  <a href="javascript:void(0)" class="add-opt" data-id="1"><img src="images/add-small.png" style="position: relative; top: 8px;"></a></p>
                            <div class="opt-holder"></div>
                        </div>
                        <div class="new-mopts"></div>
                        <br />                  
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
