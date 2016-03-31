<?php
	include 'config.php';
	
	$uniqueid=$_POST['unique_id'];
	$theurl = $_POST['theurl'];
	$del_id = $_POST['del_id'];
	$remove_id = $_POST['remove_id'];
	$id = $_POST['id'];
	$quan = $_POST['quan'];
	$mid = $_POST['mid'];
	$active = $_POST['active'];
	$dropdown = $_POST['dropdown'];
	$thecatid = $_POST['thecatid'];
	$packid = $_POST['packid'];
	$menid = $_POST['menid'];
	$thedesc = $_POST['thedesc'];
	
	
	$q=mysql_query("select id from catering_detail where uniqueid='".$uniqueid."'") or die(mysql_error());
	$package_count = mysql_num_rows($q);
	
	if(isset($packid)){
		mysql_query("update catering_order set notes='".$thedesc."' where id='".$packid."'");
	}
	
	if(isset($menid)){
		mysql_query("update catering_order_detail set notes='".$thedesc."' where id='".$menid."'");
	}
	
	if(isset($del_id)){
		mysql_query("delete from catering_order where id='".$del_id."'");
		mysql_query("delete from catering_order_detail where catering_order_id='".$del_id."'");
	}
	
	if(isset($remove_id)){
		mysql_query("delete from catering_order_detail where id='".$remove_id."'");
		
		$qw=mysql_query("select id from catering_order_detail where catering_order_id='".$thecatid."'") or die(mysql_error());
		$count=mysql_num_rows($qw);
		
		if($count>0){
			if($count==1){
				$rw=mysql_fetch_assoc($qw);
				mysql_query("update catering_order_detail set quantity='".$quan."' where id='".$rw['id']."'") or die(mysql_error());
			}
			else{
				
				$thediv = (int)($quan/$count);
				$themod = ($quan%$count);
				
				if($themod==0){
						
					while($rw=mysql_fetch_assoc($qw)){
						mysql_query("update catering_order_detail set quantity='".$thediv."' where id='".$rw['id']."'") or die(mysql_error());	
					}
						
					
				}
				else{
						
					while($rw=mysql_fetch_assoc($qw)){
						
						$hold=0;
					
						if($themod>0){
							$hold=1;
						}
						
						mysql_query("update catering_order_detail set quantity='".($thediv+$hold)."' where id='".$rw['id']."'") or die(mysql_error());	
					
						$themod=$themod-1;
					
					}
					
				}
				
			}
		}
		
	}
	
	if(isset($id)){
		mysql_query("update catering_order set quantity='".$quan."' where id='".$id."'");
		
		$qw=mysql_query("select id from catering_order_detail where catering_order_id='".$id."'") or die(mysql_error());
		$count=mysql_num_rows($qw);
		
		if($count>0){
			if($count==1){
				$rw=mysql_fetch_assoc($qw);
				mysql_query("update catering_order_detail set quantity='".$quan."' where id='".$rw['id']."'") or die(mysql_error());
			}
			else{
				
				$thediv = (int)($quan/$count);
				$themod = ($quan%$count);
				
				if($themod==0){
						
					while($rw=mysql_fetch_assoc($qw)){
						mysql_query("update catering_order_detail set quantity='".$thediv."' where id='".$rw['id']."'") or die(mysql_error());	
					}
						
					
				}
				else{
						
					while($rw=mysql_fetch_assoc($qw)){
						
						$hold=0;
					
						if($themod>0){
							$hold=1;
						}
						
						mysql_query("update catering_order_detail set quantity='".($thediv+$hold)."' where id='".$rw['id']."'") or die(mysql_error());	
					
						$themod=$themod-1;
					
					}
					
				}
				
			}
		}
		
	}
	
	if(isset($mid)){
		mysql_query("update catering_order_detail set quantity='".$quan."' where id='".$mid."'");
	}
	
	
	function getCurrentCurrency(){
		//get currency
		$qc=mysql_query("select shortname from currency where deleted=0 and set_default=1");
		$rc=mysql_fetch_assoc($qc);
		return strtolower($rc['shortname']);
	}
	
	function getSubtotal($pid){
		$q=mysql_query("select sum(quantity*price) as subtotal from catering_order_detail where catering_order_id='".$pid."'");
		
		$r=mysql_fetch_assoc($q);
		
		if($r['subtotal']!='')
			return $r['subtotal'];
		else	
			return '-';
	}
	
	
	$qs=mysql_query("select var_value from settings where var_name='week_starts'");
	$row=mysql_fetch_assoc($qs);
	
	$currentDates = date("m/d/Y");
	$dayName=date('D', strtotime($currentDates));
	
	
	$qr=mysql_query("select id from catering_settings where DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d') >= DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') and DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d') <= DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') and days like '%".$dayName."%' and deleted=0 order by id desc limit 1") or die(mysql_error());
	
	//$qr=mysql_query("select id from catering_settings where '".$currentDates."' >= start_date and '".$currentDates."' <= end_date and days like '%".$dayName."%' and deleted=0 order by id desc limit 1") or die(mysql_error());
	$rs=mysql_fetch_assoc($qr);
	$currentID=$rs['id'];
	
	
	//get the available days...
	$thedays='';
	$qr=mysql_query("select days, minimum_number_days, end_date from catering_settings where id='".$currentID."'") or die(mysql_error());
	$rq=mysql_fetch_assoc($qr);
	
	$minimum_days = $rq['minimum_number_days'];
	$theend_date = $rq['end_date'];
	
	$days=explode(',',$rq['days']);	
	
	$daynum=array('Mon'=>1,'Tue'=>2,'Wed'=>3,'Thu'=>4,'Fri'=>5,'Sat'=>6,'Sun'=>0);
	
	for($i=0;$i<count($days);$i++){
		$thedays.="'".$daynum[trim($days[$i])]."',";
	}
	
	$thedays=substr($thedays,0,strlen($thedays)-1);
	
	$future_dates='';
	$curDate = $currentDates;
	for($i=0;$i<$minimum_days;$i++){
		
		$future_dates.="'".$curDate."',";
		$curDate = date("m/d/Y",strtotime($curDate . "+1 days"));
	}
	
	$future_dates=substr($future_dates,0,strlen($future_dates)-1);
	
