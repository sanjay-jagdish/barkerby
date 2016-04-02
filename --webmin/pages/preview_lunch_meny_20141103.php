<?php
if($_GET['pdf']==1){
	ob_start();	
	echo '<page style="font-size: 14px; box-sizing:border-box;" backtop="0mm" backbottom="0mm" backleft="0mm" backright="0mm">';
}else{
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Mise en Place</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>    
<?php    
}

require '../config/config.php';

$year_num = $_GET['year'];
$week_num = $_GET['week'];

$currency_shortname = '';
$currency_sql = "SELECT shortname FROM currency WHERE set_default=1";
$currency_qry = mysql_query($currency_sql);
$currency_res = mysql_fetch_assoc($currency_qry);

//check if there is an existing lunch menu for the week
$menu_week_sql = "SELECT * FROM menu_lunch WHERE year_for=".$year_num." AND week_no=".$week_num;
$menu_week_qry = mysql_query($menu_week_sql);
$menu_week_num = mysql_num_rows($menu_week_qry);
$menu_week_check_num = $menu_week_num;

if($menu_week_num==0){
	$menu_week_sql = "SELECT * FROM menu_lunch WHERE year_for<=".$year_num." AND week_no<=".$week_num." ORDER BY year_for DESC, week_no DESC LIMIT 1";
	$menu_week_qry = mysql_query($menu_week_sql);
	$menu_week_num = mysql_num_rows($menu_week_qry);	
}

?>

<style>
body{
	background-color: #333;
	<?php if($_GET['pdf']==0){ echo 'color: #FFF;'; }else{ echo 'color: #000;'; } ?>
	font-family: ABeeZee-Regular;
	font-size: 1.6rem;
	font-weight: 300;
	margin: 0px 24px;
}

span.left_background {
    padding: 4px 0px 0px 105px;
    background: url('../images/widget_title_left.png') no-repeat scroll left center transparent;
	font-size:32px !important;
}

span.right_background {
    padding: 4px 105px 0px 0px;
    background: url('../images/widget_title_right.png') no-repeat scroll right center transparent;
}

.menu_main_description, .course_desc,  .course_desc_existing, .additional_course_desc{ width: 795px; height: 54px; }
.warning{ box-shadow: 0 0 10px #F00 !important; outline: none !important; background-color:#FFC !important; }
.additional_course_price, .unit_price_existing{ text-align: right !important; }

#accordion, #accordion textarea { 
	font-size: 12px; 
	font-family: Arial, Helvetica, sans-serif;
}

.ui-accordion .ui-accordion-content{
	height:auto !important;			
}

.link_button{ 
	font-size:16px;
	clear:both;	
	padding:12px; 
	margin:8px 4px; 
	background-color:#CCC;
	border-radius: 5px;
}

.link_button:hover{
	background-color:#999;
	color: #fff;
	cursor:pointer;
}

h4{
	text-transform:uppercase;
	text-align:center; 
}

h5{
	font-size:18px;
	font-weight:normal;
	text-decoration:underline;
}

p{ font-size:16px; }

.footer{
	border-top:dotted 2px #CCCCCC;
	font-style:italic;
	font-size:16px;
	color:#CCC;
	text-align:center;
	padding-top:12px;
	padding-bottom:24px;
}

@media screen{
	td{ font-size:18px; }
}
</style>

<?php	
$menu_week_res = mysql_fetch_assoc($menu_week_qry);

if($_GET['pdf']==0){
?>
<div style="top:0; right:24px; position:absolute; width: 200px; text-align:right; line-height:34px;">
    <a class="link_button" id="pdf_export" data-rel="" title="Export as PDF">Export as PDF</a>
</div>
<?php
}
?>

<div style="text-align:center;">

    <h4 class="widget-title widgettitle"><span class="left_background"><span class="right_background">VECKANS Lunch</span></span></h4>
	
    <span>Vecka <?php echo $week_num; ?></span>    

	<h5>
      <strong><?php echo stripslashes($menu_week_res['note_header']); ?></strong>
    </h5>

	<p><?php echo stripslashes($menu_week_res['description']); ?></p>

</div>

<div style="text-align:right; padding:8px 0px;">
 
 <input type="hidden" id="menu_parameter" value="<?php echo 'W '. $year_num.' '.$week_num; ?>" /> 


    
