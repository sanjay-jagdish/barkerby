<?php session_start();
	include '../config/config.php';
	
	$pax=explode('*',mysql_real_escape_string(strip_tags($_POST['pax'])));
	
	$count=0;
	$exists='';
	for($i=0;$i<count($pax);$i++){
		$val=explode('^',$pax[$i]);

		$q=mysql_query("select id from table_masterlist where name='".trim($val[0])."' and deleted=0");
		if(mysql_num_rows($q) > 0){
			$count+=1;
			$exists.=($i+1).'*';
		}

	}

	if($count==0){

		for($i=0;$i<count($pax);$i++){
			$val=explode('^',$pax[$i]);

			mysql_query("insert into table_masterlist(name,seats) values('".trim($val[0])."',".$val[1].")");	
			

		}	
		echo 'good';

	}
	else{
		echo substr($exists,0,strlen($exists)-1);
	}
	
?>