<?php
include_once('redirect.php'); 

$year_num = $_GET['year_num'];
$week_num = $_GET['week_num'];

if($year_num==0 && $week_num==0){
	$year_num = date("Y");
	$week_num = date("W");	
}

?>

<div class="page menu-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                	
					echo 'Menyinställningar - Lunch';
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
				.week_options a{
					color: #fff;
					text-decoration:none;
				}
					
				#wo-container .week_options{
					color: #fff;
				}
				
				.current{
					background-color:#fff;
					color:#000 !important;	
				}

				.current a{
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
					width: 75%; 
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
			
            </style>


            	<div style="padding: 19px; font-family: 'Roboto';">Aktuell vecka: <b><?php echo date("W"); ?></b> &nbsp;&nbsp;&nbsp;&nbsp;
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
    
					$offset='';
					
					if($_GET['base_week']!=''){
						$offset = '&base_week='.$_GET['base_week'];
					}			
					if($_GET['max_week']!=''){
						$offset = '&max_week='.$_GET['max_week'];
					}			
			
                    for($w = 0; $w <= $week_count; $w++){
                        
                        if(date('W',$weekly_option)==$week_num && date('Y',$weekly_option)==$year_num){ $cw='current'; }else{ $cw=''; }
                        echo '<div class="week_options '.$cw.'" data-rel="'.date('Y',$weekly_option).'-'.date('W',$weekly_option).'" id="week_option_'.date('W',$weekly_option).'"> 
								<a href="?page=lunch-menu&parent=lunchmeny&year_num='.date('Y',$weekly_option).'&week_num='.date('W',$weekly_option).$offset.'">v. '.date('W',$weekly_option).'</a> </div>';
    
                        $weekly_option = strtotime(date('Y-m-d',$weekly_option)." +1 week");								
                        
                    }
                    ?>
    
                    <a class="button_prev_next" href="?page=lunch-menu&parent=lunchmeny&max_week=<?php echo date('Y-m-d',$weekly_option); ?>" style="text-decoration: none; right: 0;">&raquo;</a>
    
                    </div>
                    
                    <div class="menu_main_box">
    				<?php
						require('pages/menu_lunch.php');
					?>
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
	/*
	$.get( "pages/menu_lunch.php", { year_num: "<?php echo $year_num; ?>", week_num: "<?php echo $week_num; ?>" })
		.done(function( data ) {
		jQuery( ".menu_main_box").html(data);
	});
	*/

   $(function(){
	
	  /*
	  $('.weekly, .week_options').click(function(){
	  
	    var val = $(this).attr('data-rel').split('-');
		var year_num = val[0];
		var week_num = val[1];
			
		load_lunch_menu(year_num,week_num);
	  
	  	$('.week_options').attr('class','week_options');
	  	$('#week_option_'+ week_num).attr("class","week_options current");
	  
	  });	
	  */ 
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
