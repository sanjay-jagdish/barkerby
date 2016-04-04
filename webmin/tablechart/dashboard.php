	<?php

	$type_id = $_SESSION['login']['type'];

function query_status($number){

	$query = mysql_query("SELECT count(id) AS bookings FROM reservation WHERE date = '".date("m/d/Y")."' AND status = '".$number."' AND deleted = '0' ");

	while($row = mysql_fetch_assoc($query)){
		$seated = $row['bookings'];
	}

	return $seated;
}


	$query=mysql_query("select * from type where id=".$type_id) or die(mysql_error());
		$row=mysql_fetch_assoc($query);

		$nav_item['menu'] = $row['menu'];
		$nav_item['category']=$row['category'];
		$nav_item['sub_category']=$row['sub_category'];
		$nav_item['reservation']=$row['reservation'];
		$nav_item['overview']=$row['overview'];
		$nav_item['order_status']=$row['order_status'];
		$nav_item['customers']=$row['customers'];
		$nav_item['the_staff']=$row['the_staff'];
		$nav_item['users']=$row['users'];
		$nav_item['announcement']=$row['announcement'];
		$nav_item['shift_request']=$row['shift_request'];
		$nav_item['scheduler']=$row['scheduler'];
		$nav_item['scheduler_chart']=$row['scheduler_chart'];
		$nav_item['reports']=$row['reports'];
		$nav_item['logs']=$row['logs'];
		$nav_item['booking_report']=$row['booking_report'];
		$nav_item['booking_report']=$row['booking_report'];
		$nav_item['settings']=$row['settings'];
		$nav_item['tables']=$row['tables'];		
		$nav_item['table_masterlist']=$row['table_masterlist'];			
		$nav_item['floorplan']=$row['floorplan'];		
		$nav_item['account']=$row['account'];
		$nav_item['advanced_settings']=$row['advanced_settings'];	
		$nav_item['notifications']=$row['notifications'];

		


		?>

<style>

.wrapper {
	overflow: hidden;
}

* {
box-sizing: border-box;
}

body{
	font-family: 'Roboto';
}

.content {
	width: 100%;
	padding: 0 15px;
	overflow: hidden;
}

.thelogo {
	background: url(./images/newdashboard/miseenplace-new.png) no-repeat;
	float: left;
	margin: 10px 0 5px;
	width:237px !important;
	height: 40px !important;
}

.thelogo a{
	height: 40px !important;
}

.header-wrapper {
	width: 100%;
	float: left;
	background: #414a4d;
}

.account-details {
	min-width: 119px;
	margin:0 !important;
}

.account-details a {
	width: 13px;
	height: 13px;
	background-color:transparent;
}

.account-details ul li:nth-child(1) div {
	font-size:16px;
	color: #FFF !important;
}

.account-details ul li:nth-child(2) a,
.account-details ul li:nth-child(3) a {
	margin-left: 5px;
	width: 20px;
	margin-top: 3px;
}

.account-details ul li:nth-child(2) a {
	background: url(./images/newdashboard/admin-profile.png) no-repeat;
}

.account-details ul li:nth-child(3) a {
	background: url(./images/newdashboard/logout.png) no-repeat;
}

.header-account {
	padding: 6px 10px 5px 20px !important;
	border-left: 1px solid gray;
}

.header-nav {
	width: 55%;
}

.header-wrapper,
.header {
	min-height: 60px;
}

.content-wrapper {
	background: #efefef;
}

.nav-wrapper {
	background: #2d3235;
	min-height: 68px;
}

.dashboard-li{
	background: url(./images/newdashboard/hem.png) no-repeat center;
}

.controlpanel-li{
	background: url(./images/newdashboard/control-panel.png) no-repeat center;
}

.orders-li{
	background: url(./images/newdashboard/orders.png) no-repeat center;
}

.dashboard-li:hover,
.controlpanel-li:hover,
.orders-li:hover{
	background-color: transparent !important;
	border-bottom:none !important;	
}

.header-wrapper{
	border-bottom:none;
}
.header-wrapper:after {
	content: "";
	width: 100%;
	height: 0px;
	margin-top: 2px;
	opacity: 0.6;
	display: block;
	position: absolute;
	z-index: 0;
	-webkit-box-shadow: 0px 0px 2px 1px #181b1c;
	-moz-box-shadow: 0px 0px 2px 1px #181b1c;
	box-shadow: 0px 0px 2px 1px #181b1c;
}

.content-container{
	margin-top: 50px;
	background-color:transparent;
}

.content-area{
	background-color:transparent;	
}

.dashboard-page {
	padding: 0;
}

#left-content{
	background-color: #fff;
	padding: 20px;
	width: 100%;
}


#news{
	overflow-x: auto;
}

.post-description {
	float: left;
	width: 50%;
	padding: 10px 0 10px 10px;
}

.post-featured-image-wrapper {
	float: right;
	width: 50%;
	max-height: 154px;
	display: flex;
}


.post-featured-image-wrapper p:nth-child(1),
.offers-featured-image-wrapper p:nth-child(1) {
	display: flex;
	margin: 0 auto;
}

