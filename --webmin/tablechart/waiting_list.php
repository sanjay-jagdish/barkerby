<?php
require('../config/config.php');
ini_set('display_errors',0);
$parameters = explode(' ',$_POST['parameters']);

$date = strip_tags(mysql_real_escape_string($_POST['date']));
$date_selected = $date;

$wait_list_sql = "SELECT * FROM wait_list WHERE date='".date('m/d/Y',strtotime($date))."' AND status=0 AND deleted=0";
$wait_list_qry = mysql_query($wait_list_sql);
$wait_list_num = mysql_num_rows($wait_list_qry);

if($wait_list_num==0){
	echo '<font color="red">There are no guests on waitlist.</font>';
}else{
?>
<script>
$('.WLreserved, .WLtable_block').click(function(e){
	
	e.preventDefault();
	var parameters = jQuery(this).attr('data-rel');
	jQuery('.fadediv, .loaddiv').fadeIn();									
		
	jQuery.ajax({
			 url: "tablechart/waitlist_accommodation.php",
			 type: 'POST',
			 data: 'res_id='+parameters+'&date=<?php echo $date_selected;?>',
			 success: function(value){
				jQuery('.fadediv, .loaddiv').fadeOut();
				modal.open({content: value});

			 }
	});			
	
});				

</script>
<!--
<div class="container avails-grid" id="booking_by_time">

	 <p class="loading alert" style="z-index:100000; margin-top:200px;">One moment please... Loading Table Chart...</p> 
	
    <div class="table-reservations-list table-container show-table" style="position:relative; width:650px; height:239px; overflow: scroll;">
-->
	<h3 style="margin:0px;">Waiting List</h3>

   		<table class="table table-condensed waiting-list table-bordered" style="width:1100px;">
        	<thead>
            	<tr>
                	<th style="width:1px;"><div style="text-align:center; color:#000 !important;">Från</div></th>
                	<th style="width:1px;"><div style="text-align:center;">Till</div></th>
                 	<th style="text-align:center; width:1px !important;">Antal</th>
                	<th style="text-align:center">Bord</th>
        	       	<th style="text-align:left;">Gäst</th>
        	       	<th style="text-align:left;">Notering</th>
                </tr>
            </thead>

<?php
$table_reservations_sql = "SELECT r.id, r.date, r.time, ADDTIME(TIME(r.time), SEC_TO_TIME(r.duration*60)) AS end, r.duration, r.tables, 
							a.fname, a.lname, r.number_people, r.notes, r.status  
							FROM wait_list r
							LEFT JOIN account a ON a.id=r.account_id
							WHERE r.date='".date('m/d/Y',strtotime($date_selected))."'
							AND r.status=0  AND r.deleted=0
							ORDER BY id ASC ";

$table_reservations_qry = mysql_query($table_reservations_sql);
$table_reservations_num = mysql_num_rows($table_reservations_qry);
if($table_reservations_num>0){
	echo '<tbody>';
	while($table_reservations = mysql_fetch_assoc($table_reservations_qry)){

		switch($table_reservations['status']){
			case 1:
				$status_class = 'green';
				break;				
			case 2:
				$status_class = 'red';
				break;				
			case 3:
				$status_class = 'black';
				break;							
			default:
				$status_class = 'blue';
		}

?>
	<tr class="WLreserved" data-rel="<?php echo $table_reservations['id']; ?>">
    	<td class="<?php echo $status_class; ?>" align="center" nowrap width="1%"><?php echo substr($table_reservations['time'],0,5); ?></td>
        <td class="<?php echo $status_class; ?>" nowrap width="1%"><?php echo date('H:i', strtotime($table_reservations['time'].' + '.$table_reservations['duration'].' minutes')); ?></td>
        <td class="<?php echo $status_class; ?>" style="text-align:center;"><?php echo $table_reservations['number_people']; ?></td>
        <td class="<?php echo $status_class; ?>" style="text-align:center;">
		<?php 
		$tables = explode(',',$table_reservations['tables']); 
		$table_count = count($tables);
		foreach($tables as $k => $table_id){
			$table_number_sql = "SELECT table_name FROM table_detail WHERE id=".$table_id;
			$table_number_qry = mysql_query($table_number_sql);
			$table_number_res = mysql_fetch_assoc($table_number_qry);
			echo $table_number_res['table_name'];
			if($table_count>($k+1) && $k>=0){ echo ', ';}	
		}
		?>
        </td>
        <td class="<?php echo $status_class; ?>" style="text-align:left;">
		<?php 
		if(trim($table_reservations['fname']).trim($table_reservations['lname'])!=''){ 
			echo $table_reservations['fname'].' '.$table_reservations['lname'];
		}else{
			echo '***Walk-In***';	
		}
		?>
        </td>
        <td class="<?php echo $status_class; ?>" style="text-align:left;"><?php echo $table_reservations['notes']; ?></td>
    </tr>
<?php
	}
	echo '</tbody>';
}
?>

        	<tfoot>
            	<tr>
                	<th align="center"><div style="text-align:center;">Från</div></th>
                	<th align="center"><div style="text-align:center;">&nbsp;&nbsp;Till&nbsp;&nbsp;</div></th>
                	<th align="center"><div style="text-align:center;">Antal</div></th>
                	<th align="center"><div style="text-align:center;">Bord</div></th>
                	<th align="center">Gäst</th>
                	<th align="left">Notering</th>
                </tr>
            </tfoot>            
            
        </table>	
<!--
    </div>
    </div>
-->
<?php
}//if there is a scheduled business/work
?>