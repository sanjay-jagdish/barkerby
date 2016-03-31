<?php
function lunchmeny(){
   $counts=0;
	$timezone_sql = "SELECT var_value AS timezone FROM settings WHERE var_name='timezone'";
	$timezone_qry = mysql_query($timezone_sql);
	$timezone = mysql_fetch_assoc($timezone_qry);
	
	date_default_timezone_set($timezone['timezone']);
	
	//echo '<font style="color:white; font-size:2px;">'.date('Y-m-d H:i:s').' Week#:'.date('N').' Day#:'.date('N').' G#:'.date('G').' H#:'.date('H').'</font>';
	
	function thedayName($date){
		
		$dayname=array('Mon'=>'Mån','Tue'=>'Tis','Wed'=>'Ons','Thu'=>'Tors','Fri'=>'Fre','Sat'=>'Lör','Sun'=>'Sön');
		
		$day=explode(' ',$date);
	
		return str_replace($day[0],$dayname[$day[0]],$date);
		
	}
	
	$year_num = date('Y');
	$week_num = date('W');
	
	$currency_shortname = '';
	$currency_sql = "SELECT shortname FROM currency WHERE set_default=1";
	$currency_qry = mysql_query($currency_sql) or die(mysql_error().'abc');
	$currency_res = mysql_fetch_assoc($currency_qry);
	
	//check if there is an existing lunch menu for the week
	$menu_week_sql = "SELECT * FROM menu_lunch WHERE year_for=".$year_num." AND week_no=".$week_num;
	$menu_week_qry = mysql_query($menu_week_sql);
	$menu_week_num = mysql_num_rows($menu_week_qry);
	$menu_week_check_num = $menu_week_num;
	
	$none_all_in = 0;
	
	if($menu_week_num==0){

		//check if there is an existing weekly menu for the year
		$menu_week_sql = "SELECT * FROM menu_lunch WHERE year_for=".$year_num;
		$menu_week_qry = mysql_query($menu_week_sql);
		$menu_week_num = mysql_num_rows($menu_week_qry);
		
		if($menu_week_num>0){
			$menu_week_sql = "SELECT * FROM menu_lunch WHERE year_for<=".$year_num." AND week_no<=".$week_num." ORDER BY year_for DESC, week_no DESC LIMIT 1";
			$menu_week_qry = mysql_query($menu_week_sql);
			$menu_week_num = mysql_num_rows($menu_week_qry);	
		}else{
			//get the most recent weekly menu
			$menu_week_sql = "SELECT * FROM menu_lunch WHERE year_for<=".$year_num." ORDER BY year_for DESC, week_no DESC LIMIT 1";
			$menu_week_qry = mysql_query($menu_week_sql);
			$menu_week_num = mysql_num_rows($menu_week_qry);				
		}
		
		$none_all_in=1;
		
	}
	
	$menu_week_res = mysql_fetch_assoc($menu_week_qry);

	$next_week_flag = ( (int)date('N') * 100) + (int)date('G'); 
	               //         (5 * 100) + 16  = 516
				   //         Saturday    4pm 
	
	//echo ' '.$next_week_flag;
	
	$nextweek = 0;
	
	if($next_week_flag>=516){
	//if(date('N')>=5 && date('H')>=16){
	//if(date('N')==1){	//for testing purposes only
		
		//echo 'Check: Verify if next week switch is needed.';
		
		$nextweek = 1;
		
		//check for the last menu item for saturday and sunday
		$n_days = array(6=>'sat', 7=>'sun');
		foreach($n_days as $d_n => $day_txt){
		
			if(date('N')<=$d_n){
			
				$courses_sql = "SELECT * FROM menu_lunch_items 
								WHERE menu_id=".$menu_week_res['id']."
								".$all_in_switch."
								AND specific_day='".$day_txt."' 
								AND deleted=0
								LIMIT 1";
				$courses_qry = mysql_query($courses_sql);
				$num_courses = mysql_num_rows($courses_qry);
				
				//echo '<br>'.$courses_sql;
							
				//if($num_courses>0 && (int)date('H')<16){
				if($num_courses>0){
					$nextweek = 0;
					//echo '<br>Break Free!';
					break;
				}
			
			}
			
			//if it's sunday and time is beyond 4pm still break for next week
			if(date('N')==7 && date('G')>=16){
				$nextweek = 1;
			}

		}
	}
	
	//echo ' Next Week: '.$nextweek;
	
	if($nextweek==1){

		//echo '<br>Next Week!';

		$year_num = date('Y', strtotime('monday this week'));
		$week_num = date('W', strtotime('monday this week'));
		
		$currency_sql = "SELECT shortname FROM currency WHERE set_default=1";
		$currency_qry = mysql_query($currency_sql) or die(mysql_error().'abc');
		$currency_res = mysql_fetch_assoc($currency_qry);
		
		//check if there is an existing lunch menu for the week
		$menu_week_sql = "SELECT * FROM menu_lunch WHERE year_for=".$year_num." AND week_no=".$week_num;
		$menu_week_qry = mysql_query($menu_week_sql);
		$menu_week_num = mysql_num_rows($menu_week_qry);
		$menu_week_check_num = $menu_week_num;
		
		$none_all_in = 0;
		
		if($menu_week_num==0){
	
			//check if there is an existing weekly menu for the year
			$menu_week_sql = "SELECT * FROM menu_lunch WHERE year_for=".$year_num;
			$menu_week_qry = mysql_query($menu_week_sql);
			$menu_week_num = mysql_num_rows($menu_week_qry);
			
			if($menu_week_num>0){
				$menu_week_sql = "SELECT * FROM menu_lunch WHERE year_for<=".$year_num." AND week_no<=".$week_num." ORDER BY year_for DESC, week_no DESC LIMIT 1";
				$menu_week_qry = mysql_query($menu_week_sql);
				$menu_week_num = mysql_num_rows($menu_week_qry);	
			}else{
				//get the most recent weekly menu
				$menu_week_sql = "SELECT * FROM menu_lunch WHERE year_for<=".$year_num." ORDER BY year_for DESC, week_no DESC LIMIT 1";
				$menu_week_qry = mysql_query($menu_week_sql);
				$menu_week_num = mysql_num_rows($menu_week_qry);				
			}
			
			$none_all_in=1;
			
		}
			
		$menu_week_res = mysql_fetch_assoc($menu_week_qry);

	}

?>

<style>
#text-lunchmeny h5 strong div { background-color: rgba(0,0,0,0) !important; }
</style>

    <div id="text-lunchmeny">
                  
        <h4 class="widget-title widgettitle"><span class="left_background"><span class="right_background">VECKANS Lunch</span></span></h4>
        
        <span class="weeknum">Vecka <?php echo $week_num; ?></span>    
    
        <h5>
          <strong><?php echo stripslashes($menu_week_res['note_header']); ?></strong>
        </h5>
    
        <p><?php echo stripslashes($menu_week_res['description']); ?></p>
    
    </div>
    
    <div style="text-align:right; padding:0px;">
     
     <input type="hidden" id="menu_parameter" value="<?php echo 'W '. $year_num.' '.$week_num; ?>" /> 
    
    
        
    </div>
    
        <?php
		$expired=0;
		
		//echo date('Y-m-d H:i:s');
				
		//Friday 4PM expiration
		if(date('N')>=5 && date('G')>=16){
			$expired=1;
		}

        if($none_all_in==0){
			$all_in_switch = '';
		}else{
			$all_in_switch = ' AND all_in=1 ';	
		}
	
		$courses_sql = "SELECT * FROM menu_lunch_items WHERE 
						menu_id=".$menu_week_res['id']."
						".$all_in_switch."
						AND specific_day IS NULL 
						AND deleted=0
						ORDER BY LENGTH(`order`),`order`, id ASC";
		$courses_qry = mysql_query($courses_sql);// or die($courses_sql);
		$courses_num = mysql_num_rows($courses_qry);	

        ?>
        
        <div class="themenu-outer">
        	<?php if($courses_num>0 && $expired==0){ ?>
            
            	<div class="thesepa"></div>
            
                <div class="themenu-container">

					<!-- LM Header (mon-fri) -->
                    
                    <?php
					$header_monfri_sql = "SELECT * FROM menu_lunch_hf 
									WHERE menu_id=".$menu_week_res['id']."
									AND `show`=1 AND content_type='header' AND specific_day IS NULL 
									".$all_in_switch;
					$header_monfri_qry = mysql_query($header_monfri_sql);
					$header_monfri = mysql_fetch_assoc($header_monfri_qry);
					
					if($header_monfri['id'] > 0 && $header_monfri['contents']!=''){
					?>	
                    <div class="themenu-inner">
                    	<?php echo stripslashes($header_monfri['contents']); ?>
                    </div>
                    <?php	
					}

					if($courses_num>0){
						
						$counts=1;
						
						while($courses_res = mysql_fetch_assoc($courses_qry)){
							
							$menu_price = ''; //clear-up course price text
							$menu_details = '';
							$menu_description = '';
							$replacement = '';
							
							$lunchprice = $courses_res['takeaway_price'];
					?>
                        <div class="themenu-inner">
                        <div class="left-pos">
                            <h3><?php echo $courses_res['name']; ?></h3>
                            <p class="testp<?php echo $counts; ?>">
							<?php 
								//get menu price
								if($courses_res['price']!=0){
									$menu_price = number_format($courses_res['price']).' '.$currency_res['shortname'];						        
								}
							
								echo '<div class="testp'.$counts.'"><p>' .stripslashes($courses_res['description']).'</p></div>';
							?>
							<style type="text/css">
                                .testp<?php echo $counts; ?> p:nth-last-child(2):after{
                                    content: ' <?php echo $menu_price; ?>';
                                }
								
								@media only screen and (max-width: 568px) {
									.testp<?php echo $counts; ?> p:nth-last-child(2):after{
                                    	content: '';
                                	}
								}
                            </style>
                             <?php ?> 
                            </p>
                            <span style="display:none" class="lmsubtotal-<?php echo $courses_res['id'];?>" data-rel="<?php echo $lunchprice; ?>"></span>
                        </div>
                            
                            <?php if($courses_res['takeaway']==1 & $lunchprice>0){ ?>
                            <div class="right-pos">
                                <div class="menyadd">
                                    <span class="menyoutputs">
                            		<strong class="mobile-price"><?php  echo $menu_price == '' ? $lunchprice : $menu_price; ?></strong>
                                    <img src="<?php echo CHILD_URL ?>/images/Lagg-till.png" class="menyimg lmcart" data-id="<?php echo $courses_res['id'];?>" data-title="<?php echo $menu_price; ?>">
                                                             
                                    </span>
                                </div>
                            </div>
                            <?php } ?>
                            
                        </div>
                    <?php
						$counts++;
						}
					}
					?>

                    <!-- LM Footer (mon-fri) -->

                    <?php
					$footer_monfri_sql = "SELECT * FROM menu_lunch_hf 
									WHERE menu_id=".$menu_week_res['id']."
									AND `show`=1 AND content_type='footer' AND specific_day IS NULL 
									".$all_in_switch;
					$footer_monfri_qry = mysql_query($footer_monfri_sql);
					$footer_monfri = mysql_fetch_assoc($footer_monfri_qry);
					
					if($footer_monfri['id'] > 0 && strip_tags($footer_monfri['contents'])!=''){
					?>	

                 	<div class="thesepa"></div>

                    <div class="themenu-inner">
                    	<?php 
							echo stripslashes($footer_monfri['contents']); 
						?>
                    </div>
                    <?php	
					}
					?>

                 </div>

				<?php
				$courses_sql = "SELECT id FROM menu_lunch_items WHERE 
								menu_id=".$menu_week_res['id']."
								".$all_in_switch."
								AND specific_day IS NOT NULL 
								AND deleted=0
								LIMIT 1";
				
				$courses_qry = mysql_query($courses_sql);
				$num_courses = mysql_num_rows($courses_qry);

		        if($num_courses==0){
			    ?>
	                 
                 <div class="footerlm"><?php echo stripslashes($menu_week_res['note_footer']); ?></div>

				<?php
			    }
				?>                 
                 <div class="thesepa"></div>
                                 
                <br />
                
             <?php }else{
				 
						echo '<div class="thesepa"></div>';
										
				   } 
			 
			 	$first_day = strtotime('monday this week');
				$last_day = strtotime('sunday this week');
				$the_day = $first_day;
				
				$week_days = 7;
				
				$lunchprice=0;
				
				for($d=1; $d<=$week_days; $d++){

					$expired=1;
					
					//echo '<font style="font-size:2px;">'.date('N').'>='.$d.' && '.date('G').'>=16</font>';
					
					//Expiration 4PM of the same day
					if($d>=date('N')){
					//if(date('N')>=$d){
						$expired=0;
					}

					//if(date('N')>=$d && date('G')>=16){
					//if($d<=date('N') && 16<=date('G')){
					// 1 <= 1   && 16>=16
					if(date('N')>$d){
						$expired=1;
					}elseif(date('N')==$d && date('G')>=16){
						$expired=1;
					}else{
						$expired=0;
					}
				
					
                   
					$courses_sql = "SELECT * FROM menu_lunch_items WHERE 
									menu_id=".$menu_week_res['id']."
									".$all_in_switch."
									AND specific_day='".date('D', $the_day)."' 
									AND deleted=0
									ORDER BY LENGTH(`order`),`order`, id ASC";
			
					$courses_qry = mysql_query($courses_sql);
					$num_courses = mysql_num_rows($courses_qry);
								
			?>
            		<div class="themenu-container" 
                    <?php
					if($d==6){
						//Lordag - Saturday
						echo 'id="lordaglunch"';	
					}
					?>                    
                    >

                        <!-- LM Header (daily) -->

                        <?php
						$header_texts = '';
						
                        $header_sql = "SELECT * FROM menu_lunch_hf 
                                        WHERE menu_id=".$menu_week_res['id']."
                                        AND `show`=1 AND content_type='header' AND specific_day='".date('D', $the_day)."' 
                                        ".$all_in_switch;
                        $header_qry = mysql_query($header_sql);
                        $header = mysql_fetch_assoc($header_qry);
                        
                        if($header['id'] > 0 && $header['contents']!=''){
                        	$header_texts = stripslashes($header['contents']);
						?>
                                                	
                        <div class="themenu-inner">
                            <?php echo $header_texts; ?>
                        </div>
                        <br />
						<?php	
                        }
                  
					if($num_courses>0 && $expired==0){

					?>
                    <div style="text-align:center;font-size: 2rem; font-weight: 400;"><em><?php echo ucwords(thedayName(date('D', $the_day)).'dag'); ?></em></div>
                    <br />
                    <?php
						
						while($courses_res = mysql_fetch_assoc($courses_qry)){
						
							$counts++;
							$menu_price = '';
							
							$lunchprice = $courses_res['takeaway_price'];

							//get menu price
							if($courses_res['price']!=0){
								$menu_price = number_format($courses_res['price']).' '.$currency_res['shortname'];						        
							}

					?>
                        <div class="themenu-inner">
                        <div class="left-pos">
                            <h3><?php echo $courses_res['name']; ?></h3>
                            <p class="testp<?php echo $counts; ?>">
							<?php 
								//get menu price
								if($courses_res['price']!=0){
									$menu_price = number_format($courses_res['price']).' '.$currency_res['shortname'];						        
								}
							
								echo '<div class="testp'.$counts.'"><p>' .stripslashes($courses_res['description']).'</p></div>';
								
								$child=2;
								if(trim($courses_res['description'])==''){
									$child=1;
								}
							?>
							<style type="text/css">
                                .testp<?php echo $counts; ?> p:nth-last-child(<?php echo $child; ?>):after{
                                    content: ' <?php echo $menu_price; ?>';
                                }
								<?php
								if($child==1){
								?>
								.testp<?php echo $counts; ?> {
									text-align:center !important;									
								}								
								<?php	
								}
								?>
								@media only screen and (max-width: 568px) {
									.testp<?php echo $counts; ?> p:nth-last-child(<?php echo $child; ?>):after{
                                    	content: '';
                                	}
								}
                            </style>
                             <?php ?> 
                            </p>
                            <span style="display:none" class="lmsubtotal-<?php echo $courses_res['id'];?>" data-rel="<?php echo $lunchprice; ?>"></span>
                            </div>
                            
                            <?php if($courses_res['takeaway']==1 & $lunchprice>0){ ?>
                            <div class="right-pos">
                                <div class="menyadd">
                                    <span class="menyoutputs">
                            		<strong class="mobile-price"><?php  echo $menu_price == '' ? $lunchprice : $menu_price; ?></strong>
                                    <img src="<?php echo CHILD_URL ?>/images/Lagg-till.png" class="menyimg lmcart" data-id="<?php echo $courses_res['id'];?>" data-title="<?php echo $menu_price; ?>">
                                    
                            
                                    </span>
                                </div>
                            </div>
                            <?php } ?>
                                
                        </div>
                    <?php
						}

				} //if there are menu items to show

						$footer_texts = '';
						
                        $footer_sql = "SELECT * FROM menu_lunch_hf 
                                        WHERE menu_id=".$menu_week_res['id']."
                                        AND `show`=1 AND content_type='footer' AND specific_day='".date('D', $the_day)."' 
                                        ".$all_in_switch;
                        $footer_qry = mysql_query($footer_sql);
                        $footer = mysql_fetch_assoc($footer_qry);
                        
                        if($footer['id'] > 0 && $footer['contents']!=''){
                        	$footer_texts = stripslashes($footer['contents']);
						?>	
                        <div class="themenu-inner">
                            <?php echo $footer_texts; ?>
                        </div>
						<?php
						}
						?>

                 </div>

				 <?php
                 if($num_courses>0 && $expired==0){
 		                echo '<div class="thesepa"></div>';
				 }
				 ?>
             
              <?php
					$the_day = strtotime(date('Y-m-d', $the_day).' +1 day');
				}	
	
				$courses_sql = "SELECT id FROM menu_lunch_items WHERE 
								menu_id=".$menu_week_res['id']."
								".$all_in_switch."
								AND specific_day IS NOT NULL 
								AND deleted=0
								LIMIT 1";
				
				$courses_qry = mysql_query($courses_sql);
				$num_courses = mysql_num_rows($courses_qry);

		       if($num_courses>0){
			  ?>
	                <div class="footerlm"><?php echo stripslashes($menu_week_res['note_footer']); ?></div>
	
			 <?php
			   }
        	 ?>
             
      </div>
             	
<?php
	
}

