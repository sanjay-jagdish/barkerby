<?php
	include 'config.php';
	
	$uniq=$_POST['uniq'];
	$theurl = $_POST['theurl'];
	$delid = $_POST['del_id'];
	$request = $_POST['request'];
	$request_id = $_POST['request_id'];
	$id = $_POST['id'];
	$quan = $_POST['quan'];
	
	$siteurl = $_POST['siteurl'];
	
	if(isset($delid)){
		mysql_query("delete from reservation_detail where id='".$delid."'");
	}
	
	if(isset($quan)){
		mysql_query("update reservation_detail set quantity='".$quan."' where id='".$id."'");
	}
	
	if(isset($request_id)){
		mysql_query("update reservation_detail set notes='".$request."' where id='".$request_id."'");
	}
	
	
	$q=mysql_query("select id from reservation where uniqueid='".$uniq."'") or die(mysql_error());
	$r = mysql_fetch_assoc($q);
	
	function getCurrentCurrency(){
		//get currency
		$qc=mysql_query("select shortname from currency where deleted=0 and set_default=1");
		$rc=mysql_fetch_assoc($qc);
		return strtolower($rc['shortname']);
	}
	
	if(mysql_num_rows($q) > 0){
		$reservation_id = $r['id'];
?>
<style>
	.cart-opt{
		padding:0;
		/*display:none;*/
	}
	.cart-opt input{
		width:auto;	
	}
	.cart-opt label{
		display:inline-block;
		font-size:14px;
		vertical-align:middle;
		line-height:22px;
	}
	
	.cart-opt #opt-name{
		min-width:150px;	
	}
	.cart-opt .opt-item {
		line-height: 20px;
	}
	.cart-opt .opt-pr-cur{
		min-width:50px;
		display: inline-block;
		text-align: right;
	}
	.menudetails textarea,
	.menudetails input[type=text]{
		border: 1px solid #cddade;
		width: 100%;
		box-sizing: border-box;
		border-radius:0 !important;
	}
	.menudetails textarea{
		min-height:115px;
		resize: none;	
	}
	
	.menudetails img{
		-webkit-transition: all 0.5s;
		-moz-transition: all 0.5s;
		-o-transition: all 0.5s;
		transition: all 0.5s;
	}
	.menudetails.with-image img:hover{
		-webkit-transform: translate(11em,0) scale(4);
		-moz-transform: translate(11em,0) scale(4);
		-ms-transform: translate(11em,0) scale(4);
		-o-transform: translate(11em,0) scale(4);
		transform: translate(11em,0) scale(4);
		cursor:crosshair;
	}
	.takeawaycart-detail{
		background:#054787 url(images/cart-bg.png) repeat-x;
		border:0;
		/*border-radius:4px;*/
		padding:12px;
		border-bottom:12px solid #1E3556;
		top: 20px !important;
		padding-bottom: 50px;
	}
	.takeawaycart-detail h2{
		text-align:left;
		color:#fdfdfd;
		font-size:26px;
		padding-left:10px;
	}
	.tacart-inner{
		background:#fdfdfd;
		padding-bottom:50px;
		height: 100%;
	}
	.quan-control input[type=text]{
		padding:5px 8px;
	}
	.quan-control input[type=button]{
		padding:8px 18px;
		border-radius:0;
		min-height:27px;
		min-width:27px;
		color:#fff;
		text-align:center;
	}
	

