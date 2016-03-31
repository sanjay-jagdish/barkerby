<?php session_start();
include '../config/config.php';
	
//explode passed strings as array	
$all_week = $_POST['all_week'];
$menu_parameter = explode(' ',$_POST['menu_parameter']);

$names = explode('<^>',$_POST['names']);
$courses = explode('<^>',$_POST['courses']);
$prices = explode('<^>',$_POST['prices']);

$sortings = explode('<^>',$_POST['e_sorts']);
$existing_names = explode('<^>',$_POST['e_names']);
$existing_courses = explode('<^>',$_POST['e_courses']);
$existing_prices = explode('<^>',$_POST['e_prices']);
$existing_items = explode('<^>',$_POST['e_items']);
$existing_days = explode('<^>',$_POST['e_days']);

$name_allin_additional =  explode('<^>',$_POST['name_allin_additional']);
$course_allin_additional =  explode('<^>',$_POST['course_allin_additional']);
$existing_allweeks =  explode('<^>',$_POST['existing_allweeks']);


$e_lmta_box = explode('<^>',$_POST['e_lmta_box']);
$e_lmta_price = explode('<^>',$_POST['e_lmta_price']);
$a_lmta_box = explode('<^>',$_POST['a_lmta_box']);
$a_lmta_price = explode('<^>',$_POST['a_lmta_price']);


$additional_names = explode('<^>',$_POST['course_name_addtl']);
$additional_courses = explode('<^>',$_POST['course_desc_addtl']);
$additional_prices = explode('<^>',$_POST['course_price_addtl']);
$additional_days = explode('<^>',$_POST['course_day_addtl']);


$header_ids = $_POST['header_ids'];
$footer_ids = $_POST['footer_ids'];
$show_headers = $_POST['show_headers'];
$show_footers = $_POST['show_footers'];
$allin_headers = $_POST['allin_headers'];
$allin_footers = $_POST['allin_footers'];
$headers_txts = $_POST['headers_txts'];
$footers_txts = $_POST['footers_txts'];		

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

//check if lunch_menu for the week is existing
$menu_chk_sql = "SELECT id FROM menu_lunch WHERE year_for = ".$menu_year." AND week_no = ".$menu_week;
$menu_chk_qry = mysql_query($menu_chk_sql);
$menu_chk_num = mysql_num_rows($menu_chk_qry);

$lunch_menu_id = $_POST['id'];

$new_record = 0;

if($menu_chk_num>0){
	
	$menu_chk_res = mysql_fetch_assoc($menu_chk_qry);
	
	$query = "UPDATE menu_lunch SET currency_id=".$currency['id'].", 
										 note_header = '".addslashes($menu_header)."',
										 note_footer = '".addslashes($menu_footer)."',
										 description = '".addslashes($menu_description)."',
										 last_saved = NOW(),
										 year_for = ".$menu_year.",
										 week_no = ".$menu_week."
								WHERE id=".$menu_chk_res['id'];
}else{
	$query = "INSERT INTO menu_lunch SET currency_id=".$currency['id'].", 
										 note_header = '".addslashes($menu_header)."',
										 note_footer = '".addslashes($menu_footer)."',
										 description = '".addslashes($menu_description)."',
										 time_created = NOW(),
										 year_for = ".$menu_year.",
										 week_no = ".$menu_week;

	$mr_lunch_menu_id = $lunch_menu_id;
	$new_record = 1;
}


