<?php

require('../../wp-config.php');

require('../../wp-blog-header.php');

$table_prefix = $wpdb->base_prefix;

try {
	
	//******************Duplicate WP users table******************//
	//Copy wp_users table structure
	mysql_query("SET sql_notes = 0;");
	mysql_query("CREATE TABLE IF NOT EXISTS ".$table_prefix."users_dup LIKE ".$table_prefix."users");
	mysql_query("SET sql_notes = 1;");
	//Copy wp_users table datas 
	mysql_query("INSERT INTO ".$table_prefix."users_dup SELECT * FROM ".$table_prefix."users");
	
	
	
	//******************Duplicate OST user table******************//
	
	//Copy ost_user table structure
	mysql_query("SET sql_notes = 0;");
	mysql_query("CREATE TABLE IF NOT EXISTS ost_user_dup LIKE ost_user");
	mysql_query("SET sql_notes = 1;");
	//Copy ost_users table datas 
	mysql_query("INSERT INTO ost_user_dup SELECT * FROM ost_user");
	
	
	
	//******************Duplicate webmin account table******************//
	
	//Copy webmin account table structure
	mysql_query("SET sql_notes = 0;");
	mysql_query("CREATE TABLE IF NOT EXISTS account_dup LIKE account");
	mysql_query("SET sql_notes = 1;");
	//Copy webmin account table datas 
	mysql_query("INSERT INTO account_dup SELECT * FROM account");
	
	
	
	//******************Duplicate reservation table******************//
	
	//Copy reservation table structure
	mysql_query("SET sql_notes = 0;");
	mysql_query("CREATE TABLE IF NOT EXISTS reservation_dup LIKE reservation");
	mysql_query("SET sql_notes = 1;");
	//Copy webmin orders table datas 
	mysql_query("INSERT INTO reservation_dup SELECT * FROM reservation");
	
	
	
	
	//******************Duplicate order_details table******************//
	
	//Copy reservation_detail table structure
	mysql_query("SET sql_notes = 0;");
	mysql_query("CREATE TABLE IF NOT EXISTS reservation_detail_dup LIKE reservation_detail");
	mysql_query("SET sql_notes = 1;");
	//Copy webmin order_details table datas 
	mysql_query("INSERT INTO reservation_detail_dup SELECT * FROM reservation_detail");
	
		
	
	//TRUNCATE tables
	mysql_query("TRUNCATE TABLE ".$table_prefix."users");
	mysql_query("TRUNCATE TABLE ost_user");
	mysql_query("TRUNCATE TABLE account");
	mysql_query("TRUNCATE TABLE reservation");
	mysql_query("TRUNCATE TABLE reservation_detail");
	
	echo "<h1>Action is performed successfully.</h1>";

} catch (Exception $e) {
    echo 'Error: ',  $e->getMessage(), "\n";
}

?>