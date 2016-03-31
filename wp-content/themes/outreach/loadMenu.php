<?php
	include 'config.php';
	
	$price='';
?>

<input type="hidden" id="thestr" value="<?php echo $_POST['str'];?>">

<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/themes/crisp_version3/scripts/numeric.js"></script>
<script type="text/javascript">

 function removeStartZero(str){
		   
			if(str.charAt(0)==0){
				str=str.substr(1,str.length);
			}
			
			return str;
	   }

 function computeTotal(){
	 var total=0;
	 
	 $('.quantity').each(function() {
			var val=$(this).val();
			if(val!='' && val!=0){
				var id=$(this).attr('data-rel');
				
				var subtotal=Number($('.subtotal-'+id).html());
		
				total+=subtotal;
			}
	  });
	 
	   
	   $('.total').html(total.toFixed(2));
 }
	   
  $(function(){
	   
	   
	   //set default values
	   if(selectedmenu_id.length > 0){
		   
		   if($('#thestr').val()=='viewdetails'){
		   	$('.thecats, .menubtn, .categ').hide();
		   }
		   
		   var settotal=0;
		   for(i=0;i<selectedmenu_id.length;i++){
			   
			   $('.menu-'+selectedmenu_id[i]).show();
			   
			   $('.quan-'+selectedmenu_id[i]).val(selectedmenu_quantity[i]);
			   $('.srequest-'+selectedmenu_id[i]).val(selectedmenu_request[i]);
			   
			   var setsubtotal=Number(selectedmenu_price[i]) * Number(selectedmenu_quantity[i]);
			   $('.subtotal-'+selectedmenu_id[i]).html(setsubtotal.toFixed(2));
			   
			   settotal+=setsubtotal;
			   
		   }
		   
		   $('.total').html(settotal.toFixed(2));
		   
	   }
	  
       $('.quantity').numeric();
	   
	   $('.catli').click(function(){
		   
		   var id=$(this).attr('data-rel');
		   
		   if(id=='all'){
			  $('.categ').fadeIn(); 
		   }
		   else{
			   $('.categ').hide();
			   
			   $('.categ-'+id).fadeIn();
		   }
		  
	   });
	   
	   
	   
	   $('.quantity').keyup(function(){
		   $(this).val($(this).val().replace('.','').replace('-',''));
		   
		   var val = $(this).val();
		   var id = $(this).attr('data-rel');
		   
		  
		   if(val<=0  || val==''){
			   $('.subtotal-'+id).html('');
		   }
		   else{
			   var price=Number($('.subtotal-'+id).attr('data-rel'));
			   $('.subtotal-'+id).html((price*val).toFixed(2));
		   }
		   
		   computeTotal();
	   });
	   
	   $('.quantity').focusout(function(){
		   var val = $(this).val();
		   var id = $(this).attr('data-rel');
		   
		  
		   if(val<=0  || val==''){
			   $('.subtotal-'+id).html('');
		   }
		   else{
			   var price=Number($('.subtotal-'+id).attr('data-rel'));
			   $('.subtotal-'+id).html((price*val).toFixed(2));
		   }
		   
		   computeTotal();
		   
	   });
	   
	  
	   
	   $('.menubtn').click(function(){
		  
		  var count=0;
		  
		  $('.quantity').each(function() {
          		var val=$(this).val();
				if(val!='' && val!=0){
					count+=1;
				}
          });
		  
		  $('.themenus .displaymsg').fadeOut('slow');
		   
		   selectedmenu_id.splice(0, selectedmenu_id.length);
		   selectedmenu_quantity.splice(0, selectedmenu_quantity.length);
		   selectedmenu_request.splice(0, selectedmenu_request.length);
		   selectedmenu_price.splice(0, selectedmenu_price.length);
		   
		   if(count>0){
			   
			   
			   $('.quantity').each(function() {
					var val=$(this).val();
					if(val!='' && val!=0){
						
						var id= $(this).attr('data-rel');
					
						selectedmenu_id.push(id);
						selectedmenu_quantity.push(Number(removeStartZero($('.quan-'+id).val())));
						selectedmenu_request.push($('.srequest-'+id).val());
						selectedmenu_price.push(Number($('.subtotal-'+id).attr('data-rel')));
						
					}
			   });
			   
			  $('.step-1 .steps-button').show(); 
			   
			  $('.fade4, .menu-box, .steps-container .displaymsg').fadeOut();
			  
			  var msg="'viewdetails'";
			  $('.totaldue cite').html('Summa att betala: <strong>Kr '+Number($('.total').html()).toFixed(2)+'</strong> <a href="javascript:void" class="view-details" onclick="loadMenu('+msg+')"\>Visa detaljer</a>');
			  
			  $('html, body').animate({
					scrollTop: $("#text-16").offset().top
			  }, 1000);
				
		   }
		   else{
			   $('.themenus .displaymsg').fadeIn('slow').addClass('errormsg').html('No menu selected.');
		   }
		   
	   });
	   
	   $('.menu-box .closebox').click(function(){
			  
			   var count=0;
		  
			  $('.quantity').each(function() {
					var val=$(this).val();
					if(val!='' && val!=0){
						count+=1;
					}
			  });
			  
			  if(count==0){
				  $('.step-1 .steps-button').hide(); 
				  $('.step-1 .totaldue label, .step-1 .totaldue cite').html(''); 
			  }
			  
	   });
	   
	   
  });
  
  
  