?>
<div class="cart-inner">
	<a href="javascript:void(0)" class="close-cart">Close</a>
    <div class="clear"></div>
	<?php if($package_count > 0){ ?>
	<div class="forcatering">
    	<h2>Catering</h2>
        
        <?php
        	$r=mysql_fetch_assoc($q);
			$catdetail_id = $r['id'];
			
			$total=0;
			$currency = getCurrentCurrency();
		?>
        
        <ul>
        	<li style="background:#580605; color:white;">
            	<table>
                	<tr>
                    	<td width="30%">Package Name</td>
                        <td width="30%">Description</td>
                        <td width="15%" style="text-align:center;">Quantity</td>
                        <td width="10%" style="text-align:center;">Price</td>
                        <td width="10%" style="text-align:center;">Subtotal</td>
                        <td></td>
                    </tr>
                </table>
            </li>
            <?php
            	$qq=mysql_query("select cc.name as name, co.quantity as quan, co.price as price, co.id as cid, co.catering_category_id as catid, cc.number_selected as numselected, cc.minimum_order as minorder, cc.has_notes as hasnotes, co.notes as notes from catering_category as cc, catering_order as co where co.catering_detail_id='".$catdetail_id."' and cc.id=co.catering_category_id and cc.deleted=0 order by cid desc") or die(mysql_error());
				
				$count_total_items=(mysql_num_rows($qq));
				
				$count=0;
				while($rr=mysql_fetch_assoc($qq)){
					$count++;
					
					//added
					$okay='not';
					
					$qt=mysql_query("select id from catering_order_detail where catering_order_id='".$rr['cid']."'");
					if(mysql_num_rows($qt) > 0){
						$okay='okay';
					}
					else{
						
						$qqq=mysql_query("select sum(price) as sum from catering_category_price where catering_category_id='".$rr['catid']."' and deleted=0");
						$rrr=mysql_fetch_assoc($qqq);
						
						if($rrr['sum']>0){
							$okay='okay';
						}
						
					}
					
					
			?>
            <li class="packages packages<?php echo $rr['cid'];?> <?php echo $okay.' '; if($count%2==0) echo 'teven'; else echo 'todd'; ?>" data-id="<?php echo $rr['cid'];?>">
            	<table>
                	<tr>
                    	<td width="30%"><?php echo $rr['name']; ?></td>
                        <td width="30%">
                        	<?php
                            	if($rr['hasnotes']==1){
							?>
                            <textarea class="packdesc packdesc<?php echo $rr['cid']; ?>" style="width:200" data-rel="<?php echo $rr['cid']; ?>"><?php echo $rr['notes']; ?></textarea>
                            <?php		
								}
							?>
                        </td>
                        <td width="15%" style="text-align:center;">
							<?php if($rr['price'] > 0){
							?>
                            	<input type="button" value="-" data-rel="<?php echo $rr['price'];?>" data-id="<?php echo $rr['cid'];?>" class="psubtract">
                            	<input type="text" value="<?php echo $rr['quan']; ?>" style="width:67px !important;" class="p-<?php echo $rr['cid'];?> packs" data-rel="<?php echo $rr['minorder']; ?>" data-id="<?php echo $rr['cid'];?>">
                                <input type="button" value="+" data-rel="<?php echo $rr['price'];?>" data-id="<?php echo $rr['cid'];?>" class="padd">
                            <?php	
									
								  }else echo '-'; ?>
                        </td>
                        <td width="10%" style="text-align:center;"><?php if($rr['price'] > 0) echo $rr['price'].' '.$currency; else echo '-'; ?></td>
                        <td width="10%" style="text-align:center;">
							<?php 
								$subtotal=$rr['quan']*$rr['price']; 
								if($rr['price'] > 0){
									echo number_format($subtotal,2).' '.$currency;
								}
								else{
									echo number_format(getSubtotal($rr['cid']),2).' '.$currency;
								}
								$total+=$subtotal;
							?>
                        </td>
                        <td>
                        	<a href="javascript:void(0)" class="remove-cart" data-rel="<?php echo $rr['cid'];?>"></a>
                        </td>
                    </tr>
                </table>
                
                <?php
                	$qqq=mysql_query("select cm.name as name, cod.quantity as quan, cod.price as price, cod.id as coid, cm.catering_subcategory_id as catsubid, cod.notes as cnotes from catering_menu as cm, catering_order_detail as cod where cm.id=cod.catering_menu_id and cod.catering_order_id='".$rr['cid']."'") or die(mysql_error());
					$countmenus = mysql_num_rows($qqq);
					if($countmenus > 0){ 
				?>
                	<ul id="drp<?php echo $rr['cid'];?>" class="subcart" <?php if($rr['price']<=0) echo 'style="display:block;"';?>>
                    	<li style="background:none !important;">
                        	<table>
                                <tr>
                                    <td width="32%">Menu Name</td>
                                    <td width="32%">Description</td>
                                    <td width="15%" style="text-align:center;"></td>
                                    <td width="10%" style="text-align:center;"></td>
                                    <td width="10%" style="text-align:center;"></td>
                                    <td style="text-align:center;"></td>
                                </tr>
                            </table>
                        </li>
                <?php
						$cnt=0;
                		while($rrr=mysql_fetch_assoc($qqq)){
							$cnt++;
				?>
                	<li class="package-menus <?php if($cnt%2==0) echo 'seven'; else echo 'sodd';?>" data-id="0">
                    	<table>
                            <tr>
                                <td width="32%"><?php echo $rrr['name']; ?></td>
                                <td width="32%">
                                <?php if($rr['hasnotes']==1){ ?>
                                	<textarea class="mendesc mendesc<?php echo $rrr['coid']; ?>" style="width:200" data-rel="<?php echo $rrr['coid']; ?>"><?php echo $rrr['cnotes']; ?></textarea>
                                <?php } ?>
                                </td>	
                                <td width="15%" style="text-align:center;">
								
								<?php 
								
									$qrys = mysql_query("select number_selected from catering_category where id='".$rr['catid']."'");
									$rss =  mysql_fetch_assoc($qrys);
								
									$rws=$rss['number_selected'];
									
									if($rws==0){
										$qry = mysql_query("select number_selected from catering_subcategory where id='".$rrr['catsubid']."'");
										$rsw =  mysql_fetch_assoc($qry);
										
										$rws = $rsw['number_selected']; 
									}
								
									
								?>
                                <?php if(($rws>1 || $rws=='') & $countmenus>1){ ?>
                            	<input type="button" value="-" data-rel="<?php echo $rrr['price'];?>" data-id="<?php echo $rrr['coid'];?>" data-title="<?php echo $rr['cid'];?>" class="msubtract">
                                <?php } ?>
                                
                            	<input type="text" value="<?php echo $rrr['quan']; ?>" style="width:67px !important;" class="m-<?php echo $rrr['coid'];?> macks macks<?php echo $rr['cid'];?>" data-rel="<?php echo $rws['number_selected'];?>" <?php if(($rws==0 || $rws!='') & $countmenus==1){ echo 'readonly="readonly"'; }?> >
                                
                                 <?php if(($rws>1 || $rws=='') & $countmenus>1){ ?>
                                <input type="button" value="+" data-rel="<?php echo $rrr['price'];?>" data-id="<?php echo $rrr['coid'];?>" data-title="<?php echo $rr['cid'];?>" class="madd">
                                 <?php } ?>
                            
                                </td>
                                <td width="10%" style="text-align:center;"><?php if($rr['price']>0) echo '-'; else  echo number_format($rrr['price'],2).' '.$currency; ?></td>
                                <td width="10%" style="text-align:center;"><?php if($rr['price']>0) echo '-'; else{ $subt=$rrr['quan']*$rrr['price']; echo number_format($subt,2).' '.$currency; $total+=$subt; } ?></td>
                                <td>
                                <?php
									if($rws>1 || $rws==''){ 
								?>
                                	<a href="javascript:void(0)" class="remove-menu-cart" data-rel="<?php echo $rrr['coid'];?>" data-title="<?php echo $rr['cid'];?>"></a>
								<?php } ?>
                                </td>
                            </tr>
                		</table>
                    </li>
                <?php			
						}
				?>    	
                    </ul>
                <?php		
					}
					
				?>
                
            </li>
            <?php		
				
				}
				
			?>
        </ul>
        <div class="cart-total">
        	<ul>
        		<li>Total : <span><?php echo '<label>'.number_format($total,2).'</label> '.$currency; ?></span></li>
            	<li><a href="#text-22" class="caterproceed" data-title="<?php echo ucfirst($currency); ?>" data-rel="<?php echo number_format($total,2); ?>" data-id="<?php echo $total; ?>">Continue</a></li>
            </ul>
        </div>
        
    </div>
    <?php } ?>
