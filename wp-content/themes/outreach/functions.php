<?php

/** Start the engine */
require_once( get_template_directory() . '/lib/init.php' );

include('ajax/manage-users.php');

include_once('shortcodes/takeaway.php');
add_shortcode( 'takeaway', 'takeAway' );
add_action('wp_footer', 'script_takeaway');

include_once('shortcodes/lunchmeny.php');
add_shortcode( 'lunchmeny', 'lunchmeny' );
add_action('wp_footer', 'script_lunchmeny');

/** Child theme (do not remove) */
define( 'CHILD_THEME_NAME', 'Outreach Theme' );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/themes/outreach' );

/** Set Localization (do not remove) */
load_child_theme_textdomain( 'outreach', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'outreach' ) );

/** Sets Content Width */
$content_width = apply_filters( 'content_width', 650, 450, 960 );

/** Load Google fonts */
add_action( 'wp_enqueue_scripts', 'outreach_load_google_fonts' );
function outreach_load_google_fonts() {
    wp_enqueue_style( 
    	'google-fonts', 
    	'http://fonts.googleapis.com/css?family=Lato', 
    	array(), 
    	PARENT_THEME_VERSION 
    );
}

/** Add Viewport meta tag for mobile browsers */
add_action( 'genesis_meta', 'outreach_add_viewport_meta_tag' );
function outreach_add_viewport_meta_tag() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
}

/** Add new image sizes */
add_image_size( 'featured', 1040, 400, TRUE );
add_image_size( 'home-sections', 215, 140, TRUE );
add_image_size( 'sidebar', 300, 150, TRUE );

/** Create additional color style options */
add_theme_support( 'genesis-style-selector', array(
	'outreach-blue' 	=>	__( 'Blue', 'outreach' ),
	'outreach-orange' 	=> 	__( 'Orange', 'outreach' ),
	'outreach-red' 		=> 	__( 'Red', 'outreach' ) 
) );

/** Add support for custom background */
add_custom_background();

/** Add support for custom header */
add_theme_support( 'genesis-custom-header', array(
	'width' 	=> 	1060,
	'height' 	=> 	120
) );

/** Add support for structural wraps */
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'inner',
	'footer-widgets',
	'footer'
) );

/** Reposition the secondary navigation */
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_before_content_sidebar_wrap', 'genesis_do_subnav' );

/** Modify the size of the Gravatar in the author box */
add_filter( 'genesis_author_box_gravatar_size', 'outreach_author_box_gravatar_size' );
function outreach_author_box_gravatar_size( $size ) {
    return '80';
}

/** Add the sub footer section */
add_action( 'genesis_before_footer', 'outreach_sub_footer', 5 );
function outreach_sub_footer() {
	if ( is_active_sidebar( 'sub-footer-left' ) || is_active_sidebar( 'sub-footer-right' ) ) {
		echo '<div id="sub-footer"><div class="wrap">';
		
		   genesis_widget_area( 'sub-footer-left', array(
		       'before' => '<div class="sub-footer-left">'
		   ) );
	
		   genesis_widget_area( 'sub-footer-right', array(
		       'before' => '<div class="sub-footer-right">'
		   ) );
	
		echo '</div><!-- end .wrap --></div><!-- end #sub-footer -->';	
	}
}

/** Add support for 4-column footer widgets */
//add_theme_support( 'genesis-footer-widgets', 4 );

/** Customizes go to top text */
add_filter( 'genesis_footer_backtotop_text', 'footer_backtotop_filter' );
function footer_backtotop_filter($backtotop) {
	return '[footer_backtotop text="' . __('Top' , 'outreach' ) . '"]';
}

/** Register widget areas */
/*
genesis_register_sidebar( array(
	'id'				=> 'boka',
	'name'			=> __( 'Boka', 'outreach' ),
	'description'	=> __( 'This is the lordagslunch section.', 'outreach' )
) );
genesis_register_sidebar( array(
	'id'				=> 'omoss',
	'name'			=> __( 'omoss', 'outreach' ),
	'description'	=> __( 'This is the lordagslunch section.', 'outreach' )
) );
 genesis_register_sidebar( array(
	'id'				=> 'lordagslunch',
	'name'			=> __( 'lordagslunch', 'outreach' ),
	'description'	=> __( 'This is the lordagslunch section.', 'outreach' )
) ); 
genesis_register_sidebar( array(
	'id'				=> 'bgimg3',
	'name'			=> __( 'bgimg3', 'outreach' ),
	'description'	=> __( 'This is the meny section.', 'outreach' )
) );
genesis_register_sidebar( array(
	'id'				=> 'bgimg4',
	'name'			=> __( 'bgimg4', 'outreach' ),
	'description'	=> __( 'This is the meny section.', 'outreach' )
) );
genesis_register_sidebar( array(
	'id'				=> 'bgimg5',
	'name'			=> __( 'bgimg5', 'outreach' ),
	'description'	=> __( 'This is the meny section.', 'outreach' )
) );
 genesis_register_sidebar( array(
	'id'				=> 'veckanslunch',
	'name'			=> __( 'veckanslunch', 'outreach' ),
	'description'	=> __( 'This is the veckanslunch section.', 'outreach' )
) ); 



genesis_register_sidebar( array(
	'id'				=> 'bgimg2',
	'name'			=> __( 'bgimg2', 'outreach' ),
	'description'	=> __( 'This is the meny section.', 'outreach' )
) );
genesis_register_sidebar( array(
	'id'				=> 'catering',
	'name'			=> __( 'Catering', 'outreach' ),
	'description'	=> __( 'This is the meny section.', 'outreach' )
) );

genesis_register_sidebar( array(
	'id'				=> 'home-featured',
	'name'			=> __( 'Home Featured', 'outreach' ),
	'description'	=> __( 'This is the home featured section.', 'outreach' )
) );


genesis_register_sidebar( array(
	'id'				=> 'meny',
	'name'			=> __( 'Meny', 'outreach' ),
	'description'	=> __( 'This is the meny section.', 'outreach' )
) );
genesis_register_sidebar( array(quantity-19
	'id'				=> 'bgimg1',
	'name'			=> __( 'bgimg1', 'outreach' ),
	'description'	=> __( 'This is the meny section.', 'outreach' )
) );
*/

genesis_register_sidebar( array(
	'id'				=> 'hem',
	'name'			=> __( 'Hem', 'outreach' ),
	'description'	=> __( 'This is the hem section.', 'outreach' )
) );
genesis_register_sidebar( array(
	'id'				=> 'avhamtningsmeny',
	'name'			=> __( 'Avhamtningsmeny', 'outreach' ),
	'description'	=> __( 'This is the avhamtningsmeny section.', 'outreach' )
) );
genesis_register_sidebar( array(
	'id'				=> 'lunchmeny',
	'name'			=> __( 'Lunchmeny', 'outreach' ),
	'description'	=> __( 'This is the lunchmeny section.', 'outreach' )
) );

/*
genesis_register_sidebar( array(
	'id'				=> 'serveringsmeny',
	'name'			=> __( 'Serveringsmeny', 'outreach' ),
	'description'	=> __( 'This is the serveringsmeny section.', 'outreach' )
) );
genesis_register_sidebar( array(
	'id'				=> 'vinlista',
	'name'			=> __( 'Vinlista', 'outreach' ),
	'description'	=> __( 'This is the vinlista section.', 'outreach' )
) );
*/
genesis_register_sidebar( array(
	'id'				=> 'kontakta-oss',
	'name'			=> __( 'Kontakta Oss', 'outreach' ),
	'description'	=> __( 'This is the kontakta oss section.', 'outreach' )
) );
/*
genesis_register_sidebar( array(
	'id'				=> 'bgimg6',
	'name'			=> __( 'bgimg6', 'outreach' ),
	'description'	=> __( 'This is the meny section.', 'outreach' )
) );
genesis_register_sidebar( array(
	'id'				=> 'sub-footer-left',
	'name'			=> __( 'Sub Footer Left', 'outreach' ),
	'description'	=> __( 'This is the sub footer left section.', 'outreach' )
) );
genesis_register_sidebar( array(
	'id'				=> 'sub-footer-right',
	'name'			=> __( 'Sub Footer Right', 'outreach' ),
	'description'	=> __( 'This is the sub footer right section.', 'outreach' )
) );
*/


//added scripts for header