</script>

<div class="thecats">
	<ul>
    	<li><a href="javascript:void" class="catli" data-rel="all">All</a></li>
    	<?php
		
        	$qs=mysql_query("select name, id from category where deleted=0");
			while($row=mysql_fetch_assoc($qs)){
		?>
        <li>
        <span><?php echo $row['name'];?></span>
        <?php
        		$q=mysql_query("select id,name from sub_category where deleted=0 and category_id=".$row['id']);
				while($r=mysql_fetch_array($q)){
		?>
        <a href="javascript:void" class="catli" data-rel="<?php echo $r[0];?>"><?php echo $r[1];?></a>
        <?php		
				}
		?>
        </li>
        <?php
			}
		?>
    </ul>
</div>
<div class="themenus">
	<table border="0px">
    	<tr style="background:#ddd !important; color:#000; font-weight:bold;">
            <!--<td width="35px"><input type="checkbox" class="allcheck"></td>-->
            <td>Rätt</td>
            <td>&nbsp;</td>
            <td width="100px">Pris</td>
            <td width="150px">Antal</td>
            <td width="60px">Summa</td>
            <td>Specialla önskemål</td>
        </tr>
    <?php
    	$q=mysql_query("select m.id, m.name, m.image, c.shortname, ca.id, m.price, m.type, m.discount, m.description from menu as m, currency as c, sub_category as ca where c.id=m.currency_id and m.deleted=0 and ca.id=m.sub_category_id and m.type<>'1'");
		
		$count=0;
		$cur = '';
		while($r=mysql_fetch_array($q)){
			$count++;
	?>
    	<tr class="categ categ-<?php echo $r[4];?> menu-<?php echo $r[0]; ?> <?php if($count%2==0) echo 'odd'; else echo 'even'; ?>">
        	<!--<td align="center"><input type="checkbox" class="thecheck" data-rel="<?php //echo $r[0]; ?>"></td>-->
        	<td class="fordeschover">
				<?php echo $r[1];?>
            	<div class="deschover">
                	<p><?php echo $r[8]; ?></p>
                </div>
            </td>
            <td>
            	<?php
                	if($r[2]!=''){
						$src='uploads/'.$r[2];
					}
					else{
						$src='';
					}
					
					$price=$r[5];
					if(strlen($r[6])>1){
						$discount=$r[7]/100;
						$price=$price-($price*$discount);
					}
					
				if($src!=''){	
				?>
            	<img src="<?php echo $site_url; ?>/webmin/<?php echo $src; ?>" style="width:50px !important;">
            	<?php } ?>
            </td>
            <td><?php echo $r[3].' '.$price; ?></td>
            <td><input type="text" class="quantity quan-<?php echo $r[0]; ?>" <?php if($_POST['str']!=''){ echo 'readonly="readonly"'; }?> style="width:70px" data-rel="<?php echo $r[0]; ?>"></td>
            <td><?php echo $r[3];?>&nbsp;<span class="subtotal subtotal-<?php echo $r[0]; ?>" data-rel="<?php echo $price; ?>" data-id="<?php echo $r[3]; ?>"></span></td>	
            <td><textarea class="srequest srequest-<?php echo $r[0]; ?>" <?php if($_POST['str']!=''){ echo 'readonly="readonly"'; }?>></textarea></td>
        </tr>
    <?php	
			$cur=$r[3];	
		}
	?>   
    	<tr style="background:#ddd !important;">
        	<td colspan="5" style="text-align:right !important;"><label style="font-weight:bold;">Summa :</label></td>
            <td><strong><?php echo $cur;?>&nbsp;</strong><span class="total" style="font-weight:bold;"></span></td>
        </tr> 
    </table>
    <input type="button" class="menubtn" value="Fortsätt">
    <!--<a href="#text-16" class="menubtn btn">Proceed</a>-->
    <div class="clear"></div>
    <div class="displaymsg"></div>
</div>