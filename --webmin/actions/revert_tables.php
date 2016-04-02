<?php

require('../../wp-config.php');

require('../../wp-blog-header.php');

$table_prefix = $wpdb->base_prefix;

try {
	//Copy wp_users table datas 
	mysql_query("INSERT INTO ".$table_prefix."users SELECT * FROM ".$table_prefix."users_dup");
	
	//Copy ost_users table datas 
	mysql_query("INSERT INTO ost_user SELECT * FROM ost_user_dup");
	
	//Copy webmin account table datas 
	mysql_query("INSERT INTO account SELECT * FROM account_dup");
	
	//Copy webmin reservation table datas 
	mysql_query("INSERT INTO reservation SELECT * FROM reservation_dup");
	
	//Copy webmin reservation_detail table datas 
	mysql_query("INSERT INTO reservation_detail SELECT * reservation_detail_dup");
	
	
	
	//DROP tables
	mysql_query("DROP TABLE IF EXISTS ".$table_prefix."users_dup");
	mysql_query("DROP TABLE IF EXISTS ost_user_dup");
	mysql_query("DROP TABLE IF EXISTS account_dup");
	mysql_query("DROP TABLE IF EXISTS reservation_dup");
	mysql_query("DROP TABLE IF EXISTS reservation_detail_dup");
	
	
	echo "<h1>Action is performed successfully.</h1>";

} catch (Exception $e) {
    echo 'Error: ',  $e->getMessage(), "\n";
}

?>