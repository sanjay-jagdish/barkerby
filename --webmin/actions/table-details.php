<?php session_start();
	include '../config/config.php';
	
	$id=mysql_real_escape_string(strip_tags($_POST['id']));
	
	$q=mysql_query("select reservation_id from reservation_table where table_detail_id=".$id);
	
	if(mysql_num_rows($q) > 0){
		while($r=mysql_fetch_assoc($q)){
			$q=mysql_query("select a.email as email, concat(a.fname, ' ',a.lname) as name, a.phone_number as phone_number, r.date as date, r.time as time, r.number_people as people, r.note as note from reservation as r, account as a where a.id=r.account_id and r.deleted=0 and r.id=".$r['reservation_id']);
			
			if(mysql_num_rows($q) > 0){
			
				while($rr=mysql_fetch_assoc($q)){
	?>
    		<div class="detail">
            	<span class="resv"><strong><?php echo date("M d, Y",strtotime($rr['date'])).' - '.$rr['time']; ?></strong></span>
                <div>
                	<ul>
						<li>Customer : <?php echo $rr['name'].' - '.$rr['email'];?></li>
                        <li>Phone Number: <?php echo $rr['phone_number'];?></li>
                    	<li>Number of Guests: <?php echo $rr['people'];?></li>
                    	<li>Note: <?php echo $rr['note']?></li>
                    </ul>    
                </div>
            </div>
    <?php				
				}
					
			}
			
		}
	}
	else{
		echo '<p style="margin:5px 0;">No details found.</p>';
	}
?>