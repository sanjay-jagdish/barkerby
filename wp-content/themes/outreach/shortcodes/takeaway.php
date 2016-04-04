<?php

function takeAway(){
$menys='';
$query=mysql_query("select * from category where deleted=0 order by IF( `order` = '', 1, 0), LENGTH(`order`),`order`, id");

if(mysql_num_rows($query) > 0){
	while($r=mysql_fetch_assoc($query)){
		    
 			//Start select menus direct from main category
			$qrr=mysql_query("select name,description,id,price,currency_id,discount,discount_unit,type,takeaway_price from menu where cat_id=".$r['id']." and type<>'1' and deleted=0 and featured=1 order by IF( `order` = '', 1, 0), LENGTH(`order`),`order`, id") or die(mysql_error());
			
			$countcatmenu = mysql_num_rows($qrr);
			
			$countsubcatmenu=0;
			
			$qqst=mysql_query("select * from sub_category where category_id=".$r['id']." order by IF( `order` = '', 1, 0), LENGTH(`order`),`order`, id") or die(mysql_error());	
		
			while($rrst=mysql_fetch_assoc($qqst)){
			
				$qrst=mysql_query("select name,description,id,price,currency_id,discount,discount_unit,type from menu where sub_category_id=".$rrst['id']." and type<>'1' and deleted=0 and featured=1 order by IF( `order` = '', 1, 0), LENGTH(`order`),`order`, id") or die(mysql_error());
				
				if(mysql_num_rows($qrst)>0){
					$countsubcatmenu=1;
				}
				
			}
			
            
			if($countcatmenu!=0 || $countsubcatmenu!=0){  //checker if it has dishes
				
				$menys.='<div class="meny_division column_'.$r['id'].'"> 
					<h4 style="margin-bottom: 5px !important; font-size: 25px;">'.$r['name'].'</h4>
					<span style="font-size: 16px; padding:0 0 32px; color:black; display:block;">'.$r['description'].'</span>';
			
			
			if($countcatmenu > 0 ){

				while($rrr=mysql_fetch_assoc($qrr)){
					
						//for the mandatory tillvals
						$qtill = mysql_query("select id from menu_option_details where menu_id='".$rrr['id']."' and single_option=1") or die(mysql_error());
						$count_tillvals = mysql_num_rows($qtill);
						//end tillvals
					
						$price=$rrr['price'];
						if(strlen($rrr['type'])>1){
							/*if($rrr['discount_unit']=="percent"){
								$discount=$rrr['discount']/100;
								$price=$price-($price*$discount);
							}else{
								$price=$price-$rrr['discount'];
							}*/
							
							if($rrr['takeaway_price']=='0.00'){
								$price=$rrr['price'];
							}else{
								$price=$rrr['takeaway_price'];
							}
						}
					$menu_price = number_format($price).' '.getCurrencyShortname($rrr['currency_id']);
					
					$menys.='<div class="section-devider">';
						
						$menys.='<div class="left-side">
						<h4 class="menu-name">'.$rrr['name'].'</h4>
						<strong class="web-price" style="font-size: 20px; margin-left: 12px;">'. $menu_price .'</strong>
						<p style="width: 80% !important;">'.$rrr['description'].'  <span style="display:none" class="subtotal-'.$rrr['id'].'" data-rel="'.$price.'">0</span></p> </div>';		

						$menys .= '  <span class="outerinputs">
						<strong class="mobile-price">'.$menu_price.'</strong>
						<img src="'.CHILD_URL.'/images/Lagg-till.png" class="takeimg tacart" data-id="'.$rrr['id'].'" data-rel="'.$rrr['name'].'<span>'.$rrr['description'].'</span>'.'" data-tillval="'.$count_tillvals.'">
						<span id="inputs">						
						<input type="button" value="+" class="addme_takeaway" data-rel="'.$rrr['id'].'">
						<input type="text" class="quantity-takeaway quantity-'.$rrr['id'].'" data-rel="'.$rrr['id'].'" placeHolder="0" data-title="'.number_format($price).'" />
						<input type="button" value="-" class="subtractme_takeaway" data-rel="'.$rrr['id'].'">
						</span>
						
						</span>';
									
				   $menys.='</div>';
						
				}
			}
		//End select menus direct from main category
		
		$qq=mysql_query("select * from sub_category where category_id=".$r['id']." order by IF( `order` = '', 1, 0), LENGTH(`order`),`order`, id") or die(mysql_error());
		
		if(mysql_num_rows($qq)){
			
			while($rr=mysql_fetch_assoc($qq)){
			
				$qr=mysql_query("select name,description,id,price,currency_id,discount,discount_unit,type,takeaway_price from menu where sub_category_id=".$rr['id']." and type<>'1' and deleted=0 and featured=1 order by IF( `order` = '', 1, 0), LENGTH(`order`),`order`, id") or die(mysql_error());
				
				if(mysql_num_rows($qr) > 0){
					
					$menys.='<h5 style="margin-bottom: 10px; margin-top:10px; float:left; width:100%;"><i style="display:block;">'.$rr['name'].'</i></h5>';
					
					while($row=mysql_fetch_assoc($qr)){
						
						
						//for the mandatory tillvals
						$qtill = mysql_query("select id from menu_option_details where menu_id='".$row['id']."' and single_option=1") or die(mysql_error());
						$count_tillvals = mysql_num_rows($qtill);
						
						//end tillvals
						
						$price=$row['price'];
						if(strlen($row['type'])>1){
							/*if($row['discount_unit']=="percent"){
								$discount=$row['discount']/100;
								$price=$price-($price*$discount);
							}else{
								$price=$price-$row['discount'];
							}*/
							
							if($row['takeaway_price']=='0.00'){
								$price=$row['price'];
							}else{
								$price=$row['takeaway_price'];
							}
						}
						
						$menu_price = number_format($price).' '.getCurrencyShortname($row['currency_id']);
						
						$menys.='<div class="section-devider">';
						
							$menys.='<div class="left-side">
							<h4 class="menu-name">'.$row['name'].'</h4>
							<strong class="web-price" style="font-size: 20px; margin-left: 12px;">'.$menu_price.'</strong>
										<p style="width: 80% !important;">'.$row['description'].'  <span style="display:none" class="subtotal-'.$row['id'].'" data-rel="'.$price.'">0</span></p> </div>';		
							// $menys .= '';	
							$menys .= '  <span class="outerinputs">
							<strong class="mobile-price">'.$menu_price.'</strong>
							<img src="'.CHILD_URL.'/images/Lagg-till.png" class="takeimg tacart" data-id="'.$row['id'].'" data-rel="'.$row['name'].'<span>'.$row['description'].'</span>'.'" data-tillval="'.$count_tillvals.'">
							<span id="inputs">						
							<input type="button" value="+" class="addme_takeaway" data-rel="'.$row['id'].'">
							<input type="text" class="quantity-takeaway quantity-'.$row['id'].'" data-rel="'.$row['id'].'" placeHolder="0" data-title="'.number_format($price).'" />
							<input type="button" value="-" class="subtractme_takeaway" data-rel="'.$row['id'].'">
							</span>
							
							</span>';
									
						$menys.='</div>';	
					}
				
				}
			
			}
			
			//$menys.='</div>';
			
		}
		
			$menys.='</div>';
		
		} //end checker if has dishes
		
	}
}

//prev button class is pay
//return '<div class="takeaway-wrapper" style="overflow:hidden; padding: 30px 0;">'.$menys.'<div style="clear:both"> <span id="sum" data-rel="0">Att betala 0</span><input type="button" class="pay" value="TILL KASSAN"/><div class="errormsg" style="display:none"></div></div></div>';
	$qs=mysql_query("select var_value from settings where var_name='takeaway_content'");
	$rs=mysql_fetch_assoc($qs);
	
	$takeawaycontent = '<div class="takeaway-content" style="margin-bottom: 30px;">
		<div class="takeaway-content-wrap">'.$rs['var_value'].'</div>
	</div>';
return '<div class="takeaway-wrapper" style="overflow:hidden; padding: 0;">'.$takeawaycontent.$menys.'<div style="clear:both"></div><div class="errormsg" style="display:none"></div></div>';
}