.post-featured-image-wrapper p,
.offers-featured-image-wrapper p{
	display: none;
}


.post-description b a {
	text-decoration: none;
	color: #555;
}

.post-item-container,
.post-content{
	background-color: #f4f4f4;
	overflow: hidden;
	margin-bottom: 30px;
}

.post-header,
.form-header{
    padding-left: 40px;
    height: 32px;
    font-size: 30px;
    font-weight: bold;
    margin: 15px 0;
}

#post-news .post-header {
	background: url(./images/newdashboard/news.png) no-repeat left;
}

#post-offers .post-header {
	background: url(./images/newdashboard/offers.png) no-repeat left;
}

#form .form-header{
	background: url(./images/newdashboard/notify-headquarters.png) no-repeat left;
}

#form-container table tr td {
	padding: 10px 0;
}

#form-container table tr td:first-child {
	text-align: right;
	/*padding-right: 20px;*/
	width: 10%;
	font-size: 15px;
}

.post-description b{
	margin-bottom: 3px;
	display: block;
}

.offers-info {
	display: inline-block;
	width: 87%;
	padding: 10px 10px 10px 0;
}

.offers-featured-image-wrapper {
	display: inline-block;
	padding: 20px;
	width: 12%;
}

.btn-purchase {
	float: right;
	color: #fff;
	background: url(./images/newdashboard/shopping-cart.png) #424242 no-repeat left;
	text-decoration: none;
	padding: 10px 10px 10px 45px;
	background-position: 15px;
	background-position-x: 15px;
	margin-left: 9px;
	font-size: 14px;
}

.offers-info p {
	margin-bottom: 10px;
}

.offers-info h4{
	margin: 0;
}

.offers-info h4 a {
	margin: 0 0 10px 0;
	color: #ea4924;
	font-size: 14px;
	text-decoration: none;
	display: block;
	padding: 0 0 5px 0;
	border-bottom: 1px solid #cecece;
}

#post-offers {
	width: 100%;
}

#post-news,
#post-offers{
	margin-bottom: 50px;
}

#name,
#email,
#subject,
#message{
	outline: none;
	padding: 10px 10px;
	width: 50%;
	color: #555;
	font-size: 15px;
	border-radius: 3px;
	border: 1px solid #efefef;
}

#message{
	height: 250px;
	width: 100%;
	display: block !important;
}

#submit,
#cancel{
	outline: none;
	border: 1px solid;
	font-size: 15px;
	border-radius: 3px;
	padding: 10px 20px;
}

#submit{
	background-color: #1f79b2;
	color: #fff;
	border-color:#1a608f;
}

#cancel{
	background-color: #fff;
	color: #000;
	border-color:#cccccc;
}

.footer {
	text-align: center;
	border-top: none;
}

#top{
	background-color: #3cb1aa;
	padding: 10px;
	color: #FFF;
}

#right-content {
	width: 32%;
}

#offers {
	overflow-x: auto;
}

.offers-featured-image-wrapper a p {
	width: 102px;
	height: 102px;
}

@media only screen and (max-width: 1040px) {
	.nav ul li a {
		padding: 25px 4px;
		font-size: 13px !important;
	}
	
	.header-nav {
		width: 50% !important;
	}
	
	.offers-featured-image-wrapper a p,
	.def-img{
		width: 95px !important;
		height: 95px !important;
	}
}

.divider {
    text-align: center;
    background: url(images/divider-bg.png) repeat-x center center;
}
.message_from{
    font-size: 15px;
}

</style>
	
<?php if(isset($_GET['stripe_id'])){
mysql_query("delete from settings where var_name='stripe_id'");
mysql_query("insert into settings(var_name,var_value) values('stripe_id','".$_GET['stripe_id']."')");

	} ?>
<div class="page dashboard-page">


<div id="left-content">
<?php $check_stripe_id = mysql_query("select * from settings where var_name='stripe_id'");

if(mysql_num_rows($check_stripe_id)>0){ ?>
<p>Your Stripe account is connected with us!</p>
<? }else{ ?>
<p>NOTE: Connect your stripe account below to receive payments!</p>
<?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
<p><a href="https://connect.stripe.com/oauth/authorize?response_type=code&client_id=ca_7qTxq683DUR3zxuSGWKE2r2LDj3VEt39&scope=read_write&state=<?=$actual_link?>"><img src="http://embien.in/e2f/webmin/images/stripeconnect.png" /></a></p>
<?php } ?>
<!-- NEWS--->
	<div id="post-news">
    	<h2 class="post-header">Nyheter</h2>
        <div id="news">
			<?php include 'news-rss-feed.php';?>
        </div>
		<div class="divider"><img src="images/logo-divider.png"></div>
    </div>
<!-- END NEWS--->

<!-- OFFERS --->
	<div id="post-offers">
    	<h2 class="post-header">Aktuella Erbjudanden</h2>
        <div id="offers">
			<?php include 'offers-rss-feed.php';?>
        </div>
		<div class="divider"><img src="images/logo-divider.png"></div>
    </div>
