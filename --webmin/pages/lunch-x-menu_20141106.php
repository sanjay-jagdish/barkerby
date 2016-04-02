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

        <div style="float:right; width:250px; margin-top: 38px; text-align:right;">
            <?php /* <div style="float:right;" class="btn specific_date" data-rel="<?php echo date("Y-W"); ?>">Specifik Datum</div> */ ?>
            <?php /* <div style="float:right;" class="btn daily" data-rel="<?php echo date("Y-W"); ?>">Dagligen</div> */ ?>
            <?php /* <div style="float:right;" class="btn weekly" data-rel="<?php echo date("Y-W"); ?>">Varje vecka</div> */ ?>
        </div>

    </div>
    <!-- end .page-header -->

    
    <div class="clear"></div>
    
    <div class="page-content">
        
        <!-- start .menulunch -->
        
        <div class="menulunch menutypes">
			
            <style>
				.page-content{
					padding: 0 !important;
				}
				.week_options{
					width: 90px;
					padding: 15px 0;
					text-align: center;
					display: inline-block;
					cursor: pointer;	
				}
				
				#wo-container .week_options{
					color: #fff;
				}
				
				.current{
					background-color:#fff;
					color:#000 !important;	
				}
				
				.menu_main_box{
					/*border-top: solid 4px #393;*/
				}
				
				.menu_description{
					width:700px;
					float:left;	
				}
				
				.menu_items{ clear:both; }
				
				.menu_item{
					padding: 8px;
					background-color:#f4f4f4;
					margin: 10px 0px;
					overflow:hidden;		
				}
				
				.menu_item_desc{
					width: 745px;	 
					float: left;
					margin-right:15px;
				}
				
				.unit_price{ text-align:right; width:90px;}
				
				.menu_item_actions{ float:right; text-align:center; margin: 40px 0 0;}
			
            	.btn{ padding:3px 5px; border-radius: 5px; text-transform:none; }
				.weekly{ color:#fff; background-color:#393; }
				.daily{ color:#fff; background-color:#36C; }
				.specific_date{ color:#fff; background-color:#F60; }
				.weekly:hover{ color:#060 !important; background-color:#6F9 !important; }
				.daily:hover{ color:#039 !important; background-color:#3CF !important; }
				.specific_date:hover{ color:#F30 !important; background-color:#FF6 !important; }
			
				/*
				--------------------ACCORDION--------------------------
				*/
				
				.ui-accordion .ui-accordion-header{
					/*margin-top: -10px !important;*/
				}
				
				.ui-accordion .ui-accordion-header{
					padding: 15px 15px 15px 30px !important;
				}
				
				.ui-state-active, .ui-widget-content .ui-state-active {
					border: 1px solid #EBBC00 !important;
					color: #428bca !important;
					background: #fff !important;
				}
				
				.ui-corner-all {
					border-radius: 0 !important;
				}
				
				.ui-state-default, 
				.ui-widget-content .ui-state-default, 
				.ui-widget-header .ui-state-default{
					background: #fff !important;
					border: none !important;
				}
				
				.ui-state-hover, 
				.ui-widget-content .ui-state-hover, 
				.ui-widget-header .ui-state-hover,
				.ui-state-focus, 
				.ui-widget-content .ui-state-focus,
				.ui-widget-header .ui-state-focus, {
					background: #fff !important;
					border: none !important;
				}
				
				.ui-widget-content{
					border: none !important;
				}
				
				.ui-corner-top{
					border-top-right-radius: 0 !important;
					border-top-left-radius: 0 !important;
				}
				
				.unit_price_existing,
				.unit_price_existing,
				.additional_course_price,
				.additional_course_price_mon,
				.additional_course_price_tue,
				.additional_course_price_wed,
				.additional_course_price_thu,
				.additional_course_price_fri,
				.additional_course_price_sat,
				.additional_course_price_sun{
					width: 80px;
					text-align: right !important; 
					outline: none;
					border: 0;
					font-family:  'Roboto' !important;
					padding: 5px;
				}
				
				.ui-widget input[name="course_name_existing"],
				input[name="additional_course_name"],
				.ui-widget textarea{
					outline: none;
					border: 0;
					padding: 5px 10px;
				}
				
				.ui-widget input[name="course_name_existing"],
				input[name="additional_course_name"]{
					margin-bottom: 4px;
					width: 670px;
					font-style: italic;
				}
				
				
				.menu_item_opt {
					float: right;
					margin: 40px 20px 0 0;
				}
				
				span.week_options{
					padding:0 !important;
				}
				
				#add_course,
				#add_course_mon,
				#add_course_tue,
				#add_course_wed,
				#add_course_thu,
				#add_course_fri,
				#add_course_sat,
				#add_course_sun{
					border: none;
					outline: none;
					color: #fff;
					border-radius: 3px;
					padding: 8px;
					padding-left: 35px;
					background-color: #328ecc;
					font-family: 'Roboto';
					background-image: url('./images/add-course.png');
					background-size: 16px;
					background-repeat: no-repeat;
					background-position: 10px center;
				}
				
				.ui-widget select,
				.menu_item_desc,
				.ui-accordion .ui-accordion-header,
				.ui-widget input[name="course_name_existing"],
				input[name="additional_course_name"],
				.ui-widget textarea,
				.menu_item_opt,
				.unit_price,
				.menu_item_desc,
				.menu_item_actions,
				#accordion, #accordion textarea{
					font-family: 'Roboto';
				}
				
				.ui-state-default .ui-icon {
					background-position: -32px 0px;
				}
            </style>

<?php
//current week
$week_num = date("W");
$year_num = date("Y");
?>

            	<div style="padding: 19px; font-family: 'Roboto';">
                    Denna vecka <?php echo date("Y"); ?>: <b><?php echo $week_num; ?></b> &nbsp;&nbsp;&nbsp;&nbsp;
                </div>
                <div style="padding:0 80px 20px 80px; clear: both; background-color: #efefef;">       
                    <div class="menu_description" style="width:100%">
                        <div style="float:left; margin-right:8px;"></div>
                    </div>
    
                    <div style="clear:both; clear:both;">
                    <br />
                    </div>
    
                    <div style="clear:both;">&nbsp;</div>
    
    
                    <?php				
                    //weeks list
                    $week_count = 10; //total number of weeks for option (starting from the current week)
                    
                    if($_GET['base_week']!=''){
                        $weekly_option = strtotime($_GET['base_week'].' -'.$week_count.' weeks');					
                    }elseif($_GET['max_week']!=''){
                        $weekly_option = strtotime($_GET['max_week'].' -1 week');					
                    }else{
                        $weekly_option = strtotime("-1 weeks");
                    }
    
                    ?>
                    
                    <div id="wo-container" style="background-color: #687174; text-align:center; position:relative">
                    
                    <style>
                        .button_prev_next{ 
                            padding: 11px 10px;
							font-size: 19px;
							position: absolute;
							color: #fff;
                        }
						.button_prev_next:hover{
							color: #000;
							background-color: #D0D0D0;
						}
                    </style>
                    
                    <a class="button_prev_next" href="?page=lunch-menu&parent=lunchmeny&base_week=<?php echo date('Y-m-d',$weekly_option); ?>" style="text-decoration:none; left: 0;">&laquo;</a>
                    <?php
    
                    for($w = 0; $w <= $week_count; $w++){
                        
                        if(date('W',$weekly_option)==$week_num && date('Y',$weekly_option)==$year_num){ $cw='current'; }else{ $cw=''; }
                        echo '<div class="week_options '.$cw.'" data-rel="'.date('Y',$weekly_option).'-'.date('W',$weekly_option).'" id="week_option_'.date('W',$weekly_option).'"> v. '.date('W',$weekly_option).' </div>';
    
                        $weekly_option = strtotime(date('Y-m-d',$weekly_option)." +1 week");								
                        
                    }
                    ?>
    
                    <a class="button_prev_next" href="?page=lunch-menu&parent=lunchmeny&max_week=<?php echo date('Y-m-d',$weekly_option); ?>" style="text-decoration: none; right: 0;">&raquo;</a>
    
                    </div>
                    
                    <div class="menu_main_box">
    
                    </div>
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
	
	  $('.weekly, .week_options').click(function(){
	  
	    var val = $(this).attr('data-rel').split('-');
		var year_num = val[0];
		var week_num = val[1];
			
		load_lunch_menu(year_num,week_num);
	  
	  	$('.week_options').attr('class','week_options');
	  	$('#week_option_'+ week_num).attr("class","week_options current");
	  
	  });	
	   
   });
   
   
   function load_lunch_menu(year_num,week_num,show){
   
   	$.post( "pages/menu_lunch.php", { year_num: year_num, week_num: week_num, show:show })
		.done(function( data ) {
		$( ".menu_main_box").html(data);
	});
   
   }


	$('.weekly').click(function(){
	  
	    var val = $(this).attr('data-rel').split('-');
		var year_num = val[0];
		var week_num = val[1];
			
		load_lunch_menu(year_num,week_num);
	  
	  	$('.week_options').attr('class','week_options');
	  	$('#week_option_'+ week_num).attr("class","week_options current");
	  
	  });	

/*	$('#wo-container').mouseover(function(){
		$('.button_prev_next').css('display','inline-block');
	});
	
	$('#wo-container').mouseout(function(){
		$('.button_prev_next').css('display','none');
	});*/

});
</script>
