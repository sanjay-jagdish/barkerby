<?php
require('../config/config.php');
ini_set('display_errors',0);
$res_id = trim($_POST['res_id']);
$status_id = trim($_POST['status']);

$status_key_class = array('B|blue','S|green','W|red','N|black');

mysql_query('UPDATE reservation SET status='.$status_id.' WHERE id='.$res_id);

echo $status_key_class[$status_id].'|'.date('y-m-d',strtotime($_POST['date_selected']));
?>
