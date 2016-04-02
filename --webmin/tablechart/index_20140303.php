<?php
//require('../config/config.php');
ini_set('display_errors',0);

$time_block_left_padding = 2;// pixels
$time_interval_width = 24; // 48 pixels; width of <td>  

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
	
	$time_increments = $garcon_settings['time_interval'];

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
<link rel="stylesheet" href="tablechart/css/bootstrap.css">
<link rel="stylesheet" href="tablechart/css/bootstrap-responsive.css">
<link rel="stylesheet" href="tablechart/styles.css">
<script src="tablechart/libs/underscore-min.js"></script>
<script src="tablechart/libs/jquery.min.js"></script>
<script src="tablechart/libs/jquery-migrate-1.2.1.min.js"></script>
<script src="tablechart/libs/jquery.scrollTo.js"></script>
<script src="tablechart/libs/bootstrap.js"></script>
<?php
if($res_det_num==1){
?>
<script src="tablechart/libs/jquery.dataTables.js"></script>
<script src="tablechart/libs/dataTables.scroller.js"></script>
<script src="tablechart/libs/FixedColumns.js"></script>
<script src="tablechart/main.js"></script>
<?php
}
?>
<link rel="stylesheet" href="tablechart/js_css/jquery-ui.css">
<script src="tablechart/js_css/jquery-ui.js"></script>

<link rel="stylesheet" media="all" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
</head>

<body>
<style>

.table-container{
	width:1135px;
}

