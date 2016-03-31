<?php
require('../../../config/config.php');

$customer_list='';
/*$query = mysql_query("SELECT id, CONCAT(fname, ' ', lname) AS name, phone_number
						 FROM account 
						 WHERE deleted=0 AND type_id=5 ORDER BY CONCAT(fname, ' ', lname) ASC") or die(mysql_error());	
while($row = mysql_fetch_assoc($query)){
	$customer_list.='{"id":'.$row['id'].', "name":'.$row['name'].', "phone":'.$row['phone_number']."},";
}

$customer_list=substr($customer_list,0,strlen($customer_list)-1);*/

$customer_list.='{"id": "1", "name": "abc", "phone": "123"}';

?>

[
<?php echo $customer_list; ?>
]