add_action("genesis_meta","add_script");
function add_script(){
?>

<script src="<?php echo CHILD_URL; ?>/js/jquery-1.9.1.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
<script type="text/javascript" src="<?php echo CHILD_URL ?>/js/jquery-ui.css"></script>
<script type="text/javascript" src="<?php echo CHILD_URL; ?>/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo CHILD_URL; ?>/js/jquery.parallax-1.1.3.js"></script>
<script type="text/javascript" src="<?php echo CHILD_URL; ?>/js/scrollspy.js"></script>
<script type="text/javascript" src="<?php echo CHILD_URL; ?>/js/jquery.jcarousel.min.js"></script>
<script type="text/javascript" src="<?php echo CHILD_URL; ?>/js/jcarousel.skeleton.js"></script>
<script type="text/javascript" src="<?php echo CHILD_URL ?>/js/numeric.js"></script>


<script type="text/javascript" src="<?php echo CHILD_URL ?>/js/jquery.ui.datepicker-sv.js"></script>
<script type="text/javascript" src="<?php echo CHILD_URL ?>/js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="<?php echo CHILD_URL ?>/js/jquery-ui-sliderAccess.js"></script>
<script type="text/javascript" src="<?php echo CHILD_URL ?>/js/jquery-ui-timepicker-sv.js"></script>


<script type="text/javascript">
jQuery(function(){
	var webmin = "<?php echo site_url();?>/webmin";
	jQuery('#footer a:last').attr('href', webmin);
	jQuery('#menu-primary_menu li:nth-child(1) a').addClass('li-active');
	
	jQuery('#menu-primary_menu li a').click(function(){
		jQuery(this).addClass('li-active');
	});
	
	//TAKE IMG EFFECTS
	jQuery('.takeimg').bind("touchstart mousedown", function(e){
        jQuery(this).css({'opacity':'0.4','filter':'alpha(opacity=40)'});
	});
	
	jQuery('.takeimg').bind("touchend mouseup touchrelease mouseout", function(e){
			jQuery(this).css({'opacity':'1','filter':'alpha(opacity=1)'});
	});
	//END TAKE IMG EFFECTS
	
	setTransitionByWidth();
	<?php if ( ! wp_is_mobile() ) { ?>
		setScrollingEffects();
	<?php }else{ ?>
		console.log('mobile');	
	<?php } ?>
	
	var widthhh = 0;
	jQuery(window).load(function(){
	   widthhh = jQuery(window).width();
	   //togller(widthhh);
	});
	
	$(window).resize(function(){
		widthhh = window.innerWidth;
		//togller(widthhh);
	});
	
	$('.toggle_cart').click(function(){
		var dis = $('.takeaway_cart_wrap').css('display');
		
		if(dis=='block'){
			 $(this).css('background' , '#223A5E url(<?php echo CHILD_URL;?>/images/arr-to-top.png) no-repeat center 5px');
			 if(widthhh <= 568){ $('#takeaway-fader').hide('fast'); }
		}else{
			$(this).css('background' , '#223A5E url(<?php echo CHILD_URL;?>/images/arr-to-bottom.png) no-repeat center 5px');
			if(widthhh <= 568){ $('#takeaway-fader').show('fast'); }
			
		}
		
		$('.takeaway_cart_wrap').slideToggle();

	});
	
	$('#takeaway-fader').on("touchstart",function(event){
		event.preventDefault();
		$('.takeaway_cart_wrap').slideToggle();
		$(this).hide('fast');
	});
	
	$('.close-mobile-cart').on("touchstart",function(event){
		event.preventDefault();
		$('.takeaway_cart_wrap').slideToggle();
	});

	//Lunch Menu Cart
	$('.toggle_cart_lunch').click(function(){
		var dis = $('.takeaway_lunch_cart_wrap').css('display');
		
		if(dis=='block'){
			 $(this).css('background' , '#000 url(<?php echo CHILD_URL;?>/images/arr-to-top.png) no-repeat center 5px');
			 if(widthhh <= 568){ $('#takeaway-lunch-fader').hide('fast'); }
		}else{
			$(this).css('background' , '#000 url(<?php echo CHILD_URL;?>/images/arr-to-bottom.png) no-repeat center 5px');
			if(widthhh <= 568){ $('#takeaway-lunch-fader').show('fast'); }
			
		}
		
		$('.takeaway_lunch_cart_wrap').slideToggle();

	});
	
	$('#takeaway-lunch-fader').on("touchstart",function(event){
		event.preventDefault();
		$('.takeaway_lunch_cart_wrap').slideToggle();
		$(this).hide('fast');
	});
	
	$('.close-mobile-cart-lunch').on("touchstart",function(event){
		event.preventDefault();
		$('.takeaway_lunch_cart_wrap').slideToggle();
	});	
	
});
	
var widthhh = 0;
jQuery(window).load(function(){
   widthhh = jQuery(window).width();
});

/*jQuery(window).resize(function(){
  if(widthhh != jQuery(window).width()){
	 //window.location.reload();
	 setTransitionByWidth();
  }
});*/


function setScrollingEffects(){
	
	var sections = {},
	_height  = jQuery(window).height(),
	thesection = {},
	i = 0;
	
	// Grab positions of our sections 
	jQuery('.scroll-section').each(function(){
		sections[jQuery(this).attr('id')] = jQuery(this).offset().top;
		thesection[jQuery(this).attr('id')] = jQuery(this).attr('id');
		
	});
	
	//add class to menus
	jQuery('#menu-primary_menu li a').each(function(){
		var id = jQuery(this).attr('href').replace('#','');
		jQuery(this).addClass(id);
	});
	
	jQuery(document).scroll(function(){
				var $this = jQuery(this),
					pos   = $this.scrollTop()-410;
					
				for(i in sections){
					if(sections[i] > pos && sections[i] < pos + _height){
						jQuery('#menu-primary_menu li a').removeClass('li-active');
						jQuery('#menu-primary_menu li a.'+thesection[i]).addClass('li-active');
						
					}
				}
	});
	


		
/*	$('#background-2-sections .wrap').parallax("50%", -0.2);
	$('#background-3-sections .wrap').parallax("50%", -0.2);
	$('#background-4-sections .wrap').parallax("50%", -0.2);
	$('#background-5-sections .wrap').parallax("50%", -0.2);
	$('#background-6-sections .wrap').parallax("50%", -0.2);
	$('#background-7-sections .wrap').parallax("50%", -0.1);*/
	
	jQuery("html, body").animate({ scrollTop: 0 }, "fast");
}
jQuery(document).ready(function() {
			
			// ******************* For Valentine update start. *******************
			//jQuery( "#menu-primary_menu" ).append( '<li id="menu-item-vl" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-vl"><input type="button" id="cust-val-btn" class="btn-get-gift-card" value="Köp presentkort"/></li>' );
			// ******************* For Valentine update end. *******************
		
			// ******************* Start Julmeny 2015 *******************
			jQuery('.btn-julmeny, .close-julmeny, .fade-julmeny').click(function(){
				jQuery('.fade-julmeny').toggle();
				jQuery('.julmeny-container').toggle();
			});
			// ******************* End Julmeny 2015 *******************
			
			// ******************* Start Christmass hours 2015 *******************
			jQuery('.btn-christmass-hours, .close-christmass-hours, .fade-christmass-hours').click(function(){
				jQuery('.fade-christmass-hours').toggle();
				jQuery('.christmass-hours-container').toggle();
			});
			// ******************* End Christmass hours 2015 *******************
		
		});
	
	$(function() {
			//gift card
		
			$('.btn-get-gift-card').click(function(){

				$('.giftcard-box .displaymsg').hide();

				$('.gcard-req-fname').val('');

				$('.gcard-req-email').val('');

				$('.gcard-req-lname').val('');

				$('.gcard-req-phone').val('');

				$('.gcard-req-giftnum').val('');

				$('.gcard-req-giftprice').val('');

				$('.gcard-req-postas').val('');

				$('.gcard-req-message').val('');

				$('.fade, .giftcard-box').fadeIn('fast');

			});



			$('.get-card-btn').click(function(){

				var fname = $('.gcard-req-fname').val();

				var email = $('.gcard-req-email').val();

				var lname = $('.gcard-req-lname').val();

				var phone = $('.gcard-req-phone').val();

				var giftnum = $('.gcard-req-giftnum').val();

				var giftprice = $('.gcard-req-giftprice').val();

				var postas = $('.gcard-req-postas').val();

				var message = $('.gcard-req-message').val();



				message = message.replace(/\n/g,"<br />");

				

				$('.giftcard-box .displaymsg').hide();



				$.ajax({

					url: "<?php echo CHILD_URL ?>/get-gift-card.php",

					type: 'POST',

					data: 'fname='+encodeURIComponent(fname)+'&email='+encodeURIComponent(email)+'&lname='+encodeURIComponent(lname)+'&phone='+encodeURIComponent(phone)+'&giftnum='+encodeURIComponent(giftnum)+'&giftprice='+encodeURIComponent(giftprice)+'&postas='+encodeURIComponent(postas)+'&message='+encodeURIComponent(message),

					success: function(value){

						$('.giftcard-box .displaymsg').hide();

						//console.log(value);

							if(value=='emptyfields'){

								$('.giftcard-box .displaymsg').removeClass('successmsg').fadeIn('slow').addClass('errormsg').html('Vänligen fyll i alla obligatoriska fält.');

								

							}else if(value=='ivalidemail'){

								$('.giftcard-box .displaymsg').removeClass('successmsg').fadeIn('slow').addClass('errormsg').html('Ogiltig E-postadress');

							}else if(value=='successful'){

								$('.giftcard-box .displaymsg').fadeIn().removeClass('errormsg').addClass('successmsg').html('Din beställning är mottagen.');

								$('.giftcard-box').delay( 2000 ).fadeOut('slow');

								$('.fade').delay( 2000 ).fadeOut('slow');

							}

					}

				});

				

			});

		 
		});
	

function setTransitionByWidth(){
	var root = jQuery('html, body');
	var _width  = jQuery(window).width();	
	
	if(_width <= 1023 && _width >= 601){
		jQuery('#wprmenu_menu a').click(function(){
			root.animate({
				scrollTop: (jQuery( $.attr(this, 'href') ).offset().top)-223
			}, 1000);
			return false;
		});
	
	}else if(_width <= 600){
		jQuery('#wprmenu_menu a').click(function(){
				root.animate({
					scrollTop: (jQuery( $.attr(this, 'href') ).offset().top)-195
				}, 1000);
				return false;
		});
		
		jQuery('#wprmenu_menu ul li:nth-child(1) a').click(function(){
				root.animate({
					scrollTop: (jQuery( $.attr(this, 'href') ).offset().top)-258
				}, 1000);
				return false;
		});
	}else{
		jQuery('#menu-primary_menu a').click(function(){		
				root.animate({
					scrollTop: (jQuery( $.attr(this, 'href') ).offset().top)-175
				}, 1000);
		return false;
		});
	}
	return false;
}
</script>

<style type="text/css">

@font-face {
	font-family: 'SonySketchEF';
	src: url('<?php echo CHILD_URL;?>/fonts/SonySketchEF.eot');
	src: url('<?php echo CHILD_URL;?>/fonts/SonySketchEF.ttf') format('truetype'),
	url('<?php echo CHILD_URL;?>/fonts/SonySketchEF.woff') format('woff'),
	url('<?php echo CHILD_URL;?>/fonts/SonySketchEF.svg#Orbitron') format('svg'),
	url('<?php echo CHILD_URL;?>/fonts/Merriweather-Bold.eot?#iefix') format('embedded-opentype');
}

@font-face {
	font-family: 'Roboto-Regular';
	src: url('<?php echo CHILD_URL;?>/fonts/Roboto-Regular.eot');
	src: url('<?php echo CHILD_URL;?>/fonts/Roboto-Regular.ttf') format('truetype'),
	url('<?php echo CHILD_URL;?>/fonts/Roboto-Regular.woff') format('woff'),
	url('<?php echo CHILD_URL;?>/fonts/Roboto-Regular.svg#Orbitron') format('svg'),
	url('<?php echo CHILD_URL;?>/fonts/Roboto-Regular-Bold.eot?#iefix') format('embedded-opentype');
}

</style>

<script type="text/javascript">
   
 function validateEmail(email)   
	{  
	 if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))  
		return (true);
	 else  
		return (false);  
 }  
 
 function checktheDay(date,thedays,string,array){
	 
	 var val=thedays.indexOf(date.getDay().toString());
	 
	 if(val!=-1){
		 
		 var str = array.indexOf(string);
		 
		 if(str!=-1)
			 return false;
		 else	 
		 	 return true;
			 
	 }
	 else{
	 	return false;
	 }
 }
