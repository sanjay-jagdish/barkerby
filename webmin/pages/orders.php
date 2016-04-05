<?php
include_once('redirect.php'); 
session_start();
//for the auto-complete of user accounts signatory

$account_list='';
$assigned_accounts = array();
$qq=mysql_query("select * from account where type_id<>5 and deleted=0");
while($rr=mysql_fetch_assoc($qq)){
	$account_list.='{"id": "'.$rr['id'].'", "name": "'.$rr['fname'].' '.$rr['lname'].'"},';
	$assigned_accounts[$rr['id']] = $rr['fname'].' '.$rr['lname'];
}
$_SESSION['assigned_accounts'] = $assigned_accounts;
?>
<script type="text/javascript">
	jQuery(function(){
		
		jQuery('.orders-page .typeselection span').click(function(){
			var id=this.id;
			
			jQuery('.typeselection span').removeClass('active');
			//jQuery.removeCookie('active_tab', { path: '/' });
			
			jQuery(this).addClass('active');
			
			jQuery('.tas').hide();
			
			jQuery('.'+id).fadeIn();
			
		});
		
		
		//load take away orders
		var takeawayinterval = function() {
		
            jQuery.ajax({
				 url: "pages/takeaway-orders.php",
				 type: 'POST',
				 success: function(value){
					var check = value.trim();
					
					
					if(check!='test'){
						jQuery('.ta1').html(value);
					}
					
				 }
			});
			
			jQuery.ajax({
				 url: "pages/takeaway-order-history.php",
				 type: 'POST',
				 success: function(value){
					var check = value.trim();
					
					if(check!='test'){
						jQuery('.ta3').html(value);
					}
					
				 }
			});
        }
		
		setInterval(takeawayinterval, 500);
		
		
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
		
		jQuery('.processed').click(function(){
			var id = jQuery(this).attr('data-rel');
			jQuery('.gradeX-'+id).attr('onclick','').fadeOut();
			
			jQuery.ajax({
				 url: "actions/process-takeaway.php",
				 type: 'POST',
				 data: 'id='+encodeURIComponent(id),
				 success: function(value){}
			});
			
		});
		
		jQuery('.startad').click(function(){
			var id = jQuery(this).attr('data-rel');
			jQuery('.gradeX-'+id).attr('onclick','');
			
			jQuery.ajax({
				 url: "actions/startad-takeaway.php",
				 type: 'POST',
				 data: 'id='+encodeURIComponent(id),
				 success: function(value){}
			});
			
		});
		
		//for data-table * orders.php
		jQuery('#theorderstakeonload').dataTable( {
			"aaSorting": [[ 3, "desc" ], [ 4, "desc" ]],
			"iDisplayLength" : 100,
			"oLanguage": {
					"sUrl": "scripts/datatable-swedish.txt"
			}
		} );
		
		
		jQuery('.thecourse').mouseover(function(){
			var resid = jQuery(this).attr('data-id');
			
			jQuery.fn.center = function ()
			{
				this.css("position","fixed");
				this.css("top", (jQuery(window).height() / 2) - (this.outerHeight() / 2)-80);
				return this;
			}
			
			
			jQuery('.course-box .box-content').html('<img src="images/loader.gif" style="margin:0 auto;">');
			
			
			jQuery.ajax({
				 url: "actions/get-course.php",
				 type: 'POST',
				 data: 'id='+encodeURIComponent(resid),
				 success: function(value){
					
					jQuery('.course-box .box-content').html(value);
					
					jQuery('.course-box').center();
					jQuery('.course-box').fadeIn();	
					
				 }
			});
			
			
		}).mouseout(function(){
			jQuery('.course-box').fadeOut();
		});
		
		$('.wrapper').mouseover(function(){
			jQuery('.course-box').fadeOut();
		});
		
		var thevolume = new Array('0','.2','.4','.6','.8','1');
		
		$( ".thevolume" ).slider({ 
			step: 1,
			max: 5,
			min:0,
			value: 5,
			slide: function (event, ui) {
				$('.forvolume span.vol').html(ui.value);
				
				var check = jQuery('body').attr('aria-valuetext');
				
				if(check==0){
				
					var checkcounter = jQuery('#notif').length;
	
					if(checkcounter>0){
						ion.sound.play("notification", {
							volume: thevolume[ui.value]
						});
					}
					
				
				}
				
				//
			}
		}).sliderAccess({
			touchonly : false
		});
		
		
		// ta stop or go
		
		$('.tago').click(function(){
			var val = $(this).attr('data-id');
			
			if(val==0){
				$(this).addClass('tastop').html('TAKE AWAY STOPPAD').attr('data-id',1);
				val=1;
			}
			else{
				$(this).removeClass('tastop').html('TAKE AWAY ÖPPEN').attr('data-id',0);
				val=0;
			}
			
			jQuery.ajax({
				 url: "actions/panic.php",
				 type: 'POST',
				 data: 'val='+encodeURIComponent(val),
				 success: function(value){}
			});
			
		});	
			
		
	});
