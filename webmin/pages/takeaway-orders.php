<?php session_start();
	include '../config/config.php';
	
	$q=mysql_query("select time_to_sec(timediff(now(),last_update)) from reservation order by last_update desc limit 1");
	$r=mysql_fetch_array($q);
	
	if($r[0]<=2){
?>
<script type="text/javascript">
	jQuery(function(){
	
		//for data-table * orders.php
		
		jQuery('#theorderstake').dataTable( {
			"aaSorting": [[ 0, "asc" ]],
			"iDisplayLength" : 100,
			"oLanguage": {
					"sUrl": "scripts/datatable-swedish.txt"
			}
		} );
		
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
	
	});
</script>

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
DATE_FORMAT(STR_TO_DATE(r.date, '%m/%d/%Y'),'%b %d, %Y') as date, DATE_FORMAT(r.time,'%H:%i') as time,r.approve as approve, r.id as rid, rt.id as rtid, r.lead_time as lead, r.acknowledged as ack, r.viewed as viewed, r.date_time as datetime, r.asap as asap, r.asap_datetime as asapdatetime, r.deliver as deliver, date_format(r.asap_datetime,'%b %d, %Y') as asapdate, date_format(r.asap_datetime,'%H:%i') as asaptime, r.bongs_status as bongs, datediff(STR_TO_DATE(concat(r.date,r.time), '%m/%d/%Y %H:%i'), now()) as count_days, datediff(STR_TO_DATE(asap_datetime, '%m/%d/%Y %H:%i'), now()) as acount_days from reservation_type as rt, account as a, reservation as r where rt.id=r.reservation_type_id and a.id=r.account_id and r.deleted=0 and rt.id in (2,3) and r.processed=0 and a.deleted=0 and viewed=0 order by date");
			
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
DATE_FORMAT(STR_TO_DATE(r.date, '%m/%d/%Y'),'%b %d, %Y') as date, DATE_FORMAT(r.time,'%H:%i') as time, r.number_people as numpeople, r.number_table as numtable, r.approve as approve, r.id as rid, rt.id as rtid, r.lead_time as lead, r.acknowledged as ack, r.viewed as viewed, r.date_time as datetime, r.asap as asap, r.asap_datetime as asaptime, r.deliver as deliver, DATE_FORMAT(r.asap_datetime,'%Y-%m-%d %k:%i:%s') as dtf, date_format(r.asap_datetime,'%b %d, %Y') as asapdate, date_format(r.asap_datetime,'%H:%i') as asaptime, r.bongs_status as bongs, datediff(STR_TO_DATE(concat(r.date,r.time), '%m/%d/%Y %H:%i'), now()) as count_days, datediff(STR_TO_DATE(asap_datetime, '%m/%d/%Y %H:%i'), now()) as acount_days, r.payment_mode as payment_mode, r.kco_payment as kco_payment from reservation_type as rt, account as a, reservation as r where rt.id=r.reservation_type_id and a.id=r.account_id and r.deleted=0 and rt.id in (2,3) and r.processed=0 and a.deleted=0 and viewed=1 and r.approve!=8 order by asapdate, asaptime") or die(mysql_error());
			
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
<?php		
	}
	else{
		echo 'test';	
	}
	
?>