if(mysql_query($query)){

	//get the id if menu is newly created
	if($menu_chk_num==0){
		$lunch_menu_id = mysql_insert_id();
	}

	//start of -> headers and footers

	$header_ids = explode('<^>',$header_ids);
	$footer_ids = explode('<^>',$footer_ids);
	$show_headers = explode('<^>',$show_headers);
	$show_footers = explode('<^>',$show_footers);
	$allin_headers = explode('<^>',$allin_headers);
	$allin_footers = explode('<^>',$allin_footers);
	$headers_txts = explode('<^>',$headers_txts);
	$footers_txts = explode('<^>',$footers_txts);		


	$schedule = array(0 => 'NULL', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun');
	
	foreach($schedule as $dk => $day){
		
		$sql_h = '';  $sql_f = '';
		
		if($header_ids[$dk]>0){
			
			if($new_record==1 && $allin_headers[$dk]==1){

				$sql_h = "INSERT INTO menu_lunch_hf 
								SET contents='".addslashes($headers_txts[$dk])."',
									menu_id=".$lunch_menu_id.",
									content_type='header',
									all_in=".$allin_headers[$dk].", 
									`show`=".$show_headers[$dk];
				
				if($dk>0){ $sql_h .= ",specific_day='".$day."'"; }			
					
				mysql_query($sql_h);
			
			}else{

				//if existing record then just update
				$sql_h = "UPDATE menu_lunch_hf 
								SET contents='".addslashes($headers_txts[$dk])."',
									all_in=".$allin_headers[$dk].", 
									content_type='header',
									`show`=".$show_headers[$dk]." 
									WHERE id=".$header_ids[$dk];				
				
				mysql_query($sql_h);
				
			}
		
		}else{
			//if not existing then add/insert
			if($headers_txts[$dk]!=''){
				$sql_h = "INSERT INTO menu_lunch_hf 
								SET contents='".addslashes($headers_txts[$dk])."',
									menu_id=".$lunch_menu_id.",
									content_type='header',
									all_in=".$allin_headers[$dk].", 
									`show`=".$show_headers[$dk];
				if($dk>0){ $sql_h .= ",specific_day='".$day."'"; }
			
				mysql_query($sql_h);
			}
		}
	
		if($footer_ids[$dk]>0){

			if($new_record==1 && $allin_headers[$dk]==1){

				$sql_f = "INSERT INTO menu_lunch_hf 
								SET contents='".addslashes($footers_txts[$dk])."',
									menu_id=".$lunch_menu_id.",
									content_type='footer',
									all_in=".$allin_footers[$dk].", 
									`show`=".$show_footers[$dk];
				if($dk>0){ $sql_f .= ",specific_day='".$day."'"; }			
			
				mysql_query($sql_f);
				
			}else{

				//if existing record then just update
				$sql_f = "UPDATE menu_lunch_hf 
								SET contents='".addslashes($footers_txts[$dk])."',
									all_in=".$allin_footers[$dk].", 
									`show`=".$show_footers[$dk]." 
									WHERE id=".$footer_ids[$dk];
				mysql_query($sql_f);		
			}


		}else{
			//if not existing then add/insert
			if($footers_txts[$dk]!=''){		
				$sql_f = "INSERT INTO menu_lunch_hf 
								SET contents='".addslashes($footers_txts[$dk])."',
									all_in=".$allin_footers[$dk].", 
									menu_id=".$lunch_menu_id.",
									content_type='footer',
									`show`=".$show_footers[$dk];
				if($dk>0){ $sql_f .= ",specific_day='".$day."'"; }
				
				mysql_query($sql_f);
			}
		}

		//execute SQL only if string is not empty
		//if($sql_h!='')
		//	mysql_query($sql_h) or die($dk.' >> '.$sql_h);
		//if($sql_f!='')
		//	mysql_query($sql_f) or die($dk.' >> '.$sql_f);	

	}
	//end of -> headers and footers

	//delete existing items that are not in the array
	$existing_items_qry = mysql_query("SELECT id FROM menu_lunch_items WHERE menu_id=".$lunch_menu_id);
	
	while($existing_res = mysql_fetch_assoc($existing_items_qry)){
		if(!in_array($existing_res['id'], $existing_items)){
			mysql_query("DELETE FROM menu_lunch_items WHERE id=".$existing_res['id']);
		}
	}
	
	//for newly added courses (all-week)
	
	//get the last
	$last_sortorder_sql = "SELECT `order` FROM menu_lunch_items 
						   WHERE menu_id=".$lunch_menu_id." 
						   AND 
						   (					   
						   	(year_for=".$menu_year." AND week_for=".$menu_week." )
							   OR
							all_in=1	
						   )
						   
						   AND specific_day IS NULL";
	
	$last_sortorder_qry = mysql_query($last_sortorder_sql);
	$last_sortorder_num = mysql_num_rows($last_sortorder_qry);
	$last_sortorder = $last_sortorder_num+0;
	
	//for newly added (all-week) courses
//	foreach($courses as $key => $course_desc){
	foreach($names as $key => $course_name){
													  
		if(trim($course_name)!=''){

			$last_sortorder++;
					
			mysql_query("INSERT INTO menu_lunch_items SET menu_id=".$lunch_menu_id.",
														  currency_id=".$currency['id'].",
														  name='".addslashes($course_name)."',
														  description='".addslashes($courses[$key])."',
														  takeaway=".($a_lmta_box[$key]+0).",
														  takeaway_price=".($a_lmta_price[$key]+0).",
														  year_for=".$menu_year.",
														  week_for=".$menu_week.",
														  `order`=".$last_sortorder.",
														  all_in=".($course_allin_additional[$key]+0).",
														  price=".$prices[$key]) or die(mysql_error());	
		}
		
	}



	if($new_record==1){

		//existing course for Monday to Friday
	
		//get the last sort
		$last_sortorder_sql = "SELECT `order` FROM menu_lunch_items 
							   WHERE menu_id=".$mr_lunch_menu_id;
		$last_sortorder_qry = mysql_query($last_sortorder_sql);
		$last_sortorder_num = mysql_num_rows($last_sortorder_qry);
		$last_sortorder = $last_sortorder_num+0;
	
		//foreach($existing_courses as $key => $course_desc){
		foreach($existing_names as $key => $course_name){
		
			if($existing_items[$key]>0){
				
				$the_day = '';
				if($existing_days[$key]!='0'){ $the_day = "specific_day='".$existing_days[$key]."', "; }
																		  
				mysql_query("INSERT INTO menu_lunch_items SET currency_id=".$currency['id'].",
															  name='".addslashes($course_name)."',
															  description='".addslashes($existing_courses[$key])."',
															  price=".$existing_prices[$key].", 
															  takeaway=".($e_lmta_box[$key]+0).",
															  takeaway_price=".($e_lmta_price[$key]+0).",
															  year_for=".$menu_year.",
															  week_for=".$menu_week.",
															  all_in=".($existing_allweeks[$key]+0).",
															  ".$the_day."
															  `order`=".$sortings[$key].",
															  menu_id=".$lunch_menu_id) or die(mysql_error());	
			
			}
		}	
		//for existing courses
	
	}

	//for newly added courses (Specific Day)

	//foreach($additional_courses as $key => $course_desc){
	foreach($additional_names as $key => $course_name){
													  
		if(trim($course_name)!=''){



			//get the last sort
			$last_sortorder_sql = "SELECT `order` FROM menu_lunch_items 
								   WHERE 
									( 
										(year_for=".$menu_year." AND week_for=".$menu_week." )
										OR
										all_in=1	
										
									)
									AND specific_day='".$additional_days[$key]."' 
								   ";			
			
			$last_sortorder_qry = mysql_query($last_sortorder_sql);
			$last_sortorder_num = mysql_num_rows($last_sortorder_qry);
			$last_sortorder = $last_sortorder_num+0;
					
			$last_sortorder++;

			mysql_query("INSERT INTO menu_lunch_items SET menu_id=".$lunch_menu_id.",
														  currency_id=".$currency['id'].",
														  name='".addslashes($additional_names[$key])."',
														  description='".addslashes($additional_courses[$key])."',
														  specific_day='".$additional_days[$key]."',
														  year_for=".$menu_year.",
														  week_for=".$menu_week.",
														  `order`=".$last_sortorder.",
														  all_in=".($course_allin_additional[$key]+0).",
														  takeaway=".($a_lmta_box[$key]+0).",
														  takeaway_price=".($a_lmta_price[$key]+0).",
														  price=".$additional_prices[$key]) or die(mysql_error());	
		}
		
	}


	if($new_record==0){

		//existing course for Monday to Friday
	
		//get the last sort
		$last_sortorder_sql = "SELECT `order` FROM menu_lunch_items 
							   WHERE menu_id=".$lunch_menu_id." AND specific_day IS NULL";
		$last_sortorder_qry = mysql_query($last_sortorder_sql);
		$last_sortorder_num = mysql_num_rows($last_sortorder_qry);
		$last_sortorder = $last_sortorder_num+0;
	
//		foreach($existing_courses as $key => $course_desc){
		foreach($existing_names as $key => $course_name){
		
			if($existing_items[$key]>0){														  
				mysql_query("UPDATE menu_lunch_items SET currency_id=".$currency['id'].",
															  name='".addslashes($course_name)."',
															  description='".addslashes($existing_courses[$key])."',
															  price=".$existing_prices[$key].", 
															  year_for=".$menu_year.",
															  week_for=".$menu_week.",
															  takeaway=".($e_lmta_box[$key]+0).",
															  takeaway_price=".($e_lmta_price[$key]+0).",
															  all_in=".($existing_allweeks[$key]+0).",
															  `order`=".$sortings[$key]."
															  WHERE id=".$existing_items[$key]) or die(mysql_error());	
			
			}
		}	
		//for existing courses
	
	}
	
	echo '1|SAVED!';
	
}else{
	echo '0|Oppsss!!! An error has occurred.<br><br>'.mysql_error().'<br><br>'.$query;
}
?>