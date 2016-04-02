<?php session_start();
	include '../config/config.php';
	
	$id=mysql_real_escape_string(strip_tags($_POST['id']));
	$mln=trim(mysql_real_escape_string(strip_tags($_POST['mln'])));
	$mls=trim(mysql_real_escape_string(strip_tags($_POST['mls'])));

	$q=mysql_query("select id from table_masterlist where name='".$mln."' and deleted=0");
	$r=mysql_fetch_assoc($q);
	
	if(mysql_num_rows($q)>0){
		if($r['id']==$id){
			mysql_query("update table_masterlist set name='".$mln."', seats='".$mls."' where id=".$id);
		}
		else{
			echo 'Invalid';
		}
	}
	else{
		mysql_query("update table_masterlist set name='".$mln."' where id=".$id);
	}
?>