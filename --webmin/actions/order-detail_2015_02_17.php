<?php session_start();
	include '../config/config.php';
	session_start();
	
	function processedBy($id){
		if($id!=''){
			
			$q=mysql_query("select concat(fname,' ',mname,' ',lname) from account where id=".$id);
			$r=mysql_fetch_array($q);
			
			return $r[0];
			
		}
		else{
			return '';
		}
	}
	
	function theOrderType($id){
		$q=mysql_query("select if(rd.lunchmeny=0,' (À la carte)',' (Lunch)') as thetype from reservation as r, reservation_detail as rd where r.id=rd.reservation_id and rd.reservation_id='".$id."'");
		$r=mysql_fetch_assoc($q);
		
		return $r['thetype'];
	}
	
	
	$id=strip_tags($_POST['id']);
	$qry = mysql_query(" UPDATE reservation SET `viewed` = '1' WHERE  `id` = ".$id);
	
	$q=mysql_query("select concat(a.fname,' ',a.mname,' ',a.lname) as name, rt.description as descrip, r.approve as approve, r.date as date, r.time as time, r.number_people as numpeople, r.number_table as numtable, r.approve_by as appby, r.note as note, r.date_time as datetime, r.reservation_type_id as resid, r.uniqueid as uniqueid, r.reason as reason, a.email as email, a.mobile_number as mob, a.phone_number as phone, a.street_name as street, r.lead_time as lead, r.acknowledged as ack, r.deliver as deliver, a.city as city, a.state as state, a.zip as zip, a.country as country, r.asap as asap, r.payment_mode as payment, datediff(STR_TO_DATE(concat(r.date,r.time), '%m/%d/%Y %H:%i'), now()) as count_days, r.asap_datetime as asapdatetime from reservation as r, reservation_type as rt, account as a where a.id=r.account_id and rt.id=r.reservation_type_id and r.id=".$id) or die(mysql_error());
	
	
	$r=mysql_fetch_assoc($q);
	
	$customer=$r['name'];
	$type=$r['descrip'];
	$status=$r['approve'];
	$date=$r['date'];
	$time=$r['time'];
	$num_people=$r['numpeople'];
	$num_table=numberTable($id);
	$accountID=$r['appby'];
	$note=$r['note'];
	$date_time=$r['datetime'];
	$typeID=$r['resid'];
	$reason=$r['reason'];
	$email=$r['email'];
	$mobile=$r['mob'];
	$phone=$r['phone'];
	$customtime=$r['lead'];
	$acknowledged=$r['ack'];
	$street=$r['street'];
	$city=$r['city'];
	$state=$r['state'];
	$zip=$r['zip'];
	$country=$r['country'];
	$asap=$r['asap'];
	$payment=$r['payment'];
	$uniqueid = $r['uniqueid'];
	$countdays = $r['count_days'];
	$asapdatetime = $r['asapdatetime'];
	
	
	$number='';
	if($mobile!='' & $phone!=''){
		$number.=$mobile.' / '.$phone;
	}
	else{
		if($mobile!=''){
			$number=$mobile;
		}
		else if($phone!=''){
			$number=$phone;
		}
	}
	
	$address='';
						
	if($street!=''){
		$address.=$street;
	}
	if($city!=''){
		
		$comma='';
							
		if($address!=''){
			$comma=',';
		}
		
		$address.=$comma.$city;
	}
	if($state!=''){
		
		$comma='';
							
		if($address!=''){
			$comma=',';
		}
		
		$address.=$comma.$state;
	}
	if($zip!=''){
		$comma='';
							
		if($address!=''){
			$comma=',';
		}
		
		$address.=$comma.$zip;
	}
	if($country!=''){
		$comma='';
							
		if($address!=''){
			$comma=',';
		}
		
		$address.=$comma.$country;
	}
	
