<?php session_start();

	define("HOST",'localhost');
	/*define("USER",'root');
	define("PASSWORD",'root');
	define("DBNAME",'crisp');*/
	
	/*define("USER",'restaura_wrdp1');
	define("PASSWORD",'IZb57fpMmp7f');
	define("DBNAME",'restaura_wrdp1');*/
	
	define("USER",'limone_wrdp1');
	define("PASSWORD",'owwFkNjB5hee');
	define("DBNAME",'limone_wrdp1');
	
	$con=mysql_connect(HOST,USER,PASSWORD);
	mysql_query("SET NAMES 'utf8'");
	mysql_query("SET CHARACTER SET utf8");	
	
	
	if($con){
		mysql_select_db(DBNAME);
	}
	else{
		die("CANNOT CONNECT TO THE DATABASE ".DBNAME);
	}
	
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
	
	$countries=array(0 => 'Afghanistan','Aland Islands','Albania','Algeria','American Samoa','Andorra','Angola','Anguilla','Antigua and Barbuda','Argentina','Armenia','Aruba','Australia','Austria','Azerbaijan','Bahrain','Bangladesh','Barbados','Belarus','Belgium','Belize','Benin','Bermuda','Bhutan','Bolivia','Bosnia and Herzegovina','Botswana','Brazil','British Indian Ocean Territory','British Virgin Islands','Brunei','Bulgaria','Burkina Faso','Burma','Burundi','Cambodia','Cameroon','Canada','Cape Verde','Caribbean Netherlands','Cayman Islands','Central African Republic','Chad','Chile','China','Christmas Island','Colombia','Comoros','Cook Islands','Costa Rica','Croatia','Cuba','Curacao','Cyprus','Czech Republic','Democratic Republic of the Congo','Denmark','Djibouti','Dominica','Dominican Republic','Ecuador','Egypt','El Salvador','Equatorial Guinea','Eritrea','Estonia','Ethiopia','Falkland Islands','Faroe Islands','Fiji','Finland','France','French Guiana','French Polynesia','Gabon','Georgia','Germany','Ghana','Gibraltar','Greece','Greenland','Grenada','Guadeloupe','Guam','Guatemala','Guernsey','Guinea','Guinea-Bissau','Guyana','Haiti','Honduras','Hong Kong','Hungary','Iceland','India','Indonesia','Iran','Iraq','Ireland','Isle of Man','Israel','Italy','Ivory Coast','Jamaica','Japan','Jersey','Jordan','Kazakhstan','Kenya','Kiribati','Kuwait','Kyrgyzstan','Laos','Latvia','Lebanon','Lesotho','Liberia','Libya','Liechtenstein','Lithuania','Luxembourg','Macau','Macedonia','Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Marshall Islands','Martinique','Mauritania','Mauritius','Mayotte','Mexico','Micronesia','Moldova','Monaco','Mongolia','Montenegro','Montserrat','Morocco','Mozambique','Namibia','Nauru','Nepal','Netherlands','New Caledonia','New Zealand','Nicaragua','Niger','Nigeria','Niue','Norfolk Island','North Korea','Northern Mariana Islands','Norway','Oman','Pakistan','Palau','Palestinian Territory','Panama','Papua New Guinea','Paraguay','Peru','Philippines','Poland','Portugal','Puerto Rico','Qatar','Republic of the Congo','Reunion','Romania','Russia','Rwanda','Saint Barthelemy','Saint Helena','Saint Kitts and Nevis','Saint Lucia','Saint Martin','Saint Pierre and Miquelon','Saint Vincent and the Grenadines','Samoa','San Marino','Sao Tome and Principe','Saudi Arabia','Senegal','Serbia','Seychelles','Sierra Leone','Singapore','Sint Maarten','Slovakia','Slovenia','Solomon Islands','Somalia','South Africa','South Korea','South Sudan','Spain','Sri Lanka','Sudan','Suriname','Svalbard','Swaziland','Sweden','Switzerland','Syria','Taiwan','Tajikistan','Tanzania','Thailand','The Bahamas','The Gambia','Timor-Leste','Togo','Tokelau','Tonga','Trinidad and Tobago','Tunisia','Turkey','Turkmenistan','Turks and Caicos Islands','Tuvalu','Uganda','Ukraine','United Arab Emirates','United Kingdom','United States','Uruguay','Uzbekistan','Vanuatu','Vatican City','Venezuela','Vietnam','Virgin Islands','Wallis and Futuna','Western Sahara','Yemen','Zambia','Zimbabwe');
	
	//mysql_query("SET time_zone = 'Europe/Stockholm'");
	
	function get_client_ip() {
		 $ipaddress = '';
		 if (getenv('HTTP_CLIENT_IP'))
			 $ipaddress = getenv('HTTP_CLIENT_IP');
		 else if(getenv('HTTP_X_FORWARDED_FOR'))
			 $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		 else if(getenv('HTTP_X_FORWARDED'))
			 $ipaddress = getenv('HTTP_X_FORWARDED');
		 else if(getenv('HTTP_FORWARDED_FOR'))
			 $ipaddress = getenv('HTTP_FORWARDED_FOR');
		 else if(getenv('HTTP_FORWARDED'))
			 $ipaddress = getenv('HTTP_FORWARDED');
		 else if(getenv('REMOTE_ADDR'))
			 $ipaddress = getenv('REMOTE_ADDR');
		 else
			 $ipaddress = 'UNKNOWN';
		
		 return $ipaddress; 
	 }
	 
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
	
	function getUsersName($id){
		if($id==''){
			return 'All';
		}
		else{
			
			$ids=explode(",",$id);
			$holder='';
			for($i=0;$i<count($ids);$i++){
				
				$q=mysql_query("select concat(fname,' ',lname) from account where id=".$ids[$i]);
				$r=mysql_fetch_array($q);
				
				$holder.=ucwords($r[0]).", ";
			}
			
			return substr($holder,0,strlen($holder)-2);
			
		}
	}
	
	function getRecipients($id){
		if($id==''){
			return '';
		}
		else{
		
			$ids=explode(",",$id);
			$holder='';
			for($i=0;$i<count($ids);$i++){
				
				$q=mysql_query("select concat(fname,' ', lname, ' - ', email) from account where id=".$ids[$i]);
				$r=mysql_fetch_array($q);
				
				$holder.=ucwords($r[0]).", ";
			}
			
			return $holder;
		
		}
	}
	
	function maxPax($id){
		$q=mysql_query("select id from table_detail where restaurant_detail_id=".$id);
		return mysql_num_rows($q);
	}

	function maxSeats($id){
		$q=mysql_query("select sum(max_pax) as total from table_detail where restaurant_detail_id=".$id);
		$r=mysql_fetch_assoc($q);

		return $r['total'];
	}
	
	function numberTable($resid){
		
		$q=mysql_query("select id from reservation_table where reservation_id=".$resid);
		return mysql_num_rows($q);
		
	}
	
	function replaceDayname($days){
		
		$day=array('Mon'=>'Mån','Tue'=>'Tis','Wed'=>'Ons','Thu'=>'Tor','Fri'=>'Fre','Sat'=>'Lör','Sun'=>'Sön');
		
		$daynames=explode(',',$days);
		
		$thedaynames='';
		for($i=0;$i<count($daynames);$i++){
			$theday=trim($daynames[$i]);
			$thedaynames.=$day[$theday].', ';
		}
		
		return substr($thedaynames,0,strlen($thedaynames)-2);
		
	}
	
	function dayName($date){
		
		$dayname=array('Mon'=>'Mån','Tue'=>'Tis','Wed'=>'Ons','Thu'=>'Tor','Fri'=>'Fre','Sat'=>'Lör','Sun'=>'Sön');
		
		$day=explode(',',$date);
	
		return str_replace($day[0],$dayname[$day[0]],$date);
		
	}
	
	
	function addPrefix($countdays){
		if($countdays==0){
			$prefx='Idag';
		}
		else if($countdays==1){
			$prefx='Imorgon';
		}
		else if($countdays<0){
			if($countdays==-1){
				$prefx='Igår';
			}
			else{
				$prefx='Passerat datum';
			}
		}
		else{
			$prefx='Om '.$countdays.' dagar';
		}
		
		return $prefx;
	}
	
	function getCategoryName($id){
		$q=mysql_query("select c.name as name from category as c, sub_category as s where c.id=s.category_id and s.id=".$id);
		$r=mysql_fetch_assoc($q);
		return $r['name'];
	}
	
	function timeFormatting($mins){
	
		$hour=intval($mins/60);
		$mins=$mins-($hour*60);
		
		$time=$mins.' min';
		
		if($hour>0){
			 if($mins>0)
				$time=$hour.' tim '.$mins.' min';
			 else
				$time=$hour.' tim';
		}
		
		return $time;
		
	}
	
	
	$menu_type=array(1=>'Dine In', 2=>'Take Away');
?>
