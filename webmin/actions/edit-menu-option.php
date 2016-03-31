<?php session_start();
	include '../config/config.php';
	
	$options = $_POST['option'];
	$menu_id = $_POST['id'];
	$group = $_POST['group'];
	
	//removing the existing data first
	$q=mysql_query("select id from menu_option_details where menu_id='".$menu_id."'");
	while(list($id) = mysql_fetch_array($q)){
		mysql_query("delete from menu_options where menu_option_detail_id='".$id."'");
		mysql_query("delete from menu_option_details where id='".$id."'");
	}
	
	//insertion process
	if(count($group)>0){
		
		foreach($group as $grp){
			
			list($thegroupname,$type,$grporder) = explode('^^^',$grp);
			list($groupname, $count) = explode('***',$thegroupname);
			
			//for menu_option_details
			mysql_query("insert into menu_option_details(menu_id, name, single_option, order_by) values('".$menu_id."', '".$groupname."', '".$type."', '".$grporder."')") or die(mysql_error());
			
			$id = mysql_insert_id();
			
			foreach($options as $val){
				
				list($thegrp,$optname,$price,$optorder) = explode('^*^',$val);
				
				if($thegrp==$thegroupname){
				
					mysql_query("insert into menu_options(menu_option_detail_id,name,price,order_by,menu_id) values ('".$id."', '".$optname."', '".$price."','".$optorder."','".$menu_id."')") or die(mysql_error());
				
				}
				
			}	
			
		}
		
	}
?>