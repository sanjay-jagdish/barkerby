<?php
	//get the current date + 100 years
	$qq=mysql_query("select DATE_FORMAT(DATE_ADD(curdate(),INTERVAL 100 YEAR), '%m/%d/%Y') as future");
	$row=mysql_fetch_assoc($qq);
?>
<style>
	span.ui_slide.ui-slider.ui-slider-horizontal.ui-widget.ui-widget-content.ui-corner-all {
		width: 200px !important;
		display:inline-block;
	}
	.sttimeslider,
	.endtimeslider{
		display:none;
		padding:20px;
		background:#f1f1f1;
		border:1px solid #ccc;
		border-radius:4px;
	}
	.ui_close{
		text-align:center;	
	}
	.ui_close .xclose{
		border: 1px solid #d3d3d3;
		background: #e6e6e6 url(images/ui-bg_glass_75_e6e6e6_1x400.png) 50% 50% repeat-x;
		font-weight: normal;
		color: #555555;
		padding:5px 10px;
		cursor:pointer;
	}
</style>
<script type="text/javascript">
	jQuery(function(){
		
		jQuery( ".advancetime" ).slider({ 
			step: 15,
			min: 15,
			max: 60,
			change: function( event, ui ) {
				var val = jQuery(this).slider( "value" );
				jQuery('.getadvancetime').attr('data-id',val);
				
				if(val<60){
					jQuery('.getadvancetime').html(val+' minuter');
				}
				else{
					jQuery('.getadvancetime').html('1 timme');
				}
			
			} 
		}).sliderAccess({
			touchonly : false
		});
		
		//start time slider
		var startTime;
		var endTime;
		$(".stTime").slider({
			min: 240,
			max: 1620,
			step:60,
			values: 240,
			slide: function(event, ui){
				var val = ui.value;
				sttimeval = val;
				var hours = parseInt(val / 60 % 24, 10);
				var stField = $(".starttime1").val();
				
				if(hours <= 9){
					hours = ("0"+hours).slice(-2);
				}
				st_arr = stField.split(':');
				
				$(".starttime1").val(hours+':'+st_arr[1]);
				
			},
			change: function( event, ui ) {
				
				//added by vaval
				
				var checkme = $('.checkme').attr('data-rel');
				
				if(checkme==0){
				
					var val = ui.value;
					sttimeval = val;
					var hours = parseInt(val / 60 % 24, 10);
					
					var sttime = $('.starttime1').val().toString().split(':');
					var stmin = Number(sttime[1]);
					var sthour = Number(sttime[0]);
					
					
					$(".endTime").slider('option',{min: val, value: val});
				
				
					if(stmin==45){
						stmin=0;
						hours = Number(hours)+1;
					
						checkhour = hours;
						
						if(checkhour==0){
							checkhour = 24;
						}
						else if(checkhour==1){
							checkhour = 25;
						}
						else if(checkhour==2){
							checkhour = 26;
						}
						else if(checkhour==3){
							checkhour = 27;
						}
						
						$(".endTime").slider('option',{min:(checkhour*60), value: (checkhour*60)});
						
					}
					else{
						stmin=stmin+15;
					}
					
					if(hours <= 9){
						
						hours = ("0"+hours).slice(-2);
					}
					
					if(hours==24){
						hours='00';
					}
					
					if(stmin==0){
						stmin='00';
					}
					
					
					$(".endtime1").val(hours+':'+stmin);
				
				}
				
			}
		}).sliderAccess({
			touchonly : false
		});
		$(".stMin").slider({
			min: 0,
			max: 45,
			step:15,
			slide: function(event, ui){
				var minutes = ui.value;
				var stField = $(".starttime1").val();
				
				if(minutes <= 9){
					minutes = ("0"+minutes).slice(-2);
				}
				st_arr = stField.split(':');
				
				$(".starttime1").val(st_arr[0]+':'+minutes);	
			},
			change: function( event, ui ) {
		
				//added by vaval
				
				var checkme = $('.checkme').attr('data-rel');
				
				if(checkme==0){
				
					var val = Number(ui.value);
					
					var sttime = $('.starttime1').val().toString().split(':');
					var sthour = Number(sttime[0]);
					
					$(".endTime").slider('option',{min:(sthour*60), value:(sthour*60)});
					
					stmin = val;
					
					if(val==45){
						stmin=0;
						sthour = sthour+1;
						
						checkhour = sthour;
						
						if(checkhour==0){
							checkhour = 24;
						}
						else if(checkhour==1){
							checkhour = 25;
						}
						else if(checkhour==2){
							checkhour = 26;
						}
						else if(checkhour==3){
							checkhour = 27;
						}
						
						$(".endTime").slider('option',{min:(checkhour*60), value: (checkhour*60)});
						
					}
					else{
						stmin=stmin+15;
					}
					
					//$(".endMin").slider('option',{min: stmin});
					
					$(".endMin").slider('value',stmin);
					
					
					if(sthour <= 9){
						sthour = ("0"+sthour).slice(-2);
					}
					
					if(stmin==0){
						stmin='00';
					}				
				
					if(sthour==24){
						sthour='00';
					}
					
					$(".endtime1").val(sthour+':'+stmin);
				
				}
			
			}
		}).sliderAccess({
			touchonly : false
		});
		
		//end time slider 
		$(".endTime").slider({
			min: 240,
			max: 1620,
			step:60,
			values: 240,
			slide: function(event, ui){
				
				var val = ui.value;
				var hours = parseInt(val / 60 % 24, 10);
				var stField = $(".endtime1").val();
				
				if(hours <= 9){
					hours = ("0"+hours).slice(-2);
				}
				st_arr = stField.split(':');
				
				$(".endtime1").val(hours+':'+st_arr[1]);
			}
		}).sliderAccess({
			touchonly : false
		});
		$(".endMin").slider({
			min: 0,
			max: 45,
			step:15,
			slide: function(event, ui){
				var minutes = Number(ui.value);
				var stField = $(".endtime1").val();
				
				if(minutes <= 9){
					minutes = ("0"+minutes).slice(-2);
				}
				st_arr = stField.split(':');
				
				$(".endtime1").val(st_arr[0]+':'+minutes);
				
			}
		}).sliderAccess({
			touchonly : false
		});
		
		$('.starttime1').click(function(){
			var vfield = $('.starttime1').val();
			var field_arr = vfield.split(':');
			$(".stTime").slider('value', field_arr[0]*60);
			$(".stMin").slider('value', field_arr[1]);
			$('.sttimeslider').slideDown();
		}).keyup(function(){
			var vfield = $('.starttime1').val();
			var field_arr = vfield.split(':');
			$(".stTime").slider('value', field_arr[0]*60);
			$(".stMin").slider('value', field_arr[1]);
		});
		
		
		$('.endtime1').click(function(){
			var vfield = $('.endtime1').val();
			var field_arr = vfield.split(':');
			$(".endTime").slider('value', field_arr[0]*60);
			$(".endMin").slider('value', field_arr[1]);
			$('.endtimeslider').slideDown();
		}).keyup(function(){
			var vfield = $('.endtime1').val();
			var field_arr = vfield.split(':');
			$(".endTime").slider('value', field_arr[0]*60);
			$(".endMin").slider('value', field_arr[1]);	
		});
		
		//closing picker slider
		$('.sttimeslider .xclose').click(function(){
			$('.sttimeslider').slideUp();
		});
		$('.endtimeslider .xclose').click(function(){
			$('.endtimeslider').slideUp();
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
				jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Please specify the Start Date');
			}
			
		});
		
		jQuery('.catering-settings-btn').click(function(){
			
			var starttime=jQuery('.starttime1').val();
			var endtime=jQuery('.endtime1').val();
			var thedays=jQuery('.thedays:checked').length;
			var startdate=jQuery('.startdate').val();
			var enddate=jQuery('.enddate').val();
			var advance=Number(jQuery('.advance').val());
		
			var days='';
			jQuery('.thedays:checked').each(function() {
				days+=this.value+", ";
			});
			
			days=days.substr(0,days.length-2);
			
			jQuery('.displaymsg').fadeOut('slow');
			
			
			var id = jQuery(this).attr('data-rel');
			
			var theurl='actions/add-catering-settings.php';
			var msg = 'saved.';
			
			if(id!=''){
				theurl='actions/edit-catering-settings.php';
				msg = 'updated.';
			}
			
		
			if(starttime!='' & endtime!='' & startdate!='' & enddate!='' & thedays>0){
				
				jQuery.ajax( {
						url: theurl,
						type: 'POST',
						data: 'starttime='+encodeURIComponent(starttime)+'&endtime='+ encodeURIComponent(endtime)+'&days='+ encodeURIComponent(days)+'&startdate='+ encodeURIComponent(startdate)+'&enddate='+ encodeURIComponent(enddate)+'&advance='+ encodeURIComponent(advance)+'&id='+ encodeURIComponent(id),
						success: function(value) {
							jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Catering Settings successfully '+msg);
					
							 setTimeout("window.location.reload();", 2000);
						}
				});
				
			}
			else{
				jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fields with asterisk are required.');
			}
		
		
		});
		
		//for data-table * catering settings
		jQuery('#thecateringsettings').dataTable( {
			"aaSorting": [[ 0, "asc" ]],
			"iDisplayLength" : 100,
			"oLanguage": {
					"sUrl": "scripts/datatable-swedish.txt"
			}
		} );
		
		
		jQuery('.delete-catering-settings').click(function(e){
		
			/* Prevent default actions */
			e.preventDefault();
			e.stopPropagation();
			
			jQuery('.fade, .modalbox').fadeIn();
			gotoModal();
	
			gotoModal();
			
			var id=jQuery(this).attr('data-rel');
			jQuery('.delete-catering-settings-box input').attr('data-rel',id);
			
			
		});
	
		jQuery('.delete-catering-settings-box input[type=button]').click(function(e){
			
			/* Prevent default actions */
			e.preventDefault();
			e.stopPropagation();
			
			var id=jQuery(this).attr('data-rel');
			
			jQuery.ajax({
				 url: "actions/delete-catering-settings.php",
				 type: 'POST',
				 data: 'id='+encodeURIComponent(id),
				 success: function(value){
					 
					 jQuery('.fade, .modalbox, .gradeX-'+id).fadeOut();
					 setTimeout("window.location.reload()",1000);
				 }
			});
			
		});
		
		
		//setting the current setting schedule
		setInterval(function() {

            jQuery.ajax({
				 url: "actions/current-catering-schedule.php",
				 type: 'POST',
				 success: function(value){
				
					jQuery('.gradeX').removeClass('current-settings');
					jQuery('.gradeX-'+value).addClass('current-settings');
					
				 }
			});

        }, 2000);
		
	
	});
	
	function copySettings(startdate, enddate, days, starttime, endtime, advance){
		
		jQuery('.checkme').attr('data-rel',1);
		
		jQuery('.startdate').val(startdate);
		jQuery('.enddate').val(enddate);
		jQuery('.starttime1').val(starttime);
		jQuery('.endtime1').val(endtime);
		jQuery('.advance').val(advance);
		
		var sttime = starttime.split(":");
		var sthour = Number(sttime[0]);
		var stmin = Number(sttime[1]);
		
		jQuery(".stTime").slider('value', (sthour*60));
		jQuery(".stMin").slider('value', stmin);
	
		if(stmin==45){
			
			stmin = 0;
			sthour = Number(sthour)+1;
			
		}
		else{
			stmin=stmin+15;
		}
		
		checkhour = sthour;
			
		if(checkhour==0){
			checkhour = 24;
		}
		else if(checkhour==1){
			checkhour = 25;
		}
		else if(checkhour==2){
			checkhour = 26;
		}
		else if(checkhour==3){
			checkhour = 27;
		}
		
		jQuery(".endTime").slider('option',{min:(checkhour*60)});
		
		//jQuery(".endMin").slider('option',{min:Number(stmin)});
		
		
		var edtime = endtime.split(":");
		var edhour = edtime[0];
		var edmin = edtime[1];
		
		edcheckhour = edhour;
			
		if(edcheckhour==0){
			edcheckhour = 24;
		}
		else if(edcheckhour==1){
			edcheckhour = 25;
		}
		else if(edcheckhour==2){
			edcheckhour = 26;
		}
		else if(edcheckhour==3){
			edcheckhour = 27;
		}
		
		jQuery(".endTime").slider('value', (Number(edcheckhour)*60));
		jQuery(".endMin").slider('value', edmin);
		
		jQuery('.thedays').prop('checked',false);
		
		jQuery('.thedays').each(function(){
            var val=jQuery(this).val();
			
			var hold=days.split(", ");
			
			for(i=0;i<hold.length;i++){
			
				if(val==hold[i]){
					jQuery(this).prop('checked',true);
				}
			}
			
        });
		
	}
