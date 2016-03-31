<?php
require('../config/config.php');

$parameters = explode('|',$_POST['parameters']);
$date_time = explode(' ',$parameters[0]);


$query = mysql_query("SELECT id, CONCAT(fname, ' ', lname) AS name, phone_number
						 FROM account 
						 WHERE deleted=0 AND id=".$parameters[2]);	
$account = mysql_fetch_assoc($query);


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
	
	#ui-datepicker-div{
		z-index:99999 !important;
	}
</style>
<div style="width:450px;">

<!--<link rel="stylesheet" media="all" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->

<!--<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>-->
<!--<script src="tablechart/js/jquery-ui-timepicker-0.3.3/jquery.ui.timepicker.js"></script>
<script src="tablechart/js/jQuerytypeahead/typeahead.js"></script>
<link href="tablechart/js/jQuerytypeahead/examples.css" rel="stylesheet" type="text/css" />-->

<script type="text/javascript">
	jQuery(function(){
		
		jQuery('.startdate').datepicker({
			firstDay: 1,
			minDate: 0, 
			dateFormat: 'mm/dd/yy',
			onSelect: function(selectedDate){
				var sdate = jQuery(this).datepicker('getDate');
				var mm=sdate.getMonth()+1;
				var dd=sdate.getDate();
				var yyyy=sdate.getFullYear();
				
				if(mm<10){
					mm="0"+mm;
				}
				if(dd<10){
					dd="0"+dd;
				}
				
				var sd=mm+"/"+dd+"/"+yyyy;
				var ed=jQuery('.enddate').val();
				
				if(ed!=''){
					
					
					if(new Date(sd)<= new Date(ed)){
						
						var nsd=new Date(sd);
						var ned=new Date(ed);
						
						var datediff=Number((ned-nsd)/(24*60*60*1000));
						
						if(datediff>=7){
							jQuery('.thedays').each(function() {
                                jQuery(this).prop('checked',true);
                            });
						}
						else{
							
							jQuery('.thedays').each(function() {
                                jQuery(this).prop('checked',false);
                            });
							
							var dayName= new Array('sun','mon','tue','wed','thu','fri','sat');
							var d=nsd.getDay();
							
							var count=d+datediff;
							
							var checker=0;
							for(i=d;i<=count;i++){
								if(i<=6){
									jQuery('#'+dayName[i]).prop('checked',true);
								}
								else{
									checker+=1;
								}
							}
							
							if(checker>0){
								for(i=0;i<checker;i++){
									jQuery('#'+dayName[i]).prop('checked',true);
								}
							}
							
							
						}
						
					}
					else{
						alert('Start Date should be less than or equal to the End Date.');
						jQuery(this).val('');
					}
				}
				
			}
		});
		
		
		jQuery('.enddate').datepicker({
			firstDay: 1,
			minDate: 0, 
			dateFormat: 'mm/dd/yy',
			onSelect: function(selectedDate){
				var edate = jQuery(this).datepicker('getDate');
				
				var mm=edate.getMonth()+1;
				var dd=edate.getDate();
				var yyyy=edate.getFullYear();
				
				if(mm<10){
					mm="0"+mm;
				}
				if(dd<10){
					dd="0"+dd;
				}
				
				var ed=mm+"/"+dd+"/"+yyyy;
				var sd=jQuery('.startdate').val();
				
				if(sd!=''){
					
					if(new Date(sd)<=new Date(ed)){
						
						var nsd=new Date(sd);
						var ned=new Date(ed);
						
						var datediff=Number((ned-nsd)/(24*60*60*1000));
						
						if(datediff>=7){
							jQuery('.thedays').each(function() {
                                jQuery(this).prop('checked',true);
                            });
						}
						else{
							
							jQuery('.thedays').each(function() {
                                jQuery(this).prop('checked',false);
                            });
							
							var dayName= new Array('sun','mon','tue','wed','thu','fri','sat');
							var d=nsd.getDay();
							
							var count=d+datediff;
							
							var checker=0;
							for(i=d;i<=count;i++){
								if(i<=6){
									jQuery('#'+dayName[i]).prop('checked',true);
								}
								else{
									checker+=1;
								}
							}
							
							if(checker>0){
								for(i=0;i<checker;i++){
									jQuery('#'+dayName[i]).prop('checked',true);
								}
							}
							
							
						}
						
					}
					else{
						alert('Start Date should be less than or equal to the End Date.');
						jQuery(this).val('');
					}
				}
				
			}
		});
		
		jQuery('.starttime, .endtime').timepicker({
			//timeFormat: 'hh:mm tt'
			minuteGrid: 15
		});
	});

	// For scheduler-page
    jQuery('.scheduler-btn').click(function(e) {
        /* Prevent default actions */
        e.preventDefault();
        e.stopPropagation();
        
        addSchedule();
    });

