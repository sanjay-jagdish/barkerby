<?php
//require '../config/config.php';
$show = $_POST['show'];
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
.menu_main_description, 
.course_desc,  
.course_desc_existing, 
.additional_course_desc, 
.course_additional_day{ 
	width: 708px; 
	max-width: 708px; 
	min-width: 708px; 
	height: 80px; 
	font-style: italic;
	background-color:#fff;
}

.course_name_existing{
	padding: 4px 0px;
	width: 708px; 
	max-width: 708px; 
	min-width: 708px; 
	font-style: italic;
	background-color:#fff;
}

.course_name_existing, .additional_course_name{
	padding: 4px 0px;
	min-height:20px;
	width: 708px; 
	max-width: 708px; 
	min-width: 708px; 
	font-style: italic;
	background-color:#fff;
}


.warning{ box-shadow: 0 0 10px #F00 !important; outline: none !important; background-color:#FFC !important; }

#accordion, #accordion textarea { 
	font-size: 12px; 
}

.ui-accordion .ui-accordion-content{
	height:auto !important;			
}

a.link_button:last-child{
	float:left;
}

.link_button{ 
	float: right;
	display: inline-block;
	padding: 12px 0;
	width: 67px;
	text-align: center;
	margin: 15px 0 0 0;
	background-color: #CCC;
	border-radius: 5px;
}

.link_button:hover{
	background-color:#999;
	color: #fff;
	cursor:pointer;	
}

#text_over_menu,
.menu_main_description,
#text_after_menu{
	padding: 7px 5px;
	color: #505458;
	outline: none;
	border-radius: 3px;
	border: solid 1px #ececec;
	font: 12px 'Roboto';
}

#text_over_menu,
#text_after_menu{
	max-width: 600px;
	min-width: 600px;
	font-style: italic !important;
}

.menu_main_description{
	width: 600px;
	max-width: 600px;
	height: 90px;
	min-width: 600px;
	font-style: italic !important;
}

.setting_menu{
	padding: 0 2.2em;
	padding-bottom: 30px;
	background-color: #fff;
}

#message{
	color: #fff; 
	padding: 8px; 
	float: right; 
	width: 149px; 
	margin-top: 5px; 
	text-align: center; 
	font-weight: bold; 
	position: absolute; 
	display: none; 
	background-color: #090;
}

.unit_price_existing{
	text-align:right;
    width: 75px;
    margin-top: 36px;
}
</style>

<?php
$save_button_id = 'lunch_meny_save_all';
	
$menu_week_res = mysql_fetch_assoc($menu_week_qry);

if( strtotime(date('Y-m-d',strtotime($year_num.'W'.$week_num.'7'))) < strtotime(date('Y-m-d')) ){
	$update_lock = 1;
}
?>

<script src="./scripts/ckeditor/ckeditor.js"></script>
<script>
		// The "instanceCreated" event is fired for every editor instance created.
		CKEDITOR.on( 'instanceCreated', function( event ) {
			var editor = event.editor,
				element = editor.element;

			// Customize editors for headers and tag list.
			// These editors don't need features like smileys, templates, iframes etc.
			if ( element.is( 'div' ) ) {
				// Customize the editor configurations on "configLoaded" event,
				// which is fired after the configuration file loading and
				// execution. This makes it possible to change the
				// configurations before the editor initialization takes place.
				editor.on( 'configLoaded', function() {

					// Remove unnecessary plugins to make the editor simpler.
					editor.config.removePlugins = 'flash,format,' +
						'forms,iframe,image,newpage,' +
						'smiley,specialchar,stylescombo,templates';

				});
			}
		});

	CKEDITOR.config.autoParagraph = false;        
</script>



<div style="background-color:#FFC; padding:10px 15px; overflow:hidden;"> OBS! Gäller hela vecka <?php echo $week_num; ?> (mån-fre)
    
    <div style="float:right; margin-top:4px; font-family: 'Roboto'; font-style: italic;">
	<?php
    if($menu_week_num>0){

		if($menu_week_check_num==0){
			
			echo 'Continued from week <span class="week_options" style="padding:none; border:none; margin:0px; width:auto; text-decoration:underline;" data-rel="'.$menu_week_res['week_no'].'-'.$menu_week_res['year_for'].'">'.$menu_week_res['week_no'].' of '.$menu_week_res['year_for'].'</span>. &nbsp;&nbsp;&nbsp;';
		}
    ?>
        <em>Created on:</em> &nbsp;<?php echo $menu_week_res['time_created']; ?> &nbsp;  <em>Last Saved:</em> <?php echo $menu_week_res['last_saved']; ?>
	<?php
	}
	?>    
    </div>
    
