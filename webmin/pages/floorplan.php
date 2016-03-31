<?php
 include_once('redirect.php'); 
$q=mysql_query("select var_value as floorplan from settings where var_name='floor_plan'");
$r=mysql_fetch_assoc($q);
	
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
				
							setTimeout("window.location.reload()", 2000);
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
	if($r['floorplan']!=''){
?>
<style type="text/css">
	.floorplan-container{
		background:url(floorplanuploads/<?php echo $r['floorplan']; ?>) no-repeat center;
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

<div class="page floorplan-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
					echo 'Bordsplan';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
       
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
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
				
				//$q=mysql_query("select id from restaurant_detail where '".$currentDate."' >= start_date and '".$currentDate."' <= end_date and days like '%".$dayName."%' order by id desc limit 1");
				
				$q=mysql_query("select id from restaurant_detail where DATE_FORMAT(STR_TO_DATE('".$currentDate."', '%m/%d/%Y'), '%Y-%m-%d') >= DATE_FORMAT(STR_TO_DATE(start_date, '%m/%d/%Y'), '%Y-%m-%d') and DATE_FORMAT(STR_TO_DATE('".$currentDate."', '%m/%d/%Y'), '%Y-%m-%d') <= DATE_FORMAT(STR_TO_DATE(end_date, '%m/%d/%Y'), '%Y-%m-%d') and days like '%".$dayName."%' and deleted=0 order by id desc limit 1") or die(mysql_error());
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
    </div>
</div>

<div class="fade"></div>
<div class="table-box modalbox" style="top: 60% !important;">
	<h2><span>Table Detail</span><a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        
    </div>
</div>