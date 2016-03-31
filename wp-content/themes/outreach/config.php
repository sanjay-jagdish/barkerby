<?php
	define("HOST",'localhost');
	define("USER",'root');
	define("PASSWORD",'root');
	define("DBNAME",'barkarby_mise');
	
	$con=mysql_connect(HOST,USER,PASSWORD);
	
	mysql_query("SET NAMES 'utf8'");
	mysql_query("SET CHARACTER SET utf8");	
	
	if($con){
		mysql_select_db(DBNAME);
	}
	else{
		die('CANNOT CONNECT TO THE DATABASE '.DBNAME);
	}
	
	
	$q=mysql_query("select var_value from settings where var_name='timezone'");
	$r=mysql_fetch_assoc($q);
	
	date_default_timezone_set($r['var_value']);
	
	$q=mysql_query("select var_value from settings where var_name='site_url'");
	$r=mysql_fetch_assoc($q);
	$site_url=$r['var_value'];
	
	
	$q=mysql_query("select var_value from settings where var_name='week_starts'");
	$row=mysql_fetch_assoc($q);
	$day_start=$row['var_value'];

	//fetch Garcon settings and save as array
	//added by VicEEr
	$settings_sql = "SELECT * FROM settings";
	$settings_qry = mysql_query($settings_sql);
	
	while($settings_res = mysql_fetch_assoc($settings_qry)){
		$garcon_settings[$settings_res['var_name']] = $settings_res['var_value'];
	}	
	//END-OF fetch Garcon settings and save as array
	//added by VicEEr

	date_default_timezone_set($garcon_settings['timezone']);
	mysql_query("SET time_zone = '".$garcon_settings['time_offset']."'");

	//added by VicEEr	
	function acctid_name_email($id){
		$s = "SELECT CONCAT(fname,'&nbsp;',lname) AS name, email FROM account WHERE id=".$id;
		$q = mysql_query($s);
		$r = mysql_fetch_assoc($q);
		return $r;
	}
	//added by VicEEr	
	function schedid_time($id){
		$s = "SELECT start_time, end_time FROM schedule WHERE id=".$id;
		$q = mysql_query($s);
		$r = mysql_fetch_assoc($q);
		return $r;
	}
	//added by VicEEr
	function acctid_name($id){
		$s = "SELECT CONCAT(fname,'&nbsp;',lname) AS name FROM account WHERE id=".$id;
		$q = mysql_query($s);
		$r = mysql_fetch_assoc($q);
		return $r['name'];
	}	
	
	
	function formatDate($date){
		//yyyy-mm-dd to mm-dd-yyyy
		$date=explode("-",$date);
		
		return $date[1]."/".$date[2]."/".$date[0];
	}
	
	
	function encrypt_decrypt($action, $string) {
		$output = false;
	
		$encrypt_method = "AES-256-CBC";
		$secret_key = 'This is my secret key';
		$secret_iv = 'This is my secret iv';
	
		// hash
		$key = hash('sha256', $secret_key);
		
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
	
		if( $action == 'encrypt' ) {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		}
		else if( $action == 'decrypt' ){
			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}
	
		return $output;
	}
	
	$countries=array(0 =>'Afghanistan','Aland Islands','Albania','Algeria','American Samoa','Andorra','Angola','Anguilla','Antigua and Barbuda','Argentina','Armenia','Aruba','Australia','Austria','Azerbaijan','Bahrain','Bangladesh','Barbados','Belarus','Belgium','Belize','Benin','Bermuda','Bhutan','Bolivia','Bosnia and Herzegovina','Botswana','Brazil','British Indian Ocean Territory','British Virgin Islands','Brunei','Bulgaria','Burkina Faso','Burma','Burundi','Cambodia','Cameroon','Canada','Cape Verde','Caribbean Netherlands','Cayman Islands','Central African Republic','Chad','Chile','China','Christmas Island','Colombia','Comoros','Cook Islands','Costa Rica','Croatia','Cuba','Curacao','Cyprus','Czech Republic','Democratic Republic of the Congo','Denmark','Djibouti','Dominica','Dominican Republic','Ecuador','Egypt','El Salvador','Equatorial Guinea','Eritrea','Estonia','Ethiopia','Falkland Islands','Faroe Islands','Fiji','Finland','France','French Guiana','French Polynesia','Gabon','Georgia','Germany','Ghana','Gibraltar','Greece','Greenland','Grenada','Guadeloupe','Guam','Guatemala','Guernsey','Guinea','Guinea-Bissau','Guyana','Haiti','Honduras','Hong Kong','Hungary','Iceland','India','Indonesia','Iran','Iraq','Ireland','Isle of Man','Israel','Italy','Ivory Coast','Jamaica','Japan','Jersey','Jordan','Kazakhstan','Kenya','Kiribati','Kuwait','Kyrgyzstan','Laos','Latvia','Lebanon','Lesotho','Liberia','Libya','Liechtenstein','Lithuania','Luxembourg','Macau','Macedonia','Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Marshall Islands','Martinique','Mauritania','Mauritius','Mayotte','Mexico','Micronesia','Moldova','Monaco','Mongolia','Montenegro','Montserrat','Morocco','Mozambique','Namibia','Nauru','Nepal','Netherlands','New Caledonia','New Zealand','Nicaragua','Niger','Nigeria','Niue','Norfolk Island','North Korea','Northern Mariana Islands','Norway','Oman','Pakistan','Palau','Palestinian Territory','Panama','Papua New Guinea','Paraguay','Peru','Philippines','Poland','Portugal','Puerto Rico','Qatar','Republic of the Congo','Reunion','Romania','Russia','Rwanda','Saint Barthelemy','Saint Helena','Saint Kitts and Nevis','Saint Lucia','Saint Martin','Saint Pierre and Miquelon','Saint Vincent and the Grenadines','Samoa','San Marino','Sao Tome and Principe','Saudi Arabia','Senegal','Serbia','Seychelles','Sierra Leone','Singapore','Sint Maarten','Slovakia','Slovenia','Solomon Islands','Somalia','South Africa','South Korea','South Sudan','Spain','Sri Lanka','Sudan','Suriname','Svalbard','Swaziland','Sweden','Switzerland','Syria','Taiwan','Tajikistan','Tanzania','Thailand','The Bahamas','The Gambia','Timor-Leste','Togo','Tokelau','Tonga','Trinidad and Tobago','Tunisia','Turkey','Turkmenistan','Turks and Caicos Islands','Tuvalu','Uganda','Ukraine','United Arab Emirates','United Kingdom','United States','Uruguay','Uzbekistan','Vanuatu','Vatican City','Venezuela','Vietnam','Virgin Islands','Wallis and Futuna','Western Sahara','Yemen','Zambia','Zimbabwe');
	
	mysql_query("SET time_zone = '".$garcon_settings['timezone']."'");
	
	function orderStatus($id){
		$q=mysql_query("select description from status where id=".$id) or die(mysql_error());
		$r=mysql_fetch_array($q);
		if(mysql_num_rows($q) > 0){
			return $r[0];
		}
		else{
			return 'Pending';
		}
	} 
	
	function processedBy($id){
		if($id!=''){
			
			$q=mysql_query("select concat(fname,' ',mname,' ',lname) from account where id=".$id);
			$r=mysql_fetch_array($q);
			
			return $r[0];
			
		}
		else{
			return '';
		}
	}
	
	function numberTable($resid){
		
		$q=mysql_query("select id from reservation_table where reservation_id=".$resid);
		return mysql_num_rows($q);
		
	}

	function dayName($date){
		
		$dayname=array('Mon'=>'Mån','Tue'=>'Tis','Wed'=>'Ons','Thu'=>'Tor','Fri'=>'Fre','Sat'=>'Lör','Sun'=>'Sön');
		
		$day=explode(' ',$date);
	
		return str_replace($day[0],$dayname[$day[0]],$date);
		
	}
	
	function getTakeawayCategorySubcategory($id,$tablename){
		$q=mysql_query("select name from ".$tablename." where id='".$id."'");
		$r=mysql_fetch_assoc($q);
		
		return $r['name'];
	}
	
	function getMenuName($id){
		$q=mysql_query("select name from menu where id='".$id."'");
		$r=mysql_fetch_assoc($q);
		
		return $r['name'];
	}

	function getLunchMenuName($id){
		$q=mysql_query("select name from menu_lunch_items where id='".$id."'");
		$r=mysql_fetch_assoc($q);
		
		return strip_tags($r['name']);
	}
	
	function getCurrentCurrency(){
		$q=mysql_query("select shortname from currency where deleted=0 and set_default=1") or die(mysql_error());
		$r=mysql_fetch_assoc($q);
		
		return strtolower($r['shortname']);
	}
	
	
?>