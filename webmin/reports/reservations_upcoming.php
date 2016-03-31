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

$date_end = date("Y-m-t");

if(trim($_POST['date_end'])!=''){
	$date_end = date("Y-m-d",strtotime(trim($_POST['date_end'])));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<link rel="stylesheet" href="../css/reports_print.css" />

<title>RESERVATIONS: Upcoming</title>
</head>

<?php
$reservations_sql = "SELECT COUNT(id) AS count, `date` FROM reservation 
					 WHERE reservation_type_id=1 AND DATE(`date`)>='".date('Y-m-d')."' AND DATE(`date`)<='".$date_end."' 
					 AND CONCAT(`date`,' ',`time`)>='".date('Y-m-d H:i:s')."' 
					 GROUP BY `date` 
					 ORDER BY `date` ASC";
$reservations_qry = mysql_query($reservations_sql);
$reservations_num = mysql_num_rows($reservations_qry);

//debugging
if($debug==1)
	echo $reservations_sql.'<br>';

?>

<body>
	
<div class="noprint top_spacer">&nbsp;</div>
    
    <div class="contents">
    
		<div class="report_title">RESERVATIONS: Upcoming</div>
		
        <div class="report_period">
			
            <div class="printonly">
                From: <b><?php echo date("m/d/Y",strtotime($date_start)); ?></b> To:  
                <b><?php echo date("m/d/Y",strtotime($date_end)); ?></b>
			</div>
            
            <div class="noprint">            
                <form method="post">
                    Up 
                  
                    To: 
                      <input type="text" name="date_end" value="<?php echo date("m/d/Y",strtotime($date_end)); ?>" size="14" style="text-align:center;" />
                    <input type="submit" name="generate" value=" Generate " />
                </form>
             </div>
                
        </div>
	
    	<?php
        if($reservations_num==0){
			echo '<font color="red"><be><br>There are no reservations within the selected dates.</font>';
		}else{
		?>
        <br />
        <table width="100%" border="0" cellspacing="0" cellpadding="3">
             <?php
			 while($res = mysql_fetch_assoc($reservations_qry)){
			 ?>
                <tr>
                  <td colspan="4" align="left" style="border-bottom:#000 solid 2px"><strong><?php echo date("F j, Y (D)", strtotime($res['date'])); ?></strong></td>
                  <td colspan="2" align="right" style="border-bottom:#000 solid 2px"> Count: <strong><?php echo number_format($res['count'],0); ?></strong></td>
          		</tr>
                <tr>
                  <td colspan="2" align="center"><em>Customer</em></td>
                  <td width="16%" align="center"><em>Time</em></td>
                  <td width="14%" align="center"><em>Pax</em></td>
                  <td width="19%" align="center"><em>Tables</em></td>
                  <td width="25%" align="center"><em>Remarks</em></td>
         		 </tr>
				<?php
				//loop thru reservations
				if( $res['date'] == date('Y-m-d') ){
					$res_det_sql = "SELECT CONCAT(fname,' ',lname) AS customer_name, CONCAT(r.`date`,' ',r.`time`) AS date_time, number_people, number_table 
									FROM reservation r, account c 
									WHERE c.id=r.account_id AND r.`date`='".$res['date']."' AND CONCAT(r.`date`,' ',r.`time`)>='".date('Y-m-d H:i:s')."'";
				}else{
					$res_det_sql = "SELECT CONCAT(fname,' ',lname) AS customer_name, CONCAT(r.`date`,' ',r.`time`) AS date_time, number_people, number_table 
									FROM reservation r, account c 
									WHERE c.id=r.account_id AND r.`date`='".$res['date']."'";
				}
				$res_det_sql .= " ORDER BY `date`, `time` ASC";
				
				$res_det_qry = mysql_query($res_det_sql) or die(mysql_error());
				
				//debugging
				if($debug==1)
					echo $res_det_sql.'<br>';
	
				$cnt=1;
				while($res_det = mysql_fetch_assoc($res_det_qry)){
				?>
             	<tr>
                    <td width="3%" style="border-bottom:dotted 1px #666; text-align:right;"><?php echo $cnt; ?></td>
                    <td width="23%" style="border-bottom:dotted 1px #666; text-align:left;"><?php echo $res_det['customer_name']; ?></td>
                    <td align="center" style="border-bottom:dotted 1px #666;"><?php echo date('h:i a', strtotime($res_det['date_time'])); ?></td>
                    <td align="center" style="border-bottom:dotted 1px #666;"><?php echo $res_det['number_people'] ?></td>
                    <td align="center" style="border-bottom:dotted 1px #666;"><?php echo $res_det['number_table']; ?></td>
                    <td align="center" style="border-bottom:dotted 1px #666;">&nbsp;</td>
                </tr>
                <?php
				$cnt++;
			 	}//looping thru reservations
                ?>
                <tr>
                	<td colspan="6">&nbsp;</td>
                </tr>
             <?php
			 }//for each dates
			 ?>
             <tfoot>
             	<tr>
                	<td colspan="6">&nbsp;	</td>
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