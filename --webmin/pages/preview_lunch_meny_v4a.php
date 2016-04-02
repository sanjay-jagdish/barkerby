<?php
ini_set('display_errors',1);
if($_GET['pdf']==1){
	ob_start();	
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

$none_all_in = 0;

if($menu_week_num==0){
	$menu_week_sql = "SELECT * FROM menu_lunch WHERE year_for<=".$year_num." AND week_no<=".$week_num." ORDER BY year_for DESC, week_no DESC LIMIT 1";
	$menu_week_qry = mysql_query($menu_week_sql);
	$menu_week_num = mysql_num_rows($menu_week_qry);	
	
	//flag 1=true to exclude courses that are not marked as "all-in"
	$none_all_in = 1;
}

?>

<style>
@import url(http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900);
html{ font-size: 62.5%; }

body{
	background-color: #fff;
	color: #000;
	font-family: sans-serif;
	font-size: 1.5rem;
	font-weight: 300;
	margin: 0px 24px;
}

h1, h2, h3, h4, h5, h6 {
    color: #580605;
    font-family: Lato,sans-serif;
    font-weight: 700;
    line-height: 1.2;
}

.widgettitle{
	color: #580605;
	font-size: 28px;
	text-align: left;
	font-weight: 800;
	text-transform:uppercase;
	padding: 50px 0px 0px;	
	text-align:center;
}

span.left_background {
    padding: 12px 0px 13px 122px;
    background: url('http://<?php echo $_SERVER['HTTP_HOST']; ?>/webmin/images/widget_title_left.png') no-repeat scroll left center<?php if($_GET['pdf']==0){ echo ' transparent'; }?>;
	font-size:32px !important;
}

span.right_background {
    padding: 12px 122px 12px 0px;
    background: url('http://<?php echo $_SERVER['HTTP_HOST']; ?>/webmin/images/widget_title_right.png') no-repeat scroll right center<?php if($_GET['pdf']==0){ echo ' transparent';} ?>;
}


.themenu-outer {
    text-align: left;
	margin:0 auto;
	width: 750px;
}

/* p{ font-size:16px; } */

.weeknum {
    color: #580605 !important;
    font-size: 25px !important;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 10px !important;
    display: block;
	text-align:center !important;
}


.footer{
    text-align: left;
    margin: 50px 0px;
    color: #000;
	font-size:16px;
}

.thesepa {
	clear:both;
    width: 282px;
    height: 36px;
    margin: 30px auto;
	position:relative;
	border:solid thin red;
}

.themenu-inner{
	width:748px;
	margin: 0 auto;
}



.name_title, .name_title p, h3 p{
	color:#580605;font-family:lato,sans-serif;font-size:2rem;font-style:normal;font-weight:700;
	text-align:left;
	margin-bottom:12px;
}


@media print{
	
	h1, h2, h3, h4, h5, h6 {
		color: rgb(88, 6, 5); font-family: lato, sans-serif; font-size: 2rem; font-style: normal;
	}
		
}


@page { 
	size: 8.267in x 11.692in; 
	margin: 2%;
}

.h3{ color: rgb(88, 6, 5); font-family: lato, sans-serif; font-size: 20px; font-style: normal; font-weight: 700; text-align: center; }
</style>

<?php	
$menu_week_res = mysql_fetch_assoc($menu_week_qry);

if($_GET['pdf']==0){
?>
<!--
<div style="top:0; right:24px; position:absolute; width: 200px; text-align:right; line-height:34px;">
    <a class="link_button" id="pdf_export" data-rel="" title="Export as PDF">Export as PDF</a>
</div>
-->
<?php
}





	
	
	$year_num = date('Y');
	$week_num = date('W');
	
	$currency_shortname = '';
	$currency_sql = "SELECT shortname FROM currency WHERE set_default=1";
	$currency_qry = mysql_query($currency_sql) or die(mysql_error().'abc');
	$currency_res = mysql_fetch_assoc($currency_qry);
	
	//check if there is an existing lunch menu for the week
	$menu_week_sql = "SELECT * FROM menu_lunch WHERE year_for=".$year_num." AND week_no=".$week_num;
	$menu_week_qry = mysql_query($menu_week_sql);
	$menu_week_num = mysql_num_rows($menu_week_qry);
	$menu_week_check_num = $menu_week_num;
	
	$none_all_in = 0;
	
	if($menu_week_num==0){

		//check if there is an existing weekly menu for the year
		$menu_week_sql = "SELECT * FROM menu_lunch WHERE year_for=".$year_num;
		$menu_week_qry = mysql_query($menu_week_sql);
		$menu_week_num = mysql_num_rows($menu_week_qry);
		
		if($menu_week_num>0){
			$menu_week_sql = "SELECT * FROM menu_lunch WHERE year_for<=".$year_num." AND week_no<=".$week_num." ORDER BY year_for DESC, week_no DESC LIMIT 1";
			$menu_week_qry = mysql_query($menu_week_sql);
			$menu_week_num = mysql_num_rows($menu_week_qry);	
		}else{
			//get the most recent weekly menu
			$menu_week_sql = "SELECT * FROM menu_lunch WHERE year_for<=".$year_num." ORDER BY year_for DESC, week_no DESC LIMIT 1";
			$menu_week_qry = mysql_query($menu_week_sql);
			$menu_week_num = mysql_num_rows($menu_week_qry);				
		}
		
		$none_all_in=1;
		
	}
	
	$menu_week_res = mysql_fetch_assoc($menu_week_qry);

?>
    <div id="text-lunchmeny">
    
        <h4 class="widget-title widgettitle" style="text-align:center;">
            <img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/webmin/images/widget_title_left.png" style="vertical-align:middle;">        
            &nbsp; Veckans Lunch &nbsp;
            <img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/webmin/images/widget_title_right.png" style="vertical-align:middle;">
        </h4>
            
        <div class="weeknum">Vecka <?php echo $week_num; ?></div>    
    
        <h5>
          <strong><?php echo stripslashes($menu_week_res['note_header']); ?></strong>
        </h5>
    
        <p><?php echo stripslashes($menu_week_res['description']); ?></p>
    
    </div>
    
    <div style="text-align:right; padding:0px;">
     
     <input type="hidden" id="menu_parameter" value="<?php echo 'W '. $year_num.' '.$week_num; ?>" /> 
    
    
        
    </div>
    
                        
        
        <?php
        if($none_all_in==0){
			$all_in_switch = '';
		}else{
			$all_in_switch = ' AND all_in=1 ';	
		}
	
		$courses_sql = "SELECT * FROM menu_lunch_items WHERE 
						menu_id=".$menu_week_res['id']."
						".$all_in_switch."
						AND specific_day IS NULL 
						AND deleted=0
						ORDER BY `order`, id ASC";
		$courses_qry = mysql_query($courses_sql);// or die($courses_sql);
		$courses_num = mysql_num_rows($courses_qry);	

        ?>
        
        <div class="themenu-outer">
        	<?php if($courses_num>0){?>
            
            	<div class="thesepa"><img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/webmin/images/separator.png"></div>
            
                <div class="themenu-container">
                    <!--<h2>Mån-Fre 
                    (<?php 
                    /*echo date('M d',strtotime('monday this week'));
                    if($menu_week_res['all_in']==0){
                        echo ' to  '.date('M d',strtotime('friday this week')); 
                    }else{
                        echo ' onwards';
                    }*/
                    ?>)</h2>-->
                    
                    <?php
					if($courses_num>0){
						while($courses_res = mysql_fetch_assoc($courses_qry)){
							
							$counts++;
							$menu_price = ''; //clear-up course price text
							$menu_details = '';
							$menu_description = '';
							$replacement = '';
							
							$lunchprice = $courses_res['takeaway_price'];
					?>
                        <div class="themenu-inner">
                            <div class="h3"><?php echo $courses_res['name']; ?></div>

							<?php 
								//get menu price
								if($courses_res['price']!=0){
									$menu_price = number_format($courses_res['price']).' '.$currency_res['shortname'];						        }
							
								echo '<div class="testp'.$counts.'"><p>' .stripslashes($courses_res['description']).'</p></div>';
							?>
							<style type="text/css">
                                .testp<?php echo $counts; ?> p:nth-last-child(2):after{
                                    content: '<?php echo ' '.$menu_price; ?>';
                                }
                            </style>
                             <?php ?> 

                            <span style="display:none" class="lmsubtotal-<?php echo $courses_res['id'];?>" data-rel="<?php echo $lunchprice; ?>"></span>
                           
                        </div>
                    <?php
						}
					}
					?>
                 </div>
                 <div class="thesepa"><img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/webmin/images/separator.png"></div>
             <?php }else{
						echo '<div class="thesepa"><img src="http://'.$_SERVER['HTTP_HOST'].'/webmin/images/separator.png"></div>';
				   } 
			 
			 	$first_day = strtotime('monday this week');
				$last_day = strtotime('saturday this week');
				$the_day = $first_day;
				
				$week_days = 6;
				
				$lunchprice=0;
				
				for($d=1; $d<=$week_days; $d++){
			
					$courses_sql = "SELECT * FROM menu_lunch_items WHERE 
									menu_id=".$menu_week_res['id']."
									".$all_in_switch."
									AND specific_day='".date('D', $the_day)."' 
									AND deleted=0
									ORDER BY `order`, id ASC";
			
					$courses_qry = mysql_query($courses_sql);
					$num_courses = mysql_num_rows($courses_qry);
			
					if($num_courses>0){
						
						
			?>

            		<div class="themenu-container">
                  
                    <?php
					if($num_courses>0){

						
						while($courses_res = mysql_fetch_assoc($courses_qry)){
							
							$lunchprice = $courses_res['takeaway_price'];
					?>
                        <div class="themenu-inner">
                            <div class="h3"><?php echo $courses_res['name']; ?></div>
								<?php echo stripslashes($courses_res['description']); ?>
                                <?php if($courses_res['price']>0 & $courses_res['price']!=''){?>
                            	<span class="themi-price"><?php echo number_format($courses_res['price']).' '.$currency_res['shortname']; ?></span>
                                <?php } ?>
                            
                            <span style="display:none" class="lmsubtotal-<?php echo $courses_res['id'];?>" data-rel="<?php echo $lunchprice; ?>"></span>
                                                            
                        </div>
                    <?php
						}
					}
					?>
                 </div>

                 <div class="thesepa"><img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/webmin/images/separator.png"></div>

            <?php			
					}
				
			 ?>

             
              <?php
					$the_day = strtotime(date('Y-m-d', $the_day).' +1 day');
				}	
        	 ?>
             
      </div>
     
     <div class="footerlm"><?php echo stripslashes($menu_week_res['note_footer']); ?></div>
        	
	






<?php
if($_GET['pdf']==1){
 
	$content = ob_get_clean();
	//==============================================================
	//==============================================================
	//==============================================================
	include("../mpdf60/mpdf.php");
	
	$mpdf=new mPDF(); 
	
	$mpdf->SetDisplayMode('fullpage');
	
	$mpdf->WriteHTML($content);
	
	$mpdf->Output(); 
	
	exit;
	
	//==============================================================
	//==============================================================

}else{
?>
</body>
</html>
<?php	
}
?>