/*for table responsive ------------------------------------------------------------*/
@media only screen and (max-width: 770px) {
   input.toggleopt{
	  margin-left: 42%;
  }
  
	.rwd-table {
	  margin: 1em 0;
	  min-width: 200px;
	}
	.rwd-table tr {
	  border-top: 1px solid #ddd;
	  border-bottom: 1px solid #ddd;
	}
	.rwd-table th {
	  display: none;
	}
	.rwd-table td {
	  display: block;
	}
	.rwd-table td:first-child {
	  padding-top: .5em;
	}
	.rwd-table td:last-child {
	  padding-bottom: .5em;
	}
	.rwd-table td:before {
	  /*content: attr(data-th) ": ";*/
	  content: attr(data-th);
	  font-weight: bold;
	  width: 10%;
	  display: inline-block;
	}
	.rwd-table th, .rwd-table td {
  		text-align: left;
	}
	.rwd-table {
	 /* background: #34495E;*/
	  /*color: #fff;*/
	  border-radius: .4em;
	  overflow: hidden;
	}
	.rwd-table th, .rwd-table td {
	 /* margin: .5em 1em;*/
	}
	
	.remove-from-cart:before {
		width: 0 !important;
	}
	
	.menudetails.with-image img:hover {
		-webkit-transform: translate(0em,0) scale(2.5) !important;
		-moz-transform: translate(0em,0) scale(2.5) !important;
		-ms-transform: translate(0em,0) scale(2.5) !important;
		-o-transform: translate(0em,0) scale(2.5) !important;
		transform: translate(0em,0) scale(2.5) !important;
	}
	
	.cart-detail, .takeawaycart-detail{
		max-height: 450px !important;
	}
}


@media (min-width: 770px) {
  .rwd-table th, .rwd-table td {
    display: table-cell;
    padding: .25em .5em;
  }
  .rwd-table th:first-child, .rwd-table td:first-child {
    padding-left: 0;
  }
  .rwd-table th:last-child, .rwd-table td:last-child {
    padding-right: 0;
  }
  
  .rwd-table th, .rwd-table td {
      padding: 5px 10px !important;
  }
  
  .rwd-table td:before {
    display: none;
  }
}