</script>
<input type="hidden" class="checkme" data-rel="0">
<div class="page menu-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                	echo 'Inställningar - Catering';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
        <div class="page-header-right"></div>
        <!-- end .page-header-right -->
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
    
    	<h3 class="setting-title"></h3>
    
    	<table>
        	<tr>
            	<td><font style="font-size:10px; color:red;">*</font>Startdatum :</td>
                <td><input type="text" class="startdate txt" style="width:295px;"></td>
            </tr>
            <!--<tr>
            	<td>Tills vidare :</td>
                <td><input type="checkbox" class="untildate" id="untildate" data-rel="<?php //echo $row['future'];?>"> <label for="untildate" style="font-size:11px;"><span style="color:red;">OBS:</span> Gäller 100år framåt.</label></td>
            </tr>-->
            <tr>
            	<td><font style="font-size:10px; color:red;">*</font>Slutdatum :</td>
                <td><input type="text" class="enddate txt" style="width:295px;"></td>
            </tr>
        	<tr>
            	<td><font style="font-size:10px; color:red;">*</font>Dagar :</td>
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
            	<td valign="top"><font style="font-size:10px; color:red;">*</font>Starttid :</td>
                <td><div><input type="text" class="starttime1 txt" value="04:00" style="width:295px;"></div>
                	<div class="sttimeslider">
                        <div><label>Timme</label><br /><span class="ui_slide stTime"></span></div>
                        <div><label>Minut</label><br /><span class="ui_slide stMin"></span></div>
                        <div class="ui_close"><a class="xclose">stäng</a></div>
                    </div>
                </td>
            </tr>
            <tr>
            	<td valign="top"><font style="font-size:10px; color:red;">*</font>Sluttid :</td>
                <td><div><input type="text" class="endtime1 txt" value="03:00" style="width:295px;"></div>
                	<div class="endtimeslider">
                        <div><label>Timme</label><br /><span class="ui_slide endTime"></span></div>
                        <div><label>Minut</label><br /><span class="ui_slide endMin"></span></div>
                        <div class="ui_close"><a class="xclose">stäng</a></div>
                    </div>
                </td>
            </tr>
            <tr>
            	<td>Minimum Days <br>to Order :</td>
                <td><input type="text" class="advance txt" style="width:295px;"></td>
            </tr>
            <tr>
            	<td colspan="2" align="right" class="foreditsettings"><input type="button" class="btn catering-settings-btn" value="Utför" data-rel=""></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
        
        <br>
        <!-- settings output -->
        
        <?php
        		$currentDate = date("m/d/Y");
				$dayName=date('D', strtotime($currentDate));
				
				//get the current time
				$t=time();
				$currentTime=date("Hi",$t);
				
				
				$q=mysql_query("select id from catering_settings where DATE_FORMAT(STR_TO_DATE('".$currentDate."', '%m/%d/%Y'), '%Y-%m-%d') >= DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') and DATE_FORMAT(STR_TO_DATE('".$currentDate."', '%m/%d/%Y'), '%Y-%m-%d') <= DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') and days like '%".$dayName."%' and deleted=0 order by id desc limit 1") or die(mysql_error());
				
				$rs=mysql_fetch_assoc($q);
				$currentID = $rs['id'];
			

		?>
        
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="thecateringsettings">
                <thead>
                    <tr>
                    	<th>Startdatum</th>
                        <th>Slutdatum</th>
                        <th>Dagar</th>
                        <th>Starttid</th>
                        <th>Sluttid</th>
                        <th>Minimum Days to Order</th>
                        <th>Åtgärd</th>
                    </tr>
                </thead>
                <tbody>
					<?php
					
						
                        $q=mysql_query("select *, date_format(STR_TO_DATE(end_date, '%m/%d/%Y'),'%b %d, %Y') as enddate  from catering_settings where deleted=0") or die(mysql_error());
   
                        while($r=mysql_fetch_assoc($q)){
                        
                    ?>
                        <tr class="gradeX gradeX-<?php echo $r['id'];?> <?php if($currentID==$r['id']) echo 'current-settings';?>" align="center">
                        	<td><?php echo date("M d, Y",strtotime($r['start_date'])); ?></td>
                            <td><?php echo $r['enddate']; ?></td>
                            <td><?php echo replaceDayname($r['days']);?></td>
                            <td><?php echo $r['start_time'];?></td>
                            <td><?php echo $r['end_time'];?></td>
                            <td><?php echo $r['minimum_number_days']; ?></td>
                            <td>
                            	<a href="javascript:void" class="copy-setting" title="Kopiera" onclick="copySettings('<?php echo $r['start_date']; ?>','<?php echo $r['end_date']; ?>','<?php echo $r['days']; ?>','<?php echo $r['start_time']; ?>','<?php echo $r['end_time']; ?>','<?php echo $r['minimum_number_days']; ?>'); jQuery('.catering-settings-btn').attr('data-rel',''); jQuery('.setting-title').html('Kopiera inställning');"><img src="images/copy.png" alt="Kopiera"></a>
                               
                               <a href="javascript:void" class="edit-setting" title="Redigera" onclick="copySettings('<?php echo $r['start_date']; ?>','<?php echo $r['end_date']; ?>','<?php echo $r['days']; ?>','<?php echo $r['start_time']; ?>','<?php echo $r['end_time']; ?>','<?php echo $r['minimum_number_days']; ?>'); jQuery('.catering-settings-btn').attr('data-rel','<?php echo $r['id'];?>'); jQuery('.setting-title').html('Redigera inställning');"><img src="images/edit.png" alt="Redigera"></a>
                             
                               <a href="javascript:void" class="delete-catering-settings" title="Radera" data-rel="<?php echo $r['id']; ?>" onclick="jQuery('.catering-settings-btn').attr('data-rel','');"><img src="images/delete.png" alt="Radera"></a>
                             
                            </td>
                        </tr>
                    <?php		
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                    	<th>Startdatum</th>
                        <th>Slutdatum</th>
                        <th>Dagar</th>
                        <th>Starttid</th>
                        <th>Sluttid</th>
                        <th>Minimum Days to Order</th>
                        <th>Åtgärd</th>
                    </tr>
                </tfoot>
            </table>
        <div class="clear"></div>
        <p style="margin:20px 0 0;"><font style="color: #e67e22;">OBS :</font> Aktuell bordsinställning visas med grön bakgrund. .</p>
    </div>
</div>

<div class="fade"></div>
<div class="delete-catering-settings-box modalbox">
	<h2>Bekräfta borttagning<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Vill du fortsätta?</p>
        <input type="button" value="Utför">
    </div>
</div>