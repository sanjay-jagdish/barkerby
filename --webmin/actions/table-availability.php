<?php session_start();
	include '../config/config.php';
	
	$id=strip_tags($_POST['id']);
	
	 $q=mysql_query("select days, start_time, end_time, id, start_date, end_date from restaurant_detail where id=".$id) or die(mysql_error());
	 $r=mysql_fetch_array($q); 
?>
<div class="table-label" style="text-transform:uppercase; font-size:12px;">
	<?php echo date("M d, Y",strtotime($r[4])); ?> - <?php echo date("M d, Y",strtotime($r[5])); ?><br>
    <?php echo $r[0];?><br>
    <?php echo $r[1]. ' - '. $r[2];?>
</div>
<table width="100%" border="1" style="margin:20px 0;">
	<tr style="background:#ddd;">
    	<th>Table Name</th>
        <th>Max Pax</th>
        <th>Status</th>
    </tr>
    <?php
    	$q=mysql_query("select table_name,max_pax,status from table_detail where restaurant_detail_id=".$id." order by table_name");
		while($r=mysql_fetch_array($q)){
	?>
    <tr>
    	<td><?php echo $r[0];?></td>
        <td><?php echo $r[1];?></td>
        <td><?php if($r[2]==0) echo '<font color="green">Available</font>'; else echo '<font color="red>Taken</font>';?></td>
    </tr>	
    <?php		
		}
	?>
</table>	