</div>

                    
	
    <?php
    $courses_sql = "SELECT * FROM menu_lunch_items WHERE 
	
					( 
						(year_for=".$year_num." AND week_for=".$week_num." )
						OR
						all_in=1	
						
					)
					AND specific_day IS NULL 
					AND deleted=0
					ORDER BY `order`, id ASC";
    $courses_qry = mysql_query($courses_sql);
    $courses_num = mysql_num_rows($courses_qry);
	?>

	<table style="width:580px" align="center" border="0">
    	<tr>
        	<td>
            Mån-Fre 
            (<?php 
			echo date('M d',strtotime('monday this week'));
			if($menu_week_res['all_in']==0){
				echo ' to  '.date('M d',strtotime('friday this week')); 
			}else{
				echo ' onwards';
			}
			?>)
			</td>
            <td>
				<div style="float:right; text-align:right;"><?php echo ($courses_num+0).'&nbsp;course'; if($courses_num>1 || $courses_num==0){ echo 's'; }; ?></div>            
            </td>
        </tr>
    	<tr>
        	<td colspan="2" style="padding-left:40px;" align="center">
				<br>
                <table cellspacing="0" border="0" style="">
	<?php
	
	if($courses_num>0){

		while($courses_res = mysql_fetch_assoc($courses_qry)){
		?>
		
        <tr>
        	<td style="text-align:left; width:200px; border-top: #777 solid thin; padding: 2px 12px 24px 12px; white-space:normal !important;">			
    			<?php echo stripslashes($courses_res['description']); ?>
            </td>
            <td style="width:5px; border-top: #777 solid thin; padding: 2px 12px 24px 12px;">&nbsp;</td>
            <td align="right" valign="top" nowrap style="width:50px; border-top: #777 solid thin; padding: 2px 12px 24px 12px;">
			<?php echo $courses_res['price'].' '.$currency_res['shortname']; ?>
			</td>
		</tr>	
		<?php
		}//looping thru courses for this menu
	

	}
	
	?>
    			</table>
            </td>
        </tr>
	        
    <?php
	
	
	$first_day = strtotime('monday this week');
	$last_day = strtotime('saturday this week');
	$the_day = $first_day;
	
	$week_days = 6;
	
	for($d=1; $d<=$week_days; $d++){

		$courses_sql = "SELECT * FROM menu_lunch_items WHERE 
		
						( 
							(year_for=".$year_num." AND week_for=".$week_num." )
							OR
							all_in=1	
							
						)
						AND specific_day='".date('D', $the_day)."' 
						AND deleted=0
						ORDER BY `order`, id ASC";

		$courses_qry = mysql_query($courses_sql);
		$num_courses = mysql_num_rows($courses_qry);

	?>

    	<tr>
        	<td>
				<?php 	
                if($menu_week_res['all_in']==0){
                    echo dayName(date('D', $the_day)).' ('.date('M d', $the_day).')';
                }else{
                    echo dayName(date('D', $the_day)).' (All '.date('l', $the_day).'s starting from '.date('M d', $the_day).')';
                }
                ?>
			</td>
            <td>
				<div style="float:right; text-align:right;"><?php echo ($num_courses+0).'&nbsp;course'; if($num_courses>1 || $num_courses==0){ echo 's'; }; ?></div>            
            </td>
        </tr>
    	<tr>
        	<td colspan="2" style="padding-left:40px;">
				<br>
    
  	<?php

	
	if($num_courses>0){
	?>
    <table width="100%" cellspacing="0" border="0">
    <?php
	
		while($courses_res = mysql_fetch_assoc($courses_qry)){
	?>	

        <tr>
        	<td width="60%" style="border-top: #777 solid thin; padding: 2px 12px 24px 12px;">			
    			<?php echo stripslashes($courses_res['description']); ?>
            </td>
            <td width="5%" style="border-top: #777 solid thin; padding: 2px 12px 24px 12px;"><div style="width:100px;">&nbsp;</div></td>
            <td width="35%" align="right" valign="top" nowrap style="border-top: #777 solid thin; padding: 2px 12px 24px 12px;">
			<?php echo $courses_res['price'].' '.$currency_res['shortname']; ?>
			</td>
		</tr>	

	<?php	
		}
	?>
    </table>
    <?php
	}
	?>      
 
        	</td>    
		</tr>
    
       
	<?php
		$the_day = strtotime(date('Y-m-d', $the_day).' +1 day');
	}	
	?>
    </table>

	<div class="footer"><?php echo stripslashes($menu_week_res['note_footer']); ?></div>


<?php
if($_GET['pdf']==1){
	echo '</page>';
    
	$content = ob_get_clean();
    // convert in PDF
    //require_once(dirname(__FILE__).'/../html2pdf.class.php');
    require_once('../html2pdf/html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'Letter', 'en');
//      $html2pdf->setModeDebug();
        $html2pdf->setDefaultFont('times');
        $html2pdf->writeHTML($content);
        $html2pdf->Output('Lunch Meny.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
	
}else{
?>
</body>
</html>
<?php	
}
?>