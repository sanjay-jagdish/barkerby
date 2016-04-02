<?php
	 include_once('redirect.php'); 
	 
if(isset($_GET['id'])){
		
		$q=mysql_query("select name, start_date, end_date, days, start_time, end_time, time_interval, dine_interval, between_interval, allowed_seats from restaurant_detail where id=".$_GET['id']);
		$rs=mysql_fetch_assoc($q);
?>
<script type="text/javascript">
	jQuery(function(){
		modifiedSettings('<?php echo $rs['name']?>','<?php echo $rs['start_date']?>','<?php echo $rs['end_date']?>','<?php echo $rs['days']?>','<?php echo $rs['start_time']?>','<?php echo $rs['end_time']?>','<?php echo $rs['time_interval']?>','<?php echo $_GET['id'];?>','<?php echo $rs['dine_interval'];?>','<?php echo $rs['between_interval'];?>','<?php echo $rs['allowed_seats'];?>','<?php echo maxSeats($_GET['id']); ?>');
	});
</script>
<?php			
		
	}
	
	//get the current date + 100 years
	$qq=mysql_query("select DATE_FORMAT(DATE_ADD(curdate(),INTERVAL 100 YEAR), '%m/%d/%Y') as future");
	$row=mysql_fetch_assoc($qq);
	
?>

