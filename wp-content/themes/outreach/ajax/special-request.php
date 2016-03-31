<?php
	include 'config.php';
	
	$q=mysql_query("select * from reservation_detail where id='".$_POST['id']."'");
	$r=mysql_fetch_assoc($q);
   
	function checkOption($id,$dishnum){
		$str='';
		$q=mysql_query("select id from reservation_menu_option where reservation_unique_id='".$_POST ['chkuniqeid']."' and menu_option_id='".$id."' and dish_num='".$dishnum."'"); 
		if(mysql_num_rows($q)>0){
			$str='checked="checked"';
		}
		return $str; 
	}
	function getMenuImage($id){
		$q=mysql_query("select image from menu where id='".$id."'");
		$r=mysql_fetch_assoc($q);
		return $r['image'];
	}

//SPECIAL REQUEST BOX HEADER
?> 

<div class="special_request_menu <?php echo getMenuImage($r['menu_id'])!='' ? 'hasImg':''; ?>">
<?php 
	if(getMenuImage($r['menu_id'])!=''){ ?>
        <div class="thedish-img">
        	<img src="<?php echo $site_url; ?>webmin/uploads/<?php echo getMenuImage($r['menu_id']); ?>" class="dish-image">
        </div> <?php
	}	
?>
	<h4><?php echo $_POST['menu_det'];?></h4>
</div>
 
 


<div class="specialbox">


