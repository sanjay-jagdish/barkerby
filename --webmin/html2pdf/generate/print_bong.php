<?php session_start();
	include '../../config/config.php';
	session_start();
	
	
	echo 'importing printing class<br>';
	echo 'calling ip 192.168.0.110<br>';
	//printer
	require_once(dirname(__FILE__) . "/escpos.php");
	$fp = fsockopen("192.168.1.110", 9100);
	if(!$fp){
		echo 'Couldnt connect to the local printer';
		die();
	}else{
		echo 'connected to printer';
		$printer = new escpos($fp);
		
	}
	
	/* Initialize */
	$printer -> initialize();
	//-end printer
	
	function processedBy($id){
		if($id!=''){
			
			$q=mysql_query("select concat(fname,' ',mname,' ',lname) from account where id=".$id);
			$r=mysql_fetch_array($q);
			
			return $r[0];
			
		}
		else{
			return '';
		}
	}
	
	function theOrderType($id){
		$q=mysql_query("select if(rd.lunchmeny=0,' (À la carte)',' (Lunch)') as thetype from reservation as r, reservation_detail as rd where r.id=rd.reservation_id and rd.reservation_id='".$id."'");
		$r=mysql_fetch_assoc($q);
		
		return $r['thetype'];
	}
	
	
	$id=strip_tags($_GET['id']);
	$qry = mysql_query(" UPDATE reservation SET `viewed` = '1' WHERE  `id` = ".$id);
	
	$q=mysql_query("select concat(a.fname,' ',a.mname,' ',a.lname) as name, a.fname as fname, a.lname as lname, rt.description as descrip, r.approve as approve, r.date as date, r.time as time, r.number_people as numpeople, r.number_table as numtable, r.approve_by as appby, r.note as note, r.date_time as datetime, r.reservation_type_id as resid, r.uniqueid as uniqueid, r.reason as reason, a.email as email, a.mobile_number as mob, a.phone_number as phone, a.street_name as street, r.lead_time as lead, r.acknowledged as ack,a.city as city,  r.deliver as deliver, a.state as state, a.zip as zip, a.country as country, r.asap as asap, r.payment_mode as payment, datediff(STR_TO_DATE(concat(r.date,r.time), '%m/%d/%Y %H:%i'), now()) as count_days, r.asap_datetime as asapdatetime from reservation as r, reservation_type as rt, account as a where a.id=r.account_id and rt.id=r.reservation_type_id and r.id=".$id) or die(mysql_error());
	
	
	$r=mysql_fetch_assoc($q);
	
	$customer=$r['name'];
	$fname = $r['fname'];
	$lname = $r['lname'];
	$type=$r['descrip'];
	$status=$r['approve'];
	$date=$r['date'];
	$time=$r['time'];
	$num_people=$r['numpeople'];
	$num_table=numberTable($id);
	$accountID=$r['appby'];
	$note=$r['note'];
	$date_time=$r['datetime'];
	$typeID=$r['resid'];
	$reason=$r['reason'];
	$email=$r['email'];
	$mobile=$r['mob'];
	$phone=$r['phone'];
	$customtime=$r['lead'];
	$acknowledged=$r['ack'];
	$street=$r['street'];
	$city=$r['city'];
	$state=$r['state'];
	$zip=$r['zip'];
	$country=$r['country'];
	$asap=$r['asap'];
	$payment=$r['payment'];
	$uniqueid = $r['uniqueid'];
	$countdays = $r['count_days'];
	$asapdatetime = $r['asapdatetime'];
	
	
	$number='';
	if($mobile!='' & $phone!=''){
		$number.=$mobile.' / '.$phone;
	}
	else{
		if($mobile!=''){
			$number=$mobile;
		}
		else if($phone!=''){
			$number=$phone;
		}
	}
	
	$address='';
						
	if($street!=''){
		$address.=$street;
	}
	if($city!=''){
		
		$comma='';
							
		if($address!=''){
			$comma=',';
		}
		
		$address.=$comma.'<br>'.$city;
	}
	if($state!=''){
		$comma='';
							
		if($address!=''){
			$comma=',';
		}
		
		$address.=$comma.'<br>'.$state;
	}
	if($zip!=''){
		$comma='';
							
		if($address!=''){
			$comma=',';
		}
		
		$address.=$comma.'<br>'.$zip;
	}
	if($country!=''){
		$comma='';
							
		if($address!=''){
			$comma=',';
		}
		
		$address.=$comma.'<br>'.$country;
	}
	