.table-reservations-list{
	float:left;
	width:300px;
	margin-left: 8px;
	padding:2px;
	border:solid thin #ccc;
}

	.reserved{ background-color:#9FC !important; } /*  */
	.in_between{ background-color:#999 !important; } /*  */

	.table_block{ 
		position:relative; 
		background-color:#9FC;
		display:table-row;
		/*border:#060 solid thin;*/
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
		padding-top: 2px;
		/*height: 23px !important; */
		font-size: 12px;
		line-height: 12px;
		vertical-align: middle;		
	}
	
	.table_block{
		height: 24px;
		padding-top:2px;
		position:absolute;
		overflow:hidden;
		margin:0px;
		text-align:left;
		padding-left:<?php echo $time_block_left_padding; ?>px;
		/*z-index:100;*/	
	}
	
	.dishout{
		width: 124px;
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

.page-content{ /*width:100% !important;*/ }

#QBook{
	padding:8px;
	border:#333 solid thin;
	background-color:#333;
	color:#F90;
}

#QBook:hover{
	text-decoration:none;
	color: #fff;
}

.hour_th{ font-weight: bold; color:#fff; background-color:#333; }
.min_th{ font-weight: normal !important; color:#333; background-color:#fff; }

th:hover{ background:none !important; }
.hour_th:hover{ background-color:#333 !important; color:#FC0 !important;} 
.min_th:hover{ background-color:#666 !important; color:#fff !important; } 

</style>
<link rel="stylesheet" type="text/css" href="css/style.css">

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
							 url: "tablechart/book_table.php",
							 type: 'POST',
							 data: 'parameters='+parameters,
							 success: function(value){
								
								modal.open({content: value});

							 }
					});			
					
				});


				$('#QBook').click(function(e){
					
					e.preventDefault();
					var parameters = jQuery(this).attr('data-rel');
															
					jQuery.ajax({
							 url: "tablechart/quick_book_table.php",
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
	  					
			  window.location='?page=dashboard&date='+selectedDate;
			
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
	
     <div class="clear"></div>
    
    <div class="page-content" style="background-color:#FFF;">

<div id="operation_details" style="float:left; width:210px; padding:20px 8px 8px 20px;">
	<strong><u>Operation Details</u></strong><br>
	Date Selected: <b><?php echo date('D m/d/Y',strtotime($date_selected)); ?></b><br>
	Open Time: <b><?php echo $res_det['start_time']; ?></b><br>
	Close Time: <b><?php echo $res_det['end_time']; ?></b><br>
	Max Tables: <strong><?php echo $max_table; ?></strong><br>
    
	Booking Interval: <b><?php echo $garcon_settings['time_interval']; ?> mins.</b>
	<br>
	Dine Interval: <b><?php echo $garcon_settings['dine_interval']; ?> mins.</b>
	<br>
	In-Between: <b><?php echo $garcon_settings['between_interval']; ?> mins.</b> <br>
<?php
	if($res_det_num==1){
	?>
    <br>
	<a id="QBook" data-rel="<?php echo $date_selected.' '.$res_det['id']; ?>">QUICK BOOK</a>
	<br>
	<?php
	}
	?>    
    
</div>

<div id="chartdate" style="float:left;"></div>




<div class="container avails-grid" id="table_chart">

	<?php
	if($res_det_num==1){
	?>

	<p class="loading alert" style="z-index:100000; margin-top:200px;">One moment please... Loading Table Chart...</p>
	
    <div style="clear:both; height:10px;">&nbsp;</div>

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
					if(date('i',$time)=='00'){
						$time_txt = '<b>'.date('H',$time).'</b>';
						$class_th = 'hour_th';
					}else{
						$time_txt = '<font color="#ccc">'.date('i',$time).'</font>'; 
						$class_th = 'min_th';
					}
					?>
                    <th style="width:70px;">
					<?php 
					echo $time_txt;
					?>
                    </th>
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
                	<td valign="middle" nowrap="nowrap">
                      <div style="width:100px;">	
						<div style="float:right; border:#999 solid thin; border-radius:5px; padding:2px 2px 0px 2px !important; height:13px !important; margin:0px;"><?php echo $tables['max_pax']; ?></div>
						<div style="float:left; margin-top:2px;"><?php echo $tables['table_name']; ?></div>
                      </div>
                    </td>
                	<td class="first_time">&nbsp;</td>
				<?php
				$time = strtotime($store_hrs['open']);
				$end = strtotime($store_hrs['close']);
				
				$time_count=1;
				$open_slot = 1;

				$in_between_ends = 0;
			
				while($time<=$end){
										
					if($block_ends>$time){
						$time_slot_class = 'reserved';	
						$open_slot = 0;
					}elseif($block_ends==$time){
						$time_slot_class = 'reserved';	
						$block_ends = '';
						$open_slot = 0;
					}else{
						$time_slot_class = '';
						$open_slot = 1;
					}
					
					if($in_between_ends>=$time && $in_between_starts<=$time){
						$open_slot = 0;
						$time_slot_class .= ' in_between';	
					}

					//check if table has reservation for the specified time
					$table_reserve_sql = "SELECT r.id, r.time, r.duration, CONCAT(a.fname, ' ', a.lname) AS name, r.number_people 
										  FROM reservation_table rt, reservation r, table_detail t, account a
										  WHERE rt.table_detail_id=".$tables['id']." AND rt.reservation_id=r.id  
										  AND t.restaurant_detail_id=".$res_det['id']." AND t.id=rt.table_detail_id AND a.id=r.account_id AND
										  r.date='".date('m/d/Y',strtotime($date_selected))."' AND r.time='".date('H:i:00',$time)."' AND r.approve=2";
					
					$table_reserve_qry = mysql_query($table_reserve_sql);
					$table_reserve_num = mysql_num_rows($table_reserve_qry);
									
					$table_block = '';
					
					if($table_reserve_num==1){
						
						$time_slot_class = 'reserved';	

						$table_reserve = mysql_fetch_assoc($table_reserve_qry);
						
						if($table_reserve['duration']!=''){
							$duration = $table_reserve['duration'];
						}else{
							$duration = $res_det['time_interval'];	
						}
						
						$block_ends = strtotime(date('H:i:00',strtotime($table_reserve['time'])).' + '.$duration.' mins');
						$time_blocks_width = $time_interval_width + ($duration/$time_increments) * $time_interval_width - $time_block_left_padding;
						
						//add number of division
						$division = $duration / $time_interval_width;
						$time_blocks_width += round($division);
						
						$table_block = '<div class="table_block" style="width:'.$time_blocks_width.'px;" 
											title="Details of Table Resrvation Here" id="tbl6">
											'.date('H:i',strtotime($table_reserve['time'])).' >> '.$table_reserve['number_people'].' '.$table_reserve['name'].' '.date('H:i',$time).'&rarr;'.date('H:i',$block_ends).' ['.$duration.'\']
											<div style="display: none;" class="popDetails" data-rel="'.$table_reserve['id'].'">'.$table_reserve['id'].'</div>
										</div>
										'; //'.date('H:i',$time).'&rarr;'.date('H:i',$block_ends).' ['.$duration.'mins]
					
						$in_between_starts = strtotime(date('H:i',$block_ends).' + '.$time_increments.' mins');
						$in_between_ends = strtotime(date('H:i',$block_ends).' + '.$garcon_settings['between_interval'].' mins');
					
					}else{
						if($open_slot==1){ $time_slot_class .= ' timeslot'; }	
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