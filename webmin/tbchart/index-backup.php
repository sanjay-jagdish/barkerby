<?php
require('../config/config.php');
ini_set('display_errors',0);

$time_block_left_padding = 2;// pixels
$time_interval_width = 57; // 48 pixels; width of <td>  

if(!isset($_GET['date'])){
	$date_selected = date("Y-m-d");
}else{
	$date_selected = $_GET['date'];
}

$q=mysql_query("SELECT var_value FROM settings WHERE var_name='week_starts'");
$row=mysql_fetch_assoc($q);

//get restaurant details e.g. start/end time with regards to current date
$res_det_sql = "SELECT id, start_time, end_time, time_interval FROM restaurant_detail 
				WHERE '".$date_selected."' BETWEEN STR_TO_DATE(start_date,'%m/%d/%Y') AND STR_TO_DATE(end_date,'%m/%d/%Y') 
				AND deleted=0 AND days LIKE '%".date('D',strtotime($date_selected))."%'
				ORDER BY id DESC LIMIT 1";

$res_det_qry = mysql_query($res_det_sql);
$res_det_num = mysql_num_rows($res_det_qry);
$res_det = mysql_fetch_assoc($res_det_qry);

if($res_det_num ==1){	
	$store_hrs['open']  = $res_det['start_time'];
	$store_hrs['close'] = $res_det['end_time'];
	
	$time_increments = $res_det['time_interval'];

	$max_tables_sql = "SELECT COUNT(id) AS max_tables FROM table_detail WHERE restaurant_detail_id=".$res_det['id'];
	$max_tables_qry = mysql_query($max_tables_sql);
	$max_tables_res = mysql_fetch_assoc($max_tables_qry);
	$max_table = $max_tables_res['max_tables'];

/* for calendar use --> disable days when restaurant is closed */

}

//loop thru each day of the month and check if open/close
$cal_days = range(1,date('t',strtotime($date_selected)));

$ds_cnt=0; //used to determine if comma (,) is needed
foreach($cal_days as $day){
	$date_check = date('Y',strtotime($date_selected)).'-'.date('m',strtotime($date_selected)).'-'.str_pad($day,2,0,STR_PAD_LEFT);
	$date_check_sql = "SELECT id
						FROM restaurant_detail 
						WHERE '".$date_check."' BETWEEN STR_TO_DATE(start_date,'%m/%d/%Y') AND STR_TO_DATE(end_date,'%m/%d/%Y') 
						AND deleted=0 AND days LIKE '%".date('D',strtotime($date_check))."%'
						ORDER BY id DESC LIMIT 1";
	$date_check_qry = mysql_query($date_check_sql);
	$date_check_num = mysql_num_rows($date_check_qry);
	
	if($date_check_num==0){
		if($ds_cnt>0){ $days_disabled .= ','; }
		$days_disabled .= "'".date('m/d/Y',strtotime($date_check))."'";
		$ds_cnt++;
	}
}
?>
<!DOCTYPE html><html><head><meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0">
<meta charset="utf-8">
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/bootstrap-responsive.css">
<link rel="stylesheet" href="styles.css">
<script src="libs/underscore-min.js"></script>
<script src="libs/jquery.min.js"></script>
<script src="libs/jquery-migrate-1.2.1.min.js"></script>
<script src="libs/jquery.scrollTo.js"></script>
<script src="libs/bootstrap.js"></script>
<?php
if($res_det_num==1){
?>
<script src="libs/jquery.dataTables.js"></script>
<script src="libs/dataTables.scroller.js"></script>
<script src="libs/FixedColumns.js"></script>
<script src="main.js"></script>
<?php
}
?>
<link rel="stylesheet" href="js_css/jquery-ui.css">
<script src="js_css/jquery-ui.js"></script>

<link rel="stylesheet" media="all" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
</head>

<body>
<style>

.table-container{
	width:1000px;
}

