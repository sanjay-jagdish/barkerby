<?php

function takeAway(){

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