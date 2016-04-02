<?php
 include_once('redirect.php');
 
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
			step: 0.5,
			min: 1,
			max: 4,
			change: function( event, ui ) {
				var val = jQuery(this).slider( "value" );
				jQuery('.getadvancetime').attr('data-id',val);
				
				var dot = val.toString().split('.');
				if(dot.length>1){
					var hh=dot[0];
					var mm=dot[1];
					
					jQuery('.getadvancetime').html(hh+' timme '+(mm*6)+' minut');
				}
				else{
					jQuery('.getadvancetime').html(val+' timme');
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
				//alert(ui.value);
				//$(".endTime").slider('value', ui.value);
				
				$(".endTime").slider('option',{min: ui.value});
				
				var stField = $(".starttime1").val();
				st_arr = stField.split(':');
				
				var endField = $(".endtime1").val();
				end_arr = endField.split(':');
				
				
				var ennn = $(".endTime").slider("values" , 0);
				
				var stField = $(".starttime1").val();
				
				st_arr = stField.split(':');
				var minsss = 0;
				if(st_arr[1]<45){
					minsss = ("0"+(Number(st_arr[1])+15)).slice(-2);
				}
				//if(ui.value > ennn){
					$(".endtime1").val(st_arr[0]+':'+minsss);
				//}
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
				var val = ui.value;				
				
				var stField = $(".starttime1").val();
				
				st_arr = stField.split(':');
				
				if(val<45){
					val = val+15;
					minsss = ("0"+(Number(st_arr[1])+15)).slice(-2);
				}
				
				$(".endMin").slider('value', val);
				$(".endtime1").val(st_arr[0]+':'+minsss);
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
				var minutes = ui.value;
				var stField = $(".endtime1").val();
				
				if(minutes <= 9){
					minutes = ("0"+minutes).slice(-2);
				}
				st_arr = stField.split(':');
				
				$(".endtime1").val(st_arr[0]+':'+minutes);
				
				//for checking minutes
				var stField = $(".starttime1").val();
				var endField = $(".endtime1").val();
				
				st_arr = stField.split(':');
				end_arr = endField.split(':');
				
				if(st_arr[0] == end_arr[0] ){
					if(st_arr[1] == ui.value){
						$(this).slider("option","min",Number(ui.value)+15);
					}
				}else{
					$(this).slider("option","min",0);
				}
			},
			change: function(event, ui){
				var stField = $(".starttime1").val();
				var endField = $(".endtime1").val();
				
				st_arr = stField.split(':');
				end_arr = endField.split(':');
				
				if(st_arr[0] == end_arr[0] ){
					if(st_arr[1] == ui.value){
						$(this).slider("option","min",Number(ui.value)+15);
					}
				}else{
					$(this).slider("option","min",0);
				}
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
		
	
		jQuery('.takeaway-settings-page .typeselection span').click(function(){
			var id=this.id;
			
			jQuery('.setting-title').html('');
			
			jQuery('.typeselection span').removeClass('active');
			
			jQuery(this).addClass('active');
			
			jQuery('.tas').hide();
			
			jQuery('.'+id).fadeIn();
			
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
		
		jQuery('.takeaway-settings-btn').click(function(){
			
			var starttime=jQuery('.starttime1').val();
			var endtime=jQuery('.endtime1').val();
			var thedays=jQuery('.thedays:checked').length;
			var startdate=jQuery('.startdate').val();
			var enddate=jQuery('.enddate').val();
			var getadvancetime = jQuery('.getadvancetime').attr('data-id');
		
			var days='';
			jQuery('.thedays:checked').each(function() {
				days+=this.value+", ";
			});
			
			days=days.substr(0,days.length-2);
			
			jQuery('.displaymsg').fadeOut('slow');
			
			
			var id = jQuery(this).attr('data-rel');
			
			var theurl='actions/add-lunchmeny-settings.php';
			var msg = 'saved.';
			
			if(id!=''){
				theurl='actions/edit-lunchmeny-settings.php';
				msg = 'updated.';
			}
			
		
			if(starttime!='' & endtime!='' & startdate!='' & enddate!='' & thedays>0){
				
				jQuery.ajax( {
						url: theurl,
						type: 'POST',
						data: 'starttime='+encodeURIComponent(starttime)+'&endtime='+ encodeURIComponent(endtime)+'&days='+ encodeURIComponent(days)+'&startdate='+ encodeURIComponent(startdate)+'&enddate='+ encodeURIComponent(enddate)+'&id='+ encodeURIComponent(id)+'&advancetime='+ encodeURIComponent(getadvancetime),
						success: function(value) {
							//alert(value);
							if(value=='Exist'){
								jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html(value);
							}else{
								jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Inställningen har sparats');
								setTimeout("window.location='?page=lunchmeny-settings&parent=lunchmeny'", 2000);
							}
							
					
							
						}
				});
				
			}
			else{
				jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fyll i obligatoriska fält.');
			}
		
		
		});
		
		//for data-table * catering settings
		jQuery('#thetakeawaysettings').dataTable( {
			"aaSorting": [[ 1, "asc" ]],
			"iDisplayLength" : 100,
			"oLanguage": {
					"sUrl": "scripts/datatable-swedish.txt"
			}
		} );
		
		
		jQuery('.delete-catering-settings').click(function(e){
		
			/* Prevent default actions */
			e.preventDefault();
			e.stopPropagation();
			
			jQuery('.fade, .delete-catering-settings-box').fadeIn();
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
				 url: "actions/delete-lunchmeny-settings.php",
				 type: 'POST',
				 data: 'id='+encodeURIComponent(id),
				 success: function(value){
					 
					 jQuery('.fade, .modalbox, .gradeX-'+id).fadeOut();
					  setTimeout("window.location='?page=lunchmeny-settings&parent=lunchmeny'", 1000);
				 }
			});
			
		});
		
		
		//setting the current setting schedule
		setInterval(function() {
            jQuery.ajax({
				 url: "actions/current-lunchmeny-schedule.php",
				 type: 'POST',
				 success: function(value){
				
					value = value.trim();
				
					//jQuery('.ta1 .gradeX').removeClass('current-settings');
					//jQuery('.ta1 .gradeX-'+value).addClass('current-settings');
					//jQuery('.ta1 .actives').html('');
					//jQuery('.ta1 .active'+value).html('<img src="images/activesettings.png">');
					
				 }
			});
        }, 2000);
		
	
	});
	
	function copySettings(startdate, enddate, days, starttime, endtime, advancetime){
		
		jQuery('.startdate').val(startdate);
		jQuery('.enddate').val(enddate);
		jQuery('.starttime1').val(starttime);
		jQuery('.endtime1').val(endtime);
		jQuery('.advancetime').slider('value', advancetime );
		jQuery('.getadvancetime').attr('data-id',advancetime);
		
		var advt = advancetime.toString().split('.');
		if(advt.length>1){
			jQuery('.getadvancetime').html(advt[0]+' timme '+(Number(advt[1])*6)+' minut');
		}
		else{
			jQuery('.getadvancetime').html(advancetime+' timme');	
		}
		
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
<div class="page takeaway-settings-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                	
					echo 'Inställningar';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
      
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
    	<div class="typeselection">
        	<span id="ta1" <?php if(!isset($_GET['tab'])){ echo 'class="active"'; }?>>Lunch Meny</span>
           <!-- <span id="ta2">Order Status</span>-->
            <span id="ta3" <?php if(isset($_GET['tab'])){ echo 'class="active"'; }?>>Menypresentation</span>
            <?php /*?><span id="ta4" <?php if(isset($_GET['tab'])){ echo 'class="active"'; }?>>Påminnelseinställningar</span><?php */?>
        </div>
        
        
        <div class="ta1 tas" <?php if(isset($_GET['tab'])){ echo 'style="display:none;"';} else echo 'style="display:block;"';?>>
        
        	<h3 class="setting-title"></h3>
        
        	<table style="float: left;">
        	<tr>
            	<td><font style="font-size:10px; color:red;">*</font>Startdatum :</td>
                <td><input type="text" class="startdate txt" style="width:295px;"></td>
            </tr>
            <tr>
            	<td>Tills vidare :</td>
                <td><input type="checkbox" class="untildate" id="untildate" data-rel="<?php echo $row['future'];?>"> <label for="untildate" style="font-size:11px;"><span style="color:red;">OBS:</span> Gäller 100år framåt.</label></td>
            </tr>
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
            	<td><font style="font-size:10px; color:red;">*</font>Annan tid :</td>
                <td style="padding: 5px 0;">
                    <span class="advancetime" style="float: left; margin: 8px 0 0 0; display:inline-block;"></span>
                    <label class="getadvancetime" data-id="1" style="float: right; width: 98px; margin-top: 8px;">1 timme</label>
                </td>
            </tr>
            <tr>
            	<td colspan="2" align="right" class="foreditsettings"><input type="button" class="btn takeaway-settings-btn" value="Utför" data-rel=""></td>
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
			
				$q=mysql_query("select id from lunchmeny_settings where DATE_FORMAT(STR_TO_DATE('".$currentDate."', '%m/%d/%Y'), '%Y-%m-%d') >= DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') and DATE_FORMAT(STR_TO_DATE('".$currentDate."', '%m/%d/%Y'), '%Y-%m-%d') <= DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') and days like '%".$dayName."%' and deleted=0 and ".$currentTime.">=(replace(start_time,':','')) and ".$currentTime."<=(replace(end_time,':','')) order by id desc limit 1") or die(mysql_error());
				
				$rs=mysql_fetch_assoc($q);
				$currentID = $rs['id'];
			
		?>
        
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="thetakeawaysettings">
                <thead>
                    <tr>
                    	<!--<th>Aktiv</th>-->
                    	<th>Startdatum</th>
                        <th>Slutdatum</th>
                        <th>Dagar</th>
                        <th>Starttid</th>
                        <th>Sluttid</th>
                        <th>Annan tid</th>
                        <th>Åtgärd</th>
                    </tr>
                </thead>
                <tbody>
					<?php
					
						
                        $q=mysql_query("select *, date_format(STR_TO_DATE(end_date, '%m/%d/%Y'),'%b %d, %Y') as enddate  from lunchmeny_settings where deleted=0") or die(mysql_error());
   
                        while($r=mysql_fetch_assoc($q)){
                        
                    ?>
                        <tr class="gradeX gradeX-<?php echo $r['id'];?> <?php //if($currentID==$r['id']) echo 'current-settings';?>" align="center">
                        	<?php /*?><td class="actives active<?php echo $r['id'];?>">
                            	<?php
                                	if($currentID == $r['id']){
								?>
                                	<img src="images/activesettings.png">
                                <?php	
									}
								?>
                            </td><?php */?>
                        	<td><?php echo date("M d, Y",strtotime($r['start_date'])); ?></td>
                            <td><?php echo $r['enddate']; ?></td>
                            <td><?php echo replaceDayname($r['days']);?></td>
                            <td><?php echo $r['start_time'];?></td>
                            <td><?php echo $r['end_time'];?></td>
                            <td><?php 
									$adv = explode('.',$r['advance_time']);
									if(count($adv)>1){
										echo $adv[0].' timme '.($adv[1]*6).' minut';
									}
									else{
										echo $r['advance_time'].' timme';
									}
								?>
                             </td>
                            <td>
                            	<a href="javascript:void" class="copy-setting" title="Kopiera" onclick="copySettings('<?php echo $r['start_date']; ?>','<?php echo $r['end_date']; ?>','<?php echo $r['days']; ?>','<?php echo $r['start_time']; ?>','<?php echo $r['end_time']; ?>','<?php echo $r['advance_time']; ?>'); jQuery('.takeaway-settings-btn').attr('data-rel',''); jQuery('.setting-title').html('Kopiera inställning');"><img src="images/copy.png" alt="Kopiera"></a>
                               
                               <a href="javascript:void" class="edit-setting" title="Redigera" onclick="copySettings('<?php echo $r['start_date']; ?>','<?php echo $r['end_date']; ?>','<?php echo $r['days']; ?>','<?php echo $r['start_time']; ?>','<?php echo $r['end_time']; ?>','<?php echo $r['advance_time']; ?>'); jQuery('.takeaway-settings-btn').attr('data-rel','<?php echo $r['id'];?>'); jQuery('.setting-title').html('Redigera inställning');"><img src="images/edit.png" alt="Redigera"></a>
                             
                               <a href="javascript:void" class="delete-catering-settings" title="Radera" data-rel="<?php echo $r['id']; ?>" onclick="jQuery('.takeaway-settings-btn').attr('data-rel','');"><img src="images/delete.png" alt="Radera"></a>
                             
                            </td>
                        </tr>
                    <?php		
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                    	<!--<th>Aktiv</th>-->
                    	<th>Startdatum</th>
                        <th>Slutdatum</th>
                        <th>Dagar</th>
                        <th>Starttid</th>
                        <th>Sluttid</th>
                        <th>Annan tid</th>
                        <th>Åtgärd</th>
                    </tr>
                </tfoot>
            </table>
        <div class="clear"></div>
       <!-- <p style="margin:20px 0 0;"><font style="color: #e67e22;">OBS :</font> Aktuell inställning visas med grön bakgrund.</p>-->
        
        
        </div>
        <!-- end .ta1 -->
        
        <div class="ta2 tas" style="display:none;">
        
        	<div class="newadd">
            	<a href="?page=order-status&subpage=add-order-status&parent=orders">Skapa ny</a>
            </div>
        	<div class="clear"></div>
        
       		<table cellpadding="0" cellspacing="0" border="0" class="display" id="theorderstatus">
            <thead>
                <tr>
                    <th>Beskrivning</th>
                    <!--<th>Typ</th>-->
                    <th>Åtgärd</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $q=mysql_query("select s.id,s.description,r.description from status as s, reservation_type as r where r.id=s.reservation_type_id and deleted=0 and s.reservation_type_id = 2") or die(mysql_error());
                    
                    while($r=mysql_fetch_array($q)){
                ?>
                    <tr class="gradeX gradeX-<?php echo $r[0];?>" align="center">
                        <td><?php 
							if(strcasecmp(ucwords($r[1]),'approve')==0){
								echo 'Godkänd beställning';
							}elseif(strcasecmp(ucwords($r[1]),'cancel')==0){
								echo 'Ej godkänd beställning';
							}else{
								echo ''.ucwords($r[1]);
							}
						?></td>
                       <?php /*?> <td><?php echo ucwords($r[2]);?></td><?php */?>
                        <td>
							<?php //12, 14, 13
									//$r[0]!=8 & $r[0]!=9 & $r[0]!=10 & $r[0]!=11 & $r[0]!=12 & $r[0]!=2 & $r[0]!=6& $r[0]!=13 & $r[0]!=14
							?>
							<?php if($r[0]!=12 & $r[0]!=14 & $r[0]!=13){ ?>                            <a href="?page=order-status&subpage=edit-order-status&id=<?php echo $r[0]; ?>&parent=orders" class="edit-order-status" title="Redigera Order Status"><img src="images/edit.png" alt="Redigera Order Status"></a> <?php /*?><?php if($_SESSION['login']['type']<3){ ?><a href="javascript:void" class="delete-order-status" title="Radera Order Status" data-rel="<?php echo $r[0]; ?>"><img src="images/delete.png" alt="Radera Order Status"></a><?php } ?><?php */?>
                            <?php }
								else{
									echo '-';
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
                    <th>Beskrivning</th>
                    <!--<th>Typ</th>-->
                    <th>Åtgärd</th>
                </tr>
            </tfoot>
        </table>
        </div>
        <!-- end .ta2 -->
        
        <div class="ta3 tas" <?php if(!isset($_GET['tab'])){ echo 'style="display:none"'; } else{ echo 'style="display:block;"'; }?>>
        
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
        	window.location='?page=lunchmeny-settings&parent=lunchmeny&tab=2';
        </script>
        <?php	
		
		}
			
		?>
				<style>
				.ta3 fieldset { 
					border:1px solid #e67e22; 
					padding:24px;
				}
				
				.ta3 legend {
				  padding: 0.2em 0.5em;
				  border:1px solid e67e22;
				  color:#eee;
				  font-size:14px;;
				  text-align:left;
				  background-color:#e67e22;
				  }		
				  
				  .ta3 .left_name{ 
					display: inline-block;
					width:100px;
					float:left;
					text-align:right;
					padding: 4px 12px 0px 0px;
				  }
				  
				  .ta3 input{
					  width:250px;
				  }
				  
				  span.advancetime.ui-slider.ui-slider-horizontal.ui-widget.ui-widget-content.ui-corner-all{
					  width: 141px !important;
				  }
				  </style>
			
			<form method="post">
                
                <fieldset>
                    <legend>
                        Menypresentation för Lunchmeny
                    </legend>
                    
                    <div class="left_name"><!--Text:--></div>
                    <div class="right_input">
                        <textarea class="takeaway_content" rows="20" cols="100" name="lunchmeny_content"><?php echo $settings['lunchmeny_content']; ?></textarea>
                    </div> 
                    
                </fieldset>
        
              
              <br>
              <input type="submit" class="btn" name="save_gen_settings" value="Spara ändringar" />
              </form>
        
        </div>
        <!--ent tab 3-->
        
        <!--tab 4-->
         <div class="ta4 tas" <?php if(!isset($_GET['tab'])){ echo 'style="display:none"'; } else{ echo 'style="display:block;"'; }?>>
            	<?php
                	$settings_sql = "SELECT * FROM settings";
					$settings_qry = mysql_query($settings_sql);
					
					while($settings_res = mysql_fetch_assoc($settings_qry)){
						$settings[$settings_res['var_name']] = $settings_res['var_value'];
					}
										
				?>               
                <table style="float: left;">
                    <tr>
                        <td><font style="font-size:10px; color:red;">*</font>Minuter :</td>
                        <td><input type="text" class="menu-price txt minuter" value="<?php echo $settings['reminder_minutes']; ?>"></td>
                        <td><input type="button" class="btn save-reminder" value="Utför" data-rel=""></td>
                    </tr>
                </table>
                <div class="displaymsg"></div>
                <script>
                	$(document).ready(function(e) {
                        $('.save-reminder').click(function(){
							jQuery('.displaymsg').hide().removeClass('successmsg errormsg');
							var minuter = $('.minuter').val();
							//if(minuter>0){
								jQuery.ajax({
									 url: "actions/minute-setting.php",
									 type: 'POST',
									 data: {minuter:minuter},
									 success: function(value){
										 if(value.trim()=='success'){
											jQuery('.displaymsg').fadeIn('fast').addClass('successmsg').html('Ändringar har sparats.');
										 }else{
											jQuery('.displaymsg').fadeIn('fast').addClass('errormsg').html('Minuter Settings failed.'+value); 
										 }
									 }
								});
							//}else{
							//	jQuery('.displaymsg').fadeIn('fast').addClass('errormsg').html('Fyll i rimider minuter.'); 
							//}
						});
                    });;
                </script>
         </div>
        <!--ent tab 4-->   
        
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
<div class="delete-order-status-box modalbox">
	<h2>Confirm Delete<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Are you sure you want to proceed?</p>
        <input type="button" value="Delete">
    </div>
</div>