<script type="text/javascript">

	(function($){

		$.fn.extend({
			sliderAccess: function(options){
				options = options || {};
				options.touchonly = options.touchonly !== undefined? options.touchonly : true; // by default only show it if touch device

				if(options.touchonly === true && !("ontouchend" in document))
					return $(this);
					
				return $(this).each(function(i,obj){
							var $t = $(this),
								o = $.extend({},{ 
												where: 'after',
												step: $t.slider('option','step'), 
												upIcon: 'ui-icon-plus', 
												downIcon: 'ui-icon-minus',
												text: false,
												upText: '+',
												downText: '-',
												buttonset: true,
												buttonsetTag: 'span',
												isRTL: false
											}, options),
								$buttons = $('<'+ o.buttonsetTag +' class="ui-slider-access">'+
												'<button data-icon="'+ o.downIcon +'" data-step="'+ (o.isRTL? o.step : o.step*-1) +'">'+ o.downText +'</button>'+
												'<button data-icon="'+ o.upIcon +'" data-step="'+ (o.isRTL? o.step*-1 : o.step) +'">'+ o.upText +'</button>'+
											'</'+ o.buttonsetTag +'>');

							$buttons.children('button').each(function(j, jobj){
								var $jt = $(this);
								$jt.button({ 
												text: o.text, 
												icons: { primary: $jt.data('icon') }
											})
									.click(function(e){
												var step = $jt.data('step'),
													curr = $t.slider('value'),
													newval = curr += step*1,
													minval = $t.slider('option','min'),
													maxval = $t.slider('option','max'),
													slidee = $t.slider("option", "slide") || function(){},
													stope = $t.slider("option", "stop") || function(){};

												e.preventDefault();
												
												if(newval < minval || newval > maxval)
													return;
												
												$t.slider('value', newval);

												slidee.call($t, null, { value: newval });
												stope.call($t, null, { value: newval });
											});
							});
							
							// before or after					
							$t[o.where]($buttons);

							if(o.buttonset){
								$buttons.removeClass('ui-corner-right').removeClass('ui-corner-left').buttonset();
								$buttons.eq(0).addClass('ui-corner-left');
								$buttons.eq(1).addClass('ui-corner-right');
							}

							// adjust the width so we don't break the original layout
							var bOuterWidth = $buttons.css({
										marginLeft: ((o.where == 'after' && !o.isRTL) || (o.where == 'before' && o.isRTL)? 10:0), 
										marginRight: ((o.where == 'before' && !o.isRTL) || (o.where == 'after' && o.isRTL)? 10:0)
									}).outerWidth(true) + 5;
							var tOuterWidth = $t.outerWidth(true);
							$t.css('display','inline-block').width(tOuterWidth-bOuterWidth);
						});		
			}
		});

	})(jQuery);


	jQuery(function(){
		
		jQuery('.add-table').click(function(){
			
			var table_count=Number(jQuery('span.the-tables').length);
			
			jQuery('.settable-inner').append('<span class="tables the-tables" id="table-'+(table_count+1)+'"><input type="text" class="thetablename txt" style="width: 90px; margin-right: 15px;" placeholder="Table Name" id="'+(table_count+1)+'"><input type="text" class="thetable txt" style="margin: 0 10px 0 0;" placeholder="Number of Seats"><a href="javascript:void(0)" class="delete-table" onclick="deleteTable('+(table_count+1)+')"><img src="images/delete-small.png" style="position: relative; top: 7px;"></a><a href="javascript:void(0)" style="display:none" class="activate-table" onclick="activateTable('+(table_count+1)+')"><img src="images/check-small.png" style="position: relative; top: 7px;"></a></span><div class="clear"></div>');
			
			jQuery('.thetable').numeric();
			
		});
		
		jQuery('.table-pax').click(function(){
			jQuery('.fade,.table-box').fadeIn();
			
			var id=jQuery(this).attr('data-rel');
			
			jQuery('.table-box .box-content').html('<img src="images/loader.gif" style="margin: 30px 0 0;">');
			
			jQuery.ajax({
				 url: "actions/table-availability.php",
				 type: 'POST',
				 data: 'id='+encodeURIComponent(id),
				 success: function(value){
					 jQuery('.table-box .box-content').html(value);
				 }
			});
			
		});
		

			$('.timeinterval').slider({
				  value:0,
				  min: 0,
				  max: 120,
				  step: 15,
				  slide: function( event, ui ) {
					//jQuery('.dinespan label').html(ui.value );
					// alert(ui.value);
					jQuery('.timespan label').html(timeFormat(ui.value)).attr('data-rel',ui.value);
				  }
				}).sliderAccess({
				touchonly : false
			});
		


			$('.dineinterval').slider({
				  value:60,
				  min: 60,
				  max: 2400,
				  step: 30,
				  slide: function( event, ui ) {
					//jQuery('.dinespan label').html(ui.value );
					// alert(ui.value);
					jQuery('.dinespan label').html(timeFormat(ui.value)).attr('data-rel',ui.value);
				  }
				}).sliderAccess({
				touchonly : false
			});
					

			$('.betweeninterval').slider({
				  value:0,
				  min: 0,
				  max: 120,
				  step: 15,
				  slide: function( event, ui ) {
					//jQuery('.dinespan label').html(ui.value );
					jQuery('.betweenspan label').html(timeFormat(ui.value)).attr('data-rel',ui.value);
				  }
				}).sliderAccess({
				touchonly : false
			});



		
		
		jQuery('.untildate').click(function(){
			
			var startdate = jQuery('.startdate').val();
			var val=jQuery(this).prop('checked');
			jQuery('.thedays').prop('disabled',false);
			
			if(startdate!=''){
				
				if(val){
					jQuery('.enddate').val(jQuery(this).attr('data-rel'));
					jQuery('.thedays').prop('checked',true);
				}
				else{
					jQuery('.enddate').val('');
					jQuery('.thedays').prop('checked',false);
				}
			
			}
			else{
				jQuery('.enddate').val('');
				jQuery('.thedays').prop('checked',false);
				alert('Please specify the Start Date');
			}
			
		});



		jQuery('.thetablename').focusout(function(){
			var id=jQuery(this).attr('id');
			var str=jQuery(this).val();
			jQuery(this).attr('value',str);

			jQuery('.thetablename').removeClass('redborder');

			var theval=new Array();
			var thesame=new Array();

			jQuery('.thetablename').each(function(){
				var val=jQuery(this).val().trim();
				if(theval.indexOf(val)== -1){
					theval.push(val);	
				}
				else{
					thesame.push(val);
				}
				
			});

			for(i=0;i<thesame.length;i++){
				jQuery('.thetablename[value='+thesame[i]+']').addClass('redborder');
			}


		});

		jQuery('.thetable, .numseats').keyup(function(){
			jQuery(this).val(jQuery(this).val().replace('.',''));
		});


		setInterval(function() {

            jQuery.ajax({
				 url: "actions/currentSchedule.php",
				 type: 'POST',
				 success: function(value){
				
					value = value.trim();
				
					jQuery('.gradeX').removeClass('current-settings');
					jQuery('.gradeX-'+value).addClass('current-settings');
				 }
			});

        }, 2000);
		
		
	});
	
	function deleteTable(id){

		// jQuery('#table-'+id+' .txt').removeClass('thetablename redborder').val('');
		
		// jQuery('#table-'+id).fadeOut().removeClass('the-tables').attr('id','');

		var val1= jQuery('#table-'+id+' .thetablename').val();
		var val2= jQuery('#table-'+id+' .thetable').val();

		jQuery('#table-'+id+' .txt').val('');
		jQuery('#table-'+id+' .txt').attr('value', '');

		jQuery('#table-'+id+' .thetablename').attr('placeholder', val1);
		jQuery('#table-'+id+' .thetable').attr('placeholder', val2);

		// alert(jQuery('#table-'+id+' .thetablename').val());

		
		

		jQuery('#table-'+id+' .txt').addClass('styled');
		
		jQuery('#table-'+id+' .txt').attr('readonly','readonly');

		jQuery('#table-'+id+' .delete-table').css({'display':'none'});

		jQuery('#table-'+id+' .activate-table').css({'display':'inline-block'});
		
		// jQuery('span.the-tables .delete-table').each(function(i){
		// 	jQuery(this).attr('onclick','deleteTable('+(i+2)+')');
		// });
		
		// jQuery('span.the-tables').each(function(i){
		// 	jQuery(this).attr('id','table-'+(i+1));
		// });
		
		var totalseats=0;
		jQuery('.thetable').each(function(){
			var val=Number(jQuery(this).val());
			totalseats+=val;
		});
		
		// jQuery('.totalseats').val(totalseats);
		jQuery('.totalseats').val(totalseats);
		
	}


	function activateTable(id){


		var val1= jQuery('#table-'+id+' .thetablename').attr('placeholder');
		var val2= jQuery('#table-'+id+' .thetable').attr('placeholder');

		// jQuery('#table-'+id+' .thetablename').attr('placeholder', 'Table Name');
		// jQuery('#table-'+id+' .thetable').attr('placeholder', 'Number of Seats');

		jQuery('#table-'+id+' .thetablename').attr('value', val1).val(val1).prop('value', val1);
		jQuery('#table-'+id+' .thetable').attr('value', val2).val(val2).prop('value', val2);

		jQuery('#table-'+id+' .txt').removeAttr('readonly');
		jQuery('#table-'+id+' .txt').removeAttr('placeholder');

		jQuery('#table-'+id+' .txt').removeClass('styled');




		jQuery('#table-'+id+' .delete-table').css({'display':'inline-block'});

		jQuery('#table-'+id+' .activate-table').css({'display':'none'});

		var totalseats=0;
		jQuery('.thetable').each(function(){
			var val=Number(jQuery(this).val());
			totalseats+=val;
		});
		jQuery('.totalseats').val(totalseats);
	}
	
	function copySettings(name,startdate,enddate,days,starttime,endtime,interval,id,dine,between,num_seats,total_seats){
		jQuery('.name').val(name);
		jQuery('.startdate').val(startdate);
		jQuery('.enddate').val(enddate);
		jQuery('.starttime').val(starttime);
		jQuery('.endtime').val(endtime);
		/*jQuery('.timeinterval').val(interval);
		jQuery('.dineinterval').val(dine);
		jQuery('.betweeninterval').val(between);*/
		
		jQuery( ".timeinterval" ).slider('option', 'value', interval);
		jQuery( ".dineinterval" ).slider('option', 'value', dine);
		jQuery( ".betweeninterval" ).slider('option', 'value', between);
		
		/*jQuery('.timespan label').html(interval);
		jQuery('.dinespan label').html(dine);
		jQuery('.betweenspan label').html(between);*/
		
		jQuery('.timespan label').html(timeFormat(interval)).attr('data-rel',interval);
		jQuery('.dinespan label').html(timeFormat(dine)).attr('data-rel',dine);
		jQuery('.betweenspan label').html(timeFormat(between)).attr('data-rel',between);
		
		jQuery('.thedays').each(function(){
			jQuery(this).prop('checked',false);
		});

		jQuery('.numseats').val(num_seats);
		jQuery('.totalseats').val(total_seats);
		
		jQuery('.untildate').prop('checked',false);
		
		jQuery('.thedays').each(function(){
            var val=jQuery(this).val();
			
			var hold=days.split(", ");
			
			for(i=0;i<hold.length;i++){
			
				if(val==hold[i]){
					jQuery(this).prop('checked',true);
				}
			}
			
        });
		
		
		jQuery('.settable').html('<div style="text-align:center"><img src="images/loader.gif" style="margin: 30px auto 0;"></div>');
		
		jQuery.ajax({
			 url: "actions/number-tables.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){
				 jQuery('.settable').html(value);
			 }
		});
		
	}
	
	
	function modifiedSettings(name,startdate,enddate,days,starttime,endtime,interval,id,dine,between,num_seats,total_seats){
		jQuery('.name').val(name);
		jQuery('.startdate').val(startdate);
		jQuery('.enddate').val(enddate);
		jQuery('.starttime').val(starttime);
		jQuery('.endtime').val(endtime);
		/*jQuery('.timeinterval').val(interval);
		jQuery('.dineinterval').val(dine);
		jQuery('.betweeninterval').val(between);*/
		
		jQuery( ".timeinterval" ).slider('option', 'value', interval);
		jQuery( ".dineinterval" ).slider('option', 'value', dine);
		jQuery( ".betweeninterval" ).slider('option', 'value', between);
		
		/*jQuery('.timespan label').html(interval);
		jQuery('.dinespan label').html(dine);
		jQuery('.betweenspan label').html(between);*/
		
		jQuery('.timespan label').html(timeFormat(interval)).attr('data-rel',interval);
		jQuery('.dinespan label').html(timeFormat(dine)).attr('data-rel',dine);
		jQuery('.betweenspan label').html(timeFormat(between)).attr('data-rel',between);
		
		jQuery('.untildate').prop('checked',false);
		
		jQuery('.thedays').each(function(){
			jQuery(this).prop('checked',false);
		});

		jQuery('.numseats').val(num_seats);
		jQuery('.totalseats').val(total_seats);
		
		jQuery('.thedays').each(function(){
            var val=jQuery(this).val();
			
			var hold=days.split(", ");
			
			for(i=0;i<hold.length;i++){
			
				if(val==hold[i]){
					jQuery(this).prop('checked',true);
				}
			}
			
        });
		
		jQuery('.settable').html('<div style="text-align:center"><img src="images/loader.gif" style="margin: 30px auto 0;"></div>');
		
		jQuery.ajax({
			 url: "actions/number-tables.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){
				 jQuery('.settable').html(value);
			 }
		});
		
		jQuery('.foreditsettings').html('<input type="button" class="btn edit-settings-btn" data-rel="'+id+'" onclick="editSettings()" value="Submit">');


		// jQuery('.see-table').click(function(){

			
		// });
		
		
	}


	function see_table(){

		var val = jQuery('.see-table').attr('data-rel');

		if(val == 0){
			jQuery('.bordsplan-wrap').slideDown("slow");
			jQuery('.see-table').attr('data-rel', '1');
			jQuery('.see-table').html('Hide Table');

		}else{
			jQuery('.bordsplan-wrap').slideUp("slow");
			jQuery('.see-table').attr('data-rel', '0');
			jQuery('.see-table').html('Show Table');
		}
	}
	
