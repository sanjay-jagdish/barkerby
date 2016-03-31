<?php include 'config.php';

	$q=mysql_query("select var_value from settings where var_name='panic_value'");
	$r=mysql_fetch_assoc($q);
	
	echo $r['var_value'];

?>