?>
<script type="text/javascript">
function proceedOrder(datarel){
	
	var val=jQuery('.choosed-action').attr('data-rel');
		
		if(val==6){
			jQuery('.cancelbox .displaymsg').hide();
			jQuery('.proceedbox').fadeOut();
			jQuery('.fade2, .cancelbox').fadeIn();
		}
		else{
			
			if(val==12){
				
				jQuery('.proceedbox .displaymsg').fadeOut('slow');
				
				if(jQuery('.thecustom').html() != ''){
					
					
					
					jQuery.ajax({
						 url: "actions/order-status.php",
						 type: 'POST',
						 data: 'status='+encodeURIComponent(val)+'&id=<?php echo $id; ?>'+'&customtime='+encodeURIComponent(jQuery('.thecustom').html()),
						 success: function(value){
							 
							 console.log('First: '+value);
							 
							 jQuery('.processedby').html(value);
							 jQuery('.fade, .orderbox, .fade2, .proceed-order-box').fadeOut('slow');
							 //direct_print_bong();
						 }
					});
					jQuery('.reason').html('');
					
				}
				else{
					jQuery('.proceedbox .displaymsg').fadeIn('slow').addClass('errormsg').html('Please set your Custom Time.');
				}
				
			}
			else{
				
				var signatory = 0;
				var proceed=0;
				var proceed1 =0;
				
				if(jQuery('#signatory').length > 0){	
				
					if(datarel==1){
						
						var taccounts = <?php echo json_encode($_SESSION['assigned_accounts'])?>;
						var arr = toArray(taccounts);
						
						if(arr.indexOf($('#signatory').val()) != -1){
							proceed1=1;
							proceed=1;
							signatory = arr.indexOf($('#signatory').val());
						}
						
					}
					
					if(signatory==0){
						proceed=1;
					}
					
					jQuery('.proceedbox .displaymsg').fadeOut();
					
					if(proceed==1 && proceed1 ==1){
	
						jQuery.ajax({
								 url: "actions/order-status.php",
								 type: 'POST',
								 data: 'status='+encodeURIComponent(val)+'&id=<?php echo $id; ?>&signed='+signatory,
								 success: function(value){
									 //direct_print_bong();
							 
							 		 console.log('Second: '+value);
									 
									 jQuery('.processedby').html(value);
									 jQuery('.fade, .orderbox, .fade2, .proceed-order-box').fadeOut('slow');
	
								 }
						});
					}
					else{
						jQuery('.proceedbox .displaymsg').html('Name not found').fadeIn().addClass('errormsg');
					}
					
					jQuery('.reason').html('');
				
				}else{
					jQuery.ajax({
								 url: "actions/order-status.php",
								 type: 'POST',
								 data: 'status='+encodeURIComponent(val)+'&id=<?php echo $id; ?>&signed='+signatory,
								 success: function(value){
							 
							 		 console.log('Third: '+value);
									 
									 jQuery('.processedby').html(value);
									 jQuery('.fade, .orderbox, .fade2, .proceed-order-box').fadeOut('slow');
									 //direct_print_bong();
									
								 }
					});
				}
			
			}
		}
	
}


function proceedOrderAction(){
		
		var acknowledge=jQuery('.acknowledge:checked').length;
		
		var ack=jQuery('.acknowledge').length;
		
		jQuery('.displaymsg-new').fadeOut('slow')
		
		if(ack==1){
			if(acknowledge==1){
				jQuery('#signatory').val('');
				jQuery('.proceedbox .displaymsg').hide();
				jQuery('.fade2, .proceed-order-box').fadeIn();
			}
			else{
				jQuery('.displaymsg-new').fadeIn('slow').addClass('errormsg').html('Please acknowledge first this reservation by clicking the checbox.');
			}
		}
		else{
			var signDet = jQuery('.proceedbox .box-content p').attr('data-rel').split('_');
			var needtoSign = signDet[0];
			var signature = signDet[1];
			
			jQuery('#signatory').val('');
			jQuery('.proceedbox .displaymsg').hide();
			
			if(needtoSign == "yes"){
				jQuery('.fade2, .proceed-order-box').fadeIn();
			}else{
				jQuery('.orderbox .tmsg .displaymsg').fadeIn('slow').addClass('successmsg').html('Mail har skickats!');
				proceedOrder(signature);
			}
		}
		
	}
	
	
	
