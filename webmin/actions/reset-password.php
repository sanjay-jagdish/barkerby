<?php 

include '../config/config.php';

$id=mysql_real_escape_string(strip_tags($_POST['id']));
$pass=mysql_real_escape_string(strip_tags($_POST['pass1']));

$q = mysql_query("Select fname from account where id = '". encrypt_decrypt('decrypt',$id) ."' and type_id<>5 and deleted=0") or die(mysql_error());

$row = mysql_fetch_assoc($q);


if(!empty($row['fname'])){
	mysql_query("update account set password = md5('". $pass ."'), readable = '". $pass ."' where id = '". encrypt_decrypt('decrypt',$id) ."' and type_id<>5 and deleted=0") or die(mysql_error());	
	
	echo 'successfull';
}else{
	echo 'invalid';
}
?>