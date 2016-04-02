<?php
	if(!isset($_SESSION['login']['id'])){
		
	   $domain = $_SERVER{"SERVER_NAME"};

	    header("Location: http://$domain/webmin");exit;
		
	}
?>