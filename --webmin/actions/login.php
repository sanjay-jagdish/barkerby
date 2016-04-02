<?php session_start();
	include '../config/config.php';
	
	$em=mysql_real_escape_string($_POST['em']);
	$pw=mysql_real_escape_string($_POST['pw']);
	$check = mysql_real_escape_string($_POST['check']);
	$q=mysql_query("SELECT a.id, a.email,concat(a.fname,' ',a.lname) as user_name, a.type_id, t.signatory 
						FROM account a, type t 
						WHERE a.email='".$em."' and a.password=md5('".$pw."') and a.deleted=0 and a.type_id<>5 
						AND a.type_id=t.id") or die(mysql_error());
	
	if(mysql_num_rows($q) > 0){
		$_SESSION['login']=array();
		while($r=mysql_fetch_assoc($q)){
			$_SESSION['login']['id']=$r['id'];
			$_SESSION['login']['email']=$r['email'];
			$_SESSION['login']['name']=$r['user_name'];
			$_SESSION['login']['type']=$r['type_id'];
			$_SESSION['login']['signatory']=$r['signatory'];
		}
		
		mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Logged in (".$_SESSION['login']['name'].").',now(),'".get_client_ip()."')");
		
		echo json_encode($_SESSION['login']);
		
	}
	else{
		$error=array('error'=>'Invalid');
		echo json_encode($error);
	}
	
?>