jQuery(function(){
	
	
	jQuery('.cancelbox input[type=button]').click(function(){
		var val=jQuery('.cancelbox textarea').val();
		
		jQuery('.cancelbox .displaymsg').fadeOut('slow');
		
		if(val!=''){
			
			var stat=jQuery('.order-status').val();
			
			jQuery.ajax({
				 url: "actions/order-status.php",
				 type: 'POST',
				 data: 'status='+encodeURIComponent(stat)+'&id=<?php echo $id; ?>'+'&reason='+encodeURIComponent(val),
				 success: function(value){ 
					 jQuery('.processedby').html(value);
				 }
			});
			
			jQuery('.order-status').val(stat);
			jQuery('.reason').html(val);
			jQuery('.fade2, .cancelbox').fadeOut('slow');
			
			//setTimeout("window.location.reload();",1000);
			
		}
		else{
			jQuery('.cancelbox .displaymsg').fadeIn('slow').addClass('errormsg').html('Field should not be empty.');
		}
		
	});
	
	
	/*jQuery('.proceedbox input[type=button]').one('click',function(e) {
		
		
	
	});*/
	jQuery('.order-status').each(function() {
		
        jQuery(this).click(function(){
			
			jQuery('.order-status').each(function(){
				jQuery(this).removeClass('choosed-action');
			});
			
			jQuery(this).addClass('choosed-action');
			
			var id =jQuery(this).attr('data-rel');
	
			if(id==12){
				jQuery('.custombox .displaymsg').hide();
				jQuery('.fade2, .custombox').fadeIn();
			}
			else{
				jQuery('.thecustom').html('');
				proceedOrderAction();
			}
		});
		
    });
	
	jQuery('.custombox input[type=button]').click(function(){
		var ctime=jQuery('.customtime').val();
		
		jQuery('.custombox .displaymsg').fadeOut('slow');
		
		if(ctime!=''){
			if(ctime > 0){
				
				jQuery('.thecustom').html(jQuery('.customtime').val()+' min');
				jQuery('.fade2, .custombox').fadeOut();
				proceedOrderAction();
				
			}
			else{
				jQuery('.custombox .displaymsg').fadeIn('slow').addClass('errormsg').html('Minutes should be greater than 0.');
			}
		}
		else{
			jQuery('.custombox .displaymsg').fadeIn('slow').addClass('errormsg').html('Field is required.');
		}
	});
	
	$('.generate-pdf').click(function(){
		$('#fdfiframe').prop('src', '');
		$('#fdfiframe').prop('src', 'html2pdf/generate/pdf.php?id='+<?php echo $id;?>);
		$('.pdf-box').fadeIn();
		gotoModal();
		return false;
	});
	
	
	$('.closebox.pfdc-box').click(function(e){
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.pdf-box').fadeOut();
	});
	
	var defaulttext = $('.defualt-text').text();
	
	//$('.selectDefault').text(defaulttext);
//	
//	$('.selectBox').on('change',function(){
//		var defaulttext2 = $('.selectBox').find(":selected").text(); 
//		$('.selectDefault').text(defaulttext2);
//	});
});	
		function toArray(_Object){
			var _Array = new Array();
			for(var name in _Object){
				_Array[name] = _Object[name];
			}
		return _Array;
		}
		
		function printpdf(){
			document.getElementById('PDFtoPrint').focus();
			document.getElementById('PDFtoPrint').contentWindow.print(false);	
		}
		
		function direct_print_bong(){
			jQuery.ajax({
				 url: "html2pdf/generate/print_bong.php",
				 type: 'GET',
				 data: 'id=<?php echo $id; ?>',
				 success: function(value){
					 //do nothing
				 }
			});
		}
		
		function direct_print_delivery(){
			jQuery.ajax({
				 url: "html2pdf/generate/print_delevery.php",
				 type: 'GET',
				 data: 'id=<?php echo $id; ?>',
				 success: function(value){
					 //do nothing
				 }
			});
		}
				
