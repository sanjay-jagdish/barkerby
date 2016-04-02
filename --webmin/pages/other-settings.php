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
	
		jQuery('.other-settings-page .typeselection span').click(function(){
			var id=this.id;
			
			jQuery('.typeselection span').removeClass('active');
			
			jQuery(this).addClass('active');
			
			jQuery('.tas').hide();
			
			jQuery('.'+id).fadeIn();
			
		});
		
		
		//added
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
					
					value=value.trim();
					
					jQuery('.gradeX').removeClass('current-settings');
					jQuery('.gradeX-'+value).addClass('current-settings');
					jQuery('.actives').html('');
					jQuery('.active'+value).html('<img src="images/activesettings.png" class="active-settings">');
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

<div class="page other-settings-page">
	<div class="page-header">
    	<div class="page-header-left">
            <h2>
            	<?php
                	
					echo 'Inställningar - Bordsbokning';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
       <!-- <div class="page-header-right">
        	<a href="?page=menu&subpage=add-menu" class="add-menu">Skapa ny</a>
        </div>-->
        <!-- end .page-header-right -->
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
    	<div class="typeselection">
            <span id="ta1" <?php if($_GET['tab']==1 || !isset($_GET['tab'])){ echo 'class="active"'; }?>>Bordsintervaller</span>
            <span id="ta2" <?php if($_GET['tab']==2){ echo 'class="active"'; }?>>Bordsinställning</span>
            <span id="ta3" <?php if($_GET['tab']==3){ echo 'class="active"'; }?>>Bordsplan</span>
            <span id="ta4" <?php if($_GET['tab']==4){ echo 'class="active"'; }?>>Övriga inställningar</span>
        </div>
        
        <div class="ta1 tas" <?php if($_GET['tab']!=1 && isset($_GET['tab'])){ echo 'style="display:none"'; }?>>
        
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
                                <td class="actives active<?php echo $r[3]; ?>">
                                	<?php if($currentID==$r[3]){ ?>
                                		<img src="images/activesettings.png" class="active-settings">
                               		<?php } ?>	 
                                </td>    
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
        
        </div><!-- end .ta1 -->
        
        <div class="ta2 tas" <?php if($_GET['tab']!=2 || !isset($_GET['tab'])){ echo 'style="display:none"'; }?>>
        
        	Fyll i bordsnummer och andtal platser för varje bord.<br />
        <table cellpadding="0" cellspacing="0" border="0" align="left" class="display" id="themasterlist" style="width: 200px; text-align:left;">
            <thead>
                <tr>
                    <th>Bordsnummer</th>
                    <th>Platser</th>
                    <th>Åtgärd</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $q=mysql_query("select * from table_masterlist where deleted=0") or die(mysql_error());
                    
                    while($r=mysql_fetch_assoc($q)){
                ?>
                    <tr class="gradeX gradesX-<?php echo $r['id'];?>" align="center">
                        <td><input type="text" class="txt masterlistname mln<?php echo $r['id'];?>" value="<?php echo ucwords($r['name']);?>" style="text-align:center" data-rel="<?php echo $r['id'];?>" /></td>
                        <td><input type="text" class="txt masterlistseat mls<?php echo $r['id']; ?>" value="<?php echo strtoupper($r['seats']);?>" style="text-align:center" data-rel="<?php echo $r['id'];?>"  /></td>
                        <td><a href="javascript:void(0)" class="delete-table-masterlist" title="Delete Table Masterlist" data-rel="<?php echo $r['id']; ?>"><img src="images/delete.png" alt="Delete Settings"></a>&nbsp;&nbsp;<a href="javascript:void(0)" class="savesettingsbtn" data-id="<?php echo $r['id']; ?>"><img src="images/savebtn.png" alt="Save Settings" style="position: relative; top: -3px;"></a></td>
                    </tr>
                    
                <?php		
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Bordsnummer</th>
                    <th>Platser</th>
                    <th>Åtgärd</th>
                </tr>
            </tfoot>
        </table>
        
        <div style="clear:both;">&nbsp;</div>
        <div class="displaymsg" id="err"></div>
        <div style="clear:both;">&nbsp;</div>

		
		<style>
			.thetablename1{ width:150px !important; } 
			.thetable1{ width:55px !important; }
		</style>
		
        <div class="settable1">
            <strong>Lägg till fler bord:</strong><br />
            <span class="tables1 the-tables1" id="table1-1"><input type="text" class="thetablename1 txt" style="width: 90px; margin-right: 15px;" placeholder="Bordsnummer"><input type="text" class="thetable1 txt" style="margin: 0 10px 0 0;" placeholder="Platser"><a href="javascript:void;" class="add-table1"><img src="images/add-small.png" style="position: relative; top: 7px;"></a></span>
            <div class="settable-inner1">
            
            </div>
            <input type="button" class="btn addmasterlist" value="Utför" style="margin-top:10px;">
        </div>
        <div class="preloader" style="width:25%; text-align:center;"></div>
        
        </div><!-- end .ta2 -->
        
        <div class="ta3 tas" <?php if($_GET['tab']!=3 || !isset($_GET['tab'])){ echo 'style="display:none"'; }?>>
        
        	<?php
            	$qs=mysql_query("select var_value as floorplan from settings where var_name='floor_plan'");
$rs=mysql_fetch_assoc($qs);
			?>
            
            <script type="text/javascript" >
				 jQuery(document).ready(function() { 
						
							jQuery('#floorplanimg').on('change', function()			{ 
							
							
								jQuery("#preview").html('');
								jQuery("#preview").html('<img src="scripts/ajaximage/loader.gif" alt="Uploading...."/>');
								jQuery("#floorplanform").ajaxForm({
									target: '#preview'
								}).submit();
						
							});
							
							jQuery( ".floortable" ).draggable({ 
								containment: ".floorplan-container", 
								scroll: false
							});
						
							
							jQuery('.floorplan-btn').click(function(){
								var count=jQuery('#preview img').length;
								
								jQuery('.displaymsg').fadeOut('slow');
								
								if(count>0){
									var img=jQuery('#preview img').attr('title');
									
									jQuery.ajax({
										 url: "actions/floorplan.php",
										 type: 'POST',
										 data: 'img='+encodeURIComponent(img),
										 success: function(value){
											 jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Floorplan image successfully added.');
								
											setTimeout("window.location.reload()", 2000);
										 }
									});
									
								}
								else{
									jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Upload image first to continue.');
								}
								
							});
							
							
							
							jQuery('.save-floorplan-btn').click(function(){
								
								var check=jQuery('.floortable').length;
								
								if(check>0){
									var pos='';
									jQuery('.floortable').each(function() {
										var thetop=jQuery(this).css('top');
										var theleft=jQuery(this).css('left');
										var id=jQuery(this).attr('id');
										
										pos+=id+"*"+thetop+'/'+theleft+'^';
										
									});
									
									pos=pos.substr(0,pos.length-1);
									
									
									jQuery.ajax({
										 url: "actions/table-position.php",
										 type: 'POST',
										 data: 'pos='+encodeURIComponent(pos),
										 success: function(value){
											 jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Floorplan successfully added.');
								
											setTimeout("window.location='?page=other-settings&parent=bordsbokning&tab=3'", 2000);
										 }
									});
								}
								else{
									jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('It seems you haven\'t configure your table settings.  Please set it first to continue.');
								}
								
								
								
							});
							
							jQuery('.floortable').click(function(e){
						
								/* Prevent default actions */
								e.preventDefault();
								e.stopPropagation();
								
								var id=jQuery(this).attr('id');
								var name=jQuery(this).attr('data-rel');
								
								jQuery('.table-box h2 span').html(name);
								jQuery('.table-box .box-content').html('<span style="text-align:center; display:block;"><img src="images/loader.gif"></span>');
								jQuery('.fade, .table-box').fadeIn();		
								
								jQuery.ajax({
									 url: "actions/table-details.php",
									 type: 'POST',
									 data: 'id='+encodeURIComponent(id),
									 success: function(value){
											jQuery('.table-box .box-content').html(value);		
									 }
								});
								
								
							});
							
							
				  }); 
				</script>
                
                <?php
					if($rs['floorplan']!=''){
				?>
				<style type="text/css">
					.floorplan-container{
						background:url(floorplanuploads/<?php echo $rs['floorplan']; ?>) no-repeat center;
						background-size:100%;
						width:97%;
						/*height:500px;*/
						overflow: hidden;
					}
				
					.item-container{
						width: 87px;
						overflow: visible;
					}
				
					.floortable{
						float: left;
					}
				</style>
				<?php		
					}
				?>
                
                <div class="page-content-left" style="width:56%">
            <table>
                <tr>
                    <td>
                        <form id="floorplanform" method="post" enctype="multipart/form-data" action='scripts/ajaximage/floorplanajax.php'>
                            Ladda upp diit bordsplan : &nbsp; <input type="file" name="floorplanimg" id="floorplanimg" class="txt" />
                        </form>
                    </td>
                </tr>
                <tr>
                	<td align="right">
                    	<input type="button" class="btn floorplan-btn" value="Utför">
                    </td>
                </tr>
            </table>
        </div>
        <div class="page-content-right" style="width:44%;">
        	<div id='preview'></div>
        </div>
        <div class="clear"></div>
        
        <div class="displaymsg"></div>
        
        <div class="clear"></div>
        
        <div class="floorplan-container">
        	<!--<div class="floortable"></div>-->
        	<div class="item-container">
            <?php
				$currentDate = date("m/d/Y");
            	$dayName=date('D', strtotime($currentDate));
				
				$q=mysql_query("select id from restaurant_detail where DATE_FORMAT(STR_TO_DATE('".$currentDate."', '%m/%d/%Y'), '%Y-%m-%d') >= DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') and DATE_FORMAT(STR_TO_DATE('".$currentDate."', '%m/%d/%Y'), '%Y-%m-%d') <= DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') and days like '%".$dayName."%' and deleted=0 order by id desc limit 1") or die(mysql_error());
				
				//$q=mysql_query("select id from restaurant_detail where '".$currentDate."' >= start_date and '".$currentDate."' <= end_date and days like '%".$dayName."%' order by id desc limit 1");
				$rs=mysql_fetch_assoc($q);
				
				if(mysql_num_rows($q) > 0){
				
					$q=mysql_query("select table_name, max_pax, id, position_top, position_left from table_detail where restaurant_detail_id=".$rs['id']." order by table_name");
					
					while($r=mysql_fetch_array($q)){
						$top='auto';
						$left='auto';
						
						if($r[3]!=''){
							$top=$r[3];
						}
						
						if($r[4]!=''){
							$left=$r[4];
						}
						
				 ?>
					<div class="floortable" id="<?php echo $r[2]; ?>" style="top:<?php echo $top; ?>; left:<?php echo $left; ?>" data-rel="<?php echo $r[0];?>"><span><?php echo $r[0];?></span><label><?php echo $r[1];?></label>
                    	<div class="detail-hover">
                        	<h4><?php echo $r[0];?></h4>
                            
                            
                            <?php
                            	$qq=mysql_query("select reservation_id from reservation_table where table_detail_id=".$r[2]);
								
								
								$curTime=date('H:i:s');
								
								$has=0;
								if(mysql_num_rows($qq) > 0){
									while($r=mysql_fetch_assoc($qq)){
										$qqq=mysql_query("select a.email as email, concat(a.fname, ' ',a.lname) as name, a.phone_number as phone_number, r.date as date, r.time as time, r.number_people as people, r.note as note from reservation as r, account as a where a.id=r.account_id and r.deleted=0 and r.id=".$r['reservation_id']." and r.date='".$currentDate."' and '".$curTime."'>= r.time and '".$curTime."'<= addtime(r.time, SEC_TO_TIME(r.duration*60))");
										
										if(mysql_num_rows($qqq) > 0){
										
											while($rr=mysql_fetch_assoc($qqq)){
								?>
										<div class="detail">
											<span class="resv"><strong><?php echo date("M d, Y",strtotime($rr['date'])).' - '.$rr['time']; ?></strong></span>
											<div>
												<ul>
													<li>Customer : <?php echo $rr['name'].' - '.$rr['email'];?></li>
													<li>Phone Number: <?php echo $rr['phone_number'];?></li>
													<li>Number of Guests: <?php echo $rr['people'];?></li>
													<li>Note: <?php echo $rr['note']?></li>
												</ul>    
											</div>
										</div>
								<?php				
											}
												
										}
										else{
											$has=1;
										}
										
									}
								}
								else{
									echo '<p style="margin:5px 0;">No details found.</p>';
								}
								
								if($has==1){
									echo '<p style="margin:5px 0;">No details found.</p>';
								}
							?>
                            
                            
                        </div>    
                    </div>
                   
				 <?php		
					}
				
				}
				else{
				?>
                	
                	<p>Du har ingen bordsintervall inställd för idag. Klicka <a href="?page=tables&parent=settings" style="color:#e67e22;">här</a></p>
                <?php 	
				}
				
			?>
			</div>
        </div>
        <p><font color="#e67e22">OBS:</font> Bordsplanet är baserad på inställd bordsintervall.</p>
        <input type="button" class="btn save-floorplan-btn" value="Spara bordsplan" style="float:right;">
        
        </div><!-- end .ta3 -->
        
        <div class="ta4 tas" <?php if($_GET['tab']!=4 || !isset($_GET['tab'])){ echo 'style="display:none"'; }?>>
        
        	<?php
            	$a_type = $_SESSION['login']['type'];
				$a_id = $_SESSION['login']['id'];
				
				
		$settings_sql = "SELECT * FROM settings";
		$settings_qry = mysql_query($settings_sql);
		
		while($settings_res = mysql_fetch_assoc($settings_qry)){
			$settings[$settings_res['var_name']] = $settings_res['var_value'];
		}
		
				
			
		if(isset($_POST['save_gen_settings'])){
			
			echo '<div style="padding:12px; background-color:#ff9; margin: 50px 0 0;">';
			
			$to_update=0; $updated=0;
			
			foreach($_POST as $field => $value){
				if(trim($value)!=''){
					$to_update++;
					if(mysql_query("UPDATE settings SET var_value='".trim($value)."' WHERE var_name='".$field."'")){
						$updated++;
					}	
				}
			}
		
			if($updated==0){
				echo '<font color="red"><strong>SAVE FAILED</strong>. &nbsp;An error has occurred while trying to save the details.</font>';
			}else{
				if($updated==$to_update){
					echo '<font color="green"><strong>SETTINGS UPDATED</strong>. &nbsp;Settings has been successfully updated.</font>';
				}else{
					echo '<font color="orange"><strong>NOT ALL SETTINGS WERE UPDATED</strong>. &nbsp;Kindly review the details below as some data were not successfuly saved.</font>';
				}	
			}
			echo '</div><br>';
			
			
			
			require 'actions/PHPMailer/PHPMailerAutoload.php';	
						
			$subject = 'SMTP Successfully Updated '.$settings['smtp_user'].' => '.$settings['smtp_from'];
			
			$message = '<b>'.$subject.'</b>';
			
			$mail = new PHPMailer();
			$mail->CharSet = 'UTF-8';
			$mail->isSMTP();
			$mail->SMTPDebug = 0;
			$mail->Host = $settings['smtp_host'];//'smtp.gmail.com';
			$mail->Port = $settings['smtp_port'];
			$mail->SMTPSecure = $settings['smtp_security'];
			$mail->SMTPAuth = true;
			$mail->Username = $settings['smtp_user'];
			$mail->Password = $settings['smtp_pass'];
			$mail->setFrom($settings['smtp_user'], $settings['smtp_from']);
			$mail->Subject = $subject;
			$mail->msgHTML($message);	
			$mail->AddAddress('garcon.test.email@gmail.com', 'Garcon Test Mail');
			if(!$mail->send()){
				echo '<div style="padding:12px; background-color:#ff9; border:red solid thin;">';
				echo '<font color="red"><strong>SMTP Connection Failed:</strong> &nbsp;Something is wrong with the SMTP Settings.  &nbsp;
						Kindly correct this otherwise, the Garcon App will not be able to send out email notifications.</font>';
				echo '</div><br>';
			}
		
		?>
        	<script type="text/javascript">
            	window.location="?page=other-settings&parent=bordsbokning&tab=4";
            </script>
        <?php	
		
		}
		
		
		
			
		?>
				<style>
				.ta4 fieldset { 
					border:1px solid #e67e22; 
					padding:24px;
				}
				
				.ta4 legend {
				  padding: 0.2em 0.5em;
				  border:1px solid e67e22;
				  color:#eee;
				  font-size:14px;;
				  text-align:left;
				  background-color:#e67e22;
				  }		
				  
				  .ta4 .left_name{ 
					display: inline-block;
					width:100px;
					float:left;
					text-align:right;
					padding: 4px 12px 0px 0px;
				  }
				  
				  .ta4 input{
					  width:250px;
				  }
				  </style>
			
			<form method="post">
				<fieldset>
					<legend>
						Max Antal Personer
					</legend>
					
					<div class="left_name">Max:</div>
					<div class="right_input">
						<input type="text" name="max_guest" value="<?php echo $settings['max_guest']; ?>" style="width:30px; text-align:center;" /> <font color="#999999">Max antal personer per boking via hemsida och app.</font>
					</div>
				</fieldset>

                <fieldset>
                    <legend>
                        Book and Order Online Content
                    </legend>
                    
                    <!--<div class="left_name">Title:</div>
                    <div class="right_input"><input type="text" name="new_title" class="new_title" value="<?php //echo $settings['new_title']; ?>" /></div> -->
                    <div class="left_name"><!--Text:--></div>
                    <div class="right_input">
                        <textarea class="new_content" rows="20" cols="100" name="new_content"><?php echo $settings['new_content']; ?></textarea>
                    </div> 
                    
                </fieldset>
                
              
              <br>
              <input type="submit" class="btn" name="save_gen_settings" value="Spara inställningar" />
              </form>
                
        </div><!-- end .ta4 -->
          
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

<div class="delete-table-masterlist-box modalbox">
	<h2>Confirm Delete<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Are you sure you want to proceed?</p>
        <input type="button" value="Delete">
    </div>
</div>

<div class="table-box modalbox" style="top: 60% !important;">
	<h2><span>Table Detail</span><a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        
    </div>
</div>

<script type="text/javascript">

function deleteTables(id){
		
		jQuery('#table1-'+id).fadeOut().removeClass('the-tables1').attr('id','');
		
		
		jQuery('span.the-tables1 .delete-table').each(function(i){
			jQuery(this).attr('onclick','deleteTables('+(i+2)+')');
		});
		
		jQuery('span.the-tables1').each(function(i){
			jQuery(this).attr('id','table1-'+(i+1));
		});
		
	}

jQuery(function(){
	
	jQuery('.masterlistseat, .thetable1').numeric();
	
	jQuery('.add-table1').click(function(){
		
		var table_count=Number(jQuery('span.the-tables1').length);
		
		jQuery('.settable-inner1').append('<span class="tables1 the-tables1" id="table1-'+(table_count+1)+'"><input type="text" class="thetablename1 txt" style="width: 90px; margin-right: 15px;" placeholder="Bordsnummer"><input type="text" class="thetable1 txt" style="margin: 0 10px 0 0;" placeholder="Platser"><a href="javascript:void;" class="delete-table" onclick="deleteTables('+(table_count+1)+')"><img src="images/delete-small.png" style="position: relative; top: 7px;"></a></span><div class="clear"></div>');
		
		jQuery('.thetable1').numeric();
		
	});
	
	
	jQuery('.masterlistseat, .thetable1').keyup(function(){
		jQuery(this).val(jQuery(this).val().replace('.',''));
	});
	
	$('.savesettingsbtn').click(function(){
		var id = $(this).attr('data-id');
		var mln = $('.mln'+id).val();
		var mls = Number($('.mls'+id).val());
		
		jQuery('.displaymsg').fadeOut();
		
		if(mln!='' & mls>0){
			
			jQuery.ajax({
				 url: "actions/update-name-masterlist.php",
				 type: 'POST',
				 data: 'id='+encodeURIComponent(id)+'&mln='+encodeURIComponent(mln)+'&mls='+encodeURIComponent(mls),
				 success: function(value){
					
					value=value.trim();
					
					if(value!='Invalid'){

						jQuery('.mln'+id).removeClass('redborder');	

						jQuery('html, body').animate({
					        scrollTop: jQuery('#err').offset().top
					    }, 500);
	
						jQuery('.displaymsg').fadeIn().addClass('successmsg').html('Table name successfully updated.');
						setTimeout("jQuery('.displaymsg').fadeOut('fast').html('');",2000); 
						
						
					}
					else{

						jQuery('.mln'+id).addClass('redborder');	
						
						jQuery('.displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html('Table name already exists.');

						
						jQuery('html, body').animate({
					        scrollTop: jQuery('#err').offset().top
					    }, 500);
						
					}
					
				 }
			});
			
		}
		else{
			
			jQuery('html, body').animate({
				scrollTop: jQuery('#err').offset().top
			}, 500);
			
			jQuery('.displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html('Table name should not be emtpy and number of seats should be greater than zero(0).');
		}
		
	});
	
	jQuery('.delete-table-masterlist').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .delete-table-masterlist-box').fadeIn();
		gotoModal();
		
		var id=jQuery(this).attr('data-rel');
		jQuery('.delete-table-masterlist-box input').attr('data-rel',id);
		
		
	});
	
	jQuery('.delete-table-masterlist-box input[type=button]').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		var id=jQuery(this).attr('data-rel');
		
		jQuery.ajax({
			 url: "actions/delete-table-masterlist.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){
				 
				 jQuery('.fade, .modalbox, .gradesX-'+id).fadeOut();
				 //setTimeout("window.location.reload()",1000);
			 }
		});
		
	});
	
	jQuery('.addmasterlist').click(function(){
	
		var count=jQuery('.the-tables1 .txt').length;
		var counter=0;
		var pax='';
		var loop=0;
		jQuery('.the-tables1 .txt').each(function(){
			loop+=1;
			if(jQuery(this).val()!=''){
				counter+=1;
				
				var val=jQuery(this).val();
				if(loop%2==0){
					sep='*';
				}
				else{
					sep='^';
				}
				
				pax+=jQuery(this).val()+sep;
			}
		});
	
		if(count==counter){
			pax=pax.substr(0,pax.length-1);
			
			jQuery('.preloader').html('<img src="images/loader.gif" style="margin: 30px 0 0;">');
			jQuery.ajax({
				 url: "actions/add-table-masterlist.php",
				 type: 'POST',
				 data: 'pax='+encodeURIComponent(pax),
				 success: function(value){
					 
					 value=value.trim();

				 	 if(value=='good'){
   						

   						 jQuery('.thetablename1').removeClass('redborder'); 	 

   						 jQuery('.displaymsg').fadeOut();

						 jQuery('.preloader').html('');
						 
						 setTimeout("window.location='?page=other-settings&parent=bordsbokning&tab=2'",1000);
					 }
					 else{

					 	jQuery('.preloader').html('');
					 	var exists=value.split('*');

					 	jQuery('.thetablename1').removeClass('redborder'); 	 

					 	for(i=0;i<exists.length;i++){
					 		jQuery('#table1-'+exists[i]+' .thetablename1').addClass('redborder');
					 	}

					 	jQuery('.displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html("Table(s) already exists.");

					 	jQuery('html, body').animate({
					        scrollTop: jQuery('#err').offset().top
					    }, 500);

					 }
				 }
			});
			
		}
		else{

			jQuery('.displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html("All fields are required.");

						
			jQuery('html, body').animate({
		        scrollTop: jQuery('#err').offset().top
		    }, 500);

		}
	
	});


});
</script>