?>
        	<?php
				function sum_the_time($time1, $time2) {
				  $times = array($time1, $time2);
				  $seconds = 0;
				  foreach ($times as $time)
				  {
					list($hour,$minute,$second) = explode(':', $time);
					$seconds += $hour*3600;
					$seconds += $minute*60;
					$seconds += $second;
				  }
				  $hours = floor($seconds/3600);
				  $seconds -= $hours*3600;
				  $minutes  = floor($seconds/60);
				  $seconds -= $minutes*60;
				  if($seconds < 9)
				  {
				  $seconds = "0".$seconds;
				  }
				  if($minutes < 9)
				  {
				  $minutes = "0".$minutes;
				  }
					if($hours < 9)
				  {
				  $hours = "0".$hours;
				  }
				  //{$hours}:{$minutes}:{$seconds}
				  return "{$hours}:{$minutes}";
				}
				$swedish_months = array('Januari','Februari','Mars','April','Maj','Juni','Juli','Augusti','September','Oktober','november','December');
				
				$dd = date("d",strtotime($date_time));
				$mm = date("n",strtotime($date_time));
				$yy = date("Y",strtotime($date_time));
				$hrmn = date("G:i",strtotime($date_time));
				$mm = $swedish_months[$mm-1];
				
				$datetime = $dd.' '.$mm.' '.$yy.' '.$hrmn;
				
				$dd = date("d",strtotime($date));
				$mm = date("n",strtotime($date));
				$yy = date("Y",strtotime($date));
				$hrmn = date("G:i",strtotime($date));
				
				$mm = $swedish_months[$mm-1];
				
				$dateonly = $dd.' '.$mm.' '.$yy;
			 
			 $bong_date ='';
			 $bong_time = '';
			 if($typeID==1){
				   
			  }else{
				  
				if($date!=''){
					$bong_date = $dateonly;
				}
				if($time=='00:00:00'){
					echo '-';
				}else{
					if($countdays==0){
						$prefx='Idag';
					}
					else if($countdays==1){
						$prefx='Imorgon';
					}
					else if($countdays<0){
						if($countdays==-1){
							$prefx='Igår';
						}
						else{
							//$prefx='Senaste '.abs($countdays).' dagar';
							$prefx='Passerat datum';
						}
					}
					else{
						$prefx='Om '.$countdays.' dagar';
					}
					
					
					
					$thetime = $time;
					if($asapdatetime!=''){
						$thetime = $asapdatetime;
					}
					
					if($asap==1){
						if($asapdatetime!=''){
							//$prefx.', '.
							$bong_time = date("G:i",strtotime($thetime)); 
						}	
						else{
							$bong_time = 'Snarast';
						}
					}else{
						//echo $prefx.', '.date("G:i",strtotime($thetime)); 
						$bong_time = date("G:i",strtotime($thetime));
						//$time2 = '00:30';
						//echo sum_the_time($time1,$time2);
					}
				}
			}?>
<?php if($typeID==2){
	
/* Printer Header */
$printer -> set_justification(escpos::JUSTIFY_CENTER);
$printer -> select_print_mode(40);
$printer -> text("Mise en Place\n");
$printer -> select_print_mode(); // Reset
$printer -> select_print_mode(184);
$printer -> text("Limone\n");
$printer -> select_print_mode(); // Reset
$printer -> feed(2);
$printer -> set_justification(escpos::JUSTIFY_LEFT);

/* Body */
$printer -> select_print_mode(8);
$printer -> text("$bong_date $bong_time\n");
$printer -> select_print_mode(); // Reset
$printer -> text("------------------------------------------------\n");

        /*$q=mysql_query("select m.name as menu, c.name as sub, m.image as img, m.price as price, rd.quantity as quantity, cu.shortname as currency, rd.notes as notes, c.id as cid, m.type as type, m.discount as discount,m.id as menu_id from reservation_detail as rd, menu as m, sub_category as c, currency as cu where m.id=rd.menu_id and c.id=m.sub_category_id and cu.id=m.currency_id and rd.reservation_id=".$id) or die(mysql_error());*/
		
		$q=mysql_query("select m.name as menu, m.image as img, m.price as price, rd.quantity as quantity, cu.shortname as currency, rd.notes as notes, m.type as type, m.discount as discount,m.id as menu_id, rd.lunchmeny as lunchmeny, m.discount_unit as unit from reservation_detail as rd, menu as m, currency as cu where m.id=rd.menu_id and cu.id=m.currency_id and m.deleted=0 and rd.reservation_id=$id and rd.lunchmeny=0") or die(mysql_error());
						
		
		if(mysql_num_rows($q)==0){
		
		$q=mysql_query("select m.name as menu, m.image as img, rd.price as price, rd.quantity as quantity, cu.shortname as currency, rd.notes as notes, m.type as type, m.discount as discount,m.id as menu_id, rd.lunchmeny as lunchmeny from reservation_detail as rd, menu_lunch_items as m, currency as cu where m.id=rd.menu_id and cu.id=m.currency_id and m.deleted=0 and rd.reservation_id=$id and rd.lunchmeny=1") or die(mysql_error());
		}
						
        $count=0;
        $total=0;
        $currency='';
        $opt_tot_all = 0;
        while($r=mysql_fetch_assoc($q)){
            $count++;
			
			$printer -> select_print_mode(16);
			$printer -> text("$r[quantity] x $r[menu]\n");
			$printer -> select_print_mode();//reset
			
                    $opt_sql = "select a.name as name,a.price as price, dish_num from reservation_menu_option as a where reservation_unique_id = '$uniqueid' and a.menu_id=$r[menu_id]";
                    
                    $opt_query = mysql_query($opt_sql) or die(mysql_error());
                    if(mysql_num_rows($opt_query)>0){
                        $counter = 0;
                        while($opt = mysql_fetch_assoc($opt_query)){
							
                                if($counter<$opt['dish_num']){
                                    $counter = $counter+1;
                                    if($counter > 1){
										$printer -> feed();
										$printer -> select_print_mode(128);
										$printer -> text("     Portion #".$counter."\n");
										$printer -> select_print_mode(0);
									}
                                    
                                }
								$printer -> text("     ".$opt['name']."\n");
                            }
                    }
            $printer -> select_print_mode(0);
			$printer -> text("$r[notes]\n");
			$printer -> select_print_mode();//reset
        }
    ?>
<?php } 

/* Footer */
$printer -> text("------------------------------------------------\n");
$printer -> select_print_mode(8);
$printer -> text("Namn: $customer\n");
$printer -> select_print_mode(); // Reset
$printer -> text("Mobilnummer: $number\n");
$printer -> feed();

$printer -> cut();

?>