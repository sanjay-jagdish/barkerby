<?php session_start();
	  include '../config/config.php';
?>
<script type="text/javascript">
	jQuery(function(){
		//for data-table * orders.php
		jQuery('#theorderstake').dataTable( {
			"aoColumns": [
				{ "asSorting": [ "desc", "asc", "asc" ] },
				{ "asSorting": [ "desc", "asc", "asc" ] },
				{ "asSorting": [ "desc", "asc", "asc" ] },
				{ "asSorting": [ "desc", "asc", "asc" ] },
				null,
				null
			]
		} );
	});
</script>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="theorderstake">
    <thead>
        <tr>
            <th>Customer</th>
            <th>Date & Time</th>
            <th>Number of Persons</th>
            <th>Number of Tables</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody class="fortake-content">
     <?php
        
        $q=mysql_query("select rt.description, concat(a.fname,' ',a.lname), r.date, r.time, r.number_people, r.number_table, r.approve, r.id, rt.id, r.lead_time, r.acknowledged from reservation_type as rt, account as a, reservation as r where rt.id=r.reservation_type_id and a.id=r.account_id and r.deleted=0 and rt.id in (2,3)");
        while($r=mysql_fetch_array($q)){
     ?>
            <tr class="gradeX gradeX-<?php echo $r[7];?>" align="center" onclick="showDetail('<?php echo $r[7];?>','<?php echo $r[8];?>')">
                <td class="<?php if($r[8]==1){ if($r[10]==0) echo 'blink'; }else{ if($r[6]==8) echo 'blink'; }  ?>"><?php echo $r[1]; ?></td>
                <td class="<?php if($r[8]==1){ if($r[10]==0) echo 'blink'; }else{ if($r[6]==8) echo 'blink'; }  ?>"><?php echo date("M d, Y",strtotime($r[2])); ?><br><?php echo $r[3]; ?></td>
                <td class="<?php if($r[8]==1){ if($r[10]==0) echo 'blink'; }else{ if($r[6]==8) echo 'blink'; }  ?>"><?php echo $r[4]; ?></td>
                <td class="<?php if($r[8]==1){ if($r[10]==0) echo 'blink'; }else{ if($r[6]==8) echo 'blink'; }  ?>"><?php echo $r[5]; ?></td>
                <td class="<?php if($r[8]==1){ if($r[10]==0) echo 'blink'; }else{ if($r[6]==8) echo 'blink'; }  ?>"><?php echo orderStatus($r[6]); if($r[6]==12){ echo ' <br> '.$r[9].' min'; } ?></td>
                <td class="<?php if($r[8]==1){ if($r[10]==0) echo 'blink'; }else{ if($r[6]==8) echo 'blink'; }  ?>"><a href="#" onclick="showDetail('<?php echo $r[7];?>','<?php echo $r[8];?>')"><img src="images/info.png"></a></td>
            </tr>
     <?php } 
mysql_close($con);
     ?>   
    </tbody>
    <tfoot>
        <tr>
            <th>Customer</th>
            <th>Date & Time</th>
            <th>Number of Persons</th>
            <th>Number of Tables</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </tfoot>
</table>