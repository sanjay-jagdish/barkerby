<div id="calendar-wrapper">
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

?>
	<input type="text" id="get_date" value="<?php echo $date_selected; ?>" style="display:none" />
<?php


$q=mysql_query("SELECT var_value FROM settings WHERE var_name='week_starts'");
$row=mysql_fetch_assoc($q);

//get restaurant details e.g. start/end time with regards to current date
$res_det_sql = "SELECT id, start_time, end_time, time_interval, dine_interval, between_interval FROM restaurant_detail 
				WHERE '".$date_selected."' BETWEEN STR_TO_DATE(start_date,'%m/%d/%Y') AND STR_TO_DATE(end_date,'%m/%d/%Y') 
				AND deleted=0 AND days LIKE '%".date('D',strtotime($date_selected))."%'
				ORDER BY id DESC LIMIT 1";
//echo $res_det_sql;

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
<html><head><meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0">


<?php
if($res_det_num==1){
?>


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
        iLeftWidth: 85,
		iLeftColumns: 2,
        fnDrawCallback: onFirstDraw
    });




    $(window).resize(onResize);
	
	$.fn.center = function ()
	{
		this.css("position","fixed");
		this.css("top", ($(window).height() / 2) - (this.outerHeight() / 2));
		//this.css("left", ($(window).width() / 2) - (this.outerWidth() / 2));
		return this;
	}
	
	//added for detail-hover
	
	// $('.table_block').mouseover(function(){
	// 	var id=$(this).attr('id');
	// 	var details=$('.'+id).html();
		
		
	// 	$('.hover-container').html(details).center();
		  
	// 	$('.hover-container, .hover-fade').delay("slow").fadeIn();
	// });

	$('.table_block').bind("contextmenu",function(e){
	   	var id=$(this).attr('id');
		var details=$('.'+id).html();
		
		
		$('.hover-container').html(details).center();
		  
		$('.hover-container, .hover-fade').delay("slow").fadeIn();
	   return false;
	}); 
	
	// $('#close').click(function(){
	// 	// $('.hover-container, .hover-fade').fadeOut();
	// 	// $('.hover-container').html('');

	// 	alert();
	// });
	
});

  function close_detail(){
  		$('.hover-container, .hover-fade').fadeOut();
		$('.hover-container').html('');
  }                                                              

