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
	background-color:#fff;
	font-family:helvetica neue,helvetica,arial,sans-serif;
	font-size:16px;
}

.course_name_existing{
	padding: 4px 0px;
	width: 708px; 
	max-width: 708px; 
	min-width: 708px; 
	background-color:#fff;
	font-family:helvetica neue,helvetica,arial,sans-serif;
	font-size:16px;
}

.course_name_existing, .additional_course_name{
	padding: 4px 0px;
	min-height:20px;
	width: 708px; 
	max-width: 708px; 
	min-width: 708px; 
	background-color:#fff;
 	color: #580605; 
	font-family: Lato,sans-serif;
	font-weight: 700; 
	font-style: normal; 
	font-size: 2rem;
}


.warning{ box-shadow: 0 0 10px #F00 !important; outline: none !important; background-color:#FFC !important; }

#accordion, #accordion textarea { 
	font-size: 12px; 
}

#accordion textarea{
	color:#000000 !important; font-family:helvetica neue,helvetica,arial,sans-serif !important; font-size:16px !important; font-style:normal !important;	
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
	padding: 12px 12px;
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
	outline: none;
	border-radius: 3px;
	border: solid 1px #ececec;
	color:#000000 !important; font-family:helvetica neue,helvetica,arial,sans-serif !important; font-size:16px !important; font-style:normal !important;	
}

#text_over_menu,
#text_after_menu{
	max-width: 600px;
	min-width: 600px;
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

.paste_from_clip{ float:right; clear:right; }

h4{ line-height: 16px !important; }

.lmtakeaway{
	margin: 10px 0 0;
}

.lmlabels{
	display:block;
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
						'smiley,specialchar,templates';

				});
			}
		});

	CKEDITOR.config.autoParagraph = false;    


// The set of styles for the Styles combo
CKEDITOR.stylesSet.add( 'default',
[
	// Lunch Meny
	{ name : 'Course Title'		 , element : 'div', styles : { 'color' : '#580605', 'font-family' : 'Lato,sans-serif', 'font-weight' : '700', 'font-style' : 'normal' , 'font-size' : '2rem' } },
	{ name : 'Course Description', element : 'p', styles : { 'color' : '#000000' , 'font-family' : 'helvetica neue,helvetica,arial,sans-serif' , 'font-style' : 'normal' , 'font-size' : '16px' } },

	// Inline Styles
	{ name : 'Marker: Yellow'	, element : 'span', styles : { 'background-color' : 'Yellow' } },
	{ name : 'Marker: Green'	, element : 'span', styles : { 'background-color' : 'Lime' } },

	// Object Styles
	{
		name : 'Image on Left',
		element : 'img',
		attributes :
		{
			'style' : 'padding: 5px; margin-right: 5px',
			'border' : '2',
			'align' : 'left'
		}
	}
]);	    
</script>