.table-reservations-list{
	float:left;
	width:300px;
	margin-left: 8px;
	padding:2px;
	border:solid thin #ccc;
}

	.table_block{ 
		position:relative; 
		background-color:#9FC;
		display:table-row;
		border:#060 solid thin;
	}
	
	td th{
		height:20px;
		width:<?php echo $time_interval_width; ?>px !important;
		padding:0px;
		margin:0px;
	}
	
	tr:hover td{ background-color:#FF9; } 
	
	.space_time{
		padding:0px !important;			
	}
	
	.first_time{
		border-right: #F30 solid 5px !important;
		width:5px;
		padding:none;
		margin:none;	
	}
	
	.last_time{
		border-left: #F30 solid 5px !important;	
	}	
	
	td div{
		padding-top: 1px;
		height: 23px !important;
		font-size: 12px;
		line-height: 12px;
		vertical-align: middle;		
	}
	
	.table_block{
		position:absolute;
		overflow:hidden;
		margin:0px;
		text-align:left;
		padding-left:<?php echo $time_block_left_padding; ?>px;
		/*z-index:100;*/	
	}
	
	label {
	    display: inline-block;
	    width: 5em;
	    font-size: 12px;
	}
	
	.odd{ background-color: #eee; }
	
	.tooltip{ width:600px; }



/* MODAL */

	* {
		margin:0; 
		padding:0;
	}

	#overlay {
		position:fixed; 
		top:0;
		left:0;
		width:100%;
		height:100%;
		background:#000;
		opacity:0.5;
		z-index:1000;
		filter:alpha(opacity=50);
	}

	#modal {
		position:absolute;
		background:url(tint20.png) 0 0 repeat;
		background:rgba(0,0,0,0.2);
		border-radius:14px;
		padding:8px;
		z-index:10000;
	}

	#content {
		border-radius:8px;
		background:#fff;
		padding:20px;
		z-index:10000;
	}

	#close {
		position:absolute;
		background:url(close.png) 0 0 no-repeat;
		width:24px;
		height:27px;
		display:block;
		text-indent:-9999px;
		top:-7px;
		right:-7px;
		z-index:10000;
	}

/* modal */

.page-content{ width:100% !important; }

</style>
<link rel="stylesheet" type="text/css" href="../css/style.css">

<div>
<script>

			var modal = (function(){
				var 
				method = {},
				$overlay,
				$modal,
				$content,
				$close;

				// Center the modal in the viewport
				method.center = function () {
					var top, left;

					top = Math.max($(window).height() - $modal.outerHeight(), 0) / 2;
					left = Math.max($(window).width() - $modal.outerWidth(), 0) / 2;

					$modal.css({
						top:top + $(window).scrollTop(), 
						left:left + $(window).scrollLeft()
					});
				};

				// Open the modal
				method.open = function (settings) {
					$content.empty().append(settings.content);

					$modal.css({
						width: settings.width || 'auto', 
						height: settings.height || 'auto'
					});

					method.center();
					$(window).bind('resize.modal', method.center);
					$modal.show();
					$overlay.show();
				};

				// Close the modal
				method.close = function () {
					$modal.hide();
					$overlay.hide();
					$content.empty();
					$(window).unbind('resize.modal');
				};

				// Generate the HTML and add it to the document
				$overlay = $('<div id="overlay"></div>');
				$modal = $('<div id="modal"></div>');
				$content = $('<div id="content"></div>');
				$close = $('<a id="close" href="#">close</a>');

				$modal.hide();
				$overlay.hide();
				$modal.append($content, $close);

				$(document).ready(function(){
					$('body').append($overlay, $modal);						
				});

				$close.click(function(e){
					e.preventDefault();
					method.close();
				});

				return method;
			}());

			// Wait until the DOM has loaded before querying the document
			$(document).ready(function(){

				$('td.timeslot').click(function(e){
					
					e.preventDefault();
					
					var parameters = jQuery(this).attr('data-rel');
					
					jQuery.ajax({
							 url: "book_table.php",
							 type: 'POST',
							 data: 'parameters='+parameters,
							 success: function(value){
								
								modal.open({content: value});

							 }
					});			
					
					
				});

			});
		