<!-- END OFFERS -->

<!-- IDEAS--->
	<div id="form">
    	<h2 class="form-header">Meddela huvudkontoret</h2>
            <div id="form-container">
            	<table style="margin-left: 40px; width:90%;">
                  <tr>
                		<td style="vertical-align: top; padding-top: 32px;">Ämne:</td>
                        <td style="vertical-align: top; padding-top: 25px;"><input type="text" id="subject" /></td>
                    </tr>
                    
                    <tr>
                		<td style="vertical-align: top; padding-top: 25px;">Skriv här:</td>
                        <td><textarea id="message" aria-hidden="false"></textarea></td>
                    </tr>
                    
                    <tr>
                    	<td colspan="2">
                        	<div class="displaymsg" style="display: block;"></div>
                        </td>
                    </tr>
                    
                    <tr>
              			<td colspan="2" style="text-align:right">
                            <input type="button" id="submit" class="form-button" value="Skicka" /> 
                            <!--<input type="button" id="cancel" class="form-button" value="Cancel" />-->
                        </td>
                    </tr>
               </table> 
            </div>   
    </div>
<!-- END IDEAS--->
    
</div><!-- end left-content -->

<style>

#top{
	background-color: #3cb1aa;
	padding: 10px;
	color: #FFF;
}

#bottom div {
	display: inline-block;
	width: 65px;
	padding: 0px;
	text-align: center;
}

#bottom {
	padding:15px;
	text-align: center;
}

#bottom div:nth-child(2) {
	border-left: 1px solid #e6e6e6;
	border-right: 1px solid #e6e6e6;
}
.widget-wrap h4{
	font-weight: 100;
}
.widget-wrap {
	border: none;
	-webkit-box-shadow: none;
	box-shadow: none;
	background-color: #fff;
}

.message-board.widget-wrap {
	text-align: center;
}

.support.widget-wrap ul li:first-child {
	padding: 0 0 20px 0 !important;
}

.widget-wrap ul li {
	padding: 0 !important;
}
.weather.widget-wrap {
	background: none;
}

#news::-webkit-scrollbar
#offers::-webkit-scrollbar {
    width: 0px;
    height: 10px;
    border-bottom: 1px solid #eee; 
    border-top: 1px solid #eee;
}
#news::-webkit-scrollbar-thumb,
#offers::-webkit-scrollbar-thumb {
    border-radius: 8px;
    background-color: #C3C3C3;
    border: 2px solid #eee;
}

#news::-webkit-scrollbar-track,
#offers::-webkit-scrollbar-track{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.2); 
} 

#news::-webkit-scrollbar,
#offers::-webkit-scrollbar{
    width: 10px;
    height: 10px;
    border-bottom: 1px solid #eee; 
    border-top: 1px solid #eee;
}
#news::-webkit-scrollbar-thumb,
#offers::-webkit-scrollbar-thumb {
    border-radius: 3px;
    background-color: #C3C3C3;
    border: 2px solid #eee;
}

#news::-webkit-scrollbar-track,
#offers::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.2); 
}

.aw-more-block{
	display:none;
}

.mce-panel {
	display: none;
}

</style>

</div> <!-- end menu-page	 -->

	<script type="text/javascript">			
	
		<?php
		foreach ($nav_item as $item => $value) {

							# code...
					if( $value == 0){

					?>
						if ( jQuery( ".<?php echo $item;?> h2" ).hasClass( "parent" ) ){

								jQuery(".<?php echo $item;?> h2").addClass('blur');
								jQuery(".<?php echo $item;?>").removeAttr( "href" );


						}else{

					<?php

						echo 'jQuery( ".'.$item.'" ).remove();';
						

					?>	
						} //end inner if
					<?php

					} //end if
				}
		?>
		
		jQuery(function(){
			jQuery('.form-button').click(function(){
				var domainname = '<?php echo $_SERVER['SERVER_NAME'];?>';
				var user = '<?php echo $_SESSION['login']['name'];?>';
				var subject = jQuery('#subject').val();
				var msg = jQuery('#message').val();
				
				msg = msg.replace(/\n/g,"<br />");
				
				$('#form .displaymsg').fadeOut();
				
				if( msg == ''){
					$('#form .displaymsg').fadeIn('slow').addClass('errormsg').html('Vänligen fyll i alla obligatoriska fält.');
				}else{
				
					//console.log('Domain = '+domainname+' User = '+user+' msg = '+msg);
					$('#form .displaymsg').fadeOut();
					jQuery.ajax({
						url: "./actions/suggestion-box.php",
							type: 'POST',
							data: 'domainname='+encodeURIComponent(domainname)+'&user='+encodeURIComponent(user)+'&subject='+encodeURIComponent(subject)+'&msg='+encodeURIComponent(msg),
							success: function(value){
								$('#form .displaymsg').fadeIn().removeClass('errormsg').addClass('successmsg').html('Tack! Vi för av oss till dig om vi behöver mer information.');
								setTimeout(function(){ window.location.reload(true); }, 3000);
								
							}
						
						
					});
				}
				
			});
		});
		
	</script>