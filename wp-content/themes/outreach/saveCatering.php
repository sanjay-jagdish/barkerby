<?php
	include 'config.php';
	
	$uniqueid= mysql_real_escape_string(strip_tags($_POST['unique_id']));
	$datetime= explode(' ',mysql_real_escape_string(strip_tags($_POST['datetime'])));
	$total= mysql_real_escape_string(strip_tags($_POST['total']));
	$payment_type= mysql_real_escape_string(strip_tags($_POST['payment_type']));
	$email= mysql_real_escape_string(strip_tags($_POST['email']));
	$pword= mysql_real_escape_string(strip_tags($_POST['pword']));
	
	function dateFormat($date){
		$date = explode('-',$date);
		return $date[1].'/'.$date[2].'/'.$date[0];
	}
	
	$q=mysql_query("select id from account where email='".$email."' and password='".md5($pword)."' and type_id=5 and deleted=0") or die(mysql_error());
	$r=mysql_fetch_assoc($q);
	$accountid = $r['id'];
	
	if(mysql_num_rows($q) > 0){
	
		$qq=mysql_query("select id from catering_detail where deleted=0 and uniqueid='".$uniqueid."'") or die(mysql_error());
		$rr=mysql_fetch_assoc($qq);
		
		if(mysql_num_rows($qq) > 0){
			$id = $rr['id'];
			
			mysql_query("update catering_detail set payment_mode='".$payment_type."', total='".$total."', account_id='".$accountid."', date='".dateFormat($datetime[0])."', time='".$datetime[1]."',status=1, date_time=now() where id='".$id."'") or die(mysql_error());
			
		}
		else{
			echo 'not';
		}
	
	}
	else{
		echo 'invalid';
	}
	
?>