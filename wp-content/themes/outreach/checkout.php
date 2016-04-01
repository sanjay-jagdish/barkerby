<?php
/*
Template Name: checkout process 
*/
$type = $_GET['type'];
if($type == 'takeaway'){
$chkqnkid = $_COOKIE['takeaway_id']; 
$tableName = "takeaway_settings";
}else if($type == 'lunch'){
$chkqnkid = $_COOKIE['lunchmeny_id']; 
$tableName = "lunchmeny_settings";
}else{	
wp_redirect(home_url() ); 
exit;
}
get_header('checkout');
include 'ajax/steps-asap.php';
$q=mysql_query("select * from reservation_detail where reservation_id=(select id from reservation where deleted=0 and uniqueid='".$chkqnkid."' order by id desc limit 1)") or die(mysql_error());
if(mysql_num_rows($q) < 1){
wp_redirect(home_url() ); 
exit;
}
$asapStatus = GetAsapStatus($tableName);
 if($asapStatus){
 	$status = "radio1";
 	$chk = "checked";
 }else{
   $status = "";
   $chk = "";
 }
// Get current user
$current_user = wp_get_current_user();
if( $current_user ) {
	$query = "select * from `account` where id='".$current_user->ID."'";
	$userdata = $wpdb->get_row($query);
}
?>

