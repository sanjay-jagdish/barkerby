<?php session_start();
	include '../config/config.php';


	$order_list = strip_tags($_POST['order']);
	$values = explode("-", $order_list);

	$order = $values[0];
	$sub_cat_id = $values[1];


	

	// $q=mysql_query("UPDATE sub_category SET order = '".$order."' WHERE id = '".$sub_cat_id."' ");

	$query = mysql_query("UPDATE `sub_category` SET `order` = '".$order."' WHERE `sub_category`.`id` = ".$sub_cat_id.";");



	// echo $sub_cat_id;
?>