</div>

                    
	<div class="menu_items">
	<div class="setting_menu">
    <div style="text-align:right; padding:8px 0px;">
 
     <input type="hidden" id="menu_parameter" value="<?php echo 'W '. $year_num.' '.$week_num; ?>" /> 
    
    <?php
    if($menu_week_num>0){
        
        $save_button_id = 'lunch_meny_update_all';
    ?>    
        <input type="hidden" id="saved_menu" value="<?php echo $menu_week_res['id']; ?>" />
        
        <?php	
        }else{
        ?>
        
        <div style="padding:4px; background-color:#FC9; text-align:center; font-family: 'Roboto';"><font color="red"><strong>* no saved menu for the week
         *</strong></font></div>
        <input type="hidden" id="saved_menu" value="0" />
        <?php	
        }
        ?>  
        
    </div>
    <div style="float:right; width: 170px; text-align:center;">
     	
        <?php
		if($update_lock==0){
		?>
        <div id="message">
 		Successfully Saved.</div>
        <input type="button" value="Spara allt för vecka <?php echo $week_num; ?>" class="btn" id="<?php echo $save_button_id; ?>" style="padding: 15px 25px; margin-top: 40px; font-family: 'Roboto';" />
   		<br />
   		<?php
		}
		?>
        <a class="link_button" id="preview_menu" data-rel="<?php echo $year_num.'-'.$week_num; ?>" title="View menu as seen by visitors.">Preview</a>
   		<a class="link_button" id="pdf_export" data-rel="<?php echo $year_num.'-'.$week_num; ?>" title="Export as PDF" style="float: left;">PDF</a>

    </div>
    

        <table>
            <tr>
                <td align="left" style="font-family: 'Roboto'; color:#000;">Text ovanför menyn:</td>
                <td>
                <div id="text_over_menu" <?php if($update_lock==0){ ?>contenteditable="true"<?php } ?>>
				<?php 
				echo stripslashes($menu_week_res['note_header']); 
				?>
                </div></td>
            </tr>
          <tr>
              <td align="left" style="font-family: 'Roboto'; color:#000;">Menu Description:</td>
              <td><span class="menu_description" style="width:100%">
                <div class="menu_main_description" <?php if($update_lock==0){ echo 'contenteditable="true"'; } ?>><?php echo stripslashes($menu_week_res['description']); ?></div>	
              </span></td>
            </tr>
          <tr>
            <td align="left" style="font-family: 'Roboto'; color:#000;"><span style="clear:both; clear:both;">Text nedanför menyn:</span></td>
            <td><span style="clear:both; clear:both;">
              <div id="text_after_menu" <?php if($update_lock==0){ echo 'contenteditable="true"'; } ?>><?php echo stripslashes($menu_week_res['note_footer']); ?></div>
            </span></td>
          </tr>
        </table>    
    </div>
    <br /><br />
    <span style="font-size: 20px; padding-left:10px; font-family: 'Roboto';">Courses:</span>
    <br /><br />

	<div id="accordion">
	
    <?php
	/*
					( 
						(year_for=".$year_num." AND week_for=".$week_num." )
						OR
						all_in=1	
						
					)
	
	*/
	if($none_all_in==0){
		$all_in_switch = '';
	}else{
		$all_in_switch = ' AND all_in=1 ';	
	}
	
    $courses_sql = "SELECT * FROM menu_lunch_items WHERE 
					menu_id=".$menu_week_res['id']."
					AND specific_day IS NULL 
					".$all_in_switch."
					AND deleted=0
					ORDER BY `order`, id ASC";

    $courses_qry = mysql_query($courses_sql);
    $courses_num = mysql_num_rows($courses_qry);
	
	//echo $courses_sql; //xxx
	?>
	
    <h3>
        Mån-Fre ( 
			<?php 
			echo date('M d',strtotime($year_num.'W'.$week_num.'1'));
			if($menu_week_res['all_in']==0){
				echo ' to  '.date('M d',strtotime($year_num.'W'.$week_num.'5')); 
			}else{
				echo ' onwards';
			}
			?>
            )
        <div style="float:right;"><?php echo ($courses_num+0).' course'; if($courses_num>1 || $courses_num==0){ echo 's'; }; ?></div>
    </h3>
    
    <div>

    <?php     
	if($courses_num>0){

		while($courses_res = mysql_fetch_assoc($courses_qry)){
		?>
	
		<div class="menu_item" id="existing_course_<?php echo $courses_res['id']; ?>">
		
			<?php if($update_lock==0){ ?>
            
            <div class="menu_item_actions">
				<img src="images/delete.png" alt="Radera" onclick="remove_existing_course(<?php echo $courses_res['id']; ?>)"><br /><br />
				Sort/Order<br />
				<select id="sort_<?php echo $courses_res['id']; ?>">
					<option value="0">---</option>
					<?php 
					//if($courses_num==0){ $courses_num=10; }
					
					for($c=1; $c<=$courses_num; $c++){
						$sel = 0;
						if($c==$courses_res['order']){ $sel='selected'; }
					?>
					<option value="<?php echo $c; ?>" <?php echo $sel; ?>><?php echo $c; ?></option>
					<?php	
					}
					?>
				</select> 
			</div>
			
            <?php }//end of if update_lock ?>
			<div class="menu_item_desc">
			  Name: <div class="course_name_existing" id="course_name_existing_<?php echo $courses_res['id']; ?>" <?php if($update_lock==0){ echo 'contenteditable="true"'; } ?>><?php echo stripslashes($courses_res['name']); ?></div><br />
			  Description:<br />
			  <div class="course_desc_existing" id="e_<?php echo $courses_res['id']; ?>" <?php if($update_lock==0){ echo 'contenteditable="true"'; } ?>><?php echo stripslashes($courses_res['description']); ?></div>
			</div>
            
            <div class="menu_item_opt">
				Pris: <input type="text" class="unit_price_existing" value="<?php echo $courses_res['price']; ?>" id="course_price_existing_<?php echo $courses_res['id']; ?>" data-rel="<?php echo $courses_res['id']; ?>" <?php if($update_lock==1){ echo 'readonly="readonly"'; } ?> />&nbsp;<?php echo $currency_res['shortname']; ?>
			
            <br /><br />
            <?php if($update_lock==0){ ?>
            <input type="checkbox" id="allweek_existing_<?php echo $courses_res['id']; ?>" <?php if($courses_res['all_in']==1){ echo 'checked'; } ?> /> Gäller alla veckor.
			<?php }else{ 
			?>
            &lt;Copy to Clipboard&gt;
            <?php
			} 
			?>
            <input type="hidden" id="course_day_existing_<?php echo $courses_res['id']; ?>" 
                value="<?php if(trim($courses_res['specific_day'])!=''){ echo $courses_res['specific_day']; }else{ echo '0'; } ?>" />

        	</div>
        </div>
	
		<?php
		}//looping thru courses for this menu
	
	?>
        
    <?php

	}

	if($courses_num==0 && $update_lock==0){
	?>
	
    <div class="menu_item" id="additional_course"><div class="menu_item_actions"><br /></div><div class="menu_item_desc">
    Name: <div class="additional_course_name" id="additional_course_name" contenteditable="true"></div><br>
    Description:<br><div class="additional_course_desc" contenteditable="true"></div>
    </div><div class="menu_item_opt">Pris: <input type="text" class="additional_course_price" id="additional_course_price_0" /> <?php echo $currency_res['shortname']; ?></div>
    </div>
    <?php
	}
	?>

    <?php if($update_lock==0){ ?>
    
    <div id="menu_items_additional"></div>
    
    <input type="button" id="add_course" value="Add Course for Mån-Fre ( <?php echo date('M d',strtotime($year_num.'W'.$week_num.'1')).' to  '.date('M d',strtotime($year_num.'W'.$week_num.'5')); ?> )" />

	<?php }//show additional button only if update lock in not enabled ?>
    </div>

	<?php
	$first_day = date('Y-m-d',strtotime($year_num.'W'.$week_num.'1'));//strtotime('monday this week');
	$last_day = date('Y-m-d',strtotime($year_num.'W'.$week_num.'7'));//strtotime('saturday this week');
	$the_day = strtotime($first_day);
	
	$week_days = 7;
	
	for($d=1; $d<=$week_days; $d++){

		$courses_sql = "SELECT * FROM menu_lunch_items WHERE 
						menu_id=".$menu_week_res['id']."	
						AND specific_day='".date('D', $the_day)."' 
						".$all_in_switch."
						AND deleted=0
						ORDER BY `order`, id ASC";

		$courses_qry = mysql_query($courses_sql);
		$num_courses = mysql_num_rows($courses_qry);

	?>
    
    <br /><br />
	<h3>
	<?php
	if($menu_week_res['all_in']==0){
		echo dayName(date('D', $the_day)).' ('.date('M d', $the_day).')';
	}else{
		echo dayName(date('D', $the_day)).' (All '.date('l', $the_day).'s starting from '.date('M d', $the_day).')';
	}
	?>
    
    <div style="float:right;"><?php echo ($num_courses+0).' course'; if($num_courses>1 || $num_courses==0){ echo 's'; }; ?></div></h3>
    <div>    	
  	<?php
	
	if($num_courses>0){
	
		while($courses_res = mysql_fetch_assoc($courses_qry)){
	?>	
        <div class="menu_item" id="existing_course_<?php echo $courses_res['id']; ?>">
            
            	<?php if($update_lock==0){ ?>
                <div class="menu_item_actions">
                    <img src="images/delete.png" alt="Radera" onclick="remove_existing_course(<?php echo $courses_res['id']; ?>)"><br /><br />
                    Sort/Order<br />
                    <select id="sort_<?php echo $courses_res['id']; ?>">
                        <option value="0">---</option>
                        <?php 
                        //if($courses_num==0){ $courses_num=10; }
                        
                        for($c=1; $c<=$num_courses; $c++){
                            $sel = 0;
                            if($c==$courses_res['order']){ $sel='selected'; }
                        ?>
                        <option value="<?php echo $c; ?>" <?php echo $sel; ?>><?php echo $c; ?></option>
                        <?php	
                        }
                        ?>
                    </select> 
                </div>
            	<?php } ?>

                <div class="menu_item_desc">
                  Name: <div class="course_name_existing" id="course_name_existing_<?php echo $courses_res['id']; ?>" contenteditable="true"><?php echo stripslashes($courses_res['name']); ?></div><br />
                  Description:<br />
                    <div class="course_desc_existing" id="e_<?php echo $courses_res['id']; ?>" contenteditable="true"><?php echo stripslashes($courses_res['description']); ?></div>
                </div>
                
                <div class="menu_item_opt">
                Pris: <input type="text" class="unit_price_existing" value="<?php echo $courses_res['price']; ?>" id="course_price_existing_<?php echo $courses_res['id']; ?>" data-rel="<?php echo $courses_res['id']; ?>" /> <?php echo $currency_res['shortname']; ?>

 				<br /><br />

            	<?php if($update_lock==0){ ?>
				
	            <input type="checkbox" id="allweek_existing_<?php echo $courses_res['id']; ?>" <?php if($courses_res['all_in']==1){ echo 'checked'; } ?> />&nbsp;Gäller&nbsp;alla&nbsp;<?php echo dayName(date('D', $the_day)); ?>dagar.
                
                <input type="hidden" id="course_day_existing_<?php echo $courses_res['id']; ?>" 
                	value="<?php if(trim($courses_res['specific_day'])!=''){ echo $courses_res['specific_day']; }else{ echo '0'; } ?>" />
    
            	<?php }else{ ?>
                	&lt;Copy to Clipboard&gt;
                <?php } ?>

                </div>
            </div>
	<?php	
		}

	}
	?>      

	<?php if($update_lock==0){ ?>
        
        <div id="menu_items_additional_<?php echo strtolower(date('D', $the_day)); ?>"></div>

 	   <input style="white-space:nowrap !important;" type="button" id="add_course_<?php echo strtolower(date('D', $the_day)); ?>" 
       value="Add Course for <?php 
		if($menu_week_res['all_in']==0){
			echo dayName(date('D', $the_day)).' ('.date('M d', $the_day).')';
		}else{
			echo 'All '.date('l', $the_day).'s';
		}

	   ?>" />     

	<?php }//end-of only show add button if update_lock is not enabled ?>
    
    	</div>

	<?php
	
		$the_day = strtotime(date('Y-m-d', $the_day).' +1 day');
	}	
	?>
    </div>
    <!-- div accordion -->    
                 

