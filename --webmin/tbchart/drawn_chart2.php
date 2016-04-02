<?php
require('../config/config.php');
ini_set('display_errors',0);

$time_block_left_padding = 2;// pixels
$time_interval_width = 48; // pixels; width of <td>  

if(!isset($_POST['date'])){
	$date_selected = date("Y-m-d");
}else{
	$date_selected = $_POST['date'];
}

$q=mysql_query("SELECT var_value FROM settings WHERE var_name='week_starts'");
$row=mysql_fetch_assoc($q);

//get restaurant details e.g. start/end time with regards to current date
$res_det_sql = "SELECT id, start_time, end_time, time_interval FROM restaurant_detail 
				WHERE '".$date_selected."' BETWEEN STR_TO_DATE(start_date,'%m/%d/%Y') AND STR_TO_DATE(end_date,'%m/%d/%Y') 
				AND deleted=0 
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
}
?>
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
	
		  jQuery( "#chartdate" ).datepicker({
			  minDate: 0, 
			  firstDay: '<?php echo $row['var_value']; ?>',
			  dateFormat: 'yy-mm-dd',
			  onSelect: function(selectedDate){
	  					
			  //window.location='?date='+selectedDate;
			  
				
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
				
								
			  }			
			});
		   
		<?php
		if($res_det_num==1){
		?>
		   $('#tbl6').bubbletip($('#tbl6_up'));
		<?php
		}
		?>   
	});


});
  
</script>
<div>
<?php
if($res_det_num==1){
?>
<script src="js/jQuery.bubbletip-1.0.6.js" type="text/javascript"></script>
<link href="js/bubbletip/bubbletip.css" rel="stylesheet" type="text/css" />
<?php
}
?>

<div class="container avails-grid" id="table_chart">

	<?php
	if($res_det_num==1){
	?>

	<p class="loading alert">Loading table...</p>

    <div class="table-container">
		<table cellpadding="0" cellspacing="0" border="0" class="table table-condensed avails-table table-bordered">
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
					
					
					//check if table has reservation for the specified time
					$table_reserve_sql = "SELECT r.duration 
										  FROM reservation_table rt, reservation r 
										  WHERE rt.table_detail_id=".$tables['id']." AND rt.reservation_id=r.id AND 
										  r.date='".$date_selected."' AND r.time='".date('H:i:00',$time)."' AND r.approve=2";
					$table_reserve_qry = mysql_query($table_reserve_sql);
					$table_reserve_num = mysql_num_rows($table_reserve_qry);
					
					
					$table_block = '';
					$time_slot_class = '';
					
					if($table_reserve_num==1){
						
						
						$table_reserve = mysql_fetch_assoc($table_reserve_qry);
	
						if(($table_reserve['duration']+0)<=0){
							$duration = $time_increments;	
						}else{
							$duration = $table_reserve['duration'];
						}
						
						$block_ends = strtotime($table_reserve['time'].' + '.$duration.' mins');
						$time_blocks_width = $time_interval_width + ($duration/$time_increments) * $time_interval_width - $time_block_left_padding;
						$table_block = '<div class="table_block" style="width:'.$time_blocks_width.'px;" 
											title="Details of Table Resrvation Here" id="tbl6">
											'.date('H:i',$time).'&rarr;'.date('H:i',$block_ends).' ['.$duration.'mins]
										</div>';
					}else{
						$time_slot_class = 'timeslot';	
					}

					?>
                    <td class="space_time <?php echo $time_slot_class; ?>" data-rel="<?php echo $date_selected.' '.date('H:i',$time).':00|'.$tables['id']; ?>"  title="<?php echo $tables['table_name'].' '.date('H:i',$time); ?>"><?php echo $table_block; ?></td>
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

<div id="overlay" style="display: none;"></div><div id="modal" style="width: auto; height: auto; top: 300.5px; left: 537px; display: none;"><div id="content"></div><a id="close" href="#">close</a></div></body>
