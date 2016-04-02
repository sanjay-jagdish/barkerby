<?php
 include_once('redirect.php'); 
?>

<div class="page menu-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                	
					echo 'Menyinställningar';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
        <!--<div class="page-header-right">
        	<a href="?page=menu&subpage=add-menu" class="add-menu">Skapa ny</a>
        </div>-->
        <!-- end .page-header-right -->
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
        
        <!-- start .menulunch -->
        
        <div class="menulunch menutypes">
			
            <style>
				.week_options{
					width: 85px;
					padding:2px;
					text-align: center;
					display: inline-block;
					border-left:solid thin #CCC;	
					border-top:solid thin #CCC;	
					border-right:solid thin #CCC;	
					margin:0px 0px 0px 8px;
					cursor: pointer;
					border-radius: 5px 5px 0px 0px;
				}
				
				.current{
					background-color:#393;
					color:#FFF;	
				}
				
				.menu_main_box{
					border-top: solid 4px #393;
				}
				
				.menu_description{
					width:700px;
					float:left;	
				}
				
				.menu_items{ clear:both; }
				
				.menu_item{
					padding: 5px;
					background-color:#CCC;
					margin: 10px 0px;
					overflow:hidden;		
				}
				
				.menu_item_desc{
					width:800px !important;	 
					float: left;
					margin-right:15px;
				}
				
				.unit_price{ text-align:right; width:90px; }
				
				.menu_item_actions{ float:right; text-align:center; }
			
            	.btn{ padding:3px 5px; margin-right:3px; float:left; border-radius: 5px; text-transform:none; }
				.weekly{ color:#fff; background-color:#393; }
				.daily{ color:#fff; background-color:#36C; }
				.specific_date{ color:#fff; background-color:#F60; }
				.weekly:hover{ color:#000 !important; background-color:#F90 !important; }
				.daily:hover{ color:#000 !important; background-color:#3CF !important; }
				.specific_date:hover{ color:#000 !important; background-color:#F60; !important; }
			
            </style>

<?php
//current week
$week_num = date("W");
$year_num = date("Y");
?>
            
            	<div style="float:left;">
                    Denna vecka <?php echo date("Y"); ?>: <b><?php echo $week_num; ?></b> &nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            
            	<div style="float:right; width:250px; text-align:right;">
                	<div class="btn weekly">Varje vecka</div>
                    <div class="btn daily">Dagligen</div>
                    <div class="btn specific_date">Specifik Datum</div>
                </div>
            
               	<div style="clear:both;">&nbsp;</div>

                <?php				
				//weeks list
				$week_count = 9; //total number of weeks for option (starting from the current week)
				$week_last = -1; //number of weeks from the past
				
				?>
                
                <div style="min-width:1000px; padding-left:12px;">
        		<?php
				$cw='';
				for($w = $week_last; $w <= $week_count; $w++){
					
					$weekly_option = strtotime($w." weeks");
					
					if(date('W',$weekly_option)==$week_num){ $cw='current'; }else{ $cw=''; }
					echo '<div class="week_options '.$cw.'" data-rel="'.date('Y',$weekly_option).'-'.date('W',$weekly_option).'" id="week_option_'.date('W',$weekly_option).'"> v. '.date('W',$weekly_option).' </div>';
				}
				?>
                </div>
                
                <div class="menu_main_box">

                </div>

        	<br /><br />

        </div>
        
        <!-- end .menulunch -->        
    </div>
</div>


<script>
jQuery( document ).ready(function() {
	// Run code

	$.post( "pages/menu_lunch.php", { year_num: "<?php echo $year_num; ?>", week_num: "<?php echo $week_num; ?>" })
		.done(function( data ) {
		jQuery( ".menu_main_box").html(data);
	});
	

   $(function(){
	
	  $('.week_options').click(function(){
	  
	    var val = $(this).attr('data-rel').split('-');
		var year_num = val[0];
		var week_num = val[1];
			
		load_lunch_menu(year_num,week_num);
	  
	  	$('.week_options').attr('class','week_options');
	  	$('#week_option_'+ week_num).attr("class","week_options current");
	  
	  });	
	   
   });
   
   function load_lunch_menu(year_num,week_num){
   
   	$.post( "pages/menu_lunch.php", { year_num: year_num, week_num: week_num })
		.done(function( data ) {
		$( ".menu_main_box").html(data);
	});
   
   }



});	
</script>
