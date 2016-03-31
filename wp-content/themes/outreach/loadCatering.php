<?php
	include 'config.php';
	
	$q=mysql_query("select var_value from settings where var_name='week_starts'");
	$row=mysql_fetch_assoc($q);
	
?>

<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/themes/crisp_version3/scripts/numeric.js"></script>
<script type="text/javascript">
	$(function(){
		
		
		loadExist();
			
		$('.numpeople').numeric();
		
		$('.numpeople').keyup(function(){
			$(this).val($(this).val().replace('.','').replace('-',''));	
			
			var id=$(this).attr('data-id');
			var price=Number($(this).attr('data-rel'));
			var num=Number($(this).val());
			
			$('.subtotal'+id).html((price*num).toFixed(2));
			$('.ctotal').html(getTotal());
		});
		
		$('.cateringmenus .btn').click(function(){
			var total = Number($('.ctotal').html());
			
			$('.cateringmenus .displaymsg').fadeOut();
			
			clearCateringArray();
			
			if(total>0){
				
				$('.cater').each(function(){
					
					
					var id = $(this).attr('id');
					var quan = Number($('.numpeople'+id).val());
					var price = Number($('.numpeople'+id).attr('data-rel'));
					var req = $('.cateringreq'+id).val();
					
					if(quan > 0){
						
						catering_id.push(id);
						catering_quantity.push(quan);
						catering_request.push(req);
						catering_price.push(price);
						
					}
					
				});
				
				if(catering_id.length > 0){
					
					$('.catering-details span').html('Kr '+getTotal());
					$('.catering-details').css('display','block');
					
					$('.fade4, .catering-box').fadeOut();
					
				}
				
				
			}
			else{
				$('.cateringmenus .displaymsg').fadeIn().addClass('errormsg').html("You haven't selected a catering package.");	
			}
			
		});
		
		$('.catering-box .closebox').click(function(){
			if(catering_id==0){
				$('.catering-details').fadeOut();
			}
		});
		
		$('.caterbtn1').click(function(){
			$('.catering-detail1').fadeOut();
			$('.catering-detail2').fadeIn();
		});
		
	});
	
	
	function loadExist(){
		if(catering_id.length > 0){
			for(i=0;i<catering_id.length;i++){
				$('.numpeople'+catering_id[i]).val(catering_quantity[i]);
				$('.cateringreq'+catering_id[i]).val(catering_request[i]);
				$('.subtotal'+catering_id[i]).html((Number(catering_quantity[i]) * Number(catering_price[i])));
			}
			
			$('.ctotal').html(getTotal());
		}
	}
	
	
	function clearCateringArray(){
		catering_id.splice(0, catering_id.length);
		catering_quantity.splice(0, catering_quantity.length);
		catering_request.splice(0, catering_request.length);
		catering_price.splice(0, catering_price.length);
	}
	
	function getTotal(){
		var total=0;
		$('.subtot').each(function(){
			var val = Number($(this).html());
			total+=val;
		});
		
		return total.toFixed(2);
	}
</script>

<div class="cateringmenus">
	<table border="0px">
    	<tr style="background:#ddd !important; color:#000; font-weight:bold;">
            <td width="200px">Name</td>
            <td width="100px">Pris</td>
            <td width="150px">Number of People</td>
            <td width="100px">Subtotal</td>
            <td width="200px">Requests</td>
        </tr>
        <?php
        	$q=mysql_query("select * from catering where deleted=0");
			$count=0;
			while($r=mysql_fetch_assoc($q)){
				$count++;
		?>
        <tr class="cater cater-<?php echo $r['id'];?> <?php if($count%2==0) echo 'odd'; else echo 'even'; ?>" id="<?php echo $r['id'];?>">
        	<td class="forcaterhover"><?php echo $r['name'];?>
            	<div class="caterhover">
                    <?php echo $r['description']?>
                </div>
            </td>
            <td>Kr <?php echo round($r['price'],2);?></td>
            <td><input type="text" class="numpeople numpeople<?php echo $r['id']; ?>" data-id="<?php echo $r['id'];?>" data-rel="<?php echo round($r['price'],2); ?>" style="width:80px !important;"></td>
            <td>Kr <span class="subtot subtotal<?php echo $r['id'];?>"></span></td>
            <td>
            	<textarea data-id="<?php echo $r['id'];?>" class="cateringreq cateringreq<?php echo $r['id']; ?>" style="width:100%;"></textarea>
            </td>
        </tr>
        <?php		
			}
		?>
        <tr style="background:#ddd !important; color:#000; font-weight:bold;">
        	<td colspan="4" style="text-align:right !important;">Total :</td>
            <td>Kr <span class="ctotal"></span></td>
        </tr>
    </table>
    
    <input type="button" value="Proceed" class="btn">
    
    <div class="displaymsg"></div>
</div>