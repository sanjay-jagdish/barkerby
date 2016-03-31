<?php
//require('../config/config.php');
ini_set('display_errors',0);

$time_block_left_padding = 2;// pixels
$time_interval_width = 24; // 48 pixels; width of <td>  

$days_maximum = 14;

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
	$store_hrs['open']  = date('H:i',strtotime($res_det['start_time'].' -2 hours'));
	$store_hrs['close'] = $res_det['end_time'];
	
	$time_increments = 30;//$garcon_settings['time_interval'];

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

<script src="tablechart/js/jquery-ui-timepicker-0.3.3/jquery.ui.timepicker.js"></script>
<script src="tablechart/js/jQuerytypeahead/typeahead.js"></script>
<link href="tablechart/js/jQuerytypeahead/examples.css" rel="stylesheet" type="text/css" />

<!-- for localization(swedish) -->
<script type="text/javascript" src="tablechart/libs/jquery.ui.datepicker-sv.js"></script>
<script type="text/javascript" src="tablechart/libs/jquery.ui.timepicker-sv.js"></script>

<?php
if($res_det_num==1){
?>
<script src="tablechart/libs/jquery.dataTables.js"></script>
<script src="tablechart/libs/dataTables.scroller.js"></script>
<script src="tablechart/libs/FixedColumns.js"></script>
<!--script src="tablechart/main.js"></script -->


<script>
$(function () {

    var dataTable;

    var tableHeight = function () {
        var $tr = $('.dataTables_scrollHeadInner thead tr');
        return $(window).height() - 4 - ($tr.length ? $tr.height() : 0);
    };

    // Change height to match window
    var onResize = function () {
        var oSettings = dataTable.fnSettings();
        oSettings.oScroll.sY = tableHeight(); 
        dataTable.fnDraw();
    };

    var assignScrollHandlers = function () {
        var $table = $('#DataTables_Table_0_wrapper'),
            $scrollBody = $('.dataTables_scrollBody'),
            $scrollHeader = $('.dataTables_scrollHead'),
            $scrollColumn = $('.DTFC_LeftBodyWrapper');

        $('.DTFC_LeftBodyWrapper td, .dataTables_scrollHeadInner th').on('click', function (e) {

            var $target = $(e.target).closest('td,th'),
                axis = $target.is('td') ? 'x' : 'y',
                $parent = $target.parent(), // the row
                index,
                $tds,
                $scroll = null;

            if (axis === 'x') {
                // index of the row within the tbody
                index = $parent.parent().find('tr').index($parent);
                // All tds in that row
                $tds = $scrollBody.find('tr:nth-child(' + (index + 1) + ')').find('td');
            } else {
                // index of the th within the header row
                index = $parent.find('th').index($target);
                // All tds in this column
                $tds = $scrollBody.find('td:nth-child(' + (index + 1) + ')');
            }

            for (var i = 0, m = $tds.length; i < m; i++) {
                if ($tds.eq(i).find('span').length) {
                    $scroll = $tds.eq(i);
                    break;
                }
            }

            if ($scroll) {
                $scrollBody.scrollTo( $scroll, { duration:500, axis: axis});
            }
        });
    };

    var onFirstDraw = _.once(function () {
        $('.loading').hide();
        $('.table-container').addClass('show-table');
        onResize();
        assignScrollHandlers();
    });

    dataTable = $('.table-chart').dataTable({
        sDom: 'frtiS',
        sScrollY: tableHeight(),
        sScrollX: '100%',
        bAutoWidth: false,
        bScrollCollapse: true,
        bPaginate: false,
        bFilter: false,
        bInfo: false,
        bSort: false,
        bDeferRender: true,
        oScroller: {
            rowHeight: 29
        }
    });

    new FixedColumns(dataTable, {
        iLeftWidth: 200,
		iLeftColumns: 2,
        fnDrawCallback: onFirstDraw
    });

    $(window).resize(onResize);
});


$(function(){

	jQuery(function(){
	
		
		  //calendar.setTimeZone('<?php echo $garcon_settings['timezone']; ?>');
	
		 // var array = [<?php echo $days_disabled; ?>]; //'02/22/2014','02/23/2014','02/28/2014'
		
		  jQuery( "#chartdate" ).datepicker({

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
	  					
			  window.location='?page=scheduler-chart&parent=staff&date='+selectedDate;
			
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
}
?>
<link rel="stylesheet" href="tablechart/js_css/jquery-ui.css">
<script src="tablechart/js_css/jquery-ui.js"></script>

<link rel="stylesheet" media="all" type="text/css" href="../css/jquery-ui.css" />
<script type="text/javascript" src="scripts/jquery-ui.min.js"></script>
<script src="scripts/jquery-ui.js"></script>
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

<?php
$colors = array(
				'#f89406',
				'#b94a48',
				'#3a87ad',
				'#9D9D00',
				'#59955C',
				'#B96F6F'
				);

foreach($colors as $key => $color_hex){
	echo '.hexcolor_'.$key.'{ background-color: '.$color_hex.' }';
}


?>

.user_title{
	color: #fff;
	text-align:center; 
	border:#999 solid thin; 
	border-radius:5px; 
	padding:2px 2px 0px 2px !important; 
	height:13px !important; 
	margin:0px; 
	overflow:hidden;	
}

	.reserved{ /*background-color: #3a87ad !important;*/ border-right: none !important; border-left: none !important; } /* #9FC */
	.in_between{ background-color:#999 !important; } /*  */

	.table_block{ 
		color: #fff;
		position:relative; 
		/*background-color:#9FC;*/
		display:table-row;
		/*border:#060 solid thin;*/
		padding: 4px 0 0 0;
		text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
		cursor: pointer;
	}
	
	td th{
		height:20px;
		width:<?php echo $time_interval_width; ?>px !important;
		padding:0px;
		margin:0px;
	}
	
	/* tr:hover td{ background-color:#FF9; } */ 
	
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
		height: 15px;
		padding-top:8px;
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

.hour_th{ font-weight: bold !important; color:#fff !important; background-color:#333 !important; }
.min_th{ font-weight: normal !important; color:#333 !important; background-color:#fff !important; }

th:hover{ background:none !important; }
.hour_th:hover{ background-color:#333 !important; color:#FC0 !important;} 
.min_th:hover{ background-color:#666 !important; color:#fff !important; } 


.fadediv{
	width:100%;
	height:100%;
	position:fixed;
	left:0;
	right:0;
	top:0;
	background:black;
	opacity:.5;
	z-index:999999;
	display:none;
}

.loaddiv{
	width:500px;
	margin:0 auto;
	left:0;
	right:0;
	top:50%;
	color:white;
	text-align:center;
	z-index:9999999;
	position:absolute;
	display:none;
}

</style>

<link rel="stylesheet" type="text/css" href="css/style.css">

<div>
<?php
if($res_det_num==1){

/*
<script src="js/jQuery.bubbletip-1.0.6.js" type="text/javascript"></script>
<link href="js/bubbletip/bubbletip.css" rel="stylesheet" type="text/css" />
*/

?>
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

		});
				
			
</script>
<?php
}
?>

<div class="wrapper">
	    
    <div class="page-content" style="background-color:#FFF;">
      <?php //<div id="chartdate" style="float:left;"></div> ?>

<?php
$staff_sql = "SELECT a.id, CONCAT(fname,' ', lname) AS staff_name, t.description  
			  FROM account a, type t
			  WHERE a.deleted=0 AND  a.type_id=t.id   
			  AND a.id=".$_SESSION['login']['id'];

$staff_qry = mysql_query($staff_sql);
$staff_res = mysql_fetch_assoc($staff_qry);
?>

<div style="clear:both;">		
	<div style="float:left; margin: 2px 6px; line-height:12px; background-color:#333;" class="user_title"><?php echo $staff_res['description']; ?></div>
    <div style="float:left; margin-top:2px;"><strong><?php echo $staff_res['staff_name']; ?></strong></div>
</div>

<div style="clear:both; height:1px;">&nbsp;</div>

<div class="container avails-grid" id="table_chart">

	<?php
	if($res_det_num==1){
	?>

	<p class="loading alert" style="z-index:100000;">One moment please... Loading Table Chart...</p>
	
    <div style="clear:both; height:10px;">&nbsp;</div>

    <div class="table-container" style="float:left; clear:left;">
	
    	<table cellpadding="0" cellspacing="0" border="0" class="table table-condensed table-chart table-bordered" style="clear:none !important;">

        	<thead>
            	<tr>
                	<th>&nbsp;</th>
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
						$time_txt = ''.date('i',$time).''; 
						$class_th = 'min_th';
					}
					?>
                    <th style="width:70px;" class="<?php echo $class_th; ?>">
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
				$start_date = strtotime('today');
				$row_color=0;
				for($days = 0; $days<=$days_maximum; $days++){ 
					$roll_date = strtotime(date('Y-m-d', $start_date)." + $days days");
					$date_selected = date('Y-m-d',$roll_date);
				?>
                <tr>
                	<td valign="middle" nowrap="nowrap">
                      <div>	
						<div style="text-align:center; margin-top:2px;"><?php echo date('d M Y',$roll_date); ?></div>
                      </div>
                    </td>
                	<td valign="middle" nowrap="nowrap">
                      <div>	
						<div class="user_title hexcolor_<?php echo $row_color; ?>"><?php echo date('D',$roll_date);; ?></div>
                      </div>
                    </td>
                	<td class="first_time">&nbsp;</td>
				<?php
				$time = strtotime($store_hrs['open']);
				$end = strtotime($store_hrs['close']);
				
				$time_count=1;
				$open_slot = 1;

				$in_between_ends = 0;
				$block_ends = '';
			
				while($time<=$end){
										
					if($block_ends>$time){
						$time_slot_class = 'reserved hexcolor_'.$row_color;	
						$open_slot = 0;
					}elseif($block_ends==$time){
						$time_slot_class = 'reserved hexcolor_'.$row_color;	
						$block_ends = '';
						$open_slot = 0;
					}else{
						$block_ends = '';
						$time_slot_class = '';
						$open_slot = 1;
					}
					
					if($in_between_ends>=$time && $in_between_starts<=$time){
						$open_slot = 0;
						$time_slot_class .= ' in_between';	
					}


					//check if staff has schedule for the current time
					$table_reserve_sql = "SELECT 'sched' AS sched_type, id, end_time, start_time, ROUND(TIME_TO_SEC((TIMEDIFF(end_time, start_time)))/60) AS duration, (TIMEDIFF(end_time, start_time)) AS diff_hr_min  
										  FROM schedule
										  WHERE account_id=".$staff_res['id']." AND
										  ( 
										  	STR_TO_DATE('".date('m/d/Y',strtotime($date_selected))."', '%m/%d/%Y') BETWEEN STR_TO_DATE(valid_from, '%m/%d/%Y') AND STR_TO_DATE(valid_until, '%m/%d/%Y') 
										  ) 
										  AND deleted=0
										  AND days LIKE '%".date('D',strtotime($date_selected))."%' AND start_time='".date('H:i',$time)."' 
										  AND 
										  	 
												id NOT IN (SELECT sched_01 FROM shift_request WHERE deleted=0 AND approve=1 AND date_2=STR_TO_DATE('".date('m/d/Y',strtotime($date_selected))."', '%m/%d/%Y')) AND  
												id NOT IN (SELECT sched_02 FROM shift_request WHERE deleted=0 AND approve=1 AND date_1=STR_TO_DATE('".date('m/d/Y',strtotime($date_selected))."', '%m/%d/%Y'))
											
										  UNION 
										  
										  SELECT 'shift' AS sched_type, s.id, s.end_time, s.start_time, ROUND(TIME_TO_SEC((TIMEDIFF(s.end_time, s.start_time)))/60) AS duration, (TIMEDIFF(s.end_time, s.start_time)) AS diff_hr_min  
										  	FROM shift_request sr, schedule s
											WHERE sr.account_id_1=".$staff_res['id']." AND sr.sched_02=s.id AND sr.option=1 AND sr.deleted=0 AND sr.approve=1 
												  AND sr.date_2=STR_TO_DATE('".date('m/d/Y',strtotime($date_selected))."', '%m/%d/%Y')	
												  AND s.start_time='".date('H:i',$time)."' 												  					


										  UNION 
										  
										  SELECT 'shift' AS sched_type, s.id, s.end_time, s.start_time, ROUND(TIME_TO_SEC((TIMEDIFF(s.end_time, s.start_time)))/60) AS duration, (TIMEDIFF(s.end_time, s.start_time)) AS diff_hr_min  
										  	FROM shift_request sr, schedule s
											WHERE sr.account_id_2=".$staff_res['id']." AND sr.sched_01=s.id AND sr.option=1 AND sr.deleted=0 AND sr.approve=1 
												  AND sr.date_1=STR_TO_DATE('".date('m/d/Y',strtotime($date_selected))."', '%m/%d/%Y')	
												  AND s.start_time='".date('H:i',$time)."' 												  					


										  UNION 
										  
										  SELECT 'drop' AS sched_type, s.id, s.end_time, s.start_time, ROUND(TIME_TO_SEC((TIMEDIFF(s.end_time, s.start_time)))/60) AS duration, (TIMEDIFF(s.end_time, s.start_time)) AS diff_hr_min  
										  	FROM shift_request sr, schedule s
											WHERE sr.account_id_2=".$staff_res['id']." AND sr.sched_01=s.id AND sr.option=2 AND sr.deleted=0 AND sr.approve=1 
												  AND sr.date_1=STR_TO_DATE('".date('m/d/Y',strtotime($date_selected))."', '%m/%d/%Y')	
												   AND s.id=sr.sched_01 AND s.start_time='".date('H:i',$time)."'
										  ";
		
					$table_reserve_qry = mysql_query($table_reserve_sql);
					$table_reserve_num = mysql_num_rows($table_reserve_qry);
									
					$table_block = '';
					
					if($table_reserve_num==1){
						

						$table_reserve = mysql_fetch_assoc($table_reserve_qry);

						
						$sr_note = '';
						$sr_icon = '';

						if($table_reserve['sched_type']!='sched'){
							if($table_reserve['sched_type']=='shift'){
								$sr_note = $table_reserve['sched_type'];//'&hArr;';
								$sr_icon = '<img src="images/shift-swap.png" style="width:16px; height:16px;">';
							}else{
								$sr_note = $table_reserve['sched_type'];//'&rArr; ';
								$sr_icon = '<img src="images/shift-drop.png" style="width:16px; height:16px; -webkit-filter: drop-shadow(5px 5px 10px black);">';
							}
						}
		
						//subtract the timeinterval to stop shading before the next time-slot
						//$table_reserve['end_time'] = date('H:i',strtotime(($table_reserve['end_time'].' - '.$time_increments.' minutes')));
												
						$time_slot_class = 'reserved hexcolor_'.$row_color;	
					
						$duration = $table_reserve['duration'];
						
						$block_ends = strtotime(date('H:i',strtotime(($table_reserve['end_time'].' - '.$time_increments.' minutes'))));
						
						if($table_reserve['end_time']=='00:00')
							$block_ends = strtotime('23:59:59');
							
						$time_blocks_width = $time_interval_width + ($duration/$time_increments) * $time_interval_width - $time_block_left_padding;
						
						//add number of division
						$division = $duration / $time_interval_width;
						$time_blocks_width += round($division);
						$hrs_mins = explode(':',$table_reserve['diff_hr_min']);
						
						//this is to fix negative time difference
						if($hrs_mins[0]<0){
							$hrs_new = $hrs_mins[0] + 24;
							$table_reserve['diff_hr_min'] = $hrs_new.':'.$hrs_mins[1];
							$hrs_mins = explode(':',$table_reserve['diff_hr_min']);
						}
						
						$table_block = '<div class="table_block" style="width:'.$time_blocks_width.'px;" data-rel="'.$sr_note.$table_reserve['id'].'">'.$sr_icon.'
											'.date('H:i',$time).'&rarr;'.$table_reserve['end_time'].' ['.$hrs_mins[0].'hrs '.$hrs_mins[1].'mins]
											<div style="display: none;" class="popDetails" data-rel="'.$table_reserve['id'].'">'.$table_reserve['id'].'</div>
										</div>
										'; //'.date('H:i',$time).'&rarr;'.date('H:i',$block_ends).' ['.$duration.'mins]
					
						$in_between_starts = strtotime(date('H:i',$block_ends).' + '.$time_increments.' mins');
						//$in_between_ends = strtotime(date('H:i',$block_ends).' + '.$garcon_settings['between_interval'].' mins');
					
					}else{
						//$table_block = $table_reserve_sql;
						if($open_slot==1){ 
							$time_slot_class .= ' timeslot'; 
						}	
						
					}
					

					?>
                    <td class="space_time <?php echo $time_slot_class; ?>" data-rel="<?php echo $date_selected.'|'.date('H:i',$time).':00|'.$staff_res['id']; ?>" 
                    		title="<?php echo $tables['table_name'].' '.date('H:i',$time); ?>">
							
							<?php if($table_seq==1){ ?>
                            <span id="time_<?php echo date('Hi',$time); ?>">
                            <?php } ?>
							
							<?php echo $table_block; ?>

                    </td>
                    <?php
					$time = strtotime(date('H:i:s',$time).' + '.$time_increments.' mins');
					$time_count++;
				}
				?>	
                	<td class="last_time">&nbsp;</td>
                </tr>	
                <?php	
					$table_seq++;				
					$row_color++;
					
					if($row_color==count($colors))
						$row_color=0;
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


<div class="fadediv">&nbsp;</div>
<div class="loaddiv">
	<h2>Loading...Please wait...</h2>
</div>

</body>
<script type="text/javascript">
$(document).ready(function(e) {
	$(".dataTables_scrollBody").scrollTo("#time_<?php echo date('H00'); ?>");	
});
</script>

</html>