<div style="background-color:#FFC; padding:10px 15px; overflow:hidden; display:none;"> OBS! Gäller hela vecka <?php echo $week_num; ?> (mån-fre)
    
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
        
        <div style="padding:4px; background-color:#FC9; text-align:center; font-family: 'Roboto';"><font color="red"><strong>* Ingen lunchmeny denna vecka
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
 		Sidan är uppdaterad.</div>
        <input type="button" value="Uppdatera vecka <?php echo $week_num; ?>" class="btn" id="<?php echo $save_button_id; ?>" style="padding: 15px 25px; margin-top: 40px; font-family: 'Roboto';" />
   		<br />
   		<?php
		}
		?>
        <a class="link_button" id="preview_menu" data-rel="<?php echo $year_num.'-'.$week_num; ?>" title="View menu as seen by visitors.">Förhandsvisning</a>
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
              <td align="left" style="font-family: 'Roboto'; color:#000;">Meny presentation:</td>
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
    <span style="font-size: 20px; padding-left:10px; font-family: 'Roboto';">Lunchrätter:</span>
    <br /><br />

	<div id="accordion">
	
    <?php
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
			echo date('d F',strtotime($year_num.'W'.$week_num.'1'));
			if($menu_week_res['all_in']==0){
				echo ' till  '.date('d F',strtotime($year_num.'W'.$week_num.'5')); 
			}else{
				echo ' onwards';
			}
			?>
            )
        <div style="float:right;"><?php echo ($courses_num+0).' rätt'; if($courses_num>1 || $courses_num==0){ echo 'er'; }; ?></div>
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
				Ordningsföljd<br />
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
			<div class="menu_item_desc">Rätt: <div class="course_name_existing" id="course_name_existing_<?php echo $courses_res['id']; ?>" <?php if($update_lock==0){ echo 'contenteditable="true"'; } ?>><?php echo stripslashes($courses_res['name']); ?></div><br />
			  Beskrivning:<br />
			  <div class="course_desc_existing" id="e_<?php echo $courses_res['id']; ?>" <?php if($update_lock==0){ echo 'contenteditable="true"'; } ?>><?php echo stripslashes($courses_res['description']); ?></div>
			</div>
            
            <div class="menu_item_opt">
				Pris: <input type="text" class="unit_price_existing" value="<?php echo $courses_res['price']; ?>" id="course_price_existing_<?php echo $courses_res['id']; ?>" data-rel="<?php echo $courses_res['id']; ?>" <?php if($update_lock==1){ echo 'readonly="readonly"'; } ?> />&nbsp;<?php echo $currency_res['shortname']; ?>
                
                <div class="lmtakeaway">
                  <ul>
               	    <li><input type="checkbox" class="lmcbox_existing" id="lmcbox_<?php echo $courses_res['id']; ?>" data-rel="lmlabel<?php echo $courses_res['id']; ?>" data-id="lmtapris<?php echo $courses_res['id']; ?>" <?php if($courses_res['takeaway']==1){ echo 'checked'; } ?> onclick="lmfield('lmcbox_<?php echo $courses_res['id']; ?>')"  <?php if($update_lock==1){ echo 'readonly="readonly"'; } ?>><label for="lmcbox_<?php echo $courses_res['id']; ?>">Take away</label></li>
                        <li class="lmlabels lmlabel<?php echo $courses_res['id']; ?>" <?php if($courses_res['takeaway']==1){ echo 'style="display:block !important;"'; }else{ echo 'style="display:none !important;"'; } ?>>Take away Pris: <input type="text" id="lmta_price_<?php echo $courses_res['id']; ?>" class="lmtapris_existing lmtapris<?php echo $courses_res['id']; ?>" value="<?php echo $courses_res['takeaway_price']; ?>"  <?php if($update_lock==1){ echo 'readonly'; } ?> style="text-align:right;"></li>
                    </ul>
                </div>
			
           <br />
            <?php if($update_lock==0){ ?>
            <input type="checkbox" id="allweek_existing_<?php echo $courses_res['id']; ?>" <?php if($courses_res['all_in']==1){ echo 'checked'; } ?> /> Gäller alla veckor.
			<?php }else{ 
			?>
            <span class="copy_to_clip link_button" id="<?php echo $courses_res['id']; ?>" style="text-decortion:none !important;">Kopiera</span>
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
    Rätt: <div class="additional_course_name" id="additional_course_name_0" contenteditable="true"></div><br>
    Beskrivning:<br><div class="additional_course_desc" id="additionalTextarea_0" contenteditable="true"></div>
    </div><div class="menu_item_opt">Pris: <input type="text" class="additional_course_price" id="additional_course_price_0" /> <?php echo $currency_res['shortname']; ?>
	<div class="lmtakeaway"><ul><li><input class="lmcbox_additional" id="almcbox0" data-rel="almlabel0" data-id="almtapris0" onclick="lmfield('almcbox0')" type="checkbox"><label for="almcbox0">Take away</label></li><li class="lmlabels almlabel0" style="display:none;">Take away Pris: <input class="lmtapris_additional almtapris0" type="text" style="text-align:right;"></li></ul></div>    
    </div>
		<br /><br /><br /><br /><br />
        <span class="paste_from_clip link_button" onclick="ccbd(0)" id="ccbd_0">Klistra in</span>

    </div>
    <?php
	}
	?>

    <?php if($update_lock==0){ ?>
    
    <div id="menu_items_additional"></div>
    
    <input type="button" id="add_course" value="Lägg till rätt för Mån-Fre ( <?php echo date('d M',strtotime($year_num.'W'.$week_num.'1')).' till  '.date('d M',strtotime($year_num.'W'.$week_num.'5')); ?> )" />

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
		echo dayName(date('D', $the_day)).' ('.date('d M', $the_day).')';
	}else{
		echo dayName(date('D', $the_day)).' (All '.date('l', $the_day).'s starting from '.date('d M', $the_day).')';
	}
	?>
    
    <div style="float:right;"><?php echo ($num_courses+0).' rätt'; if($num_courses>1 || $num_courses==0){ echo 'er'; }; ?></div></h3>
    <div>    	
  	<?php
	
	if($num_courses>0){
	
		while($courses_res = mysql_fetch_assoc($courses_qry)){
	?>	
        <div class="menu_item" id="existing_course_<?php echo $courses_res['id']; ?>">
            
            	<?php if($update_lock==0){ ?>
                <div class="menu_item_actions">
                    <img src="images/delete.png" alt="Radera" onclick="remove_existing_course(<?php echo $courses_res['id']; ?>)"><br /><br />
                    Ordningsföljd<br />
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

                <div class="menu_item_desc">Rätt: <div class="course_name_existing" id="course_name_existing_<?php echo $courses_res['id']; ?>" contenteditable="true"><?php echo stripslashes($courses_res['name']); ?></div><br />
                  Beskrivning:<br />
                    <div class="course_desc_existing" id="e_<?php echo $courses_res['id']; ?>" contenteditable="true"><?php echo stripslashes($courses_res['description']); ?></div>
                </div>
                
                <div class="menu_item_opt">
                Pris: <input type="text" class="unit_price_existing" value="<?php echo $courses_res['price']; ?>" id="course_price_existing_<?php echo $courses_res['id']; ?>" data-rel="<?php echo $courses_res['id']; ?>" /> <?php echo $currency_res['shortname']; ?>

				<div class="lmtakeaway">
                	<ul>
                    	<li><input type="checkbox" class="lmcbox_existing" id="lmcbox_<?php echo $courses_res['id']; ?>" data-rel="lmlabel<?php echo $courses_res['id']; ?>" data-id="lmtapris<?php echo $courses_res['id']; ?>" <?php if($courses_res['takeaway']==1){ echo 'checked="checked"'; } ?> onclick="lmfield('lmcbox_<?php echo $courses_res['id']; ?>')"  <?php if($update_lock==1){ echo 'disabled="disabled"'; } ?>><label for="lmcbox_<?php echo $courses_res['id']; ?>">Take away</label></li>
                        <li class="lmlabels lmlabel<?php echo $courses_res['id']; ?>" <?php if($courses_res['takeaway']==1){ echo 'style="display:block !important"'; }else{ echo 'style="display:none !important"'; } ?>>Take away Pris: <input type="text"  <?php if($update_lock==1){ echo 'readonly="readonly"'; } ?> id="lmta_price_<?php echo $courses_res['id']; ?>" class="lmtapris<?php echo $courses_res['id']; ?>" value="<?php echo $courses_res['takeaway_price']; ?>" style="text-align:right;"></li>
                    </ul>
                </div>
 				
                <br />

            	<?php if($update_lock==0){ ?>
				
	            <input type="checkbox" id="allweek_existing_<?php echo $courses_res['id']; ?>" <?php if($courses_res['all_in']==1){ echo 'checked'; } ?> />&nbsp;Gäller&nbsp;alla&nbsp;<?php echo dayName(date('D', $the_day)); ?>dagar.
                
                <input type="hidden" id="course_day_existing_<?php echo $courses_res['id']; ?>" 
                	value="<?php if(trim($courses_res['specific_day'])!=''){ echo $courses_res['specific_day']; }else{ echo '0'; } ?>" />
    
            	<?php }else{ ?>
			            <span class="copy_to_clip link_button" id="<?php echo $courses_res['id']; ?>" style="text-decortion:none !important;">Kopiera</span>
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
       value="Lägg till rätt för <?php 
		if($menu_week_res['all_in']==0){
			echo dayName(date('D', $the_day)).' ('.date('d M', $the_day).')';
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

function lmfield(id){
	
	var val = $('#'+id).prop('checked');
	var thelabel = $('#'+id).attr('data-rel');
	var theprice = $('#'+id).attr('data-id');
	
	if(val){
		$('.'+thelabel).fadeIn();
	}
	else{
		$('.'+thelabel).fadeOut();
		//$('.'+theprice).val('');
	}
	
}


var add_count = 0;
$('#add_course').on('click', function (e) {
	add_count+=1;
	$( "#menu_items_additional" ).append('<div class="menu_item" id="additional_course_'+add_count+'"><div class="menu_item_actions"><img src="images/delete.png" alt="Radera" onClick="remove_additional_course('+add_count+')"><br /></div><div class="menu_item_desc">Rätt: <div class="additional_course_name" id="additional_course_name_'+add_count+'"  contenteditable="true"></div><br>Beskrivning:<br><div class="additional_course_desc" id="additionalTextarea_'+add_count+'" contenteditable="true"></div></div><div class="menu_item_opt">Pris: <input type="text" class="additional_course_price" id="additional_course_price_'+add_count+'" /> <?php echo $currency_res['shortname']; ?><div class="lmtakeaway"><ul><li><input type="checkbox" class="lmcbox" id="almcbox'+add_count+'" data-rel="almlabel'+add_count+'" data-id="almtapris'+add_count+'" onclick="lmfield(\'almcbox'+add_count+'\')"><label for="almcbox'+add_count+'">Take away</label></li><li class="lmlabels almlabel'+add_count+'" style="display:none;">Take away Pris: <input type="text" class="lmtapris almtapris'+add_count+'" style="text-align:right;"></li></ul></div><br /><input type="checkbox" id="allweek_additional_'+add_count+'">Gäller alla veckor.</div><span class="link_button paste_from_clip" onclick="ccbd('+add_count+')" id="ccbd_'+add_count+'">Klistra in</span></div>');

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
	$( "#menu_items_additional_<?php echo strtolower(date('D', $the_day)); ?>" ).append('<div class="menu_item" id="additional_course_<?php echo strtolower(date('D', $the_day)); ?>_'+add_count+'"><div class="menu_item_actions"><img src="images/delete.png" alt="Radera" onClick="remove_additional_course_<?php echo strtolower(date('D', $the_day)); ?>('+add_count+')"><br /></div><div class="menu_item_desc">Rätt: <div class="additional_course_name" id="additional_course_name_<?php echo strtolower(date('D', $the_day)); ?>_'+add_count+'" contenteditable="true"></div><br>Beskrivning:<br><div class="course_additional_day" data-rel="<?php echo strtolower(date('D', $the_day)); ?>" id="additionalTextarea_<?php echo strtolower(date('D', $the_day)); ?>_'+add_count+'" contenteditable="true"></div></div><div class="menu_item_opt">Pris: <input type="text" class="additional_course_price_<?php echo strtolower(date('D', $the_day)); ?>" id="additional_course_price_<?php echo strtolower(date('D', $the_day)); ?>_'+add_count+'" /> <?php echo $currency_res['shortname']; ?><div class="lmtakeaway"><ul><li><input type="checkbox" class="lmcbox" id="almcbox'+add_count+'" data-rel="almlabel'+add_count+'" data-id="almtapris'+add_count+'" onclick="lmfield(\'almcbox'+add_count+'\')"><label for="almcbox'+add_count+'">Take away</label></li><li class="lmlabels almlabel'+add_count+'" style="display:none;">Take away Pris: <input type="text" class="lmtapris almtapris'+add_count+'" style="text-align:right;" ></li></ul></div><br /><input type="checkbox" id="all_<?php echo strtolower(date('D', $the_day)); ?>_additional_'+add_count+'">Gäller alla <?php echo dayName(date('D', $the_day)); ?>dagar.</div><span class="link_button paste_from_clip" onclick="ccbd_d(\'<?php echo strtolower(date('D', $the_day)); ?>_'+add_count+'\')" id="ccbd_'+add_count+'">Klistra in</span></div></div>');

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

	var existing_lmta_box = '';
	var existing_lmta_price = '';

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

	var lmta_box_additional = '';
	var lmta_price_additional = '';

	var lmta_box_existing = '';
	var lmta_price_existing = '';
		
	var allin = 0;
	var lmta_check = 0;
	var lmta_price = 0;
	
	var parameter_check = 0;
	
	$(".course_additional_day").each(function(){
		
		id = $(this).attr('id');
		id_arr = id.split('_');
		day = $(this).attr('data-rel');
		course_name = $('#additional_course_name_'+day+'_'+id_arr[2]).html();
		course_desc = $('#additionalTextarea_'+day+'_'+id_arr[2]).html();
		course_prc = $('#additional_course_price_'+day+'_'+id_arr[2]).val();

		allin = 0; 
		
		parameter_check = 0;
		
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

		lmta_check = 0;
		
		if( $('#almcbox'+id_arr[2]).is(':checked') ){
			lmta_check = 1;
		}

		lmta_box_additional = lmta_box_additional + add_separator + lmta_check;
		
		if( $('.almtapris'+id_arr[2]).val() > 0 ){
			lmta_price = $('.almtapris'+id_arr[2]).val();
		}else{
			lmta_price = 0;
		}
		
		lmta_price_additional = lmta_price_additional + add_separator + lmta_price;

		if(course_prc>0){

			$('#additional_course_price_'+day+'_'+id_arr[2]).removeClass('warning');		
			course_price_additional = course_price_additional + add_separator + course_prc;
			
			var val_desc_chk = $('#additionalTextarea_'+day+'_'+id_arr[2]).text().trim();
			var val_name_chk = $('#additional_course_name_'+day+'_'+id_arr[2]).text().trim();
				
			if(val_desc_chk==''){ parameter_check++; }
			if(val_name_chk==''){ parameter_check++; }			
		
			if(parameter_check==2){ 
				errors++; 
				if(val_name_chk==''){ $('#additional_course_name_'+day+'_'+id_arr[2]).addClass('warning'); }
				if(val_desc_chk==''){ $('#additionalTextarea_'+day+'_'+id_arr[2]).addClass('warning'); }			
			}
		
		}else{
			course_price_additional = course_price_additional + add_separator + '0';
		}

		cnt++;
	});
	
	//console.log('BOX: '+lmta_box_additional_day);
	//console.log('PRICE: '+lmta_price_additional_day);
	
	$(".course_desc_existing").each(function(){
	
		var val_desc = $(this).html().trim();
		var id = $(this).attr('id');
		var id_data = id.split('_');
		var name = $('#course_name_existing_'+id_data[1]).html();		
		var price = $('#course_price_existing_'+id_data[1]).val();		
		var specific_day = $('#course_day_existing_'+id_data[1]).val();		
		var allweek_existing = 0;
		
		parameter_check=0;
		
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
			

		lmta_check = 0;
		
		if( $('#lmcbox_'+id_data[1]).is(':checked') ){
			lmta_check = 1;
		}

		lmta_box_existing = lmta_box_existing + existing_separator + lmta_check;
		
		if( $('#lmta_price_'+id_data[1]).val() > 0 ){
			lmta_price = $('#lmta_price_'+id_data[1]).val();
		}else{
			lmta_price = 0;
		}
		
		lmta_price_existing = lmta_price_existing + existing_separator + lmta_price;


		if(price>0){

			$('#course_price_existing_'+id_data[1]).removeClass('warning');		
			existing_prices = existing_prices+existing_separator+price; 			

			var val_desc_chk = $('#course_name_existing_'+id_data[1]).text().trim();
			var val_name_chk = $('#e_'+id_data[1]).text().trim();
				
			if(val_desc_chk==''){ parameter_check++; }
			if(val_name_chk==''){ parameter_check++; }			
	
			if(parameter_check==2){ 
				errors++; 
				if(val_desc_chk==''){ $('#e_'+id_data[1]).addClass('warning'); }
				if(val_name_chk==''){ $('#course_name_existing_'+id_data[1]).addClass('warning'); }			
							
			}
	
		}else{
			existing_prices = existing_prices+existing_separator+'0';
		}
		
		if(val_desc_chk!=''){
			$(this).removeClass('warning');			
			existing_courses = existing_courses+existing_separator+val_desc; 		
		}	

		if(val_name_chk!=''){
			$('#course_name_existing_'+id_data[1]).removeClass('warning');			
			existing_names = existing_names+existing_separator+name; 		
		}	
	
	cnt++;
	});		

	console.log(lmta_price_existing);

	var names = '';
	var courses = '';
	var prices = '';
	var separator = '';
	var cnt=0;

	$(".additional_course_desc").each(function(){
	
		var val_desc = $(this).html().trim();
		var id = $(this).attr('id')+'';
		var id_data = id.split('_');
		var name = $('#additional_course_name_'+id_data[1]).html();		
		var price = $('#additional_course_price_'+id_data[1]).val();		
		
		allin = 0;
		
		if($('#all_week_additional_'+id_data[1]).is(':checked')){
			all_in = 1;
		}
				
		if($(".additional_course_desc").length && cnt>0){
			separator = '<^>';	
		}

		course_allin_additional = course_allin_additional + separator + allin;


		lmta_check = 0;
		
		if( $('#almcbox'+id_data[1]).is(':checked') ){
			lmta_check = 1;
		}

		lmta_box_additional = lmta_box_additional + add_separator + lmta_check;
		
		if( $('.almtapris'+id_data[1]).val() > 0 ){
			lmta_price = $('.almtapris'+id_data[1]).val();
		}else{
			lmta_price = 0;
		}
		
		lmta_price_additional = lmta_price_additional + add_separator + lmta_price;
		

		if(price>0){
			$('#additional_course_price_'+id_data[1]).removeClass('warning');		
			prices = prices+separator+price; 		

			var val_desc_chk = $(this).text().trim();
			var val_name_chk = $('#additional_course_name_'+day+'_'+id_arr[1]).text().trim();

			if(val_desc_chk==''){ parameter_check++; }
			if(val_name_chk==''){ parameter_check++; }			
	
			if(parameter_check==2){ 
				errors++; 
				if(val_desc_chk==''){  $(this).addClass('warning'); }
				if(val_name_chk==''){ $('#additional_course_name_'+id_data[1]).addClass('warning'); }			
			}

		}else{
			prices = prices+separator+'0';
		}
		
		if(val_desc_chk!=''){
			$(this).removeClass('warning');			
			courses = courses+separator+val_desc; 		
		}	

		if(val_name_chk!=''){
			names = names+separator+name; 		
		}	
	
	cnt++;
	});		

	var text_after_menu = $('#text_after_menu').html().trim();
				
	console.log('BOX: '+lmta_box_additional);
	console.log('PRICE: '+lmta_price_additional);
	console.log('eBOX: '+lmta_box_existing);
	console.log('ePRICE: '+lmta_price_existing);
	
	//return false;

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
				e_lmta_box:lmta_box_existing,
				e_lmta_price:lmta_price_existing,
				a_lmta_box:lmta_box_additional,
				a_lmta_price:lmta_price_additional,
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

$('.copy_to_clip').on('click', function (e) {
		
		var ccb = $(this).attr('id');
		
		$('#'+ccb).html('Details Copied');
		$('#'+ccb).css('background-color', 'green');
		$('#'+ccb).css('color', '#ffffff');

		$.post( "actions/menu_lunch_copy_clipboard.php", { 
				id:ccb
			})
			.done(function( data ){
					setTimeout(
						function() 
						{
							$('#'+ccb).text('Copy to Clipboard');
							$('#'+ccb).css('background-color', '#cccccc');
							$('#'+ccb).css('color', '#000000');
						}, 5000
					);		
		});

});

function ccbd(id){
		
	$.post( "actions/menu_lunch_paste_clipboard.php", { id:id })
		.done(function( data ){
			var info = data.split('<^>');	
			
			if(info[0]=='' && info[1]=='' && info[2]==''){ alert('Clipboard is empty.'); }
			
			$('#additional_course_name_'+id).html(info[0]);
			$('#additionalTextarea_'+id).html(info[1]);
			$('#additional_course_price_'+id).val(info[2]);		
		});		
};

function ccbd_d(id){
	
	var param = id.split('_');
	
	$.post( "actions/menu_lunch_paste_clipboard.php", { id:param[1] })
		.done(function( data ){
			
			var info = data.split('<^>');	

			if(info[0]=='' && info[1]=='' && info[2]==''){ alert('Clipboard is empty.'); }
			
			$('#additional_course_name_'+param[0]+'_'+param[1]).html(info[0]);
			$('#additionalTextarea_'+param[0]+'_'+param[1]).html(info[1]);
			$('#additional_course_price_'+param[0]+'_'+param[1]).val(info[2]);		
		});		
};

$( "#accordion" ).accordion({
	collapsible: true
});
</script>