</div>

<script>

var add_count = 0;
$('#add_course').on('click', function (e) {
	add_count+=1;
	$( "#menu_items_additional" ).append('<div class="menu_item" id="additional_course_'+add_count+'"><div class="menu_item_actions"><img src="images/delete.png" alt="Radera" onClick="remove_additional_course('+add_count+')"><br /></div><div class="menu_item_desc">Name: <div class="additional_course_name" id="additional_course_name_'+add_count+'"  contenteditable="true"></div><br>Description:<br><div class="additional_course_desc" id="additionalTextarea_'+add_count+'" contenteditable="true"></div></div><div class="menu_item_opt">Pris: <input type="text" class="additional_course_price" id="additional_course_price_'+add_count+'" /> <?php echo $currency_res['shortname']; ?><br /><br /><input type="checkbox" id="allweek_additional_'+add_count+'">Gäller alla veckor.</div></div>');

	CKEDITOR.inline( 'additional_course_name_'+add_count );
	CKEDITOR.inline( 'additionalTextarea_'+add_count );


});


function remove_additional_course(id){

	$( "#additional_course_"+id ).css( "background-color","red" );

	$( "#additional_course_"+id ).toggle( "scale", function() {
		// Animation complete.
		$( "#additional_course_"+id ).remove();
	});

}

