<?php
require('../config/config.php');

$q=mysql_query("select var_value from settings where var_name='week_starts'");
$row=mysql_fetch_assoc($q);

$store_hrs['open']  = '08:00:00';
$store_hrs['close'] = '17:00:00';
$time_block_left_padding = 2;// pixels
$time_interval_width = 48; // pixels; width of <td>  
$max_table = 50;
?>
<!DOCTYPE html><html><head><meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0">
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/bootstrap-responsive.css">
<link rel="stylesheet" href="styles.css">
<script src="libs/underscore-min.js"></script>
<script src="libs/jquery.min.js"></script>
<script src="libs/jquery-migrate-1.2.1.min.js"></script>
<script src="libs/jquery.scrollTo.js"></script>
<script src="libs/bootstrap.js"></script>
<script src="libs/jquery.dataTables.js"></script>
<script src="libs/dataTables.scroller.js"></script>
<script src="libs/FixedColumns.js"></script>
<script src="main.js"></script>

<link rel="stylesheet" href="js_css/jquery-ui.css">
<script src="js_css/jquery-ui.js"></script>

<link rel="stylesheet" media="all" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
</head>

<body>
<style>
	.table_block{ 
		position:relative; 
		background-color:#9FC;
		display:table-row;
	}
	
	td th{
		height:20px;
		width:<?php echo $time_interval_width; ?>px;
		padding:0px;
		margin:0px;
	}
	
	.space_time{
		padding:0px !important;			
	}
	
	td div{
		padding-top: 5px;
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
</style>
<div>
<script>
$(function(){

jQuery(function(){

    		jQuery( "#chartdate" ).datepicker({
	  	  minDate: 0, 
		  firstDay: <?php echo $row['var_value']; ?>
	   });	
	   
	   $('#tbl6').bubbletip($('#tbl6_up'));
});

});
  
</script>


<script src="js/jQuery.bubbletip-1.0.6.js" type="text/javascript"></script>
<link href="js/bubbletip/bubbletip.css" rel="stylesheet" type="text/css" />

<div id="chartdate"></div>

<div class="container avails-grid">

	<p class="loading alert">Loading table...</p>

    <div class="table-container">
		<table cellpadding="0" cellspacing="0" border="0" class="table table-condensed avails-table table-bordered">
        	<thead>
            	<tr>
                	<th>&nbsp;</th>
				<?php 
				$time = strtotime($store_hrs['open']);
				$end = strtotime($store_hrs['close']);
				
				while($time<=$end){
					?>
                    <th style="width:70px;"><?php echo date('H:i',$time); ?></th>
                    <?php
					$time = strtotime(date('H:i:s',$time).' + 15 mins');
				}
				?>	
                	<th>&nbsp;</th>
        		</tr>
            </thead>
            <tbody>
            	<?php
				$tables = range(1,$max_table); //30 tables
				$length = strlen(end($tables));
				foreach($tables as $tbl_num){
				?>
                <tr>
                	<td align="center">Table# <?php echo str_pad($tbl_num,$length,'0', STR_PAD_LEFT); ?></td>
				<?php
				$time = strtotime($store_hrs['open']);
				$end = strtotime($store_hrs['close']);
				
				while($time<=$end){
					
					$time_increments = '15';
					$table_block = '';
					if($time==strtotime('10:45:00') && $tbl_num == 6){
						$duration = 120;
						$block_ends = strtotime('10:45:00 + '.$duration.' mins');
						$time_blocks_width = $time_interval_width + ($duration/15) * $time_interval_width - $time_block_left_padding;
						$table_block = '<div class="table_block" style="width:'.$time_blocks_width.'px;" 
											title="Details of Table Resrvation Here" id="tbl6">
											Tbl#'.str_pad($tbl_num,$length,'0', STR_PAD_LEFT).' 10:45&rarr;'.date('H:i',$block_ends).' ['.$duration.'mins]
										</div>';
					}


					if($time==strtotime('12:00:00') && $tbl_num == 14){
						$duration = 45;
						$block_ends = strtotime('12:00:00 + '.$duration.' mins');
						$time_blocks_width = $time_interval_width + ($duration/15) * $time_interval_width - $time_block_left_padding;
						$table_block = '<div class="table_block" style="width:'.$time_blocks_width.'px;" 
											title="Details of Table Resrvation Here">
											Tbl#'.str_pad($tbl_num,$length,'0', STR_PAD_LEFT).' 12:00&rarr;'.date('H:i',$block_ends).' ['.$duration.'mins]
										</div>';
					}


					if($time==strtotime('08:30:00') && $tbl_num == 16){
						$duration = 45;
						$block_ends = strtotime('08:30:00 + '.$duration.' mins');
						$time_blocks_width = $time_interval_width + ($duration/15) * $time_interval_width - $time_block_left_padding;
						$table_block = '<div class="table_block" style="width:'.$time_blocks_width.'px;" 
											title="Details of Table Resrvation Here">
											Tbl#'.str_pad($tbl_num,$length,'0', STR_PAD_LEFT).' 08:30&rarr;'.date('H:i',$block_ends).' ['.$duration.'mins]
										</div>';
					}
					?>
                    <td class="space_time" title="Tbl#<?php echo str_pad($tbl_num,$length,'0', STR_PAD_LEFT).' '.date('H:i',$time); ?>"><?php echo $table_block; ?></td>
                    <?php
					$time = strtotime(date('H:i:s',$time).' + '.$time_increments.' mins');
				}
				?>	
                	<td>&nbsp;</td>
                </tr>	
                <?php	
				}
				?>
            </tbody>
        </table>	    
    </div>


<div id="tbl6_up" style="display:none;">
	<div style="width:300px; overflow:visible;">
    <strong>Table# 6</strong>
    It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).    
	</div>
</div>

</div>
</body>
</html>