</script>
<style>
	.invoice-info{
		padding-top:6px;
		line-height: 1;
	}
	.invoice-info span{
		min-width:150px;
		display:inline-block;
	}
	.invoice-info label{
		display:inline-block;
		min-width:50px;
		text-align:right;	
	}
	.generate-pdf{
		float:right;
		margin-top:-30px;
	}
	.pdf-box{
		position: absolute;
		top: 0 !important;
		bottom:0 important;
		width: 800px;
		min-height: 200px;
		height:100%;
		background: white;
		left: 0;
		right: 0;
		margin: 0 auto;
		display: none;
		z-index: 9999;
	}
	.pdf-box .box-content{
		box-sizing:border-box;
		border:0;
		width:100% !important;
		height:90%;
		max-height: 100%;
		position:relative;
		bottom:0;
	}
	.pdf-box iframe{
		position: absolute;
		top: 0;
		bottom: 0;
		left: 0;
		right: 0;
		height: 100%;
		width: 100%;
	}
	.print-pdf {
		width: 149px;
		min-height: 43px;
		display: inline-block;
		border-radius: 5px;
		margin-top: -117px;
		margin-right: 12px;
		padding-top: 12px;
		float: right;
		background: #f48d32 url(images/button.png);
		box-sizing: border-box;
		color: #fff;
		font-weight: bold;
		text-transform: uppercase;
		text-decoration: none;
	}
	.print-pdf:hover{
		box-shadow: 0 0 8px #333;	
	}
</style>
<div class="pdf-box modalbox" style="display:none;">
	<h2>PDF<a href="javascript:void(0)" class="closebox pfdc-box">X</a></h2>
    <div class="box-content">
        <iframe id="fdfiframe" src="" frameborder="0"></iframe>
    </div>
    <!--<p align="right"><input type="button" value="Save PDF" />&nbsp;<input type="button" value="Print PDF"></p>-->