<?php
$first_day = strtotime('monday this week');
$last_day = strtotime('friday this week');
$the_day = $first_day;

$week_days = 7;

for($d=1; $d<=$week_days; $d++){
	
?>


var add_count = 0;
$('#add_course_<?php echo strtolower(date('D', $the_day)); ?>').on('click', function (e) {
	add_count+=1;
	$( "#menu_items_additional_<?php echo strtolower(date('D', $the_day)); ?>" ).append('<div class="menu_item" id="additional_course_<?php echo strtolower(date('D', $the_day)); ?>_'+add_count+'"><div class="menu_item_actions"><img src="images/delete.png" alt="Radera" onClick="remove_additional_course_<?php echo strtolower(date('D', $the_day)); ?>('+add_count+')"><br /></div><div class="menu_item_desc">Name: <div class="additional_course_name" id="additional_course_name_<?php echo strtolower(date('D', $the_day)); ?>_'+add_count+'" contenteditable="true"></div><br>Description:<br><div class="course_additional_day" data-rel="<?php echo strtolower(date('D', $the_day)); ?>" id="additionalTextarea_<?php echo strtolower(date('D', $the_day)); ?>_'+add_count+'" contenteditable="true"></div></div><div class="menu_item_opt">Pris: <input type="text" class="additional_course_price_<?php echo strtolower(date('D', $the_day)); ?>" id="additional_course_price_<?php echo strtolower(date('D', $the_day)); ?>_'+add_count+'" /> <?php echo $currency_res['shortname']; ?><br /><br /><input type="checkbox" id="all_<?php echo strtolower(date('D', $the_day)); ?>_additional_'+add_count+'">Gäller alla <?php echo dayName(date('D', $the_day)); ?>dagar.</div></div></div>');

	CKEDITOR.inline( 'additional_course_name_<?php echo strtolower(date('D', $the_day)); ?>_'+add_count );
	CKEDITOR.inline( 'additionalTextarea_<?php echo strtolower(date('D', $the_day)); ?>_'+add_count );

});


function remove_additional_course_<?php echo strtolower(date('D', $the_day)); ?>(id){

	$( "#additional_course_<?php echo strtolower(date('D', $the_day)); ?>_"+id ).css( "background-color","red" );

	$( "#additional_course_<?php echo strtolower(date('D', $the_day)); ?>_"+id ).toggle( "scale", function() {
		// Animation complete.
		$( "#additional_course_<?php echo strtolower(date('D', $the_day)); ?>_"+id ).remove();
	});

}


<?php
	$the_day = strtotime(date('Y-m-d', $the_day).' +1 day');
}

