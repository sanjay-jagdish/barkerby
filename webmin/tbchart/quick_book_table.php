<?php
require('../config/config.php');
ini_set('display_errors',0);
$parameters = explode(' ',$_POST['parameters']);

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
?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">

<style>
	tr:hover td{ background:none !important; }
	td{ padding:2px; }
	.new_customer{ display:none; }
	#duration{  }
	#selected_tables, #selected_seats{ font-weight:bold; }
</style>
<div style="width:450px;">

<!--<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="tablechart/js/jquery-ui-timepicker-0.3.3/jquery.ui.timepicker.js"></script>

<script src="tablechart/js/jQuerytypeahead/typeahead.js"></script>
<link href="tablechart/js/jQuerytypeahead/examples.css" rel="stylesheet" type="text/css" />-->

<script>


jQuery('#time_start, #time_end').timepicker({
    showPeriodLabels: false,
});

/*		
jQuery( "#time_start" ).change(function() {
  // Check input( $( this ).val() ) for validity here
	var start_time = jQuery( "#time_start" ).val();
	var end_time = jQuery( "#time_end" ).val();

	jQuery.ajax({
			 url: "tablechart/timeDiffMins.php",
			 type: 'POST',
			 data: 'start='+start_time+'&end='+end_time,
			 success: function(value){
				/*
				if(jQuery.isNumeric(value)){	
					alert(value)				
				}else{
					alert(value);
				}
				*/
/*
				jQuery('#duration').html(value);
			 }
	});		
	
});
*/

jQuery( "#time_start" ).change(function() {
  // Check input( $( this ).val() ) for validity here
	var start_time = jQuery( "#time_start" ).val();
	var end_time = jQuery( "#time_end" ).val();

	if(start_time==''){
		jQuery('#duration').html('<font color="red">Invalid Time</font>');
		return false;
	}

	jQuery.ajax({
			 url: "tablechart/timeDiffMins.php",
			 type: 'POST',
			 data: 'start='+start_time+'&end='+end_time,
			 success: function(value){
				jQuery('#duration').html(value);
			 }
	});		
	
});


jQuery( "#time_end" ).change(function() {
  // Check input( $( this ).val() ) for validity here
	var start_time = jQuery( "#time_start" ).val();
	var end_time = jQuery( "#time_end" ).val();

	if(end_time==''){
		jQuery('#duration').html('<font color="red">Invalid Time</font>');
		return false;
	}


	jQuery.ajax({
			 url: "tablechart/timeDiffMins.php",
			 type: 'POST',
			 data: 'start='+start_time+'&end='+end_time,
			 success: function(value){
				jQuery('#duration').html(value);
			 }
	});		
	
});


jQuery( "#existing" ).change(function() {
  // Check input( $( this ).val() ) for validity here
	var exisiting = jQuery( this ).val();
	if(exisiting==1){
		jQuery('.new_customer').hide('slow');	
		jQuery('#search').show('show');	
	}else{
		jQuery('.new_customer').show('slow');	
		jQuery('#search').hide('slow');	
	}
});

