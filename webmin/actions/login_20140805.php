<?php session_start();
	include '../config/config.php';
	
	$em=mysql_real_escape_string($_POST['em']);
	$pw=mysql_real_escape_string($_POST['pw']);
	$check = mysql_real_escape_string($_POST['check']);
	
	$q=mysql_query("select id,email,concat(fname,' ',lname) as name, type_id from account where email='".$em."' and password=md5('".$pw."') and deleted=0 and type_id<>5") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		$_SESSION['login']=array();
		while($r=mysql_fetch_assoc($q)){
			$_SESSION['login']['id']=$r['id'];
			$_SESSION['login']['email']=$r['email'];
			$_SESSION['login']['name']=$r['name'];
			$_SESSION['login']['type']=$r['type_id'];
		}



		
		mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Logged in.',now(),'".get_client_ip()."')");
		
		echo json_encode($_SESSION['login']);
		
	}
	else{
		$error=array('error'=>'Invalid');
		echo json_encode($error);
	}
	
?>