</div>
<table width="100%">
<tr><td valign="top">
<table style="box-sizing:border-box; padding:10px;">
	<tr align="left">
    	<td width="150px" class="grey">Bokning mottagen :</td>
        <?php 
			$swedish_months = array('Januari','Februari','Mars','April','Maj','Juni','Juli','Augusti','September','Oktober','november','December');
			
			$dd = date("d",strtotime($date_time));
			$mm = date("n",strtotime($date_time));
			$yy = date("Y",strtotime($date_time));
			$hrmn = date("H:i",strtotime($date_time));
			$mm = $swedish_months[$mm-1];
			
			$datetime = $dd.' '.$mm.' '.$yy.' '.$hrmn;
			
			$dd = date("d",strtotime($date));
			$mm = date("n",strtotime($date));
			$yy = date("Y",strtotime($date));
			$hrmn = date("H:i",strtotime($date));
			
			$mm = $swedish_months[$mm-1];
			
			$dateonly = $dd.' '.$mm.' '.$yy;
		?>
        <td><?php echo $datetime; ?></td>
    </tr>
	<tr align="left">
    	<td class="grey">Namn :</td>
        <td><?php echo $customer; ?></td>
    </tr>
    <tr align="left">
    	<td class="grey">E-post :</td>
        <td><?php echo $email; ?></td>
    </tr>
    <tr align="left">
    	<td class="grey">Mobilnummer :</td>
        <td><?php echo $number; ?></td>
    </tr>
    <tr align="left">
    	<td class="grey">Adress :</td>
        <td><?php echo $address; ?></td>
    </tr>
    <tr align="left">
    	<td class="grey">Typ av bokning :</td>
        <td><?php 
			if($r['deliver']==1){
				echo 'Utkörnig'.theOrderType($id);
			}
			else{
				echo $type.theOrderType($id);
			}
	 	?>
     </td>
    </tr>
    <?php if($typeID==1){ ?>
    <tr align="left">
    	<td class="grey">Datum :</td>
        <td><?php echo $dateonly; ?></td>
    </tr>
    <tr align="left">
    	<td class="grey">Tid :</td>
        <td><?php echo $time; ?></td>
    </tr>
    <tr align="left">
    	<td class="grey">Antal personer :</td>
        <td><?php echo $num_people; ?></td>
    </tr>
    <tr align="left">
    	<td class="grey">Antal bord :</td>
        <td><?php echo $num_table; ?></td>
    </tr>
    <?php }
		else if($typeID==2){
	?>
    <tr align="left">
    	<td class="grey">Hämtas, datum :</td>
        <td><?php 
				if($date!=''){
					echo $dateonly;
				}
				else{
					echo '-';
				}
			?>
        </td>
    </tr>
    <tr align="left">
    	<td style="font-size: 22px; font-weight: 700;">Hämtas, tid :</td>
        <td style="font-size:22px; font-weight:700;"><?php 
			
			//if($asap==0){
				if($time=='00:00:00'){
					echo '-';
				}
				else{
					
					if($countdays==0){
						$prefx='Idag';
					}
					else if($countdays==1){
						$prefx='Imorgon';
					}
					else if($countdays<0){
						if($countdays==-1){
							$prefx='Igår';
						}
						else{
							//$prefx='Senaste '.abs($countdays).' dagar';
							$prefx='Passerat datum';
						}
					}
					else{
						$prefx='Om '.$countdays.' dagar';
					}
					
					
					
					$thetime = $time;
					if($asapdatetime!=''){
						$thetime = $asapdatetime;
					}
					
					if($asap==1){
						if($asapdatetime!=''){
							echo $prefx.', '.date("H:i",strtotime($thetime)); 
						}	
						else{
							echo 'Snarast';
						}
					}
					else{
						echo $prefx.', '.date("H:i",strtotime($thetime)); 
					}
					
				}
			/*}
			else{
				echo 'Snarast';
			}*/
			
			?>
         </td>
    </tr>	
    <?php	
		}
	?>
    <!--<tr align="left">
    	<td>Övrigt :</td>
        <td><?php //echo $note; ?></td>
    </tr>-->
    <tr align="left">
    	<td class="grey" style="font-size: 22px; color: #000; font-weight: bold;">Status :</td>
        <td style="font-size: 18px;font-weight: 700;">
        
        	<?php
			
			if($status==8){
			
				$condition='and (id=13 or id=14)';
            	if($asap==1){
					$condition='and (id<>13)';
				}
                	$q=mysql_query("select id,description from status where reservation_type_id=".$typeID." and deleted=0 $condition");
					$ccc = 0;
					while($r=mysql_fetch_array($q)){
						$ccc++;
					
						if($r[1] == 'Approve'){
							$action_name = 'Godkänd';
						}else if($r[1] == 'Cancel'){
							$action_name = 'Ej godkänd';
						}
						else{
							$action_name = $r['description'];
						}
					
					?>
      
                 <input type="button" class="order-status<?php echo ($ccc==1) ? ' defualt-text':''; echo ' order-status-'.strtolower(str_replace(' ','_',$action_name));?>" value="<?php echo $action_name;?>" data-rel="<?php echo $r[0];?>" />
                 
                 <?php 
				 	if( $action_name == '30 minuter'){
						echo "<div style='clear: both'></div>";
					}
				 ?>
                 
                 
                <?php		
				}
				
			}else{
					
					$q=mysql_query("select description from status where deleted=0 and id='".$status."'");
					$r=mysql_fetch_assoc($q);
					
					// echo $r['description'];
					if($r['description'] == 'Approve'){
						echo 'Godkänd beställning';
					}else if($r['description'] == 'Cancel'){
						echo 'Ej godkänd beställning';
					}
					else
						echo $r['description'];
				}
			?>
            
			<span class="reason"><?php echo $reason;?></span>
            <span class="thecustom"><?php if($customtime!=0) echo $customtime.' min'; ?></span>
		
        </td>
    </tr>
    <?php if($typeID==1){ ?>
    <tr align="left">
    	<td class="grey">Bekräfta :</td>
        <td><input type="checkbox" class="acknowledge" <?php if($acknowledged==1) echo 'checked="checked"';?>></td>
    </tr>
    <?php } ?>
    
    <?php 
	$q2=mysql_query("SELECT h.date_time, s.description AS status, CONCAT(a.fname,' ',a.lname) AS signatory_name, h.signatory 
					 FROM reservation_status_history h, account a, status s 
					 WHERE h.reservation_id=".$id." AND a.id=h.signatory AND h.status=s.id
					 ORDER BY h.date_time DESC LIMIT 1");
	$r2=mysql_fetch_assoc($q2);
	
	if($r2['signatory']>0){ ?>
    <tr align="left">
    	<td class="grey">Signatory :</td>
        <td><?php echo $r2['signatory_name']; ?></td>
    </tr>
    <?php } ?>
    <?php if($status==8){?>
    <tr align="right">
    	<td></td>
    	<td><!--<input type="button" class="btn order-detail-btn" value="Utför">--></td>
    </tr>
    <tr align="right" class="tmsg">
    	<td></td>
    	<td><div class="displaymsg"></div></td>
    </tr>
    <?php } ?>
</table>
</td>
<td valign="top">
<table style="box-sizing:border-box; padding:10px;">
	<tr align="left">
    	<td valign="top" class="grey">Betalsätt :</td>
        <td>
			<?php 
				if($payment=='cash'){
					echo 'Kort/Kontant';
				}
				else if($payment=='invoice'){
					echo '<strong>Faktura</strong>';
					$inv_query = mysql_query("select * from invoice where reservation_unique_id = '$uniqueid'") or die(mysql_error());
					if($inv = mysql_fetch_assoc($inv_query)){
						echo '<div class="invoice-info">';
						echo '<div><span class="grey">Företag:</span>'.$inv['business'].'</div>';
						echo '<div><span class="grey">Orgnr:</span>'.$inv['org_number'].'</div>';
						echo '<div><span class="grey">Postadress:</span>'.$inv['address'].'</div>';
						echo '<div><span class="grey">Postnr/Ort:</span>'.$inv['zip'].'</div>';
						echo '<div><span class="grey">Ref/Idnummer:</span>'.$inv['location'].'</div>';
						echo '</div>';
					}
				}
		   ?>
        </td>
    </tr>
    <tr align="left">
    	<td class="grey">Bearbetad av :</td>
        <td class="processedby"><?php echo processedBy($accountID); ?></td>
    </tr>
    <?php 
		$d_query = mysql_query("select * from reservation_delivery where reservation_unique_id = '$uniqueid'") or die(mysql_error());
		if(mysql_num_rows($d_query)>0){
	?>
    <tr align="left">
        <td colspan="2">
			<?php
				echo '<strong>Utkörniginformation</strong>';
				if($delivery = mysql_fetch_assoc($d_query)){
					echo '<div class="invoice-info">';
					echo '<div><span class="">Namn:</span>'.$delivery['d_name'].'</div>';
					echo '<div><span class="">Mobiltelefon:</span>'.$delivery['d_mobile'].'</div>';
					echo '<div><span class="">Adress:</span>'.$delivery['d_address'].'</div>';
					echo '<div><span class="">Portkod:</span>'.$delivery['d_buzz'].'</div>';
					echo '<div><span class="">Övrig:</span>'.$delivery['d_other'].'</div>';
					echo '<div><a href="javascript:void(0)" onclick="direct_print_delivery()" class="btn btn-print" style="display:inline-block; text-decoration:none; margin-top:10px;">Print</a></div>';
					echo '</div>';
				}
			?>
        </td>
    </tr>
    <?php } ?>
</table>
</td></tr></table>
<div style="clear:both;"></div>
<div class="displaymsg displaymsg-new"></div>
<a href="javascript:void(0);" class="generate-pdf pdf-btn">Generera till PDF</a>

<?php if($typeID==2){ ?>
<div class="menu-selected">
	
	<!--<span class="title">Beställda rätter</span>-->
	<table width="100%">
    	<tr style="background: #ddd;">
        	<!--<td>Typ</td>-->
        	<td style="padding: 8px 0; font-size: 19px; font-weight: 600;">Rätt</td>
            <td style="font-size: 19px; font-weight: 600; text-align: left;text-indent: 30px;">Tillval</td>
            <!--<td>Bild</td>-->
            <!--<td>Pris</td>-->
            <td style="font-size: 19px; font-weight: 600;">Antal</td>
            <td style="font-size: 19px; font-weight: 600;">Totalt</td>
            <td style="font-size: 19px; font-weight: 600;">Önskemål</td>
        </tr>
        <?php
        	$q=mysql_query("select m.name as menu, m.image as img, m.price as price, rd.quantity as quantity, cu.shortname as currency, rd.notes as notes, m.type as type, m.discount as discount,m.id as menu_id, rd.lunchmeny as lunchmeny, m.discount_unit as unit from reservation_detail as rd, menu as m, currency as cu where m.id=rd.menu_id and cu.id=m.currency_id and m.deleted=0 and rd.reservation_id='".$id."' and rd.lunchmeny=0") or die(mysql_error());
			$count=0;
			$total=0;
			$currency='';
			$opt_tot_all = 0;
			while($r=mysql_fetch_assoc($q)){
				$count++;
				$opt_tot = 0;
				$price=$r['price'];
				if(strlen($r['type'])>1){
					if($r['unit']=='percent'){
						$discount=$r['discount']/100;
						$price=$price-($price*$discount);
					}else{
						$price=$price-$r['discount'];
					}
					
				}
				
				
				$images='src="images/no-photo-available.png" width="30"';
				if($r['img']!=''){
					$images='src="uploads/'.$r['img'].'" width="60"';
				}
				
				$subtotal=$price*$r['quantity'];
				
		?>
        <tr style="background:<?php if($count%2==0) echo '#ddd'; else echo '#f8f8f8';?>" class="menuvalues">
        	<!--<td><?php 
				/*$lunchmeny = 'À la carte';
				if($r['lunchmeny']==1){
					$lunchmeny = 'Lunch';
				}
				echo strip_tags($lunchmeny);*/?></td>-->
        	<td width="300px" align="left"><?php echo '<div style="padding:10px 10px; font-size: 20px; font-weight: 600;">'.$r['menu'].'</div>'; ?></td>
            <td style="width: 300px;">
            <?php
            	
					$opt_sql = "select a.name as name,a.price as price, dish_num from reservation_menu_option as a where reservation_unique_id = '$uniqueid' and a.menu_id=$r[menu_id] order by dish_num";
					
					$opt_query = mysql_query($opt_sql) or die(mysql_error());
					if(mysql_num_rows($opt_query)>0){
						echo '<div style="text-align:left; padding:15px 5px 15px; font-size:15px;">';
						$counter = 0;
						while($opt = mysql_fetch_assoc($opt_query)){
								$opt_tot += $opt['price'];
								if($counter<$opt['dish_num']){
									$counter = $counter+1;
									if($counter > 1){
										echo '<br />';
									}
									echo 'Portion #'.$counter;
									
								}
								if($opt['price']==0){
									$opt_price = '0 kr';
								}else{
									$opt_price = number_format($opt['price'],0).' kr';
								}
								echo '<div class="invoice-info" style="padding-top:5px;"><em>';
								echo '<div><span>'.$opt['name'].'</span><label>'.$opt_price.'</label></div>';
								echo '</em></div>';
							}
						echo '</div>';
					}
					$subtotal +=$opt_tot;
			?>
            </td>
            <?php /*?><td><?php echo $r['sub'].' - '.getCategoryName($r['cid']); ?></td><?php */?>
           <!-- <td><img <?php /*echo $images; */?> ></td>-->
            <!--<td><?php /*echo $price.' kr';*/ ?></td>-->
            <td><?php echo $r['quantity']; ?></td>
            <td><?php echo $subtotal.' kr'; ?></td>
            <td width="150" align="left"><?php echo $r['notes']; ?></td>
        </tr>	
        <?php		
				$total+=$subtotal;
				$currency= 'kr';
			}
			
			
			//for lunch meny
			
			$qm=mysql_query("select m.name as menu, m.image as img, rd.price as price, rd.quantity as quantity, cu.shortname as currency, rd.notes as notes, m.type as type, m.discount as discount,m.id as menu_id, rd.lunchmeny as lunchmeny from reservation_detail as rd, menu_lunch_items as m, currency as cu where m.id=rd.menu_id and cu.id=m.currency_id and m.deleted=0 and rd.reservation_id='".$id."' and rd.lunchmeny=1") or die(mysql_error());
			
			while($r=mysql_fetch_assoc($qm)){
				$count++;
				$opt_tot = 0;
				$price=$r['price'];
				if(strlen($r['type'])>1){
					
					$discount=$r['discount']/100;
					$price=$price-($price*$discount);
				}
				
				
				$images='src="images/no-photo-available.png" width="30"';
				if($r['img']!=''){
					$images='src="uploads/'.$r['img'].'" width="60"';
				}
				
				$subtotal=$price*$r['quantity'];
				
		?>
        <tr style="background:<?php if($count%2==0) echo '#ddd'; else echo '#f8f8f8';?>" class="menuvalues">
        	<td><?php 
				$lunchmeny = 'À la carte';
				if($r['lunchmeny']==1){
					$lunchmeny = 'Lunch';
				}
				echo strip_tags($lunchmeny);?></td>
        	<td width="200px" align="left"><?php echo '<div style="padding:0 10px;">'.strip_tags($r['menu']).'</div>'; ?></td>
            <td>
            <?php
            	
					$opt_sql = "select a.name as name,a.price as price, dish_num from reservation_menu_option as a where reservation_unique_id = '$uniqueid' and a.menu_id=$r[menu_id] order by dish_num";
					
					$opt_query = mysql_query($opt_sql) or die(mysql_error());
					if(mysql_num_rows($opt_query)>0){
						echo '<div style="text-align:left; padding:10px 5px 10px; font-size:12px;">';
						$counter = 0;
						while($opt = mysql_fetch_assoc($opt_query)){
								$opt_tot += $opt['price'];
								if($counter<$opt['dish_num']){
									$counter = $counter+1;
									if($counter > 1){
										echo '<br />';
									}
									echo 'Portion #'.$counter;
									
								}
								if($opt['price']==0){
									$opt_price = '0 kr';
								}else{
									$opt_price = number_format($opt['price'],0).' kr';
								}
								echo '<div class="invoice-info" style="padding-top:5px;"><em>';
								echo '<div><span>'.$opt['name'].'</span><label>'.$opt_price.'</label></div>';
								echo '</em></div>';
							}
						echo '</div>';
					}
					$subtotal +=$opt_tot;
			?>
            </td>
            <?php /*?><td><?php echo $r['sub'].' - '.getCategoryName($r['cid']); ?></td><?php */?>
            <td><img <?php echo $images; ?> ></td>
            <td><?php echo $price.' kr'; ?></td>
            <td><?php echo $r['quantity']; ?></td>
            <td><?php echo $subtotal.' kr'; ?></td>
            <td width="150" align="left"><?php echo $r['notes']; ?></td>
        </tr>	
        <?php		
				$total+=$subtotal;
				$currency= 'kr';
			}
			
		?>
        <tr style="font-weight:bold; color:#00000c; background: #ddd;">
        	<td colspan="3" style="padding:8px 5px; text-align:right;">Slutsumma :</td>
            <td><?php echo $total+$opt_tot_all.' '.$currency; ?></td>
            <td></td>
        </tr>
    </table>
</div>
<?php } ?>
<br /><br />

<iframe style="display:none;" src="html2pdf/generate/print.php?id=<?php echo $id;?>" id="PDFtoPrint"></iframe>