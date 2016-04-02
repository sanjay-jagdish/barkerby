<?php
session_start();
include '../config/config.php';
include '../reports/params.php';

if($_SESSION['login']['id']==0){ die('<br><br><font color="red">You need to be logged in to view this report.</font>'); }

$top = 10; //default top count

if( trim($_POST['top'])>0 ){
	$top = trim($_POST['top']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<link rel="stylesheet" href="../css/reports_print.css" />

<title>Report Title</title>
</head>

<?php

?>

    <div class="noprint top_spacer">&nbsp;</div>
    
    <div class="contents">
    
		<div class="report_title">
        	Report Title
        </div>
		
        <div class="report_period">
        	<form method="post">
            	From: <input type="text" name="date_start" size="10" />
                To: <input type="text" name="date_end" size="10" />
                <input type="submit" name="generate" value=" Generate " />
            </form>
            <br />
        </div>
	
    	
        <table>
        
        </table>
    
    	<div class="report_timestamp">Generated <?php echo date("m/d/Y H:i:sa"); ?></div>
        
    </div>

</body>
</html>