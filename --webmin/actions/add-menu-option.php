<?php session_start();

	include '../config/config.php';
	
	$options = $_POST['option'];
	$menu_id = $_POST['id'];
	$group = $_POST['group'];
	
	if(count($group)>0){
		
		foreach($group as $grp){
			
			list($thegroupname,$type) = explode('^^^',$grp);
			list($groupname, $count) = explode('***',$thegroupname);
			
			//for menu_option_details
			mysql_query("insert into menu_option_details(menu_id, name, single_option) values('".$menu_id."', '".$groupname."', '".$type."')") or die(mysql_error());
			
			$id = mysql_insert_id();
			
			foreach($options as $val){
				
				list($thegrp,$optname,$price) = explode('^*^',$val);
				
				if($thegrp==$thegroupname){
				
					mysql_query("insert into menu_options(menu_option_detail_id,name,price,menu_id) values ('".$id."', '".$optname."', '".$price."', '".$menu_id."')") or die(mysql_error()) or die(mysql_error());
				
				}
				
			}	
			
		}
		
	}
	
?>