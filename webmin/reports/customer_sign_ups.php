<?php
session_start();
include '../config/config.php';
include '../reports/params.php';

$debug = $_GET['debug']+0;

if($debug==1)
	ini_set('display_errors',0);

if($_SESSION['login']['id']==0){ die('<br><br><font color="red">You need to be logged in to view this report.</font>'); }

$top = 10; //default top count

if( trim($_POST['top'])>0 ){
	$top = trim($_POST['top']);
}

$date_start = date("Y-m-d",strtotime('last week'));
$date_end = date("Y-m-d",strtotime('today'));

if(trim($_POST['date_start'])!=''){
	$date_start = date("Y-m-d",strtotime(trim($_POST['date_start'])));
}
if(trim($_POST['date_end'])!=''){
	$date_end = date("Y-m-d",strtotime(trim($_POST['date_end'])));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<link rel="stylesheet" href="../css/reports_print.css" />

<title>Customer Sign-Ups</title>
</head>

<?php
$customers_sql = "SELECT * FROM account WHERE type_id=5 AND DATE(date_created)>='".$date_start."' AND DATE(date_created)<='".$date_end."'";
$customers_qry = mysql_query($customers_sql);
$customers_num = mysql_num_rows($customers_qry);

if($debug==1)
echo $customers_sql;
?>

<body>
	
<div class="noprint top_spacer">&nbsp;</div>
    
    <div class="contents">
    
		<div class="report_title">
        	Customer Sign-Ups 
        </div>
		
        <div class="report_period">
			
            <div class="printonly">
                From: <b><?php echo date("m/d/Y",strtotime($date_start)); ?></b> To:  
                <b><?php echo date("m/d/Y",strtotime($date_end)); ?></b>
			</div>
            
            <div class="noprint">            
                <form method="post">
                    From: 
                  <input type="text" name="date_start" value="<?php echo date("m/d/Y",strtotime($date_start)); ?>" size="14" style="text-align:center;" />
                    To: 
                  <input type="text" name="date_end" value="<?php echo date("m/d/Y",strtotime($date_end)); ?>" size="14" style="text-align:center;" />
                    <input type="submit" name="generate" value=" Generate " />
                </form>
             </div>
                
        </div>
	
    	<?php
        if($customers_num==0){
			echo '<font color="red"><be><br>There are no customer sign-ups for the period selected.</font>';
		}else{
		?>
        <br />
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
             <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Mobile</th>
                    <th>Address</th>
                    <th>Sign-Up Date</th>                
                </tr>
             <thead>
             <tbody>
             <?php
			 $cnt=1;
			 while($res = mysql_fetch_assoc($customers_qry)){
			 ?>
             	<tr>
                    <td style="text-align:right;"><?php echo $cnt; ?></td>
                    <td><?php echo $res['fname'].' '.$res['mname'].' '.$res['lname']; ?></td>
                    <td><?php echo $res['email']; ?></td>
                    <td><?php echo $res['phone_numer']; ?></td>
                    <td><?php echo $res['mobile_number']; ?></td>
                    <td><?php echo $res['street'].'<br>'.$res['city'].'<br>'.$res['state'].' '.$res['zip']; ?></td>
                    <td><?php echo date("m/d/Y H:i:s",strtotime($res['date_created'])); ?></td>                
                </tr>
             <?php
			 $cnt++;
			 }
			 ?>
             </tbody>
             <tfoot>
             	<tr>
                	<th colspan="7">&nbsp;	</th>
                </tr>
             </tfoot>
        </table>
    	<?php
		}
		?>
    	<div class="report_timestamp">Generated <?php echo date("m/d/Y H:i:sa"); ?></div>
        
    </div>
    
</body>
</html>