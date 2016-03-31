<?php

	include 'config.php';

	
	function removeColon($str){
		$val=explode(':',$str);
		return $val[0].$val[1];
	}
	
	function timeFormat($str){
		$first=substr($str,0,strlen($str)-2);
		$second=substr($str,strlen($str)-2,strlen($str));
		
		if(strlen($first)==1){
			$first='0'.$first;
		}
		else if(strlen($first)<1){
			$first='00';
		}
		
		return $first.":".$second;
	}
	
	function addZero($str){
		
		if(strlen($str)==1){
			return '0'.$str;
		}
		else{
			
			return $str;
			
		}
	}
	
	$numguest=$_POST['numguest'];
	$currentDate=$_POST['currentDate'];
	$dayName=date('D', strtotime($currentDate));
	
	
?>
<script type="text/javascript">
	function setTheTime(str,btn,endtime){
		jQuery('.home-middle-3 .reservation-btn').attr('data-rel',btn);
		jQuery('.restime').val(str+' - '+endtime).attr('data-rel',str).attr('data-id',endtime);
		jQuery('.reservation-fade, .time-container').fadeOut();
	}
</script>
<?php	

	//get current time
	$t=time();
	$curtime=date("Hi",$t);
	
	//format "09/04/2014"
	$curdate = date('m/d/Y');
	
	$disabled_time=array();
	
	$q=mysql_query("select id, start_time, end_time, time_interval, dine_interval, between_interval, allowed_seats from restaurant_detail where '".$currentDate."' >= start_date and '".$currentDate."' <= end_date and days like '%".$dayName."%' and deleted=0 and ".$curtime.">=(replace(start_time,':','')) and ".$curtime."<=(replace(end_time,':','')) order by id desc limit 1") or die(mysql_error());

	$rs=mysql_fetch_assoc($q);
	
	$start_time=$rs['start_time'];
	$end_time=$rs['end_time'];
	$interval=$rs['time_interval'];
	$dine=$rs['dine_interval'];
	$between=$rs['between_interval'];
	$allowed_seats=$rs['allowed_seats'];
	
	$end_time_of_reservation = ($dine+$between);
	
	$currentID=$rs['id'];
	
	if(mysql_num_rows($q) > 0){
		
		/* trapping the time */
		
		//added
		$notincluded='';
		$q=mysql_query("select rt.table_detail_id from reservation_table as rt, reservation as r where r.checkout=0 and r.id=rt.reservation_id and r.date='".$currentDate."' and rt.deleted=0 and r.deleted=0") or die(mysql_error());
		while($r=mysql_fetch_array($q)){
			$notincluded.='and id<>'.$r[0].' ';
		}
		
		//$q=mysql_query("select id from table_detail where restaurant_detail_id=".$currentID." and max_pax>=".$numguest." $notincluded order by max_pax, id limit 1") or die(mysql_error());
		
		$q=mysql_query("select id from table_detail where restaurant_detail_id=".$currentID." and max_pax>=".$numguest." $notincluded order by max_pax, id") or die(mysql_error());
		
		$totaltables=mysql_num_rows($q);



		//added for limits
		// replaced by VicEEr ==> "select sum(td.max_pax) as used from table_detail as td, reservation_table as rt where td.id=rt.table_detail_id and rt.deleted=0 and td.restaurant_detail_id=".$currentID
		
		$qq=mysql_query("select sum(td.max_pax) as used from table_detail as td, reservation_table as rt, reservation as r 
						     where r.date='".$currentDate."' AND r.status<3 AND r.id=rt.reservation_id AND td.id=rt.table_detail_id and rt.deleted=0 and td.restaurant_detail_id=".$currentID) or die(mysql_error());
		$rr=mysql_fetch_assoc($qq);


		$currentAllowed=($allowed_seats-$rr['used']);

		// echo 'Current Number of Seats allowed : '.$currentAllowed;

		if($numguest<=$currentAllowed){
			
			if($totaltables > 0){
			
			
				echo '<div style="text-align:left; float: left; margin: 0 0 10px 20px;">Bokningsbara tider <strong>'.date("d M, Y",strtotime($currentDate)).'</strong></div><div style="clear:both;"></div>';
				
				
				
				$i=removeColon($start_time);
				$to=removeColon($end_time);
				while($i<=$to){
					
					$time=$i;
					$thetime=timeFormat($time);
					
					$val=explode(":",$thetime);
					$hour=$val[0];
					$min=$val[1];
					
					
					if($min>59){
						
						$extra=$min-60;
						
						$hour=$hour+1;
						$thetime=addZero($hour).":".addZero($extra);
						
						$i=addZero($hour).''.addZero($extra);
				
					}
					
					if(removeColon($thetime)<=$to){
						
						$counter=0;
						$q=mysql_query("select id from reservation where time='".$thetime."' and date='".$currentDate."' and deleted=0");
						while($r=mysql_fetch_array($q)){
							$res_id=$r[0];
							
							$qq=mysql_query("select id from reservation_table where deleted=0 and reservation_id=".$res_id);
							$counter+=mysql_num_rows($qq); 
							
						}
						
						
						if(($totaltables-$counter) > 0 ){
							
							if($curdate==$currentDate){
								
								if((removeColon($thetime) >= $curtime)){
									echo "<span onClick=\"setTheTime('".$thetime."','".$currentID."','".timeFormat(removeColon(timeFormat(removeColon($thetime)+($end_time_of_reservation))))."')\" data-rel='".removeColon($thetime)."' class='theactive' data-id='".$end_time_of_reservation."'>".$thetime."</span>";
								}
								else{
									echo "<span class='disabledtime'>".$thetime."</span>";
								}
							
							}
							else{
								echo "<span onClick=\"setTheTime('".$thetime."','".$currentID."','".timeFormat(removeColon(timeFormat(removeColon($thetime)+($end_time_of_reservation))))."')\" data-rel='".removeColon($thetime)."' class='theactive' data-id='".$end_time_of_reservation."'>".$thetime."</span>";
							}
						}
						else{
							echo "<span class='disabledtime tobedisabled' data-rel='".removeColon(timeFormat(removeColon($thetime)+($end_time_of_reservation)))."' data-id='".removeColon($thetime)."'>".$thetime."</span>";
						}
						
						
						
						
					} 
					
					$i+=$interval;
				}
			
			}
			else{
				//echo "There's no available table(s) for $numguest persons.";
				echo "Det finns ingen tillgänglig tabell (er) för $numguest personer.";
			}

		}
		else{
			echo "Currently, there are no available seats.  However, you can call us.";
		}

		
	}
	else{
		//echo "There's no schedule set for the date(".$currentDate.") you've selected.";
		echo "Det finns ingen tidsplan fastställts för datum (".$currentDate.") som du har valt.";
	}
		
	
?>

<script type="text/javascript">
	jQuery(function(){
		
		jQuery('.disabledtime').remove();
	
		jQuery('.tobedisabled').each(function(){
		
			var starttime = Number(jQuery(this).attr('data-id'));
			var endtime = Number(jQuery(this).attr('data-rel'));
			
			jQuery('.theactive').each(function(){
			
				var thetime = Number(jQuery(this).attr('data-rel'));
				
				if(thetime>=starttime & thetime<=endtime){
					jQuery(this).addClass('disabledtime');
				}
			
			});
		
		});
	
	});
</script>
                            