<?php
    $opt_q = mysql_query("select * from menu_option_details where menu_id = $r[menu_id] order by order_by, id") or die(mysql_error());
    $theoptioncount = mysql_num_rows($opt_q);
		if($theoptioncount>0){
		                               <?php if($r['quantity']>1){
                            echo '<div class="cart-opt-wrap" id="opt-holder-'.$r['id'].'">';
                             for ($x=1; $x <= number_format($r['quantity']); $x++) {
                                    echo '<div class="portion-head btn-'.$x.'-'.$r['id'].'">
												<input type="button" class="toggleoptin" alt="dish-holder-'.$x.'-'.$r['id'].'" value="Portion #'.$x.'"><a href="javascript:void(0)" class="remove-tillval" data-id="dish-holder-'.$x.'-'.$r['id'].'" data-rel="btn-'.$x.'-'.$r['id'].'" data-title="'.$r['id'].'" data-count="'.$x.'">X</a>
										</div>';
                                    
                                    $query_opt = mysql_query("select *, if(order_by=0,'A',order_by) as theorderby from menu_option_details where menu_id = $r[menu_id] order by theorderby, id") or die(mysql_error());
                                    
                                    echo '<div class="cart-opt" id="dish-holder-'.$x.'-'.$r['id'].'">';
                                    
                                    $theradiocount=0;
                                    
                                    while($opt = mysql_fetch_assoc($query_opt)){
                                        
                                        $theradiocount++;
                                        
                                        $qry=mysql_query("select id, name, price, if(order_by=0,'A',order_by) as theorderby from menu_options where menu_option_detail_id='".$opt['id']."' order by theorderby, id");
                                        
                                        ?>
                                        <div class="option-row">
                                        <h5 class="h5<?php echo $opt['id']; ?>"><?php echo $opt['name']; ?></h5>
                                        <?php
                                        
                                        $cnt=0;
                                        $total_cnt=mysql_num_rows($qry);
                                        $okay=0;
                                        
                                        while(list($optid, $optname, $optprice) = mysql_fetch_array($qry)){
                                    
                                            $cnt++;
                                    
                                        $pr_mu = ($optprice!=0) ? number_format($optprice,0).' '.getCurrentCurrency() : '';
                                        
                                    ?>	
                                        <div class="opt-item">
                                            <?php if($opt['single_option']==0){ ?>
                                            <input type="checkbox" rel="st-<?php echo $r['id']; ?>" alt="<?php echo $optprice; ?>" id="opt_<?php echo $x; ?>-<?php echo $optid; ?>" style="width:auto;" <?php echo checkOption($optid,$x);  ?>/>&nbsp;<label for="opt_<?php echo $x; ?>-<?php echo $optid; ?>" title="<?php echo $optname; ?>" class="theopt-name"><?php echo $optname; ?></label><span class="opt-pr-cur"><label><?php echo $pr_mu; ?></label></span>
                                            <?php }
                                            else{ ?>
                                            <input type="radio" rel="st-<?php echo $r['id']; ?>" alt="<?php echo $optprice; ?>" id="opt_<?php echo $x; ?>-<?php echo $optid; ?>" style="width:auto;" name="opt-radio-<?php echo $theradiocount.'-'.$x.'-'.$r['id']; ?>" <?php 
                                            
                                            if(checkOption($optid,$x)!=''){
                                                echo 'checked="checked"';
                                                $okay=1;
                                            }
                                            
                                            if($cnt==$total_cnt & $okay==0){
                                                echo 'checked="checked"';
                                            }
                                            
                                            ?>/>&nbsp;<label style="width:150px;" for="opt_<?php echo $x; ?>-<?php echo $optid; ?>" title="<?php echo $optname; ?>" class="theopt-name"><?php echo $optname; ?></label><span class="opt-pr-cur"><label><?php echo $pr_mu; ?></label></span>
                                            <?php } ?>
                                            
                                        </div>
                                   <?php
                                        }
										
										?>
										</div>
										<?php
                                        
                                    }
                                    echo '</div>';
                             }
                             echo '</div>';
                            }else{?>
                                
                                <div class="cart-opt-wrap" id="opt-holder-<?php echo $r['id'];?>">
                                    <div class="cart-opt" id="dish-holder-<?php echo $r['id'];?>">
                                    
                                    <?php 
                                    
                                    $theradiocount=0;
                                    
                                    while($opt = mysql_fetch_assoc($opt_q)){ 
                                        
                                        $theradiocount++;
                                        
                                            $qry=mysql_query("select id, name, price, if(order_by=0,'A',order_by) as theorderby from menu_options where menu_option_detail_id='".$opt['id']."' order by theorderby, id");
                                            
                                        ?>
                                        
                                        <div class="option-row">
                                        
                                        <h5 class="h5<?php echo $opt['id']; ?>"><?php echo $opt['name']; ?></h5>
                                        <?php
                                        
                                            $cnt=0;
                                            $okay=0;
                                            $total_cnt=mysql_num_rows($qry);
                                            
                                            while(list($optid, $optname, $optprice) = mysql_fetch_array($qry)){
                                    
                                                $cnt++;
                                    
                                    ?>
                                        <div class="opt-item">
                                        
                                        <?php if($opt['single_option']==0){ ?>
                                             <input type="checkbox" rel="st-<?php echo $r['id'];?>" alt="<?php echo $optprice;?>" id="opt_1-<?php echo $optid;?>" <?php echo checkOption($optid,1); ?>/>
                                        <?php }else{ 
                                        ?>
                                            <input type="radio" rel="st-<?php echo $r['id'];?>" alt="<?php echo $optprice;?>" id="opt_1-<?php echo $optid;?>" <?php 
                                            
                                            
                                                if(checkOption($optid,1)!=''){
                                                    echo 'checked="checked"';
                                                    $okay=1;
                                                }
                                                
                                                if($cnt==$total_cnt & $okay==0){
                                                    echo 'checked="checked"';
                                                }
                                            
                                            ?> name="opt-radio-<?php echo $theradiocount.'-'.$r['id']; ?>">
                                        <?php } ?>
                                        
                                            <label id="opt-name" class="theopt-name" for="opt_1-<?php echo $optid;?>" title="<?php echo $optname; ?>"><?php echo $optname;?></label>&nbsp;
                                            <span class="opt-pr-cur">
                                            <label id="opt-pr"><?php echo ($optprice!=0)? number_format($optprice,0).' '.getCurrentCurrency() : '';?></label>
                                            </span>
                                            
                                        </div>
                                 <?php }  //end while
                                 
                                 ?>                             
                                    </div>                             
                                 <?php
                                 
                                    } //end while
                                 ?>
                                 </div>                        
                             </div>
                             
                            <?php }?>
                         
                         <?php }?>
    