</script>
<div class="page orders-page">
	<div class="page-header">
    	<div class="page-header-left">
            <h2>
            	<?php
					echo 'Beställningar';
				?>
               
            </h2>
        </div>
        <!-- end .page-header-left -->
        
        <div class="page-header-right" style="padding:0 !important;">
        	<div class="forvolume">
            	<label>Volym</label>
        		<div class="thevolume"></div>
                <span class="vol">5</span>
            </div>
        	<input type="button" class="btn mutebtn" value="" style="display:inline-block; vertical-align:middle;" data-rel="0">
        </div>
       
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
    	
        <div class="typeselection">
            <!--<input type="button" class="btn refreshbtn" value="Uppdatera" style="float:right;">-->
            <span id="ta4" class="active">Arbetssidan</span>
            <span id="ta1">Orderlistan</span>
            <span id="ta2">Catering</span>
            <span id="ta3">Beställningshistorik</span>
            <?php
            	$qp=mysql_query("select var_value from settings where var_name='panic_value'");
				$rp=mysql_fetch_assoc($qp);
				
				$panicval=$rp['var_value'];
				$panicstop='';
				$panicvalue='TAKE AWAY ÖPPEN';
				if($panicval==1){
					$panicstop=' tastop';
					$panicvalue='TAKE AWAY STOPPAD';
				}
				
			?>
            <a href="javascript:void(0)" data-id="<?php echo $panicval; ?>" class="tago<?php echo $panicstop; ?>"><?php echo $panicvalue; ?></a>
        </div>
        <div class="clear"></div>
        
        <div class="ta1 tas" style="display:none;">
        
        	<div class="legendwrap">
            
            <div class="legendbox">
                <span class="redpink"></span>
                <label>Ny beställning</label>
            </div>
            
            <div class="legendbox">
                <span class="red"></span>
                <label>Måste hanteras</label>
            </div>
            
            <div class="legendbox">
                <span class="yellow"></span>
                <label>Ej påbörjad</label>
            </div>
            
            <div class="legendbox">
                <span class="green"></span>
                <label>Påbörjad</label>
            </div>
            
            <div class="legendbox">
                <span class="grey"></span>
                <label>Avbokad</label>
            </div>
            <div class="clear"></div>
        </div>  
        
        <?php
		
			function theOrderType($id){
				$q=mysql_query("select if(rd.lunchmeny=0,' (À la carte)',' (Lunch)') as thetype from reservation as r, reservation_detail as rd where r.id=rd.reservation_id and rd.reservation_id='".$id."'");
				$r=mysql_fetch_assoc($q);
				
				return $r['thetype'];
			}
		
			$getorders = array();
		
			//getting the new orders
        	$qn = mysql_query("select rt.description as descrip, concat(a.fname,' ',a.lname) as name, r.payment_mode as payment_mode, r.kco_payment as kco_payment,
DATE_FORMAT(STR_TO_DATE(r.date, '%m/%d/%Y'),'%b %d, %Y') as date, DATE_FORMAT(r.time,'%H:%i') as time,r.approve as approve, r.id as rid, rt.id as rtid, r.lead_time as lead, r.acknowledged as ack, r.viewed as viewed, r.date_time as datetime, r.asap as asap, r.asap_datetime as asapdatetime, r.deliver as deliver, date_format(r.asap_datetime,'%b %d, %Y') as asapdate, date_format(r.asap_datetime,'%H:%i') as asaptime, r.bongs_status as bongs, datediff(STR_TO_DATE(concat(r.date,r.time), '%m/%d/%Y %H:%i'), now()) as count_days, datediff(STR_TO_DATE(asap_datetime, '%m/%d/%Y %H:%i'), now()) as acount_days from reservation_type as rt, account as a, reservation as r where rt.id=r.reservation_type_id and a.id=r.account_id and r.deleted=0 and rt.id in (2,3)  and r.processed=0 and a.deleted=0 and viewed=0 order by date");
			
			while($rn=mysql_fetch_assoc($qn)){
				
					$getorders[] = array(
						'descrip'=>$rn['descrip'], 
						'name'=>$rn['name'], 
						'date'=>$rn['date'], 
						'time'=>$rn['time'], 
						'approve'=>$rn['approve'], 
						'rid'=>$rn['rid'], 
						'rtid'=>$rn['rtid'], 
						'lead'=>$rn['lead'], 
						'ack'=>$rn['ack'], 
						'viewed'=>$rn['viewed'], 
						'datetime'=>$rn['datetime'], 
						'asap'=>$rn['asap'], 
						'asapdatetime'=>$rn['asapdatetime'], 
						'deliver'=>$rn['deliver'], 
						'asapdate'=>$rn['asapdate'], 
						'asaptime'=>$rn['asaptime'],
						'bongs'=>$rn['bongs'],
						'count_days'=>$rn['count_days'],
						'acount_days'=>$rn['acount_days'],
						'payment_mode'=>$rn['payment_mode'],
						'kco_payment'=>$rn['kco_payment']
					);
				
			}
	
			//getting the unattended orders
			
			$qc=mysql_query("select rt.description as descrip, concat(a.fname,' ',a.lname) as name, 
DATE_FORMAT(STR_TO_DATE(r.date, '%m/%d/%Y'),'%b %d, %Y') as date, DATE_FORMAT(r.time,'%H:%i') as time, r.number_people as numpeople, r.number_table as numtable, r.approve as approve, r.id as rid, rt.id as rtid, r.lead_time as lead, r.acknowledged as ack, r.viewed as viewed, r.date_time as datetime, r.asap as asap, r.asap_datetime as asaptime, r.deliver as deliver, date_format(r.asap_datetime,'%b %d, %Y') as asapdate, date_format(r.asap_datetime,'%H:%i') as asaptime, r.bongs_status as bongs, datediff(STR_TO_DATE(concat(r.date,r.time), '%m/%d/%Y %H:%i'), now()) as count_days, datediff(STR_TO_DATE(asap_datetime, '%m/%d/%Y %H:%i'), now()) as acount_days, r.payment_mode as payment_mode, r.kco_payment as kco_payment from reservation_type as rt, account as a, reservation as r where rt.id=r.reservation_type_id and a.id=r.account_id and r.deleted=0 and rt.id in (2,3) and r.processed=0 and a.deleted=0 and viewed=1 and r.approve=8 order by date, time") or die(mysql_error());
			
			while($rn=mysql_fetch_assoc($qc)){
				
				$getorders[] = array(
					'descrip'=>$rn['descrip'], 
					'name'=>$rn['name'], 
					'date'=>$rn['date'], 
					'time'=>$rn['time'], 
					'approve'=>$rn['approve'], 
					'rid'=>$rn['rid'], 
					'rtid'=>$rn['rtid'], 
					'lead'=>$rn['lead'], 
					'ack'=>$rn['ack'], 
					'viewed'=>$rn['viewed'], 
					'datetime'=>$rn['datetime'], 
					'asap'=>$rn['asap'], 
					'asapdatetime'=>$rn['asapdatetime'], 
					'deliver'=>$rn['deliver'], 
					'asapdate'=>$rn['asapdate'], 
					'asaptime'=>$rn['asaptime'],
					'bongs'=>$rn['bongs'],
					'count_days'=>$rn['count_days'],
					'acount_days'=>$rn['acount_days'],
					'payment_mode'=>$rn['payment_mode'],
				    'kco_payment'=>$rn['kco_payment']
				);
			
			}
		
			
			$getnow = date('Y-m-d H:i:s');
			
			//getting the orders
			
			 $qd=mysql_query("select rt.description as descrip, concat(a.fname,' ',a.lname) as name, 
DATE_FORMAT(STR_TO_DATE(r.date, '%m/%d/%Y'),'%b %d, %Y') as date, DATE_FORMAT(r.time,'%H:%i') as time, r.number_people as numpeople, r.number_table as numtable, r.approve as approve, r.id as rid, rt.id as rtid, r.lead_time as lead, r.acknowledged as ack, r.viewed as viewed, r.date_time as datetime, r.asap as asap, r.asap_datetime as asapdatetime, r.deliver as deliver, DATE_FORMAT(r.asap_datetime,'%Y-%m-%d %k:%i:%s') as dtf, date_format(r.asap_datetime,'%b %d, %Y') as asapdate, date_format(r.asap_datetime,'%H:%i') as asaptime, r.bongs_status as bongs, datediff(STR_TO_DATE(concat(r.date,r.time), '%m/%d/%Y %H:%i'), now()) as count_days, datediff(STR_TO_DATE(asap_datetime, '%m/%d/%Y %H:%i'), now()) as acount_days, r.payment_mode as payment_mode, r.kco_payment as kco_payment from reservation_type as rt, account as a, reservation as r where rt.id=r.reservation_type_id and a.id=r.account_id and r.deleted=0 and rt.id in (2,3) and r.processed=0 and a.deleted=0 and viewed=1 and r.approve!=8 order by asapdate, asaptime") or die(mysql_error());
			
			while($rn=mysql_fetch_assoc($qd)){
				
				$getorders[] = array(
					'descrip'=>$rn['descrip'], 
					'name'=>$rn['name'], 
					'date'=>$rn['date'], 
					'time'=>$rn['time'], 
					'approve'=>$rn['approve'], 
					'rid'=>$rn['rid'], 
					'rtid'=>$rn['rtid'], 
					'lead'=>$rn['lead'], 
					'ack'=>$rn['ack'], 
					'viewed'=>$rn['viewed'], 
					'datetime'=>$rn['datetime'], 
					'asap'=>$rn['asap'], 
					'asapdatetime'=>$rn['asapdatetime'], 
					'deliver'=>$rn['deliver'], 
					'asapdate'=>$rn['asapdate'], 
					'asaptime'=>$rn['asaptime'],
					'bongs'=>$rn['bongs'],
					'count_days'=>$rn['count_days'],
					'acount_days'=>$rn['acount_days'],
					'payment_mode'=>$rn['payment_mode'],
				    'kco_payment'=>$rn['kco_payment']
				);
				
			}
			
			
		?>  
                 
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="theorderstake">
                <thead>
                    <tr>
                    	<th class="tohide"><font style="font-size:15px;">#</font></th>
                    	<th><font style="font-size:15px;">Typ</font></th>
                        <th><font style="font-size:15px;">Startad</font></th>
                        <th><font style="font-size:15px;">Spättat</font></th>
                        <th><font style="font-size:15px;">Namn</font></th>
                        <th><font style="font-size:13px;">Datum</font></th>
                        <!--<th>Beställningen gjordes</th>-->
                        <th><font style="font-size:20px;">Tid</font></th>
                        <th><font style="font-size:15px;">Antal rätter</font></th>
                        <!--<th>Status</th>-->
                        <!--<th>Bearbetades, datum och tid</th>-->
                        <!-- <th></th> -->
                    </tr>
                </thead>
                <tbody>
                 <?php
				 
				 	function checkLunchmeny($resid){
						
						$q=mysql_query("select lunchmeny from reservation_detail where reservation_id='".$resid."'");
						list($lunchmeny) = mysql_fetch_array($q);
						
						return $lunchmeny;
					}
				 
				 	function getCoursesCount($resid, $lunchmeny){
						
						if($lunchmeny==0){
							
							$q=mysql_query("select rd.id from reservation_detail as rd, menu as m where  m.id=rd.menu_id and rd.reservation_id='".$resid."' and rd.lunchmeny=0 and m.deleted=0");
						}
						else{
							$q=mysql_query("select rd.id from reservation_detail as rd, menu_lunch_items as m where  m.id=rd.menu_id and rd.reservation_id='".$resid."' and rd.lunchmeny=1 and m.deleted=0");
						}
						
						$count = mysql_num_rows($q);
						
						return $count;
						
					}
					
					function getCoursesQuantity($resid, $lunchmeny){
						
						if($lunchmeny==0){
							
							$q=mysql_query("select sum(rd.quantity) from reservation_detail as rd, menu as m where  m.id=rd.menu_id and rd.reservation_id='".$resid."' and rd.lunchmeny=0 and m.deleted=0");
						}
						else{
							$q=mysql_query("select sum(rd.quantity) from reservation_detail as rd, menu_lunch_items as m where  m.id=rd.menu_id and rd.reservation_id='".$resid."' and rd.lunchmeny=1 and m.deleted=0");
						}
						
						list($quantity) = mysql_fetch_array($q);
						
						return $quantity; 
						
					}
					
				
					$count=0;
						
					for($i=0;$i<count($getorders);$i++){
						
						
						if($getorders[$i]['payment_mode']=='klarna' &&  $getorders[$i]['kco_payment']==0){
							//do not include for display	
						}
						else{
							
							$count++;
							
							$notprocessed='';
							if($getorders[$i]['approve']!=8){
								
								if($getorders[$i]['approve']==14){
									$notprocessed='thecancelled';
								}
								else{
									$notprocessed='notp';
								}
							}
							
							$thegreen='';
							if($getorders[$i]['bongs']==1){
								$thegreen='thegreen';
							}
						
							if(getCoursesCount($getorders[$i]['rid'], checkLunchmeny($getorders[$i]['rid']))>0){
					
					 ?>
							<tr class="gradeX gradeX-<?php echo $getorders[$i]['rid'];?> <?php echo $notprocessed;?> <?php echo $thegreen;?>" align="center" onclick="showDetail('<?php echo $getorders[$i]['rid'];?>','<?php echo $getorders[$i]['rtid'];?>')">	
								<td class="<?php if($getorders[$i]['viewed']==0) echo 'blink sound'; ?> pointer tohide themedium">
									<?php echo $count; ?>
								</td>
								<td class="<?php if($getorders[$i]['viewed']==0) echo 'blink sound'; ?> pointer themedium">
									<?php
										if($getorders[$i]['deliver']==1){
											echo 'Utkörnig'.theOrderType($getorders[$i]['rid']);
										}
										else{
											echo 'Take away'.theOrderType($getorders[$i]['rid']);
										}
									?>
								</td>
								
								<td class="<?php if($getorders[$i]['viewed']==0) echo 'blink sound'; ?> themedium">
									<?php if($getorders[$i]['approve']!=8){?>
										<input type="checkbox" data-rel="<?php echo $getorders[$i]['rid'];?>" class="startad" <?php if($getorders[$i]['approve']!=8 & $getorders[$i]['bongs']==1){ echo 'checked="checked"'; }?>>
									<?php } ?>
								</td>
								
								<td class="<?php if($getorders[$i]['viewed']==0) echo 'blink sound'; ?> themedium">
									<?php if($getorders[$i]['approve']!=8 & $getorders[$i]['bongs']==1){?>
										<input type="checkbox" data-rel="<?php echo $getorders[$i]['rid'];?>" class="processed">
									<?php } ?>
								</td>
								
								<td class="<?php if($getorders[$i]['viewed']==0) echo 'blink sound'; ?> pointer themedium"><?php echo $getorders[$i]['name']; ?></td>
								<td class="<?php if($getorders[$i]['viewed']==0) echo 'blink sound'; ?> pointer thesmall">
									<?php
									
										if($getorders[$i]['approve']!=8){
											if($getorders[$i]['asap']==0){
												echo $getorders[$i]['date']; 
											}
											else{
												echo $getorders[$i]['asapdate'];
											}
										}
										else{
											echo $getorders[$i]['date'];
										}
									?>
								</td>
								<td class="<?php if($getorders[$i]['viewed']==0) echo 'blink sound'; ?> pointer thelarge">
									<?php
									
									
										
										if($getorders[$i]['approve']!=8){
											if($getorders[$i]['asap']==0){
												if(addPrefix($getorders[$i]['count_days'])!='Passerat datum'){	
													echo addPrefix($getorders[$i]['count_days']).', '.$getorders[$i]['time']; 
												}
												else{
													echo addPrefix($getorders[$i]['count_days']); 
												}
											}
											else{
												if(addPrefix($getorders[$i]['acount_days'])!='Passerat datum'){
													echo addPrefix($getorders[$i]['acount_days']).', '.$getorders[$i]['asaptime'];
												}
												else{
													echo addPrefix($getorders[$i]['acount_days']);
												}
											}
										}
										else{
											
											if($getorders[$i]['asap']==0){
												echo 'Annan Tid'; 
											}
											else{
												echo 'Snarast';
											}
											
										}
									?>
								</td>
							   
								<td class="<?php if($getorders[$i]['viewed']==0) echo 'blink sound'; ?> pointer themedium">
									<span class="thecourse" data-id="<?php echo $getorders[$i]['rid']; ?>"><?php echo getCoursesQuantity($getorders[$i]['rid'], checkLunchmeny($getorders[$i]['rid'])); ?></span>
								</td>
							 
							</tr>
					 <?php 		} 
						}
				 
					} //end for
					
				 ?>     
                </tbody>
                <tfoot>
                    <tr>
                    	<th class="tohide"><font style="font-size:15px;">#</font></th>
                    	<th><font style="font-size:15px;">Typ</font></th>
                        <th><font style="font-size:15px;">Startad</font></th>
                        <th><font style="font-size:15px;">Spättat</font></th>
                        <th><font style="font-size:15px;">Namn</font></th>
                        <th><font style="font-size:13px;">Datum</font></th>
                        <!--<th>Beställningen gjordes</th>-->
                        <th><font style="font-size:20px;">Tid</font></th>
                        <th><font style="font-size:15px;">Antal rätter</font></th>
                        <!--<th>Status</th>-->
                        <!--<th>Bearbetades, datum och tid</th>-->
                        <!-- <th></th> -->
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <!-- end fortakeaway -->
        
        <div class="ta2 tas" style="display:none;">
        	<table cellpadding="0" cellspacing="0" border="0" class="display" id="thecatering">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Catering Date & Time</th>
                        <th>Total</th>
                        <th>Transaction Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
					
					
                        $q=mysql_query("select * from catering_detail where status=1 and deleted=0 order by date,time asc") or die(mysql_error());
                        
                        $count=0;
                        while($r=mysql_fetch_assoc($q)){
                            $count++;
                    ?>
                        <tr class="gradeX gradeX-<?php echo $r['id'];?>" align="center">
                            <td><?php echo $count; ?></td>
                            <td><?php echo 'Catering '.$count;?></td>
                            <td><?php echo date("M d, Y",strtotime($r['date'])); ?><br><?php echo $r['time']; ?></td>
                            <td><?php echo $r['total'];?></td>
                            <td><?php echo date("M d, Y",strtotime($r['date_time'])); ?><br><?php echo date("H:i",strtotime($r['date_time'])); ?></td>
                        </tr>
                    <?php		
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Catering Date & Time</th>
                        <th>Total</th>
                        <th>Transaction Date & Time</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- end .ta2 -->
        
         <div class="ta3 tas" style="display:none;">
         
         	<!--<div class="legendwrap">
                <div class="legendbox">
                	<span class="green"></span>
                    <label>Processed</label>
                </div>
                <div class="clear"></div>
            </div>  --> 
         
         	<table cellpadding="0" cellspacing="0" border="0" class="display" id="theorderstakeonload">
                <thead>
                    <tr>
                    	<th class="tohide"><font style="font-size:15px;">#</font></th>
                    	<th><font style="font-size:15px;">Typ</font></th>
                        <th><font style="font-size:15px;">Namn</font></th>
                        <th><font style="font-size:13px;">Datum</font></th>
                        <!--<th>Beställningen gjordes</th>-->
                        <th><font style="font-size:15px;">Tid</font></th>
                        <th><font style="font-size:15px;">Antal rätter</font></th>
                        <!--<th>Status</th>-->
                        <!--<th>Bearbetades, datum och tid</th>-->
                        <!-- <th></th> -->
                        <th><font style="font-size:15px;">Slutsumma</font></th>
                    </tr>
                </thead>
                <tbody>
                 <?php
				 
				 	//getting the course total
				 	function getCoursesTotal($id){

						$q=mysql_query("select m.name as menu, m.image as img, rd.price as price, rd.quantity as quantity, cu.shortname as currency, rd.notes as notes, m.type as type, m.discount as discount,m.id as menu_id, rd.lunchmeny as lunchmeny from reservation_detail as rd, menu as m, currency as cu where m.id=rd.menu_id and cu.id=m.currency_id and m.deleted=0 and rd.reservation_id='".$id."' and rd.lunchmeny=0") or die(mysql_error());
						
						$count=0;
						$total=0;
						$currency='kr';
						$opt_tot_all = 0;
						
						while($r=mysql_fetch_assoc($q)){
							$count++;
							$opt_tot = 0;
							$price=$r['price'];
							
							$subtotal=$price*$r['quantity'];
							
					
							$opt_sql = "select a.name as name,a.price as price, dish_num from reservation_menu_option as a where reservation_unique_id = '".$uniqueid."' and a.menu_id=$r[menu_id] order by dish_num";
							
							$opt_query = mysql_query($opt_sql) or die(mysql_error());
							if(mysql_num_rows($opt_query)>0){
								
								while($opt = mysql_fetch_assoc($opt_query)){
									$opt_tot += $opt['price'];
								}
							}
							
							$subtotal+=$opt_tot;
					
							$total+=$subtotal;
							$currency= 'kr';
						
						}
						
						//for lunch meny
						
						$qm=mysql_query("select m.name as menu, m.image as img, rd.price as price, rd.quantity as quantity, cu.shortname as currency, rd.notes as notes, m.type as type, m.discount as discount,m.id as menu_id, rd.lunchmeny as lunchmeny from reservation_detail as rd, menu_lunch_items as m, currency as cu where m.id=rd.menu_id and cu.id=m.currency_id and m.deleted=0 and rd.reservation_id='".$id."' and rd.lunchmeny=1") or die(mysql_error());
						
						while($r=mysql_fetch_assoc($qm)){
							$count++;
							$opt_tot = 0;
							$price=$r['price'];
							
							$subtotal=$price*$r['quantity'];
							
							$opt_sql = "select a.name as name,a.price as price, dish_num from reservation_menu_option as a where reservation_unique_id = '".$uniqueid."' and a.menu_id=$r[menu_id] order by dish_num";
							
							$opt_query = mysql_query($opt_sql) or die(mysql_error());
							if(mysql_num_rows($opt_query)>0){
								
								while($opt = mysql_fetch_assoc($opt_query)){
									$opt_tot += $opt['price'];
								}
								
							}
							
							$subtotal +=$opt_tot;
						
							$total+=$subtotal;
							$currency = 'kr';
							
						}
						
						return $total+$opt_tot_all.' '.$currency;
				
					}
				 
				 
                    $qq=mysql_query("select rt.description as descrip, concat(a.fname,' ',a.lname) as name, 
DATE_FORMAT(STR_TO_DATE(r.date, '%m/%d/%Y'),'%b %d, %Y') as date, DATE_FORMAT(r.time,'%k:%i') as time, r.number_people as numpeople, r.number_table as numtable, r.approve as approve, r.id as rid, rt.id as rtid, r.lead_time as lead, r.acknowledged as ack, r.viewed as viewed, r.date_time as datetime, r.asap as asap, r.asap_datetime as asaptime, r.deliver as deliver, date_format(r.asap_datetime,'%b %d, %Y') as asapdate, date_format(r.asap_datetime,'%H:%i') as asaptime, datediff(STR_TO_DATE(concat(r.date,r.time), '%m/%d/%Y %H:%i'), now()) as count_days, datediff(STR_TO_DATE(asap_datetime, '%m/%d/%Y %H:%i'), now()) as acount_days from reservation_type as rt, account as a, reservation as r where rt.id=r.reservation_type_id and a.id=r.account_id and r.deleted=0 and (rt.id=2 or rt.id=3) and r.processed=1 and a.deleted=0 order by r.date desc, r.time desc") or die(mysql_error());
				    
					$count=0;
                    while($r=mysql_fetch_assoc($qq)){
						$count++;
						
						if(getCoursesCount($r['rid'], checkLunchmeny($r['rid']))>0){
                 ?>
                        <tr class="gradeX gradeX-<?php echo $r['rid'];?> processed" align="center" onclick="showDetail('<?php echo $r['rid'];?>','<?php echo $r['rtid'];?>')">	
                        	<td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer tohide themedium">
                            	<?php echo $count; ?>
                            </td>
                        	<td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer themedium">
                            	<?php
                                	if($r['deliver']==1){
										echo 'Utkörnig'.theOrderType($r['rid']);
									}
									else{
										echo 'Take away'.theOrderType($r['rid']);
									}
								?>
                            </td>
                            
                            <td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer themedium"><?php echo $r['name']; ?></td>
                            <td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer thesmall">
                            	<?php
								
									if($r['approve']!=8){
										if($r['asap']==0){
									 		echo $r['date']; 
										}
										else{
											echo $r['asapdate'];
										}
									}
									else{
                                		echo $r['date'];
									}
								?>
                            </td>
                            <td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer themedium">
                            	<?php
                                	
									if($r['approve']!=8){
										if($r['asap']==0){
											if(addPrefix($r['count_days'])!='Passerat datum'){
									 			//echo addPrefix($r['count_days']).', '.$r['time']; 
												echo $r['time']; 
											}
											else{
												//echo addPrefix($r['count_days']); 
												echo $r['time'];
											}
										}
										else{
											if(addPrefix($r['acount_days'])!='Passerat datum'){
												//echo addPrefix($r['acount_days']).', '.$r['asaptime'];
												echo $r['asaptime'];
											}
											else{
												//echo addPrefix($r['acount_days']);
												echo $r['asaptime'];
											}
										}
									}
									else{
										
										if($r['asap']==0){
											echo 'Annan Tid'; 
										}
										else{
											echo 'Snarast';
										}
										
									}
								?>
                            </td>
                            <!--<td class="<?php //if($r['viewed']==0) echo 'blink sound'; ?> pointer">
								<?php
									/*if($r['date']!=''){
										if($r['asap']==0){
									 		echo date("M d, Y",strtotime($r['date'])).' '.$r['time']; 
										}
										else{
											echo 'Snarast';
										}
									}
									else{
										echo '-';
									}*/
								?>
                            </td>-->
                            <td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer themedium">
                            	<span class="thecourse" data-id="<?php echo $r['rid']; ?>"><?php echo getCoursesQuantity($r['rid'], checkLunchmeny($r['rid'])); ?></span>
                            </td>
                            <!--<td class="<?php //if($r['viewed']==0) echo 'blink sound'; ?> pointer"><?php //if(orderStatus($r['approve']) == 'Approve'){ echo 'Godkänd beställning'; } else if(orderStatus($r['approve'])=='Cancel'){ echo 'Ej godkänd beställning'; } else{ echo orderStatus($r['approve']); } if($r['approve']==12){ echo ' <br> '.$r['lead'].' min'; } ?></td>-->
                             <!--<td class="<?php //if($r['viewed']==0) echo 'blink sound'; ?> pointer">
                            	<?php
                                	//echo $r['asaptime'];
								?>
                            </td>-->
                            <!-- <td class="<?php //if($r['viewed']==0) echo 'blink sound'; ?>"><a href="#" onclick="showDetail('<?php //echo $r['rid'];?>','<?php //echo $r['rtid'];?>')"><img src="images/info.png"></a></td> -->
                            <td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer themedium">
                            	<span class="thecourse" data-id="<?php echo $r['rid']; ?>"><?php echo getCoursesTotal($r['rid']); ?></span>
                            </td>
                        </tr>
                 <?php } 
					}
				 ?>     
                </tbody>
                <tfoot>
                    <tr>
                    	<th class="tohide"><font style="font-size:15px;">#</font></th>
                    	<th><font style="font-size:15px;">Typ</font></th>
                        <th><font style="font-size:15px;">Namn</font></th>
                        <th><font style="font-size:13px;">Datum</font></th>
                        <!--<th>Beställningen gjordes</th>-->
                        <th><font style="font-size:15px;">Tid</font></th>
                        <th><font style="font-size:15px;">Antal rätter</font></th>
                        <!--<th>Status</th>-->
                        <!--<th>Bearbetades, datum och tid</th>-->
                        <!-- <th></th> -->
                        <th><font style="font-size:15px;">Slutsumma</font></th>
                    </tr>
                </tfoot>
            </table>
         </div>
         <!-- end .ta3 -->
        <div class="clear"></div>
        
    </div>
    
    <!-- start .ta4 -->
         <div class="ta4 tas workside">
         
         <div class="legendwrap">
         	
            <div class="legendbox">
                <span class="redpink"></span>
                <label>Ny beställning</label>
            </div>
         
            <div class="legendbox">
                <span class="red"></span>
                <label>Måste hanteras</label>
            </div>
            
            <div class="legendbox">
                <span class="yellow"></span>
                <label>Ej påbörjad</label>
            </div>
            
            <div class="legendbox">
                <span class="green"></span>
                <label>Påbörjad</label>
            </div>
            
            <div class="legendbox">
                <span class="grey"></span>
                <label>Avbokad</label>
            </div>
            <div class="clear"></div>
        </div>   
         
         <?php require_once('pages/workside.php');?>
        </div>  <!-- end .ta4 -->
        
</div>
<div class="fade"></div>
<div class="delete-order-box modalbox">
	<h2>Confirm Delete<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Are you sure you want to proceed?</p>
        <input type="button" value="Delete">
    </div>
</div>
<div class="detail-order-box orderbox">
	<h2><span>Order Detail</span><a href="#" class="closebox" data-rel="order">X</a></h2>
    <div class="box-content">
        <!-- contents here -->
    </div>
</div>
<div class="fade2"></div>
<div class="cancel-order-box cancelbox">
	<h2>Reason for Cancelling<a href="#" class="closebox2">X</a></h2>
    <div class="box-content">
    	<textarea placeholder="Comments here..."></textarea>
        <input type="button" value="Submit">
        <div class="displaymsg"></div>
    </div>
</div>
<div class="proceed-order-box proceedbox">
	<h2>&nbsp;<a href="#" class="closebox3">X</a></h2>
    <div class="box-content">
		<?php
		
		function getSignatory($id){
			$q=mysql_query("select signatory from type where id=(select type_id from account where id='".$id."')");
			$r=mysql_fetch_assoc($q);
			
			return $r['signatory'];
		}
		
		 $signature = getSignatory($_SESSION['login']['id']);
		
		if(getSignatory($_SESSION['login']['id'])==1){
		?>
        <p data-rel="yes_<?php echo $signature;?>">Vänligen signera åtgärden<!--Vänligen bekräfta åtgärden--></p>
		<br /><!--Please sign with your name and click "Submit".-->
    	<input type="text" id="signatory" style="width:250px !important;" />
		<?php
		}else{
		?>
        <p data-rel="no_<?php echo $signature;?>">Vänligen bekräfta åtgärden</p>
        
		<?php 
		}
		?>
        <input type="button" value="Signera" onclick="proceedOrder('<?php echo getSignatory($_SESSION['login']['id']); ?>')">
        <div class="displaymsg"></div>
    </div>
</div>
<div class="custom-order-box custombox">
	<h2>Om hur många minuter är maten<br /> klar för avhämtning?<a href="#" class="closebox4" style="position: absolute;
top: 8px;
right: 8px;">X</a></h2>
    <div class="box-content">
        <p><input type="text" class="customtime txt"> minuter</p>
        <input type="button" value="Skicka">
        <div class="displaymsg"></div>
    </div>
</div>
<div class="table-box modalbox" style="top: 90% !important;">
	<h2><span>Table Detail</span><a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        
    </div>
</div>
<div class="course-box modalbox">
	<h2><span>Course Detail</span><a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        
    </div>
</div>
<script type="text/javascript" language="javascript">
$('#signatory').typeahead({
     name: 'signatory',
	 valueKey: 'name',
     local: [<?php echo $account_list; ?>]		
	}).on('typeahead:selected', function($e, datum){
        $('#signatory').attr('data-rel',datum["id"]);
	})
</script>