function script_takeaway(){
		?>
	<script type="text/javascript">
	var siteurl = $('.takeaway_cart_wrap').attr('data-rel');
	function updateQuantity(val,id,menu_id, tillval_count, menu_det, price)
	{
		$('#takeaway .cart_content').html('<center><img src="'+siteurl+'/images/loader.gif'+'"></center>');
		$.ajax({
			url: siteurl+"/update-cart.php",
			type: 'POST',
			data: 'id='+encodeURIComponent(id)+'&val='+encodeURIComponent(val)+'&menu_id='+encodeURIComponent(menu_id)+'&uniq='+encodeURIComponent(jQuery.cookie('takeaway_id')),
			success: function(value){
				$('#takeaway .cart_content').load(siteurl+"/takeaway-cart.php");
				
				//for the cart
				getcountItem(0);
				
				if(tillval_count>0){
										
					$('.special_request_content').html('<center><img src="'+siteurl+'/images/loader.gif'+'"></center>');
					$.ajax({
						url: siteurl+"/special-request.php",
						type: 'POST',
						async:false,
						data: 'id='+encodeURIComponent(id)+'&menu_det='+encodeURIComponent(menu_det)+'&siteurl='+encodeURIComponent(siteurl)+'&price='+encodeURIComponent(price),
						success: function(value){
							$('.special_request_content').html(value);
						}
					});
					$('.fader, #special_request').fadeIn();
				}
			}
		});
	}
	
	function computeTotal()
	{
		var total=0;
		$('.quantity-takeaway').each(function() {
			var val=$(this).val();
			if(val!='' && val!=0){
				var id=$(this).attr('data-rel');
				
				var subtotal=Number($('.subtotal-'+id).html());

				total+=subtotal;
			}
		});
		
	   	$('#sum').html('Att betala '+total.toFixed(2));
	    $('#sum').attr('data-rel',total.toFixed(2));
	}

	$(function(){
		$(document).find('.quantity-takeaway').numeric();
		$(document).on('blur', '.quantity-takeaway', function(){
			var id = $(this).attr('data-id');
			var menu_id = $(this).attr('data-rel');
			var val = $(this).val();
			updateQuantity(val,id,menu_id, 0, 0, 0);
		});

		$(document).on('keypress', '.quantity-takeaway', function(e) {
			if(e.which == 13)
			{
				var id = $(this).attr('data-id');
				var menu_id = $(this).attr('data-rel');
				var val = $(this).val();
				updateQuantity(val,id,menu_id, 0, 0, 0);
			}
		});
		
		$(document).on('click', '.addme_takeaway', function(){
			var id = $(this).attr('data-id');
			var menu_id = $(this).attr('data-rel');
			var val = Number($('.quantity-'+id).val());
			var tillval_count = $(this).attr('data-tillvals');
			var menu_det = $('.btn_optional_dish-'+id).attr('data-rel');
			var price = $('.btn_optional_dish-'+id).attr('data-price');
			val+=1;
			updateQuantity(val,id,menu_id, tillval_count, menu_det, price);
		});
		
		$(document).on('click', '.subtractme_takeaway', function(){
			var id = $(this).attr('data-id');
			var menu_id = $(this).attr('data-rel');
			var val = Number($('.quantity-'+id).val());
			val-=1;
			if(val==0){
				val=0;
			}
			updateQuantity(val,id,menu_id, 0, 0, 0);
		});

		$(document).on('click','.btn_optional_dish',function(){
			var id = $(this).attr('data-id');
			var menu_det = $(this).attr('data-rel');
			var price = $(this).attr('data-price');
			
			$('.special_request_content').html('<center><img src="'+siteurl+'/images/loader.gif'+'"></center>');
			$.ajax({
				url: siteurl+"/special-request.php",
				type: 'POST',
				data: 'id='+encodeURIComponent(id)+'&menu_det='+encodeURIComponent(menu_det)+'&siteurl='+encodeURIComponent(siteurl)+'&price='+encodeURIComponent(price)+'&check=first',
				success: function(value){
					$('.special_request_content').html(value);
				}
			});
			
			$('.fader, #special_request').fadeIn();
			
		});

		$('.quantity-takeaway_old').keyup(function() {
			$(this).val($(this).val().replace('.','').replace('-',''));
			var val = $(this).val();
			var id = $(this).attr('data-rel');
			var price = Number($('.subtotal-'+id).attr('data-rel'));
			$('.subtotal-'+id).html((val*price));
			computeTotal();
		});
    	
	   	$('.addme_takeaway_old').click(function() {
			var theclass = $(this).attr('data-rel');
			var val = Number($('.quantity-'+theclass).val());
			
			val = val+1;
			
			$('.quantity-'+theclass).val(val);
		   	var price = Number($('.subtotal-'+theclass).attr('data-rel'));
		   	$('.subtotal-'+theclass).html((val*price));
		   	computeTotal();
			
		}); 	
		
		$('.subtractme_takeaway_old').click(function(){
			var theclass = $(this).attr('data-rel');
			var val = Number($('.quantity-'+theclass).val());
			val = val-1;
			if(val<=0){
				val=0;
			}
			$('.quantity-'+theclass).val(val);
			var price = Number($('.subtotal-'+theclass).attr('data-rel'));
		   	$('.subtotal-'+theclass).html((val*price));
		   	computeTotal();
		}); 

		$('.btn_check_outt').click(function(){
			
			$.fn.center = function ()
			{
				this.css("position","fixed");
				// this.css("top", ((jQuery(window).height() / 2) - (this.outerHeight() / 2))+50);
				this.css("top", "80px");
				//this.css("left", ($(window).width() / 2) - (this.outerWidth() / 2));
				return this;
			}
			
			
			var total = $(this).attr('data-rel');
			
			$('.fader').fadeIn();
			$('.step-2-wrapper').addClass('steploader').html('<center><img src="'+siteurl+'/images/loader.gif'+'"></center>');
			$('.step-2-wrapper').center();
			
			$.ajax({
				url: siteurl+"/steps.php",
				type: 'POST',
				async: false,
				data: 'total='+encodeURIComponent(total)+'&tablename='+encodeURIComponent('takeaway_settings')+'&siteurl='+encodeURIComponent(siteurl),
				success: function(value){	
				
					$('.step-2-wrapper').removeClass('steploader').addClass('removecenter').html(value);
					$('.steps-container').center();
					$('.takeemail').val($.cookie('limone_email'));
					$('.takepass').val($.cookie('limone_pass'));
					
				}
			 });
	   });
	});
	</script>
<?php
}