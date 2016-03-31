<?php

	include 'config.php';
	// $tempTotal = explode( "=", $_POST['total']);
	$totalnumber = $_POST['total'];
	$tablename = $_POST['tablename'];
	
	$q=mysql_query("select var_value from settings where var_name='week_starts'");
	$row=mysql_fetch_assoc($q);
	
	$currentDates = date("m/d/Y");
	$dayName=date('D', strtotime($currentDates));
	
	//get the current time
	$t=time();
	$currentTime=date("Hi",$t);
	
	
	$q=mysql_query("select id, if(".$currentTime.">(replace(end_time,':','')), (replace(end_time,':',''))+2400, (replace(end_time,':',''))) as theendtime from ".$tablename." where DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d') >= DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') and DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d') <= DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') and ".$currentTime.">=(replace(start_time,':','')) and ".$currentTime."<=if(".$currentTime.">(replace(end_time,':','')), (replace(end_time,':',''))+2400, (replace(end_time,':',''))) and days like '%".$dayName."%' and deleted=0 limit 1") or die(mysql_error());
	
	$rs=mysql_fetch_assoc($q);
	$currentID=$rs['id'];
	
	
	//get the available days...
	$thedays='';
	$qr=mysql_query("select days, end_date, start_time, end_time, start_date, date_format(STR_TO_DATE(start_date, '%m/%d/%Y'),'%d %M %Y') as formatted_startdate, date_format(STR_TO_DATE(end_date, '%m/%d/%Y'),'%d %M %Y') as formatted_enddate, advance_time as advancetime  from ".$tablename." where id=".$currentID);
	$rq=mysql_fetch_assoc($qr);
	
	$days=explode(',',$rq['days']);
	
	$theend_date = $rq['end_date'];	
	$thestart_date = $rq['start_date'];	
	$current_starttime = $rq['start_time'];	
	$current_endtime = $rq['end_time'];
	$theactivedays = $rq['days'];
	$formatted_startdate = $rq['formatted_startdate'];
	$formatted_enddate = $rq['formatted_enddate'];
	$advancetime = $rq['advancetime'];
	
	$daynum=array('Mon'=>1,'Tue'=>2,'Wed'=>3,'Thu'=>4,'Fri'=>5,'Sat'=>6,'Sun'=>0);
	
	for($i=0;$i<count($days);$i++){
		$thedays.="'".$daynum[trim($days[$i])]."',";
		//$availableDays .=$daynum[trim($days[$i])].",";
	}
	//echo 'Active Days';
	//$availableDays = $availableDays=substr($availableDays,0,strlen($availableDays)-1);
	
	$thedays=substr($thedays,0,strlen($thedays)-1);
	
	
	$themonth = date("m");
	$theday = date("d");
	$theyear = date("Y");
	$thehour = date("H");
	$themin = date("i");
	
	
	//Start getting available days from takeaway-settings c/o jk
	
	
	if($sql_count>1){
	$query = "select days from ".$tablename." where deleted=0 and end_date >=date_format(STR_TO_DATE('".$currentDates."', '%Y-%m-%d'),'%m/%d/%Y')";
	}else{
		$query = "select days from ".$tablename." where deleted=0";
	}
	
	//ako sa giusob jeck :D
	
	//$query = "select days from ".$tablename." where id='".$currentID."'";
	
	$qdate=mysql_query($query) or die(mysql_error());
	$availableDays = '';
	while($rdate = mysql_fetch_assoc($qdate)){
		$av_dayz=explode(',',$rdate['days']);
		for($i=0;$i<count($av_dayz);$i++){
			$availableDays .=$daynum[trim($av_dayz[$i])].",";
		}
	}
	
	
	$availableDays = $availableDays=substr($availableDays,0,strlen($availableDays)-1);
	$array_days = explode(',',$availableDays);
	$av_unique = array_unique($array_days);
	$availableDays = implode(',',$av_unique);
	
	$sql_counts = mysql_query("SELECT id FROM ".$tablename." where deleted = 0");
	//$sql_count = mysql_fetch_assoc($sql_count);
	$sql_count = mysql_num_rows($sql_counts);
	
	if($sql_count>1){
		
		$maxDate=mysql_query("select min(DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d')) as stdate, max(end_date) as date, max(DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d')) as endtime, min(start_time) as sttime, DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d') as curdate,advance_time as advancetime, if(DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') < DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d'),1,0) as checker from ".$tablename." where deleted = 0 and DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') >=DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d')") or die(mysql_error());
		
		/*$maxDate = mysql_query("select min(DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d')) as stdate, max(DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d')) as enddate, min(STR_TO_DATE(start_time, '%H:%i')) as sttime, max(STR_TO_DATE(end_time, '%H:%i')) as endtime, DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d') as curdate,advance_time as advancetime,
days
from takeaway_settings 
where deleted = 0");*/
	
	}else{
		
		$maxDate=mysql_query("select DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') as stdate, DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') as date, end_time  as endtime, start_time  as sttime, DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d') as curdate,advance_time as advancetime, if(DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') < DATE_FORMAT(STR_TO_DATE('".$currentDates."', '%m/%d/%Y'), '%Y-%m-%d'),1,0) as checker from ".$tablename." where deleted = 0") or die(mysql_error());
		
	}
	
	$noSettings = mysql_num_rows($maxDate);
	
	$maxDate = mysql_fetch_assoc($maxDate);
	
	$advancetime = $maxDate['advancetime'];
	$checker = $maxDate['checker'];
	
	//start date yy-mm-dd
	$stdate = $maxDate['stdate'];
	$sqlDateSTDate = explode('-',$stdate);
	
	$strtdate = $sqlDateSTDate[1].'/'.$sqlDateSTDate[2].'/'.$sqlDateSTDate[0];
	
	if($checker==1){
		$noSettings=0;
	}
	
	$minstartdate = $currentDates;
	
	if(strtotime($currentDates) < strtotime($strtdate)){
		$minstartdate = $strtdate;
	}
		
	$activeDate = $minstartdate;
	
	$minstartdate = ''.str_replace('/','-',$minstartdate);
	
	
	//end date
	$sqlDateSTDate = explode('-',$maxDate['date']);
	
	$endDate = $sqlDateSTDate[1].'/'.$sqlDateSTDate[2].'/'.$sqlDateSTDate[0];
	
	$MaxDate = ''.str_replace('/','-',$endDate);
	
	
	//start getting time for just today
	$curDate = explode('/',$currentDates);
	$curDate_a = $curDate[2].'-'.$curDate[0].'-'.$curDate[1];
	
	$nowTime = date("H:i");
	$hour = date("H");
	$mins = date("i");
	//echo $nowTime;
	
	$timestamp = strtotime($curDate_a);
	$day = date('D', $timestamp);
	
	//echo $day;
	
	//and CAST(start_time As Time) <= CAST('$nowTime' As Time) 
	//and CAST(end_time As Time) >= CAST('$nowTime' As Time)
	$query_time = "Select start_time,end_time,advance_time from $tablename
	where deleted = 0 
	and DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') <= '$curDate_a'
	and DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') >= '$curDate_a' 
	and (CAST(start_time As Time) <= CAST('$nowTime' As Time) or CAST(end_time As Time) >= CAST('$nowTime' As Time))
	and days like '%$day%'
	order by DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') limit 1";
	
	$sql_time = mysql_query($query_time) or die(mysql_error());
	
	//echo $query_time;
	//echo 'rows: '.mysql_num_rows($sql_time).' ';
	while($row = mysql_fetch_assoc($sql_time)){
		//$row['start_time'].'='.$row['end_time'].'='.$row['advance_time'];	
		
		$MaxHrmn = explode(':',$row['end_time']);
		$MinHrmn = explode(':',$row['start_time']);
		
		$MaxTime = $row['end_time'];
		$MinTime = $row['start_time'];
		
		$maxHr = $MaxHrmn[0];
		$maxMn = $MaxHrmn[1];
		
		$MinHr = $MinHrmn[0];
		$MinMn = $MinHrmn[1];
		
		$advance = $row['advance_time'];
		
		$thour = $hour;
		
		if($advance>=60){
			$thour=$hour+1;
		}else{
			$ttempmins = 0;
			$ttempmins = $mins+$advance;
			if($ttempmins >=60){
				$ttempmins = $ttempmins-60;
				$thour=$hour+1;
			}
		}
	}
	
	//end getting time for just today
	
	
	//echo $availableDays;
	//End getting available days from takeaway-settings c/o jk
	// $minstartdate;
	
	$sql_query = mysql_query("select DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') as stdate, DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') as enddate
from ".$tablename." 
where deleted = 0") or die(mysql_error());
	
	//echo mysql_num_rows($sql_query);
	$date_arr = array();
	while($row = mysql_fetch_assoc($sql_query)){
		$date_arr = array_merge($date_arr, getDatesFromRange($row['stdate'],$row['enddate']));
	}
	//print_r($date_arr);
	
	//echo implode('","',$date_arr);
	
	function getDatesFromRange($start, $end){
		$dates = array($start);
		while(end($dates) < $end){
			$dates[] = date('Y-m-d', strtotime(end($dates).' +1 day'));
		}
		return $dates;
	}
	
?>
<input type="hidden" id="activeDate" value="<?php echo $minstartdate; ?>" />
<input type="hidden" class="thecookie" value="<?php if($tablename=='takeaway_settings'){ echo 'ta';} else if($tablename=='breakfast_settings'){ echo 'bf';} else{ echo 'lm'; } ?>">
<input type="hidden" id="currenDate" value="<?php echo $theyear.'-'.$themonth.'-'.$theday; ?>" />
<script type="text/javascript">
	
	var MaxMin = 0;
	var MaxHour = 0;
	var MinHour = 0;
	var MinMin = 0;
	
	var da = new Date();
	var hour = da.getHours();
	var mins = da.getMinutes();
	
	var curHour = 0;
	var curMin = 0;
	
	var valmini = '';
	var minmins = '';
	
	var todaysDate = '0000-00-00';
	var calendarDate = '0000-00-00';
	var calendarDateTime = '0000-00-00 00:00';
	var activeDateTime = '0000-00-00 00:00';
	
	var activeDate = '';
	
	var isMoveDay = 0;
	//alert(activeDate);
	function validateEmail(email){  
		 if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))  
			return (true);
		 else  
			return (false);  
	 }  
	 
	 function isDate(val) {
		var d = new Date(val);
		return !isNaN(d.valueOf());
	 }
	 
	$(function(){
		var thecookies = $('.thecookie').val();
	
		if(thecookies=='lm'){
			thecookie = jQuery.cookie('lunchmeny_id');
		}
		else if(thecookies=='bf'){
			thecookie = jQuery.cookie('breakfast_id');
		}
		else{
			thecookie = jQuery.cookie('takeaway_id');
		}
		
		
		$('.prevs, .noactive, .close-steps').click(function(){
			
			var thetype = $(this).attr('data-id');
			
			if(thetype=='takeaway_settings' || thetype=='breakfast_settings'){
			
				$('.takeawaycart-detail, .fade').css({'display':'block'});
				$('.errormsg').css({'display':'none'});
				$('.step-2-wrapper').html('');
				//$('html, body').animate({scrollTop:$('#text-24').position().top}, 'slow');
			
			}
			else{
				
				$('.lunchmenycart-detail, .fade').css({'display':'block'});
				$('.errormsg').css({'display':'none'});
				$('.step-2-wrapper').html('');
				//$('html, body').animate({scrollTop:$('#text-lunchmeny').position().top}, 'slow');
				
			}
			
		});
		 
		 
		/* $('.ifnew').click(function(){
			// $('.resemails').val($('.takeemail').val());	
		 });*/
		 
		 $('.boka').click(function(){
		 	var em=$('.takeemail').val();
			var pw=$('.takepass').val();
			var agree=$('#agree11').prop('checked');
			var datetime=$('#datepicker2').val();
			var check = $('.remember').prop("checked");
			var asap = 0;
			var deliver = $('.deliver').prop('checked');
			if(check) {
				rememberMe(em, pw);
			}
			
			if(deliver){
				deliver=1;
			}
			else{
				deliver=0;
			}
			
			
			$('.step-2 .displaymsg').fadeOut();
			
			//if(em!='' & pw!='' & datetime!=''){
			if(em!=''){
				
				if(pw!=''){
					
					if(datetime!=''){
					
						if(validateEmail(em)){
							
							if(agree){
						
								
								var paymenttype=$('.paymenttype').val();
								
								var notgo = 0;
							
								if(datetime.indexOf('Tid')!=-1){
									datetime=$('#datepicker2').attr('data-rel');
									asap=1;
								}
								
								var tocontinue=0;
								
								
								//added
							
								var d = new Date(datetime);
							
								
								var hour = d.getHours();
								if(hour<10){
									hour = '0'+hour;
								}
								
								var minute = d.getMinutes();
								if(minute<10){
									minute = '0'+minute;
								}
								
								var thedayy = d.getDay();
								
								var selecteddate = (d.getMonth()+1)+'/'+d.getDate()+'/'+d.getFullYear();
								var thetime = hour+':'+minute;
								
								if($("#datepicker2").val()!=''){
									
									var notgo = 0;
							
									if(datetime=='Välj tid och datum'){
										tocontinue=0;
										
										$('.step-2 .displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html('Välj datum för att fortsätta.');
										
									}
									else{
										
										tocontinue=1;
									
									}
									
								}
								else{
									
									$('.step-2 .displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html('Vänligen välj tid och datum för avhämtning.');
									
									//goScroll('step2err');
								}
								
								//end added
								
								
								if(tocontinue==1){
								
									
								$('.step-2 .displaymsg').fadeIn().html('<img src="<?php echo $site_url; ?>/wp-content/themes/outreach/images/rectangular.gif" style="margin:0 auto;">').removeClass('errormsg');	
								var opt_cookie =unescape(jQuery.cookie('cart_opt'))
								var cart_opt=opt_cookie.split(',')
								$.ajax({
									 url: "<?php echo $site_url; ?>/wp-content/themes/outreach/saveTakeAway.php",
									 type: 'POST',
									 data: 'em='+encodeURIComponent(em)+'&pw='+encodeURIComponent(pw)+'&datetime='+encodeURIComponent(datetime)+'&paymenttype='+encodeURIComponent(paymenttype)+'&uniq='+encodeURIComponent(thecookie)+'&asap='+encodeURIComponent(asap)+'&cart_opt='+cart_opt.toString()+'&deliver='+encodeURIComponent(deliver)+'&ordertype='+encodeURIComponent(thecookies),
									 success: function(value){
										 //console.log('cart opt - '+value);
										 //return false;
										 if(value=='Invalid'){
											 
											 $('.step-2 .displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html('Den angivna e-postadressen är inte registrerad.'); 
											 
										 }
									
										 else{
											 
											 $('.step-2 .displaymsg').fadeOut();
											 
											 $('.success-box .box-content').html('');
											 $('.success-box .box-content').html(" Tack för din beställning. En bekräftelse på din beställning kommer inom kort att mailas till dig.<br><br>Vänligen kontrollera din skräpkorg om du inte har fått någon bekräftelse inom 5 minuter.<br><br>Ring oss om du har några frågor på telefonnummer: 08-10 70 67.").css('text-align','left');
											 $('.success-box, .fade3').fadeIn('slow');
											
											 gotoModal();
											 
											 $('.menu-takeaway, .takeawaycart-detail, .fade').css({'display':'none'});
											 //$('html, body').animate({scrollTop:$('#text-24').position().top}, 'slow');
											 
											 if(thecookies=='lm'){
												jQuery.removeCookie('lunchmeny_id', { path: '/' });
											 }
											 else if(thecookies=='bf'){
												jQuery.removeCookie('breakfast_id', { path: '/' });
											 }
											 else{
												jQuery.removeCookie('takeaway_id', { path: '/' });
											 }
											 
											 
											 jQuery.removeCookie("inv_business", { path: '/'});
											 jQuery.removeCookie("inv_orgnum", { path: '/' });	
											 jQuery.removeCookie("inv_address", { path: '/'});	
											 jQuery.removeCookie("inv_zip", { path: '/'});	
											 jQuery.removeCookie("inv_location", { path: '/'});
											 jQuery.removeCookie("cart_opt", { path: '/'});
											 
											 jQuery.removeCookie("d_name", { path: '/'});
											 jQuery.removeCookie("d_mobile", { path: '/' });	
											 jQuery.removeCookie("d_address", { path: '/'});	
											 jQuery.removeCookie("d_buzz", { path: '/'});	
											 jQuery.removeCookie("d_other", { path: '/'});
											 
											 //added
											 jQuery.removeCookie('cart_opt');
											
										 }
										
									 }
								 });
								
								}
								 
							}
							else{
								$('.step-2 .displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html('Vänligen läs och godkänn användarvillkoren.');
							}	 
								
						}
						else{
							$('.step-2 .displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html('Den angivna e-postadressen är felaktig.');
						}
				
					}
					else{
						$('.step-2 .displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html('Vänligen välj tid och datum för avhämtning');
					}
				
				}
				else{
					$('.step-2 .displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html('Fel lösenord - försök igen');
				}
					
			}
			else{
				
				$('.step-2 .displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html('Vänligen uppge en giltig e-postadress');
				
			}
			
			
		 });
		 
		 
		  $('.registering, .ifnew').click(function(){
			  
			
			$('.tos2, .takeaways-btn').show();
			$('.tos1, .bokas-btn').hide();
			
			
			$('.signup-box .displaymsg').removeClass('errormsg').html('');
			$('.fade2, .signup-box').fadeIn('slow');	
			//gotoModal();
		
		  });
		  
		
		
		
	//bokas-btn here...
	 
	  $(".takeaways-btn").bind("click", function( event ) {	
	  //$('.takeaways-btn').click(function(){
			
		 var fname=$('.signup-box .resfname').val();
		 var lname=$('.signup-box .reslname').val();
		 var email=$('.signup-box .resemails').val();
		 var phone=$('.signup-box .resphone').val();
		 var company=$('.signup-box .rescompany').val();
		 var street=$('.signup-box .resstreet').val();
		 var city=$('.signup-box .rescity').val();
		 var zip=$('.signup-box .reszip').val();
		 var country=$('.signup-box .rescountry').val();
		 var pword=$('.signup-box .respwords').val();
		 var cpword=$('.signup-box .rescpwords').val();
		 var agree = $('#agree22').prop('checked');
		 
		 $('.signup-box .displaymsg').fadeOut('slow');
		 
		 if(fname!=''){
			 
			 if(lname!=''){
				 
				 if(email!=''){
					 
					 if(phone!=''){
			
						 if(pword!=''){	
						 
							if(cpword!=''){
								
								 if(pword==cpword){
						
								
									 	if(validateEmail(email)){
										 
											 if(agree){
												 
												
												
													 
													 $('.signup-box .displaymsg').fadeIn().html('<img src="<?php echo $site_url; ?>/wp-content/themes/outreach/images/rectangular.gif" style="margin:0 auto;">').removeClass('errormsg');
													 
													
													 $.ajax({
														 url: "<?php echo $site_url; ?>/wp-content/themes/outreach/saveOtherTakeAway.php",
														 type: 'POST',
														 data: 'fname='+encodeURIComponent(fname)+'&lname='+encodeURIComponent(lname)+'&email='+encodeURIComponent(email)+'&phone='+encodeURIComponent(phone)+'&company='+encodeURIComponent(company)+'&street='+encodeURIComponent(street)+'&city='+encodeURIComponent(city)+'&zip='+encodeURIComponent(zip)+'&country='+encodeURIComponent(country)+'&pword='+encodeURIComponent(pword),
														 success: function(value){
																 
																 value=value.trim();
																 
																 if(value=='Invalid'){
																	 
																	 $('.signup-box .displaymsg').fadeIn('slow').addClass('errormsg').html('E-postadressen du har angett är redan registrerad. Vänligen uppge en annan E-postadress.');
																	 
																 }
																 else{
																	 
																	$('.takeemail').val(email); 
																	$('.signup-box, .fade2').fadeOut();
															 
																 } 
																 
														   }
													  });
												  
											
												
											
											 }
											 else{
													$('.signup-box .displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Vänligen läs och godkänn användarvillkoren.');
											 }
											 
										
										 
									 }
									 else{
										$('.signup-box .displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Den angivna e-postadressen är felaktig.');		
									 }
								
								
								
								 }
								 else{
										$('.signup-box .displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Lösenord och upprepa lösenordet matchar inte.');		
								}
								 
							
							}
							else{
								$('.signup-box .displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Felaktigt lösenord, försök igen.');
							}
							 
						}
						else{
							$('.signup-box .displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Vänligen uppge ett lösenord.');
						}
					
					 }
					 else{
						$('.signup-box .displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Mobilnummer krävs');	
					 }
				
				 }
				 else{
					$('.signup-box .displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Vänligen uppge en giltig e-postadress');
				 }
			
			 }
			 else{
			 	$('.signup-box .displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Efternamn krävs');
			 }
			 
		 }
		 else{
		
		 	$('.signup-box .displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Förnamn krävs');
		 }
			
	  });
	 $('.agreelabel1').click(function(){
		 
		 
			 $('.fade, .agreement1-box').fadeIn();
			 gotoModal();
			 
	
	 });
	 
	 
	// $(this).unbind(event);
		
	});
	
	function loadMenu(str){
	
			$('.menu-box .box-content').html('<img src="<?php echo $site_url; ?>/wp-content/themes/outreach/images/preloader.gif" style="margin:0 auto;">');	
			 
			$.ajax({
				url: "<?php echo $site_url; ?>/wp-content/themes/outreach/loadMenu.php",
				type: 'POST',
				data: 'str='+encodeURIComponent(str),
				success: function(value){
				 
				   $('.menu-box .box-content').html(value);
				
				}
			 });
			 
			$('.fade4, .menu-box').fadeIn();
	}
</script>
<script type="text/javascript">
var daysToDisable = [];
 function checktheDay(date,thedays){
	 
	 var val=thedays.toString().indexOf(date.getDay().toString());
	 
	 if(val!=-1)
		 return true;	 
	 else
	 	return false;
 }
 
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
function goScroll(id){
	 $('html,body').animate({
        scrollTop: $("#"+id).offset().top - 150},
        'slow');
}
  $(function(){
	  
	    /*var da = calcTime('+2').split(' ');
		var d = da[1].split(':');
		
		var hour = Number(d[0]);
		var mins = Number(d[1]);*/
		
		var thedays = [<?php echo $thedays; ?>]; 
		
		var today = '<?php echo $themonth.'/'.$theday.'/'.$theyear; ?>';
		
		var advancetime = '<?php //echo $advancetime; ?>'.split('.');
		
		var addhour = 0;
		var addmin = 0;
		
		todaysDate = '<?php echo $theyear.'-'.$themonth.'-'.$theday; ?>';
		
		if(advancetime.length>1){
			addhour=Number(advancetime[0]);	
			addmin=Number(advancetime[1])*6;	
		}
		else{
			addhour=Number(advancetime[0]);	
		}
		
		/*if(addhour==0){
			addhour=1;
		}*/
		
		hour = hour + addhour;
		var newmin = mins+addmin;
		mins = newmin;
		
		if(newmin >= 60){
		
			var thenewmin = newmin-60;
			
			if(thenewmin==0){
				mins= 0;
			}else if(thenewmin>0 || thenewmin< 15){
				mins= 15;
			}
			else if(thenewmin>15 || thenewmin<30){
				mins=30;
			}
			else if(thenewmin>30 || thenewmin<45){
				mins=45;
			}
			else if(thenewmin>45 || thenewmin<60){
				//mins=45;
				hour = hour+1;
			}
			
		}
		else{
			if(thenewmin==0){
				mins= 15;
			}else if(thenewmin>0 || thenewmin< 15){
				mins= 15;
			}
			else if(thenewmin>15 || thenewmin<30){
				mins=30;
			}
			else if(thenewmin>30 || thenewmin<45){
				mins=45;
			}
			else if(thenewmin>45 || thenewmin<60){
				//mins=45;
				hour = hour+1;
			}
		}
		
		
		if(hour>=24){
			hour = Number('00');
		}
		
		daysToDisable = [<?php echo $availableDays;?>]
		
		/*var enableSpecificDays = function(date) {
			var day = date.getDay();
			for (i = 0; i < daysToDisable.length; i++) {
				if ($.inArray(day, daysToDisable) != -1) {
					return [true];
				}
			}
			return [false,'pinkies'];
		}*/
		
		daysToActive = ["<?php echo implode('","',$date_arr);?>"];
		
		var date1 = new Date(today);
		var date2 = new Date("<?php echo str_replace('-','/',$MaxDate);?>");
		var timeDiff = Math.abs(date2.getTime() - date1.getTime());
		var numOfActiveDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
		
		curHour = hour;
		curMin = mins;
		
		var newDateVal = '';
	    $( "#datepicker2" ).datetimepicker({
			showOn: "button",
			minDate: new Date("<?php echo str_replace('-','/',$minstartdate);?>"),
			maxDate: new Date("<?php echo str_replace('-','/',$MaxDate);?>"),
			beforeShowDay: function(date,inst) {
			
				var string = $.datepicker.formatDate('yy-mm-dd', date);
				var day = date.getDay();
				
				for (i = 0; i < daysToActive.length; i++) {
					if ($.inArray(string, daysToActive) != -1 && $.inArray(day, daysToDisable) != -1) {
						return [true];
					}
				}
				return [false,'pinkies'];
			},
			dateFormat: 'yy-mm-dd',
			timeFormat: 'hh:mm',
			addSliderAccess: true,
			sliderAccessArgs: { touchonly: false },
			onSelect:function(date,ui){
				//console.log(date);
				setTimeout(function(){
					$('.ui-timepicker-div dl').html('<div class="beforetime"><span class="loading">Bearbetar...</span></div>');
					$('#datepicker2').val('Bearbetar...');
					$('.ui-datepicker-close ').hide();
				},100);
				
				newDateVal = date;
				$('.steps-button-2 input').prop('disabled',true);
				
				setTimeout(function(){
					custometimeslider(0);
				},200);
				
				setTimeout(function(){
					var curVal = date;
					cur_arr = curVal.split(' ');
					
					$('#datepicker2').val(cur_arr[0]+' '+$(".starttime1").val());	
					//alert('aaa');
					
				},1000);
				
				var dt = date.split(' ');
				var dd = dt[0];
				var hhmm = dt[1].split(':');
				var hh = hhmm[0];
				var mm = hhmm[1];
				
				calendarDate = dd;
				
				if(hh >= Number(<?php echo $maxHr;?>) && mm > Number(<?php echo $maxMin?>)){
					$('.ui_tpicker_time').html('<?php echo $MaxTime;?>');
				}
			},
			onClose: function(date, ui) {
				calendarDateTime = date;
				setTimeout(function(){
					$('#datepicker2').val(calendarDateTime);
					$('.steps-button-2 input').prop('disabled',false);
				},100);
			}
			
		}).click(function(){
			
			$('.steps-button-2 input').prop('disabled',true);
			
			isMoveDay = 0;
			
			var curTime = hour+':'+mins;
			var time1 = "<?php echo $MinHr;?>:<?php echo $MinMn;?>";
			var time2 = "<?php echo $maxHr;?>:<?php echo $maxMn;?>";
			
			var curDay = new Date(todaysDate).getDate();
			var calDay ='';
			var myDate = new Date(todaysDate);
			
			//2014-12-09
			//12-09-2014
			
			var myDay = myDate.getDay();
			//console.log(+daysToDisable+' '+myDay);
			
			//console.log('the Day '+$.inArray(myDay, daysToDisable));
			
			activeDate = $('#activeDate').val();
			aa_rr = activeDate.split('-');
			activeDate = aa_rr[2]+'-'+aa_rr[0]+'-'+aa_rr[1];
			//console.log(activeDate);
			if(isValidDate(activeDate)){
				calDay = new Date(activeDate).getDate();
			}
					
			//1 if greater, -1 if less and 0 if the same
			//console.log(curDay+' - '+calDay)
			//console.log(curTime+ ' - ' +time1 +' - '+ time2);
			//console.log(compareTimes(curTime,time1) +' == '+ compareTimes(curTime,time2)+' > '+curDay +'-' +calDay);
			
			var isMoveDate = 0;
			//console.log(Number(<?php //echo $thour;?>)+' time '+Number(<?php //echo $maxHr;?>));
			//console.log(Number(<?php //echo $ttempmins;?>) +' minutes '+ Number(<?php //echo $maxMn;?>));
			
			if(Number(<?php echo $thour;?>) == Number(<?php echo $maxHr;?>) && Number(<?php echo $ttempmins;?>) >= Number(<?php echo $maxMn;?>)){
					isMoveDate = 1;
			}
			
			if((compareTimes(curTime,time1) == 1 || compareTimes(curTime,time1) == 0) && (compareTimes(curTime,time2) == -1 || compareTimes(curTime,time2) == 0) && ( +curDay == +calDay) && Number(<?php echo $thour;?>)>0){
				
				$('#datepicker2').datetimepicker('hide');
				$('.steps-button, .fade').fadeIn();
				gotoModal();
				
				if(isMoveDate == 1){
					//console.log('will move '+$('#currenDate').val());
					 
					 karonDate = $('#currenDate').val();
					 karon = karonDate.split('-');
					 yy = karon[0];
					 mm = karon[1];
					 dd = Number(karon[2])+1;
					 if(dd<10){
						 dd = ('0'+dd).slice(-2);
					 }
					 
					 console.log('to date '+yy+'-'+mm+'-'+dd); 
					 
					 $("#datepicker2").datepicker( "option", "minDate", new Date(yy+'-'+mm+'-'+dd) );
					 $('#datepicker2').datepicker('setDate', new Date(yy+'-'+mm+'-'+dd));
					 $('#datepicker2').val('Bearbetar...');
					 
					 calendarDate = new Date(yy+'-'+mm+'-'+dd);
					 
					 setTimeout(function(){
						$('.ui-timepicker-div dl').html('<div class="beforetime">Välj datum</div>');
						$('.ui-datepicker-close ').hide();
						$('.steps-button-2 input').prop('disabled',true);
						
					},300);
				}
				
			}else{
				if(+curDay == +calDay && (compareTimes(curTime,time2)==1)){
					
					 //console.log('will move '+$('#currenDate').val());
					 
					 karonDate = $('#currenDate').val();
					 karon = karonDate.split('-');
					 yy = karon[0];
					 mm = karon[1];
					 dd = Number(karon[2])+1;
					 if(dd<10){
						 dd = ('0'+dd).slice(-2);
					 }
					 
					 console.log('to date '+yy+'-'+mm+'-'+dd); 
					 
					 $("#datepicker2").datepicker( "option", "minDate", new Date(yy+'-'+mm+'-'+dd) );
					 $('#datepicker2').datepicker('setDate', new Date(yy+'-'+mm+'-'+dd));
					 $('#datepicker2').val('Bearbetar...');
					 
					 calendarDate = new Date(yy+'-'+mm+'-'+dd);
					 
					 setTimeout(function(){
						$('.ui-timepicker-div dl').html('<div class="beforetime">Välj datum</div>');
						$('.ui-datepicker-close ').hide();
						$('.steps-button-2 input').prop('disabled',true);
						
					},300);
					
					 isMoveDay = 1;
				}else if(isMoveDate == 1){
					//console.log('will move '+$('#currenDate').val());
					 
					 karonDate = $('#currenDate').val();
					 karon = karonDate.split('-');
					 yy = karon[0];
					 mm = karon[1];
					 dd = Number(karon[2])+1;
					 if(dd<10){
						 dd = ('0'+dd).slice(-2);
					 }
					 
					 console.log('to date '+yy+'-'+mm+'-'+dd); 
					 
					 $("#datepicker2").datepicker( "option", "minDate", new Date(yy+'-'+mm+'-'+dd) );
					 $('#datepicker2').datepicker('setDate', new Date(yy+'-'+mm+'-'+dd));
					 $('#datepicker2').val('Bearbetar...');
					 
					 calendarDate = new Date(yy+'-'+mm+'-'+dd);
					 
					 setTimeout(function(){
						$('.ui-timepicker-div dl').html('<div class="beforetime">Välj datum</div>');
						$('.ui-datepicker-close ').hide();
						$('.steps-button-2 input').prop('disabled',true);
						
					},300);
				}
				$('#datepicker2').datetimepicker('show');
			}
			
			if(!isValidDate($('#datepicker2').val())){
				setTimeout(function(){
					$('.ui-timepicker-div dl').html('<div class="beforetime">Välj datum</div>');
					$('.ui-datepicker-close ').hide();
					$('.steps-button-2 input').prop('disabled',true);
					
					karonDate = $('#currenDate').val();
					 karon = karonDate.split('-');
					 yy = karon[0];
					 mm = karon[1];
					 dd = Number(karon[2]);
					 if(isMoveDay != 1){
						  $('#datepicker2').datepicker('setDate', new Date(yy+'-'+mm+'-'+dd));
					 }
					
					 custometimeslider(1);
				},300);
			}else{ 
				custometimeslider(0);
			}
			
			setTimeout(function(){
				datenow = $("#datepicker2").datepicker( 'getDate' );
				
				$('#datepicker2').val($.datepicker.formatDate('yy-mm-dd', datenow)+' '+$(".starttime1").val());	
				//alert('aaa');
			},500);
				
		});
		
		$('.steps-button h2 a').click(function(){
			$('.steps-button').fadeOut();
			
		});
		$('.current-date').click(function(){
			setTimeout(function(){
				currDate();
				// 2014-08-07 16:43
				$('.steps-button-2 input').prop('disabled',false);
			},1000);
			
			$('.steps-button').fadeOut();
		});
		$('.choose-date').click(function(){
			$('.steps-button').fadeOut();
	        $('#datepicker2').datetimepicker('show');
			
			if(!isValidDate($('#datepicker2').val())){
				setTimeout(function(){
					$('.ui-timepicker-div dl').html('<div class="beforetime">Välj datum</div>');
					$('.ui-datepicker-close ').hide();
					$('.steps-button-2 input').prop('disabled',true);
					
					karonDate = $('#currenDate').val();
					 karon = karonDate.split('-');
					 yy = karon[0];
					 mm = karon[1];
					 dd = Number(karon[2]);
					$('#datepicker2').datepicker('setDate', new Date(yy+'-'+mm+'-'+dd));
					
					 custometimeslider(1);
				},300);
			}else{
				custometimeslider(0);
			}
	    });
		
		$( "#datepicker2" ).focusin(function(){
			
			$('.ui-datepicker-current').click(function(){
				clickCurrentDate();
			});
			
			$('.ui-datepicker-div .av-time').remove();
			
			//$('.ui-datepicker-buttonpane').html('<a href="javascript:void" class="datepicker-close ui-state-default" data-handler="hide" data-event="click">Ok</a>');
			
			$('.datepicker-close').click(function(){
				
				//alert('adfddf')
				//onDatePickerClose();
				
				$( "#datepicker2" ).datepicker('hide');
				
			});
		});
		
		$('.dtlabel').click(function(){
			$('#datepicker2').datetimepicker('show');
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
				
				$('.forgot-box .displaymsg').fadeIn().html('<img src="<?php echo $site_url; ?>/wp-content/themes/outreach/images/rectangular.gif" style="margin:0 auto;">').removeClass('errormsg');
				
				$.ajax({
					url: "<?php echo $site_url; ?>/wp-content/themes/outreach/forgot-password.php",
					type: 'POST',
					data: 'email='+encodeURIComponent(email),
					success: function(value){
					 	if(value!='not'){
					   		$('.forgot-box .displaymsg').fadeIn().removeClass('errormsg').addClass('successmsg').html('Ditt lösenord har nu skickats - kontrollera din inkorg.');	
						}
						else{
							$('.forgot-box .displaymsg').removeClass('successmsg').fadeIn().addClass('errormsg').html('E inte erkänns.');
						}
					}
				 }); 
				
				
			}
			else{
				$('.forgot-box .displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html('Den angivna e-postadressen är inte registrerad.');		
			}
			
		}
		else{
			$('.forgot-box .displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html('E-postadress krävs.');	
		}
		
	 });
  });
  
  function onDatePickerClose(){
	  			 $('#datepicker2').datetimepicker('show');
				var thedate = $("#datepicker2").datetimepicker( 'getDate' );
				var d = new Date(thedate);
				
				var hour = d.getHours();
				if(hour<10){
					hour = '0'+hour;
				}
				
				var minute = d.getMinutes();
				if(minute<10){
					minute = '0'+minute;
				}
				
				var thedayy = d.getDay();
				
				var selecteddate = (d.getMonth()+1)+'/'+d.getDate()+'/'+d.getFullYear();
				var thetime = hour+':'+minute;
				
				$('#ui-datepicker-div #dperr').remove();
				$('#ui-datepicker-div').append('<div id="dperr" style="margin-bottom:5px;" class="clear errormsg"></div>');
				
				
				$('#ui-datepicker-div #dperr').hide();
			
				if($("#datepicker2").val()!=''){}
				else{
					
					$('#dperr').fadeIn().removeClass('successmsg').addClass('errormsg').html('Vänligen välj tid och datum för avhämtning.');
					
					//goScroll('step2err');
				}  
  }
  
  function compareTimes(timeOne, timeTwo) {           
		if(daterize(timeOne) > daterize(timeTwo)) return 1;
		if(daterize(timeOne) < daterize(timeTwo)) return -1;
		return 0;
	}
	
	function daterize(time) {
		return Date.parse("Thu, 01 Jan 1970 " + time + " GMT");
	}
  
  function clickCurrentDate(){
	 $('#datepicker2').datetimepicker('hide');
	 var val=$('#datepicker2').val();
	 $('#datepicker2').val('Tid för avhämtning mailas till dig');
	 $('#datepicker2').attr('data-rel',val);
	 $('#datepicker2').blur();
  }
  function currDate(){
  	var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
    var h = today.getHours();
    var m = today.getMinutes();
    if(mm<10){
    	mm = "0"+mm;
    }
    if(dd<10){
    	dd = "0"+dd;
    }
    var value = yyyy+"-"+mm+"-"+dd+" "+h+":"+m;
	
	
    $('#datepicker2').val('Tid för avhämtning mailas till dig');
    $('#datepicker2').attr('data-rel',value);
  }
  
  $(function(){
   //acction for invoice
		 /*--------------------------------*/
		 $('.paymenttype').change(function(){
			if($(this).val()=='invoice'){
				//console.log($(this).val());
				$('#business').val(jQuery.cookie("inv_business"));
				$('#orgnum').val(jQuery.cookie("inv_orgnum"));
				$('#address').val(jQuery.cookie("inv_address"));
				$('#zip').val(jQuery.cookie("inv_zip"));
				$('#location').val(jQuery.cookie("inv_location"));
					
				 $('.invoice-box').removeClass('errormsg').html();  
				 $('.invoice-box .displaymsg').hide()
				$('.fade, .invoice-box').fadeIn();
				gotoModal();
			} 
		});
		
		$( '.invoice-box' ).delegate( "#save-invoice", "click", function(e) {
			e.preventDefault();
			//console.log('whahh');
			var business = $('#business').val();
			var orgnum = $('#orgnum').val();
			var address = $('#address').val();
			var zip = $('#zip').val();
			var location = $('#location').val();
			var req = '';
			var erf = 0;
			if(business==''){
				req=req+"Företag fältet är obligatoriskt!<br />";
				erf ++;
			}
			if(orgnum==''){
				req=req+"Orgnr fältet är obligatoriskt!<br />";
				erf ++;
			}
			if(address==''){
				req=req+"Postadress fältet är obligatoriskt!<br />";
				erf ++;
			}
			if(zip==''){
				req=req+"Postnummer fältet är obligatoriskt!<br />";
				erf ++;
			}
			if(location==''){
				req=req+"Ort fältet är obligatoriskt!<br />";
				erf ++;
			}
			if(erf!=0){
				$('.invoice-box .displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html('Kontrollera följande:<br />'+req);	
			}else{
				var expiration_date = new Date();
				var expiration_minutes = 60;
				expiration_date.setTime(expiration_date.getTime() + (expiration_minutes * 60 * 1000));
				
				jQuery.cookie("inv_business", business, { path: '/', expires: expiration_date});
				jQuery.cookie("inv_orgnum", orgnum, { path: '/', expires: expiration_date });	
				jQuery.cookie("inv_address", address, { path: '/', expires: expiration_date});	
				jQuery.cookie("inv_zip", zip, { path: '/', expires: expiration_date});	
				jQuery.cookie("inv_location", location, { path: '/', expires: expiration_date});
				
				$('.invoice-box .displaymsg').fadeIn().removeClass('errormsg').addClass('successmsg').html('Information har sparats');
				setTimeout(function(){
					jQuery('.fade, .modalbox, .orderbox').fadeOut();	
				},500);
				
			}
		});
		
		$('#deliver').click(function(){
			if($(this).is(':checked')){
				//console.log($(this).val());
				$('#d_name').val(jQuery.cookie("d_name"));
				$('#d_mobile').val(jQuery.cookie("d_mobile"));
				$('#d_address').val(jQuery.cookie("d_address"));
				$('#d_buzz').val(jQuery.cookie("d_buzz"));
				$('#d_other').val(jQuery.cookie("d_other"));
					
				 $('.delivery-box').removeClass('errormsg').html();  
				 $('.delivery-box .displaymsg').hide()
				$('.fade, .delivery-box').fadeIn();
				gotoModal();
			} 
		});
				
		$( '.delivery-box' ).delegate( "#save-delivery", "click", function(e) {
			e.preventDefault();
			//console.log('whahh');
			var d_name = $('#d_name').val();
			var d_mobile = $('#d_mobile').val();
			var d_address = $('#d_address').val();
			var d_buzz = $('#d_buzz').val();
			var d_other = $('#d_other').val();
			var req = '';
			var erf = 0;
			if(d_name==''){
				req=req+"Namn fältet är obligatoriskt!<br />";
				erf ++;
			}
			if(d_mobile==''){
				req=req+"Mobil fältet är obligatoriskt!<br />";
				erf ++;
			}
			if(d_address==''){
				req=req+"Adress fältet är obligatoriskt!<br />";
				erf ++;
			}
			if(d_buzz==''){
				req=req+"Surr kod fältet är obligatoriskt!<br />";
				erf ++;
			}
			if(d_other==''){
				req=req+"Övrig information fältet är obligatoriskt!<br />";
				erf ++;
			}
			if(erf!=0){
				$('.delivery-box .displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html('Kontrollera följande:<br />'+req);	
			}else{
				var expiration_date = new Date();
				var expiration_minutes = 60;
				expiration_date.setTime(expiration_date.getTime() + (expiration_minutes * 60 * 1000));
				
				jQuery.cookie("d_name", d_name, { path: '/', expires: expiration_date});
				jQuery.cookie("d_mobile", d_mobile, { path: '/', expires: expiration_date });	
				jQuery.cookie("d_address", d_address, { path: '/', expires: expiration_date});	
				jQuery.cookie("d_buzz", d_buzz, { path: '/', expires: expiration_date});	
				jQuery.cookie("d_other", d_other, { path: '/', expires: expiration_date});
				
				$('.invoice-box .displaymsg').fadeIn().removeClass('errormsg').addClass('successmsg').html('Information har sparats');
				setTimeout(function(){
					jQuery('.fade, .modalbox, .orderbox').fadeOut();	
				},500);
				
			}
		});
				
		/*$('#save-invoice').bind('click',function(e){
			
			console.log('Clicked');
			
			business = $('#business').val();
			orgnum = $('#orgnum').val();
			address = $('#address').val();
			zip = $('#zip').val();
			location = $('#location').val();
		});*/
		$('.closebox.iv-box').click(function(e){
			e.preventDefault();
			e.stopPropagation();
			
			jQuery('.fade, .modalbox, .orderbox').fadeOut();
			
			/*if(jQuery(this).attr('data-rel')=='order'){
				setTimeout("window.location.reload();",1000);
			}*/
			$('.paymenttype').val('cash');
			
		});
		
		$('.closebox.delivery-box').click(function(e){
			e.preventDefault();
			e.stopPropagation();
			
			jQuery('.fade, .modalbox, .orderbox').fadeOut();
			
			/*if(jQuery(this).attr('data-rel')=='order'){
				setTimeout("window.location.reload();",1000);
			}*/
			$('.deliver').prop('checked',false);
			
		});
		
		//end for invoice
		/*--------------------------------*/
		
  });
  
  function custometimeslider(isClick){
	  var html ='';
	  html += '<div class="sttimeslider">';
	  html +='<div><label>Tid</label><input type="text" readonly="readonly" value="<?php echo $MinTime;?>" class="starttime1 ui_tpicker_time"></div>';
      html +='<div><label>Timme</label><span class="ui_slide stTime"></span></div>';
      html +='<div><label>Minut</label><span class="ui_slide stMin"></span></div>';
      html +='</div>';
	  $('.ui-timepicker-div dl').html(html);
	  
	var curDay = new Date(todaysDate).getDate();
	var dbminHr = 0;
	var dbminMn = 0;
	var dbmaxHr = 0;
	var dbmaxMn = 0;
	
	if(isClick == 1){
		
		datenow = $("#datepicker2").datepicker( 'getDate' );
		calendarDate = $.datepicker.formatDate('yy-mm-dd', datenow);
	
	}
	
	if(isValidDate(calendarDate)){
		var calDay = new Date(calendarDate).getDate();
		//alert(calendarDate);
		
		var d = new Date();
		var hour = d.getHours();
		var mins = d.getMinutes();
		var dayname = d.getDay();
		
		var isToday = 0;
		if( +curDay == +calDay){
			isToday = 1;
		}
		
		$.ajax({
			url: "<?php echo $site_url; ?>/wp-content/themes/outreach/getSettings.php",
			type: 'POST',
			async:false,
			data: 'table='+encodeURIComponent('<?php echo $tablename;?>')+'&date='+encodeURIComponent(calendarDate)+'&curDate='+encodeURIComponent('<?php echo $currentDates; ?>')+'&hour='+encodeURIComponent(hour)+'&mins='+encodeURIComponent(mins)+'&isToday='+encodeURIComponent(isToday)+'&dayname='+encodeURIComponent(dayname)+'&thour='+encodeURIComponent(<?php echo $thour; ?>),
			success: function(value){
				//console.log(value);
				
				if(value == ''){
					setTimeout(function(){
						$('.ui-timepicker-div dl').html('<div class="beforetime">Välj datum</div>');
						$('.ui-datepicker-close ').hide();
						$('.steps-button-2 input').prop('disabled',true);
						$('#datepicker2').val('Välj tid och datum');
					},400);
					setTimeout(function(){
						$('#datepicker2').val('Välj tid och datum');
					},1100);
					
				}else{
				
				//console.log(hour+':'+mins);
				
				$('.ui-datepicker-close ').show();
				
				var val = value.split('=');
				var dbsttime = val[0].split(':');
					dbminHr = dbsttime[0];
					dbminMn = dbsttime[1];
				var dbendtime = val[1].split(':');
					dbmaxHr = dbendtime[0];
					dbmaxMn = dbendtime[1];
				var advance = Number(val[2]);
				
				//console.log('min-max '+dbminHr+':'+dbminMn )
					
					if(dbminHr>dbmaxHr){
						dbmaxHr = Number(dbmaxHr)+24;
					}
					
					$('.ui-timepicker-div .av-time').remove();
					$('#ui-datepicker-div .ui-timepicker-div').prepend('<div style="padding:10px 0 0; font-size:12px;" class="av-time">Avhämtning erbjuds mellan:<br />'+val[0]+' - '+val[1]+'</div>');
					
					thour = 0;
					
					if(+curDay == +calDay){
						
						if(advance>=60){
							thour=hour+1;
						}else{
							var ttempmins = 0;
							ttempmins = mins+advance;
							if(Number(ttempmins) >=60){
								thour=hour+1;
							}
						}
						
						if((hour >= Number(dbminHr) && hour <= Number(dbmaxHr)) || thour == Number(dbminHr)  ){
							if(advance>=60){
								hour=hour+1;
							}else{
								var tempmins = 0;
								tempmins = mins+advance;
								//alert(tempmins);
								if(Number(tempmins) >=60){
									deductedmins = Number(tempmins)-60;
									mins=deductedmins;
									hour=hour+1;
								}else{
									mins = tempmins;	
								}
							}
							
						
						
							//alert(mins);
							if(hour >= dbminHr || hour <= dbminHr){
								
								if(mins>=0 && mins<=14){
									dbminMn = 15;
								}else if(mins>=15 && mins<=29){
									dbminMn = 30;
								}else if(mins>=30 && mins<=44){
									dbminMn = 45;
								}else if(mins>=45 && mins<=59){
									hour = hour+1;
									dbminMn = 0;
								}
							
								dbminHr = ('0'+(hour)).slice(-2);
								dbminMn = ('0'+Number(dbminMn)).slice(-2);
								//console.log(mins);
							}else{
								dbminHr = ('0'+Number(dbminHr)).slice(-2);
								dbminMn = ('0'+Number(dbminMn)).slice(-2);
							}
						}
					}
				//alert(dbminMn);
				
				$(".starttime1").val(dbminHr+':'+dbminMn);
				
				//alert(dbminHr+'<>'+dbmaxHr);
				//alert(dbminMn+'<>'+dbmaxMn);
				
				var shour = 0;
				$(".stTime").slider({
					min: dbminHr*60,
					max: dbmaxHr*60,
					step:60,
					values: dbminHr*60,
					slide: function(event, ui){
						
						var val = Number(ui.value);
						shour = val/60;
						sttimeval = val/60;
						hours = parseInt(val / 60 % 24, 10);
						
						var stField = $(".starttime1").val();
						st_arr = stField.split(':');
						
						
						
						if(hours <= 9){
							hours = ("0"+hours).slice(-2);
						}
						
						
						
						$(".stMin").slider("value", 0);
						$(".stMin").slider('option',{min: 0 ,max: dbmaxMn });
						
						$(".starttime1").val(hours+':'+("0"+st_arr[1]).slice(-2));
						
						dbmaxHr = parseInt(dbmaxHr % 24, 10);
						//alert('max: '+dbmaxHr);
						
						if(hours == dbminHr){
							if( Number(st_arr[1]) <= Number(dbminMn)){
								$(".stMin").slider("value", Number(dbminMn));
								$(".stMin").slider('option',{min: Number(dbminMn) ,max: 45 });
								
								$(".starttime1").val(hours+':'+("0"+dbminMn).slice(-2));
								
								setTimeout(function(){
									var picker = $('#datepicker2').val().split(' ');
									if(isValidDate(picker[0])){
										$('#datepicker2').val(picker[0]+' '+hours+':'+("0"+dbminMn).slice(-2));
									}
								},200);
							}else{
								$(".stMin").slider('option',{min: Number(dbminMn), max:45 });
								$(".stMin").slider("value", Number(st_arr[1]));	
							}
						}else if(hours == dbmaxHr){
							//alert(dbminMn+'<>'+dbmaxMn);
							if( Number(st_arr[1]) >= Number(dbmaxMn) ){
								$(".stMin").slider("value", Number(dbmaxMn) );
								$(".stMin").slider('option',{min: 0,max: Number(dbmaxMn)});
								if(dbmaxMn < 10){
									dbmaxMn = ("0"+dbmaxMn).slice(-2)
								}
								$(".starttime1").val(hours+':'+dbmaxMn);
								setTimeout(function(){
									var picker = $('#datepicker2').val().split(' ');
									if(isValidDate(picker[0])){
										$('#datepicker2').val(picker[0]+' '+hours+':'+dbmaxMn);
									}
								},200);
							}else{
								$(".stMin").slider('option',{min: 0, max: Number(dbmaxMn) });	
							}
						}else{
							$(".stMin").slider('option',{min: 0, max: 45});
							$(".stMin").slider("value", Number(st_arr[1])	);
						}
						
						setTimeout(function(){
							var picker = $('#datepicker2').val().split(' ');
							if(isValidDate(picker[0])){
								$('#datepicker2').val(picker[0]+' '+hours+':'+st_arr[1]);
							}
						},100);
						
					}
				}).sliderAccess({
					touchonly : false
				});
				
				
				$(".stMin").slider({
					min: Number(dbminMn),
					max: 45,
					step:15,
					slide: function(event, ui){
						var str = ui.value;
						
						var minutes = str;
						
						if(minutes <= 9){
							minutes = ("0"+minutes).slice(-2);
						}
						//console.log(minutes);
						
						var stField = $(".starttime1").val();
						var st_arr = stField.split(':');
						$(".starttime1").val(st_arr[0]+':'+minutes);
						
						if(Number(st_arr[0]) == Number(dbminHr)){
							if( str <= dbminMn){
								
								$(".stMin").slider("value", Number(dbminMn));
								$(".stMin").slider('option',{min: Number(dbminMn) ,max: 45 });
								
								$(".starttime1").val(hours+':'+("0"+dbminMn).slice(-2));
								
								setTimeout(function(){
									var picker = $('#datepicker2').val().split(' ');
									if(isValidDate(picker[0])){
										newDateVal = picker[0]+' '+st_arr[0]+':'+("0"+dbminMn).slice(-2);
										$('#datepicker2').val(newDateVal);
									}
								},200);
								
							}else{
								$(".stMin").slider('option',{min: 0, max:45 });	
							}
						}else if(Number(st_arr[0]) == Number(dbmaxHr)){
							//alert(minutes +' > '+ dbmaxMn )
							if( str > dbmaxMn ){
								$(".stMin").slider("value", Number(dbmaxMn) );
								$(".stMin").slider('option',{min: 0, max: Number(dbmaxMn)});
								if(dbmaxMn < 10){
									dbmaxMn = ("0"+dbmaxMn).slice(-2);
								}
								$(".starttime1").val(hours+':'+dbmaxMn);
								
								setTimeout(function(){
									var picker = $('#datepicker2').val().split(' ');
									if(isValidDate(picker[0])){
										newDateVal = picker[0]+' '+st_arr[0]+':'+dbmaxMn;
										$('#datepicker2').val(newDateVal);
									}
								},200);
							}else{
								$(".stMin").slider('option',{min: 0, max:45 });	
							}
						}else{
							$(".stMin").slider('option',{min: 0, max: 45});
						}
						
						//console.log('process');
						setTimeout(function(){
							var picker = $('#datepicker2').val().split(' ');
							if(isValidDate(picker[0])){
								newDateVal = picker[0]+' '+st_arr[0]+':'+minutes;
								$('#datepicker2').val(newDateVal);
							}
						},100);
					}
				}).sliderAccess({
					touchonly : false
							});  
				}
			}
		});
	}
	if(isClick == 1){
		setTimeout(function(){
			var curVal = $('#datepicker2').val();
			cur_arr = curVal.split(' ');
			
			$('#datepicker2').val(cur_arr[0]+' '+$(".starttime1").val());	
			//alert('aaa');
			
		},1000);
	}
  }
  
  function isValidDate(value) {
    var dateWrapper = new Date(value);
    return !isNaN(dateWrapper.getDate());
}
</script>
<style>
	
	a.ui-state-default.ui-state-active {
		color: green !important;
		border-color: green !important;
	}

	.beforetime{
		padding:20px;
		text-align:center;	
	}
	.sttimeslider a.ui-slider-handle.ui-state-default.ui-corner-all{
		display:none;	
	}
	.sttimeslider span.ui_slide.ui-slider.ui-slider-horizontal.ui-widget.ui-widget-content.ui-corner-all {
		display: none !important;
	}
	.sttimeslider{
		padding:10px;	
	}
	.sttimeslider label{
		min-width:85px;
		display:inline-block;
	}
	.sttimeslider > div{
		margin-bottom:10px;	
	}
	.sttimeslider span{
		display:inline-block;
	}
	.sttimeslider input{
		background:transparent;
		text-align:center;
		border:0;
		width:60px;
		display:inline-block;
		box-shadow:none;
	}
	.ui-datepicker-unselectable span.ui-state-default {
		background:#f6afad !important;
		border-color:#f6afad !important;
	}
	.steps-content ul li{
		margin-bottom:25px;
		text-align:left;
	}
	.steps-content ul li .label{
		width:5%;
		padding-top:0px;
		min-width:10px;
		display:inline-block;
		vertical-align:top;
		white-space:nowrap;
	}
	.steps-content ul li .st-col{
		color:#000;
		width:93%;
		display:inline-block;
		vertical-align:middle;
	}
	.steps-content ul li .st-col .t-input{
		width:100% !important;	
	}
	.steps-content ul li .takeemail{
		margin-bottom: 4px !important;
	}
	.steps-content ul li .takeemail,
	.steps-content ul li .takepass{
		width:100% !important;
	}
	.modalbox table{
		width:100%;
		margin-bottom:0;
	}
	#save-invoice{
		font-family: 'Roboto-Regular';
	}
	.modalbox table tr td{
		text-align:left;
		font-family: 'Roboto-Regular';	
	}
	.modalbox table tr td input[type='text']{
		width:100%;
		background: #f3f1e9;
		padding:10px;
	}
	.modalbox .box-content{
		padding:20px;	
	}
	.modalbox h2{
		text-align:left;
		font-family: 'Roboto-Medium';
		padding-left: 20px;
	}
	.modalbox .displaymsg{
		text-align:left;
	}
	div#dperr {
		padding: 9px 10px !important;
	}
	ul.radiousers {
		max-width: 400px;
		width: 100%;
	}
	.modalbox.success-box .Ok{
		bottom:20px;
		margin-left: 16%;
		font-size: 14px !important;
		font-weight: normal; 
	}
	.modalbox.success-box .box-content {
		padding-bottom: 72px;
	}
	
	#datepicker2:read-only{
	}
	.ui-datepicker-trigger{display:none};
</style>
<div class="steps-container">
	<h3 class="thestep-label">
    	<?php
        	if($tablename=='lunchmeny_settings'){
				echo 'Lunch Meny';
			}
			else if($tablename=='breakfast_settings'){
				echo 'Frukost';
			}
			else{
				echo 'Take away';
			}
		?>
    <a href="javascript:void(0)" class="close-steps" data-id="<?php echo $tablename; ?>">Close</a>
    </h3>
    <div class="steps-box step-2">
    <?php
    
	if($noSettings>0){ ?>
        <div class="steps-content">
        
            <ul style="margin: 7px auto 0;" class="radiousers">
                <li>
                	<b class="label" style="padding-top:10px;">1.</b>
         				<div class="st-col"><input type="button" class="t-input" id="datepicker2" value="Välj tid och datum" /></div>
                </li>
                
                <li>
                	<b class="label">2.</b>
                    <div class="st-col">
                    	Hur vill du betala?<br />
                    	<select class="paymenttype">
                            <option value="cash">
                                Kort/Kontant vid avhämtning
                             </option>
                             <option value="invoice">
                                Faktura (Endast företag)
                             </option>
                             <option value="pay	online with seqr">
                             	Betala online med SEQR
                             </option>
                        </select>
                    </div>
                </li>
                <li style="display:none;">
                    <b class="label">3.</b>
                    <div class="st-col">
                    	<input type="checkbox" class="deliver" id="deliver"><label for="deliver" class="">Önskar Utkörning</label>
                    </div>
                </li>
                <li>
                    <b class="label">3.</b>
                    <div class="st-col">
                        Uppge dina användaruppgifter eller<br />
                        <a href="javascript:void(0)" style="font-size: 13px; float:none;" class="ifnew">Skapa ett konto</a><br />
                    
                        <input type="text" class="takeemail t-input" placeholder="*E-post"><br />
                        <input type="password" class="takepass t-input" placeholder="*Lösenord"><br />
                        <a href="javascript:void(0)" class="forgot"  style="font-size: 13px;">Klicka här om du har glömt ditt lösenord</a><br />
                        <input type="checkbox" id="remember"  class="tos remember" checked="checked"><label for="remember" class="" style="font-size: 12px;">Kom ihåg mig</label><br />
                    </div>
                </li>
                <li class="exists">
                    	<b class="label">4.</b>
                        <div class="st-col">
                        	<input type="checkbox" id="agree11" class="tos"><label for="agree11" class="agreelabel1">Jag har läst och godkänner användarvillkoren</label> 
                        </div>
                </li>
                <li style="padding-left:27px;">
                	<div class="displaymsg" id="step2err"></div>
                </li>
            </ul>
        </div>
        <span class="clear" style="display:block;"></span>
        <div class="steps-button-2" style="padding:0;">
        	<input type="button" value="Tillbaka" data-rel="step-1" class="prevbtn1 prevs btn" data-id="<?php echo $tablename; ?>">
            <input type="button" class="exists boka btn" value="BESTÄLL">
            <input type="button" value="Registrerings" class="registering" style="display:none;">
        </div>
               	<span class="totaldue" style="margin-top:20px;">
            	<label style="display:block;"></label>
                <cite style="font-size: 18px">Att betala: <strong><?php echo $totalnumber; ?> kr </strong></cite>
            </span>
        
     <?php }
	 	else{
	 ?>
        	<p>Tyvärr går det inte att beställa take away via hemsidan för tillfället. Vänligen ring eller maila oss för mer information.</p>
            <a href="javascript:void(0)" class="btn noactive" data-id="<?php echo $tablename; ?>">Tillbaka</a>
            <div class="clear"></div>
     <?php	
		}
	 ?>
    </div>
    <a href="javascript:void(0)" class="goto goto-bottom"></a>
	<a href="javascript:void(0)" class="goto goto-top"></a>
    <!-- end .step-2 -->
    
</div>
<div class="steps-box step-1">
    <div class="clear"></div>
    <div class="steps-button modalbox" style="display:none;">
        <h2>När vill du ha din beställning klar? <a href="javascript:void(0)"></a></h2>
        <div class="box-cont">
        <input type="button" value="SNARAST" data-rel="step-2"  class="current-date">
        <input type="button" value="ANNAN TID" data-rel="step-2"  class="choose-date">
        </div>
        
    </div>
    <div class="displaymsg"></div>
</div>
<div class="invoice-box modalbox" style="display:none;">
	<h2>Faktureringsinformation<a href="javascript:void(0)" class="closebox iv-box"></a></h2>
    <div class="box-content">
        <table class="">
            <tr>
            	<td>Företag:</td>
                <td><input type="text" id="business"></td>
            </tr>
            <tr>
            	<td>Orgnr:</td>
                <td><input type="text" id="orgnum"></td>
            </tr>
            <tr>
            	<td>Adress:</td>
                <td><input type="text" id="address"></td>
            </tr>
            <tr>
            	<td>Postnr/Ort:</td>
                <td><input type="text" id="zip"></td>
            </tr>
            <tr>
            	<td>Ref/Idnummer:</td>
                <td><input type="text" id="location"></td>
            </tr>
            <tr>
            	<td></td>
                <td style="text-align:right"><!-- Skapa konto --><input type="button" value="Skicka" id="save-invoice"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
    </div>
</div>
<div class="delivery-box modalbox" style="display:none;">
	<h2>Kontaktuppgifter<a href="javascript:void(0)" class="closebox delivery-box"></a></h2>
    <div class="box-content">
        <table class="">
            <tr>
            	<td>Mottagarens namn:</td>
                <td><input type="text" id="d_name"></td>
            </tr>
            <tr>
            	<td>Mottagarens mobiltelefon:</td>
                <td><input type="text" id="d_mobile"></td>
            </tr>
            <tr>
            	<td>Leveransadress:</td>
                <td><input type="text" id="d_address"></td>
            </tr>
            <tr>
            	<td>Portkod:</td>
                <td><input type="text" id="d_buzz"></td>
            </tr>
            <tr>
            	<td>Övrig information:</td>
                <td><input type="text" id="d_other"></td>
            </tr>
            <tr>
            	<td></td>
                <td style="text-align:right"><!-- Skapa konto --><input type="button" value="Skicka" id="save-delivery"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
    </div>
</div>


<!--GO TO CART SCROLL---------------------------------------->	
<script type="text/javascript">
$(function(){	

		$('.signup-box h2 a').click(function(){
			$('.signup-box').fadeOut();
		});


		$(".step-2").css('overflow' ,'auto');
		var cart_scroller = $(".step-2");
		
		
		$('.goto-bottom').click(function(){
			cart_scroller.animate({ scrollTop: cart_scroller[0].scrollHeight }, "fast");
		});
		$('.goto-top').click(function(){
			cart_scroller.animate({ scrollTop: 0 }, "fast");
		});
		
		//var h =  parseInt(cart_scroller.height().toString()) + 59;
		
		var h =  Number(cart_scroller.height()) + 59;
		
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
		
		//signup scrolling
		var signup_scroller = $(".signup-box .box-content");
		
		
		$('.s-goto-bottom').click(function(){
			signup_scroller.animate({ scrollTop: signup_scroller[0].scrollHeight }, "fast");
		});
		$('.s-goto-top').click(function(){
			signup_scroller.animate({ scrollTop: 0 }, "fast");
		});
		
		//var h =  parseInt(signup_scroller.height().toString()) + 59;
		
		var h =  Number(signup_scroller.height()) + 59;
		
		signup_scroller.scroll(function() {
	
		  if (signup_scroller[0].scrollHeight - signup_scroller.scrollTop() == signup_scroller.outerHeight()) {
			 $('.s-goto-bottom').fadeOut('fast');
			 $('.s-goto-top').show('fast');
		  }else{
			 $('.s-goto-bottom').fadeIn('fast');
			 $('.s-goto-top').hide('fast');
		  }
			
		});	
		
		console.log(signup_scroller.prop('scrollHeight')+' '+h);
		
		if(signup_scroller.prop('scrollHeight') > h){
			$('.s-goto').show('fast');
		}else{
			$('.s-goto').hide('fast');
		}
		
		if(signup_scroller.prop('scrollHeight') == h+1){
			$('.s-goto').hide('fast');
		}
		if($(window).width()<=400){
			$('td.tos2').removeAttr('style');
		}
});

$(window).resize(function(){
		if($(window).width()<=400){
			$('td.tos2').removeAttr('style');
		}
});
</script>
<!--END GO TO CART SCROLL---------------------------------------->

<style>
.goto{
		right: 45px;
		bottom: 30px;
	}
</style>