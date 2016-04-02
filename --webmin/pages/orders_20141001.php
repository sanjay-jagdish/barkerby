<?php
include_once('redirect.php'); 

//for the auto-complete of user accounts signatory
$account_list='';
$qq=mysql_query("select * from account where type_id<>5 and deleted=0");
while($rr=mysql_fetch_assoc($qq)){
	$account_list.='{"id": "'.$rr['id'].'", "name": "'.$rr['fname'].' '.$rr['lname'].'"},';
}

?>
<script type="text/javascript">
	jQuery(function(){
		
		jQuery('.orders-page .typeselection span').click(function(){
			var id=this.id;
			
			jQuery('.typeselection span').removeClass('active');
			
			jQuery(this).addClass('active');
			
			jQuery('.tas').hide();
			
			jQuery('.'+id).fadeIn();
			
		});
		
		
		//load take away orders
		setInterval(function() {
		

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

        }, 2000);
		
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
		
		jQuery('.mutebtn').click(function(){
			var val=jQuery(this).attr('data-rel');
			if(val==0){
				jQuery(this).attr('data-rel',1);
				
				jQuery('#notificationAudio').remove();
				
				jQuery(this).val('Ljud På');
				
			}
			else{
				jQuery(this).attr('data-rel',0);
				
				jQuery(this).val('Ljud Av');
				
				jQuery('<audio id="notificationAudio"><source src="sounds/notification.ogg" type="audio/ogg"><source src="sounds/notification.mp3" type="audio/mpeg"><source src="sounds/notification.wav" type="audio/wav"></audio>').appendTo('body');
				
				setInterval(function(){ 
				
					if(jQuery('.orders-page .sound').length > 0){
						jQuery('#notificationAudio').get(0).play(); 
					}
			
				}, 500);
				
			}
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
		
		//for data-table * orders.php
		jQuery('#theorderstake2').dataTable( {
			"aaSorting": [[ 2, "desc" ]],
			"iDisplayLength" : 100,
			"oLanguage": {
					"sUrl": "scripts/datatable-swedish.txt"
			}
		} );
			
		
	});
</script>
<div class="page orders-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
					echo 'Beställningar';
				?>
               
            </h2>
        </div>
        <!-- end .page-header-left -->
        
        <div class="page-header-right" style="padding:0 !important;">
        	<input type="button" class="btn mutebtn" value="Ljud Av" style="float:right;" data-rel="0">
        </div>
       
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
    	
        <div class="typeselection">
            <!--<input type="button" class="btn refreshbtn" value="Uppdatera" style="float:right;">-->
            <span id="ta1" class="active">Take away</span>
            <span id="ta2">Catering</span>
            <span id="ta3">Beställnings Historik</span>
        </div>
        <div class="clear"></div>
        
        <div class="ta1 tas">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="theorderstake">
                <thead>
                    <tr>
                    	<th>#</th>
                        <th>Hämtad</th>
                        <th>Namn</th>
                        <th>Beställningen gjordes</th>
                        <th>Hämtas, datum och tid</th>
                        <th>Status</th>
                        <th>Bearbetades, datum och tid</th>
                        <!-- <th></th> -->
                    </tr>
                </thead>
                <tbody>
                 <?php
				    
                    $qq=mysql_query("select rt.description as descrip, concat(a.fname,' ',a.lname) as name, DATE_FORMAT(STR_TO_DATE(r.date, '%m/%d/%Y'),'%b %d, %Y') as date, DATE_FORMAT(r.time,'%k:%i') as time, r.number_people as numpeople, r.number_table as numtable, r.approve as approve, r.id as rid, rt.id as rtid, r.lead_time as lead, r.acknowledged as ack, r.viewed as viewed, r.date_time as datetime, r.asap as asap, r.asap_datetime as asaptime from reservation_type as rt, account as a, reservation as r where rt.id=r.reservation_type_id and a.id=r.account_id and r.deleted=0 and rt.id=2 and r.processed=0 and a.deleted=0 order by r.id desc") or die(mysql_error());
				
					$count=0;
                    while($r=mysql_fetch_assoc($qq)){
						$count++;
                 ?>
                        <tr class="gradeX gradeX-<?php echo $r['rid'];?>" align="center" onclick="showDetail('<?php echo $r['rid'];?>','<?php echo $r['rtid'];?>')">	
                        	<td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer"><?php echo $count; ?></td>
                            
                            <td class="<?php if($r['viewed']==0) echo 'blink sound'; ?>">
                            	<?php if($r['approve']!=8){?>
                            		<input type="checkbox" data-rel="<?php echo $r['rid'];?>" class="processed">
                                <?php } ?>
                            </td>
                            
                            <td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer"><?php echo $r['name']; ?></td>
                            <td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer"><?php echo date("M d, Y H:i",strtotime($r['datetime'])); ?></td>
                            <td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer">
								<?php
									if($r['date']!=''){
										if($r['asap']==0){
									 		echo date("M d, Y",strtotime($r['date'])).' '.$r['time']; 
										}
										else{
											echo 'Snarast';
										}
									}
									else{
										echo '-';
									}
								?>
                            </td>
                            <td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer"><?php if(orderStatus($r['approve']) == 'Approve'){ echo 'Godkänd beställning'; } else if(orderStatus($r['approve'])=='Cancel'){ echo 'Ej godkänd beställning'; } else{ echo orderStatus($r['approve']); } if($r['approve']==12){ echo ' <br> '.$r['lead'].' min'; } ?></td>
                             <td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer">
                            	<?php
                                	echo $r['asaptime'];
								?>
                            </td>
                            <!-- <td class="<?php //if($r['viewed']==0) echo 'blink sound'; ?>"><a href="#" onclick="showDetail('<?php //echo $r['rid'];?>','<?php //echo $r['rtid'];?>')"><img src="images/info.png"></a></td> -->
                        </tr>
                 <?php } ?>     
                </tbody>
                <tfoot>
                    <tr>
                    	<th>#</th>
                        <th>Hämtad</th>
                        <th>Namn</th>
                        <th>Beställningen gjordes</th>
                        <th>Hämtas, datum och tid</th>
                        <th>Status</th>
                        <th>Bearbetades, datum och tid</th>
                        <!-- <th>Åtgärd</th> -->
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
         	<table cellpadding="0" cellspacing="0" border="0" class="display" id="theorderstake2">
                <thead>
                    <tr>
                    	<th>#</th>
                        <th>Namn</th>
                        <th>Beställningen gjordes</th>
                        <th>Hämtas, datum och tid</th>
                        <th>Status</th>
                        <th>Bearbetades, datum och tid</th>
                        <!-- <th></th> -->
                    </tr>
                </thead>
                <tbody>
                 <?php
				    
                    $qq=mysql_query("select rt.description as descrip, concat(a.fname,' ',a.lname) as name, DATE_FORMAT(STR_TO_DATE(r.date, '%m/%d/%Y'),'%b %d, %Y') as date, DATE_FORMAT(r.time,'%k:%i') as time, r.number_people as numpeople, r.number_table as numtable, r.approve as approve, r.id as rid, rt.id as rtid, r.lead_time as lead, r.acknowledged as ack, r.viewed as viewed, r.date_time as datetime, r.asap as asap, r.asap_datetime as asaptime from reservation_type as rt, account as a, reservation as r where rt.id=r.reservation_type_id and a.id=r.account_id and r.deleted=0 and rt.id=2 and r.processed=1 and a.deleted=0 order by r.id desc") or die(mysql_error());
				
					$count=0;
                    while($r=mysql_fetch_assoc($qq)){
						$count++;
                 ?>
                        <tr class="gradeX gradeX-<?php echo $r['rid'];?>" align="center" onclick="showDetail('<?php echo $r['rid'];?>','<?php echo $r['rtid'];?>')">	
                        	<td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer"><?php echo $count; ?></td>
                            <td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer"><?php echo $r['name']; ?></td>
                            <td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer"><?php echo date("M d, Y H:i",strtotime($r['datetime'])); ?></td>
                            <td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer">
								<?php
									if($r['date']!=''){
									 	echo date("M d, Y",strtotime($r['date'])).' '.$r['time']; 
									}
									else{
										echo '-';
									}
								?>
                            </td>
                            <td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer"><?php if(orderStatus($r['approve']) == 'Approve'){ echo 'Godkänd beställning'; } else if(orderStatus($r['approve'])=='Cancel'){ echo 'Ej godkänd beställning'; } else{ echo orderStatus($r['approve']); } if($r['approve']==12){ echo ' <br> '.$r['lead'].' min'; } ?></td>
                             <td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer">
                            	<?php
                                	echo $r['asaptime'];
								?>
                            </td>
                            <!-- <td class="<?php //if($r['viewed']==0) echo 'blink sound'; ?>"><a href="#" onclick="showDetail('<?php //echo $r['rid'];?>','<?php //echo $r['rtid'];?>')"><img src="images/info.png"></a></td> -->
                        </tr>
                 <?php } ?>     
                </tbody>
                <tfoot>
                    <tr>
                    	<th>#</th>
                        <th>Namn</th>
                        <th>Beställningen gjordes</th>
                        <th>Hämtas, datum och tid</th>
                        <th>Status</th>
                        <th>Bearbetades, datum och tid</th>
                        <!-- <th>Åtgärd</th> -->
                    </tr>
                </tfoot>
            </table>
         </div>
         <!-- end .ta3 -->
        
        
        <div class="clear"></div>
        
    </div>
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
        <p>Vänligen bekräfta åtgärden</p>
		<?php
		if($_SESSION['login']['signatory']==1){
		?>
		<br />Please sign with your name and click "Submit".
    	<input type="text" id="signatory" style="width:250px !important; padding:8px;" />
		<?php
		}
		?>
        <input type="button" value="Bekräfta">
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

<script type="text/javascript" language="javascript">
$('#signatory').typeahead({
     name: 'signatory',
	 valueKey: 'name',
     local: [<?php echo $account_list; ?>]		
	}).on('typeahead:selected', function($e, datum){
        $('#signatory').attr('data-rel',datum["id"]);
	})
</script>