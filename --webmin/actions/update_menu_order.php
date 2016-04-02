<?php session_start();
	include '../config/config.php';


	$order_list = strip_tags($_POST['order']);
	$values = explode("-", $order_list);

	$order = $values[0];
	$menu_id = $values[1];


	

	// $q=mysql_query("UPDATE sub_category SET order = '".$order."' WHERE id = '".$sub_cat_id."' ");

	$query = mysql_query("UPDATE `menu` SET `order` = '".$order."' WHERE `menu`.`id` = ".$menu_id);



	// echo $sub_cat_id;
?>