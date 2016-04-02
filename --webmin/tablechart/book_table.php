<?php
require('../config/config.php');

$parameters = explode('|',$_POST['parameters']);
$date_time = explode(' ',$parameters[0]);

$date = strip_tags(mysql_real_escape_string($_POST['date']));

if(strtotime($parameters[0])<=strtotime('NOW')){
	die('You can no longer make a reservation from <b>'.date('H:i',strtotime($date_time[1])).'</b> and earlier.');
}

$customer_list='';
$thecustomers='';
$query = mysql_query("SELECT id, CONCAT(fname, ' ', lname) AS name, phone_number
						 FROM account 
						 WHERE deleted=0 AND type_id=5 ORDER BY CONCAT(fname, ' ', lname) ASC");	
while($row = mysql_fetch_assoc($query)){
	$customer_list.='{"id": "'.$row['id'].'", "name": "'.$row['name'].' - '.$row['phone_number'].'"},';
	$thecustomers.=$row['name'].' - '.$row['phone_number']."***";
}

$customer_list=substr($customer_list,0,strlen($customer_list)-1);

//$customer_list='{ "tag": "HTML5", "name": "HTML5 LocalStorage API", "description": "HTML5 LocalStorage API,Client Side Storage" }';

?>

<!--<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">-->

<style>
	tr:hover td{ background:none !important; }
	td{ padding:2px; }
	.new_customer{ display:none; }
	ul{ 
		z-index:9999999 !important; 
		display: block /*!important*/;	
	}
</style>
<div style="width:450px; overflow:auto">

<script>
jQuery(function() {
	

jQuery( "#time_end" ).change(function() {
  // Check input( $( this ).val() ) for validity here
	var start_time = jQuery( "#time_start" ).val();
	var end_time = jQuery( this ).val();
	var table_id = jQuery( "#table_id" ).val();
	var date_selected = jQuery( "#date_selected" ).val();
	var res_id =  jQuery( "#res_det_id" ).val();
	
	jQuery.ajax({
			 url: "tablechart/timeDiffMins_book_table.php",
			 type: 'POST',
			 data: 'start='+start_time+'&end='+end_time+'&table_id='+table_id+'&date_selected='+date_selected+'&res_id='+res_id,
			 success: function(value){
				jQuery('#duration').html(value);
			 }
	});		
	
});

jQuery("#client_search").autocomplete({
    source: function (request, response) {
        jQuery.getJSON("client_search.php", {
            term: request.term
        }, response);
    },
    minLength: 2,
    select: function(event, ui) {
        console.log(ui.item ? "Selected: " + ui.item.value + " aka " + ui.item.id : "Nothing selected, input was " + this.value);
    }
});


jQuery( "#save_booking" ).click(function() {
 
//  var new_cust = jQuery('#existing').val(); //1 == existing; 0 == new
 	var customer_id = jQuery('#customer_id').val();	
 
 	if(jQuery('#time_error').val()==0){
 
	  if(customer_id>0){
					
				var res_det_id = jQuery('#res_det_id').val();
				var date_selected = jQuery('#date_selected').val();	
				var time_start = jQuery('#time_start').val();	
				var time_end= jQuery('#time_end').val();	
				var pax = jQuery('#pax').val();
				var extra_chairs = jQuery('#extra_chairs').val();
				var table_id = jQuery('#table_id').val();	
				var notes = jQuery('#notes').val();

				if(extra_chairs>0){
					alert('Warning: You have set '+extra_chairs+' extra chair(s).');
				}
		
				jQuery.ajax({
						 url: "tablechart/saveBooking.php",
						 type: 'POST',
						 async: true,
						 data: 'date_selected='+date_selected+'&time_start='+time_start+'&time_end='+time_end+'&table_id='+table_id+'&pax='+pax+'&notes='+notes+'&res_det_id='+res_det_id+'&customer_id='+customer_id+'&extra_chairs='+extra_chairs,
						 success: function(value){
	
							if(jQuery.isNumeric(value)){	
								modal.open({content: '<font color="green">SUCCESS!</font> Reservation <b>No. '+value+'</b> has been created.'});
								setTimeout(function(){
									 jQuery('#modal, #overlay').fadeOut();
								}, 3000);
									jQuery.ajax({
										// alert ('<?php echo $date;?>');
	
										// call calendar-data.php to refresh content on page
										// should include the reservation added after load
	
											url: "tablechart/calendar-data.php",
											type: 'GET',
											data: 'date='+encodeURIComponent('<?php echo $date;?>'),
											success: function(value){
												jQuery('.fadediv, .loaddiv').fadeOut();
												jQuery('#calendar-wrapper').html(value);
												// alert(value);
											}
										});
							
							}else{
								alert(value);
							}
						 }
				});		
			
		}else{
		  
			var res_det_id = jQuery('#res_det_id').val();
			var date_selected = jQuery('#date_selected').val();	
			var time_start = jQuery('#time_start').val();	
			var time_end= jQuery('#time_end').val();	
			var pax = jQuery('#pax').val();
			var extra_chairs = jQuery('#extra_chairs').val();
			var table_id = jQuery('#table_id').val();	
			var notes = jQuery('#notes').val();
			var first_name = jQuery('#first_name').val();	
			var last_name = jQuery('#last_name').val();	
			var phone_number = jQuery('#phone_number').val();	
			var email_address = jQuery('#email_address').val();
			var street = jQuery('#street').val();
			var city = jQuery('#city').val();
			var zip = jQuery('#zip').val();
			var country = jQuery('#country').val();
							
			if(first_name!='' && last_name!='' && phone_number!=''){

		
				if(extra_chairs>0){
					alert('Warning: You have set '+extra_chairs+' extra chair(s).');
				}
				
				jQuery.ajax({
						 url: "tablechart/saveOtherBooking.php",
						 type: 'POST',
						 async: true,
						 data: 'date_selected='+date_selected+'&time_start='+time_start+'&time_end='+time_end+'&table_id='+table_id+'&pax='+pax+'&notes='+notes+'&res_det_id='+res_det_id+'&first_name='+first_name+'&last_name='+last_name+'&phone_number='+phone_number+'&email_address='+email_address+'&street='+street+'&city='+city+'&zip='+zip+'&country='+country+'&extra_chairs='+extra_chairs,
						 success: function(value){
	
	
							if(jQuery.isNumeric(value)){	
								modal.open({content: '<font color="green">SUCCESS!</font> Reservation <b>No. '+value+'</b> has been created.'});
								setTimeout(function(){
									jQuery('#modal, #overlay').fadeOut();
								}, 3000);
									jQuery.ajax({
	
										// call calendar-data.php to refresh content on page
										// should include the reservation added after load
	
											url: "tablechart/calendar-data.php",
											type: 'GET',
											data: 'date='+encodeURIComponent('<?php echo $date;?>'),
											success: function(value){
												jQuery('.fadediv, .loaddiv').fadeOut();
												jQuery('#calendar-wrapper').html(value);
												// alert(value);
											}
										});
							
							}else{
								alert(value);
							}
						 }
				});			
				
				
				//div #modal #content
			}else{
				alert('ERROR: Please fill-in the First & Last names and the Phone Number.');
			}
	  }
  
	}else{
		alert('ERROR: Please double-check the time set.');
	}
  
});

jQuery( "#save_walkin" ).click(function() {
	
 	if(jQuery('#time_error').val()==0){

		//if(confirm('Do you really want to book this as WALK-IN?')){	 
	
		var res_det_id = jQuery('#res_det_id').val();
		var date_selected = jQuery('#date_selected').val();	
		var time_start = jQuery('#time_start').val();	
		var time_end= jQuery('#time_end').val();	
		var pax = jQuery('#pax').val();
		var extra_chairs = parseInt(jQuery('#extra_chairs').val());
		var table_id = jQuery('#table_id').val();	
		var notes = jQuery('#notes').val();

		if(extra_chairs>0){
			alert('Warning: You have set '+extra_chairs+' extra chair(s).');
		}
		
				jQuery.ajax({
						 url: "tablechart/saveBooking.php",
						 type: 'POST',
						 async: true,
						 data: 'date_selected='+date_selected+'&time_start='+time_start+'&time_end='+time_end+'&table_id='+table_id+'&pax='+pax+'&notes='+notes+'&res_det_id='+res_det_id+'&customer_id=0&extra_chairs='+extra_chairs,
						 success: function(value){
	
							if(jQuery.isNumeric(value)){	
								modal.open({content: '<font color="green">SUCCESS!</font> Reservation <b>No. '+value+'</b> has been created.'});
								setTimeout(function(){
									 jQuery('#modal, #overlay').fadeOut();
								}, 3000);
									jQuery.ajax({
										// alert ('<?php echo $date;?>');
	
										// call calendar-data.php to refresh content on page
										// should include the reservation added after load
	
											url: "tablechart/calendar-data.php",
											type: 'GET',
											data: 'date='+encodeURIComponent('<?php echo $date;?>'),
											success: function(value){
												jQuery('.fadediv, .loaddiv').fadeOut();
												jQuery('#calendar-wrapper').html(value);
												// alert(value);
											}
										});
							
							}else{
								alert(value);
							}
						 }
				});		

		//}//if confirmed

	}else{
		alert('ERROR: Please double-check the time set.');
	}
	

});

jQuery('#time_end').timepicker({
		showPeriodLabels: false,
  });  
 

//jQuery('#time_end').val(getCurrentDate()); 


jQuery('#customer_search').typeahead({
     name: 'customers',
	 valueKey: 'name',
     local: [<?php echo $customer_list; ?>]		
	}).on('typeahead:selected', function($e, datum){
        jQuery('#customer_id').val(datum["id"]);
	}).on('typeahead:closed', function($e, datum){
		
		var thecustomers=new Array();
		var contact = jQuery('#customer_search').val();
		var val=jQuery('#customer_search').val();
        var thelist=jQuery('#thecustomers').val().split("***");
		
		for(i=0;i<thelist.length;i++){
			thecustomers.push(thelist[i]);
		}
		
		if(thecustomers.indexOf(val)!=-1){
			jQuery('.new_customer').fadeOut();
		}
		else{
			if(jQuery.isNumeric(contact)){
				// alert('here again');
				jQuery('#phone_number').val(contact);
			}
			// alert('here');
			jQuery('.new_customer').fadeIn();
			jQuery('#customer_id').val("");
		}
		
	});

});


  
  function getCurrentDate(){
	var now     = new Date(); 
    var hour    = now.getHours();
    var minute  = Math.round(Number(now.getMinutes())/ 5) * 5;
	
	if(minute > 59){
		minute=0;
	}
	
	if(minute==5 || minute==0){
		minute="0"+minute;
	}
	
 	return hour+":"+ minute;
}
</script>

<?php
//get restaurant details e.g. start/end time with regards to current date
$res_det_sql = "SELECT id, start_time, end_time, time_interval, dine_interval, between_interval FROM restaurant_detail 
				WHERE id=".$parameters[2]."
				AND deleted=0";

$res_det_qry = mysql_query($res_det_sql);
$res_det = mysql_fetch_assoc($res_det_qry);

?>

<h3>Book A Table
  
  <input type="hidden" name="res_det_id" id="res_det_id" value="<?php echo $parameters[2]; ?>" />
</h3>

<table width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td align="right">Date&nbsp; </td>
    <td align="center">:&nbsp;</td>
    <td>
    	<strong><?php echo date('D, d M Y',strtotime($date_time[0])); ?></strong>
    	<input type="hidden" name="date_selected" id="date_selected" value="<?php echo $date_time[0]; ?>" />
    </td>
    <td>&nbsp;</td>
    <td align="right">From </td>
    <td align="center">:&nbsp;</td>
    <td>
<?php
$min_start_time = $date_time[1];
$ideal_end_time = date('H:i:00', strtotime($min_start_time.' +'.$res_det['dine_interval'].' mins'));
?>

    <strong><?php echo date('H:i',strtotime($date_time[1])); ?>
    <input type="hidden" name="time_start" id="time_start" value="<?php echo date('H:i',strtotime($date_time[1])); ?>:00" />
    </strong></td>
  </tr>
	<tr>
    	<td align="right">Table&nbsp; </td>
   	  <td align="center">:&nbsp;</td>
        <td><strong>
          <?php 
	  $table_det_sql = "SELECT table_name, max_pax FROM table_detail WHERE id=".$parameters['1'];
	  $table_det_qry = mysql_query($table_det_sql);
	  $table_det = mysql_fetch_assoc($table_det_qry);
	  echo $table_det['table_name']
	  ?>
      <input type="hidden" name="table_id" id="table_id" value="<?php echo $parameters['1']; ?>" /> 
        </strong></td>
        <td>&nbsp;</td>
        <td align="right" valign="middle">To </td>
        <td align="center">:&nbsp;</td>
        <td valign="middle">
        <input type="text" id="time_end" style="width:75px !important;" value="<?php echo substr($ideal_end_time,0,5); ?>">
        <!--<select name="time_end" id="time_end" style="width:80px;">
		<?php 
		//get restaurant details e.g. start/end time with regards to current date
		/*$res_det_sql = "SELECT id, start_time, end_time, time_interval FROM restaurant_detail 
						WHERE '".date('Y-m-d',strtotime($date_time[0]))."' BETWEEN STR_TO_DATE(start_date,'%m/%d/%Y') AND STR_TO_DATE(end_date,'%m/%d/%Y') 
						AND deleted=0 
						ORDER BY id DESC LIMIT 1";
		
		$res_det_qry = mysql_query($res_det_sql);
		$res_det_num = mysql_num_rows($res_det_qry);
		$res_det = mysql_fetch_assoc($res_det_qry);
		
		if($res_det_num ==1){	
			
			$time_increments = $garcon_settings['time_interval'];
			$time = strtotime($date_time[1]);
			$end = strtotime($res_det['end_time']);

			$time = strtotime(date('H:i:s',$time).' + '.$time_increments.' mins');	
		
			while($time<=$end){
				if($ideal_end_time==date('H:i:s',$time)){ $sel='selected="selected"'; }else{ $sel=''; }
			?>	
            	<option value="<?php echo date('H:i:s',$time); ?>" <?php echo $sel; ?>><?php echo date('H:i',$time); ?></option>
			<?php	
				//check if table has reservation for the specified time
				$table_reserve_sql = "SELECT rt.id
									  FROM reservation_table rt, reservation r 
									  WHERE rt.table_detail_id=".$parameters[1]." AND rt.reservation_id=r.id AND 
									  r.date='".date('m/d/Y',strtotime($date_time[0]))."' AND r.time='".date('H:i:00',$time)."' AND r.approve=2";
				$table_reserve_qry = mysql_query($table_reserve_sql);
				$table_reserve_num = mysql_num_rows($table_reserve_qry);
			
				if($table_reserve_num==0){			
					$time = strtotime(date('H:i:s',$time).' + '.$time_increments.' mins');	
				}else{
					break;	
				}
			}*/
		?>
        </select>-->
        <?php
		//}//only if the store is open
		?>
        </td>
    </tr>
	<tr>
	  <td align="right">Seats&nbsp; </td>
	  <td align="center">:&nbsp;</td>
	  <td>
      <?php
	  $pax = range(1,$table_det['max_pax']);
	  ?>
      <select name="pax" id="pax" style="width:60px; margin-top:4px;">
      	<?php
        foreach($pax as $p => $val){
			echo '<option value="'.$val.'">'.$val.'</option>';
		}
		?>
      </select>
      </td>
	  <td>&nbsp;</td>
	  <td align="right" valign="top">Duration</td>
	  <td align="center" valign="top">:</td>
	  <td valign="top">
		<div id="duration">
		<?php
		$hrs = gmdate("H", ($res_det['dine_interval'] * 60));
		$mins = gmdate("i", ($res_det['dine_interval'] * 60));
		//double-check if table is available for the selected time until the set dine_interval less and plus between_interval
		$table_check_sql = "SELECT r.id FROM reservation r, reservation_table rt 
							WHERE r.id=rt.reservation_id AND r.status<3 AND r.deleted=0 AND rt.deleted=0 AND rt.table_detail_id=".$parameters['1']." AND
							STR_TO_DATE(r.date,'%m/%d/%Y')='".$date_time[0]."' 
							AND 
							(
								r.time BETWEEN '".date('H:i',strtotime($date_time[1].'-'.$res_det['between_interval'].' minutes')).":00' 
											AND '".date('H:i',strtotime($date_time[1].'+'.($res_det['dine_interval']+$res_det['between_interval']-1).' minutes')).":00' 	
											
								OR
								
								ADDTIME(TIME(r.time), SEC_TO_TIME(duration*60)) BETWEEN '".date('H:i',strtotime($date_time[1].'-'.($res_det['between_interval']-1).' minutes')).":00' 
											AND '".date('H:i',strtotime($date_time[1].'+'.($res_det['dine_interval']+$res_det['between_interval']-1).' minutes')).":00' 
							)";
		$table_check_num = mysql_num_rows(mysql_query($table_check_sql));
		if($table_check_num<=0)
			echo 'Hrs: <strong>'.$hrs.'</strong> &nbsp;Mins: <strong>'.$mins.'</strong><input id="time_error" value="0" type="hidden" />';		
		else
			echo '<font color="red">Selected table is not available<br>up to the end time set.</font><input id="time_error" value="1" type="hidden" />';
		?>
        </div>      
      </td>
    </tr>
  <tr>
  	<td align="right"><div style="text-align:right;">Extra Chairs</div></td>
    <td>:</td>
    <td colspan="5"><input type="text" id="extra_chairs" style="width:35px !important;" /></td>
  </tr>
  <tr>
    <td align="right" valign="top">Notes/Remarks</td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <textarea name="notes" id="notes" style="width:300px; height:50px;"></textarea>
    <input type="button" id="save_walkin" value="Save As Wak-In" style="padding:4px;" />
    </td>
  </tr>
  <tr>
    <td align="right" valign="top">Existing Customer </td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    	<input type="text" id="customer_search" style="width:250px !important;">
        <input type="button" value="Search" class="searchbtn">
    </td>
  </tr>
  <!--<tr>
    <td align="right" valign="top">&nbsp; </td>
    <td align="right" valign="top">&nbsp;</td>
    <td colspan="5">
    	<select name="existing" id="existing">
        	<option value="1">Existing Customer</option>
        	<option value="0">New Customer</option>
        </select>
    </td>
  </tr>-->
  <!--<tr id="search">
    <td align="right" valign="top">Customer&nbsp; </td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <select id="client" name="client" style="width:300px;">
    	<option> - select customer - </option>
	<?php
    /*$query = mysql_query("SELECT id, CONCAT(fname, ' ', lname) AS name 
						 FROM account 
						 WHERE deleted=0 AND type_id=5 ORDER BY CONCAT(fname, ' ', lname) ASC");	
	while($row = mysql_fetch_assoc($query)){
		?>
        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
        <?php
	}*/
	?>
    </select>
    </td>
  </tr>-->
  <tr class="new_customer">
    <td align="center" valign="top" colspan="7">Record not found. Create new customer. Fill up below.</td>
  </tr>  
  <tr class="new_customer">
    <td align="right" valign="top">*First Name&nbsp; </td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <input type="text" id="first_name" name="first_name" style="width:300px;" /></td>
  </tr>  
  <tr class="new_customer">
    <td align="right" valign="top">*Last Name&nbsp; </td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <input type="text" id="last_name" name="last_name" style="width:300px;" /></td>
  </tr>  
  <tr class="new_customer">
    <td align="right" valign="top">*Phone Number&nbsp; </td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <input type="text" id="phone_number" name="phone_number" style="width:300px;" /></td>
  </tr>  
  <tr class="new_customer">
    <td align="right" valign="top">Email Address&nbsp; </td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <input type="text" id="email_address" name="email_address" style="width:300px;" /></td>
  </tr>  
  <tr class="new_customer">
    <td align="left" valign="top">Post Address&nbsp; </td>
    <td align="right" valign="top">&nbsp;</td>
    <td colspan="5">&nbsp;</td>
  </tr>  
  <tr class="new_customer">
    <td align="right" valign="top">Street &nbsp; </td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <input type="text" id="street" name="street" style="width:300px;" /></td>
  </tr>  
  <tr class="new_customer">
    <td align="right" valign="top">City &nbsp;</td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <input type="text" id="city" name="city" style="width:300px;" /></td>
  </tr>  
  <tr class="new_customer">
    <td align="right" valign="top">ZIP &nbsp;</td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <input type="text" id="zip" name="zip" style="width:300px;" /></td>
  </tr>  
  <tr class="new_customer">
    <td align="right" valign="top">Country &nbsp;</td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <select id="country">
        <?php
        foreach($countries as $kc => $country){
        ?>
            <option value="<?php echo $country; ?>" <?php if($garcon_settings['default_country']==$country){ echo 'selected="selected"'; } ?>><?php echo $country; ?></option>
        <?php
        }
        ?>
    </select>          
    </td>
  </tr>  
  <tr>
    <td align="right" valign="top">&nbsp;</td>
    <td align="right" valign="top">&nbsp;</td>
    <td colspan="5">
   	  <input type="button" id="save_booking" value="Save Booking" style="padding:4px;" /></td>
  </tr>
</table>

<input type="hidden" id="customer_id">
<input type="hidden" id="thecustomers" value="<?php echo $thecustomers; ?>">

</div>
