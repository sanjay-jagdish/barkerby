<?php session_start();
	include '../config/config.php';


	$order_list = strip_tags($_POST['order']);
	$values = explode("-", $order_list);

	$order = $values[0];
	$cat_id = $values[1];


	

	// $q=mysql_query("UPDATE sub_category SET order = '".$order."' WHERE id = '".$sub_cat_id."' ");

	$query = mysql_query("UPDATE `category` SET `order` = '".$order."' WHERE `category`.`id` = ".$cat_id);



	// echo $sub_cat_id;
?>