$(function(){

	jQuery(function(){
	
		/*
		jQuery( ".timeslot" ).click(function(e){
			e.preventDefault();
			e.stopPropagation();
			
			var timeslot = jQuery(this).attr('data-rel');
			alert(timeslot);
		
		});
		*/
		  //calendar.setTimeZone('<?php echo $garcon_settings['timezone']; ?>');
	
		  var array = [<?php echo $days_disabled; ?>]; //'02/22/2014','02/23/2014','02/28/2014'
		
		  jQuery( "#chartdate" ).datepicker({

			  minDate: 0, 
			  firstDay: '<?php echo $row['var_value']; ?>',
				<?php
				if($_GET['date']!=''){
				$sel_date = explode('-',$_GET['date']);
				?>
				defaultDate: new Date(<?php echo $sel_date[0].','.(int)($sel_date[1]-1).','.$sel_date[2]; ?>),
				<?php	  
				}
				?>	
			  dateFormat: 'yy-mm-dd',
			  onSelect: function(selectedDate){
	  					
			  window.location='?date='+selectedDate;
			  
			  /*
				jQuery.ajax({
						 url: "operation_details.php",
						 type: 'POST',
						 data: 'date='+selectedDate,
						 success: function(value){
							jQuery('#operation_details').html(value);	
							
							jQuery.ajax({
									 url: "drawn_chart2.php",
									 type: 'POST',
									 data: 'date='+selectedDate,
									 success: function(value){
									 	jQuery('#table_chart').html(value);
									 }
							});
						 }
				});			
			  */	
								
			  },
				beforeShowDay: function(date){
				var string = $.datepicker.formatDate('mm/dd/yy', date);
				return [ array.indexOf(string) == -1 ];
		  	  }
			
			});

	   
		<?php
		if($res_det_num==1){
		?>
		   //$('#tbl6').bubbletip($('#tbl6_up'));
		<?php
		}
		?>   
	});


});
  
</script>

<?php
if($res_det_num==1){

/*
<script src="js/jQuery.bubbletip-1.0.6.js" type="text/javascript"></script>
<link href="js/bubbletip/bubbletip.css" rel="stylesheet" type="text/css" />
*/

?>

<?php
}
?>

<div class="wrapper">
	<div class="header-wrapper">
    	<div class="header">
        	<div class="thelogo"><a href="?page=dashboard"></a></div>
            <!-- end .thelogo -->
            <div class="header-nav">
            	<!--<ul>
                    <li class="nav-notifications"><a href="?page=notifications"></a></li>
                    <li class="nav-users"><a href="?page=users"></a></li>
                    <li class="nav-statistics"><a href="?page=statistics"></a></li>
                </ul>-->
            </div>

        <!--
        	<span> </span>
            <h2>
            	<?php
                	if(isset($_GET['page'])){
						if(isset($_GET['subpage'])){
							echo ucwords(removeDashTitle($_GET['subpage']));
						}
						else{
							echo ucwords(removeDashTitle($_GET['page']));
						}
					}
				?>
            </h2>
        -->    
        </div>
        <div class="page-header-right">
        	<!--<input type="text" placeholder="To search type and hit enter..." class="searchbox">-->
        </div>
    </div>
    
     <div class="clear"></div>
    
    <div class="page-content" style="background-color:#FFF;">

<div id="operation_details" style="float:left; width:210px; padding:20px 8px 8px 20px;">
	<strong><u>Operation Details</u></strong><br><br>
	Date Selected: <b><?php echo date('D m/d/Y',strtotime($date_selected)); ?></b><br>
	Open Time: <b><?php echo $res_det['start_time']; ?></b><br>
	Close Time: <b><?php echo $res_det['end_time']; ?></b><br>
	Interval: <b><?php echo $res_det['time_interval']; ?> mins.</b><br>
	Max Tables: <strong><?php echo $max_table; ?></strong>