<div class="checkout-page-outer">
<div class="container clearfix">
	<div class="page-title">
		<h3>Kassa</h3>
	</div>
	<div class="delvry-time-cont">
		<div class="row">
			<div class="col-sm-5">
				<h4>1. VÄLJ TIDPUNKT OCH LEVERANSSÄTT</h4>
				<div class="row">
					<div class="col-xs-6">
						<div class="delvry-apap"> 
							<input type="hidden" name="chkuniqeid" value="<?php echo $chkqnkid; ?>" id="chkuniqeid" />
                            <input type="hidden" name="pluscheck" value="" id="pluscheck" /> 
							<input type="radio" name="radiog_lite" id="radio1" class="css-checkbox" <?php echo $asapStatus?'checked':'disabled'; ?>/>	
							<label for="radio1" class="css-label radGroup1">Snarast</label>
							<input type="hidden" id="asap" name="asap" value="<?php echo $asapStatus?'1':'0'; ?>">

						</div>
					</div>
					<div class="col-xs-6">
						<div class="delvry-time">
							<input type="radio" name="radiog_lite" id="radio2" class="css-checkbox" <?php echo $asapStatus?'':'checked'; ?>/>
							<label for="radio2" class="css-label radGroup1">Annan tid</label>
							<input type="hidden" id="order_time" name="order_time">
						</div>
					</div>
					<div class="col-xs-6">
						<div class="get-food">
							<input type="radio" name="radiog_lite2" id="radio3" class="css-checkbox" checked disabled/>
							<label for="radio3" class="css-label radGroup1">Jag hämtar maten</label>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-xs-12" id='input_datetime_div' style="display:<?php echo $asapStatus?'none':'block'; ?>">
				<div id="input_datetime"></div>
				<div class='clearfix'></div>
				<div class="row timepicker-outer">
					<div class='col-xs-9'>
						<div class="timepicker-cont">
							<h6>Avhämtning erbjuds mellan:</h6>
							<h6 id='range'></h6>
							<div class="row">
								<div class='col-xs-6'>
									Tid: 
								</div>
								<div class='col-xs-6' id='orderTime'>
									00:00
								</div>
							</div>
							<div class="row">
								<div class='col-xs-6'>
									Timme: 
								</div>
								<div class='col-xs-6'>
									<button type="button" id='subHour' class="qnty-btn pull-left times">-</button>
									<button type="button" id='addHour' class="qnty-btn pull-left times">+</button>
								</div>
							</div>
							<div class="row">
								<div class='col-xs-6'>
									Minut: 
								</div>
								<div class='col-xs-6'>
									<button type="button" id='subMin' class="qnty-btn pull-left times">-</button>
									<button type="button" id='addMin' class="qnty-btn pull-left times">+</button>
								</div>
							</div>
						</div>
					</div>
					<div class='clearfix'></div>
					<div class='col-xs-9'>
						
					</div>
				</div>
			</div>
			<div class='clearfix'></div>
		</div>
	</div>
	<div class="oder-cont">
		<div class="row">
			<div class="col-sm-6">
				<h4>2. DIN BESTÄLLNING</h4>
				<div class="prod-cont">
					<?php include 'ajax/cart.php'; ?>
				</div>
			</div>
			<div class="col-sm-6 order-option">
				
			</div>
		</div>
	</div>
	<div class="your-info">
		<div class="row">
			<div class="col-sm-4">
				<h4>3. DINA UPPGIFTER</h4>
				<?php if( !$current_user->ID ){ ?>
					<p id='notloggedin'><span class="create-account auth-login">Logga in</span> eller <span class="create-account auth-signup">Skapa ett konto</span></p>
				<?php } ?>
				
				<div id='userdata' style="display:<?php echo $current_user->ID?'block':'none'?>">
					<div class="form-group">
						<label for="telefon">Telefon</label>
						<input type="text" name="phone" class="form-control required" id="phone" required value="<?php echo $userdata->mobile_number; ?>">
					</div>
					<div class="form-group">
						<label for="fullname">Name</label>
						<input type="text" name="name" class="form-control required" id="fullname" required value="<?php echo $userdata->fname; ?>">
					</div>
				</div>
				
				<p id='loggedin' style="display:<?php echo $current_user->ID? 'block': 'none';?>">
					Welcome <span id='current_email'><?php echo $current_user->user_email; ?> </span> <a href="<?php echo wp_logout_url( site_url('checkout/?type='.$type) ); ?>">Logout?</a>
				</p>

				<!-- <form method="Post" id="login-form">
					<div class="icon-field">
						<input type="text" class="form-control" placeholder="E-Post" name="email" id="email" >
						<i class="flaticon-back"></i>
					</div>
					<div class="icon-field">
						<input type="password" class="form-control" placeholder="Losenord" name="pass" id="pass" >
						<i class="flaticon-web"></i>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="chckbox-cont">
								<input type="checkbox" name="checkboxG52" id="checkboxG52" class="css-checkbox" >
								<label for="checkboxG52" class="css-label">Kom ihag mig</label>
							</div>
						</div>
						<div class="col-md-6 text-right">
							<p><span class="show-glomt">Glomt losenord</span></p>
						</div>
					</div>
					<div class="chckbox-cont">
						<input type="checkbox" name="checkboxG55" id="checkboxG55" class="css-checkbox" required>
						<label for="checkboxG55" class="css-label">Jag har last och godkanner</label>
						<a href="#policy" class="popup policy">anvandarvillkoren</a>
					</div>
					<div class="btn-cont">
						<button type="button" class="btn btn-lg btn-red btn-full" id="login-button">LOGGA IN</button>
					</div>
				</form> -->
			</div>

			<div id="policy" style="display:none;">
				<div class="popup-data">
					<h3>Title here</h3>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
					<h4>Lorem ipsum dolor</h4>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
					<h4>Lorem ipsum dolor</h4>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
					<h4>Lorem ipsum dolor</h4>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
					<h4>Lorem ipsum dolor</h4>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
					<h4>Lorem ipsum dolor</h4>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore $asapStatusmagna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
				</div>
			</div>

			<div class="col-md-8">
				<div class="signup-form">
					<div class="row">
						<div class="col-md-6">
							<div class="icon-field">
								<input type="text" class="form-control" placeholder="Fornamn">
								<i class="flaticon-black"></i>
							</div>
						</div>
						<div class="col-md-6">
							<div class="icon-field">
								<input type="text" class="form-control" placeholder="Efternamn">
								<i class="flaticon-black"></i>
							</div>
						</div>
						<div class="col-md-6">
							<div class="icon-field">
								<input type="text" class="form-control" placeholder="E-post">
								<i class="flaticon-back"></i>
							</div>
						</div>
						<div class="col-md-6">
							<div class="icon-field">
								<input type="text" class="form-control" placeholder="Mobilnummer">
								<i class="flaticon-technology"></i>
							</div>
						</div>
						<div class="col-md-6">
							<div class="icon-field">
					  <input type="text" class="form-control" placeholder="Gatuadress">
								<i class="flaticon-internet"></i>
							</div>
						</div>
						<div class="col-md-6">
							<div class="icon-field">
								<input type="text" class="form-control" placeholder="Ort">
								<i class="flaticon-map"></i>
							</div>
						</div>
						<div class="col-md-6">
							<div class="icon-field">
								<input type="text" class="form-control" placeholder="Postnummer">
								<i class="flaticon-tool-1"></i>
							</div>
						</div>
						<div class="col-md-6">
							<div class="icon-field">
								<select class="form-control">
									<option value="Sweden">Sweden</option>
								</select>
								<i class="flaticon-web"></i>
							</div>
						</div>
						<div class="col-md-6">
							<div class="icon-field">
								<input type="text" class="form-control" placeholder="Lösenord">
								<i class="flaticon-back"></i>
							</div>
						</div>
						<div class="col-md-6">
							<div class="icon-field">
								<input type="text" class="form-control" placeholder="Upprepa lösenord">
								<i class="flaticon-back"></i>
							</div>
						</div>
					</div>						
				</div>
				<div class="provide-emailid">
					<div class="row">
						<div class="col-md-6">
							<h4>Uppge din e-postadress:</h4>
							<form>
								<div class="icon-field">
									<input type="text" class="form-control" placeholder="E-Post">
									<i class="flaticon-back"></i>
								</div>
								<div class="btn-cont">
									<button type="button" class="btn btn-lg btn-red btn-full" >LOGGA IN</button>
								</div>
							</form>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="valj-betalsatt">
		<h4>4. VÄLJ BETALSÄTT</h4>
		<div class="row">
			<div class="col-md-7">
				<div class="row">
					<div class="col-md-4">
						<div class="online-pay">
							<input type="radio" name="radiopay_lite" id="radiop1" class="css-checkbox pay-option" checked="checked" value="online" />
							<label for="radiop1" class="css-label radGroup1">Betala online</label>
						</div>
					</div>
					<div class="col-md-4">
						<div class="cash-pay">
							<input type="radio" name="radiopay_lite" id="radiop2" class="css-checkbox pay-option" value="cash" checked/>
							<label for="radiop2" class="css-label radGroup1">Betalas vid avhämtning</label>
						</div>
					</div>
					<div class="col-md-4">
						<div class="invoice-pay">
							<input type="radio" name="radiopay_lite" id="radiop3" class="css-checkbox pay-option invoice-form-show" value="company" />
							<label for="radiop3" class="css-label radGroup1">Faktura (endast företag)</label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-7">
						<button type="button" class="btn btn-lg btn-red btn-full" id="main-checkout">BESTÄLL</button>
						
					</div>
				</div>
			</div>
			<div class="col-md-4 col-md-offset-1">
				<div class="invoice-form">
					<div class="icon-field">
						<input type="text" class="form-control" placeholder="Företag">
						<i class="flaticon-social"></i>
					</div>
					<div class="icon-field">
						<input type="text" class="form-control" placeholder="Org.nr.">
						<i class="flaticon-social-1"></i>
					</div>
					<div class="icon-field">
						<input type="text" class="form-control" placeholder="Adress">
						<i class="flaticon-internet"></i>
					</div>
					<div class="icon-field">
						<input type="text" class="form-control" placeholder="Post.nr/Ort">
						<i class="flaticon-tool-1"></i>
					</div>
					<div class="icon-field">
						<input type="text" class="form-control" placeholder="Ref/Idnummer">
						<i class="flaticon-black"></i>
					</div>
					<input type="hidden" id="email" >
					<input type="hidden" id="pass" >
					<input type="hidden" id="paymenttype" value="cash">
					<div class="icon-field">
						<button type="button" class="btn btn-lg btn-red btn-full" id="main-checkout">BESTÄLL</button>
					</div>	
				</div>
				<div class="stripe-form" style="display:none;">
