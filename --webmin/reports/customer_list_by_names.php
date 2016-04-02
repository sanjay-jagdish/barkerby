<?php
session_start();
include '../config/config.php';
include '../reports/params.php';

if($_SESSION['login']['id']==0){ die('<br><br><font color="red">You need to be logged in to view this report.</font>'); }

$letter_start = 'A';

if(trim($_GET['start'])!=''){
	$letter_start = trim($_GET['start']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<link rel="stylesheet" href="../css/reports_print.css" />

<title>List of Customers By Last Name</title>
</head>

<?php
$customers_sql = "SELECT * FROM account WHERE type_id=5 AND lname LIKE '".$letter_start."%' ORDER BY lname ASC";
$customers_qry = mysql_query($customers_sql);
$customers_num = mysql_num_rows($customers_qry);
?>

<body>
	
<div class="noprint top_spacer">&nbsp;</div>
    
    <div class="contents">
    
		<div class="report_title">
       	List of Customers By Last Name</div>
		
        <div class="report_period">
			
            <div class="printonly">
                Starting with letter <div class="alpha_btns"><?php echo $letter_start; ?></div>            
            </div>
                        
            <div class="noprint" style="height:50px; vertical-align:baseline;">            
                  Starting with letter: 
                  <?php
				  foreach($alpha as $k => $letter){
				  ?>
                  	<div class="alpha_btns<?php if($letter==$letter_start){ echo '_print'; } ?>" onclick="reload_page('<?php echo $letter; ?>')"><?php echo $letter; ?></div>
				  <?php	  
				  }
				  ?>
          </div>
                
        </div>
	
    	<?php
        if($customers_num==0){
			echo '<font color="red"><be><br>There are no customers with last names starting with letter '.$letter_start.'.</font>';
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
                </tr>
             <thead>
             <tbody>
             <?php
			 $cnt=1;
			 while($res = mysql_fetch_assoc($customers_qry)){
			 ?>
             	<tr>
                    <td style="text-align:right;"><?php echo $cnt; ?></td>
                    <td><?php echo $res['lname'].', '.$res['fname'].' '.$res['mname']; ?></td>
                    <td><?php echo $res['email']; ?></td>
                    <td><?php echo $res['phone_numer']; ?></td>
                    <td><?php echo $res['mobile_number']; ?></td>
                    <td><?php echo $res['street'].'<br>'.$res['city'].'<br>'.$res['state'].' '.$res['zip']; ?></td>
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

<script type="text/javascript">
function reload_page(letter){
	var url = '?start='+letter;
	window.location.href = url;
}
</script>
</html>