</style>
<h2>Kundvagn <a href="javascript:void(0)" class="close-cart">Close</a></h2>
<div class="tacart-inner">
    <div class="clear"></div>
	<div class="forcatering">
        <div class="cartbox">
        	<table align="center" class="rwd-table">
            	<tr class="firsttr">
                	<th>Bild</th>
                	<th width="400px">Rätt</th>
                    <th>Speciella önskemål</th>
                    <th>Pris</th>
                    <th>Antal</th>
                    <th>Totalt</td>
                    <th width="110"></th>
                </tr>
                <?php
                	$q=mysql_query("select m.name as menu, rd.notes as notes, rd.price as price, rd.quantity as quantity, rd.id as id, m.image as img, m.id as menu_id, rd.lunchmeny as lunchmeny, m.single_option as single_option, m.sub_category_id as subcat, m.cat_id as cat from reservation_detail as rd, menu as m where m.id=rd.menu_id and m.deleted=0 and rd.reservation_id='".$reservation_id." and rd.lunchmeny=0'");
					
					
					$count = 0;
					
					$subtotal=0;
				
					while($r=mysql_fetch_assoc($q)){
						$count++;
					
						
						if($count%2==0){
							$theclass='even';
						}
						else{
							$theclass='odd';
						}
						if(trim($r['img'])!=''){
							$pimg = 'with-image';
						}else{
							$pimg = 'no-image';
						}
				?>
                	<tr class="<?php echo $theclass.' '.$pimg; ?> menudetails menudetail-<?php echo $r['id'];?>">
                    	<td data-th="Bild:" align="center" valign="top">
                        	<?php
                            	if(trim($r['img'])!=''){
							?>
                            	<img src="<?php echo $siteurl.'/webmin/uploads/'.$r['img']; ?>" style="width:100px!important; height:auto; display:block; margin:0 auto;">
                            <?php		
								}else{
								?>
								<img src="<?php echo $siteurl.'/webmin/images/no-photo-available.png'; ?>" style="width:30px!important; height:auto; display:block; margin:0 auto;">
								<?php
								}
							?>
                        </td>
                    	<td data-th="Rätt:" valign="top"  width="400px">
							<?php 
								
								$subcat = getTakeawayCategorySubcategory($r['subcat'],'sub_category');
								
								if($r['subcat']==0){
									$subcat = getTakeawayCategorySubcategory($r['cat'],'category');
								}
								
								echo $subcat.' - '.$r['menu'];  
							
							?>
                        	 
                            <?php $opt_q = mysql_query("select * from menu_options where menu_id = $r[menu_id]") or die(mysql_error());
							if(mysql_num_rows($opt_q)>0){
							?>
                             <!--<div><input type="button" style="font-size:12px; padding:8px 18px; border-radius:0;" class="toggleopt" alt="opt-holder-<?php //echo $r['id'];?>" value="Tillval"></div>-->
                             <?php if($r['quantity']>1){
								echo '<div style="padding-left:10px;" class="cart-opt" id="opt-holder-'.$r['id'].'">';
								 for ($x=1; $x <= number_format($r['quantity']); $x++) {
									 	echo '<div class="btn-'.$x.'-'.$r['id'].'"><input type="button" style="font-size:12px; padding:8px 18px; text-transform:none; border-radius:0;" class="toggleoptin" alt="dish-holder-'.$x.'-'.$r['id'].'" value="Portion #'.$x.'"><a href="javascript:void(0)" class="remove-tillval" data-id="dish-holder-'.$x.'-'.$r['id'].'" data-rel="btn-'.$x.'-'.$r['id'].'" data-title="'.$r['id'].'" data-count="'.$x.'">X</a></div>';
										$query_opt = mysql_query("select * from menu_options where menu_id = $r[menu_id]") or die(mysql_error());
										echo '<div class="cart-opt" id="dish-holder-'.$x.'-'.$r['id'].'">';
										
										$cnt = 0;
										
										while($opt = mysql_fetch_assoc($query_opt)){
											$pr_mu = ($opt['price']!=0)? number_format($opt['price'],0).' '.getCurrentCurrency() : '';
											$cnt++;
										?>	
											<div>
                                            
                                            <?php if($r['single_option']==0){ ?>
                                            <input type="checkbox" rel="st-<?php echo $r['id']; ?>" alt="<?php echo $opt['price']; ?>" id="opt_<?php echo $x; ?>-<?php echo $opt['id']; ?>" style="width:auto;"/>&nbsp;<label style="width:150px;" for="opt_<?php echo $x; ?>-<?php echo $opt['id']; ?>"><?php echo $opt['name']; ?></label><span class="opt-pr-cur"><label><?php echo $pr_mu; ?></label></span>
                                            <?php }
											else{
											?>
                                            <input type="radio" rel="st-<?php echo $r['id']; ?>" alt="<?php echo $opt['price']; ?>" id="opt_<?php echo $x; ?>-<?php echo $opt['id']; ?>" style="width:auto;" name="opt-radio<?php echo $x; ?>" <?php if($cnt==1){ echo 'checked="checked"'; }?>/>&nbsp;<label style="width:150px;" for="opt_<?php echo $x; ?>-<?php echo $opt['id']; ?>"><?php echo $opt['name']; ?></label><span class="opt-pr-cur"><label><?php echo $pr_mu; ?></label></span>
                                            <?php } ?>
                                            </div>
                                       <?php
									        
										}
										echo '</div>';
								 }
								 echo '</div>';
							 	}else{?>
								 	<div class="cart-opt" id="opt-holder-<?php echo $r['id'];?>">
                                    <?php 
									$cnt = 0;
									while($opt = mysql_fetch_assoc($opt_q)){ 
										$cnt++;
									?>
                                        <div class="opt-item">
                                        <?php if($r['single_option']==0){ ?>
                                        <input type="checkbox" rel="st-<?php echo $r['id'];?>" alt="<?php echo $opt['price'];?>" id="opt_1-<?php echo $opt['id'];?>" />
                                        <?php }
											else{
												
										?>
                                        <input type="radio" rel="st-<?php echo $r['id'];?>" alt="<?php echo $opt['price'];?>" id="opt_1-<?php echo $opt['id'];?>" <?php if($cnt==1){ echo 'checked="checked"'; }?> name="opt-radio">
                                        <?php	
											}
										?>
                                        <label id="opt-name" for="opt_1-<?php echo $opt['id'];?>"><?php echo $opt['name'];?></label>&nbsp;
                                        <span class="opt-pr-cur">
                                            <label id="opt-pr"><?php echo ($opt['price']!=0)? number_format($opt['price'],0).' '.getCurrentCurrency() : '';?></label>
                                        </span></div>
                                    <?php }?>
                                 </div>
							 	<?php }?>
                             
                             <?php }?>
                        </td>
                        <td data-th="Specialla önskemål:" valign="top" class="special-req"><textarea class="request" data-rel="<?php echo $r['id'];?>"><?php echo $r['notes']; ?></textarea></td>
                        <td data-th="Pris:" valign="top"><?php echo $r['price'].' '.getCurrentCurrency(); ?></td>
                        <td data-th="Antal:" class="quan-control" valign="top">
                        <input type="text :" class="quan quan<?php echo $r['id'];?>" data-rel="<?php echo $r['id'];?>" value="<?php echo $r['quantity']; ?>" style="width:67px !important;">
                        <input type="button" value="-" data-rel="<?php echo $r['id'];?>" class="msubtract">
                        <input type="button" value="+" data-rel="<?php echo $r['id'];?>" class="madd">
                        </td>
                        <td data-th="Totalt:" valign="top";><?php echo '<span class="stotal" id="st-'.$r['id'].'" alt="'.($r['price']*$r['quantity']).'">'.($r['price']*$r['quantity']).'</span> '.getCurrentCurrency(); 
									$subtotal+=($r['price']*$r['quantity']);  ?></td>
                        <td valign="top" class="remove-from-cart"><a href="javascript:void(0)" class="remove-cart" data-rel="<?php echo $r['id'];?>"></a></td>
                    </tr>
                <?php
					}
				?>
           </table>
                
           <div style="font-weight:bold; background:#ddd; padding: 20px; text-align:right;">
                <span>Slutsumma: </span>
                <span align="left" class="thetotal"><?php echo '<label style="font-size: 18px;" id="sumtotal" alt="'.number_format($subtotal,2).'">'.number_format($subtotal,0).'</label> '.getCurrentCurrency(); ?></span>
           </div>
           
           <div>
               <span style="padding:0 !important;"><a href="javascript:void(0)" class="open-takeaway-cart" data-rel="<?php echo $subtotal; ?>">Fortsätt</a></span>
            </div>
            
        </div>
        
    </div>    
