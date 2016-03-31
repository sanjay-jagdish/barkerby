<?php
	date_default_timezone_set("Europe/Stockholm");

	$siteurl='http://restaurantaruba.com/';
	
	
	/** The name of the database for WordPress */
	define('DB_NAME', 'restaura_wrdp1');
	
	/** MySQL database username */
	define('DB_USER', 'restaura_wrdp1');
	
	/** MySQL database password */
	define('DB_PASSWORD', 'IZb57fpMmp7f');
	
	/** MySQL hostname */
	define('DB_HOST', 'localhost');
	
	$con=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
	
	if($con){
		mysql_select_db(DB_NAME);
	}
	else{
		die('CANNOT CONNECT TO THE DATABASE '.DB_NAME);
	}
	
	$email=$_POST['email'];
	$cpw=$_POST['cpw'];
	
	$q=mysql_query("select id from account where email='".$email."' and type_id=5 and password='".md5($cpw)."'");
	
	if(mysql_num_rows($q) > 0 ){
		
		$r=mysql_fetch_array($q);
		
		echo $r['id'];
		
	}
	else{
		echo 'not';
	}
	
?>	