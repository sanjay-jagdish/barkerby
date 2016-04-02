<?php
session_start();
include '../config/config.php';

$id=strip_tags($_POST['id']);

$q=mysql_query("SELECT name, description, price FROM menu_lunch_items WHERE id=".$id);
$r=mysql_fetch_assoc($q);

$_SESSION['ccb_name'] = $r['name'];
$_SESSION['ccb_desc'] = $r['description'];
$_SESSION['ccb_price'] = $r['price'];
?>