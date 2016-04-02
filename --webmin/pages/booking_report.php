<?php
  include_once('redirect.php');

    $date_selected = date("Y-m-d");
  


?>
<div id="booking-report">
    

<script type="text/javascript">
    
    jQuery(function(){

   
    

          
          jQuery( "#chartdate" ).datepicker({
              firstDay: '<?php echo $row['var_value']; ?>', 
              dateFormat: 'yy-mm-dd',
              onSelect: function(selectedDate){
                        
                    alert(selectedDate);
            
              },
              maxDate: new Date()
            
            
            });

       
        <?php
        if($res_det_num==1){
        ?>
           
        <?php
        }
        ?>   
    });




</script>

<div id="calendar-container">
    <div id="chartdate" style="float:left;"></div>
</div>

<div id="table-container">
    
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="booking_report">
        
        <thead>

            <tr >
                    <th>From - To</th>
                    <th> Name </th>
                    <th> Number of Guest</th>
                    <th> Tables </th>
                    <th> Status </th>
            </tr>
            
        </thead>
        <tbody>


        <?php

          $query = "SELECT r.id, r.date, r.time, ADDTIME(TIME(r.time), SEC_TO_TIME(r.duration*60)) AS end, r.duration, 
              a.fname, a.lname, 
              rt.tables, r.number_people, r.note, r.status  
              FROM reservation r
              LEFT JOIN (SELECT COUNT(id) AS `tables`, reservation_id  FROM reservation_table GROUP BY reservation_id) AS rt ON r.id=rt.reservation_id 
              LEFT JOIN account a ON a.id=r.account_id
              WHERE r.date='".date('m/d/Y',strtotime($date_selected))."'
              ORDER BY `r`.`id` ASC ";



			
            // echo "query here".$query;

        $reservation_qry = mysql_query($query);
        // $table_reservations_num = mysql_num_rows($reservation_qry);

        while($reservations_data = mysql_fetch_assoc($reservation_qry)){

        ?>
            <tr class="gradeX gradeX-<?php echo $r[3];?>" align="center">
                <td><?php echo date('H:i',strtotime($reservations_data['time']))." - ".date('H:i',strtotime($reservations_data['end'])); ?></td>
                <td>
				<?php 
				if(trim($reservations_data['fname'].$reservations_data['lname'])!=''){
					echo $reservations_data['fname']." ".$reservations_data['lname']; 
				}else{
					echo '***Walk-In***';	
				}
				?>
                </td>
                <td><?php echo $reservations_data['number_people']; ?></td>
                <td><?php echo $reservations_data['tables']; ?></td>
                <td><?php echo $reservations_data['status']; ?></td>
              </tr>

        <?php

          }
        ?>

        </tbody>
         <tfoot>
                <tr>
                    <th>From - To</th>
                    <th> Name </th>
                    <th> Number of Guest</th>
                    <th> Table </th>
                    <th> Status </th>
                </tr>
            </tfoot>
    </table>

</div>

</div>