</script>
<?php
}
?>

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

	.reserved{ background-color:#06C !important; color: #fff !important; border-left: none !important; border-right: none !important; } /*  */
	.in_between{ background-color:#999 !important; border-left: none !important; border-right: none !important; } /*  */

	.table_block{ 
		position:relative; 
		/*background-color:#9FC;*/
		display:table-row;
		/*border:#060 solid thin;*/
		text-shadow: 3px 1px 3px rgba(0, 0, 0, 1) !important;
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
	
	.odd{ background-color: #fff !important; }
	
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
	cursor:pointer !important;
}

#QBook:hover{
	text-decoration:none;
	color: #fff;
}

.hour_th{ font-weight: lighter !important; color:#000 !important; background-color:#EAEAEA !important; padding:0px !important; width:15px !important; }
.min_th{ font-weight: lighter !important; color:#000 !important; background-color:#fff !important; padding:0px !important; width:15px !important; }

th:hover{ background:none !important; }
/* .hour_th:hover{ background-color:#333 !important; color:#FC0 !important;} */
/* .min_th:hover{ background-color:#666 !important; color:#fff !important; } */

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

.ui-timepicker-title{
	padding:5px !important;
}




<?php
/*
$time_check = date('i')%$res_det['time_interval'];

if($time_check==0){
	$time_class = date('Hi');
}else{
	if( (date('i')/$res_det['time_interval']) <= 0.5 ){
		$time_class = date('H').(date('i')-$time_check);		
	}else{
		$pwaki = $res_det['time_interval'] - $time_check;
		$multiplier = $res_det['time_interval'] * (date('i')/$res_det['time_interval']) + $pwaki;
		$time_class = date('H').str_pad(((date('i')-$time_check)),2,0, STR_PAD_LEFT);		
	}
}
*/

//Get the total number of minutes since the opening time
//get the quotient by dividing it with the time_interval
//get the closest time equal to the time intervals 
$time_elapsed = (strtotime(date('H:i')) - strtotime($res_det['start_time'])) / 60;
$interval_unit = round($time_elapsed / $res_det['time_interval']);
$time_limit = date('Hi', strtotime($res_det['start_time'].' + '.$interval_unit*$res_det['time_interval'].' minutes'));

?>
.t<?php echo $time_limit; ?>{
	border-right: solid 3px orange;
}


.reserve_status{
	border-radius:12px;
	padding:2px 3px 3px 2px;
	margin:2px 2px 0px 0px;
	height:10px;
	width:10px;
	text-align:center;
	color:#fff;
	float:left; 
}

.blue{ background-color:#06C !important; color:#FFF !important;}
.green{ background-color:#090 !important; color:#FFF !important;}
.red{ background-color:#C30 !important;  color:#FFF !important; }
.black{ background-color:#000 !important; color:#FFF !important;}

.dataTables_scroll{
	overflow: auto !important;
}

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

					close_detail();
					
					e.preventDefault();
					var parameters = jQuery(this).attr('data-rel');
					
					jQuery('.fadediv, .loaddiv').fadeIn();
					jQuery.ajax({
							 url: "tablechart/book_table.php",
							 type: 'POST',
							 data: 'parameters='+parameters+'&date=<?php echo $date_selected;?>',
							 success: function(value){
								jQuery('.fadediv, .loaddiv').fadeOut();
								modal.open({content: value});

							 }
					});			
					
				});


				$('#QBook').click(function(e){
					
					e.preventDefault();
					var parameters = jQuery(this).attr('data-rel');
					jQuery('.fadediv, .loaddiv').fadeIn();									
					
					jQuery.ajax({
							 url: "tablechart/quick_book_table.php",
							 type: 'POST',
							 data: 'parameters='+parameters+'&date=<?php echo $date_selected;?>',
							 success: function(value){
								jQuery('.fadediv, .loaddiv').fadeOut();
								modal.open({content: value});

							 }
					});			
					
				});


				$('.new_waitlist_btn').click(function(e){
					
					e.preventDefault();
					var parameters = jQuery(this).attr('data-rel');
					jQuery('.fadediv, .loaddiv').fadeIn();									
					
					jQuery.ajax({
							 url: "tablechart/add_wait_list.php",
							 type: 'POST',
							 data: 'parameters='+parameters+'&date=<?php echo $date_selected;?>',
							 success: function(value){
								jQuery('.fadediv, .loaddiv').fadeOut();
								modal.open({content: value});

							 }
					});			
					
				});
				
				$('#waiting_list_link').click(function(e){
					
					e.preventDefault();
					var parameters = jQuery(this).attr('data-rel');
					jQuery('.fadediv, .loaddiv').fadeIn();									
					
					jQuery.ajax({
							 url: "tablechart/waiting_list.php",
							 type: 'POST',
							 data: 'parameters='+parameters+'&date=<?php echo $date_selected;?>',
							 success: function(value){
								jQuery('.fadediv, .loaddiv').fadeOut();
								modal.open({content: value});

							 }
					});			
					
				});
				

				$('.reserved, .table_block').click(function(e){
					
					e.preventDefault();
					var parameters = jQuery(this).attr('data-rel');
					jQuery('.fadediv, .loaddiv').fadeIn();									
					
					jQuery.ajax({
							 url: "tablechart/edit_booking.php",
							 type: 'POST',
							 data: 'res_id='+parameters+'&date=<?php echo $date_selected;?>',
							 success: function(value){
								jQuery('.fadediv, .loaddiv').fadeOut();
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
	  					
			  // window.location='?page=dashboard&date='+selectedDate;

					  jQuery('.fadediv, .loaddiv').fadeIn();

					  		jQuery.ajax({

									url: "tablechart/calendar-data.php",
									type: 'GET',
									data: 'date='+encodeURIComponent(selectedDate),
									success: function(value){
										jQuery('.fadediv, .loaddiv').fadeOut();
										jQuery('#calendar-wrapper').html(value);
										// alert(value);
									}
								});
			
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
<div style="text-align:center;">
	
    <style>
	#legend_container ul li { display: inline; padding-right: 8px; }
	.new_waitlist_btn{
		float:left;  height:20px; width:80px;  background-color:#FC9; height:20px; padding:5px; border-radius: 0px 10px 10px 0px;	
	}
	.new_waitlist_btn:hover{ text-decoration:underline; color:#FFF; background-color:#F60; cursor: pointer; }
    #waiting_list_link:hover{ text-decoration:underline; cursor: pointer;  }
    </style>
    
    <div style="margin-left:45px; width:630px; float: left;" id="legend_container">
    	<div style="float:left; margin-right:6px;">Status Legend:</div> 
    	<ul style="display:inline; list-style: none; padding: none;">
        	<li><div class="reserve_status blue" style="line-height:12px; float:left;">&nbsp;</div><div style="float:left;">Booked-Not Arrived Yet &nbsp;&nbsp;&nbsp;</div></li>
    		<li><div class="reserve_status green" style="line-height:12px; float:left;">&nbsp;</div><div style="float:left;">Have Been Seated  &nbsp;&nbsp;&nbsp;</div></li> 
    		<li><div class="reserve_status red" style="line-height:12px; float:left;">&nbsp;</div><div style="float:left;">Waiting To Be Seated  &nbsp;&nbsp;&nbsp;</div></li> 
    		<li><div class="reserve_status black" style="line-height:12px; float:left;">&nbsp;</div><div style="float:left;">No Show</div></li> 
    	</ul>
    </div>
    
    <div style="float:left; margin-top:-6px;">
        <div style="float:left; margin-left:12px; background-color:#FC9; height:20px; padding:5px; border-radius: 10px 0px 0px 10px;">
            <div style="float:left; width:80px;">Waiting List</div>
        </div>
        
        <?php 
		$waitlist_sql = "SELECT id FROM wait_list WHERE date='".date('m/d/Y',strtotime($date_selected))."' AND status=0 AND deleted=0";
		$waitlist_qry = mysql_query($waitlist_sql);
		$waitlist_cnt = mysql_num_rows($waitlist_qry);

		$lv = 'is';
		$s = '';
		if($waitlist_cnt>0){
			if($waitlist_cnt>1){ $lv='are'; $s='s'; }
			$waitlist_report = '<font color="red">There '.$lv.' <strong>'.number_format($waitlist_cnt,0).'</strong> record'.$s.' in the list.</font>';
		}else{
			$waitlist_report = 'There are no guests on waitlist.';
		}
		?>
        
        <div id="waiting_list_link" style="float:left; background-color:#FFF; margin:0px; height:20px; border-top: solid 2px #fc9;  border-bottom: solid 2px #fc9; padding:3px;">
        <?php echo $waitlist_report; ?>
        </div>
        <div class="new_waitlist_btn" data-rel="<?php echo $date_selected.' '.$res_det['id']; ?>">+ Add New</div>
    </div>
    
    <div style="float:right; width:100px; text-align:right; margin-right:20px;"><a href="tablechart_window.php" target="_blank">Helsidesläge</a></div>
</div>
<?php
}
?>

<div class="wrapper">
	
     <div class="clear"></div>
    
    <div class="page-content" style="background-color:#FFF;">

<div id="operation_details" style="float:left; width:210px; padding:0px 8px 8px 20px; line-height:15px;">
	<strong><u>Information</u></strong><br>
	Datum: <b><?php echo date('D m/d/Y',strtotime($date_selected)); ?></b><br>
	Starttid: <b><?php echo $res_det['start_time']; ?></b><br>
	Stoptid: <b><?php echo $res_det['end_time']; ?></b><br>
	Antal bord: <strong><?php echo $max_table; ?></strong><br>
    
	Bokningsintervall: <b><?php echo $res_det['time_interval']; ?> mins.</b>
	<br>
	Sittningsintervall: <b><?php echo $res_det['dine_interval']; ?> mins.</b>
	<br>
	Dukningsintervall: <b><?php echo $res_det['between_interval']; ?> mins.</b> <br>
	
    <div style="width:200px; border: solid thin 1px; margin-top:4px;">
		Booking Summary:
        <style>
			.booking_summary th{ padding:2px 4px; }
			.booking_summary td{ border:#CCC solid thin; }
		</style>
    	<?php 
		//lunch count
		$lunch_sql = "SELECT count(id) AS bookings FROM reservation WHERE date='".date('m/d/Y',strtotime($date_selected))."' AND time<'16:00:00' AND deleted=0";
		$lunch_qry = mysql_query($lunch_sql);
		$lunch = mysql_fetch_assoc($lunch_qry);
		//dinner count
		$dinner_sql = "SELECT count(id) AS bookings FROM reservation WHERE date='".date('m/d/Y',strtotime($date_selected))."' AND time>='16:00:00' AND deleted=0";
		$dinner_qry = mysql_query($dinner_sql);
		$dinner = mysql_fetch_assoc($dinner_qry);
		?>
        <table class="booking_summary" style="background-color:#CCC; border-collapse:collapse; border-color:#CCC;" width="100%" border="1">
        	<thead><tr><th>Lunch</th><th>Middag</th><th>Total</th></tr></thead>
        	<tbody>
            	<tr>
                	<td align="center" bgcolor="#FFFFFF"><strong><?php echo number_format($lunch['bookings'],0); ?></strong></td>
                	<td align="center" bgcolor="#FFFFFF"><strong><?php echo number_format($dinner['bookings'],0); ?></strong></td>
                	<td align="center" bgcolor="#FFFFFF"><strong><?php echo number_format(($lunch['bookings']+$dinner['bookings']),0); ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>

<?php
	if($res_det_num==1){
	?>
    <br>
	<a id="QBook" data-rel="<?php echo $date_selected.' '.$res_det['id']; ?>">Boka</a>
	<br>

	<?php
	}
	?>    

        
</div>

<div id="chartdate" style="float:left;"></div>




<?php
if($res_det_num==1){
?>


<?php
}//if there is a scheduled business/work
?>


<div class="container avails-grid" id="table_chart">

	<?php
	if($res_det_num==1){
	?>

	<p class="loading alert" style="z-index:100000;">One moment please... Loading Table Chart...</p>
	
    <div style="clear:both; height:10px;">&nbsp;</div>

    <div class="table-container" style="float:left; clear:left;">
	
    	<table cellpadding="0" cellspacing="0" border="0" class="compact table table-condensed table-chart table-bordered" style="clear:none !important;">

        	<thead>
            	<tr>
                	<th rospan="2">&nbsp;</th>
                	<th rospan="2">&nbsp;</th>
                	<th rospan="2">&nbsp;</th>
				<?php 
				$time_h = strtotime($store_hrs['open']);
				$end_h = strtotime($store_hrs['close']);
				$hr_span = 60 / $time_increments;
								
				while($time_h<=$end_h){
					if(date('i',$time_h)=='00'){
						$time_txt_h = '<font style="font-size:12px;"><b>'.date('H',$time_h).'</b></font>';
						$class_th = '';
                    ?>
				  <th class="<?php echo $class_th; ?>" colspan="<?php echo $hr_span; ?>" style="text-align:left !important; padding-left:0px !important">
					<?php 
					echo $time_txt_h;
					?>
                    </th>
                    <?php
					}
					$time_h = strtotime(date('H:i:s',$time_h).' + '.$time_increments.' mins');
				}
				?>	
               	  <th>&nbsp;</th>
                </tr>
            	<tr>
                	<th>&nbsp;</th>
                	<th>&nbsp;</th>
                	<th>&nbsp;</th>
				<?php 
				$time = strtotime($store_hrs['open']);
				$end = strtotime($store_hrs['close']);
								
				$colCount = 0;
								
				while($time<=$end){

					$tdClass = ($colCount % 2) ? 'min_th' : 'hour_th'; 
		
					if(date('i',$time)=='00'){
						$time_txt = '&nbsp;0&nbsp;';
					}else{
						$time_txt = ''.date('i',$time).''; 
					}
					?>
                    <th style="width:70px; line-height:10px; font-size:10px !important; text-align:center; vertical-align:middle;" class="<?php echo $tdClass; ?>" bgcolor="<?php echo $rowColor; ?>">
					<?php 
					echo $time_txt;
					?>
                    </th>
                    <?php
					$time = strtotime(date('H:i:s',$time).' + '.$time_increments.' mins');
					$colCount++;
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

				$table_seq = 1;

				while($tables = mysql_fetch_assoc($tables_qry)){
				?>
                <tr>
                	<td valign="middle" nowrap="nowrap" style="width:1px;">
                      <div>	
						<div style="float:left; margin-top:2px;"><?php echo $tables['table_name']; ?></div>
                      </div>
                    </td>
                	<td valign="middle" nowrap="nowrap">
                      <div>	
						<div style="float:right; border:#999 solid thin; border-radius:5px; padding:2px 2px 0px 2px !important; height:13px !important; margin:0px;"><?php echo $tables['max_pax']; ?></div>
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
						$time_slot_class = 'reserved '.$status_class;	
						$open_slot = 0;
					}elseif($block_ends==$time){
						$time_slot_class = 'reserved '.$status_class;	
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
					$table_reserve_sql = "SELECT r.id, r.time, r.duration, CONCAT(a.fname, ' ', a.lname) AS name, a.email, a.phone_number, r.number_people, r.status 
										  FROM reservation r
										  LEFT JOIN reservation_table rt ON rt.reservation_id=r.id 
										  LEFT JOIN table_detail t ON t.id=rt.table_detail_id 
										  LEFT JOIN account a ON a.id=r.account_id
										  WHERE rt.table_detail_id=".$tables['id']." AND t.restaurant_detail_id=".$res_det['id']." 
										  AND r.date='".date('m/d/Y',strtotime($date_selected))."' AND r.time>='".date('H:i:00',$time)."' 
										  AND r.time<'".date('H:i:00', strtotime(date('H:i:00',$time).' + '.$res_det['time_interval'].' mins'))."'
										  AND r.approve=2 AND rt.deleted=0 AND r.deleted=0 AND r.deleted=0 
										  AND r.status<>3";

					
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
										
						switch($table_reserve['status']){
							case 1:
								$status_class = 'green';
								break;				
							case 2:
								$status_class = 'red';
								break;				
							case 3:
								$status_class = 'black';
								break;							
							default:
								$status_class = 'blue';
						}

						$time_slot_class .= ' '.$status_class;
						
						if(trim($table_reserve['name'])!=''){
							$client_name = $table_reserve['name'];
						}else{
							$client_name = '***Walk-In***';
						}
												
						$table_block = '<div class="table_block" id="table_block_'.$table_reserve['id'].$tables['id'].'" style="width:'.$time_blocks_width.'px;" data-rel="'.$table_reserve['id'].'">
																						
											'.date('H:i',strtotime($table_reserve['time'])).' >> '.$table_reserve['number_people'].' '.$client_name.' '.date('H:i',$time).'&rarr;'.date('H:i',$block_ends).' ['.$duration.'\']
											
											<div class="detail-hover table_block_'.$table_reserve['id'].$tables['id'].'" data-rel="'.$table_reserve['id'].'">
                        					<a id="close" href="javascript:void" onClick="close_detail()">close</a>
												<h4>'.$tables['table_name'].'</h4>
                            
                            					<div class="detail">
													
													<span class="resv"><strong>'.date('H:i',$time).'&rarr;'.date('H:i',$block_ends).' ['.$duration.'mins\']</strong></span>
													
													<div>
														<ul>
															<li>R.ID : '.$table_reserve['id'].'</li>
															<li>Customer : '.$client_name.' - '.$table_reserve['email'].'</li>
															<li>Phone Number: '.$table_reserve['phone_number'].'</li>
															<li>Number of Guests: '.$table_reserve['number_people'].'</li>
															<li>Note: '.nl2br($table_reserve['notes']).'</li>
														</ul>    
													</div>
													
												</div>
                            
                       						 </div>
											 								
											
										</div>
										'; //'.date('H:i',$time).'&rarr;'.date('H:i',$block_ends).' ['.$duration.'mins]
					
						$in_between_starts = strtotime(date('H:i',$block_ends).' + '.$time_increments.' mins');
						$in_between_ends = strtotime(date('H:i',$block_ends).' + '.$res_det['between_interval'].' mins');
					
						$data_rel = $table_reserve['id'];
					
					}else{
						
						if($open_slot==1){ 
							$time_slot_class .= ' timeslot'; 
						}	
						
						$data_rel = $date_selected.' '.date('H:i',$time).':00|'.$tables['id'].'|'.$res_det['id'];
					}

					if($time>=$in_between_ends){ $time_slot_class = 'timeslot'; }
					
					$tdClass = ($colCount % 2) ? '' : 'hour_th'; 
					
					?>
                    <td class="<?php echo 't'.date('Hi',$time); ?> space_time <?php echo $time_slot_class.' '.$tdClass; ?>" 
                    	data-rel="<?php echo $data_rel; ?>" 
                        title="<?php echo $tables['table_name'].' '.date('H:i',$time); ?>">
                        
						                        
                        <?php if($table_seq==1){ ?>
                        <span id="time_<?php echo date('Hi',$time); ?>">
                        <?php } ?>
						<?php echo $table_block; ?>
                        <?php if($table_seq==1){ ?>
                        </span>
                        <?php } ?>

                        
                    </td>
                    <?php
					$time = strtotime(date('H:i:s',$time).' + '.$time_increments.' mins');
					$time_count++;

				$colCount++;
				}
				?>	
                	<td class="last_time">&nbsp;</td>
                </tr>	
                <?php	
               	$table_seq++;

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

<div class="fadediv">&nbsp;</div>
<div class="loaddiv">
	<h2>Loading...Please wait...</h2>
</div>

<div id="overlay" style="display: none;"></div><div id="modal" style="width: auto; height: auto; top: 300.5px; left: 537px; display: none;"><div id="content"></div><a id="close" href="#">close</a></div>

</div>

<!-- for detail-hover -->
<div class="hover-fade"></div>
<div class="hover-container">

</div>
<!-- end detail-hover -->
<?php
if($date_selected==date("Y-m-d")){
?>
<script type="text/javascript">
$(document).ready(function(e) {
	$(".dataTables_scrollBody").scrollTo("#time_<?php echo date('H00'); ?>");	
});
</script>
<?php
}
?>
</body>
</html>
</div>                            