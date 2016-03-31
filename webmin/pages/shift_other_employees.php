<?php
session_start();
 include_once('redirect.php'); 
include '../config/config.php';

$employee_sql = "SELECT type_id  
				  FROM account WHERE id=".$_POST['account_id'];
$employee_qry = mysql_query($employee_sql);
$employee_res = mysql_fetch_assoc($employee_qry);

//determine the time using the passed schedule_id
$sched_id = $_POST['sched_id'];
$time_sql = "SELECT start_time, end_time FROM schedule WHERE id=".$sched_id;
$time_qry = mysql_query($time_sql);
$time_res = mysql_fetch_assoc($time_qry);

$date_dropped = $_POST['date'];
$day_dropped = date('D',strtotime($_POST['date']));


$employees_sql = "SELECT a.id, CONCAT(fname, ' ', lname) AS name, email  
				  FROM account a, type t 
				  
				  WHERE a.id<>".$_POST['account_id']." 
				  
				  	AND a.type_id=".$employee_res['type_id']." 
			  
					AND a.id NOT IN
				 	(
					SELECT account_id AS id FROM schedule WHERE deleted=0 
						AND ( '".$date_dropped."' BETWEEN STR_TO_DATE(valid_from, '%m/%d/%Y') AND STR_TO_DATE(valid_until, '%m/%d/%Y') )
						AND ( '".$time_res['start_time']."' BETWEEN STR_TO_DATE(start_time, '%H:%i') AND STR_TO_DATE(end_time, '%H:%i')  )
						AND days NOT LIKE '%".$day_dropped."%'
					)
					GROUP BY a.id 
					ORDER BY CONCAT(fname, ' ', lname) ASC
					";
				 
$employees_qry = mysql_query($employees_sql);
$employees_num = mysql_num_rows($employees_qry);


//<option value='".$employees_res['id']."'>".$employees_res['name']." &lt;".$employees_res['email']."&gt;</option>
if($employees_num>0){
?>

<br />
List of off-duty employees whom you may offer the scheduled shift.<br /><br />
<i>To select/desselect multiple names, pres and hold the <strong>Ctrl</strong> key (Windows) or <strong>Cmd</strong> key (Macintosh) while clicking the names.</i><br /><br />
<select multiple="multiple" id="other_emp_selected">
<?php
//foreach($other_employees as $id => $data){	
while($employees_res = mysql_fetch_assoc($employees_qry)){
	//$emp_data = explode('^',$data);
	//echo "<option value='".$id."'>".$emp_data[0]." &lt;".$emp_data[1]."&gt;</option>\n";
	echo "<option value='".$employees_res['id']."'>".$employees_res['name']." &lt;".$employees_res['email']."&gt;</option>\n";
}
?>
</select>
<?php

}else{
?>
<br />
<div style="color:#F00;">Ingen personal tillgänglig.</div>
<?php	

}
?>