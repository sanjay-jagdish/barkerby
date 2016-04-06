<?php session_start();
	// cookie settings
	if(isset($_COOKIE["webmin_id"])){
		
		$_SESSION['login']['id']=$_COOKIE['webmin_id'];
		$_SESSION['login']['email']=$_COOKIE['email'];
		$_SESSION['login']['name']=$_COOKIE['name'];
		$_SESSION['login']['type']=$_COOKIE['type'];
		
    }
	else{
		 header('Location:/webmin/');exit;
	}
	
	include 'config/config.php';
	
	
	function removeDashTitle($title){
	  return str_replace('-',' ',$title);
	}
	
	//delete unnecessary images
	$imgs = scandir('uploads');
	
	for($i=0;$i<count($imgs);$i++){
		if(strlen($imgs[$i]) > 2){
			$q=mysql_query("select id from menu where image='".$imgs[$i]."'");
			if(mysql_num_rows($q) == 0){
				unlink('uploads/'.$imgs[$i]);
			}
		}
	}
	
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Mise en Place</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>-->
<script type="text/javascript" src="scripts/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="scripts/jquery_cookie.js"></script>
<script type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/ajaximage/scripts/jquery.form.js"></script>
<script type="text/javascript" src="scripts/numeric.js"></script>
<script src="scripts/lightbox/js/lightbox-2.6.min.js" type="text/javascript"></script>
<link href="scripts/lightbox/css/lightbox.css" rel="stylesheet" />
<!-- added scripts -->
<link rel="stylesheet" media="all" type="text/css" href="css/jquery-ui.css" />
<script type="text/javascript" src="scripts/jquery-ui.min.js"></script>
<script src="scripts/jquery-ui.js"></script>
<script type="text/javascript" src="scripts/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="scripts/jquery-ui-sliderAccess.js"></script>
<!-- end added -->
<!-- for localization(swedish) -->
<script src="scripts/jquery.ui.datepicker-sv.js"></script>
<!-- timepicker localization -->
<script type="text/javascript" src="scripts/jquery-ui-timepicker-sv.js"></script>
<?php
if( $_GET['page'] == 'controlpanel' ){
	?>
<!-- added for calendar -->
<meta charset="utf-8">
<link rel="stylesheet" href="tablechart/css/bootstrap.css">
<link rel="stylesheet" href="tablechart/css/bootstrap-responsive.css">
<link rel="stylesheet" href="tablechart/styles.css">
<script src="tablechart/libs/underscore-min.js"></script>
<!-- <script src="tablechart/libs/jquery.min.js"></script> -->
<script src="tablechart/libs/jquery-migrate-1.2.1.min.js"></script>
<script src="tablechart/libs/jquery.scrollTo.js"></script>
<script src="tablechart/libs/bootstrap.js"></script>
<!-- for localization(swedish) -->
<script type="text/javascript" src="tablechart/libs/jquery.ui.datepicker-sv.js"></script>
<script src="tablechart/libs/jquery.dataTables.js"></script>
<script src="tablechart/libs/dataTables.scroller.js"></script>
<script src="tablechart/libs/FixedColumns.js"></script>
<!--script src="tablechart/main.js"></script -->
<script src="tablechart/js/jQuerytypeahead/typeahead.js"></script>
<link href="tablechart/js/jQuerytypeahead/examples.css" rel="stylesheet" type="text/css" />
<script src="tablechart/js/jquery-ui-timepicker-0.3.3/jquery.ui.timepicker.js"></script>
<link rel="stylesheet" href="tablechart/js_css/jquery-ui.css">
<script src="tablechart/js_css/jquery-ui.js"></script>
<link rel="stylesheet" media="all" type="text/css" href="css/jquery-ui.css" />
<script type="text/javascript" src="scripts/jquery-ui.min.js"></script>
<script src="scripts/jquery-ui.js"></script>
<!-- edit booking -->
<link rel="stylesheet" href="css/jquery-ui.css">
<!-- end added for calendar -->
<?php
	}
	// end if get page = controlpanel
?>
<?php
	$page_active=$_GET['page'];
	
	/*if(isset($_GET['parent'])){
		$page_active=$_GET['parent'];
	}*/
	
	if(isset($_GET['subpage'])){
		$page_active='';
	}
	