</div>

<div class="dropdown"><?php echo $dropdown; ?></div>


<script type="text/javascript">

	
 function calcTime(offset) {

		// create Date object for current location
		d = new Date();
		
		// convert to msec
		// add local time zone offset 
		// get UTC time in msec
		utc = d.getTime() + (d.getTimezoneOffset() * 60000);
		
		// create new Date object for different city
		// using supplied offset
		nd = new Date(utc + (3600000*offset));
		
		// return time as a string
		return nd.toLocaleString();
	}

	$(function(){
		
		$('.menucart span').html('<?php echo $count_total_items; ?>');
		
		var checker = Number($('.cart-total label').html());
		if(checker<=0){
			$('.fade, .cart-detail, .menucart').fadeOut();
			//jQuery.removeCookie('uniqueid', { path: '/' });
			window.location.reload();
		}
		
		$('.not').remove();
		
		$.fn.center = function ()
			{
				this.css("position","fixed");
				this.css("top", (($(window).height() / 2) - (this.outerHeight() / 2))+50);
				return this;
		}
		
		$('.packs, .macks').numeric();
		
		$('.close-cart').click(function(){
			$('.fade, .cart-detail').fadeOut();
		});
		
		//for package
		$('.padd').click(function(){
			//var price = Number($(this).attr('data-rel'));
			var theclass = $(this).attr('data-id');
			var quan = Number($('.p-'+theclass).val());
			quan = quan+1;
			$('.p-'+theclass).val(quan);
			
			$('.cart-detail').html('<div class="theloading">Loading...</div>');
			$.ajax({
					url: "<?php echo $theurl; ?>/cart.php",
					type: 'POST',
					async: false,
					data: 'unique_id='+encodeURIComponent('<?php echo $uniqueid; ?>')+'&id='+encodeURIComponent(theclass)+'&theurl='+encodeURIComponent('<?php echo $theurl; ?>')+'&quan='+encodeURIComponent(quan),
					success: function(value){
						
						$('.cart-detail').html(value).center();
						
					}
			});	
			
		});
		
		$('.psubtract').click(function(){
			//var price = Number($(this).attr('data-rel'));
			var theclass = $(this).attr('data-id');
			var quan = Number($('.p-'+theclass).val());
			var minorder = Number($('.p-'+theclass).attr('data-rel'));
			
			if(quan>minorder){
				
				quan = quan-1;
			
				if(quan<=1){
					quan=1;
				}
				
				$('.p-'+theclass).val(quan);
				
				$('.cart-detail').html('<div class="theloading">Loading...</div>');
				
				$.ajax({
						url: "<?php echo $theurl; ?>/cart.php",
						type: 'POST',
						async: false,
						data: 'unique_id='+encodeURIComponent('<?php echo $uniqueid; ?>')+'&id='+encodeURIComponent(theclass)+'&theurl='+encodeURIComponent('<?php echo $theurl; ?>')+'&quan='+encodeURIComponent(quan),
						success: function(value){
						
							$('.cart-detail').html(value).center();
							
						}
				});	
				
			}
			else{
				alert('Minimum order for this package is '+minorder);
			}
			
			
		});
		
		$('.packs').focusout(function(){
			var val = Number($(this).val());
			var minorder = Number($(this).attr('data-rel'));
			
			if(val<minorder){
				$(this).val(minorder);
				alert('Minimum order for this package is '+minorder);
			}
			
		}); 
		
		
		//for menu
		$('.madd').click(function(){
			//var price = Number($(this).attr('data-rel'));
			var theclass = $(this).attr('data-id');
			var theid = $(this).attr('data-title');
			var quan = Number($('.m-'+theclass).val());
			
			var pack = $('.p-'+theid).val();
			var total=0;
			
			$('.macks'+theid).each(function(){
				var val = Number($(this).val());
				total+=val;
			});
			
			if(total<pack){
			
				quan = quan+1;
				$('.m-'+theclass).val(quan);
				
				$('.cart-detail').html('<div class="theloading">Loading...</div>');
				$.ajax({
						url: "<?php echo $theurl; ?>/cart.php",
						type: 'POST',
						async: false,
						data: 'unique_id='+encodeURIComponent('<?php echo $uniqueid; ?>')+'&mid='+encodeURIComponent(theclass)+'&theurl='+encodeURIComponent('<?php echo $theurl; ?>')+'&quan='+encodeURIComponent(quan)+'&thecatid='+encodeURIComponent(theid),
						success: function(value){
						
							$('.cart-detail').html(value).center();
							
						}
				});	
			
			}
			else{
				alert('Maximum total number of menu is '+pack);
			}
			
		});
		
		$('.msubtract').click(function(){
			//var price = Number($(this).attr('data-rel'));
			var theclass = $(this).attr('data-id');
			var theid = $(this).attr('data-title');
			var quan = Number($('.m-'+theclass).val());
			quan = quan-1;
			
			if(quan<=1){
				quan=1;
			}
			
			$('.m-'+theclass).val(quan);
			
			$('.cart-detail').html('<div class="theloading">Loading...</div>');
			$.ajax({
					url: "<?php echo $theurl; ?>/cart.php",
					type: 'POST',
					async: false,
					data: 'unique_id='+encodeURIComponent('<?php echo $uniqueid; ?>')+'&mid='+encodeURIComponent(theclass)+'&theurl='+encodeURIComponent('<?php echo $theurl; ?>')+'&quan='+encodeURIComponent(quan)+'&thecatid='+encodeURIComponent(theid),
					success: function(value){
					
						$('.cart-detail').html(value).center();
						
					}
			});	
			
		});
		
		$('.packdesc').focusout(function(){
			var id = $(this).attr('data-rel');
			var val = $(this).val();
			
			$.ajax({
					url: "<?php echo $theurl; ?>/cart.php",
					type: 'POST',
					async: false,
					data: 'unique_id='+encodeURIComponent('<?php echo $uniqueid; ?>')+'&packid='+encodeURIComponent(id)+'&theurl='+encodeURIComponent('<?php echo $theurl; ?>')+'&thedesc='+encodeURIComponent(val),
					success: function(value){
					
						$('.cart-detail').html(value).center();
						
					}
			});	
			
		});
		
		$('.mendesc').focusout(function(){
			var id = $(this).attr('data-rel');
			var val = $(this).val();
			
			$.ajax({
					url: "<?php echo $theurl; ?>/cart.php",
					type: 'POST',
					async: false,
					data: 'unique_id='+encodeURIComponent('<?php echo $uniqueid; ?>')+'&menid='+encodeURIComponent(id)+'&theurl='+encodeURIComponent('<?php echo $theurl; ?>')+'&thedesc='+encodeURIComponent(val),
					success: function(value){
					
						$('.cart-detail').html(value).center();
						
					}
			});	
			
		});
		
		$('.remove-cart').click(function(){
			var id = $(this).attr('data-rel');
			
			
			$('.cart-detail').html('<div class="theloading">Loading...</div>');
			$.ajax({
					url: "<?php echo $theurl; ?>/cart.php",
					type: 'POST',
					async: false,
					data: 'unique_id='+encodeURIComponent('<?php echo $uniqueid; ?>')+'&del_id='+encodeURIComponent(id)+'&theurl='+encodeURIComponent('<?php echo $theurl; ?>'),
					success: function(value){
					
						$('.cart-detail').html(value).center();
						
					}
			});	
			
		});
		
		$('.remove-menu-cart').click(function(){
			var id = $(this).attr('data-rel');
			var catid = $(this).attr('data-title');
			var quan = $('.p-'+catid).val();
			
			$('.cart-detail').html('<div class="theloading">Loading...</div>');
			$.ajax({
					url: "<?php echo $theurl; ?>/cart.php",
					type: 'POST',
					async: false,
					data: 'unique_id='+encodeURIComponent('<?php echo $uniqueid; ?>')+'&remove_id='+encodeURIComponent(id)+'&theurl='+encodeURIComponent('<?php echo $theurl; ?>')+'&thecatid='+encodeURIComponent(catid)+'&quan='+encodeURIComponent(quan),
					success: function(value){
					
						$('.cart-detail').html(value).center();
						
					}
			});	
			
		});
		
		$('.caterproceed').click(function(){
			
			$('.packs').each(function(){
				var catid = $(this).attr('data-id');
				var val = Number($(this).val());
				
				var check = $('#drp'+catid+' .macks').length;
				if(check>0){
					var total=0;
					$('#drp'+catid+' .macks').each(function(){
						var str = Number($(this).val());
						total+=str;
					});
				}
				else{
					total=val;
				}
				
				if(val!=total){
					$('.packages'+catid).addClass('tored');
				}
				
			});
			
			var err = $('.tored').length;
			
			if(err>0){
				alert('Total number of menus should correspond to the number of quantity of a package.');
			}
			else{
		

				$('.cateremail').val(jQuery.cookie('limone_email'));
				$('.caterpass').val(jQuery.cookie('limone_pass'));

				$('.cateringwindow_1, .fade, .cart-detail').fadeOut();
				$('.cateringwindow_2').css('display','block');
				
				$('.catercurrency').html($(this).attr('data-title'));
				$('.catertotal').html($(this).attr('data-rel')).attr('data-rel',$(this).attr('data-id'));
			
			}
		
		});
		
		
		/*var d = new Date();
		var hour = d.getHours();     
		var mins = d.getMinutes();*/
		
		var da = calcTime('+2').split(' ');
		var d = da[1].split(':');
		
		var hour = Number(d[0]);
		var mins = Number(d[1]);
		
		var thedays = [<?php echo $thedays; ?>]; 
		var futuredates = [<?php echo $future_dates; ?>]; 
		
	    $( "#caterdate" ).datetimepicker({
			/*minDate: 0,*/
			minDate: new Date('<?php echo $currentDates; ?>'),
			maxDate : '<?php echo (strtotime($theend_date) - strtotime($currentDates)) / (60 * 60 * 24); ?>',
			firstDay: <?php echo $row['var_value']; ?>,
			addSliderAccess: true,
			sliderAccessArgs: { touchonly: false },
			hour: hour+1,
			stepMinute: 15,
			minute: mins,
			beforeShowDay: function(date){
					var string = $.datepicker.formatDate('mm/dd/yy', date);
				
					return [checktheDays(date,thedays,futuredates,string)];
				
					
			}
			
		});
		
		
		$('.caterdatelabel').click(function(){
			$('#caterdate').datepicker('show');
		});
		
		$('.prevcater').click(function(){
			$('.cateringwindow_1, .fade, .cart-detail').fadeIn();
			$('.cateringwindow_2').fadeOut();
		});
		
		$('.caterterms').click(function(){
		 
			 $('.fade3, .agreement-cater-box').fadeIn().center();
	
		});
		
		$('.caternew').click(function(){
			 $('.fade, .signup-cater-box').fadeIn().center();
		});
		
		
	});
	
	 function checktheDays(date,thedays,futuredate,string){
		 
		 if(futuredate.indexOf(string) != -1){
			return false; 
		 }
		 else{
	 
			 var val=thedays.indexOf(date.getDay().toString());
			 
			 if(val!=-1)
				 return true;	 
			 else
				return false;
			
		 }
	
	 }
	
</script>