<?php session_start();

		  include '../config/config.php';	




	  
	  mysql_query("insert into log(account_id,description,date_time,ip_address) values(".$_SESSION['login']['id'].",'Logged out.',now(),'".get_client_ip()."')");
		

	  session_destroy();
	  unset($_SESSION['login']);


	  
	  // header("Location:../");exit;
?>