<?php } ?>

	<div class="request-msg-row"><h5>Speciella önskemål</h5><textarea cols="8" class="requests-message"><?php echo $r['notes'];?></textarea> </div>
    
</div>

<?php

	function getOptionTotal($menu_id){
		$total=0;
		
		$q=mysql_query("select sum(price) as total from reservation_menu_option where menu_id='".$menu_id."' and reservation_unique_id='".$_POST ['chkuniqeid']."'");
		$r=mysql_fetch_assoc($q);
		
		if(mysql_num_rows($q)>0){
			$total = $r['total'];
		}
		
		return $total;
	}

	$thetotal = 0;
	$subTotal=0;

	//get the total
	$qt=mysql_query("select * from reservation_detail where reservation_id=(select id from reservation where deleted=0 and uniqueid='".$_COOKIE['takeaway_id']."' order by id desc limit 1)") or die(mysql_error());
	
	if(mysql_num_rows($qt)>0){
		 while($rt=mysql_fetch_assoc($qt)){
			 if($r['menu_id'] == $rt['menu_id']){
			 $subTotal =  (($rt['price']*$rt['quantity'])+getOptionTotal($rt['menu_id']));	

			 }

			 if($rt['id']==$_POST['id']){
				 $theoption = 0;
			 }
			 $theoption = getOptionTotal($rt['menu_id']);
			 $theprice=(($rt['price']*$rt['quantity'])+$theoption);
			 $thetotal+=$theprice;
			 
		 }
		 $subTotal = $subTotal;
	}

?>

<div style="text-align:center;" class="reqtotal" data-price="<?php echo $_POST['price']; ?>">
	<?php
    	//if(isset($temp_tillval)){
	?>
	<span><strong>Totalt pris:</strong> <label data-total="<?php echo $subTotal; ?>"><?php echo $subTotal; ?></label></span>
    <?php
		//}
	?>
	<input type="button" value="Fortsätt" id="btn_skip" data-id="<?php echo $_POST['id']; ?>" data-placeholder="<?php echo $r['menu_id']; ?>" data-rel="<?php echo $_COOKIE['takeaway_id'];?>" data-title="<?php echo getMenuName($r['menu_id']);?>"/>
</div>

<?php
	$thetemptillval = 0;
	if(isset($temp_tillval)){
		$thetemptillval = 1;
	}
?>
<script>
 
	
	
   $('#btn_skip').click(function(){
	  var siteurl = $('#main-table').attr('data-rel');
	  var addons='';

	  var menu_id = $(this).attr('data-placeholder');
	  var uniq = $(this).attr('data-rel');
	  var check = true; 
	  var notes = $('#requests-message').val();
	  var resid = $(this).attr('data-id');
	  
	  $('.cart-opt input[type=checkbox]:checked, .cart-opt input[type=radio]:checked').each(function(){
		var id=$(this).attr('id'); 
		var price=$(this).attr('alt');
		var name = $('label[for='+id+']').attr('title');
		
		addons+=id+'*'+price+'*'+name+'^';
		
	  });
	  
	  addons = addons.substring(0, addons.length-1);
	  
	  $.ajax({
		url: siteurl+"/ajax/save-options.php",
		type: 'POST',
		data: 'menu_id='+encodeURIComponent(menu_id)+'&uniq='+encodeURIComponent(uniq)+'&notes='+encodeURIComponent(notes)+'&res_detail_id='+encodeURIComponent(resid)+'&addons='+encodeURIComponent(addons)+'&thetemptillval='+encodeURIComponent('<?php echo thetemptillval; ?>')+'&check=check',
		success: function(value){
			var obj = $.parseJSON(value);
			$('#subtotal-'+resid).html(obj.subtotal);
			$('.btn_optional_dish-'+resid).removeClass('active');
			$('.total-amt').html(obj.total);
            $('.order-option').hide();
		}
	 });
	  
   });
   	
	</script>
