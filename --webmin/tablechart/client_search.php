<?php
ini_set('display_errors',1);

if ($term = @$_GET['term']) {
	require('../config/config.php');
    $q = strtolower($term);
    $query = mysql_query("SELECT id, CONCAT(fname, ' ', lname) AS name 
						 FROM account 
						 WHERE fname LIKE '%$q%' OR lname LIKE '%$q%' 
						 AND deleted=0 AND type_id=5") or die(mysql_error());
    $results = array();
    while($row = mysql_fetch_array($query)){
		//$results[] = array( 'id' => $row['id'] , 'label' => $row['email'], 'value' => $row['name'] );
		$row['value']=htmlentities(stripslashes($row['name']));
		$row['id']=(int)$row['id'];
		$row_set[] = $row;//build an array
	}
    echo json_encode($row_set);
}
?>