?>
<script src="scripts/js/ion.sound.js"></script>
<script type="text/javascript">
	function forBlinking(){
		jQuery('.blink').css({'background':'#ffc4c4','color':'#333'}); 
			
			jQuery.ajax({
				 url: "pages/count-orders.php",
				 type: 'POST',
				 success: function(value){
					value = Number(value);
					if( value > 0){
						 if(jQuery('body').attr('aria-label')==1){
							//ion.sound.pause("notification");
							jQuery('body').attr('aria-valuetext',1);
							jQuery('audio')[0].pause();
						 }
						 else{
							jQuery('body').attr('aria-valuetext',0); 
							//ion.sound.play("notification");
							jQuery('audio')[0].play();
						 }
						 
						 jQuery(".orders-li span").html("<div id='notif'>"+value+"</div>");
						
					}	
					else{
						console.log('pause - '+value);
						//ion.sound.pause("notification");
						jQuery('audio')[0].pause();
					}
				 }
			});
	}
	jQuery(function(){
		 jQuery("a.sidebar-<?php echo $_GET['page']; ?>").css({'color':'white', 'backgroundColor':'#2c2c2c'}); 
		 
		 jQuery(".nav ul li").removeClass('active');   
		 jQuery(".<?php echo $page_active; ?>-li").addClass('active');   
		 
		 //jQuery('<audio class="notificationAudio"><source src="sounds/notification.ogg" type="audio/ogg"><source src="sounds/notification.mp3" type="audio/mpeg"><source src="sounds/notification.wav" type="audio/wav"></audio>').appendTo('body');
		 
		 ion.sound({
            sounds: [
                {name: "notification"}
            ],
            path: "scripts/sounds/",
            preload: true,
            volume: 1.0
        });
		
		jQuery('.orders-li').click(function(){
			forBlinking();
		});
	
		setInterval(function(){ 
			
			jQuery('.orders-li').trigger( "click" );
			
			//forBlinking();
			
		}, 2000);
			
		//setInterval(function(){ jQuery('.blink').css({'background':'#E07373','color':'#000'}); }, 1000);
		
		
	});
	
	
</script>
<script src="scripts/jQuerytypeahead/typeahead.js"></script>
<link href="scripts/jQuerytypeahead/examples.css" rel="stylesheet" type="text/css" />
<?php
	if(isset($_GET['parent'])){
?>
<script type="text/javascript">
	jQuery(function(){
		jQuery('.parent-<?php echo $_GET['parent']; ?>').css('display','block');
		jQuery('.hover-<?php echo $_GET['parent']; ?>').html('');
		jQuery('.page-header-left').css('background-image','url(images/<?php echo $_GET['parent'];?>.png)');
	});
</script>
<?php		
	}
	else{
?>
<script type="text/javascript">
	jQuery(function(){
		jQuery('.parent-<?php echo $_GET['page']; ?>').css('display','block');
		jQuery('.hover-<?php echo $_GET['page']; ?>').html('');
		jQuery('.page-header-left').css('background-image','url(images/<?php echo $_GET['page'];?>.png)');
	});
</script>
<?php			
	}
?>
<!-- for data table -->
<script type="text/javascript" language="javascript" src="scripts/data-table/media/js/jquery.dataTables.min.js"></script>
<!-- Disable 
<SCRIPT LANGUAGE="JavaScript">  
 
function disableselect(e){  
return false  
}  
function reEnable(){  
return true  
}  
//if IE4+  
document.onselectstart=new Function ("return false")  
document.oncontextmenu=new Function ("return false")  
//if NS6  
if (window.sidebar){  
document.onmousedown=disableselect  
document.onclick=reEnable  
}  
</script>-->
<!-- tinyMCE -->
<script src="scripts/tinymce.min.js"></script>
<script>
tinymce.init({
	toolbar: "undo redo | sizeselect | bold italic | fontselect |  fontsizeselect | alignleft aligncenter alignright alignjustify",
	theme_advanced_font_sizes: "10px,12px,13px,14px,16px,18px,20px",
	font_size_style_values: "12px,13px,14px,16px,18px,20px",
	selector:'textarea',
	oninit : 'setPlainText'			
});
</script>
</script>
<style type="text/css" title="currentStyle">
	@import "scripts/data-table/media/css/demo_page.css";
	@import "scripts/data-table/media/css/demo_table.css";
