<?php session_start();
	include '../config/config.php';
	
	$cusid = $_POST['id'];
?>
<script type="text/javascript">
	jQuery(function(){
	
		//for data-table * orders.php
		jQuery('#theorderstake2').dataTable( {
			"aaSorting": [[ 0, "asc" ]],
			"iDisplayLength" : 10,
			"oLanguage": {
					"sUrl": "scripts/datatable-swedish.txt"
			}
		} );
		
		
		jQuery('.thecourse').mouseover(function(){
			var resid = jQuery(this).attr('data-id');
			
			jQuery.fn.center = function ()
			{
				this.css("position","fixed");
				this.css("top", (jQuery(window).height() / 2) - (this.outerHeight() / 2)-60);
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
		
		
		jQuery('.closebox').click(function(e){
		
			/* Prevent default actions */
			e.preventDefault();
			e.stopPropagation();
			
			jQuery('.fade, .orderbox').fadeOut();
			
		});
		
	});
	
	function showDetail(id,typeid){
	
	if(typeid==1){
		jQuery('.orderbox h2 span').html('Bokningsinformation');
	}
	else if(typeid==2){
		jQuery('.orderbox h2 span').html('Take Away Information');
	}else{
		jQuery('.orderbox h2 span').html('Lunch Menu Information');
	}
	
	jQuery('.orderbox .box-content').html('<img src="images/loader.gif" style="margin: 30px 0 0;">');
	jQuery.ajax( {
            url: "actions/order-detail.php",
            type: 'POST',
            data: 'id=' + encodeURIComponent(id),
            success: function(value) {
				jQuery('.orderbox .box-content').fadeIn().html(value);
	           
            }
        });
	
	jQuery('.fade, .orderbox').fadeIn();	
		jQuery.ajax({
			 url: "actions/viewedReservation.php",
			 type: 'POST',
			 data: 'typeId='+encodeURIComponent(typeid),
			 success: function(value){
				 
				 if(value!=0){
					 jQuery(".orders-li #notif").html(value);
				}else{
					jQuery(".orders-li div").remove();
				}
			 }
		});	
	
}
</script>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="theorderstake2">
    <thead>
        <tr>
            <th class="tohide"><font style="font-size:15px;">#</font></th>
            <th><font style="font-size:15px;">Typ</font></th>
            <th><font style="font-size:15px;">Namn</font></th>
            <th><font style="font-size:13px;">Datum</font></th>
            <th><font style="font-size:20px;">Tid</font></th>
            <th><font style="font-size:15px;">Antal rätter</font></th>
        </tr>
    </thead>
    <tbody>
     <?php
	 
	 function getCoursesCount($resid){
						
		$q=mysql_query("select id from reservation_detail where reservation_id='".$resid."'");
		return mysql_num_rows($q);
		
	 }
	 
	 function theOrderType($id){
			$q=mysql_query("select if(rd.lunchmeny=0,' (À la carte)',' (Lunch)') as thetype from reservation as r, reservation_detail as rd where r.id=rd.reservation_id and rd.reservation_id='".$id."'");
			$r=mysql_fetch_assoc($q);
			
			return $r['thetype'];
		}
        
         $qq=mysql_query("select rt.description as descrip, concat(a.fname,' ',a.lname) as name, 
DATE_FORMAT(STR_TO_DATE(r.date, '%m/%d/%Y'),'%b %d, %Y') as date, DATE_FORMAT(r.time,'%k:%i') as time, r.number_people as numpeople, r.number_table as numtable, r.approve as approve, r.id as rid, rt.id as rtid, r.lead_time as lead, r.acknowledged as ack, r.viewed as viewed, r.date_time as datetime, r.asap as asap, r.asap_datetime as asaptime, r.deliver as deliver, date_format(r.asap_datetime,'%b %d, %Y') as asapdate, date_format(r.asap_datetime,'%H:%i') as asaptime, datediff(STR_TO_DATE(concat(r.date,r.time), '%m/%d/%Y %H:%i'), now()) as count_days, datediff(STR_TO_DATE(asap_datetime, '%m/%d/%Y %H:%i'), now()) as acount_days from reservation_type as rt, account as a, reservation as r where rt.id=r.reservation_type_id and a.id=r.account_id and r.account_id='".$cusid."' and r.deleted=0 and rt.id=2 and r.processed=1 and a.deleted=0 order by r.last_update desc") or die(mysql_error());
    
        $count=0;
        while($r=mysql_fetch_assoc($qq)){
            $count++;
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
                            <td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer thelarge">
                            	<?php
                                	
									if($r['approve']!=8){
										if($r['asap']==0){
											/*if(addPrefix($r['count_days'])!='Passerat datum'){
									 			echo addPrefix($r['count_days']).', '.$r['time']; 
											}
											else{
												echo addPrefix($r['count_days']); 
											}*/
											
											echo $r['time']; 
											
										}
										else{
											/*if(addPrefix($r['acount_days'])!='Passerat datum'){
												echo addPrefix($r['acount_days']).', '.$r['asaptime'];
											}
											else{
												echo addPrefix($r['acount_days']);
											}	*/
											
											echo $r['asaptime'];
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
                      
                            <td class="<?php if($r['viewed']==0) echo 'blink sound'; ?> pointer themedium">
                            	<span class="thecourse" data-id="<?php echo $r['rid']; ?>"><?php echo getCoursesCount($r['rid']); ?></span>
                            </td>
                         
                        </tr>
     <?php } ?>     
    </tbody>
    <tfoot>
        <tr>
            <th class="tohide"><font style="font-size:15px;">#</font></th>
            <th><font style="font-size:15px;">Typ</font></th>
            <th><font style="font-size:15px;">Namn</font></th>
            <th><font style="font-size:13px;">Datum</font></th>
            <th><font style="font-size:20px;">Tid</font></th>
            <th><font style="font-size:15px;">Antal rätter</font></th>
        </tr>
    </tfoot>
</table>

<div class="course-box modalbox">
	<h2><span>Course Detail</span><a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        
    </div>
</div>

<div class="fade"></div>
<div class="detail-order-box orderbox">
	<h2><span>Order Detail</span><a href="#" class="closebox" data-rel="order">X</a></h2>
    <div class="box-content">
        <!-- contents here -->
    </div>
</div>