function addSchedule(){
	var empname=jQuery('.empname').val();
	var starttime=jQuery('.starttime').val();
	var endtime=jQuery('.endtime').val();
	var thedays=jQuery('.thedays:checked').length;
	var startdate=jQuery('.startdate').val();
	var enddate=jQuery('.enddate').val();
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(empname!='' & startdate!='' & enddate!='' & starttime!='' & endtime!='' & thedays!=0){
		
		if(enddate >= startdate){
			
			var days='';
			jQuery('.thedays:checked').each(function() {
				days+=this.value+", ";
			});
			
			days=days.substr(0,days.length-2);
			
			jQuery.ajax( {
					url: "./actions/add-schedule.php",
					type: 'POST',
					data: 'starttime='+encodeURIComponent(starttime)+'&endtime='+ encodeURIComponent(endtime)+'&days='+ encodeURIComponent(days)+'&startdate='+ encodeURIComponent(startdate)+'&enddate='+ encodeURIComponent(enddate)+'&id='+ encodeURIComponent(empname),
					success: function(value) {
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Schedule successfully saved.');
				
						 setTimeout("window.location.reload();", 2000);
					}
			});
			
		}
		else{
			jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Start Date should be lesser than or equal to the End Date.');
		}
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fields are all required.');
	}
}


</script>
<h2><?php echo $account['name']; ?><input type="hidden" value="<?php echo $parameters[2]; ?>" class="empname" /></h2>
<h5>Staff Schedule</h5>

<table>
        	<tbody>
        	<tr>
            	<td><div align="right">Start Date :</div></td>
                <td><input type="text" class="startdate txt" style="width:100px; text-align:center;" value="<?php echo date("m/d/Y",strtotime($parameters[1])); ?>" id="start_date" /></td>
                <td align="right">End Date :</td>
                <td><input type="text" class="enddate txt" style="width:100px; text-align:center;" id="end_date" /></td>
            </tr>
        	<tr>
            	<td> <div align="right">Days :</div></td>
                <td colspan="3">
                	<input type="checkbox" id="mon" class="thedays" value="Mon"> <label for="mon" style="width:35px;">Mon</label>
                    <input type="checkbox" id="tue" class="thedays" value="Tue"> <label for="tue" style="width:35px;">Tue</label>
                    <input type="checkbox" id="wed" class="thedays" value="Wed"> <label for="wed" style="width:35px;">Wed</label>
                    <input type="checkbox" id="thu" class="thedays" value="Thu"> <label for="thu" style="width:35px;">Thu</label>
                    <input type="checkbox" id="fri" class="thedays" value="Fri"> <label for="fri" style="width:35px;">Fri</label>
                    <input type="checkbox" id="sat" class="thedays" value="Sat"> <label for="sat" style="width:35px;">Sat</label>
                    <input type="checkbox" id="sun" class="thedays" value="Sun"> <label for="sun" style="width:35px;">Sun</label>
                </td>
            </tr>
        	<tr>
            	<td nowrap="nowrap"> <div align="right">Start Time :</div></td>
                <td><input type="text" class="starttime txt" style="width:100px; text-align:center;" value="<?php echo substr($parameters[1],0,5); ?>" id="start_time"></td>
                <td align="right">End Time :</td>
                <td><input type="text" class="endtime txt" style="width:100px; width:100px; text-align:center;" id="end_time" /></td>
            </tr>
            <tr>
                <td colspan="4">
                        <div class="displaymsg"></div>
                </td>
            </tr>
            <tr>
            	<td colspan="4" align="right" class="foreditbtn"><input type="button" class="btn scheduler-btn" value="Submit"></td>
            </tr>
        </tbody></table>

</div>