</style>
</head>
<body aria-valuetext="0" aria-label="">

<audio controls style="display:none;">
  <source src="sounds/notification.mp3" type="audio/mpeg">
  Your browser does not support the audio tag.
</audio>

<div class="wrapper">
	<?php if($_GET['page']=='dashboardold'){ ?>
	<!-- moblile redirect script -->

	<div class="header-wrapper">
    	<div class="header">
        	<div class="thelogo"><a href="?page=controlpanel"></a></div>
            <!-- end .thelogo -->
            <div class="header-nav">
            	<!--<ul>
                    <li class="nav-notifications"><a href="?page=notifications"></a></li>
                    <li class="nav-users"><a href="?page=users"></a></li>
                    <li class="nav-statistics"><a href="?page=statistics"></a></li>
                </ul>-->
               <a href="http://www.limoneristorante.se/" target="_blank"> <img src="images/logo-limone.png" style="margin:7px 0 0;"> </a>
            </div>
            <!-- end .header-nav -->
            <div class="header-account">
            	<div class="account-image"><img src="images/account.png"></div>
                <div class="account-details">
                	<ul>
                    	<li><div style="padding: 0px 4px 4px 4px; font-weight: bold; color: #e67e22;"><?php echo $_SESSION['login']['name']; ?></div></li>
                        <li><a href="?page=profile&subpage=edit-profile&parent=staff">Ändra min profil</a></li>
                        <!--<li><a href="?page=account-settings">Account Settings</a></li>-->
                        <li><a href="#" onClick="logout()">Logga ut</a></li>
                    </ul>
                </div>
            </div>
            <!-- end .header-account -->
        </div>
        <!-- end .header -->
    </div>
    <!-- end .header-wrapper -->
    <?php }else if($_GET['page']=='dashboard'){?>
    <script>
	var widthhh = 0;
	jQuery(function(){
			jQuery(window).load(function(){
			   widthhh = jQuery(window).width();
			   if(widthhh < 767){
				  window.location.href="crisp.php?page=orders";   
				}
			});
			
			$(window).resize(function(){
				widthhh = window.innerWidth;
				 if(widthhh < 767){
				  window.location.href="crisp.php?page=orders";   
				}
			}); 
	}); 
</script>	
    	<div class="header-wrapper">
    	<div class="header">
        	<div class="thelogo"><a href="?page=controlpanel"></a></div>
            <!-- end .thelogo -->
            <div class="header-nav">
            	<!--<ul>
                    <li class="nav-notifications"><a href="?page=notifications"></a></li>
                    <li class="nav-users"><a href="?page=users"></a></li>
                    <li class="nav-statistics"><a href="?page=statistics"></a></li>
                </ul>-->
               <a href="http://www.limoneristorante.se/" target="_blank"> <img src="images/grekiska_logo.png" style="height:45px;margin:5px 0 0;"> </a>
            </div>
            <!-- end .header-nav -->
            <div class="header-account">
            	<div class="account-image"><img src="images/newdashboard/account1.png"></div>
                <div class="account-details">
                	<ul>
                    	<li><div style="padding: 0px 4px 4px 4px; font-weight: bold; color: #e67e22;"><?php echo $_SESSION['login']['name']; ?></div></li>
                        <li><a href="?page=profile&subpage=edit-profile&parent=staff"><!--Ändra min profil--></a></li>
                        <!--<li><a href="?page=account-settings">Account Settings</a></li>-->
                        <li><a href="#" onClick="logout()"><!--Logga ut--></a></li>
                    </ul>
                </div>
            </div>
            <!-- end .header-account -->
        </div>
        <!-- end .header -->
    </div>
    <!-- end .header-wrapper -->
    <?php } ?>
    
    <?php
	
		function showNav($val,$child){
			if($val==1){
				return 'included';
			}
			else{
				if(strlen($child)>0){
					
					$child=explode('*',$child);
					
					$check=0;
					for($i=0;$i<count($child);$i++){
						$q=mysql_query("select ".$child[$i]." from type where id=".$_SESSION['login']['type']) or die(mysql_error());
						$r=mysql_fetch_array($q);
						
						if($r[0]==1){
							$check=1;
							break;
						}	
						
					 }
					 
					 if($check==1){
						 return 'notclickable';
					 } 
					 else{
						return 'notincluded';	
					 }
					
				}
				else{
					return 'notincluded';
				}
			}
		}
	
		
	
		$query=mysql_query("select * from type where id=".$_SESSION['login']['type']) or die(mysql_error());
		$row=mysql_fetch_assoc($query);
			
		$nav_menu=$row['menu'];
		$nav_category=$row['category'];
		$nav_subcategory=$row['sub_category'];
		$nav_reservation=$row['reservation'];
		$nav_overview=$row['overview'];
		$nav_order_status=$row['order_status'];
		$nav_customers=$row['customers'];
		$nav_the_staff=$row['the_staff'];
		$nav_users=$row['users'];
		$nav_announcement=$row['announcement'];
		$nav_shift_request=$row['shift_request'];
		$nav_scheduler=$row['scheduler'];
		$nav_scheduler_chart=$row['scheduler_chart'];
		$nav_reports=$row['reports'];
		$nav_logs=$row['logs'];
		$nav_booking_report=$row['booking_report'];
		$nav_settings=$row['settings'];
		$nav_tables=$row['tables'];		
		$nav_table_masterlist=$row['table_masterlist'];			
		$nav_floorplan=$row['floorplan'];		
		$nav_account=$row['account'];
		$nav_advanced_settings=$row['advanced_settings'];	
		$nav_notifications=$row['notifications'];
		$nav_catering=$row['catering'];
		$nav_catering_category=$row['catering_category'];
		$nav_catering_subcategory=$row['catering_subcategory'];
		$nav_catering_settings=$row['catering_settings'];
		$nav_catering_menu=$row['catering_menu'];
				
		
	?>
    
    <div class="nav-wrapper admin-menu">
    	<button class="menu-btn">Menu</button>
    	<div class="nav">
        	<ul>
            	
            	<li class="nav-dashboard navs"><a href="?page=dashboard" title="Hem" class="dashboard-li">Home</a></li>
            	<?php /* <li class="nav-controlpanel navs"><a href="?page=controlpanel" title="Boken" class="controlpanel-li">Boken</a></li> */ ?>                
                <li class="nav-reservation navs"><a href="?page=orders" title="Beställningar" class="orders-li"><span></span>Beställningar</a></li>
                <?php /* <li class="nav-bordsbokning navs"><a href="javascript:void(0)" class="bordsbokning-li">Bordsbokning</a> 
                	<ul>
                    	<li class="nav-logs navs"><a href="?page=other-settings&parent=bordsbokning">Inställningar</a></li>
                    	<li class="nav-logs navs <?php echo showNav($nav_booking_report,''); ?>"><a href="?page=booking_report&parent=reports">Historik</a></li>
                        <li class="nav-logs navs "><a href="?page=table-statistics&parent=reports">Statistik</a></li>
                        <li class="nav-logs navs <?php echo showNav($nav_logs,''); ?>"><a href="?page=logs&parent=reports">Logg</a></li>
                    </ul>
                </li> */ ?> 
                <li class="nav-takeaway navs"><a href="javascript:void(0)" class="takeaway-li">Takeout</a>
                	<ul>
                    	<li class="nav-takeawaymenu navs" data-rel="nav-takeaway"><a href="?page=takeaway-menu&parent=takeaway">Menyinställningar</a></li>
                        <li class="nav-takeawaysettings navs" data-rel="nav-takeaway"><a href="?page=takeaway-settings&parent=takeaway">Inställningar</a></li>
                        <li class="nav-logs navs "><a href="?page=takeaway-statistics&parent=reports">Statistik</a></li>
                         <li class="nav-logs navs <?php echo showNav($nav_logs,''); ?>"><a href="?page=logs&parent=reports">Logg</a></li>
                    </ul>
                </li>
                <?php /* <li class="nav-catering navs"><a href="javascript:void(0)" class="catering-li <?php echo showNav($nav_catering,''); ?>">Catering</a> 
                	<ul>
                        <li class="nav-catering_menu navs <?php echo showNav($nav_catering_menu,''); ?>" data-rel="nav-catering"><a href="?page=catering-menu&parent=catering">Menyinställningar</a></li>
                        <li class="nav-catering_settings navs <?php echo showNav($nav_catering_settings,''); ?>" data-rel="nav-catering"><a href="?page=catering-settings&parent=catering">Catering Settings</a></li>
                         <li class="nav-logs navs "><a href="?page=catering-statistics&parent=reports">Statistik</a></li>
                         <li class="nav-logs navs <?php echo showNav($nav_logs,''); ?>"><a href="?page=logs&parent=reports">Logg</a></li>
                    </ul>
                </li> */ ?> 
                <li class="nav-lunchmeny navs"><a href="javascript:void(0)" class="lunchmeny-li">Lunch</a>
                
                	<ul>
                        <li class="nav-lunch_menu navs <?php echo showNav($nav_catering_menu,''); ?>" data-rel="nav-lunchmeny"><a href="?page=lunch-menu&parent=lunchmeny">Menyinställningar</a></li>
                        <li class="nav-takeawaysettings navs" data-rel="nav-lunchmeny"><a href="?page=lunchmeny-settings&parent=lunchmeny">Inställningar</a></li>
                         <li class="nav-logs navs <?php echo showNav($nav_logs,''); ?>"><a href="?page=logs&parent=reports">Logg</a></li>
                  </ul>
                
              </li>
                <?php /* <li class="nav-alacarte navs"><a href="javascript:void(0)" class="alacarte-li">À la carte meny</a>  
                
                	<ul>
                    	<li class="nav-menu navs <?php echo showNav($nav_menu,'category*sub_category'); ?>" data-rel="nav-alacarte"><a href="?page=menu">Menyinställningar</a>
                         <li class="nav-logs navs <?php echo showNav($nav_logs,''); ?>"><a href="?page=logs&parent=reports">Logg</a></li>
                    </ul>
                
                </li> */ ?> 
                
                <li class="nav-personal navs"><a href="javascript:void(0)" class="personal-li">Användare</a>
                
                	<ul>
                    	<li class="nav-users navs <?php echo showNav($nav_users,''); ?>"><a href="?page=users&parent=staff">Personalkonton</a></li>
                        <li class="nav-announcement navs <?php echo showNav($nav_announcement,''); ?>"><a href="?page=announcement&parent=staff">Utskick</a></li>
                        <li class="nav-account navs <?php echo showNav($nav_account,''); ?>"><a href="?page=account&parent=settings">Rättigheter</a></li>
                         <li class="nav-logs navs <?php echo showNav($nav_logs,''); ?>"><a href="?page=logs&parent=reports">Logg</a></li>
                    </ul>
                
                </li>
                
                <?php /*
                <li class="nav-alacarte navs"><a href="javascript:void(0)" class="alacarte-li">Schemaläggning</a>
                
                	<ul>
                    	<li class="nav-scheduler_chart navs <?php echo showNav($nav_scheduler_chart,''); ?>"><a href="?page=scheduler-chart&parent=staff">Schema</a></li>
                        <li class="nav-scheduler navs <?php echo showNav($nav_scheduler,''); ?>"><a href="?page=scheduler&parent=staff">Schemaläggaren</a></li>
                        <li class="nav-shift_request navs <?php echo showNav($nav_shift_request,''); ?>"><a href="?page=shift&parent=staff">Skiftbyte</a></li>
                         <li class="nav-logs navs <?php echo showNav($nav_logs,''); ?>"><a href="?page=logs&parent=reports">Logg</a></li>
                    </ul>
                
                </li>
                
                <li class="nav-alacarte navs"><a href="javascript:void(0)" class="alacarte-li">Marknadsföring</a>
                
                	<ul>
                    	<li class="nav-customers navs <?php echo showNav($nav_customers,''); ?>"><a href="?page=customers">Kundregister</a></li>
                        <li class="nav-notifications navs <?php echo showNav($nav_notifications,''); ?>"><a href="?page=notifications">Push-notiser</a></li>
                         <li class="nav-logs navs <?php echo showNav($nav_logs,''); ?>"><a href="?page=logs&parent=reports">Logg</a></li>
                    </ul>
                
                </li> */ ?>
                
                <li class="nav-alacarte navs"><a href="javascript:void(0)" class="alacarte-li">Inställningar</a>
                
                	<ul>
                    	<li class="nav-advanced_settings navs <?php echo showNav($nav_advanced_settings,''); ?>"><a href="?page=advanced-settings&parent=settings">Avancerade inställningar</a></li>
                         <li class="nav-logs navs <?php echo showNav($nav_logs,''); ?>"><a href="?page=logs&parent=reports">Logg</a></li>
                    </ul>
                
                </li>
                
                <li class="nav-sidor navs"><a href="javascript:void(0)" class="sidor-li">Sidor</a>
                
                	<ul>
                    	<li class="nav-hem navs <?php echo showNav($nav_advanced_settings,''); ?>"><a href="?page=sidor&code=hem">Hem</a></li>
                        <li class="nav-delikatesskorgar navs <?php echo showNav($nav_advanced_settings,''); ?>"><a href="?page=sidor&code=avhamtning">Avhämtning</a></li>
                        <li class="nav-kontakt navs <?php echo showNav($nav_advanced_settings,''); ?>"><a href="?page=sidor&code=kontakt">Kontakt</a></li>
                    </ul>
                
                </li>

             
             
             	<!--<a href="?page=reports" class="reports-li main">Rapporter</a>-->
                <!--<li class="nav-reports navs <?php //echo showNav($nav_reports,'logs*booking_report'); ?>"><a href="javascript:void(0)" class="reports-li main">Rapporter</a>
                
                	<ul>
                        <li class="nav-logs navs <?php //echo showNav($nav_logs,''); ?>"><a href="?page=logs&parent=reports">Logg</a></li>
                        <li class="nav-logs navs <?php //echo showNav($nav_booking_report,''); ?>"><a href="?page=booking_report&parent=reports">Booking Report</a></li>
                    </ul>
                </li>-->
              
                <?php if($_GET['page']!='dashboard' and $_GET['page']!='dashboardold'){
                	echo '<li class="navs logout" style="float:right;"><a href="#" onClick="logout()">Logga ut</a></li>';
                 }else{
                 	echo '<li class="navs logout" style="float:right; display: none" ><a href="#" onClick="logout()">Logga ut</a></li>';
                 }
				  ?>
            </ul>
        </div>
    </div>
    
    <div class="content-wrapper">
    	<div class="content">
        	
            <div class="content-container">
            	<div class="content-area content-<?php echo $_GET['page'];?>">
                
                <?php if($_GET['page']!='dashboard'){?>
                <div class="breadcrumb">Du är här : <?php if(isset($_GET['page'])) echo '<a href="?page='.$_GET['page'].'">'.$_GET['page'].'</a>'; if(isset($_GET['subpage'])) echo ' &#8250; <a href="?page='.$_GET['page'].'&subpage='.$_GET['subpage'].'">'.$_GET['subpage'].'</a>';  ?></div> 
					
						<?php } include 'pages/content.php'; ?>
					
                </div>
                <!-- end .content-area -->
                <div class="footer">
                	<p>&copy; Copyright <?php echo date('Y'); ?> Icington Sverige AB. All Rights Reserved.</p>
                </div>
            </div>
            <!-- end .content-container -->
        </div>
        <!-- end .content -->
    </div>
    <!-- end .content-wrapper -->
</div>
<!-- end .wrapper -->
<script type="text/javascript">
	jQuery(function(){
		jQuery('.notclickable a.main, .notincluded a').removeAttr('href');
		jQuery('.notclickable a.main').click(function(){
			alert("You don't have permission.");
		});
	});
		$(document).ready(function(){
			
		
			setInterval(function() {
					var date_now = jQuery('#get_date').val();
				
			  					jQuery.ajax({
												url: "tablechart/calendar-data.php",
												type: 'GET',
												 data: 'date='+date_now,
												success: function(value){
													jQuery('.fadediv, .loaddiv').fadeOut();
													jQuery('#calendar-wrapper').html(value);
													// alert(value);
												}
											});
			},60000);
			});
</script>
</body>
</html>
                            
                            