</script>

<div class="page settings-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
					echo 'Bordsintervaller';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
       
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
    	
    	<table>
    		<tr>
            	<td>Name :</td>
                <td><input type="text" class="name txt" style="width:295px;"></td>
            </tr>
        	<tr>
            	<td>Startdatum :</td>
                <td><input type="text" class="startdate txt" style="width:295px;"></td>
            </tr>
            <tr>
            	<td>Tills vidare :</td>
                <td><input type="checkbox" class="untildate" id="untildate" data-rel="<?php echo $row['future'];?>"> <label for="untildate" style="font-size:11px;"><span style="color:red;">OBS:</span> Gäller 100år framåt.</label></td>
            </tr>
            <tr>
            	<td>Slutdatum :</td>
                <td><input type="text" class="enddate txt" style="width:295px;"></td>
            </tr>
        	<tr>
            	<td>Dagar :</td>
                <td>
                	<input type="checkbox" id="mon" class="thedays" value="Mon"> <label for="mon">Mån</label>
                    <input type="checkbox" id="tue" class="thedays" value="Tue"> <label for="tue">Tis</label>
                    <input type="checkbox" id="wed" class="thedays" value="Wed"> <label for="wed">Ons</label>
                    <input type="checkbox" id="thu" class="thedays" value="Thu"> <label for="thu">Tor</label>
                    <input type="checkbox" id="fri" class="thedays" value="Fri"> <label for="fri">Fre</label>
                    <input type="checkbox" id="sat" class="thedays" value="Sat"> <label for="sat">Lör</label>
                    <input type="checkbox" id="sun" class="thedays" value="Sun"> <label for="sun">Sön</label>
                </td>
            </tr>
        	<tr>
            	<td>Starttid :</td>
                <td><input type="text" class="starttime txt" style="width:295px;"></td>
            </tr>
            <tr>
            	<td>Sluttid :</td>
                <td><input type="text" class="endtime txt" style="width:295px;"></td>
            </tr>
            <tr>
            	<td>Bokningsintervall :</td>
                <td><!--<input type="text" class="timeinterval txt" style="width:270px;" value="5">-->
                <span class="timeinterval timeslider"></span> <span class="timespan thespan"><label></label></span>
                </td>
            </tr>
            <tr>
            	<td>Sittningsintervall :</td>
                <td><!--<input type="text" class="dineinterval txt" style="width:270px;" value="90">-->
                <span class="dineinterval timeslider"></span> <span class="dinespan thespan"><label></label></span></td>
            </tr>
            <tr>
            	<td>Dukningsintervall :</td>
                <td><!--<input type="text" class="betweeninterval txt" style="width:270px;" value="15">-->
                <span class="betweeninterval timeslider"></span> <span class="betweenspan thespan"><label></label></span></td>
            </tr>
            

            <tr>
            	<td colspan="2">

            		<a href="javascript:void(0)" class="see-table" onclick="see_table()" data-rel="0">See tables</a>

            	<div class="bordsplan-wrap" style="display:none">

            		

					<table>
						 <tr>
			            	<td><strong>Bordsnummer</strong></td>
			                <td><strong>Antal platser</strong></td>
			            </tr>
					</table>
                	
                    <?php

                    	$settotal=0;

                    	$qr=mysql_query("select * from table_masterlist where id=1");
						
						if(mysql_num_rows($qr) > 0){
						
							$rw=mysql_fetch_assoc($qr);	

							$settotal+=$rw['seats'];
					?>
                    
                    <div class="settable" >
                    	<span class="tables the-tables" id="table-1"><input type="text" class="thetablename txt" style="width: 90px; margin-right: 15px;" placeholder="Table Name" value="<?php echo $rw['name'];?>" id="1"><input type="text" class="thetable txt" style="margin: 0 10px 0 0;" placeholder="Number of Seats" value="<?php echo $rw['seats'];?>"><a href="javascript:void(0)" class="add-table"><img src="images/add-small.png" style="position: relative; top: 7px;"></a></span>
                        <div class="settable-inner">
                     		 <?php
                             	$qy=mysql_query("select * from table_masterlist where id>1 and deleted=0");
								if(mysql_num_rows($qy) > 0){
									$count=1;
									while($ro=mysql_fetch_assoc($qy)){
										$count++;
										$settotal+=$ro['seats'];
							 ?>
                             <span class="tables the-tables" id="table-<?php echo $count; ?>"><input type="text" class="thetablename txt" style="width: 90px; margin-right: 15px;" placeholder="Table Name" value="<?php echo $ro['name'];?>" id="<?php echo $count; ?>"><input type="text" class="thetable txt" style="margin: 0 10px 0 0;" placeholder="Number of Seats" value="<?php echo $ro['seats'];?>"><a href="javascript:void(0)" class="delete-table" onclick="deleteTable('<?php echo $count; ?>')"><img src="images/delete-small.png" style="position: relative; top: 7px;"></a><a href="javascript:void(0)" style="display:none" class="activate-table" onclick="activateTable('<?php echo $count; ?>')"><img src="images/check-small.png" style="position: relative; top: 7px;"></a></span><div class="clear"></div>	
                             <?php			
									}
								}
							 ?>  	
                        </div>
                    </div>
                    
                    <?php		
						}
						else{
							
							//if table_masterlist is empty. 
							//this one will be loaded.
					?>

                
                	<div class="settable">
                    	<span class="tables the-tables" id="table-1"><input type="text" class="thetablename txt" style="width: 90px; margin-right: 15px;" placeholder="Table Name" id="1"><input type="text" class="thetable txt" style="margin: 0 10px 0 0;" placeholder="Number of Seats"><a href="javascript:void(0)" class="add-table"><img src="images/add-small.png" style="position: relative; top: 7px;"></a></span>
                        <div class="settable-inner">
                        
                        </div>
                    </div>
                    
                    <?php } ?>

                    </div> <!-- end bordsplan-wrap -->

                </td>
            </tr>
            <tr>
            	<td>Antal platser :</td>
                <td><input type="text" class="totalseats txt" style="width:295px;" readonly="readonly" value="<?php echo $settotal; ?>"></td>
            </tr>
            <tr>
            	<td>Bokningsbart online :</td>
                <td><input type="text" class="numseats txt" style="width:295px;"></td>
            </tr>
            <tr>
            	<td colspan="2" align="right" class="foreditsettings"><input type="button" class="btn settings-btn" value="Utför"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
        
        <div class="newtab">
        	<a href="javascript:void" class="btn">Trash Bin</a>
            <a href="javascript:void" class="btn">Current Settings</a>
        </div>
        
        <br>
        
        <!-- settings output -->
        
        <?php
        		
				$currentDate = date("m/d/Y");
				$dayName=date('D', strtotime($currentDate));

				//get the current time
				$t=time();
				$currentTime=date("Hi",$t);
				
				
				$q=mysql_query("select id from restaurant_detail where DATE_FORMAT(STR_TO_DATE('".$currentDate."', '%m/%d/%Y'), '%Y-%m-%d') >= DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') and DATE_FORMAT(STR_TO_DATE('".$currentDate."', '%m/%d/%Y'), '%Y-%m-%d') <= DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') and days like '%".$dayName."%' and deleted=0 order by id desc limit 1") or die(mysql_error());
				
				
				//select id from restaurant_detail where '".$currentDate."' >= start_date and '".$currentDate."' <= end_date and days like '%".$dayName."%' and deleted=0 and ".$currentTime.">=(replace(start_time,':','')) and ".$currentTime."<=(replace(end_time,':','')) order by id desc limit 1
				
				//$q=mysql_query("select id from restaurant_detail where DATE_FORMAT(STR_TO_DATE('".$currentDate."', '%m/%d/%Y'), '%Y-%m-%d') >= DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') and DATE_FORMAT(STR_TO_DATE('".$currentDate."', '%m/%d/%Y'), '%Y-%m-%d') <= DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') and days like '%Wed%' and deleted=0 and ".$currentTime.">=(replace(start_time,':','')) and ".$currentTime."<=(replace(end_time,':','')) order by id desc limit 1") or die(mysql_error());
				
				
				$rs=mysql_fetch_assoc($q);
				$currentID = $rs['id'];
				

		?>
        
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="thesettings">
                <thead>
                    <tr>
                    	<th>Name</th>
                    	<th>Aktiv</th>                    	
                    	<th>Startdatum</th>
                        <th>Slutdatum</th>
                        <th>Dagar</th>
                        <th>Starttid</th>
                        <th>Sluttid</th>
                        <th>Bokningsintervall</th>
                        <th>Sittningsintervall</th>
                        <th>Dukningsintervall</th>
                        <th>Antal platser</th>
                        <th>Bokningsbart online</th>
                        <th>Åtgärd</th>
                    </tr>
                </thead>
                <tbody>
					<?php
                        //$q=mysql_query("select days, start_time, end_time, id, start_date, date_format(STR_TO_DATE(end_date, '%m/%d/%Y'),'%b %d, %Y'), time_interval, dine_interval, between_interval, allowed_seats, end_date from restaurant_detail where deleted=0") or die(mysql_error());
						
						$q=mysql_query("select days, start_time, end_time, id, start_date, end_date, time_interval, dine_interval, between_interval, allowed_seats, date_format(STR_TO_DATE(end_date, '%m/%d/%Y'),'%b %d, %Y'), name from restaurant_detail where deleted=0") or die(mysql_error());
   
                        while($r=mysql_fetch_array($q)){
                        
                    ?>
                        <tr class="gradeX gradeX-<?php echo $r[3];?> <?php if($currentID==$r[3]) echo 'current-settings';?>" align="center">
                       		 <td><?php echo $r[11]; ?></td>
                        	<td><img src="images/activesettings.png" class="active-settings"></td>
                        	<td><?php echo date("M d, Y",strtotime($r[4])); ?></td>
                            <td><?php echo $r[10]; ?></td>
                            <td><?php echo replaceDayname($r[0]);?></td>
                            <td><?php echo $r[1];?></td>
                            <td><?php echo $r[2];?></td>
                            <td><?php echo timeFormatting($r[6]);?></td>
                            <td><?php echo timeFormatting($r[7]);?></td>
                            <td><?php echo timeFormatting($r[8]);?></td>
                            <!--<td><a href="javascript:void(0)" class="table-pax" data-rel="<?php //echo $r[3]; ?>"><?php //echo maxPax($r[3]);?></a></td>-->
                            <td><?php echo maxSeats($r[3]);?></td>
                            <td><?php echo $r[9]; ?></td>
                            <td>
                            	<a href="javascript:void(0)" class="copy-setting" title="Kopiera intervall" onclick="copySettings('<?php echo $r[11]; ?>','<?php echo $r[4];?>','<?php echo $r[5];?>','<?php echo $r[0];?>','<?php echo $r[1];?>','<?php echo $r[2];?>','<?php echo $r[6];?>','<?php echo $r[3];?>','<?php echo $r[7]; ?>','<?php echo $r[8]; ?>','<?php echo $r[9]; ?>','<?php echo maxSeats($r[3]); ?>')"><img src="images/copy.png" alt="Kopiera intervall"></a>
                                
                               <?php
							   
                               		$qq=mysql_query("select rt.id from reservation_table as rt, table_detail as td, restaurant_detail as rd where td.id=rt.table_detail_id and rd.id=td.restaurant_detail_id and td.restaurant_detail_id=".$r[3]) or die(mysql_error());
									if(mysql_num_rows($qq)==0 & $r[5]>=$currentDate){
							   ?> 
                               <a href="javascript:void(0)" class="edit-setting" title="Redigera intervall" onclick="modifiedSettings('<?php echo $r[11]; ?>','<?php echo $r[4];?>','<?php echo $r[5];?>','<?php echo $r[0];?>','<?php echo $r[1];?>','<?php echo $r[2];?>','<?php echo $r[6];?>','<?php echo $r[3];?>','<?php echo $r[7]; ?>','<?php echo $r[8]; ?>','<?php echo $r[9]; ?>','<?php echo maxSeats($r[3]); ?>')"><img src="images/edit.png" alt="Redigera intervall"></a>
                               <?php } ?>
                               
                               <?php if($_SESSION['login']['type']<3){ 
							   			if(mysql_num_rows($qq)==0){
							   ?>
                               <a href="javascript:void(0)" class="delete-setting" title="Radera intervall" data-rel="<?php echo $r[3]; ?>"><img src="images/delete.png" alt="Radera intervall"></a>                               
                               <?php 	} 
							   		}
							   ?> 
                            </td>
                        </tr>
                    <?php		
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                    	<th>Name</th>
                    	<th>Aktiv</th>
                    	<th>Startdatum</th>
                        <th>Slutdatum</th>
                        <th>Dagar</th>
                        <th>Starttid</th>
                        <th>Sluttid</th>
                        <th>Bokningsintervall</th>
                        <th>Sittningsintervall</th>
                        <th>Dukningsintervall</th>
                        <th>Antal platser</th>
                        <th>Bokningsbart online</th>
                        <th>Åtgärd</th>
                    </tr>
                </tfoot>
            </table>
        <div class="clear"></div>
        <p style="margin:20px 0 0;"><font style="color: #e67e22;">OBS :</font> Aktuell bordsinställning visas med grön bakgrund. .</p>
    </div>
</div>

<div class="fade"></div>
<div class="delete-setting-box modalbox">
	<h2>Confirm Delete<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Are you sure you want to proceed?</p>
        <input type="button" value="Delete">
    </div>
</div>

<div class="table-box orderbox">
	<h2>Table Availability<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <!-- content here -->
    </div>
</div>