</div>

<div id="chartdate" style="float:left;"></div>


<div class="container avails-grid" id="table_chart">

	<?php
	if($res_det_num==1){
	?>

	<p class="loading alert" style="z-index:100000; margin-top:200px;">One moment please... Loading Table Chart...</p>

    <div class="table-container" style="float:left; clear:left;">
	
    	<table cellpadding="0" cellspacing="0" border="0" class="table table-condensed avails-table table-bordered" style="clear:none !important;">

        	<thead>
            	<tr>
                	<th>&nbsp;</th>
                	<th>&nbsp;</th>
				<?php 
				$time = strtotime($store_hrs['open']);
				$end = strtotime($store_hrs['close']);
				
				while($time<=$end){
					?>
                    <th style="width:70px;"><?php echo date('H:i',$time); ?></th>
                    <?php
					$time = strtotime(date('H:i:s',$time).' + '.$time_increments.' mins');
				}
				?>	
                	<th>&nbsp;</th>
        		</tr>
            </thead>
            <tbody>
            	<?php
				$length = strlen($max_table);
				
				$tables_sql = "SELECT id, table_name, max_pax FROM table_detail	WHERE restaurant_detail_id=".$res_det['id']." ORDER BY id ASC";
				$tables_qry = mysql_query($tables_sql);
				while($tables = mysql_fetch_assoc($tables_qry)){
				?>
                <tr>
                	<td align="center"><?php echo $tables['table_name']; ?></td>
                	<td class="first_time">&nbsp;</td>
				<?php
				$time = strtotime($store_hrs['open']);
				$end = strtotime($store_hrs['close']);
				
				$time_count=1;
				while($time<=$end){
					
/*
SELECT r.date, r.time, DATE_FORMAT(DATE_ADD(TIME(r.time), INTERVAL 60 MINUTE),'%H:%i') AS end, r.duration, CONCAT(a.fname, ' ', a.lname) AS name, 
							t.table_name, rt.table_detail_id, r.number_people 
							FROM reservation r, account a, reservation_table rt, table_detail t 
							WHERE r.id=rt.reservation_id AND r.date='".date('m/d/Y',strtotime($date_selected))."' AND a.id=r.account_id AND r.approve=2
							AND t.restaurant_detail_id=".$res_det['id']." AND t.id=rt.table_detail_id
							ORDER BY time, end, fname, lname ASC 
							*/
					//check if table has reservation for the specified time
					$table_reserve_sql = "SELECT r.time, r.duration, CONCAT(a.fname, ' ', a.lname) AS name, r.number_people 
										  FROM reservation_table rt, reservation r, table_detail t, account a
										  WHERE rt.table_detail_id=".$tables['id']." AND rt.reservation_id=r.id  
										  AND t.restaurant_detail_id=".$res_det['id']." AND t.id=rt.table_detail_id AND a.id=r.account_id AND
										  r.date='".date('m/d/Y',strtotime($date_selected))."' AND r.time='".date('H:i:00',$time)."' AND r.approve=2";
					
					$table_reserve_qry = mysql_query($table_reserve_sql);
					$table_reserve_num = mysql_num_rows($table_reserve_qry);
									
					$table_block = '';
					$time_slot_class = '';
					
					if($table_reserve_num==1){
						
						$table_reserve = mysql_fetch_assoc($table_reserve_qry);
						
						if($table_reserve['duration']!=''){
							$duration = $table_reserve['duration'];
						}else{
							$duration = $res_det['time_interval'];	
						}
						
						$block_ends = strtotime($table_reserve['time'].' + '.$duration.' mins');
						$time_blocks_width = $time_interval_width + ($duration/$time_increments) * $time_interval_width - $time_block_left_padding;
						
						//add number of division
						$division = $duration / $time_interval_width;
						$time_blocks_width += round($division);
						
						$table_block = '<div class="table_block" style="width:'.$time_blocks_width.'px;" 
											title="Details of Table Resrvation Here" id="tbl6">
											'.$table_reserve['number_people'].' '.$table_reserve['name'].' '.date('H:i',$time).'&rarr;'.date('H:i',$block_ends).' ['.$duration.'\']
										</div>'; //'.date('H:i',$time).'&rarr;'.date('H:i',$block_ends).' ['.$duration.'mins]
					}else{
						$time_slot_class = 'timeslot';	
					}

					?>
                    <td class="space_time <?php echo $time_slot_class; ?>" data-rel="<?php echo $date_selected.' '.date('H:i',$time).':00|'.$tables['id'].'|'.$res_det['id']; ?>" title="<?php echo $tables['table_name'].' '.date('H:i',$time); ?>"><?php echo $table_block; ?></td>
                    <?php
					$time = strtotime(date('H:i:s',$time).' + '.$time_increments.' mins');
					$time_count++;
				}
				?>	
                	<td class="last_time">&nbsp;</td>
                </tr>	
                <?php	
				}
				?>
            </tbody>
        </table>	    

    </div>



	<div class="container avails-grid" id="table_chart">
    <div class="table-reservations-list table-container show-table" style="position:relative; width:200px;">
   		<table class="table table-condensed avails-table table-bordered">
        	<thead>
            	<tr>
                	<th>From</th>
                	<th>To</th>
                	<th>Firstname</th>
                	<th>Lastname</th>
                	<th>Table&nbsp;No.</th>
                	<th>Pax</th>
                </tr>
            </thead>

<?php
$table_reservations_sql = "SELECT r.date, r.time, DATE_FORMAT(DATE_ADD(TIME(r.time), INTERVAL 60 MINUTE),'%H:%i') AS end, r.duration, a.fname, a.lname, 
							t.table_name, rt.table_detail_id, r.number_people 
							FROM reservation r, account a, reservation_table rt, table_detail t 
							WHERE r.id=rt.reservation_id AND r.date='".date('m/d/Y',strtotime($date_selected))."' AND a.id=r.account_id AND r.approve=2
							AND t.restaurant_detail_id=".$res_det['id']." AND t.id=rt.table_detail_id
							ORDER BY time, end, fname, lname ASC ";

$table_reservations_qry = mysql_query($table_reservations_sql);
$table_reservations_num = mysql_num_rows($table_reservations_qry);
if($table_reservations_num>0){
	echo '<tbody>';
	while($table_reservations = mysql_fetch_assoc($table_reservations_qry)){
?>
	<tr>
    	<td><?php echo substr($table_reservations['time'],0,5); ?></td>
        <td><?php echo date('H:i', strtotime($table_reservations['time'].' + '.$table_reservations['duration'].' minutes')); ?></td>
        <td><?php echo $table_reservations['fname']; ?></td>
   		<td><?php echo $table_reservations['lname']; ?></td>
        <td><?php echo $table_reservations['table_name']; ?></td>
        <td><?php echo $table_reservations['number_people']; ?></td>
    </tr>
<?php
	}
	echo '</tbody>';
}
?>

        	<tfoot>
            	<tr>
                	<th>From</th>
                	<th>To</th>
                	<th>Firstname</th>
                	<th>Lastname</th>
                	<th>Table&nbsp;No.</th>
                	<th>Pax</th>
                </tr>
            </tfoot>            
            
        </table>	
    </div>
    </div>
       
	<?php
	}else{
		echo '<br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="Red">There is no open/close times available for the selected date <b>'.date('d M Y',strtotime($date_selected)).'</b>.</font>';
	}
	?>

</div>
<div id="overlay" style="display: none;"></div><div id="modal" style="width: auto; height: auto; top: 300.5px; left: 537px; display: none;"><div id="content"></div><a id="close" href="#">close</a></div>

</div>

</body>
</html>