</div>
<a href="javascript:void(0)" class="goto goto-bottom"></a>
<a href="javascript:void(0)" class="goto goto-top"></a>

<?php		
		
	}
	
?>	


<script type="text/javascript">
	var quan_start = 0;
	var quan_end = 0;
	$(function(){

		var checker = Number($('.thetotal label').html());
		if(checker<=0){
			$('.fade, .takeawaycart-detail, .takeawaycart').fadeOut();
			jQuery.removeCookie('cart_opt');
			//jQuery.removeCookie('takeaway_id', { path: '/' });
			//window.location.reload();
		}
		
		$('.remove-tillval').click(function(){
			var portion = $(this).attr('data-rel');
			var portion_options = $(this).attr('data-id');
			var quan = Number($('.quan'+$(this).attr('data-title')).val());
			var id = $(this).attr('data-title');
			var iterator = Number($(this).attr('data-count'));
			var count = $('.remove-tillval').length;
			
			$('.'+portion+', #'+portion_options).remove();
			
			quan = quan-1;
			
			if(quan<1){
				//quan=1;
				removeItem(id);
			}
			else{
				restoreCookie();
				
				
				var hold = iterator;
				var arr = new Array();
				var str=(jQuery.cookie('cart_opt')).split(',');
				
				while(iterator<count){
					iterator++;
					
					for(i=0;i<str.length;i++){
						
						console.log(str[i].indexOf('_'+iterator));
						
						if(str[i].indexOf('_'+iterator)!=-1){
							str[i]=str[i].replace('_'+iterator,'_'+hold);
						}
						
						arr.push(str[i]);
					}
					
					hold++;
				}
				
				var expiration_date = new Date();
				var expiration_minutes = 60;
				expiration_date.setTime(expiration_date.getTime() + (expiration_minutes * 60 * 1000));
				
				jQuery.cookie('cart_opt', arr.join(','), {path: '/', expires:expiration_date});
				
				
				$.ajax({
						url: "<?php echo $theurl; ?>/takeaway-cart.php",
						type: 'POST',
						async: false,
						data: 'uniq='+encodeURIComponent('<?php echo $uniq; ?>')+'&quan='+encodeURIComponent(quan)+'&id='+encodeURIComponent(id)+'&theurl='+encodeURIComponent('<?php echo $theurl; ?>'),
						success: function(value){
							
							$('.takeawaycart-detail').html(value).center();
							var opt_cookie =unescape(jQuery.cookie('cart_opt'));
							//console.log(opt_cookie);
							
							if(opt_cookie === null || opt_cookie === "" || opt_cookie === "null" || opt_cookie === 'undefined'){
								  //no cookie
								  //do nothing
								  //console.log('ek');
							}else{
								var cart_opt=opt_cookie.split(',')
								
								for (i = 0; i < cart_opt.length; i++) { 
									
									$('#'+cart_opt[i]).attr('checked',true)
									
									var optval = parseFloat($('#'+cart_opt[i]).attr('alt'));
									var st_tar = $('#'+cart_opt[i]).attr('rel');
									
									var cur_stotal = parseFloat($('.stotal#'+st_tar).html());
									var cur_total = parseFloat($('#sumtotal').html());
								
									$('#'+st_tar).html(cur_stotal+optval);
									$('#sumtotal').html(cur_total+optval);
									$('.open-takeaway-cart').attr('data-rel',cur_total+optval);
								}
							}
						}
				});
			}
			
			
			
		});
		
		$('.quan').numeric();
		
		$.fn.center = function ()
			{
				this.css("position","fixed");
				this.css("top", (($(window).height() / 2) - (this.outerHeight() / 2))+50);
				return this;
		}
		
		$('.close-cart').click(function(){
			$('.fade, .takeawaycart-detail').fadeOut();
		});
		$('.quan').focusin(function(){
			var quan = Number($(this).val());
			quan_start = quan;
		}).focusout(function(){
			
			var id = $(this).attr('data-rel');
			var quan = Number($(this).val());
			
			if(quan_start > quan){
				var last_quan = Array.apply(null, {length: quan_start}).map(Number.call, Number);
				var new_quan =  Array.apply(null, {length: quan}).map(Number.call, Number);
				var cur_arr = last_quan.uniqueFrom(new_quan);
				for(var i=0;i<cur_arr.length;i++){
					var elem = cur_arr[i]+1;
					var opt_holder = '#dish-holder-'+elem+'-'+id;
					if($(opt_holder+' .input[type=checkbox]').length > 0){
						$(opt_holder+' .input[type=checkbox]').prop('checked',false);
					}
					else{
						$(opt_holder+' .input[type=radio]').prop('checked',false);
					}
					$(opt_holder).remove();
				}
			}
			
			restoreCookie();
			
			if(quan<1){
				//quan=1;
				removeItem(id);
			}
			else{
				$.ajax({
						url: "<?php echo $theurl; ?>/takeaway-cart.php",
						type: 'POST',
						async: false,
						data: 'uniq='+encodeURIComponent('<?php echo $uniq; ?>')+'&quan='+encodeURIComponent(quan)+'&id='+encodeURIComponent(id)+'&theurl='+encodeURIComponent('<?php echo $theurl; ?>'),
						success: function(value){
							
							
							$('.takeawaycart-detail').html(value).center();
							
							var opt_cookie =unescape(jQuery.cookie('cart_opt'))
							//console.log(opt_cookie);
							
							if(opt_cookie === null || opt_cookie === "" || opt_cookie === "null" || opt_cookie === 'undefined'){
								  //no cookie
								  //do nothing
								  //console.log('ek');
							}else{
								var cart_opt=opt_cookie.split(',')
								
								for (i = 0; i < cart_opt.length; i++) { 
									
									$('#'+cart_opt[i]).attr('checked',true)
									
									var optval = parseFloat($('#'+cart_opt[i]).attr('alt'));
									var st_tar = $('#'+cart_opt[i]).attr('rel');
									
									var cur_stotal = parseFloat($('.stotal#'+st_tar).html());
									var cur_total = parseFloat($('#sumtotal').html());
								
									$('#'+st_tar).html(cur_stotal+optval);
									$('#sumtotal').html(cur_total+optval);
									$('.open-takeaway-cart').attr('data-rel',cur_total+optval);
								}
							}
						}
				});	
			}
		});
		
		$('.madd').click(function(){
			
			restoreCookie();
			
			var id = $(this).attr('data-rel');
			var quan = Number($('.quan'+id).val());
			
			quan = quan+1;
			
			$.ajax({
					url: "<?php echo $theurl; ?>/takeaway-cart.php",
					type: 'POST',
					async: false,
					data: 'uniq='+encodeURIComponent('<?php echo $uniq; ?>')+'&quan='+encodeURIComponent(quan)+'&id='+encodeURIComponent(id)+'&theurl='+encodeURIComponent('<?php echo $theurl; ?>'),
					success: function(value){
						
						$('.takeawaycart-detail').html(value).center();
						var opt_cookie =unescape(jQuery.cookie('cart_opt'))
						//console.log(opt_cookie);
						
						if(opt_cookie === null || opt_cookie === "" || opt_cookie === "null" || opt_cookie === 'undefined'){
							  //no cookie
							  //do nothing
							  //console.log('ek');
						}else{
							var cart_opt=opt_cookie.split(',')
							
							for (i = 0; i < cart_opt.length; i++) { 
								
								$('#'+cart_opt[i]).attr('checked',true)
								
								var optval = parseFloat($('#'+cart_opt[i]).attr('alt'));
								var st_tar = $('#'+cart_opt[i]).attr('rel');
								
								var cur_stotal = parseFloat($('.stotal#'+st_tar).html());
								var cur_total = parseFloat($('#sumtotal').html());
							
								$('#'+st_tar).html(cur_stotal+optval);
								$('#sumtotal').html(cur_total+optval);
								$('.open-takeaway-cart').attr('data-rel',cur_total+optval);
							}
						}
					}
			});	
				
		});
		
		$('.msubtract').click(function(){
			
			
			
			var id = $(this).attr('data-rel');
			var quan = Number($('.quan'+id).val());
			
			var last_quan = Array.apply(null, {length: quan}).map(Number.call, Number);
			var new_quan =  Array.apply(null, {length: quan-1}).map(Number.call, Number);
			
			var cur_arr = last_quan.uniqueFrom(new_quan);
			
			for(var i=0;i<cur_arr.length;i++){
				var elem = cur_arr[i]+1;
				var opt_holder = '#dish-holder-'+elem+'-'+id;
				if($(opt_holder+' .input[type=checkbox]').length > 0){
					$(opt_holder+' .input[type=checkbox]').prop('checked',false);
				}
				else{
					$(opt_holder+' .input[type=radio]').prop('checked',false);
				}
				$(opt_holder).remove();
			}
			
			quan = quan-1;
			
			if(quan<1){
				//quan=1;
				removeItem(id);
			}
			else{
				restoreCookie();
				$.ajax({
						url: "<?php echo $theurl; ?>/takeaway-cart.php",
						type: 'POST',
						async: false,
						data: 'uniq='+encodeURIComponent('<?php echo $uniq; ?>')+'&quan='+encodeURIComponent(quan)+'&id='+encodeURIComponent(id)+'&theurl='+encodeURIComponent('<?php echo $theurl; ?>'),
						success: function(value){
							
							$('.takeawaycart-detail').html(value).center();
							var opt_cookie =unescape(jQuery.cookie('cart_opt'))
							//console.log(opt_cookie);
							
							if(opt_cookie === null || opt_cookie === "" || opt_cookie === "null" || opt_cookie === 'undefined'){
								  //no cookie
								  //do nothing
								  //console.log('ek');
							}else{
								var cart_opt=opt_cookie.split(',')
								
								for (i = 0; i < cart_opt.length; i++) { 
									
									$('#'+cart_opt[i]).attr('checked',true)
									
									var optval = parseFloat($('#'+cart_opt[i]).attr('alt'));
									var st_tar = $('#'+cart_opt[i]).attr('rel');
									
									var cur_stotal = parseFloat($('.stotal#'+st_tar).html());
									var cur_total = parseFloat($('#sumtotal').html());
								
									$('#'+st_tar).html(cur_stotal+optval);
									$('#sumtotal').html(cur_total+optval);
									$('.open-takeaway-cart').attr('data-rel',cur_total+optval);
								}
							}
						}
				});
			}
				
		});
		
		$('.remove-cart').click(function(){
			
			
			
			var id = $(this).attr('data-rel');
			if($('.menudetail-'+id+' input[type=checkbox]').length > 0){
				$('.menudetail-'+id+' input[type=checkbox]').prop('checked',false);
			}
			else{
				$('.menudetail-'+id+' input[type=radio]').prop('checked',false);
			}
			
			restoreCookie();
			
			removeItem(id);
			
			//for the cart
			getcountItem();
				 
		});
		
		$('.request').focusout(function(){
		
			var id = $(this).attr('data-rel');
			var val = $(this).val();
			
			$.ajax({
					url: "<?php echo $theurl; ?>/takeaway-cart.php",
					type: 'POST',
					async: false,
					data: 'uniq='+encodeURIComponent('<?php echo $uniq; ?>')+'&request='+encodeURIComponent(val)+'&request_id='+encodeURIComponent(id)+'&theurl='+encodeURIComponent('<?php echo $theurl; ?>'),
					success: function(value){
					
						$('.takeawaycart-detail').html(value).center();
						
					}
			});	 
		
		});
		
		//pay
		
		$('.open-takeaway-cart').click(function(){
			restoreCookie();
			var total = $(this).attr('data-rel');
			  $.ajax({
				url: "<?php echo $theurl; ?>/steps.php",
				type: 'POST',
				async: false,
				data: 'total='+encodeURIComponent(total)+'&tablename='+encodeURIComponent('takeaway_settings'),
				success: function(value){
				
					//console.log(value);
					
					//$('.menu-takeaway, .takeawaycart-detail, .fade').css({'display':'none'});
					
					$('.takeawaycart-detail').css({'display':'none'});
					
					$('.step-2-wrapper').html(value);
					$('.steps-container').center();
					//$('html, body').animate({scrollTop:$('#text-24').position().top}, 'slow');
					$('.takeemail').val(jQuery.cookie('limone_email'));
					$('.takepass').val(jQuery.cookie('limone_pass'));
					
				}
			 }); 
	   });
   $('.opt-item input[type=checkbox], .opt-item input[type=radio]').click(function(){
	  	//.stotal
		//#sumtotal
		var optval = parseFloat($(this).attr('alt'));
		var st_tar = $(this).attr('rel');
		var cur_stotal = parseFloat($('.stotal#'+st_tar).html());
		var cur_total = parseFloat($('#sumtotal').html());
		
		if (this.checked) {
			$('#'+st_tar).html(cur_stotal+optval);
			$('#sumtotal').html(cur_total+optval);
			$('.open-takeaway-cart').attr('data-rel',formatNumber(cur_total+optval));
		}else{
			$('#'+st_tar).html(cur_stotal-optval);
			$('#sumtotal').html(cur_total-optval);
			$('.open-takeaway-cart').attr('data-rel',formatNumber(cur_total-optval));
		}
   });
    $('.cart-opt .cart-opt input[type=checkbox], .cart-opt .cart-opt input[type=radio]').click(function(){
	  	//.stotal
		//#sumtotal
		var optval = parseFloat($(this).attr('alt'));
		var st_tar = $(this).attr('rel');
		var cur_stotal = parseFloat($('.stotal#'+st_tar).html());
		var cur_total = parseFloat($('#sumtotal').html());
		
		if (this.checked) {
			$('#'+st_tar).html(cur_stotal+optval);
			$('#sumtotal').html(cur_total+optval);
			$('.open-takeaway-cart').attr('data-rel',formatNumber(cur_total+optval));
		}else{
			$('#'+st_tar).html(cur_stotal-optval);
			$('#sumtotal').html(cur_total-optval);
			$('.open-takeaway-cart').attr('data-rel',formatNumber(cur_total-optval));
		}
   });
   $('.toggleopt').click(function(){
	    //$('.cart-opt').slideUp('fast');
	    var holder = $(this).attr('alt');
		$('#'+holder).slideToggle( "fast" );   
   });
   
    $('.toggleoptin').click(function(){
		$('.cart-opt .cart-opt').slideUp('fast');
	    var holder = $(this).attr('alt');
		if($("#"+holder).css("display") == "block"){
			//do nothing here
		}else{
			$('#'+holder).slideToggle( "fast" );   
		}
		
   });
	});
	
	
	function removeItem(id){
	
		$.ajax({
					url: "<?php echo $theurl; ?>/takeaway-cart.php",
					type: 'POST',
					async: false,
					data: 'uniq='+encodeURIComponent('<?php echo $uniq; ?>')+'&del_id='+encodeURIComponent(id)+'&theurl='+encodeURIComponent('<?php echo $theurl; ?>'),
					success: function(value){
						
						$('.takeawaycart-detail').html(value).center();
						
						var opt_cookie =unescape(jQuery.cookie('cart_opt'))
						//console.log(opt_cookie);
						
						if(opt_cookie === null || opt_cookie === "" || opt_cookie === "null" || opt_cookie === 'undefined'){
							  //no cookie
							  //do nothing
							  //console.log('ek');
						}else{
							var cart_opt=opt_cookie.split(',')
							
							for (i = 0; i < cart_opt.length; i++) { 
								
								$('#'+cart_opt[i]).attr('checked',true)
								
								var optval = parseFloat($('#'+cart_opt[i]).attr('alt'));
								var st_tar = $('#'+cart_opt[i]).attr('rel');
								
								var cur_stotal = parseFloat($('.stotal#'+st_tar).html());
								var cur_total = parseFloat($('#sumtotal').html());
							
								$('#'+st_tar).html(cur_stotal+optval);
								$('#sumtotal').html(cur_total+optval);
								$('.open-takeaway-cart').attr('data-rel',cur_total+optval);
							}
						}
					}
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
	function restoreCookie(){
		jQuery.removeCookie('cart_opt');
		var cart_opt = [];
		if($('.cartbox input[type=checkbox]').length > 0){
			$('.cartbox input[type=checkbox]').each(function(){
				if(this.checked){
					cart_opt.push($(this).attr('id'));
				}
			});
		}
		else{
			$('.cartbox input[type=radio]').each(function(){
				if(this.checked){
					cart_opt.push($(this).attr('id'));
				}
			});
		}
		//console.log('new COokie'+cart_opt);
		var expiration_date = new Date();
		var expiration_minutes = 60;
		expiration_date.setTime(expiration_date.getTime() + (expiration_minutes * 60 * 1000));
		
		jQuery.cookie('cart_opt', cart_opt.join(','), {path: '/', expires:expiration_date});	
	}
	Array.prototype.uniqueFrom = function() {
	  if (!arguments.length)
		return [];
	  var a1 = this.slice(0); // Start with a copy
	
	  for (var n=0; n < arguments.length; n++) {
		var a2 = arguments[n];
		if (!(a2 instanceof Array))
		  throw new TypeError( 'argument ['+n+'] must be Array' );
	
		for(var i=0; i<a2.length; i++) {
		  var index = a1.indexOf(a2[i]);
		  if (index > -1) {
			a1.splice(index, 1);
		  } 
		}
	  }
	  return a1;
	}
	
</script>


<!--GO TO CART SCROLL---------------------------------------->	
<script type="text/javascript">
$(function(){	
		$(".tacart-inner").css('overflow' ,'auto');
		var cart_scroller = $(".tacart-inner");
		
		
		$('.goto-bottom').click(function(){
			cart_scroller.animate({ scrollTop: cart_scroller[0].scrollHeight }, "fast");
		});
		$('.goto-top').click(function(){
			cart_scroller.animate({ scrollTop: 0 }, "fast");
		});
		
		var h =  parseInt(cart_scroller.height().toString()) + 59;
		
		cart_scroller.scroll(function() {
	
		  if (cart_scroller[0].scrollHeight - cart_scroller.scrollTop() == cart_scroller.outerHeight()) {
			 $('.goto-bottom').fadeOut('fast');
			 $('.goto-top').show('fast');
		  }else{
			 $('.goto-bottom').fadeIn('fast');
			 $('.goto-top').hide('fast');
		  }
			
		});	
		
		console.log(cart_scroller.prop('scrollHeight')+' '+h);
		
		if(cart_scroller.prop('scrollHeight') > h){
			$('.goto').show('fast');
		}else{
			$('.goto').hide('fast');
		}
		
		if(cart_scroller.prop('scrollHeight') == h+1){
			$('.goto').hide('fast');
		}
});
</script>
<!--END GO TO CART SCROLL---------------------------------------->