?>

function remove_existing_course(id){
	
	$( "#existing_course_"+id ).css( "background-color","red" );

	$( "#existing_course_"+id ).toggle( "scale", function() {
		// Animation complete.
		$( "#existing_course_"+id ).remove();
	});

}

$('#lunch_meny_update_all').on('click', function (e) {
	
	var menu_id = $('#saved_menu').val();
	var menu_parameter = $('#menu_parameter').val();
	
	var errors = 0;
	//***variable
	
	var allweek=0;
	
	if($('#allweek').prop('checked')){ allweek=1; }

	var text_over_menu = $('#text_over_menu').html().trim();
		
	//***variable
	var menu_main_description = $('.menu_main_description').html().trim();


	var items = '';
	var existing_names = '';
	var existing_courses = '';
	var existing_prices = '';
	var existing_sorts = '';
	var existing_allweeks = '';
	var existing_separator = '';
	var existing_day = '';
	var cnt=0;

	var day = '';
	var course_des= '';
	var id = '';
	var id_arr = '';
	
	var course_add_day= '';
	var course_add_name = '';
	var course_add_desc = '';
	var course_add_price = '';
	var add_separator = '';
	var name_allin_additional = '';
	var course_allin_additional = '';
	var course_day_additional = '';
	var course_name_additional = '';
	var course_desc_additional = '';
	var course_price_additional = '';
	
	var allin = 0;
	
	$(".course_additional_day").each(function(){
		
		
		id = $(this).attr('id');
		id_arr = id.split('_');
		day = $(this).attr('data-rel');
		course_name = $('#additional_course_name_'+day+'_'+id_arr[2]).html();
		course_desc = $('#additionalTextarea_'+day+'_'+id_arr[2]).html();
		course_prc = $('#additional_course_price_'+day+'_'+id_arr[2]).val();

		allin = 0; 
		
		if( $('#all_'+day+'_additional_'+id_arr[2]).is(':checked') ){
			allin = 1;
		}

		if($('.course_additional_day').length>1 && cnt>0){
			add_separator = '<^>';	
		}
		
		course_allin_additional = course_allin_additional + add_separator + allin;
		
		course_day_additional = course_day_additional + add_separator + day;
		course_name_additional = course_name_additional + add_separator + course_name;
		course_desc_additional = course_desc_additional + add_separator + course_desc;

		if(course_prc>0){
			$('#additional_course_price_'+day+'_'+id_arr[2]).removeClass('warning');		
			course_price_additional = course_price_additional + add_separator + course_prc;
		}else{
			course_price_additional = course_price_additional + add_separator + '0';
		}

		cnt++;
	});
	
	
	//console.log('day: '+course_day_additional);
	//console.log('desc: '+course_desc_additional);
	//console.log('price: '+course_price_additional);
	//return false;
	
	
	var cnt=0;
	
	$(".course_desc_existing").each(function(){
	
		var val_desc = $(this).html().trim();
		var id = $(this).attr('id');
		var id_data = id.split('_');
		var name = $('#course_name_existing_'+id_data[1]).html();		
		var price = $('#course_price_existing_'+id_data[1]).val();		
		var specific_day = $('#course_day_existing_'+id_data[1]).val();		
		var allweek_existing = 0;
		
		if( $('#allweek_existing_'+id_data[1]).is(':checked') ){
			allweek_existing = 1;
		}		
		
		if($('.course_desc_existing').length>1 && cnt>0){
			existing_separator = '<^>';	
		}

		existing_allweeks = existing_allweeks + existing_separator + allweek_existing;
		existing_day = existing_day + existing_separator + specific_day;
		
		items = items+existing_separator+id_data[1];
		existing_sorts = existing_sorts+existing_separator+$('#sort_'+id_data[1]).val(); 
		
			
		if(price>0){
			$('#course_price_existing_'+id_data[1]).removeClass('warning');		
			existing_prices = existing_prices+existing_separator+price; 			
		}else{
			existing_prices = existing_prices+existing_separator+'0';
		}
		
		if(val_desc==''){
			errors++;
			$(this).addClass('warning');
		}else{
			$(this).removeClass('warning');			
			existing_courses = existing_courses+existing_separator+val_desc; 		
		}	

		if(name==''){
			errors++;
			$('#course_name_existing_'+id_data[1]).addClass('warning');
		}else{
			$('#course_name_existing_'+id_data[1]).removeClass('warning');			
			existing_names = existing_names+existing_separator+name; 		
		}	
	
	cnt++;
	});		

	var names = '';
	var courses = '';
	var prices = '';
	var separator = '';
	var cnt=0;

	$(".additional_course_desc").each(function(){
	
		var val_desc = $(this).html().trim();
		var id = $(this).attr('id');
		var id_data = id.split('_');
		var name = $('#additional_course_name_'+id_data[1]).html();		
		var price = $('#additional_course_price_'+id_data[1]).val();		

		allin = 0;
		
		if($('#all_week_additional_'+id_arr[2]).is(':checked')){
			all_in = 1;
		}
				
		if($(".additional_course_desc").length && cnt>0){
			separator = '<^>';	
		}

		course_allin_additional = course_allin_additional + separator + allin;

		if(price>0){
			$('#additional_course_price_'+id_data[1]).removeClass('warning');		
			prices = prices+separator+price; 		
		}else{
			//errors++;
			//$('#additional_course_price_'+id_data[1]).addClass('warning');
			//$(this).addClass('warning');
			prices = prices+separator+'0';
		}
		
		if(val_desc==''){
			errors++;
			$(this).addClass('warning');
		}else{
			$(this).removeClass('warning');			
			courses = courses+separator+val_desc; 		
		}	

		if(name==''){
			errors++;
			$('#additional_course_name_'+id_data[1]).addClass('warning');
		}else{
			$('#additional_course_name_'+id_data[1]).removeClass('warning');			
			names = names+separator+name; 		
		}	
	
	cnt++;
	});		

	var text_after_menu = $('#text_after_menu').html().trim();
			
	//alert('id:'+menu_id+',all_week:'+allweek+',menu_parameter:'+menu_parameter+',header_text:'+text_over_menu+',menu_description:'+menu_main_description+',courses:'+courses+',prices:'+prices+',e_courses:'+existing_courses+',e_prices:'+existing_prices+',e_items:'+items+',text_after_menu:'+text_after_menu);
	
	//return false;
		
	if(errors>0){
		alert('Some required fields are empty, they are highlighted with red color.');	
	}else{	
			
		var menu_parameter = $('#menu_parameter').val();

		$.post( "actions/menu_lunch_update.php", { 
				id:menu_id,
				menu_parameter: menu_parameter,  
				header_text: text_over_menu,
				menu_description: menu_main_description,
				names: names,
				courses: courses,
				prices: prices, 
				e_names: existing_names,
				e_courses: existing_courses,
				e_prices: existing_prices, 
				e_items:items,
				e_sorts:existing_sorts,
				e_days:existing_day,
				course_day_addtl:course_day_additional,
				course_name_addtl:course_name_additional,
				course_desc_addtl:course_desc_additional,
				course_price_addtl:course_price_additional,
				name_allin_additional:course_allin_additional,
				course_allin_additional:course_allin_additional,
				existing_allweeks:existing_allweeks,
				text_after_menu: text_after_menu
			})
			.done(function( data ){
				var resulta = data.split('|');
				//alert('Result: '+resulta[1]);
				if(resulta[0]==1){
					var menu_params = menu_parameter.split(' ');

						$("#message").fadeIn('slow');
						setTimeout(
							function() 
							{
								$("#message").fadeOut('slow');
							}, 10000
						);
						setTimeout(
							function() 
							{
								window.location.reload();
							}, 500
						);		
				}else{
						$("#message").css('background-color','red');
						$("#message").html(data);
						$("#message").fadeIn('slow');
						setTimeout(
							function() 
							{
								//$("#message").fadeOut('slow');
							}, 10000
						);
					
				}
		});		

	}
});

$('#preview_menu').on('click', function (e) {
		
		var data_rel = $(this).attr('data-rel');
		var data_array = data_rel.split('-');
		window.open('http://<?php echo $_SERVER['HTTP_HOST']; ?>/webmin/pages/preview_lunch_meny.php?year='+data_array[0]+'&week='+data_array[1], '_blank');

});

$('#pdf_export').on('click', function (e) {
		
		var data_rel = $(this).attr('data-rel');
		var data_array = data_rel.split('-');
		window.open('http://<?php echo $_SERVER['HTTP_HOST']; ?>/webmin/pages/pdf_lunch_meny.php?year='+data_array[0]+'&week='+data_array[1]+'&pdf=1', '_blank');

});


$( "#accordion" ).accordion({
	collapsible: true
});
</script>