//source: [{"id":"17","label":"jdoe@gmail.com","value":"John Doe"},{"id":"18","label":"djane@gmail.com","value":"Jane Doe"}],
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
	
	var pax = parseInt(jQuery('#pax').val());
	var seats_sel = parseInt(jQuery('#selected_seats').html());
			
	//check if the number of guests is provided
	if(pax==0 || isNaN(pax)){
		alert('Please enter the number of guests.');
		return false;
	}
	
	//check if number of seats is enough
	if(seats_sel>0){
		if(pax>seats_sel){
			alert('Seats not enough. Select more seats.');
			return false;
		}
	}else{
		alert('You have not selected a table yet to accommodate the guests.');
		return false;
	}
	

	var value = '';
	
	var table_id = $('input:checkbox:checked.table_option').map(function () {
	  return this.value;
	}).get();
      
	var customer_id = jQuery('#customer_id').val();	
 
  	if(customer_id>0){
	  		
		if(seats_sel>pax){
			var extra_seats = seats_sel-pax;
			confirm(extra_seats+' extra seat(s) detected.  Do you want to proceed?');		
		}
				
			var res_det_id = jQuery('#res_det_id').val();
			var date_selected = jQuery('#date_selected').val();	
			var time_start = jQuery('#time_start').val();	
			var time_end= jQuery('#time_end').val();	
			var pax = jQuery('#pax').val();
			//var table_id = jQuery('#table_id').val();	
			var notes = jQuery('#notes').val();
	
			jQuery.ajax({
					 url: "tablechart/saveBooking.php",
					 type: 'POST',
					 data: 'date_selected='+date_selected+'&time_start='+time_start+'&time_end='+time_end+'&table_id='+table_id+'&pax='+pax+'&notes='+notes+'&res_det_id='+res_det_id+'&customer_id='+customer_id,
					 success: function(value){
						if(jQuery.isNumeric(value)){	
							modal.open({content: '<font color="green">SUCCESS!</font> Reservation <b>No. '+value+'</b> has been created.'});
							setTimeout(function(){
								 location.reload();
							}, 3000);
						
						}else{
							alert(value);
						}
					 }
			});		


  }else{
	  
		if(seats_sel>pax){
			var extra_seats = seats_sel-pax;
			confirm(extra_seats+' extra seat(s) detected.  Do you want to proceed?');		
		}
	  
		var res_det_id = jQuery('#res_det_id').val();
		var date_selected = jQuery('#date_selected').val();	
		var time_start = jQuery('#time_start').val();	
		var time_end= jQuery('#time_end').val();	
		var pax = jQuery('#pax').val();
		//var table_id = jQuery('#table_id').val();	
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
			
			jQuery.ajax({
					 url: "tablechart/saveOtherBooking.php",
					 type: 'POST',
					 data: 'date_selected='+date_selected+'&time_start='+time_start+'&time_end='+time_end+'&table_id='+table_id+'&pax='+pax+'&notes='+notes+'&res_det_id='+res_det_id+'&first_name='+first_name+'&last_name='+last_name+'&phone_number='+phone_number+'&email_address='+email_address+'&street='+street+'&city='+city+'&zip='+zip+'&country='+country,
					 success: function(value){
						if(jQuery.isNumeric(value)){	
							modal.open({content: '<font color="green">SUCCESS!</font> Reservation <b>No. '+value+'</b> has been created.'});
							setTimeout(function(){
								 location.reload();
							}, 3000);
						
						}else{
							alert(value);
						}
					 }
			});			
			
			
			//div #modal #content
		}else{
			var search_qry = jQuery('#customer_search').val();
			if(search_qry!=''){
				alert('ERROR: Please fill-in the First & Last names and the Phone Number.');
			}else{
				alert('ERROR: Please try to search the customer by First or Last Name or Phone Number.');
			}
		}
  }
  
});


jQuery('#customer_search').typeahead({
     name: 'customers',
	 valueKey: 'name',
     local: [<?php echo $customer_list; ?>]		
	}).on('typeahead:selected', function($e, datum){
        jQuery('#customer_id').val(datum["id"]);
	}).on('typeahead:closed', function($e, datum){
		
		var thecustomers=new Array();
		var val=jQuery('#customer_search').val();
        var thelist=jQuery('#thecustomers').val().split("***");
		
		for(i=0;i<thelist.length;i++){
			thecustomers.push(thelist[i]);
		}
		
		if(thecustomers.indexOf(val)!=-1){
			jQuery('.new_customer').fadeOut();
		}
		else{
			jQuery('.new_customer').fadeIn();
			jQuery('#customer_id').val("");
		}
		
	});
	
jQuery('#reload_tables').click(function() {
	
	var date_selected = jQuery('#date_selected').val();
	var time_start = jQuery('#time_start').val();
	var time_end = jQuery('#time_end').val();
	var pax = jQuery('#pax').val();
	
	//clear tables
	jQuery('#selected_tables').html('0');
	jQuery('#selected_seats').html('0');
	jQuery('#seats_difference').html('');
	
			jQuery.ajax({
					 url: "tablechart/qb_table_list.php",
					 type: 'POST',
					 data: 'date_selected='+date_selected+'&time_start='+time_start+'&time_end='+time_end+'&pax='+pax,
					 success: function(value){
							jQuery('#table_list').html(value);					
					 }
			});			
});

</script>

<div style="float:left; width:200px;">
    <h3 style="margin:0px; padding:0px;">Boka
      <input type="hidden" name="res_det_id" id="res_det_id" value="<?php echo $parameters[1]; ?>" />
    </h3>
</div>

<div style="float:right; width:250px; text-align:right; vertical-align:bottom;">
	Aktuell tid och datum: <?php echo date('H:i:s - d M Y'); ?>
</div>

<div style="clear:both; height:1px;">&nbsp;</div>