<span class="payment-errors"></span>

					<div class="icon-field pay-cont">
						<input type="text" class="form-control" placeholder="Credit Card Number" data-stripe="number">
						<i class="glyphicon glyphicon-credit-card"></i>
					</div>
					<div class="icon-field pay-cont">
						<input type="text" class="form-control" placeholder="CVC" data-stripe="cvc">
						<i class="glyphicon glyphicon-modal-window"></i>
					</div>
					<div class="icon-field pay-cont">
						<input type="text" class="form-control" placeholder="Expiration Month (MM)" data-stripe="exp-month">
						<i class="glyphicon glyphicon-calendar"></i>
					</div>
					<div class="icon-field pay-cont">
						<input type="text" class="form-control" placeholder="Expiration Year (YYYY)" data-stripe="exp-year">
						<i class="glyphicon glyphicon-calendar"></i>
					</div>
					<div class="icon-field pay-cont">
					<button type="button" class="btn btn-lg btn-red btn-full" id="main-checkout1" style="display:none;" onclick="validateCC()">BESTÄLL</button>
<input type="hidden" name="stripeToken" value="" id="stripeToken" />
</div>
				</div>
			</div>
		</div>
	</div>
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

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">28

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <a href="<?php echo home_url(); ?>" class="close" >&times;</a>
        <h4 class="modal-title">Bekräftelse</h4>
      </div>
      <div class="modal-body">
        <p>Tack för din beställning. <br> Om beställningen har gjorts under våra öppettider så kommer en bekräftelse på din beställning att mails till dig inom kort. Vänligen kontrollera din skräpkorg om du inte har fått någon bekräftelse inom 5 minuter. <br> Om du har lagt din beställning under tiden vi har stängt så skickas mailas en bekräftelse till dig så snart vi har öppnat.</p>
      </div>
      <div class="modal-footer">
        <a href="<?php echo home_url(); ?>" class="btn btn-default" >Close</a>
      </div>
    </div>
