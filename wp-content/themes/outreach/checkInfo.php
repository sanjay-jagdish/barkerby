<?php
	include 'config.php';
	
	
	$email=mysql_real_escape_string(strip_tags($_POST['email']));
	$pword=mysql_real_escape_string(strip_tags($_POST['pword']));
	
	$q=mysql_query("select id,confirmed from account where email='".$email."' and password='".md5($pword)."' and type_id=5") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		$r=mysql_fetch_assoc($q);
		
		if($r['confirmed']==1){
			echo $r['id'];
		}
		else{
			echo 'not';
		}
		
	}
	else{
		echo 0;
	}
	
?>	