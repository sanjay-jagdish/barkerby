<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );
//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Genesis Sample Theme' );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/' );
define( 'CHILD_THEME_VERSION', '2.0.1' );
//* Enqueue Lato Google font
add_action( 'wp_enqueue_scripts', 'genesis_sample_google_fonts' );
function genesis_sample_google_fonts() {
	wp_enqueue_style( 'google-font-lato', '//fonts.googleapis.com/css?family=Lato:300,700', array(), CHILD_THEME_VERSION );
}
//* Add HTML5 markup structure
add_theme_support( 'html5' );
//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );
//* Add support for custom background
add_theme_support( 'custom-background' );
//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );
add_action("genesis_before_header","function_menu");
function function_menu()
{
 echo '<div id="fixed_part">';
}
$defaults = array(
	'theme_location'  => '',
	'menu'            => '',
	'container'       => 'div',
	'container_class' => '',
	'container_id'    => 'menu_container',
	'menu_class'      => 'menu',
	'menu_id'         => 'menu_ul',
	'echo'            => true,
	'fallback_cb'     => 'wp_page_menu',
	'before'          => '',
	'after'           => '',
	'link_before'     => '',
	'link_after'      => '',
	'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
	'depth'           => 0,
	'walker'          => ''
);
add_action("genesis_after_header","fixed_part");
function fixed_part(){
	 wp_nav_menu( array('menu' => 'primary_menu' ));
	echo '</div>';
	
}
add_filter ( 'genesis_edit_post_link' , '__return_false' );
add_filter('show_admin_bar', '__return_false');
/**
add_action("genesis_header","genesis_logo_header");
function genesis_logo_header(){
	echo '<h1 itemprop="headline" class="site-title"><a title="Crisp" href="'.get_option("siteurl").'">Crisp</a></h1>';
}
**/
add_filter( 'genesis_footer_creds_text', 'sp_footer_creds_text' );
function sp_footer_creds_text() {
echo '<div class="creds"><p>';
echo '© HE Lind AB - Alla rättigheter förbehålles. Mise en Place by <a title="Icington" href="http://www.icington.com/">Icington</a>';
echo '</p></div>';
}
add_action("genesis_meta","script_insert");
function script_insert()
{
/*echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>';*/
}
genesis_register_sidebar( array(
	'id'				=> 'home-featured',
	'name'			=> __( 'Home Featured', 'minimum'),
	'description'	=> __( 'This is the home featured  section.', 'crisp' ),
	'before_title' => '<h4 class="widget-title widgettitle"><span class="left_background"><span class="right_background">',
	'after_title' => '</span></span></h4>'
) );
add_action("genesis_meta","add_script");
function add_script(){
$q=mysql_query("select var_value from settings where var_name='week_starts'");
$row=mysql_fetch_assoc($q);
$q=mysql_query("select var_value from settings where var_name='max_guest'");
$rows=mysql_fetch_assoc($q);
?>	
	<link rel="stylesheet" href="<?php echo CHILD_URL ?>/js/jquery-ui.css"/>
	<script src="js/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="js/jquery.parallax-1.1.3.js"></script>
    
	<!--<script type="text/javascript" src="js/jquery.localscroll-1.2.7-min.js"></script>
	<script type="text/javascript" src="js/jquery.scrollTo-1.4.2-min.js"></script>   !-->
    
	<script src="<?php echo CHILD_URL ?>/js/jquery-ui.js"></script>
	<script src="js/jquery.jcarousel.min.js"></script>
    <script src="js/jcarousel.skeleton.js"></script>
	<script type="text/javascript" src="js/scrollspy.js"></script>
	<script type="text/javascript" src="<?php echo CHILD_URL ?>/js/jquery.ui.datepicker-sv.js"></script>
    <script type="text/javascript" src="<?php echo CHILD_URL ?>/js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="<?php echo CHILD_URL ?>/js/jquery-ui-sliderAccess.js"></script>
    <script type="text/javascript" src="<?php echo CHILD_URL ?>/js/jquery-ui-timepicker-sv.js"></script>
    
	<script type="text/javascript">
	
		var selectedmenu_id=new Array();
		var selectedmenu_quantity=new Array();
		var selectedmenu_request=new Array();
		var selectedmenu_price=new Array();
		
		/*var catering_id=new Array();
		var catering_quantity=new Array();
		var catering_request=new Array();
		var catering_price=new Array();*/
	
		$(function() {
			//gift card
			$('.btn-get-gift-card').click(function(){
				$('.giftcard-box').fadeIn('fast');
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
				
				$('.giftcard-box .displaymsg').fadeOut();
				
				if(fname == '' || email=='' || lname=='' || phone=='' || giftnum=='' || giftprice=='' || postas=='' || message==''){
					$('.giftcard-box .displaymsg').fadeIn('slow').addClass('errormsg').html('Vänligen fyll i alla obligatoriska fält.');  
				}else if(!validateEmail(email)){
					$('.giftcard-box .displaymsg').fadeIn('slow').addClass('errormsg').html('Ogiltig E-postadress');
				}else{

					$.ajax({
						url: "<?php echo CHILD_URL ?>/get-gift-card.php",
						type: 'POST',
						data: 'fname='+encodeURIComponent(fname)+'&email='+encodeURIComponent(email)+'&lname='+encodeURIComponent(lname)+'&phone='+encodeURIComponent(phone)+'&giftnum='+encodeURIComponent(giftnum)+'&giftprice='+encodeURIComponent(giftprice)+'&postas='+encodeURIComponent(postas)+'&message='+encodeURIComponent(message),
						success: function(value){
							$('.giftcard-box .displaymsg').fadeOut();
							$('.giftcard-box .displaymsg').fadeIn().removeClass('errormsg').addClass('successmsg').html('Din beställning är mottagen.');
					   		$('.giftcard-box').delay( 2000 ).fadeOut('slow');
						}
				 	});
				}
			});
			//end gift card
			$(".resemail").val(jQuery.cookie('limone_email'));
			$(".respassword").val(jQuery.cookie('limone_pass'));
			$( "#menu_list" ).tabs();
			$( "#reservation_list" ).tabs();
			
			$("#book_button").addClass("the-active");
			
			$('.nav-header').scrollspy({ offset: 5000});
			$('[data-spy="scroll"]').each(function () {
				var $spy = $(this).scrollspy('refresh');
			});
			$('.quantity-takeaway, .packagequantity').click(function(){
				$(this).removeAttr('placeHolder');
			}).blur(function(){
				if($(this).val()=='' || $(this).val() == 0){
					$(this).val('').attr('placeholder','0');
				}
			});
			var $root = $('html, body');
			$('#menu-primary_menu a, #wprmenu_menu_ul a, .caterproceed, .nosettings').click(function() {
				$root.animate({
					scrollTop: ($( $.attr(this, 'href') ).offset().top)-86
				}, 1000);
				return false;
			});
			
			$('#menu-item-26 a').addClass('bokamenu');
			$('#menu-item-27 a').addClass('bestmenu');
			
			$('#menu-primary_menu li a').each(function(){
				var href=$(this).attr('href');
				href=href.substr(1,href.length);
				
				$(this).addClass(href);
				
			});
			
			
			var sections = {},
			_height  = $(window).height(),
			thesection = {},
			i = 0;
		
			// Grab positions of our sections 
			$('.home-featured-1 section').each(function(){
				sections[$(this).attr('id')] = $(this).offset().top;
				thesection[$(this).attr('id')] = $(this).attr('id');
			});
			
			sections['text-lunchmeny'] = $('#text-lunchmeny').offset().top;
			thesection['text-lunchmeny'] = 'text-lunchmeny';
		
			$(document).scroll(function(){
				var $this = $(this),
					pos   = $this.scrollTop()-186;
					
				for(i in sections){
					if(sections[i] > pos && sections[i] < pos + _height){
						$('#menu-primary_menu li a').removeClass('li-active');
						$('#menu-primary_menu li a.'+thesection[i]).addClass('li-active');
						
						if(thesection[i]=='text-16'){
							var dis=$('#reservation_form').css('display');
							
							if(dis=='block'){
								$('.bestmenu').removeClass('li-active');
								$('.bokamenu').addClass('li-active');
							}
							else{
								$('.bestmenu').addClass('li-active');
								$('.bokamenu').removeClass('li-active');
							}
							
						}
					}  
				}
			});
			
            
            //$( ".datepicker" ).datepicker({ autoSize: false }).css({'font-size' : 'smaller' });
            
            $("#book_button").click(function(){
	            
	            $("#reservation_form").fadeIn();
	             $("#takeaway_form").fadeOut();
	             /*$("#take_away").css("background","#191919");
	             $("#book_button").css("background","#F1592A");*/
	             
				 $('.signup-box .bokas-btn').show();
				 $('.signup-box .takeaways-btn').hide();
				 
				 $('#menu-primary_menu li a').removeClass('li-active');
		 		 $('#menu-item-26 a').addClass('li-active');
				 
				 $('#take_away').removeClass('the-active');
				 $(this).addClass('the-active');
            });	
            
            $("#take_away").click(function(){
	            
	            $("#takeaway_form").fadeIn();
	             $("#reservation_form").fadeOut();
	             /*$("#take_away").css("background","#F1592A");
	             $("#book_button").css("background","#191919");*/
	            
				 $('.signup-box .bokas-btn').hide();
				 $('.signup-box .takeaways-btn').show();
				 
				 $('#menu-primary_menu li a').removeClass('li-active');
		 		 $('#menu-item-27 a').addClass('li-active');
				 
				 $('#book_button').removeClass('the-active');
				 $(this).addClass('the-active');
            });	
            
	   var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
	   
			if(!isMobile){
				 $('#text-11 .textwidget').parallax("50%", -0.5);
				 $('#text-13').parallax("50%",-0.5);
				 $('#text-15').parallax("50%",-0.2);
				 $('#text-17').parallax("50%",-0.2);
				 $('#text-19').parallax("50%",-0.5);
				 $('#text-21').parallax("50%",-0.5);
				 $('#text-28').parallax("50%",-0.2);
			}
              $(window).scroll(function() {
				 var height = $(window).scrollTop();
                 if($( window ).width()>450){
				 if(height  > 400) {
                        $("#limon_logo").fadeOut(100);
					}else{
						 $("#limon_logo").fadeIn(100);
					}  }
					
					
			});
			
	});
	</script>
    <!-- added -->
    
<style type="text/css">
@font-face {
	font-family: 'Roboto-Medium';
	src: url('<?php echo CHILD_URL;?>/fonts/Roboto-Medium.eot');
	src: url('<?php echo CHILD_URL;?>/fonts/Roboto-Medium.ttf') format('truetype'),
	url('<?php echo CHILD_URL;?>/fonts/Roboto-Medium.woff') format('woff'),
	url('<?php echo CHILD_URL;?>/fonts/Roboto-Medium.svg#Orbitron') format('svg'),
	url('<?php echo CHILD_URL;?>/fonts/Roboto-Medium.eot?#iefix') format('embedded-opentype');
}
@font-face {
	font-family: 'Roboto-Regular';
	src: url('<?php echo CHILD_URL;?>/fonts/Roboto-Regular.eot');
	src: url('<?php echo CHILD_URL;?>/fonts/Roboto-Regular.ttf') format('truetype'),
	url('<?php echo CHILD_URL;?>/fonts/Roboto-Regular.woff') format('woff'),
	url('<?php echo CHILD_URL;?>/fonts/Roboto-Regular.svg#Orbitron') format('svg'),
	url('<?php echo CHILD_URL;?>/fonts/Roboto-Regular.eot?#iefix') format('embedded-opentype');
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
    
      $( "#datepicker" ).datepicker({
	  	  minDate: 0, 
		  maxDate : '<?php echo (strtotime($theend_date) - strtotime($currentDate)) / (60 * 60 * 24); ?>',
		  firstDay: <?php echo $row['var_value']; ?>,
		  onSelect: function(selectedDate){
			  $('.restime').val('');
		  },
		  beforeShowDay: function(date){
				var string = $.datepicker.formatDate('mm/dd/yy', date);
				
				return [checktheDay(date,thedays,string,array)];
			
				
		 }
	   });
	   
	   
	  
     $('a.time-close').click(function(){
          $('.reservation-fade, .time-container').fadeOut();
      });
	 
	 
	 //getting the number of guest
	 var str_options='<option value="">*Sällskapets Storlek</option>';
	 for(i=1;i<=<?php echo $rows['var_value']; ?>;i++){
		str_options+='<option value="'+i+'">'+i+'</option>';
     }
	 
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
	 
	 
	 $('.forgot').click(function(){
	    $('.forgot-box').removeClass('errormsg').html();   
	 	$('.fade, .forgot-box').fadeIn();
		gotoModal();
	 
	 });
	 
	 $('.forgot-box .btn').click(function(){
	 	var email=$('.forgotemail').val();
		
		$('.forgot-box .displaymsg').fadeOut();
		
		if(email!=''){
			
			if(validateEmail(email)){
				
				$('.forgot-box .displaymsg').fadeIn().html('<img src="<?php echo CHILD_URL; ?>/images/rectangular.gif" style="margin:0 auto;">').removeClass('errormsg');
				
				$.ajax({
					url: "<?php echo CHILD_URL ?>/forgot-password.php",
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
		
	 });
	 
	 
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
  
</script>
<script type="text/javascript" src="<?php echo CHILD_URL ?>/scripts/numeric.js"></script>
<script type="text/javascript">
  $(function(){
       $('.resguest, .restable, .quantity, .packagequantity').numeric();
	   
	   var d = new Date();
		var hour = d.getHours();     
		var mins = d.getMinutes();
		
	    $( "#datepicker3" ).datetimepicker({
			minDate: 0,
			firstDay: <?php echo $row['var_value']; ?>,
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
add_action("genesis_site_title","added_logo");
function added_logo(){
	
   // echo '<div id="limon_logo"></div>';
		
}
function menutag_func($atts){
/*extract( shortcode_atts( array(
		'foo' => 'something',
		'bar' => 'something else',
	), $atts ) );*/
global $wpdb;
// function getCurrencyShortname($id){
// 	$q=mysql_query("select shortname from currency where id=".$id);
// 	$r=mysql_fetch_assoc($q);
	
// 	return strtolower($r['shortname']);
// }
$menys='';
$query=mysql_query("select * from category where deleted=0 order by IF( `order` = '', 1, 0), `order`, id");
if(mysql_num_rows($query) > 0){
	while($r=mysql_fetch_assoc($query)){
		
		//Start select menus direct from main category
			$qll=mysql_query("select name,description,id,price,currency_id,discount,type from menu where cat_id=".$r['id']." and type<>2 and deleted=0 and featured=1 order by IF( `order` = '', 1, 0), `order`, id") or die(mysql_error());
		
			$menys.= '<div class="meny_division column_'.$r['id'].'">';
			$menys.= '<h4 style="margin-bottom: 5px !important; font-size: 25px;">'.$r['name'].'</h4>
					<span style="font-size: 16px; padding:0 0 20px; color:black; display:block;">'.$r['description'].'</span>';
			
			if(mysql_num_rows($qll) > 0){
				while($rll=mysql_fetch_assoc($qll)){
					
					$menys.='<div class="section-devider">';
					
					$menys.='<div class="left-side"><h4>'.$rll['name'].'</h4>
							<p style="width: 80% !important;">'.$rll['description'].'</p></div>';
							
					$menys.='<span style="float:right; font-size: 20px; padding: 0 10px 0 0; color:black; font-weight:bold;">'.number_format($rll['price']).' '.getCurrencyShortname($rll['currency_id']).'</span>';
					$menys .= '</div>';
				}
			}
			//End select menus direct from main category
		
		$qq=mysql_query("select * from sub_category where category_id=".$r['id']." order by IF( `order` = '', 1, 0), `order`, id") or die(mysql_error());
		
		if(mysql_num_rows($qq)){		
			
			while($rr=mysql_fetch_assoc($qq)){
			
				$qr=mysql_query("select name,description,id,price,currency_id,discount,type from menu where sub_category_id=".$rr['id']." and type<>2 and deleted=0 and featured=1 order by IF( `order` = '', 1, 0), `order`, id") or die(mysql_error());
				
				if(mysql_num_rows($qr) > 0){
					
					$menys.='<h5 style="margin-bottom: 10px; margin-top:10px; float:left; width:100%;"><i style="display:block;">'.$rr['name'].'</i></h5>';
					
					while($row=mysql_fetch_assoc($qr)){
						$menys .= '<div class="section-devider">';
						
						$price=$row['price'];
						/*if(strlen($row['type'])>1){
							
							$discount=$row['discount']/100;
							$price=$price-($price*$discount);
						}*/
						
						$menys.='<div class="left-side"><h4>'.$row['name'].'</h4>
								<p style="width: 80% !important;">'.$row['description'].'</p></div>';		
						$menys .= '<span style="float:right; font-size: 20px;
						padding: 0 10px 0 0; color:black; font-weight:bold;">'.number_format($price).' '.getCurrencyShortname($row['currency_id']).'</span>';		
								$menys .= '</div>';	
					}
				
				}
			
			}
			
			$menys.='</div>';
			
		}
		
	}
}
return '<div style="overflow:hidden; padding-top:30px;">'.$menys.'</div>';
}
add_shortcode( 'menutag', 'menutag_func' );
add_filter('widget_text', 'do_shortcode');
function getCurrencyShortname($id){
	$q=mysql_query("select shortname from currency where id=".$id);
	$r=mysql_fetch_assoc($q);
	
	return strtolower($r['shortname']);
}
function takeAway(){
$menys='';
$query=mysql_query("select * from category where deleted=0 order by IF( `order` = '', 1, 0), `order`, id");
if(mysql_num_rows($query) > 0){
	while($r=mysql_fetch_assoc($query)){
		
			//Start select menus direct from main category
			$qrr=mysql_query("select name,description,id,price,currency_id,discount,discount_unit,type from menu where cat_id=".$r['id']." and type<>'1' and deleted=0 and featured=1 order by IF( `order` = '', 1, 0), `order`, id") or die(mysql_error());
		
			$menys.='<div class="meny_division column_'.$r['id'].'"> 
					<h4 style="margin-bottom: 5px !important; font-size: 25px;">'.$r['name'].'</h4>
					<span style="font-size: 16px; padding:0 0 32px; color:black; display:block;">'.$r['description'].'</span>';
			
			if(mysql_num_rows($qrr) > 0){
				while($rrr=mysql_fetch_assoc($qrr)){
					
					$price=$rrr['price'];
						if(strlen($rrr['type'])>1){
							if($rrr['discount_unit']=="percent"){
								$discount=$rrr['discount']/100;
								$price=$price-($price*$discount);
							}else{
								$price=$price-$rrr['discount'];
							}
					}
					
					$menys.='<div class="section-devider">';
						
						$menys.='<div class="left-side">
						<h4 class="menu-name">'.$rrr['name'].'</h4>
						<strong style="font-size: 20px; margin-left: 12px;">'.number_format($price).' '.getCurrencyShortname($rrr['currency_id']).'</strong>
						<p style="width: 80% !important;">'.$rrr['description'].'  <span style="display:none" class="subtotal-'.$rrr['id'].'" data-rel="'.$price.'">0</span></p> </div>';		

						$menys .= '  <span class="outerinputs">
						
						<img src="'.CHILD_URL.'/images/lagtill.png" class="takeimg tacart" data-id="'.$rrr['id'].'">
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
		
		$qq=mysql_query("select * from sub_category where category_id=".$r['id']." order by IF( `order` = '', 1, 0), `order`, id") or die(mysql_error());
		
		if(mysql_num_rows($qq)){
			
			while($rr=mysql_fetch_assoc($qq)){
			
				$qr=mysql_query("select name,description,id,price,currency_id,discount,discount_unit,type from menu where sub_category_id=".$rr['id']." and type<>'1' and deleted=0 and featured=1 order by IF( `order` = '', 1, 0), `order`, id") or die(mysql_error());
				
				if(mysql_num_rows($qr) > 0){
					
					$menys.='<h5 style="margin-bottom: 10px; margin-top:10px; float:left; width:100%;"><i style="display:block;">'.$rr['name'].'</i></h5>';
					
					while($row=mysql_fetch_assoc($qr)){
						
						$price=$row['price'];
						if(strlen($row['type'])>1){
							if($row['discount_unit']=="percent"){
								$discount=$row['discount']/100;
								$price=$price-($price*$discount);
							}else{
								$price=$price-$row['discount'];
							}
						}
						$menys.='<div class="section-devider">';
						
						$menys.='<div class="left-side">
						<h4 class="menu-name">'.$row['name'].'</h4>
						<strong style="font-size: 20px; margin-left: 12px;">'.number_format($price).' '.getCurrencyShortname($row['currency_id']).'</strong>
									<p style="width: 80% !important;">'.$row['description'].'  <span style="display:none" class="subtotal-'.$row['id'].'" data-rel="'.$price.'">0</span></p> </div>';		
						// $menys .= '';	
						$menys .= '  <span class="outerinputs">
						
						<img src="'.CHILD_URL.'/images/lagtill.png" class="takeimg tacart" data-id="'.$row['id'].'">
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
			
			$menys.='</div>';
			
		}
		
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
add_shortcode( 'takeaway', 'takeAway' );
add_filter('widget_text', 'do_shortcode');
add_action('wp_footer', 'script_takeaway');
function script_takeaway(){
		?>
			<script type="text/javascript">
				  $(function(){
				  	   $('.quantity-takeaway').numeric();
					   $('.quantity-takeaway').keyup(function(){
							
					   		 $(this).val($(this).val().replace('.','').replace('-',''));
				   
							   var val = $(this).val();
							   var id = $(this).attr('data-rel');
							   var price = Number($('.subtotal-'+id).attr('data-rel'));
							   	$('.subtotal-'+id).html((val*price));
							   	computeTotal();
						   });
					    function computeTotal(){
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
						   
						   	$('.addme_takeaway').click(function(){
								var theclass = $(this).attr('data-rel');
								var val = Number($('.quantity-'+theclass).val());
								
								val = val+1;
								
								$('.quantity-'+theclass).val(val);
							   	var price = Number($('.subtotal-'+theclass).attr('data-rel'));
							   	$('.subtotal-'+theclass).html((val*price));
							   	computeTotal();
								
							}); 	
							
							$('.subtractme_takeaway').click(function(){
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
							
							//for lunchmeny
							$('.addme_lunchmeny').click(function(){
								var theclass = $(this).attr('data-rel');
								var val = Number($('.lmquantity-'+theclass).val());
								
								val = val+1;
								
								$('.lmquantity-'+theclass).val(val);
							   	var price = Number($('.lmsubtotal-'+theclass).attr('data-rel'));
							   	$('.lmsubtotal-'+theclass).html((val*price));
							   	//computeTotal();
								
							}); 
							
							$('.subtractme_lunchmeny').click(function(){
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
function getCurrentCurrency(){
	//get currency
	$qc=mysql_query("select shortname from currency where deleted=0 and set_default=1");
	$rc=mysql_fetch_assoc($qc);
	return strtolower($rc['shortname']);
}
function getCateringMenus($catid,$subcatid,$catprice,$tobeselected){ 
	$result = '';
	$theid=$catid;
	$left='';
	$menulist='';
	
	$query = 'and catering_category_id='.$catid;
	
	if($subcatid!=0){
		$query = 'and catering_subcategory_id='.$subcatid;
		$theid='-'.$subcatid;
	}
	
	//getting the menus
	$qqq=mysql_query("select * from catering_menu where deleted=0 $query");
	
	if(mysql_num_rows($qqq) > 0){
		
		while($rrr=mysql_fetch_assoc($qqq)){
			
			$price='';
			
			if($catprice==''){
				
				$left='toleft';
				$menulist='goleft';
				
				//get currency
				$currency = getCurrentCurrency();
			
				$qp = mysql_query("select * from catering_menu_price where catering_menu_id='".$rrr['id']."'") or die(mysql_error());
				if(mysql_num_rows($qp) > 0){
					
					$cnt=0;
					while($rp=mysql_fetch_assoc($qp)){
						
						$cnt++;
						
						$pricedesc='';
						if($rp['price_description']!=''){
							$pricedesc= ' - '.$rp['price_description'];
						}
						
						$radiobtn='';
						if(mysql_num_rows($qp) > 1){
							$checked='';
							if($cnt==1){
								$checked='checked="checked"';
							}
							
							$radiobtn='<input type="radio" name="pr-'.$r['id'].'" '.$checked.' class="theprice" data-id="'.$rp['price'].'">';
						}
						
						$price.=$radiobtn.'<span>'.$rp['price'].'</span>&nbsp;'.$currency.' / '.$rp['price_type'].$pricedesc.'<br>';
					}
					
					$price='<label class="forpricing forpricing'.$rrr['id'].'">'.$price.'</label>';
					
				}
			
			}
			
			//data-id is the menu id
			
			$inputtype='';
			if($tobeselected==1){
				$inputtype='<input type="radio" name="men'.$theid.'" class="radiomenus menu'.$theid.'" data-id="'.$rrr['id'].'">';
			}
			else if($tobeselected>1){
				
				//data-rel is the category or subcategory id
				//data-title is the limit of menus to be selected
				//data-id is the menu id
				$inputtype='<input type="checkbox" class="checkmenus menu'.$theid.'" data-rel="'.$theid.'" data-title="'.$tobeselected.'" data-id="'.$rrr['id'].'">';
			}
			
			$quantityfield='';
			
			if($catprice==''){
			//data-rel is the category id
			
				if($price!=''){
			
					$quantityfield='
							<div class="quantitybox">	
								<input type="button" value="+" class="addme" data-rel="'.$rrr['id'].'">									
				 				<input type="text" placeHolder="0" class="packagequantity quan-'.$rrr['id'].'" data-rel="'.$rrr['id'].'" value="" data-title="0">
				 				<input type="button" value="-" class="subtractme" data-rel="'.$rrr['id'].'">
							</div>
							';
				}
			}
			
			$result.='<div class="menulist '.$menulist.'">';
			$result.=$inputtype.'<h4 class="cmenuname '.$left.'" data-id="'.$rrr['id'].'">'.$rrr['name'].'</h4>'.$price;
			if($rrr['description']!=''){
				$result.='<span class="cmenudesc">'.$rrr['description'].'</span>'; 
			}
			$result.='</div>'.$quantityfield;
			$result.='<div class="clear"></div>';
		}
		
	}
	
	return '<div class="cmenus">'.$result.'</div>';
	
}
function theCatering($atts){
	
	$result='';
	
	//$result.='<div class="checkoutdiv"><input type="button" value="Checkout" class="checkout"></div>';
	
	//getting the categories
	$q = mysql_query("select * from catering_category where deleted=0");
	
	if(mysql_num_rows($q) > 0){
		
		$counter=0;
		
		while($r=mysql_fetch_assoc($q)){
			
			$counter++;
			
			//get currency
			$currency = getCurrentCurrency();
			
			$price='';
			$getprice='';
			$qp = mysql_query("select * from catering_category_price where catering_category_id='".$r['id']."'");
			if(mysql_num_rows($qp) > 0){
				
				$cnt=0;
				while($rp=mysql_fetch_assoc($qp)){
					
					$cnt++;
					
					if($rp['price']!=0){
						$pricedesc='';
						if($rp['price_description']!=''){
							$pricedesc= ' - '.$rp['price_description'];
						}
						
						$getprice = $rp['price'];
						
						$radiobtn='';
						if(mysql_num_rows($qp) > 1){
							
							$checked='';
							if($cnt==1){
								$checked='checked="checked"';
							}
							
							$radiobtn='<input type="radio" name="pr-'.$r['id'].'" '.$checked.' class="theprice" data-id="'.$rp['price'].'">';
						}
						
						$price.=$radiobtn.'<span>'.$rp['price'].'</span>&nbsp;'.$currency.' / '.$rp['price_type'].$pricedesc.'<br>';
					}
					
				}
			}
			
			//for the quantity field
			
			$qm=mysql_query("select cmp.price from catering_menu as cm, catering_menu_price as cmp where cm.id=cmp.catering_menu_id and cm.catering_category_id='".$r['id']."'");
			$pricecount = mysql_num_rows($qm);
			
			$quantityfield = '';
			
			if($pricecount==0){
				
				//data-rel is the category id
				$quantityfield='
							<div class="quantitybox">
								<input type="button" value="+" class="addme" data-rel="'.$r['id'].'">	
				 				<input type="text" placeHolder="0" class="packagequantity quan-'.$r['id'].'" data-rel="'.$r['id'].'" value="" data-title="'.$r['minimum_order'].'">
								<input type="button" value="-" class="subtractme" data-rel="'.$r['id'].'">	
							</div>
							';
			}
			
			//check if theres a subcategory
			$qc=mysql_query("select id from catering_subcategory where catering_category_id='".$r['id']."'");
			$totalsubcat=mysql_num_rows($qc);
			
			//data-rel is the limit of menus to be selected
			//data-title is the total number of subcategories a category have.
			
			$result.='<div class="cateringbox cateringbox'.$r['id'].'">';
			$result.='<h4 style="margin-bottom:5px;" class="ccatname ccatname'.$r['id'].'" data-rel="'.$r['number_selected'].'" data-title="'.$totalsubcat.'">'.$r['name'].'</h4>';
			if($r['description']!=''){
				$result.='<span class="ccatdesc">'.$r['description'].'</span>';
			}
			
			$result.='<div class="clear"></div>';
			
			//getting the subcategories
			$qq=mysql_query("select * from catering_subcategory where deleted=0 and catering_category_id='".$r['id']."'");
			 
			if(mysql_num_rows($qq) > 0){
			
				while($rr=mysql_fetch_assoc($qq)){
					
					//data-rel is the limit of menus to be selected
					
					$result.='<div class="csubcatname fromcat'.$r['id'].'" data-rel="'.$rr['number_selected'].'" data-id="'.$rr['id'].'"><h5>'.$rr['name'].'</h5>';
					if($rr['description']!=''){
						$result.='<span class="csubcatdesc"> - '.$rr['description'].'</span>';
					}
					$result.='</div>';
					
					$result.='<div class="clear"></div>';
					
					$result.=getCateringMenus($r['id'],$rr['id'],$price,$rr['number_selected']);
					
					$result.='<div class="clear"></div>';
					
				}
			
			}
			else{
				$result.=getCateringMenus($r['id'],0,$price,$r['number_selected']);
				
				$result.='<div class="clear"></div>';
			}
			
			if($r['added_description']!=''){
				$result.='<span class="addeddesc">'.$r['added_description'].'</span>';
			}
			
			$result.='<div class="clear"></div>';
			
			$result.='<div class="forpricing">';
			
			if($price != ''){
				$result.='<span class="cprice">
							<label>Pris:</label> <label>' .$price.'</label></span>';
			}
			
			//data-id is the category / package price
			//data-rel is the category / package id
			
			//$result.='<div class="clear"></div><a href="javascript:void(0)" class="addcart" data-rel="'.$r['id'].'" data-id="'.$getprice.'">LÄGG I VARUKORG</a>'.$quantityfield;
			
			$result.='<div class="tocart" style="position:relative; top:-20px;"><img src="'.CHILD_URL.'/images/lagtill.png" style="float: right; margin: 10px 0 0;" class="addcart" data-rel="'.$r['id'].'" data-id="'.$getprice.'">'.$quantityfield.'</div>';
			
			//$result.='<div class="tocart" style="position:relative; top:-20px;"><img src="http://www.limoneristorante.se/wp-content/uploads/2014/08/one.png" style="float: right; margin: 20px 0 0;"><a href="javascript:void(0)" class="addcart" data-rel="'.$r['id'].'" data-id="'.$getprice.'">LÄGG I VARUKORG</a>'.$quantityfield.'</div>';
			
			$result.='</div>';
			
			$result.='<div class="clear"></div>';
			
			$result.='</div>';
			
			if($counter>1 & $counter%2==0){
				$result.='<div class="clear"></div>';
			}	
			
		}
		
		
		
	}
	else{
		$result = 'Category not found.';
	}
	
	$result.='<div class="checkoutdiv"><input type="button" value="Till Kassan" class="checkout"></div>';
	
	return '<div class="cateringmenus">'.$result.'</div>';
	
}
add_shortcode( 'catering', 'theCatering' );
add_filter('widget_text', 'do_shortcode');
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
				 $(function(){
					 
					 	if(!are_cookies_enabled()){
							$('.clickcatering').addClass('clickcateringdisable').html('Please enable cookie of your browser to proceed.').removeAttr('data-title').removeClass('clickcatering');
							
							$('.pay').val('Please enable cookie of your browser to proceed.').removeAttr('data-title').removeClass('pay');
						}
						
						//check expiration of cookie for take away
					
						setInterval(function() {
							
							/*var tacheck=0;
								
							$.ajax({
									url: "<?php //echo CHILD_URL; ?>/check-settings.php",
									type: 'POST',
									async:false,
									data: 'tablename='+encodeURIComponent('takeaway_settings'),
									success: function(value){
										tacheck=value.trim();
									}
							});
							
							if(tacheck==1){
								
								var tac = jQuery('.takeawaycart').css('display');
								if(tac!='block'){
									console.log(tac);
									jQuery.removeCookie('takeaway_id', { path: '/' });
									//window.location.reload();
								}
								
							}
									*/			
									
							if(jQuery.cookie('takeaway_id')==undefined || jQuery.cookie('uniqueid')==undefined || jQuery.cookie('lunchmeny_id')==undefined){
								
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
						
						
						//for lunch meny cart
						
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
											
											/*var opt_cookie =unescape(jQuery.cookie('cart_opt'));
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
											}*/
										}
								});	
							}
							/*else{
								$('.fade, .cart-detail').fadeIn();
								$('.takeawaycart-detail').html('<div class="tacart-inner"><a href="javascript:void(0)" class="close-cart" onclick="closetheCart()">Close</a><div class="clear"></div><span class="emptycart">You have an empty cart.</span></div>').center();
							}*/
								
						});
						
						
						
						//for the cart
						setInterval(function(){ 
							
								var count = '';
								
								
								$.ajax({
										url: "<?php echo CHILD_URL ?>/countItem.php",
										type: 'POST',
										async: false,
										data: 'unique_id='+encodeURIComponent(jQuery.cookie('uniqueid'))+'&uniq='+encodeURIComponent(jQuery.cookie('takeaway_id'))+'&lmuniq='+encodeURIComponent(jQuery.cookie('lunchmeny_id')),
										success: function(value){
											count=value;
										}
								});	
								
								
								
								var str = count.toString().split('*');
								
								//for catering
								if(Number(str[0]) > 0){
									$('.menucart').fadeIn();
									$('.menucart span').html(str[0]);
								}
								else{
									$('.menucart').fadeOut();
									$('.menucart span').html(str[0]);
								}
								
								//for takeaway
								if(Number(str[1]) > 0){
									
									$('.takeawaycart').fadeIn();
									$('.takeawaycart span').html(str[1]);
								}
								else{
									$('.takeawaycart').fadeOut();
									$('.takeawaycart span').html(str[1]);
									
								}
								
								
								//for lunchmeny
								if(Number(str[2]) > 0){
									
									$('.lunchmenycart').fadeIn();
									$('.lunchmenycart span').html(str[2]);
								}
								else{
									$('.lunchmenycart').fadeOut();
									$('.lunchmenycart span').html(str[2]);
									
								}
								
							
								/*if(count>0){
									$('.menucart').fadeIn();
									$('.menucart span').html(count);
								}
								else{
									$('.menucart').fadeOut();
									$('.menucart span').html(count);
								}*/
								
						}, 4000);
						
						
						//for take away cart
						
						$('.tacart').click(function(){
							var menuid = $(this).attr('data-id');
							var uniq = jQuery.cookie('takeaway_id');
							var quan = Number($('.quantity-'+menuid).val());
							var price = $('.quantity-'+menuid).attr('data-title');
							
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
								
									$.ajax({
											url: "<?php echo CHILD_URL; ?>/takeaway-orders.php",
											type: 'POST',
											data: 'uniq='+encodeURIComponent(uniq)+'&menu_id='+encodeURIComponent(menuid)+'&quan='+encodeURIComponent(quan)+'&price='+encodeURIComponent(price),
											success: function(value){
												$('.takeawaycart span').html(value);
											}
									});
									
									$('.takeawaycart').fadeIn();
									
									var cart = $('.takeawaycart');
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
							var quan = Number($('.lmquantity-'+menuid).val());
							var price = $('.lmquantity-'+menuid).attr('data-title');
							
							
							
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
											url: "<?php echo CHILD_URL; ?>/takeaway-orders.php",
											type: 'POST',
											data: 'uniq='+encodeURIComponent(uniq)+'&menu_id='+encodeURIComponent(menuid)+'&quan='+encodeURIComponent(quan)+'&price='+encodeURIComponent(price)+'&lunchmeny=true',
											success: function(value){
												$('.lunchmenycart span').html(value);
											}
									});
									
									$('.lunchmenycart').fadeIn();
									
									var cart = $('.lunchmenycart');
									var imgtodrag = $(this).parent('.menyoutputs').find("img").eq(0);
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
									
									$('.step-2-wrapper').html('<div class="steps-container"><h3 class="thestep-label">Lunch Meny</h3><div class="steps-box step-2" style="height: 200px !important;"><p>Tyvärr går det inte att beställa take away via hemsidan för tillfället. Vänligen ring eller maila oss för mer information.</p><a href="#text-18" class="nosettings btn" onclick="$(\'.steps-container\').fadeOut();">Ok</a><div class="clear"></div></div></div>');
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
							
				 });
			</script>
		<?php
}
function booking_content($atts){
	$q=mysql_query("select var_value from settings where var_name='new_content'");
	$r=mysql_fetch_assoc($q);
	return $r['var_value'];
}
add_shortcode( 'office_hours', 'booking_content' );
add_filter('widget_text', 'do_shortcode');


function lunchmeny($atts){
	
	function dayName($date){
		
		$dayname=array('Mon'=>'Mån','Tue'=>'Tis','Wed'=>'Ons','Thu'=>'Tor','Fri'=>'Fre','Sat'=>'Lör','Sun'=>'Sön');
		
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
		
	}
	
	$menu_week_res = mysql_fetch_assoc($menu_week_qry);

	echo $menu_week_sql.'<br>'.$menu_week_res['id']; //xxx

?>
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
						ORDER BY `order`, id ASC";
		$courses_qry = mysql_query($courses_sql);// or die($courses_sql);
		$courses_num = mysql_num_rows($courses_qry);	
        ?>
        
        <div class="themenu-outer">
        	<?php if($courses_num>0){?>
            
            	<div class="thesepa"></div>
            
                <div class="themenu-container">
                    <!--<h2>Mån-Fre 
                    (<?php 
                    /*echo date('M d',strtotime('monday this week'));
                    if($menu_week_res['all_in']==0){
                        echo ' to  '.date('M d',strtotime('friday this week')); 
                    }else{
                        echo ' onwards';
                    }*/
                    ?>)</h2>-->
                    
                    <?php
					if($courses_num>0){
						while($courses_res = mysql_fetch_assoc($courses_qry)){
							
							$lunchprice = $courses_res['takeaway_price'];
					?>
                        <div class="themenu-inner">
                            <h3><?php echo $courses_res['name']; ?></h3>
                            <p>
							<?php 
								//get menu price
								if($courses_res['price']!=0){
									$menu_price = '<span class="themi-price">' . number_format($courses_res['price']).' '.$currency_res['shortname'] . '</span>';						}
							
								$menu_description = addslashes($courses_res['description']); 
								$pattern = array ('/(.*<p .*>).*(<\/p>.*)/','/(.*<p>).*(<\/p>.*)/');
		
								
								$menu_details = strip_tags($menu_description, '<span><strong><a><h1><h2><h3><h4><i>') . $menu_price;
								$replacement = '$1'.$menu_details.'$2';
								
								if(strlen($menu_description) != strlen(strip_tags($menu_description))){
									//substr($menu_description, -2) == '<p'
									if(substr($menu_description, 0, 2) == '<p'){
										echo stripslashes(preg_replace($pattern, $replacement, $menu_description));
									}else{
										echo '<p>' .stripslashes($menu_description) . $menu_price . '</p>';
									}
								}else{
									echo stripslashes($menu_description) . $menu_price;
								}
	
							?>
                            </p>
                            <span style="display:none" class="lmsubtotal-<?php echo $courses_res['id'];?>" data-rel="<?php echo $lunchprice; ?>"></span>
                            
                            <?php if($courses_res['takeaway']==1 & $lunchprice>0){ ?>
                            <div class="menyadd">
                            	<span class="menyoutputs">
						
                                <img src="<?php echo CHILD_URL ?>/images/lagtill.png" class="menyimg lmcart" data-id="<?php echo $courses_res['id'];?>">
                                <span id="lminputs">						
                                <input type="button" value="+" class="addme_lunchmeny" data-rel="<?php echo $courses_res['id'];?>">
                                <input type="text" class="quantity-lunchmeny lmquantity-<?php echo $courses_res['id'];?>" data-rel="<?php echo $courses_res['id'];?>" placeHolder="0" data-title="<?php echo number_format($lunchprice); ?>" />
                                <input type="button" value="-" class="subtractme_lunchmeny" data-rel="<?php echo $courses_res['id'];?>">
                                </span>
						
								</span>
                            </div>
                            <?php } ?>
                            
                        </div>
                    <?php
						}
					}
					?>
                 </div>
                 <div class="thesepa"></div>
             <?php } 
			 
			 	$first_day = strtotime('monday this week');
				$last_day = strtotime('saturday this week');
				$the_day = $first_day;
				
				$week_days = 6;
				
				$lunchprice=0;
				
				for($d=1; $d<=$week_days; $d++){
			
					$courses_sql = "SELECT * FROM menu_lunch_items WHERE 
									menu_id=".$menu_week_res['id']."
									".$all_in_switch."
									AND specific_day='".date('D', $the_day)."' 
									AND deleted=0
									ORDER BY `order`, id ASC";
			
					$courses_qry = mysql_query($courses_sql);
					$num_courses = mysql_num_rows($courses_qry);
			
					if($num_courses>0){
					
			?>
            		<div class="themenu-container">
                  
                    <?php
					if($num_courses>0){
						while($courses_res = mysql_fetch_assoc($courses_qry)){
							
							$lunchprice = $courses_res['takeaway_price'];
					?>
                        <div class="themenu-inner">
                            <h3><?php echo $courses_res['name']; ?></h3>
                            <p>
								<?php echo stripslashes($courses_res['description']); ?>
                                <?php if($courses_res['price']>0 & $courses_res['price']!=''){?>
                            	<span class="themi-price"><?php echo number_format($courses_res['price']).' '.$currency_res['shortname']; ?></span>
                                <?php } ?>
                            </p>
                            
                            <span style="display:none" class="lmsubtotal-<?php echo $courses_res['id'];?>" data-rel="<?php echo $lunchprice; ?>"></span>
                            
                            <?php if($courses_res['takeaway']==1 & $lunchprice>0){ ?>
                            <div class="menyadd">
                            	<span class="menyoutputs">
						
                                <img src="<?php echo CHILD_URL ?>/images/lagtill.png" class="menyimg lmcart" data-id="<?php echo $courses_res['id'];?>">
                                <span id="lminputs">						
                                <input type="button" value="+" class="addme_lunchmeny" data-rel="<?php echo $courses_res['id'];?>">
                                <input type="text" class="quantity-lunchmeny lmquantity-<?php echo $courses_res['id'];?>" data-rel="<?php echo $courses_res['id'];?>" placeHolder="0" data-title="<?php echo number_format($lunchprice); ?>" />
                                <input type="button" value="-" class="subtractme_lunchmeny" data-rel="<?php echo $courses_res['id'];?>">
                                </span>
						
								</span>
                            </div>
                            <?php } ?>
                                
                        </div>
                    <?php
						}
					}
					?>
                 </div>
                 <div class="thesepa"></div>
            <?php			
					}
				
			 ?>
             
              <?php
					$the_day = strtotime(date('Y-m-d', $the_day).' +1 day');
				}	
        	 ?>
             
      </div>
     
     <div class="footerlm"><?php echo stripslashes($menu_week_res['note_footer']); ?></div>
        	
<?php	
	
}
add_shortcode( 'lunch_meny', 'lunchmeny' );
add_filter('widget_text', 'do_shortcode');