$(function() {
	
<?php

         mysql_connect('localhost','root','root');
         mysql_select_db('barkarby_mise');
	 	//$currentDate = date("Y-m-d");
		$currentDate = date("m/d/Y");
	 	$theDates=array();
		
		//get the reservation dates
	 	//$q=mysql_query("select date from reservation where date>='".$currentDate."' and checkout=0 and reservation_type_id=1 group by date") or die(mysql_error());
		
		$q=mysql_query("select date from reservation where DATE_FORMAT(STR_TO_DATE(date, '%m/%d/%Y'), '%Y-%m-%d') >=DATE_FORMAT(STR_TO_DATE('".$currentDate."', '%m/%d/%Y'), '%Y-%m-%d') and checkout=0 and reservation_type_id=1 group by date") or die(mysql_error());
		
		while($r=mysql_fetch_assoc($q)){
			$theDates[]=$r['date'];
		}
		
		$disabledDates='';
		
		
		function getidfromDate($date){
			$dayName=date('D', strtotime($date));
			
			//DATE_FORMAT(STR_TO_DATE('".$currentDate."', '%m/%d/%Y'), '%Y-%m-%d')
			
			$q=mysql_query("select id from restaurant_detail where DATE_FORMAT(STR_TO_DATE('".$date."', '%m/%d/%Y'), '%Y-%m-%d') >= DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') and DATE_FORMAT(STR_TO_DATE('".$date."', '%m/%d/%Y'), '%Y-%m-%d') <= DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') and days like '%".$dayName."%' and deleted=0 order by id desc limit 1");	
				
			//$q=mysql_query("select id from restaurant_detail where '".$date."' >= start_date and '".$date."' <= end_date and days like '%".$dayName."%' and deleted=0 order by id desc limit 1");
			$rs=mysql_fetch_assoc($q);
			
			return $rs['id'];
		}
		
		
		$restaurantdetail_id=getidfromDate($currentDate);
		
		//get reservation_ids
		for($i=0;$i<count($theDates);$i++){
			
			$q=mysql_query("select rt.id from reservation as r, reservation_table as rt where r.id=rt.reservation_id and r.date='".$theDates[$i]."' and r.checkout=0 and r.reservation_type_id=1") or die(mysql_error());
			
			$thecount=mysql_num_rows($q);
			
			$q=mysql_query("select id from table_detail where restaurant_detail_id=".getidfromDate($theDates[$i])) or die(mysql_error());
			$totalcount=mysql_num_rows($q);
			
			if($totalcount==$thecount){
				$disabledDates.='"'.$theDates[$i].'",';
			}
			
			$restaurantdetail_id=getidfromDate($theDates[$i]);
			
		}
	 
	 	if($disabledDates!=''){
			$disabledDates=substr($disabledDates,0,strlen($disabledDates)-1);
		}	
		
		//get the available days...
		
		
		$thedays='';
		$qr=mysql_query("select days, end_date from restaurant_detail where id='".$restaurantdetail_id."'") or die(mysql_error());
		$rq=mysql_fetch_assoc($qr);
		
		$days=explode(',',$rq['days']);	
		$theend_date = $rq['end_date'];
		
		$daynum=array('Mon'=>1,'Tue'=>2,'Wed'=>3,'Thu'=>4,'Fri'=>5,'Sat'=>6,'Sun'=>0);
		
		for($i=0;$i<count($days);$i++){
			$thedays.="'".$daynum[trim($days[$i])]."',";
		}
		$thedays=substr($thedays,0,strlen($thedays)-1);
	 ?>
	 
	 //var array = ['02/14/2014','02/15/2014'];
	 var array = [<?php echo $disabledDates; ?>];
	 var thedays = [<?php echo $thedays; ?>]; 
    
      /*$( "#datepicker" ).datepicker({
	  	  minDate: 0, 
		  maxDate : '<?php //echo (strtotime($theend_date) - strtotime($currentDate)) / (60 * 60 * 24); ?>',
		  firstDay: <?php //echo $row['var_value']; ?>,
		  onSelect: function(selectedDate){
			  $('.restime').val('');
		  },
		  beforeShowDay: function(date){
				var string = $.datepicker.formatDate('mm/dd/yy', date);
				
				return [checktheDay(date,thedays,string,array)];
			
				
		 }
	   });*/
	   
	   
	  
     $('a.time-close').click(function(){
          $('.reservation-fade, .time-container').fadeOut();
      });
	 
	 
	 //getting the number of guest
	 var str_options='<option value="">*Sällskapets Storlek</option>';
	 /*for(i=1;i<=<?php echo $rows['var_value']; ?>;i++){
		str_options+='<option value="'+i+'">'+i+'</option>';
     }*/
	 
	 $('.resguest').html(str_options);
	 
	 
	 $('.resguest').change(function(){
	 	$('.restime').val(''); 
	 });
     $('.restime').click(function(){
		 
		  var num_guest=Number($('.resguest').val());
		  
		  $('.home-middle-3 .displaymsg').fadeOut('slow')
		 
		  if(num_guest>0 & num_guest!=''){
			  
			 
			 $('.reservation-fade, .time-container').fadeIn();
			
			  // var arrDaysName= new Array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
			  
			  var thedate=$("#datepicker").datepicker( 'getDate' );
			  var curDay = new Date(thedate);
			  
			  //var dayName=arrDaysName[curDay.getDay()];
			  var mm=Number(curDay.getMonth()+1);
			  var dd=Number(curDay.getDate());
			  var yyyy=curDay.getFullYear();
			  
			  if(mm<10){
				  mm='0'+mm;
			  }
			  
			  if(dd<10){
				  dd='0'+dd;
			  }
			  
			  var toDay=mm+"/"+dd+"/"+yyyy;
	 
			  $('.time-inner').html('<img src="<?php echo CHILD_URL ?>/images/preloader.gif" style="margin:0 auto;">');	
			  
			  $.ajax({
				 url: "<?php echo CHILD_URL ?>/schedule.php",
				 type: 'POST',
				 data: 'currentDate='+encodeURIComponent(toDay)+'&numguest='+encodeURIComponent(num_guest),
				 success: function(value){
					 
					   $('.time-inner').html(value);
					
				 }
			  });
			  
			 
		  } 
		  else{
			  jQuery('html, body').animate({
					scrollTop: jQuery('#theerr').offset().top-300
				}, 500);
			 $('.home-middle-3 .displaymsg').fadeIn('slow').addClass('errormsg').html('Ange sällskapets storlek.');
		  }
		 
		 
     });
	 
	 //saving the book a table
	 $('.home-middle-3 .reservation-btn').click(function(){
		  
		 
		  var thedate=$("#datepicker").datepicker( 'getDate' );
		  var curDay = new Date(thedate);
		  
		  //var dayName=arrDaysName[curDay.getDay()];
		  var mm=Number(curDay.getMonth()+1);
		  var dd=Number(curDay.getDate());
		  var yyyy=curDay.getFullYear();
		  
		  if(mm<10){
			  mm='0'+mm;
		  }
		  
		  if(dd<10){
			  dd='0'+dd;
		  }
		  
          var toDay=mm+"/"+dd+"/"+yyyy;	
		 
          var time=$('.restime').val();
		  var guest=$('.resguest').val();
		  var email=$('.resemail').val();
		  var notes=$('.resrequest').val();
		  var pword=$('.respassword').val();
		  var res_detail_id=$('.reservation-btn').attr('data-rel');
		  var agree = $('#agree').prop('checked');
		  var check = jQuery('.rememberBok').prop('checked');
		
			if (check) {
				rememberMe(email, pword);
			}
		  
		  $('.home-middle-3 .displaymsg').fadeOut('slow');
		  
		  if(time!='' & guest!='' & email!='' & pword!=''){
			  
			  
			  if(validateEmail(email)){
				  
				  
					 //check account if exists
					 
					 $('#reservation_form .displaymsg').fadeIn().html('<img src="<?php echo CHILD_URL; ?>/images/rectangular.gif" style="margin:0 auto;">').removeClass('errormsg');
					 
					 $.ajax({
						 url: "<?php echo CHILD_URL ?>/checkInfo.php",
						 type: 'POST',
						 data: 'email='+encodeURIComponent(email)+'&pword='+encodeURIComponent(pword),
						 success: function(value){
							 
							 value=value.trim();
							 
					
							 
							 if(value==0){
								$('.home-middle-3 .displaymsg').fadeIn('slow').addClass('errormsg').html('Invalid email address/password.'); 
							 }
							 else if(value=='not'){
								 $('.home-middle-3 .displaymsg').fadeIn('slow').addClass('errormsg').html('Kontrollera din e-post och bekräfta din registrering.'); 
							 }
							 else{
								 
								 
								 if(agree){ 
								 
									//save booking...
									
									var time=$('.restime').attr('data-rel');
									var endtime=$('.restime').attr('data-id');
									
									
									$.ajax({
										 url: "<?php echo CHILD_URL ?>/saveBooking.php",
										 type: 'POST',
										 data: 'date='+encodeURIComponent(toDay)+'&time='+encodeURIComponent(time)+'&guest='+encodeURIComponent(guest)+'&email='+encodeURIComponent(email)+'&notes='+encodeURIComponent(notes)+'&res_detail_id='+encodeURIComponent(res_detail_id)+'&account_id='+encodeURIComponent(value)+'&endtime='+encodeURIComponent(endtime),
										 success: function(value){
											  
										
											
											 $('.success-box .box-content').html('');
											 
											 value=value.trim();
											 
											 if(value=='not'){
												
												 $('.success-box .box-content').html('We regret to inform you that we currently do not have an available table for your reservation.  However, you may contact us so we can accommodate you in a special table just for you.<br><br>Call us at 021-417560.').css('text-align','left');
												
												 
											 }
											 else{
												 
												 val=value.split("**");
												 
												 $('.success-box .box-content').html("Tack för din bokning! Vi har reserverat ett bord för <strong>"+val[3]+"</strong> personer, klockan <strong>"+val[2]+"</strong> den <strong>"+val[1]+"</strong>.<br><br>Hör av dig till oss snarast om du vill göra några ändringar i din bokning på telefonnummer 021-417560. En bekräftelse har även skickats till din e-postadress.<br><br>Välkommen! ").css('text-align','left');
												 
												 
												 
											 }
											 
											 $('.success-box, .fade3').fadeIn();
											 
											 
											 $('#reservation_form .displaymsg').fadeOut().html('').removeClass('errormsg');
											 
											  gotoModal();
											
											
										 }
									  });
								  
								  
								  }
								  else{
									 $('.home-middle-3 .displaymsg').fadeIn('slow').addClass('errormsg').html('Godkänn användarvillkoren för att fortsätta');  
								  } 
								
								
							 }
							
						 }
					  });
				 
			  	  
				  
			  }
			  else{
				 $('.home-middle-3 .displaymsg').fadeIn('slow').addClass('errormsg').html('Invalid email address.'); 
			  }
			  
		  }
		  else{
			  $('.home-middle-3 .displaymsg').fadeIn('slow').addClass('errormsg').html('Fyll i alla obligatoriska fält.');
		  }
		  
     });
	 
	 $('.createaccount-btn, .newuser').click(function(){
		
		$('.tos1').show();
		$('.tos2').hide();
		$('.signup-box .displaymsg').removeClass('errormsg').html('');
		$('.fade2, .signup-box').fadeIn('slow');
		gotoModal();	
	 
	 });
	 
	 
	 
	 $('.modalbox h2 a').click(function(){
		
		if($('.agreement-box').css('display')=='block'){
			$('.fade, .agreement-box, .agreement1-box').fadeOut();
		} 
		else if($('.agreement1-box').css('display')=='block'){
			$('.fade, .agreement1-box').fadeOut();
		}
		else{ 
	 		$('.fade, .modalbox, .fade2, .fade3, .fade4').fadeOut();
		}
	 });
	 
	 $('.resguest').keyup(function(){
		  	var val=this.value;
			
			if(val>8){
				$(this).val('');
			}
	 });
	 
	 
	 // $.ajax({
		// url: "<?php //echo CHILD_URL ?>/steps.php",
		// type: 'POST',
		// success: function(value){
		 
		//    $('#takeaway_form').html(value);
		
		// }
	 // }); 
	 
	 /*$('.existing').click(function(){
		var val=$(this).prop('checked');
		
		$('.hidesecond').hide();
		
		if(val){
			$('.hidefirst').fadeIn();
			$('.createaccount-btn').hide();
		}
		else{
			$('.hidefirst').hide();
			$('.createaccount-btn').fadeIn();
		}
		
	 });
	 
	 $('.newuser').click(function(){
		 
		var val=$(this).prop('checked');
		
		$('.hidefirst').hide();
		
	 });*/
	 
	 
	 $('#menu-item-26 a').click(function(){
		 $('#takeaway_form').hide();
	 	 $('#reservation_form').fadeIn();
		 $('#book_button, #take_away').removeClass('the-active');
		 $('#book_button').addClass('the-active');
		 
		 $('#menu-primary_menu li a').removeClass('li-active');
		 
		 $(this).addClass('li-active');
	 });
	 
	 $('#menu-item-27 a').click(function(){
		 // $('#reservation_form').hide();
		 // $('#takeaway_form').fadeIn();
		 // $('#book_button, #take_away').removeClass('the-active');
		 // $('#take_away').addClass('the-active');
		 
		 // $('#menu-primary_menu li a').removeClass('li-active');
		 
		 // $(this).addClass('li-active');
	 });
	 
	 
	 $('.agreelabel').click(function(){
		 
		 
			 $('.fade, .agreement-box').fadeIn();
			 gotoModal();
			 
	
	 });
	 
	 
	 /*$('.forgot').click(function(){
	    $('.forgot-box').removeClass('errormsg').html();   
	 	$('.fade, .forgot-box').fadeIn();
		gotoModal();
	 
	 });
	 
	 $('.forgot-box .btn').click(function(){
	 	var email=$('.forgotemail').val();
		
		$('.forgot-box .displaymsg').fadeOut();
		
		if(email!=''){
			
			if(validateEmail(email)){
				
				$('.forgot-box .displaymsg').fadeIn().html('<img src="<?php //echo CHILD_URL; ?>/images/rectangular.gif" style="margin:0 auto;">').removeClass('errormsg');
				
				$.ajax({
					url: "<?php //echo CHILD_URL ?>/forgot-password.php",
					type: 'POST',
					data: 'email='+encodeURIComponent(email),
					success: function(value){
					 	if(value!='not'){
					   		$('.forgot-box .displaymsg').fadeIn().removeClass('errormsg').addClass('successmsg').html('Ditt lösenord har nu skickats - kontrollera din inkorg.');	
						}
						else{
							$('.forgot-box .displaymsg').fadeIn().addClass('errormsg').html('E inte erkänns.');
						}
					}
				 }); 
				
				
			}
			else{
				$('.forgot-box .displaymsg').fadeIn().addClass('errormsg').html('Den angivna e-postadressen är inte registrerad.');		
			}
			
		}
		else{
			$('.forgot-box .displaymsg').fadeIn().addClass('errormsg').html('E-postadress krävs.');	
		}
		
	 });*/
	 
	 
	 if ($(window).width() < 1024) {
	   $('#menu-primary_menu a').click(function(){
	   		$('#menu-primary_menu').hide();
	   });
	   
	   $('.menu-primary_menu-container').click(function(){
		  	$('#menu-primary_menu').show();
	   });
	 }
	
 
  });
  
function gotoModal(){
	
	jQuery.fn.center = function ()
	{
		this.css("position","fixed");
		// this.css("top", ((jQuery(window).height() / 2) - (this.outerHeight() / 2))+50);
		this.css("top", "80px");
		//this.css("left", ($(window).width() / 2) - (this.outerWidth() / 2));
		return this;
	}
	
	jQuery('.modalbox').center();
	
	
	}
	
	function getPanic(){
					 
		 var val='';
		 
		 $.ajax({
				url: "<?php echo CHILD_URL ?>/getPanic.php",
				type: 'POST',
				async: false,
				success: function(value){
				
					val=value;
					
				}
		});	
		
		return val;
		 
	 }
  
  
</script>
<script type="text/javascript" src="<?php echo CHILD_URL ?>/scripts/numeric.js"></script>
<script type="text/javascript">
  $(function(){
       $('.resguest, .restable, .quantity, .packagequantity').numeric();
	   
	   var d = new Date();
		var hour = d.getHours();     
		var mins = d.getMinutes();
		
		//firstDay: <?php //echo $row['var_value']; ?>,
	    $( "#datepicker3" ).datetimepicker({
			minDate: 0,
			addSliderAccess: true,
			sliderAccessArgs: { touchonly: false },
			hour: hour+1,
			minute: mins
		});
		
		
		$( "#datepicker3" ).focusin(function(){
		
			$('.ui-datepicker-current').click(function(){
				theCurrentDate();
			});
			
		});
		
		$( "#datepicker3" ).click(function(){
		
			$('.ui-datepicker-current').click(function(){
				theCurrentDate();
			});
			
		});
		
		$('.ctlabel').click(function(){
			$('#datepicker3').datepicker('show');
		});
		
		
		 $('.agreelabel2').click(function(){
	
			 $('.fade, .agreement2-box').fadeIn();
			 gotoModal();
			 
	    });
		
		$('.submitcatering').click(function(){
			//alert();
		});
		if ($(window).width() <= 480) {
			$('a[href*=#]').on('click', function(event){     
				event.preventDefault();
				$('html,body').animate({scrollTop:($(this.hash).offset().top)-86}, 500);
			});
			
			$("#inputs input[type=text]").val(1);
		}
  });
  
  function theCurrentDate(){
		 $('#datepicker3').datepicker('hide');
		 var val=$('#datepicker3').val();
		 $('#datepicker3').val('Tid för avhämtning mailas till dig');
		 $('#datepicker3').attr('data-rel',val);
		 $('#datepicker3').blur();
  }
</script>


<?php	
}

//end added scripts



