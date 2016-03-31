<?php session_start();
include '../config/config.php';
	
//explode passed strings as array	
$all_week = $_POST['all_week'];
$menu_parameter = explode(' ',$_POST['menu_parameter']);
$courses = explode('<^>',$_POST['courses']);
$prices = explode('<^>',$_POST['prices']);

$additional_courses = explode('<^>',$_POST['course_desc_addtl']);
$additional_prices = explode('<^>',$_POST['course_price_addtl']);
$additional_days = explode('<^>',$_POST['course_day_addtl']);

array_filter($additional_courses);
array_filter($additional_prices);
array_filter($additional_days);

if($menu_parameter[0]=='W'){
	//weekly menu
	$menu_year = $menu_parameter[1];
	$menu_week = $menu_parameter[2];

}

$menu_header = trim($_POST['header_text']);
$menu_description = trim($_POST['menu_description']);
$menu_footer = trim($_POST['text_after_menu']);

$sql = 'SELECT id FROM currency WHERE set_default=1';
$qry = mysql_query($sql);
$currency = mysql_fetch_assoc($qry);

$query = "INSERT INTO menu_lunch SET currency_id=".$currency['id'].", 
									 all_in=".$all_week.",
									 note_header = '".addslashes($menu_header)."',
									 note_footer = '".addslashes($menu_footer)."',
									 description = '".addslashes($menu_description)."',
									 time_created = NOW(),
									 year_for = ".$menu_year.",
									 week_no = ".$menu_week;

if(mysql_query($query)){
	//get last id inserted
	$sql = 'SELECT MAX(id) AS id FROM menu_lunch';
	$last_id = mysql_fetch_assoc(mysql_query($sql));
	$lunch_menu_id = $last_id['id'];
	
	foreach($courses as $key => $course_desc){
													  
		mysql_query("INSERT INTO menu_lunch_items SET menu_id=".$lunch_menu_id.",
													  currency_id=".$currency['id'].",
													  description='".addslashes($course_desc)."',
													  price=".$prices[$key]) or die(mysql_error());	
	}


	//for newly added courses (Specific Day)
	foreach($additional_courses as $key => $course_desc){
													  
		if(trim($course_desc)!=''){
					
			mysql_query("INSERT INTO menu_lunch_items SET menu_id=".$lunch_menu_id.",
														  currency_id=".$currency['id'].",
														  description='".addslashes($course_desc)."',
														  specific_day='".$additional_days[$key]."',
														  price=".$additional_prices[$key]) or die(mysql_error());	
		}
		
	}
			
	echo '1|SAVED!';
	
}else{
	echo '0|Oppsss!!! An error has occurred.';
}

?>