<table width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td align="right" valign="middle">Datum&nbsp; </td>
    <td align="center" valign="middle">:&nbsp;</td>
    <td valign="middle">
    	<strong><?php echo dayName(date('D M d, Y',strtotime($parameters[0]))); ?></strong>
    	<input type="hidden" name="date_selected" id="date_selected" value="<?php echo $parameters[0]; ?>" />
    </td>
    <td valign="middle">&nbsp;</td>
    <td align="right" valign="middle">Från </td>
    <td align="center" valign="middle">:&nbsp;</td>
    <td valign="middle">
	<?php
		//get restaurant details e.g. start/end time with regards to current date
		$res_det_sql = "SELECT id, start_time, end_time, time_interval, dine_interval, between_interval FROM restaurant_detail 
						WHERE id=".$parameters[1]."
						AND deleted=0";
		
		$res_det_qry = mysql_query($res_det_sql);
		$res_det_num = mysql_num_rows($res_det_qry);
		$res_det = mysql_fetch_assoc($res_det_qry);
		
		if($res_det_num ==1){	
			
			$min_start_time = date('H:i:s',strtotime('+1 hour'));
			$min_start_time = roundToInterval($min_start_time,$res_det['time_interval']);
			
			$ideal_end_time = date('H:i:00', strtotime($min_start_time.' +'.$res_det['dine_interval'].' mins'));
			
			$time_increments = $res_det['time_interval'];
			$time = strtotime($res_det['start_time']);
			$end = strtotime($res_det['end_time']);
			
			$seltag = 0;
			while($time<=$end){
				if($min_start_time==date('H:i:00',$time) && $seltag==0){ $sel=' selected="selected"'; $seltag=1; }else{ $sel=''; }
				$time_options .= '<option value="'.date('H:i:s',$time).'" '.$sel.'>'.date('H:i',$time).'</option>';
	            $time = strtotime(date('H:i:s',$time).' + '.$time_increments.' mins');	
			}
		?>
		<!--select name="time" id="time_start" style="width:80px;">	
			<?php echo $time_options; ?>
		</select -->
		<input type="text" name="time" value="<?php echo substr($min_start_time,0,5); ?>" id="time_start" style="width:50px;" />
    <?php
		}
	?>
	</td>
  </tr>
	<tr>
	  <td align="right" valign="middle">Antal personer&nbsp; </td>
	  <td align="center" valign="middle">:&nbsp;</td>
	  <td valign="middle">
	  <input type="text" name="pax" id="pax" style="width:60px;" />
		</td>
    	<td valign="middle">&nbsp;</td>
        <td align="right" valign="middle">Till </td>
        <td align="center" valign="middle">:&nbsp;</td>
        <td valign="middle">
		<?php 
		if($res_det_num ==1){	
			
			$time_increments = $res_det['time_interval'];
			$time = strtotime($res_det['start_time']);
			$end = strtotime($res_det['end_time']);

			$increments = $time_increments;
		
			while($time<=$end){
	 			$time = strtotime(date('H:i:s',$time).' + '.$time_increments.' mins');	
	           	if($ideal_end_time==date('H:i:s',$time)){ $sel='selected="selected"'; }else{ $sel=''; }
				$time_options .= '<option value="'.date('H:i:s',$time).'" '.$sel.'>'.date('H:i',$time).'</option>';
			}
		
		?>
        <!--select name="time_end" id="time_end" style="width:80px;">
		<?php echo $time_options; ?>
        </select -->
        <?php
		}//only if the store is open
		?>
        <input type="text" name="time_end" value="<?php echo substr($ideal_end_time,0,5); ?>" id="time_end" style="width:50px;" />

        </td>
    </tr>
	<tr>
	  <td colspan="3" align="right">
      	<div align="center"><input type="button" id="reload_tables" value=" Uppdatera bordslista&nbsp; " /></div>
      </td>
	  <td align="left">&nbsp;</td>
	  <td align="right">Längd</td>
	  <td align="center">:</td>
	  <td align="left">
      	<div id="duration">
		<?php 
		$hrs = gmdate("H", ($res_det['dine_interval'] * 60));
		$mins = gmdate("i", ($res_det['dine_interval'] * 60));
		echo 'Tim: <strong>'.$hrs.'</strong> &nbsp;Min: <strong>'.$mins.'</strong>';
		//echo $garcon_settings['dine_interval']; 
		?>
        </div>
      </td>
    </tr>
	<tr>
	  <td align="right">Platser</td>
	  <td align="center">:</td>
	  <td><div id="selected_seats">0</div></td>
	  <td>&nbsp;</td>
	  <td align="right">Antal bord</td>
	  <td align="center">:</td>
	  <td><div id="selected_tables">0</div></td>
    </tr>
	<tr>
	  <td colspan="7" align="center">
      	<div id="seats_difference"></div>
        Välj bord:
   	    <div style="overflow:auto; width:250px !important; height:150px !important; border:solid thin #ccc;" id="table_list">
        
        Klicka på “Uppdatera bordslista” för tillgängliga bord.
        <!--table border="1" style="border-collapse:collapse; border:#CCC;">
        	<thead>
	        	<tr>
	        	  <th width="10%" bgcolor="#CCCCCC">Seats</th>
                	<th bgcolor="#CCCCCC" style="width:25px !important;">&nbsp;</th>
                    <th width="83%" bgcolor="#CCCCCC">Table</th>
                </tr>
    		</thead>
            <tbody>
            	<tr>
            	  <td align="center">2</td>
                	<td align="center"><input type="checkbox" /></td>
                    <td align="center">Table 1</td>
                </tr>
            	<tr>
            	  <td align="center">4</td>
               	  <td align="center"><input type="checkbox" /></td>
                    <td align="center">Table 2</td>
                </tr>
            	<tr>
            	  <td align="center">2</td>
               	  <td align="center"><input type="checkbox" /></td>
                    <td align="center">Table 3</td>
                </tr>
            </tbody>
        </table -->
        </div>
      </td>
    </tr>
	<tr>
	  <td align="right">&nbsp;</td>
	  <td align="center">&nbsp;</td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td align="right">&nbsp;</td>
	  <td align="center">&nbsp;</td>
	  <td>&nbsp;</td>
    </tr>
  <tr>
    <td align="right" valign="top">Övrig information</td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <textarea name="notes" id="notes" style="width:300px; height:50px;"></textarea>
    </td>
  </tr>
  <tr>
    <td align="right" valign="top">Gäst </td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    	<input type="text" id="customer_search" style="width:250px !important;">
        <input type="button" value="Skapa" class="searchbtn">
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
   /* $query = mysql_query("SELECT id, CONCAT(fname, ' ', lname) AS name 
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
    <td align="center" valign="top" colspan="7">Ingen gäst med dessa uppgifter hittades. Lägg till gäst nedan.</td>
  </tr>  
  <tr class="new_customer">
    <td align="right" valign="top">*Förnamn&nbsp; </td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <input type="text" id="first_name" name="first_name" style="width:300px;" /></td>
  </tr>  
  <tr class="new_customer">
    <td align="right" valign="top">*Efternamn&nbsp; </td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <input type="text" id="last_name" name="last_name" style="width:300px;" /></td>
  </tr>  
  <tr class="new_customer">
    <td align="right" valign="top">*Mobilnummer&nbsp; </td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <input type="text" id="phone_number" name="phone_number" style="width:300px;" /></td>
  </tr>  
  <tr class="new_customer">
    <td align="right" valign="top">E-post&nbsp; </td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <input type="text" id="email_address" name="email_address" style="width:300px;" /></td>
  </tr>  
  <!--<tr class="new_customer">
    <td align="left" valign="top">&nbsp; </td>
    <td align="right" valign="top">&nbsp;</td>
    <td colspan="5">&nbsp;</td>
  </tr>  -->
  <tr class="new_customer">
    <td align="right" valign="top">Adress &nbsp; </td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <input type="text" id="street" name="street" style="width:300px;" /></td>
  </tr>  
  <tr class="new_customer">
    <td align="right" valign="top">Ort &nbsp;</td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <input type="text" id="city" name="city" style="width:300px;" /></td>
  </tr>  
  <tr class="new_customer">
    <td align="right" valign="top">Postnummer &nbsp;</td>
    <td align="right" valign="top">:&nbsp;</td>
    <td colspan="5">
    <input type="text" id="zip" name="zip" style="width:300px;" /></td>
  </tr>  
  <tr class="new_customer">
    <td align="right" valign="top">Land &nbsp;</td>
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
   	  <input type="button" id="save_booking" value="Spara" style="padding:4px;" /></td>
  </tr>
</table>

</div>

<input type="hidden" id="customer_id">
<input type="hidden" id="thecustomers" value="<?php echo $thecustomers; ?>">

<?php
function roundToInterval($timestring, $interval){
    $minutes = date('i', strtotime($timestring));
    $minutes = $minutes - ($minutes % $interval);
	return date('H',strtotime($timestring)).':'.str_pad($minutes,2,0,STR_PAD_LEFT).':00';
}
?>