28
  </div>
</div>

<script type="text/javascript" src="<?php echo CHILD_URL; ?>/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php echo CHILD_URL; ?>/checkout-scripts/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo CHILD_URL; ?>/checkout-scripts/fancybox/jquery.fancybox.js"></script>

<script type="text/javascript" src="<?php echo CHILD_URL; ?>/checkout-scripts/js/ajax.js"></script>
<script src="https://cdn.auth0.com/js/lock-8.2.min.js"></script>

<script type="text/javascript" src="<?php echo CHILD_URL; ?>/checkout-scripts/js/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
jQuery(function($){

	var newuser = 0, orderTime = '', minTime = 0, maxTime = 0;
	var current_email = "<?php echo $current_user->user_email; ?>";
	var siteurl = $('#main-table').attr('data-rel');
	var ABSPATH = "<?php echo ABSPATH; ?>";
	var adminurl = "<?php echo admin_url('admin-ajax.php');?>";
	var AUTH0_CLIENT_ID = 'NGPPsIJAXVho281vlZPAEq7CCFs0695v'; 
	var AUTH0_DOMAIN = 'e2f.eu.auth0.com';
	var AUTH0_CALLBACK_URL = location.href;
	var lock = new Auth0Lock(
		AUTH0_CLIENT_ID,
		AUTH0_DOMAIN
    );
	
	// Auth0 Login
	$('.auth-login').click(function() {
		lock.showSignin(function(err, profile, token) {
			if (!err)
			{
				// Save the JWT token.
				localStorage.setItem('userToken', token);
				console.log(profile);
				// Check into local database
				$.ajax({
					url: adminurl,
					type: 'POST',
					data: {
						'action': 'login_auth0_user',
						'user': profile,
						'email': profile.email
					},
					success: function(value){
						if( value )
						{
							value = $.parseJSON(value);
							current_email = profile.email;
							$('#notloggedin').remove();
							$('#current_email').html(current_email);
							$('#loggedin, #userdata').show();
							$('#fullname').val( value.fname );
							$('#phone').val( value.mobile_number );							
						}
					}
				});
			}
		});
	});
	// Auth0 Signup
	$('.auth-signup').click(function() {
		lock.showSignup(function(err, profile, token) {
			if (!err)
			{ 
			  	// Save the JWT token.
			  	localStorage.setItem('userToken', token);
			  	console.log(profile);
			  	// Save into local database
				$.ajax({
					url: adminurl,
					type: 'POST',
					data: {
						'action': 'signup_auth0_user',
						'user': profile,
						'email': profile.email
					},
					success: function(value) {
						newuser = 1;
						current_email = profile.email;
						$('#notloggedin').remove();
						$('#current_email').html(current_email);
						$('#loggedin, #userdata').show();
						$('#fullname').val( value );
					}
				});
			}
		});
	});
	$(".menu-primary_menu-container ul li > a").each(function() {	
	  	var hr = $(this).attr('href');
	  	if(hr.charAt(0) == "#"){		 
			$(this).attr('href','<?php echo home_url(); ?>/'+ hr);
	  	}
	});
	var dateToday = new Date();
	var loaded = false;
	$('#input_datetime').datetimepicker({
		inline: true,
		timepicker: 0,
		dayOfWeekStart: 1,
		minDate: dateToday,
	    scrollMonth: 0,
	    maxDate: new Date(dateToday.getFullYear(), dateToday.getMonth()+2, -1),
	    yearEnd: false,
	    monthEnd: false, 
		onGenerate:function(dp,$input){
			if(!loaded)
			{
				$.ajax({
					url: siteurl+"/ajax/steps-otherTime.php",
					type: 'POST',
					data: 'datetime='+dp.toDateString()+'&tablename=takeaway_settings',
					success: function(value){
						var obj = $.parseJSON(value);
						minTime = convertToSeconds(obj[0]);
						maxTime = convertToSeconds(obj[1]);
						$('#orderTime').html( obj[0] && obj[1] ? obj[0] : '');
						$('#range').html( obj[0] && obj[1] ? obj[0]+'-'+obj[1] : 'Ingen tid tillgänglig');
						if( !obj[0] && !obj[1]) {
							$('.times').attr('disabled', true);
						} else {
							$('.times').attr('disabled', false);
						}
					}
				});
				loaded=true; 
			}
		},
	 	onSelectDate:function(dp,$input){
			$.ajax({
				url: siteurl+"/ajax/steps-otherTime.php",
				type: 'POST',
				data: 'datetime='+dp.toDateString()+'&tablename=takeaway_settings',
				success: function(value){
					var obj = $.parseJSON(value);
					minTime = convertToSeconds(obj[0]);
					maxTime = convertToSeconds(obj[1]);
					$('#orderTime').html( obj[0] && obj[1] ? obj[0] : '');
					$('#range').html( obj[0] && obj[1] ? obj[0]+'-'+obj[1] : 'Ingen tid tillgänglig');
					if( !obj[0] &&main-checkout1 !obj[1] ) {
						$('.times').attr('disabled', true);
					} else {
						$('.times').attr('disabled', false);
					}
				}
			});
		}
	}).datetimepicker('hide');
	$.datetimepicker.setLocale('se');
	$('#radio2').on('click', function () {
		if ($(this).is(':checked')){
			$('#radio1').attr('checked', false);
			$('#asap').val(0);
			$('#input_datetime_div').show();
			$('#input_datetime').datetimepicker('show');
		}
	});		
	
	$('#radio1').on('click', funcmain-checkout1tion () {
		if ($(this).is(':checked')) {
			$('#radio2').attr('checked', false);
			$('#asap').val(1);
			$('#input_datetime_div').hide();
			$('#input_datetime').datetimepicker('hide');
		}
	});
	
	$('.popup').fancybox({
		maxWidth:800,
	});
	$(document).on('click','.create-accountt',function(){
		$('.signup-form').show();
		$('.provide-emailid').hide();
	});
	$(document).on('click','.show-glomt',function(){
		$('.provide-emailid').show();
		$('.signup-form').hide();
	});
	$('.pay-option').on('click', function () {
	    var ptype = $(this).val(); 
	    $("#paymenttype").val(ptype);


	    $('.stripe-form').hide();
	    if(ptype=="online"){
	    $('.stripe-form').show();	
	    $('#main-checkout').hide();
	    $('#main-checkout1').show();
	    }else{
	    	$('#main-checkout1').hide();
	    $('#main-checkout').show();
	    }

		$('.invoice-form').hide();
		if ($('.invoice-form-show').is(':checked')){
			$('.invoice-form').show();
			$('#main-checkout').hide();
		}
	});
	$(document).on("click",'#main-checkout',function() {
	    if( current_email )
	    {
	    	var fullname = $('#fullname').val();
	    	var phone = $('#phone').val();
	    	if( !fullname || !phone ) 
	    	{
	    		alert('Uppge ditt mobilnummer för att genomföra beställningen');
	    		$('#phone').focus();
	    		return false;
	    	}
		    // Check if time is selected
		    var asap = $('#asap').val();
		    var time = $('#orderTime').html();
		    if( asap==0 && !time ) 
		    {
		    	alert('Ange när du vill ha din beställning');
		    	return false;
		    }
		    
		    var totalprice = $('#total-price').val();
		    var deliver = 0;
			var paymenttype =  $('#paymenttype').val();

var stripe_token = '';
if(paymenttype=="online"){
	var temp_token = $("#stripeToken").val();
	if(temp_token!=""){
		stripe_token = "&stripeToken="+temp_token;
	}
}


			var uniq =  '<?php echo $chkqnkid; ?>';
			var cart_opt = 0;
			var type = "<?php echo $type; ?>";
			var date = $('#input_datetime').datetimepicker('getValue');
		    date = date.toDateString();
			$.ajax({
				url: siteurl+"/ajax/saveOrders.php",
				type: 'POST',
				async: false,
				data: 'em='+encodeURIComponent(current_email)+'&fullname='+encodeURIComponent(fullname)+'&phone='+encodeURIComponent(phone)+'&newuser='+encodeURIComponent(newuser)+'&date='+encodeURIComponent(date)+'&time='+encodeURIComponent(time)+'&paymenttype='+encodeURIComponent(paymenttype)+'&uniq='+encodeURIComponent(uniq)+'&asap='+encodeURIComponent(asap)+'&deliver='+encodeURIComponent(deliver)+'&totalprice='+encodeURIComponent(totalprice)+'&type='+encodeURIComponent(type)+stripe_token,
				success: function(value){
				    if(value=='done'){
							$('#myModal').modal({
                               backdrop: 'static',
                               keyboard: false
                           });
					} else {
						alert('Det gick inte att lämna orderdetaljer .');
					}
				}
			});
		} else {
			alert('Logga in eller skapa ett konto för att genomföra beställningen.');
		}
	});
	
	$(document).on('click','.setting-btn',function(){
		var siteurl = $('#main-table').attr('data-rel');
		var id = $(this).attr('data-id');
		var menu_det = $(this).attr('data-rel');
		var price = $(this).attr('data-price');
		
		var uniq =  '<?php echo $chkqnkid; ?>';
		$('.special_request_content').html('<center><img src="'+siteurl+'/images/loader.gif'+'"></center>');
		$.ajax({
			url: siteurl+"/checkout-special-request.php",
			type: 'POST',
			data: 'id='+encodeURIComponent(id)+'&menu_det='+encodeURIComponent(menu_det)+'&siteurl='+encodeURIComponent(siteurl)+'&price='+encodeURIComponent(price)+'&chkuniqeid='+encodeURIComponent(uniq),
			success: function(value){
				$('.special_request_content').html(value);
			}
		});
		
		$('.fader, #special_request').fadeIn();
	});
	$('#addHour').click(function(){
		var current = $('#orderTime').html();
		var time = current.split(':');
		var hour = parseInt(time[0]) + 1;
		var minute = time[1];
		current = convertToSeconds(hour+':'+minute);
		if( hour<24 && current>=minTime && current<=maxTime ) {
			minute = current!=maxTime ? minute : '00';
			$('#orderTime').html(hour+':'+minute);
		} else {
			alert('Du har valt ett ogiltigt tid, försök igen');
		}
	});
	$('#subHour').click(function(){
		var current = $('#orderTime').html();
		var time = current.split(':');
		var hour = parseInt(time[0]) - 1;
		var minute = parseInt(time[1]);
		current = convertToSeconds(hour+':'+minute);
		if( hour>0 && current>=minTime && current<=maxTime ) {
			$('#orderTime').html(hour+':'+minute);
		} else {
			alert('Du har valt ett ogiltigt tid, försök igen');
		}
	});
	$('#addMin').click(function(){
		var current = $('#orderTime').html();
		var time = current.split(':');
		var hour = parseInt(time[0]);
		var minute = parseInt(time[1]) + 15;
		if( minute == 60 ){
			minute = '00';
			hour++;
		}
		current = convertToSeconds(hour+':'+minute);
		if( hour<24 && current>=minTime && current<=maxTime ) {
			$('#orderTime').html(hour+':'+minute);
		} else {
			alert('Du har valt ett ogiltigt tid, försök igen');
		}
	});
	$('#subMin').click(function(){
		var current = $('#orderTime').html();
		var time = current.split(':');
		var hour = parseInt(time[0]);
		var minute = parseInt(time[1]) - 15;
		minute = minute== 0 ? '00' : minute;
		if( minute == '-15' ){
			minute = 45;
			hour--;
		}
		current = convertToSeconds(hour+':'+minute);
		if( hour>0 && current>=minTime && current<=maxTime ) {
			$('#orderTime').html(hour+':'+minute);
		} else {
			alert('Du har valt ett ogiltigt tid, försök igen');
		}
	});
	$(document).on('click','.remove-tillval',function(){
 		var portion = $(this).attr('data-rel');
		var portion_options = $(this).attr('data-id');
		var id = $(this).attr('data-title');
		var siteurl = $('#main-table').attr('data-rel');
		var dishnum = $(this).attr('data-count');
		var menu_id = jQuery("#btn_skip").attr('data-placeholder');
		var uniq = $('#btn_skip').attr('data-rel');
		
		var type = 'update';
		if($('.cart-opt').length==1){
			type = 'delete';
		}
		
		$('.'+portion+', #'+portion_options).remove();
		
		//jQuery('.reqtotal label').html('Updating...');
		
		var orig_item_price=Number($('.reqtotal').attr('data-price'));
		getOptionTotal(orig_item_price);
		
		var current_total_price = Number($('.reqtotal label').attr('data-total'));
		
		$('.reqtotal label').attr('data-total', (current_total_price - orig_item_price));
		
		$.ajax({
			url: siteurl+"/ajax/update-quantity.php",
			type: 'POST',
			async: false,
			data: 'menu_id='+encodeURIComponent(menu_id)+'&id='+encodeURIComponent(id)+'&type='+encodeURIComponent(type)+'&dishnum='+encodeURIComponent(dishnum)+'&uniq='+encodeURIComponent(uniq)+'&check=check',
			success: function(value){
			var obj = $.parseJSON(value);
		    var fieldName = $(".qtyminus").attr('field');
 	        var currentVal = parseInt($('input[name='+fieldName+']').val());
   		    if (!isNaN(currentVal)) {
            $('input[name='+fieldName+']').val(currentVal-1);
        	} else {
            $('input[name='+fieldName+']').val(0);
              var val = 0;
	        }
		    $('.total-amt').html(obj.total);
		    $("#subtotal-"+id).html(obj.subtotal);
		     $(".reqtotal label").html(obj.subtotal);
		     $("#total-price").val($(".reqtotal label").attr('data-total'));
             
		     checkgetcountItem(0);
				if($('.cart-opt').length==2){
					$('.remove-tillval, .cart-opt input[type=button]').hide();
				}
				
			}
		 });
	});
});