/**
 * Filter the genesis_seo_site_title function to use an image for the logo instead of a background image
 * 
 * The genesis_seo_site_title function is located in genesis/lib/structure/header.php
 * @link http://blackhillswebworks.com/?p=4144
 *
 */

	add_filter( 'genesis_seo_title', 'bhww_filter_genesis_seo_site_title', 10, 2 );

	function bhww_filter_genesis_seo_site_title( $title, $inside ){
	 
		$child_inside = sprintf( '<a href="%s" title="%s"><img  style="width: 130px;" src="'. get_stylesheet_directory_uri() .'/images/logo.png" title="%s" alt="%s"/></a> <span>Grekiska kolgrillsbaren</span>', trailingslashit( home_url() ), esc_attr( get_bloginfo( 'name' ) ), esc_attr( get_bloginfo( 'name' ) ), esc_attr( get_bloginfo( 'name' ) ) );
	 
		$title = str_replace( $inside, $child_inside, $title );
	 
		return $title;
		
	}
	
	/** 
 * Remove the site description 
 * 
 * @link http://www.briangardner.com/code/remove-header-elements/
 */
 
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

//*** E2F Custom Content ***//

function e2f_cc($atts){
	
	ob_start();
	$atts = shortcode_atts(
			array(
				'code' => '',
			), $atts, 'e2f_cc' );

	if(trim($atts['code'])!=''){
		
		$qry1 = "SELECT content FROM custom_content WHERE short_code='".$atts['code']."' AND visibility = 1";
		$res1 = mysql_query($qry1) or die("Error " . mysql_error());
		$row1 = mysql_fetch_array($res1);
		
		echo $row1['content'];
	}
	
	return ob_get_clean();
}
add_shortcode( 'E2F_CC', 'e2f_cc' );
add_filter('widget_text', 'do_shortcode');

//*******************MENU SHORTCODE*******************//

function menus($atts){
	ob_start();
	
	
	extract( shortcode_atts( array(
			'for' => ''
	), $atts ) );

	if($for=='take-out'){
	$cattable = 'category';
	$subcattable = 'sub_category';
	$menucattable= 'menu';
	$cartname = 'tacart';
	$ttype = 2;
    do_shortcode('[takeaway]');	
	}else if($for == 'lunchmeny'){
    $ttype =0;
    do_shortcode('[lunchmeny]');
	}else{
		$cattable = 'breakfast_category';
		$subcattable = 'breakfast_sub_category';
		$menucattable= 'breakfast_menu';
		$cartname = 'bfcart';
		$ttype = 3;
	}

	
	/*$qs=mysql_query("select var_value from settings where var_name='takeaway_content'");
	$rs=mysql_fetch_assoc($qs);
	
	echo $rs['var_value'];*/

?>
<div style="overflow:hidden; padding-top:0px;">
<!--START-->
<?php	
if($ttype == 2){
	//GET CATEGORIES
	$qry1 = "Select * from $cattable where deleted = '0' and id = '1' order by length(`order`), `order` asc";
	$res1 = mysql_query($qry1) or die("Error " . mysql_error());
	$row1 = mysql_fetch_assoc($res1);
	
	$cat_id = $row1['id'];
	$cat_name = $row1['name'];
	$cat_decs = $row1['description'];
	$newcat_desc = '';
	$newcatdesc = $cat_decs;
	
	if(is_numeric(strpos($cat_decs,'<p'))){
		$newcat_desc = $cat_decs;
		$newcatdesc='';
	}
	
	echo '<h4 class="cat_name">' .utf8_encode($cat_name). '</h4>'.utf8_encode($newcat_desc).'
			<p class="cat_desc">' .utf8_encode($newcatdesc). '</p>';
	
	
	//START GET MENUS UNDER MAIN CATEGORY
	$qry2 = "Select *, if(`order`='','A',`order`) as theorder from $menucattable where (cat_id = '". $cat_id ."' and deleted = '0' and featured= '1' and type = $ttype) order by length(theorder), theorder asc";
	$res2 = mysql_query($qry2) or die("Error " . mysql_error());		
			
				if(mysql_num_rows($res2) != 0){
					$cnt1 = 1;
					//START MENU DIVISION
					echo '<div class="meny_division column_'. $cnt1 .'">';
					
					while($row2 = mysql_fetch_array($res2)){
                        $qtill_2 = mysql_query("select id from menu_option_details where menu_id='".$row2['id']."' and single_option=1") or die(mysql_error());
						    $count_tillvals_2 = mysql_num_rows($qtill_2);
                           if($count_tillvals_2 > 0){
                           	$tillvals_2 =1;
                           }else{
                            $tillvals_2 =0;

                           }

						$menu_id_2 = $row2['id'];
						$menu_name_2 = utf8_encode($row2['name']);
						$use_price_2 = ($row2['takeaway_price']=='0.00') ? $row2['price'] : $row2['takeaway_price'];
						$menu_price_2 = '<span class="menu-price"> '. $row2['price'] .':-</span>';
						$menu_description_2 = utf8_encode(strip_tags($row2['description']));
						?>	
							<div class="section-devider">
									<div class="left-side">
										<h4><?php echo utf8_encode($menu_name_2). ' '; if($menu_name_2!=''){echo $use_price_2;}?></h4>
										<p><?php echo utf8_encode($menu_description_2). ' '; if($menu_name_2==''){echo $use_price_2;}?></p>
									</div>
                                     <span class="outerinputs">
						
						<img src="<?php echo CHILD_URL; ?>/images/Lagg-till.png" class="takeimg tacart" data-id="<?php echo $menu_id_2; ?>" data-rel="<?php echo $menu_name_2.'<span>'.$menu_description_2.'</span>'; ?>" data-tillval="<?php echo $tillvals_2; ?>" data-title="<?php echo $use_price_2; ?>">
						
						
						</span>

	
								</div>
					   <?php				
					}
					
						echo '</div>';
					//END MENU DIVISION
					
					$cnt1++;	
				}
	//END GET MENUS UNDER MAIN CATEGORY	
	
	
	
	//GET SUB CATEGORIES
	$qry3 = "Select *, if(`order`='','A',`order`) as theorder from $subcattable where (category_id = '". $cat_id ."' and deleted = '0') order by length(theorder) asc, theorder asc";
	$res3 = mysql_query($qry3) or die("Error " . mysql_error());

	if(mysql_num_rows($res3) != 0){
		$cnt2 = 1;
		
		while($row3 = mysql_fetch_array($res3)){
			$subcat_id = $row3['id'];
			$subcat_name = utf8_encode($row3['name']);
			$subcat_desc = utf8_encode($row3['description']);
			
			//START MENU DIVISION
			echo '<div class="meny_division column_'. $cnt2 .'">';
			
			echo '<h1 class="subcategory-name">' .$subcat_name. '</h1>
					<p class="subcategory_desc">'.$subcat_desc.'</p>';
			
			//GET MENUS UNDER SUB CARTEGORY
			$qry4 = "Select * from $menucattable where (sub_category_id = '". $subcat_id ."' and deleted = '0' and featured= '1' and type = $ttype) order by length(`order`), `order` asc";
			$res4 = mysql_query($qry4) or die("Error " . mysql_error());

			if(mysql_num_rows($res4) != 0){
				while($row4 = mysql_fetch_array($res4)){
                 

					$qtill_4 = mysql_query("select id from menu_option_details where menu_id='".$row4['id']."' and single_option=1") or die(mysql_error());
						    $count_tillvals_4 = mysql_num_rows($qtill_4);
                           if($count_tillvals_4 > 0){
                           	$tillvals_4 =1;
                           }else{
                            $tillvals_4 =0;

                           }
						$menu_id_4 = $row4['id'];
						$menu_name_4 = utf8_encode($row4['name']);
						$use_price_4 = ($row4['takeaway_price']=='0.00') ? $row4['price'] : $row4['takeaway_price'];
						$menu_price_4 = '<span class="menu-price"> '. $row4['price'] .':-</span>';
						$menu_description_4 = utf8_encode(strip_tags($row4['description']));
						?>	
							<div class="section-devider">
									<div class="left-side">
										<h4><?php echo $menu_name_4. ' '; if($menu_name_4!=''){echo $use_price_4;}?></h4>
										<p><?php echo $menu_description_4. ' '; if($menu_name_4==''){echo $use_price_4;}?></p>
									</div>
                                     <span class="outerinputs">
						
						<img src="<?php echo CHILD_URL; ?>/images/Lagg-till.png" class="takeimg tacart" data-id="<?php echo $menu_id_4; ?>" data-rel="<?php echo $menu_name_4.'<span>'.$menu_description_4.'</span>'; ?>" data-tillval="<?php echo $tillvals_4; ?>" data-title="<?php echo $use_price_4; ?>">
						
						                                     

								</div>
					   <?php		
				}	
				
			}
			//GET MENUS UNDER SUB CARTEGORY
			
				echo '</div>';
		//END MENU DIVISION
		
		$cnt2++;
			
		}
}
}
?>
<!--END-->
</div>

<?php
return ob_get_clean();
}
add_shortcode( 'MENU', 'menus' );
add_filter('widget_text', 'do_shortcode');




//* Remove the edit link
add_filter ( 'genesis_edit_post_link' , '__return_false' );

//Remove admin bar
add_filter('show_admin_bar', '__return_false');



