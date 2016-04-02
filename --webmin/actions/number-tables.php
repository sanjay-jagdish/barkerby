<?php session_start();
	include '../config/config.php';
	
	$id=strip_tags($_POST['id']);
	
	$q=mysql_query("select max_pax,table_name from table_detail where restaurant_detail_id=".$id." order by id");
	$count=mysql_num_rows($q);
	
	$thepax=array();
	$tablename=array();
	while($r=mysql_fetch_array($q)){
		$thepax[]=$r[0];
		$tablename[]=$r[1];
	}
	
?>

<script type="text/javascript">
	jQuery(function(){
		
		jQuery('.add-table').click(function(){
			
			var table_count=Number(jQuery('span.the-tables').length);
			
			jQuery('.settable-inner').append('<span class="tables the-tables" id="table-'+(table_count+1)+'"><input type="text" class="thetablename txt" style="width: 90px; margin-right: 15px;" placeholder="Table Name"><input type="text" class="thetable txt" style="margin: 0 10px 0 0;"><a href="javascript:void;" class="delete-table" onclick="deleteTable('+(table_count+1)+')"><img src="images/delete-small.png" style="position: relative; top: 7px;"></a></span><div class="clear"></div>');
			
			
		});

		jQuery('.thetable').numeric();

		jQuery('.thetable').keyup(function(){
			jQuery(this).val(jQuery(this).val().replace('.',''));
		});

		jQuery('.thetablename').focusout(function(){
			var id=jQuery(this).attr('id');
			var str=jQuery(this).val();
			jQuery(this).attr('value',str);

			jQuery('.thetablename').removeClass('redborder');

			var theval=new Array();
			var thesame=new Array();

			jQuery('.thetablename').each(function(){
				var val=jQuery(this).val().trim();
				if(theval.indexOf(val)== -1){
					theval.push(val);	
				}
				else{
					thesame.push(val);
				}
				
			});

			for(i=0;i<thesame.length;i++){
				jQuery('.thetablename[value='+thesame[i]+']').addClass('redborder');
			}


		});

	});
</script>	

<span class="tables the-tables" id="table-1"><input type="text" class="thetablename txt" style="width: 90px; margin-right: 15px;" placeholder="Table Name" value="<?php echo $tablename[0];?>"><input type="text" class="thetable txt" style="margin: 0 10px 0 0;" value="<?php echo $thepax[0];?>"><a href="javascript:void;" class="add-table"><img src="images/add-small.png" style="position: relative; top: 7px;"></a></span>
<div class="settable-inner">
<?php
	$j=1;
	
	for($i=0;$i<($count-1);$i++){
?>
	<span class="tables the-tables" id="table-<?php echo ($i+2); ?>"><input type="text" class="thetablename txt" style="width: 90px; margin-right: 15px;" placeholder="Table Name" value="<?php echo $tablename[$j];?>"><input type="text" class="thetable txt" style="margin: 0 10px 0 0;" value="<?php echo $thepax[$j];?>"><a href="javascript:void(0)" class="delete-table" onclick="deleteTable('<?php echo ($i+2); ?>')"><img src="images/delete-small.png" style="position: relative; top: 7px;"></a><a href="javascript:void(0)" style="display:none" class="activate-table" onclick="activateTable('<?php echo ($i+2); ?>')"><img src="images/check-small.png" style="position: relative; top: 7px;"></a></span><div class="clear"></div>
<?php		
		$j++;
	}
?>
</div>
<?php		
	
?>	