function validateCC(){
	Stripe.setPublishableKey('pk_test_5xbqEycbrzJY82RQgzffb5mg');
	var $form = $(".stripe-form");
	Stripe.card.createToken($form, stripeResponseHandler);
}

function stripeResponseHandler(status, response) {
  var $form = $('.stripe-form');

  if (response.error) {
    // Show the errors on the form
    $form.find('.payment-errors').text(response.error.message);
    //$form.find('button').prop('disabled', false);
    throw new Error("Something went badly wrong!");
  } else {
    // response contains id and card, which contains additional card details
    var token = response.id;
    // Insert the token into the form so it gets submitted to the server
    $('#stripeToken').val(token);
    // and submit
    //$form.get(0).submit();
    $('#main-checkout').click();
  }
  
};

function checkgetcountItem(del)
{
	var type = "<?php echo $_POST['type']; ?>";
	var takeawayId = '';
	var lunchId = '';
	var Uid = '';
	if(type == 'takeaway'){
	  takeawayId = '<?php echo $chkqnkid; ?>';	
	}else if(type == 'lunch'){
      lunchId = '<?php echo $chkqnkid; ?>';
	}
	var count = '';
	 var siteurl = $('#main-table').attr('data-rel');
	 var uniq = $('#btn_skip').attr('data-rel');
	$.ajax({
		url: siteurl+"/countItem.php",
		type: 'POST',
		async: false,
		data: 'unique_id='+encodeURIComponent(Uid)+'&uniq='+encodeURIComponent(takeawayId)+'&lmuniq='+encodeURIComponent(lunchId)+'&bfuniq='+encodeURIComponent(Uid)+'&del='+del,
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
// Convert time to seconds
function convertToSeconds(time)
{
	if( !time ) {
		return 0;
	}
	var a = time.split(':');
	var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60; 
	return seconds;
}
</script>

<style>
.xdsoft_noselect {
	position:relative !important;
	left: 0 !important;
	top: 0 !important;
}
</style>
<?php get_footer(); ?>