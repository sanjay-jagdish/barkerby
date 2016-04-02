<?php session_start();
	include '../config/config.php';
	
	$options = $_POST['option'];
	$menu_id = $_POST['id'];
	
	foreach($options as $val){
		$val_arr = explode(':',$val);
		if($val_arr[1]==''){
			$price = 0;
		}else{
			$price = $val_arr[1];
		}
		mysql_query("insert into menu_options(menu_id,name,price) values (".$menu_id.", '".$val_arr[0]."', ".$price.")") or die(mysql_error());
	}
?>