add_action('wp_footer', 'script_catering');
function script_catering(){
	
	
	?>
			<script type="text/javascript" src="<?php echo CHILD_URL ?>/js/jquery_cookie.js"></script>
			<script type="text/javascript">
			
			function closetheCart(){
				$('.fade, .cart-detail, .takecart-detail').fadeOut();
			}
			
			function are_cookies_enabled()
			{
				var cookieEnabled = (navigator.cookieEnabled) ? true : false;
			
				if (typeof navigator.cookieEnabled == "undefined" && !cookieEnabled)
				{ 
					document.cookie="testcookie";
					cookieEnabled = (document.cookie.indexOf("testcookie") != -1) ? true : false;
				}
				return (cookieEnabled);
			}
			
			function rememberMe(email, password){
				jQuery.cookie('limone_email', email, { expires: 7, path: '/' });
				jQuery.cookie('limone_pass', password, { expires: 7, path: '/' });
			}
			
			
	function getcountItem(del)
	{
		var count = '';
		$.ajax({
			url: "<?php echo CHILD_URL ?>/countItem.php",
			type: 'POST',
			async: false,
			data: 'unique_id='+encodeURIComponent(jQuery.cookie('uniqueid'))+'&uniq='+encodeURIComponent(jQuery.cookie('takeaway_id'))+'&lmuniq='+encodeURIComponent(jQuery.cookie('lunchmeny_id'))+'&bfuniq='+encodeURIComponent(jQuery.cookie('breakfast_id'))+'&del='+del,
			success: function(value){
				count=value;
			}
		});	
		
		var str = count.toString().split('*');
		
		//for takeaway
		if(Number(str[1]) > 0 ){
			$("#takeaway").fadeIn();
			$('.toggle_cart span').fadeIn();
			$('.toggle_cart span').html(str[1]);
		}else {
			$('.toggle_cart span').fadeOut();
			$('.toggle_cart span').html(str[1]);
			$("#takeaway").fadeOut();	
		}

		if(Number(str[2]) > 0){
			$("#takeaway-lunch").fadeIn();	
		    $('.toggle_cart_lunch span').fadeIn();
			$('.toggle_cart_lunch span').html(str[2]);
		} else {	
			$('.toggle_cart_lunch span').fadeOut();
			$('.toggle_cart_lunch span').html(str[2]);
			$("#takeaway-lunch").fadeOut();	
		}
	}			
			$(function(){
					 
					 	if(!are_cookies_enabled()){
							$('.clickcatering').addClass('clickcateringdisable').html('Please enable cookie of your browser to proceed.').removeAttr('data-title').removeClass('clickcatering');
							
							$('.pay').val('Please enable cookie of your browser to proceed.').removeAttr('data-title').removeClass('pay');
						}
						
						//check expiration of cookie for take away
					
						setInterval(function() {
									
									
							if(jQuery.cookie('takeaway_id')==undefined || jQuery.cookie('uniqueid')==undefined || jQuery.cookie('lunchmeny_id')==undefined || jQuery.cookie('breakfast_id')==undefined){
								
								var sbox = jQuery('.success-box').css('display');
								if(sbox!='block'){
									window.location.reload();
								}
							}
						}, 1000);
					
					
					 	//set expiration for cookie
					 	var expiration_date = new Date();
					    var expiration_minutes = 60;
					    expiration_date.setTime(expiration_date.getTime() + (expiration_minutes * 60 * 1000));
						
						
						 
						//for catering cookie
					 	if(jQuery.cookie('uniqueid')=='' || jQuery.cookie('uniqueid')==undefined){
							jQuery.cookie('uniqueid', '<?php echo uniqid(); ?>', { expires: expiration_date, path: '/' });
						}
						
						//for takeaway cookie
						if(jQuery.cookie('takeaway_id')=='' || jQuery.cookie('takeaway_id')==undefined){
							jQuery.cookie('takeaway_id', '<?php echo uniqid(); ?>', { expires: expiration_date, path: '/' });
						}
						
						//for lunchmeny cookie
						if(jQuery.cookie('lunchmeny_id')=='' || jQuery.cookie('lunchmeny_id')==undefined){
							jQuery.cookie('lunchmeny_id', '<?php echo uniqid(); ?>', { expires: expiration_date, path: '/' });
						}
						
						//for breakfast cookie
						if(jQuery.cookie('breakfast_id')=='' || jQuery.cookie('breakfast_id')==undefined){
							jQuery.cookie('breakfast_id', '<?php echo uniqid(); ?>', { expires: expiration_date, path: '/' });
						}
						
						
						//for the cart
						getcountItem();
						
						
						$('.clickcatering').attr('data-title',jQuery.cookie('uniqueid'));	
						$('.pay').attr('data-title',jQuery.cookie('takeaway_id'));
						
						
					 
						$('.clickcatering').click(function(){
							//DISABLE CATERING
							
							//var val = Number($(this).attr('data-rel'));
							
							//if(val==0){
							//	$(this).attr('data-rel', '1');
							//	$('.catering-detail2').fadeIn();
							//}
							//else{
							//	$(this).attr('data-rel', '0');
							//	$('.catering-detail2').fadeOut();
							//}
							
							
						}); 
	
						$('.addme').click(function(){
							var theclass = $(this).attr('data-rel');
							var val = Number($('.quan-'+theclass).val());
							var minorder = Number($('.quan-'+theclass).attr('data-title'));
						
							if(val>=minorder){
								val = val+1;
							}
							else{
								$('.quan-'+theclass).val(minorder);
								val = minorder;
								//alert('Minimum order for this package is '+minorder);
							}
							
							$('.quan-'+theclass).val(val);
							
						}); 	
						
						$('.subtractme').click(function(){
							var theclass = $(this).attr('data-rel');
							var val = Number($('.quan-'+theclass).val());
							var minorder = Number($('.quan-'+theclass).attr('data-title'));
							
							if(val>minorder){
								val = val-1;
							}
							else{
								$('.quan-'+theclass).val(minorder);
								val = minorder;
								alert('Minimum order for this package is '+minorder);
							}
							
							if(val<=0){
								val=0;
							}
							
							$('.quan-'+theclass).val(val);
							
						});
						
						$('.packagequantity').focusout(function(){
							var val = Number($(this).val());
							var minorder = Number($(this).attr('data-title'));
							
							if(val != '' || val > 0){
								if(val<minorder){
									$(this).val(minorder);
									alert('Minimum order for this package is '+minorder);
								}
							}else{
								$(this).val('').attr('placeholder','0');	
							}
							
						}); 
						
						$('.checkmenus').click(function(){
						
							var theclass = $(this).attr('data-rel');
							var limit = $(this).attr('data-title');
							
							var count = $('.menu'+theclass+':checked').length;
							
							if(count>limit){
								alert('You can only select at least '+limit+' menu(s).');
								$(this).attr('checked',false);
							}
						
						});	
						
						$('.addcart').click(function(){
							
							var catid = $(this).attr('data-rel');
							var thecat = $('.ccatname'+catid).attr('data-title');
							var catlimit = Number($('.ccatname'+catid).attr('data-rel'));
							var checker = Number($(this).attr('data-id'));
							var limit = 0;
							var count = 0;
							
							var radio = 0;
							var countradio = 0;
							
							var minorder = Number($('.quan-'+catid).attr('data-title'));
							
							
							if(checker!=0){
								if(thecat > 0){
									$('.fromcat'+catid).each(function() {
										limit+=Number($(this).attr('data-rel'));
									});
								
									
									$('.fromcat'+catid).each(function() {
										var subcatid=$(this).attr('data-id');
										count+=$('.menu-'+subcatid+':checked').length;
										
										countradio+=$('input:radio.menu-'+subcatid).length;
										radio+=$('input:radio.menu-'+subcatid+':checked').length;
										
										
									});
									
									
								}
								else{
									limit = catlimit;
									
									count = $('.menu'+catid+':checked').length;
									
									radio+=$('input:radio.menu'+catid+':checked').length;
									countradio+=$('input:radio.menu'+catid).length;
								
								}
								
								
								if(count<=limit){
									
									
									var okay=0;
									
									if(limit>0){
										if(count>=0){
											
											if(countradio>0){
												if(radio>0){
													okay=1;
												}
											}
											else{
												okay=1;
											}
										}
									}
									else{
										okay=1;
									}
								
									if(okay==1){
									
										var quan = Number($('.cateringbox'+catid+' .packagequantity').val());
										
										if(quan>0){
											
											var theprice = Number($('.cateringbox'+catid+' .forpricing label span').html());
											
											var checkprice = $('.cateringbox'+catid+' .forpricing .theprice').length;
											
											if(checkprice > 0){
												theprice = Number($('.cateringbox'+catid+' .forpricing .theprice:checked').attr('data-id'));
											}
											
											//catid***quantity***price
											var theorder = catid+'***'+quan+'***'+theprice;
											
											//for division
											
											var rad=$('.cateringbox'+catid+' .radiomenus:checked').length;
											var chk=$('.cateringbox'+catid+' .checkmenus:checked').length;
											var pereach = '';
											
										
											if(rad>0){
												pereach=quan+'*';
											}
											if(chk>0){
											
												var thediv = parseInt((quan/chk));
												var themod = (quan%chk);
												
												if(themod==0){
													
													for(i=0;i<chk;i++){
														pereach+=thediv+'*';
													}
													
												}
												else{
													
													for(i=0;i<chk;i++){
														var hold=0;
														
														if(themod>0){
															hold=1;
														}
														pereach+=(thediv+hold)+'*';
														
														themod=themod-1;
													}
												
												}
											
											}
											
											pereach = pereach.substr(0,pereach.length-1);
											
											//
										
											
											var thedetail='';
											var pereach = pereach.split('*');
											$('.cateringbox'+catid+' .radiomenus:checked').each(function(i){
												//menuid***quantity***price
												thedetail+= $(this).attr('data-id')+'***'+pereach[i]+'***0^';
											});
											
											$('.cateringbox'+catid+' .checkmenus:checked').each(function(i){
												//menuid***quantity***price
												thedetail+= $(this).attr('data-id')+'***'+pereach[i]+'***0^';
											});
											
											thedetail = thedetail.substr(0,thedetail.length-1);
											
											$.ajax({
												url: "<?php echo CHILD_URL ?>/orders.php",
												type: 'POST',
												async:false,
												data: 'order='+encodeURIComponent(theorder)+'&order_detail='+encodeURIComponent(thedetail)+'&unique_id='+encodeURIComponent(jQuery.cookie('uniqueid')),
												success: function(value){
													
													//cartcount = value;
													
												}
											});
											
											
											//added
											
													$('.menucart').fadeIn();
											
													var cart = $('.menucart');
													var imgtodrag = $(this).parent('.tocart').find("img").eq(0);
													if (imgtodrag) {
														var imgclone = imgtodrag.clone()
															.offset({
															top: imgtodrag.offset().top,
															left: imgtodrag.offset().left
														})
															.css({
															'opacity': '0.5',
																'position': 'absolute',
																'height': '50px',
																'width': '50px',
																'z-index': '100'
														})
															.appendTo($('body'))
															.animate({
															'top': cart.offset().top + 10,
																'left': cart.offset().left + 10,
																'width': 20,
																'height': 20
														}, 1000, 'easeInOutExpo');
														
														setTimeout(function () {
															cart.effect("shake", {
																times: 2
															}, 200);
														}, 1500);
											
														imgclone.animate({
															'width': 0,
																'height': 0
														}, function () {
															$(this).detach()
														});
													}
													
													
													//end added
													
													var cartcount = Number($('.menucart span').html());
													cartcount+=1;
													
													$('.menucart span').html(cartcount);
													
											
											
										}
										else{
											alert('Vänligen uppge antal portioner.');
										}
									
									}
									else{
										alert("Vänligen välj de tillval som krävs för denna rätt.");
									}
								
								}
								else{
									alert("Vänligen välj de tillval som krävs för denna rätt.");
								}
								
								
							}
							else{
							
								var hasvalue = 0;
								
								$('.cateringbox'+catid+' .packagequantity').each(function(){
									if(Number($(this).val())>0){
										hasvalue+=1;
									}
								});
								
								if(hasvalue>0){
									
									var thedetail='';
									
									//catid***quantity***price
									var theorder = catid+'***0***0';
									
									$('.cateringbox'+catid+' .packagequantity').each(function(){
										if(Number($(this).val())>0){
											
											var menuid = $(this).attr('data-rel');
											var theprice = Number($('.cateringbox'+catid+' .forpricing'+menuid+' span').html());
											var checkprice = $('.cateringbox'+catid+' .forpricing'+menuid+' .theprice').length;
											
											if(checkprice > 0){
												theprice = Number($('.cateringbox'+catid+' .forpricing'+menuid+' .theprice:checked').attr('data-id'));
											}
											
											//menuid***quantity***price
											thedetail+= menuid +'***'+$(this).val()+'***'+theprice+'^';
											
											
										}
									});
									
									thedetail = thedetail.substr(0,thedetail.length-1);
								
									
									$.ajax({
											url: "<?php echo CHILD_URL ?>/orders.php",
											type: 'POST',
											data: 'order='+encodeURIComponent(theorder)+'&order_detail='+encodeURIComponent(thedetail)+'&unique_id='+encodeURIComponent(jQuery.cookie('uniqueid')),
											success: function(value){}
									});
									
								}
								else{
									alert('Please input the number of quantity for the menu/item desired.');
								}
							
							}
								
						
						});
						
						$('.checkout, .menucart, .menucart span').click(function(){
							
							$.fn.center = function ()
							{
								this.css("position","fixed");
								this.css("top", (($(window).height() / 2) - (this.outerHeight() / 2))+50);
								return this;
							}
							
							var checker = Number($('.menucart span').html());
							
							if(checker > 0){		
								$.ajax({
										url: "<?php echo CHILD_URL ?>/cart.php",
										type: 'POST',
										async: false,
										data: 'unique_id='+encodeURIComponent(jQuery.cookie('uniqueid'))+'&theurl='+encodeURIComponent('<?php echo CHILD_URL ?>'),
										success: function(value){
										
											$('.fade, .cart-detail').fadeIn();
											$('.cart-detail').html(value).center();
											
										}
										
										
								});	
							}
							else{
								$('.fade, .cart-detail').fadeIn();
								$('.cart-detail').html('<div class="cart-inner"><a href="javascript:void(0)" class="close-cart" onclick="closetheCart()">Close</a><div class="clear"></div><span class="emptycart">You have an empty cart.</span></div>').center();
							}
								
						});
						
						
						//for takeaway open cart
						
						
						$('.takeawaycart, .takeawaycart span').click(function(){
							$.fn.center = function ()
							{
								this.css("position","fixed");
								this.css("top", (($(window).height() / 2) - (this.outerHeight() / 2))+50);
								return this;
							}
							
							var checker = Number($('.takeawaycart span').html());
							
							if(checker > 0){		
								$.ajax({
										url: "<?php echo CHILD_URL ?>/takeaway-cart.php",
										type: 'POST',
										async: false,
										data: 'uniq='+encodeURIComponent(jQuery.cookie('takeaway_id'))+'&theurl='+encodeURIComponent('<?php echo CHILD_URL ?>')+'&siteurl='+encodeURIComponent('<?php echo bloginfo('siteurl'); ?>'),
										success: function(value){
											
											
											$('.fade, .takeawaycart-detail').fadeIn();
											$('.takeawaycart-detail').html(value).center();
											
											var opt_cookie =unescape(jQuery.cookie('cart_opt'));
											console.log(opt_cookie);
											
											if(opt_cookie === null || opt_cookie === "" || opt_cookie === "null" || opt_cookie === 'undefined'){
												  //no cookie
												  //do nothing
												  //console.log('ek');
											}else{
												var cart_opt=opt_cookie.split(',');
												
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
							/*else{
								$('.fade, .cart-detail').fadeIn();
								$('.takeawaycart-detail').html('<div class="tacart-inner"><a href="javascript:void(0)" class="close-cart" onclick="closetheCart()">Close</a><div class="clear"></div><span class="emptycart">You have an empty cart.</span></div>').center();
							}*/
								
						});
						
                       //for lunch 
						$('.lunchmenycart, .lunchmenycart span').click(function(){
									$.fn.center = function ()
									{
										this.css("position","fixed");
										this.css("top", (($(window).height() / 2) - (this.outerHeight() / 2))+50);
										return this;
									}
									
									var checker = Number($('.lunchmenycart span').html());
									
									if(checker > 0){		
										$.ajax({
												url: "<?php echo CHILD_URL ?>/lunchmeny-cart.php",
												type: 'POST',
												async: false,
												data: 'uniq='+encodeURIComponent(jQuery.cookie('lunchmeny_id'))+'&theurl='+encodeURIComponent('<?php echo CHILD_URL ?>')+'&siteurl='+encodeURIComponent('<?php echo bloginfo('siteurl'); ?>'),
												success: function(value){
												
													$('.fade, .lunchmenycart-detail').fadeIn();
													$('.lunchmenycart-detail').html(value).center();
													
													
												}
										});	
									}
								
										
								});
										
								
						
						//for breakfast open cart
						
						
						$('.breakfastcart, .breakfastcart span').click(function(){
							$.fn.center = function ()
							{
								this.css("position","fixed");
								this.css("top", (($(window).height() / 2) - (this.outerHeight() / 2))+50);
								return this;
							}
							
							var checker = Number($('.breakfastcart span').html());
							
							if(checker > 0){		
								$.ajax({
										url: "<?php echo CHILD_URL ?>/breakfast-cart.php",
										type: 'POST',
										async: false,
										data: 'uniq='+encodeURIComponent(jQuery.cookie('breakfast_id'))+'&theurl='+encodeURIComponent('<?php echo CHILD_URL ?>')+'&siteurl='+encodeURIComponent('<?php echo bloginfo('siteurl'); ?>'),
										success: function(value){
										
											$('.fade, .takeawaycart-detail').fadeIn();
											$('.takeawaycart-detail').html(value).center();
											
											var opt_cookie =unescape(jQuery.cookie('cart_opt'));
											console.log(opt_cookie);
											
											if(opt_cookie === null || opt_cookie === "" || opt_cookie === "null" || opt_cookie === 'undefined'){
												  //no cookie
												  //do nothing
												  //console.log('ek');
											}else{
												var cart_opt=opt_cookie.split(',');
												
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
							/*else{
								$('.fade, .cart-detail').fadeIn();
								$('.takeawaycart-detail').html('<div class="tacart-inner"><a href="javascript:void(0)" class="close-cart" onclick="closetheCart()">Close</a><div class="clear"></div><span class="emptycart">You have an empty cart.</span></div>').center();
							}*/
								
						});
						
						
						//for lunch meny cart
						

						
						
						//for take away cart
						
				$('.tacart').click(function(){
							   
								var menuid = $(this).attr('data-id');
								var uniq = jQuery.cookie('takeaway_id');
								//var quan = Number($('.quantity-'+menuid).val());
								var quan = 1;
								var price = $(this).attr('data-title');
								
								var tillval_count = $(this).attr('data-tillval');
								var menu_det = $(this).attr('data-rel');
								
								var siteurl = $('.takeaway_cart_wrap').attr('data-rel');
								
								//added by Karbhe
								//var isMobile = (/android|webos|iphone|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
								//if(isMobile){ quan = 1; }
								
								var check = getPanic();
								
								if(check==1){
									
									$('.fade, .panic-box').fadeIn(100);
									gotoModal();
									
									return false;
								}
								
								if(quan > 0){
									
									//check settings first
									
									var check=0;
									
									$.ajax({
											url: "<?php echo CHILD_URL; ?>/check-settings.php",
											type: 'POST',
											async:false,
											data: 'tablename='+encodeURIComponent('takeaway_settings'),
											success: function(value){
												check=value.trim();
											}
									});
																								
									if(check==0){
										
											//save orders
				$.ajax({
						url: "<?php echo CHILD_URL; ?>/takeaway-orders.php",
						type: 'POST',
						async:false,
						data: 'uniq='+encodeURIComponent(uniq)+'&menu_id='+encodeURIComponent(menuid)+'&quan='+encodeURIComponent(quan)+'&price='+encodeURIComponent(price)+'&tillval_count='+tillval_count+'&lunchmeny=0',
						    success: function(value){
							var resid = value;
							if(tillval_count>0){
				
								$('.special_request_content').html('<center><img src="'+siteurl+'/images/loader.gif'+'"></center>');
								$.ajax({
									url: "<?php echo CHILD_URL; ?>/special-request.php",
									type: 'POST',
									async:false,
									data: 'id='+encodeURIComponent(resid)+'&menu_det='+encodeURIComponent(menu_det)+'&siteurl='+encodeURIComponent('<?php echo CHILD_URL; ?>')+'&temp_tillval='+tillval_count+'&price='+encodeURIComponent(price)+'&check=second',
									success: function(value){
										$('.special_request_content').html(value);
									}
								});
								
								$('.fader, #special_request').fadeIn();
								
								$('#btn_skip').click(function(){
									//for the cart
									getcountItem(0);
								});	 
							
							}
							else{
							   
								$('#takeaway .cart_content').html('<center><img src="'+siteurl+'/images/loader.gif'+'"></center>');
								$('#takeaway .cart_content').load("<?php echo CHILD_URL.'/takeaway-cart.php';?>");
								
								//for the cart
								getcountItem(0);
								
							}
							
							
						}
				});
											
											//end save orders
										
										
									
									}
									else{
										
										//for no settings
										
										$.fn.center = function ()
										{
											this.css("position","fixed");
											this.css("top", (($(window).height() / 2) - (this.outerHeight() / 2))+50);
											return this;
										}
										
										$('.step-2-wrapper').html('<div class="steps-container"><h3 class="thestep-label">Take away</h3><div class="steps-box step-2" style="height: 200px !important;"><p>Tyvärr går det inte att beställa take away via hemsidan för tillfället. Vänligen ring eller maila oss för mer information.</p><a href="#text-18" class="nosettings btn" onclick="$(\'.steps-container\').fadeOut();">Ok</a><div class="clear"></div></div></div>');
										$('.steps-container').center();
										
									}
								
									
								}
								else{
									alert('Minsta beställning är en (1) rätt.');
								}
								
							});
							
							
							//for lunch meny
							
							$('.lmcart').click(function(){
								
								var menuid = $(this).attr('data-id');
								var uniq = jQuery.cookie('lunchmeny_id');
								
								//var quan = Number($('.lmquantity-'+menuid).val());
								var quan = 1;
								var price = $(this).attr('data-title');
								var siteurl = $('.takeaway_lunch_cart_wrap').attr('data-rel');
								
								var check = getPanic();
								
								if(check==1){
									
									$('.fade, .panic-box').fadeIn(100);
									gotoModal();
									
									return false;
								}
								
								if(quan > 0){
									
									//check settings first
									
									var check=0;
									
									$.ajax({
											url: "<?php echo CHILD_URL; ?>/check-settings.php",
											type: 'POST',
											async:false,
											data: 'tablename='+encodeURIComponent('lunchmeny_settings'),
											success: function(value){
											
												check=value.trim();
											}
									});
									
									if(check==0){
									
										$.ajax({
												url: "<?php echo CHILD_URL; ?>/lunch-orders.php",
												type: 'POST',
												data: 'uniq='+encodeURIComponent(uniq)+'&menu_id='+encodeURIComponent(menuid)+'&quan='+encodeURIComponent(quan)+'&price='+encodeURIComponent(price)+'&lunchmeny=1',
												success: function(value){
														   
															$('#takeaway-lunch .lunch_cart_content').html('<center><img src="'+siteurl+'/images/loader.gif'+'"></center>');
															$('#takeaway-lunch .lunch_cart_content').load("<?php echo CHILD_URL.'/lunch-cart.php';?>");
															//for the cart
															getcountItem(0);
												}
										});
										
										
									
									}
									else{
										
										//for no settings
										
										$.fn.center = function ()
										{
											this.css("position","fixed");
											this.css("top", (($(window).height() / 2) - (this.outerHeight() / 2))+50);
											return this;
										}
										
										$('.step-2-wrapper').html('<div class="steps-container"><h3 class="thestep-label">Lunch Meny</h3><div class="steps-box step-2" style="height: 200px !important;"><p>Tyvärr går det inte att beställa take away via hemsidan för tillfället. Vänligen ring eller maila oss för mer information.</p><a href="#text-18" class="nosettings btn" onclick="$(\'.steps-container\').fadeOut();">Ok</a><div class="clear"></div></div></div>');
										$('.steps-container').center();
										
									}
								
									
								}
								else{
									alert('Minsta beställning är en (1) rätt.');
								}
								
							});
													
						
						
						
						
						//for breakfast cart
						
						$('.bfcart').click(function(){
							var menuid = $(this).attr('data-id');
							var uniq = jQuery.cookie('breakfast_id');
							//var quan = Number($('.bquantity-'+menuid).val());
							//var price = $('.bquantity-'+menuid).attr('data-title');
							
							var price = Number($(this).attr('data-title'));
							
							var check = getPanic();
							
							if(check==1){
								
								$('.fade, .panic-box').fadeIn(100);
								gotoModal();
								
								return false;
							}
							
							
							var quan = 1;
							
							if(quan > 0){
								
								//check settings first
								
								var check=0;
								
								$.ajax({
										url: "<?php echo CHILD_URL; ?>/check-settings.php",
										type: 'POST',
										async:false,
										data: 'tablename='+encodeURIComponent('breakfast_settings'),
										success: function(value){
											check=value.trim();
											console.log(check)
										}
								});
								
								if(check==0){
								
									$.ajax({
											url: "<?php echo CHILD_URL; ?>/takeaway-orders.php",
											type: 'POST',
											data: 'uniq='+encodeURIComponent(uniq)+'&menu_id='+encodeURIComponent(menuid)+'&quan='+encodeURIComponent(quan)+'&price='+encodeURIComponent(price)+'&lunchmeny=2',
											success: function(value){
												$('.breakfastcart span').html(value);
											}
									});
									
									$('.breakfastcart').fadeIn();
									
									//for the cart
									getcountItem();
									
									var cart = $('.breakfastcart');
									var imgtodrag = $(this).parent('.outerinputs').find("img").eq(0);
									if (imgtodrag) {
										var imgclone = imgtodrag.clone()
											.offset({
											top: imgtodrag.offset().top,
											left: imgtodrag.offset().left
										})
											.css({
											'opacity': '0.5',
												'position': 'absolute',
												'height': '50px',
												'width': '50px',
												'z-index': '100'
										})
											.appendTo($('body'))
											.animate({
											'top': cart.offset().top + 10,
												'left': cart.offset().left + 10,
												'width': 20,
												'height': 20
										}, 1000, 'easeInOutExpo');
										
										setTimeout(function () {
											cart.effect("shake", {
												times: 2
											}, 200);
										}, 1500);
							
										imgclone.animate({
											'width': 0,
												'height': 0
										}, function () {
											$(this).detach()
										});
									}
								
								}
								else{
									
									//for no settings
									
									$.fn.center = function ()
									{
										this.css("position","fixed");
										this.css("top", (($(window).height() / 2) - (this.outerHeight() / 2))+50);
										return this;
									}
									
									$('.step-2-wrapper').html('<div class="steps-container"><h3 class="thestep-label">Lunchmeny</h3><div class="steps-box step-2" style="height: 200px !important; text-align:center;"><p>Tyvärr går det inte att beställa take away via hemsidan för tillfället.<br>Vänligen ring eller maila oss för mer information.</p><a href="#kontakta-oss-sections" class="nosettings btn" onclick="$(\'.steps-container\').fadeOut();">Ok</a><div class="clear"></div></div></div>');
									$('.steps-container').center();
									
								}
							
								
							}
							else{
								alert('Minsta beställning är en (1) rätt.');
							}
							
						});
						
						
						
						
						
						//added script
						
						$('.bokas-btn').click(function(){
		 
							 var fname=$('.resfname').val();
							 var lname=$('.reslname').val();
							 var email=$('.resemails').val();
							 var phone=$('.resphone').val();
							 var company=$('.rescompany').val();
							 var street=$('.resstreet').val();
							 var city=$('.rescity').val();
							 var zip=$('.reszip').val();
							 var country=$('.rescountry').val();
							 var pword=$('.respwords').val();
							 var cpword=$('.rescpwords').val();
							 var guest=$('.resguest').val();
							 var time=$('.restime').val();
							 var note=$('.resrequest').val();
							 var agree = $('#agree2').prop('checked');
							 
							 var res_detail_id=$('.reservation-btn').attr('data-rel');
							 
							 var thedate=$("#datepicker").datepicker( 'getDate' );
							  var curDay = new Date(thedate);
							  
							  //var dayName=arrDaysName[curDay.getDay()];
							  var mm=Number(curDay.getMonth()+1);
							  var dd=Number(curDay.getDate());
							  var yyyy=curDay.getFullYear();
							  
							  if(mm<10){
								  mm='0'+mm;
							  }
							  
							  if(dd<10){
								  dd='0'+dd;
							  }
							  
							  var toDay=mm+"/"+dd+"/"+yyyy;	
							
							 
							 $('.signup-box .displaymsg').fadeOut('slow');
							 
							 if(fname!='' & lname!='' & email!='' & phone!='' & pword!='' & cpword!=''){
								 
								 if(guest!='' & time!=''){
									 
									 if(validateEmail(email)){
										 
										 if(pword==cpword){
											 
											  if(agree){ 
											
											 
												  $('.signup-box .displaymsg').fadeIn().html('<img src="<?php echo CHILD_URL; ?>/images/rectangular.gif" style="margin:0 auto;">').removeClass('errormsg');
												 
												  $.ajax({
													 url: "<?php echo CHILD_URL; ?>/saveOtherBooking.php",
													 type: 'POST',
													 data: 'fname='+encodeURIComponent(fname)+'&lname='+encodeURIComponent(lname)+'&email='+encodeURIComponent(email)+'&phone='+encodeURIComponent(phone)+'&company='+encodeURIComponent(company)+'&street='+encodeURIComponent(street)+'&city='+encodeURIComponent(city)+'&zip='+encodeURIComponent(zip)+'&country='+encodeURIComponent(country)+'&pword='+encodeURIComponent(pword)+'&guest='+encodeURIComponent(guest)+'&time='+encodeURIComponent(time)+'&notes='+encodeURIComponent(note)+'&date='+encodeURIComponent(toDay)+'&res_detail_id='+encodeURIComponent(res_detail_id),
													 success: function(value){
														 
															 
															 value=value.trim();
															
															 
															 if(value=='Invalid'){
																 
																 $('.signup-box .displaymsg').fadeIn('slow').addClass('errormsg').html('E-postadressen du har angett är redan registrerad. Vänligen uppge en annan E-postadress.');
																 
															 }
															 else{
																 
																 
																	 $('.fade2, .signup-box').fadeOut();
																 
																	 $('.success-box .box-content').html('');
																 
																	 if(value=='not'){
																		
																		 $('.success-box .box-content').html('We regret to inform you that we currently do not have an available table for your reservation.  However, you may contact us so we can accommodate you in a special table just for you.<br><br>Call us at 021-417560.').css('text-align','left');
																	
																		 
																	 }
																	 else{
																		 
																		 val=value.split("**");
																		 
																		 $('.success-box .box-content').html("Tack för din bokning! Vi har reserverat ett bord för <strong>"+val[3]+"</strong> personer, klockan <strong>"+val[2]+"</strong> den <strong>"+val[1]+"</strong>..<br><br>Hör av dig till oss snarast om du vill göra några ändringar i din bokning på telefonnummer 021-417560. En bekräftelse har även skickats till din e-postadress.<br><br>Välkommen! ").css('text-align','left');
																
																		 
																	 }
																	 
																	  $('.success-box, .fade3').fadeIn();
																	  
																	  $('.signup-box .displaymsg').fadeOut().html('').removeClass('errormsg');
																 
																		gotoModal();
																	 
															
																
															 }
															 
													   }
												  });
												 
											 }
											 else{
												  $('.signup-box .displaymsg').fadeIn('slow').addClass('errormsg').html('Godkänn användarvillkoren för att fortsätta.');  
														
											 }
										 
										 
										 }
										  else{
												$('.signup-box .displaymsg').fadeIn('slow').addClass('errormsg').html('Passwords do not match.');
										 }  
										 
										 
									 }
									 else{
										$('.signup-box .displaymsg').fadeIn('slow').addClass('errormsg').html('Invalid email address.');		
									 }
									 
								 }
								 else{
									$('.signup-box .displaymsg').fadeIn('slow').addClass('errormsg').html('Antal personer och tid måste anges.');	
								 }
								 
							 }
							 else{
								$('.signup-box .displaymsg').fadeIn('slow').addClass('errormsg').html('Fyll i alla obligatoriska fält.');
							 }
							 
						 });
						 
						 
						 //for catering...
						 
						 $('.caterbtn').click(function(){
								var datetime = $('#caterdate').val();
								var total = Number($('.catertotal').attr('data-rel'));
								var payment_type = $('.catertype').val();
								var email = $('.cateremail').val();
								var pword = $('.caterpass').val();
								var check = $('.catercheck').prop('checked');
								var checks = $('.rememberCater').prop('checked');
								
								if (checks) {
									rememberMe(email, pword);
								}
								
								$('.catermsg').fadeOut();
								
								if(datetime!='' & email!='' & pword!=''){
									
									if(validateEmail(email)){
										
										if(check){
										
											
											$.ajax({
											 url: "<?php echo CHILD_URL; ?>/saveCatering.php",
											 type: 'POST',
											 data: 'unique_id='+encodeURIComponent(jQuery.cookie('uniqueid'))+'&datetime='+encodeURIComponent(datetime)+'&total='+encodeURIComponent(total)+'&payment_type='+encodeURIComponent(payment_type)+'&email='+encodeURIComponent(email)+'&pword='+encodeURIComponent(pword),
											 success: function(value){
												 
												   value = value.trim();
												 
												   
												   if(value=='invalid'){
													   $('.catermsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Invalid username/password.');
												   }
												   else if(value=='not'){
													   $('.catermsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Invalid transaction.');
												   }
												   else{
													   
													     $('.catermsg, .cateringwindow_2, .menucart, .catering-detail2').fadeOut();
														 $('.cateringwindow_1').fadeIn();
														 
														 $('.clickcatering').attr('data-rel',0).attr('data-title','');
														 
														 $('.success-box a.closebox').attr('onclick','window.location.reload()');
													   
														 $('.success-box .box-content').html("Tack för din beställning. En bekräftelse på din beställning kommer inom kort att malas till dig. Vänligen ring oss om du har några frågor på telefonnummer: 021-417560.").css('text-align','left');
														 $('.success-box, .fade3').fadeIn();
														 
														 gotoModal();
													
														
														jQuery.removeCookie('uniqueid', { path: '/' });
														
														
												   }
												
											 }
										  });
											
										}
										else{
											$('.catermsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Godkänn användarvillkoren för att fortsätta.');
										}
									
									}
									else{
										$('.catermsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Invalid Email Address.');
									}
									
								}
								else{
									$('.catermsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('All fields are required.');
								}
								
							});
							
							
							
							$('.cater-new-btn').click(function(){
		 
							 var fname=$('.catfname').val();
							 var lname=$('.catlname').val();
							 var email=$('.catemails').val();
							 var phone=$('.catphone').val();
							 var company=$('.catcompany').val();
							 var street=$('.catstreet').val();
							 var city=$('.catcity').val();
							 var zip=$('.catzip').val();
							 var country=$('.catcountry').val();
							 var pword=$('.catpwords').val();
							 var cpword=$('.catcpwords').val();
							 var agree = $('.signup-cater-box .catercheck').prop('checked');
							 
							 var datetime = $('#caterdate').val();
							 var total = Number($('.catertotal').attr('data-rel'));
							 var payment_type = $('.catertype').val();
							
							 
							 $('.signup-cater-box .displaymsg').fadeOut('slow');
							
							 
							 if(fname!='' & lname!='' & email!='' & phone!='' & pword!='' & cpword!=''){
								 
									 
								 if(validateEmail(email)){
									 
									 if(pword==cpword){
										 
										 
										 if(datetime!=''){
											 
											 if(agree){
												 
												 
												 $.ajax({
													 url: "<?php echo CHILD_URL; ?>/saveOtherCatering.php",
													 type: 'POST',
													 data: 'unique_id='+encodeURIComponent(jQuery.cookie('uniqueid'))+'&datetime='+encodeURIComponent(datetime)+'&total='+encodeURIComponent(total)+'&payment_type='+encodeURIComponent(payment_type)+'&fname='+encodeURIComponent(fname)+'&lname='+encodeURIComponent(lname)+'&email='+encodeURIComponent(email)+'&phone='+encodeURIComponent(phone)+'&company='+encodeURIComponent(company)+'&street='+encodeURIComponent(street)+'&city='+encodeURIComponent(city)+'&zip='+encodeURIComponent(zip)+'&country='+encodeURIComponent(country)+'&pword='+encodeURIComponent(pword)+'&cpword='+encodeURIComponent(cpword),
													 success: function(value){
														 
														   value = value.trim();
														 
														   
														   if(value=='invalid'){
															   $('.signup-cater-box .displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Email Address already exists!.');
														   }
														   else if(value=='not'){
															   $('.signup-cater-box .displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Invalid transaction.');
														   }
														   else{
															   
																 $('.catermsg, .cateringwindow_2, .menucart, .catering-detail2, .signup-cater-box, .fade3').fadeOut();
																 $('.cateringwindow_1').fadeIn();
																 
																 $('.clickcatering').attr('data-rel',0).attr('data-title','');
																 
																 $('.success-box a.closebox').attr('onclick','window.location.reload()');
															   
																 $('.success-box .box-content').html("Tack för din beställning. En bekräftelse på din beställning kommer inom kort att malas till dig. Vänligen ring oss om du har några frågor på telefonnummer: 021-417560.").css('text-align','left');
																 $('.success-box, .fade3').fadeIn();
																 
																 gotoModal();
															
																
																jQuery.removeCookie('uniqueid', { path: '/' });
																
																
														   }
														
													 }
												  });
												 
												 
											 }
											 else{
												$('.signup-cater-box .displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Godkänn användarvillkoren för att fortsätta.');	
											 }
											 
										 }
										 else{
											$('.signup-cater-box .displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Catering date is required.');
										 }
									 
									 
									 }
									  else{
											$('.signup-cater-box .displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Passwords do not match.');
									 }  
									 
									 
								 }
								 else{
									$('.signup-cater-box .displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Invalid email address.');		
								 }
									
								 
							 }
							 else{
								$('.signup-cater-box .displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Fyll i alla obligatoriska fält.');
							 }
							 
						 });
						 
						 
						 $('.panicbtn').click(function(){
						 
						 	$('.fade, .panic-box').fadeOut(100);
						 
						 });
							
				 });
			</script>
		<?php
}


function boxes($atts){
	ob_start();
?>

<div id="takeaway-fader" data-id="takeaway" ></div>
<div id="takeaway" style="display:none">
    <a href="javascript:void(0)" class="toggle_cart" >Beställ Avhämtning<span></span></a>
    <div class="takeaway_cart_wrap" data-rel="<?php echo CHILD_URL; ?>">
        <div class="take_away_cart">
            <h4></h4>
            <div class="cart_content">
                <?php  include ABSPATH.'wp-content/themes/outreach/takeaway-cart.php';?>
            </div>
        </div>
    	<a href="#" class="close-mobile-cart" data-id="takeaway"></a>
    </div>
</div>

<div id="takeaway-lunch-fader" data-id="takeaway-lunch" ></div>
<div id="takeaway-lunch" style="display:none">
    <a href="javascript:void(0)" class="toggle_cart_lunch">Veckans Lunch<span></span></a>
    <div class="takeaway_lunch_cart_wrap" data-rel="<?php echo CHILD_URL; ?>">
        <div class="take_away_lunch_cart">
            <h4></h4>
            <div class="lunch_cart_content">
                <?php  include ABSPATH.'wp-content/themes/outreach/lunch-cart.php';?>
            </div>
        </div>
    	<a href="#" class="close-mobile-cart-lunch"></a>
    </div>
</div>


<div class="fader"></div>
<div id="special_request">
	<div class="special_request_wrap">
    	<a class="close-modals" href="javascript:void(0)" data-rel="#special_request,.fader"></a>
        <div class="special_request_content">
        	<!-- special request's out -->
        </div>
    </div>
</div>


<?php
	$countries=array('Afghanistan','Aland Islands','Albania','Algeria','American Samoa','Andorra','Angola','Anguilla','Antigua and Barbuda','Argentina','Armenia','Aruba','Australia','Austria','Azerbaijan','Bahrain','Bangladesh','Barbados','Belarus','Belgium','Belize','Benin','Bermuda','Bhutan','Bolivia','Bosnia and Herzegovina','Botswana','Brazil','British Indian Ocean Territory','British Virgin Islands','Brunei','Bulgaria','Burkina Faso','Burma','Burundi','Cambodia','Cameroon','Canada','Cape Verde','Caribbean Netherlands','Cayman Islands','Central African Republic','Chad','Chile','China','Christmas Island','Colombia','Comoros','Cook Islands','Costa Rica','Croatia','Cuba','Curacao','Cyprus','Czech Republic','Democratic Republic of the Congo','Denmark','Djibouti','Dominica','Dominican Republic','Ecuador','Egypt','El Salvador','Equatorial Guinea','Eritrea','Estonia','Ethiopia','Falkland Islands','Faroe Islands','Fiji','Finland','France','French Guiana','French Polynesia','Gabon','Georgia','Germany','Ghana','Gibraltar','Greece','Greenland','Grenada','Guadeloupe','Guam','Guatemala','Guernsey','Guinea','Guinea-Bissau','Guyana','Haiti','Honduras','Hong Kong','Hungary','Iceland','India','Indonesia','Iran','Iraq','Ireland','Isle of Man','Israel','Italy','Ivory Coast','Jamaica','Japan','Jersey','Jordan','Kazakhstan','Kenya','Kiribati','Kuwait','Kyrgyzstan','Laos','Latvia','Lebanon','Lesotho','Liberia','Libya','Liechtenstein','Lithuania','Luxembourg','Macau','Macedonia','Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Marshall Islands','Martinique','Mauritania','Mauritius','Mayotte','Mexico','Micronesia','Moldova','Monaco','Mongolia','Montenegro','Montserrat','Morocco','Mozambique','Namibia','Nauru','Nepal','Netherlands','New Caledonia','New Zealand','Nicaragua','Niger','Nigeria','Niue','Norfolk Island','North Korea','Northern Mariana Islands','Norway','Oman','Pakistan','Palau','Palestinian Territory','Panama','Papua New Guinea','Paraguay','Peru','Philippines','Poland','Portugal','Puerto Rico','Qatar','Republic of the Congo','Reunion','Romania','Russia','Rwanda','Saint Barthelemy','Saint Helena','Saint Kitts and Nevis','Saint Lucia','Saint Martin','Saint Pierre and Miquelon','Saint Vincent and the Grenadines','Samoa','San Marino','Sao Tome and Principe','Saudi Arabia','Senegal','Serbia','Seychelles','Sierra Leone','Singapore','Sint Maarten','Slovakia','Slovenia','Solomon Islands','Somalia','South Africa','South Korea','South Sudan','Spain','Sri Lanka','Sudan','Suriname','Svalbard','Swaziland','Sweden','Switzerland','Syria','Taiwan','Tajikistan','Tanzania','Thailand','The Bahamas','The Gambia','Timor-Leste','Togo','Tokelau','Tonga','Trinidad and Tobago','Tunisia','Turkey','Turkmenistan','Turks and Caicos Islands','Tuvalu','Uganda','Ukraine','United Arab Emirates','United Kingdom','United States','Uruguay','Uzbekistan','Vanuatu','Vatican City','Venezuela','Vietnam','Virgin Islands','Wallis and Futuna','Western Sahara','Yemen','Zambia','Zimbabwe');
?>

<div class="fade2"></div>
<div class="signup-box modalbox">
	<h2>Skapa konto<a href="javascript:void" class="closebox"></a></h2>
    <div class="box-content">
    	
		<table align="center">
        	<tr>
            	<td><font style="color:red; font-size:13px;">*</font>Förnamn</td>
                <td><input type="text" class="resfname"></td>
                <td><font style="color:red; font-size:13px;">*</font>Efternamn</td>
                <td><input type="text" class="reslname"></td>
            </tr>
            <tr>
            	<td><font style="color:red; font-size:13px;">*</font>E-post</td>
                <td><input type="text" class="resemails"></td>
                <td><font style="color:red; font-size:13px;">*</font>Mobilnummer</td>
                <td><input type="text" class="resphone"></td>
            </tr>
            <tr>
            	<td>Företag</td>
                <td colspan="3"><input type="text" class="rescompany"></td>
            </tr>
            <tr>
            	<td>Gatuadress</td>
                <td><input type="text" class="resstreet"></td>
                <td>Ort</td>
                <td><input type="text" class="rescity"></td>
            </tr>
            <tr>
            	<td>Postnummer</td>
                <td><input type="text" class="reszip"></td>
                <td>Land</td>
                <td>
                	<?php
                    	$q=mysql_query("select var_value from settings where var_name='default_country'");
						$r=mysql_fetch_assoc($q);
					?>
                       <div class="select-container">
                	<select class="rescountry">
                    	<?php
                        	for($i=0;$i<count($countries);$i++){
						?>
                        	<option value="<?php echo $countries[$i];?>" <?php if($countries[$i]==$r['var_value']) echo 'selected="selected"';?>><?php echo $countries[$i];?></option>
                        <?php		
							}
						?>
                    </select>
                    </div>
                </td>
            </tr>
           <tr>
            	<td><font style="color:red; font-size:13px;">*</font>Lösenord</td>
                <td><input type="password" class="respwords"></td>
                <td><font style="color:red; font-size:13px;">*</font>Upprepa lösenord</td>
                <td><input type="password" class="rescpwords"></td>
            </tr>
            <tr>
            	<td colspan="4" style="text-align:right !important;" class="tos1"><input type="checkbox" id="agree2" class="tos" style="width:20px !important; float:none !important;"><label class="agreelabel">Jag har läst och godkänner användarvillkoren</label></td>

                <td colspan="4" style="text-align:right !important;" class="tos2"><input type="checkbox" id="agree22" class="tos" style="width:20px !important; float:none !important;"><label class="agreelabel1">Jag har läst och godkänner användarvillkoren</label></td>
            </tr>
        </table>
        <input type="button" value="Skapa konto" class="bokas-btn" style="float:right;">
        <input type="button" value="Skapa konto" class="takeaways-btn" style="float:right; display:none;  background-color:#223a5e !important; font-size: 17px;">
        <div class="displaymsg"></div>
    </div>
</div>

<div class="reservation-fade"></div>
<div class="time-container">
  <a href="javascript:void(0)" class="time-close">Stäng mig</a>
  <div class="clear"></div>
  <div class="time-inner"></div>
</div>

<div class="fade"></div>
<div class="confirm-box modalbox">
	<h2>Confirm Details<a href="javascript:void(0)" class="closebox"></a></h2>
    <div class="box-content">
        <div class="review"></div>
        <input type="button" value="Proceed">
    </div>
</div>

<div class="fade3"></div>
<div class="success-box modalbox">
	<h2>Bekräftelse <a href='javascript:void(0)' class='closebox Ok' onClick="window.location.reload();">Ok</a></h2>
    <div class="box-content"></div>
</div>

<div class="fade4"></div>
<div class="menu-box modalbox">
	<h2>Meny för avhämtning</h2>
    <div class="box-content"></div>

</div>

<div class="agreement-box modalbox">
	<h2>Bestämmelser och villkor<a href="javascript:void(0)" class="closebox"></a></h2>
    <div class="box-content" style="text-align:left;">
    	<p><strong>Bestämmelser och villkor</strong></p>
  
        <p>Villkor för bordsbeställning:</p>
         
        <p>All slags förändring av bokningen (avbokning, ombokning, försening etc) måste bekräftas för att vara giltig. Ring vänligen restaurangen och meddela eventuella förändringar. Om du/ni inte dyker upp vid den bokade tiden, förbehåller sig restaurangen rätten att släppa det bokade bordet till andra gäster samt att en avgift kan komma att tas ut för icke gjorda avbokningar.</p>


    </div>
</div>

<div class="agreement1-box modalbox">
    <h2>Bestämmelser och villkor<a href="javascript:void(0)" class="closebox"></a></h2>
    <div class="box-content" style="text-align:left;">
  
        <p>Vett och etikett gäller. </p>
         
        <!-- <p>Avbeställning av av din order måste ske senast 1 timme före den bestämda avhämtningstiden och bekräftas för att vara giltig.
Beställningar märkta ”snarast” är inte möjliga att avbeställa. <br><br>Vid utebliven avhämtning av beställd mat faktureras summan för den beställda men icke avhämtade maten.</p> -->


    </div>
</div>

<div class="agreement2-box modalbox">
    <h2>Bestämmelser och villkor<a href="javascript:void(0)" class="closebox"></a></h2>
    <div class="box-content" style="text-align:left;">
  
        <p>Villkor för take away-/avhämtningstjänster: </p>
         
        <p>Avbeställning måste ske senast xx timmar före den bestämda avhämtningstiden och bekräftas för att vara giltig. Vid utebliven avhämtning av beställd mat faktureras summan för den beställda men icke avhämtade maten. </p>

        <p>Vid utebliven avhämtning av beställd mat faktureras summan för den beställda men icke avhämtade maten.</p>


    </div>
</div>

<div class="forgot-box modalbox">
	<h2>Glömt ditt lösenord?<a href="javascript:void(0)" class="closebox"></a></h2>
    <div class="box-content">
    	<p>Uppge din e-postadress:</p>
        <p><input type="text" class="forgotemail txt"></p>
        <p><input type="button" value="Skicka" class="btn"></p>
        <div class="displaymsg"></div>
    </div>
</div>

<div class="catering-box modalbox">
	<h2>Catering Packages<a href="javascript:void(0)" class="closebox"></a></h2>
    <div class="box-content"></div>
</div>

<div class="agreement-cater-box modalbox">
    <h2>Bestämmelser och villkor<a href="javascript:void(0)" class="closebox"></a></h2>
    <div class="box-content" style="text-align:left;">
  
        <p>Villkor för beställning av catering: </p>
         
        <p>Avbeställning måste ske senast 48 timmar före den överenskomna leveranstiden och bekräftas för att vara giltig. Vid utebliven avbeställning/sen avbeställning förbehåller vi oss rätten att fakturera er på totalsumman. </p>

    </div>
</div>


<!-- cater signup -->
<div class="signup-cater-box modalbox">
	<h2>Skapa konto<a href="javascript:void" class="closebox"></a></h2>
    <div class="box-content">
    	
		<table align="center">
        	<tr>
            	<td><font style="color:red; font-size:13px;">*</font>Förnamn</td>
                <td><input type="text" class="catfname"></td>
                <td><font style="color:red; font-size:13px;">*</font>Efternamn</td>
                <td><input type="text" class="catlname"></td>
            </tr>
            <tr>
            	<td><font style="color:red; font-size:13px;">*</font>E-post</td>
                <td><input type="text" class="catemails"></td>
                <td><font style="color:red; font-size:13px;">*</font>Mobilnummer</td>
                <td><input type="text" class="catphone"></td>
            </tr>
            <tr>
            	<td>Gatuadress</td>
                <td><input type="text" class="catstreet"></td>
                <td>Ort</td>
                <td><input type="text" class="catcity"></td>
            </tr>
            <tr>
            	<td>Postnummer</td>
                <td><input type="text" class="catzip"></td>
                <td>Land</td>
                <td>
                	<?php
                    	$q=mysql_query("select var_value from settings where var_name='default_country'");
						$r=mysql_fetch_assoc($q);
					?>
                	<select class="catcountry">
                    	<?php
                        	for($i=0;$i<count($countries);$i++){
						?>
                        	<option value="<?php echo $countries[$i];?>" <?php if($countries[$i]==$r['var_value']) echo 'selected="selected"';?>><?php echo $countries[$i];?></option>
                        <?php		
							}
						?>
                    </select>
                </td>
            </tr>
           <tr>
            	<td><font style="color:red; font-size:13px;">*</font>Lösenord</td>
                <td><input type="password" class="catpwords"></td>
                <td><font style="color:red; font-size:13px;">*</font>Upprepa lösenord</td>
                <td><input type="password" class="catcpwords"></td>
            </tr>            
            <tr>
                <td>Företag</td>
                <td colspan="3" style="text-align: left;
padding: 5px 0 0 28px;"><input type="text" class="catcompany"></td>
            </tr>
            <tr>
            	<td colspan="4" style="text-align:right !important;"><input type="checkbox" class="catercheck"><a href="javascript:void(0)" class="caterterms">Jag har läst och godkänner användarvillkoren</a></td>
                
            </tr>
        </table>
        <input type="button" value="Skapa konto" class="cater-new-btn" style="float:right;">
        <div class="displaymsg"></div>
    </div>
</div>


<div class="giftcard-box modalbox">
        <h2 style="background:#007BF4";>Här kan du enkelt beställa Limones presentkort <a href="javascript:void(0)" class="close-req-giftcard"></a> </h2>
        <div class="giftcard-box-content">
        	<table style="margin-bottom: 10px;">
            	<tr>
                	<td><font style="color:red; font-size:13px;">*</font>Förnamn</td>
                    <td><input type="text" class="gcard-req-fname"></td>
                    <td><font style="color:red; font-size:13px;">*</font>Efternamn</td>
                    <td><input type="text" class="gcard-req-lname"></td>
                </tr>
                <tr>
                	<td><font style="color:red; font-size:13px;">*</font>E-post</td>
                    <td><input type="text" class="gcard-req-email"></td>
                    <td><font style="color:red; font-size:13px;">*</font>Mobiletelefon</td>
                    <td><input type="text" class="gcard-req-phone"></td>
                </tr>
                
                <tr>
                	<td><font style="color:red; font-size:13px;">*</font>Antal presentkort</td>
                    <td><input type="text" class="gcard-req-giftnum"></td>
                    <td><font style="color:red; font-size:13px;">*</font>Värde per presentkort</td>
                    <td><input type="text" class="gcard-req-giftprice"></td>
                </tr>
                
                <tr>
                	<td colspan="4" style="padding-bottom: 0; line-height: 1;"><font style="color:red; font-size:13px;">*</font>Ska presentkorten hämtas på Limone eller ska de postas?</td>
                </tr>
                
                <tr>
                	<td style="vertical-align: top; padding-top: 0;"></td>
                        <td><input type="text" class="gcard-req-postas"></td>
                </tr>
                
                <tr>
                	<td colspan="4">Om "Postas", vänligen uppge leverans- och fakturaadress samt org.nr/pers.nr</td>
                </tr>
                <tr>
                	<td></td>
                	<td colspan="3"><textarea style="width: 100%; padding: 10px; background-color: #f3f1e9;" rows="5" cols="80" class="gcard-req-message"></textarea></td>
                </tr>
            </table>     
            <input type="button" value="Beställ" class="get-card-btn" style="float: right; font-size: 17px; background-color: rgb(88, 6, 5) !important;">
            <div class="displaymsg" style="display: block;"></div>
    </div>
</div>


<div class="panic-box modalbox">
	<h2>Viktigt meddelande</h2>
    <div class="box-content">
        <p>Tyvärr kan vi inte ta emot fler beställningar just nu. Vänligen försök igen om en liten stund.</p>
        <p><input type="button" value="Ok" class="btn panicbtn" style="margin:20px 0 0 !important"></p>
    </div>
</div>


<?php	
	return ob_get_clean();
}
add_shortcode( 'boxes', 'boxes' );
add_filter('widget_text', 'do_shortcode');

function getCurrencyShortname($id){
	$q=mysql_query("select shortname from currency where id=".$id);
	$r=mysql_fetch_assoc($q);
	
	return strtolower($r['shortname']);
}

function gettheCurrentCurrency(){
	//get currency
	$qc=mysql_query("select shortname from currency where deleted=0 and set_default=1");
	$rc=mysql_fetch_assoc($qc);
	return strtolower($rc['shortname']);
}