function script_lunchmeny() {
?>
	<script type="text/javascript">




	
	var siteurl = $('.takeaway_lunch_cart_wrap').attr('data-rel');
	function updateQuantitylunch(val,id,menu_id, tillval_count, menu_det, price)
	{	
		$('#takeaway-lunch .lunch_cart_content').html('<center><img src="'+siteurl+'/images/loader.gif'+'"></center>');
		$.ajax({
			url: siteurl+"/update-cart.php",
			type: 'POST',
			data: 'id='+encodeURIComponent(id)+'&val='+encodeURIComponent(val)+'&menu_id='+encodeURIComponent(menu_id)+'&uniq='+encodeURIComponent(jQuery.cookie('lunchmeny_id')),
			success: function(value){
				$('#takeaway-lunch .lunch_cart_content').load(siteurl+"/lunch-cart.php");
				
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

	$(function(){
		$(document).find('.quantity-lunch').numeric();
		$(document).on('blur', '.quantity-lunch', function(){
			var id = $(this).attr('data-id');
			var menu_id = $(this).attr('data-rel');
			var val = $(this).val();
			updateQuantitylunch(val,id,menu_id, 0, 0, 0);
		});

		$(document).on('keypress', '.quantity-lunch', function(e) {
			if(e.which == 13)
			{
				var id = $(this).attr('data-id');
				var menu_id = $(this).attr('data-rel');
				var val = $(this).val();
				updateQuantitylunch(val,id,menu_id, 0, 0, 0);
			}
		});
		
		$(document).on('click', '.addme_lunch', function(){
			var id = $(this).attr('data-id');
			var menu_id = $(this).attr('data-rel');
			var val = Number($('.lmquantity-'+id).val());
			var tillval_count = $(this).attr('data-tillvals');
			var menu_det = $('.btn_optional_dish-'+id).attr('data-rel');
			var price = $('.btn_optional_dish-'+id).attr('data-price');
			val+=1;
			updateQuantitylunch(val,id,menu_id, tillval_count, menu_det, price);
		});
		
		$(document).on('click', '.subtractme_lunch', function(){
			var id = $(this).attr('data-id');
			var menu_id = $(this).attr('data-rel');
			var val = Number($('.lmquantity-'+id).val());
			val-=1;
			if(val==0){
				val=0;
			}
			updateQuantitylunch(val,id,menu_id, 0, 0, 0);
		});

		//for lunchmeny
		$('.addme_lunch_old').click(function(){
			alert('a');
			var theclass = $(this).attr('data-rel');
			var val = Number($('.lmquantity-'+theclass).val());
			
			val = val+1;
			
			$('.lmquantity-'+theclass).val(val);
		   	var price = Number($('.lmsubtotal-'+theclass).attr('data-rel'));
		   	$('.lmsubtotal-'+theclass).html((val*price));
		   	//computeTotal();
			
		}); 
		
		$('.subtractme_lunch_old').click(function(){
			var theclass = $(this).attr('data-rel');
			var val = Number($('.lmquantity-'+theclass).val());
			
			val = val-1;
			
			if(val<=0){
				val=0;
			}
			
			$('.lmquantity-'+theclass).val(val);
			var price = Number($('.lmsubtotal-'+theclass).attr('data-rel'));
		   	$('.lmsubtotal-'+theclass).html((val*price));
		   	//computeTotal();
			
		}); 
	});
	</script>
<?php
}
