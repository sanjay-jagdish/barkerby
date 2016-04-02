<?php
include_once('redirect.php');

require('../config/config.php');
ini_set('display_errors',0);
$res_id = trim($_POST['res_id']);

$res_details_sql = "SELECT r.account_id AS id, r.date, r.time, r.duration, ADDTIME(TIME(r.time), SEC_TO_TIME(r.duration*60)) AS end, r.duration, a.fname, a.lname, a.phone_number, 
						r.number_people AS pax, r.note 
						FROM reservation r
						LEFT JOIN account a ON a.id=r.account_id 
						WHERE r.approve=2 AND r.id=".$res_id;
$res_details_qry = mysql_query($res_details_sql);
$res_details_num = mysql_num_rows($res_details_qry);

if($res_details_num>0){
	
	$res_details_res = mysql_fetch_assoc($res_details_qry);

	$currentDate = $res_details_res['date'];
	$time_start = $res_details_res['time'];
	$time_end = $res_details_res['end'];

	if(trim($res_details_res['fname'].$res_details_res['lname'])!=''){
		$client = $res_details_res['fname'].' '.$res_details_res['lname'].' ['.$res_details_res['phone_number'].']';
	}else{
		$client = '***Walk-In***';
	}
	

	$numguest=$res_details_res['pax'];
	$time = substr($res_details_res['time'],0,5).' &rarr; '.substr($res_details_res['end'],0,5);	


	$hrs = gmdate("H", ($res_details_res['duration'] * 60));
	$mins = gmdate("i", ($res_details_res['duration'] * 60));
	$duration = 'Hrs: <strong>'.$hrs.'</strong> &nbsp;Mins: <strong>'.$mins.'</strong>';

	$dayName=date('D', strtotime($currentDate));
	
	$q=mysql_query("select id, start_time, end_time, time_interval, dine_interval, between_interval from restaurant_detail 
					where '".date('Y-m-d',strtotime($currentDate))."' >= STR_TO_DATE(start_date, '%m/%d/%Y') and '".date('Y-m-d',strtotime($currentDate))."' <= STR_TO_DATE(end_date, '%m/%d/%Y') and days like '%".$dayName."%' order by id desc limit 1") 
				or die(mysql_error());
	$rs=mysql_fetch_assoc($q);


$seats_tables_sql = "SELECT rt.table_detail_id AS id, td.max_pax, td.table_name  
					 FROM reservation r, reservation_table rt, table_detail td 
					 WHERE r.id=".$res_id." AND r.id=rt.reservation_id AND rt.table_detail_id=td.id AND rt.deleted=0";
$seats_tables_qry = mysql_query($seats_tables_sql);

?>
		<table border="1" style="border-collapse:collapse; border:#CCC;">
		  <thead>
			<tr>
			  <th width="10%" bgcolor="#CCCCCC">Seats</th>
			  <th width="83%" bgcolor="#CCCCCC">Table</th>
			</tr>
		  </thead>
		  <tbody>
			<?php
	while($seats_tables_res = mysql_fetch_assoc($seats_tables_qry)){
	?>
			<tr>
			  <td align="center"><?php echo $seats_tables_res['max_pax']; ?></td>
			  <td align="center" nowrap="nowrap"><?php echo $seats_tables_res['table_name']; ?></td>
			  </tr>
			<?php
	}		
	
	/* trapping the time */
	
	//list down tables available
	$tables_sql = "SELECT td.id, td.table_name, td.max_pax
				   FROM table_detail td
				   WHERE td.restaurant_detail_id=".$rs['id']." 
						 AND id NOT IN 
							 (SELECT rt.table_detail_id 
							  FROM reservation_table rt, reservation r 
							  WHERE r.id=rt.reservation_id AND r.date='".date('m/d/Y',strtotime($currentDate))."' 
									AND (

											( '".$time_start."' BETWEEN r.time AND ADDTIME(TIME(r.time), SEC_TO_TIME(duration*60)) ) 
												OR 
											( '".$time_end."' BETWEEN r.time AND ADDTIME(TIME(r.time), SEC_TO_TIME(duration*60)) ) 
																					
										)
							  )
					ORDER BY td.max_pax ASC";
	
											
	$tables_qry = mysql_query($tables_sql) or die($tables_sql.'<br>'.mysql_error());

			
	//$q=mysql_query("select id from table_detail where restaurant_detail_id=".$currentID." and max_pax>=".$numguest);
	$totaltables=mysql_num_rows($tables_qry);
	
	if($totaltables>0){			

			$tables_stats_sql = "SELECT COUNT(td.id) AS tables, SUM(td.max_pax) AS seats
								   FROM table_detail td
								   WHERE td.restaurant_detail_id=".$rs['id']." 
										 AND id NOT IN 
											 (SELECT rt.table_detail_id 
											  FROM reservation_table rt, reservation r 
											  WHERE r.id=rt.reservation_id AND r.date='".date('m/d/Y',strtotime($currentDate))."' 
													AND (r.time >= '".$time_start."' OR DATE_FORMAT(DATE_ADD(TIME(r.time), INTERVAL 60 MINUTE),'%H:%i') <= '".$time_end."' )
											  )
						   ";
					
			$tables_stats_qry = mysql_query($tables_stats_sql) or die(mysql_error());
			$tables_stats_res = mysql_fetch_assoc($tables_stats_qry);

		?>
		  </tbody>
		  <tbody>
			<?php
		while($table_det = mysql_fetch_assoc($tables_qry)){
		?>
			<?php
		}		
		?>
		  </tbody>
		  <?php	
	}
	else{
		echo "There are no available tables for the selected date (".date('d M Y',strtotime($currentDate)).").";
	}			
?>
                </table>
                <br />
              </div></td>
      	  </tr>
        </table>
<?php

	echo '|'.$res_id.'|'.$client.'|'.$time.'|'.$duration.'|'.$numguest; //.'|'.$tables

}else{
	echo '|||||';	
}
?>