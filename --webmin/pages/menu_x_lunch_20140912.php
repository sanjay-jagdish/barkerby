<?php
require '../config/config.php';

$year_num = $_POST['year_num'];
$week_num = $_POST['week_num'];

$currency_shortname = '';
$currency_sql = "SELECT shortname FROM currency WHERE set_default=1";
$currency_qry = mysql_query($currency_sql);
$currency_res = mysql_fetch_assoc($currency_qry);

//check if there is an existing lunch menu for the week
$menu_week_sql = "SELECT * FROM menu_lunch WHERE year_for=".$year_num." AND week_no=".$week_num;
$menu_week_qry = mysql_query($menu_week_sql);
$menu_week_num = mysql_num_rows($menu_week_qry);


?>

<style>
.menu_main_description, .course_desc,  .course_desc_existing, .additional_course_desc{ width: 795px; height: 150px; }
.warning{ box-shadow: 0 0 10px #F00 !important; outline: none !important; background-color:#FFC !important; }
.additional_course_price, .unit_price_existing{ text-align: right !important; }
</style>

<?php
$save_button_id = 'lunch_meny_save_all';
	
$menu_week_res = mysql_fetch_assoc($menu_week_qry);
?>

<div style="background-color:#FFC; padding:4px; overflow:hidden;">
    <h2 style="float:left;">OBS! Gäller hela vecka <?php echo $week_num; ?> (mån-fre)</h2>
    <div style="float:right;"><input type="checkbox" id="allweek" <?php if($menu_week_res['all_in']){ echo 'checked'; } ?> /> Gäller ala veckor.</div>
</div>

<div style="text-align:right; padding:8px 0px;">
 
 <input type="hidden" id="menu_parameter" value="<?php echo 'W '. $year_num.' '.$week_num; ?>" /> 

<?php
if($menu_week_num>0){
	
	$save_button_id = 'lunch_meny_update_all';
?>
    <em>Created on:</em> &nbsp;<?php echo $menu_week_res['time_created']; ?> &nbsp;  <em>Last Saved:</em> <?php echo $menu_week_res['last_saved']; ?>
    
    <input type="hidden" id="saved_menu" value="<?php echo $menu_week_res['id']; ?>" />
    
	<?php	
    }else{
    
		//check if all in weekly menu of the last record is in effect
		$sql = "SELECT week_no, year_for, all_in FROM menu_lunch WHERE specific_days='' ORDER BY id DESC LIMIT 1";
		$qry = mysql_query($sql);
		$num = mysql_num_rows($qry);
	?>
    
    <div style="padding:4px; background-color:#FC9; text-align:center;"><font color="red"><strong>* no saved menu for the week
    <?php
	$res = mysql_fetch_assoc($qry);
	if($res['all_in']==1){
		echo '<font color="green"> but week <b>'.$res['week_no'].'</b> for year <b>'.$res['year_for'].'</b> is still in effect</font>';
	}
	?>
     *</strong></font></div>
    <input type="hidden" id="saved_menu" value="0" />
    <?php	
    }
    ?>  
    
</div>

Text ovanför menyn: <input name="text" id="text_over_menu" class="" value="<?php echo stripslashes($menu_week_res['note_header']); ?>" style="width:600px;" />

<br /><br />


<div class="menu_description">
    Menu Description:
    <textarea class="menu_main_description"><?php echo stripslashes($menu_week_res['description']); ?></textarea>
</div>


<div style="float:right; width: 200px; text-align:right;">
	<div style="color:#fff; background-color:#090; padding:8px; float:right; width:200px; text-align:center; font-weight:bold; display:none;" id="message">Successfully Saved.</div>
    <input type="button" value="Spara allt" class="btn" id="<?php echo $save_button_id; ?>" style="padding:12px; float:right; margin-top:45px;" />
</div>

                    
<div class="menu_items">
    
    <br /><br />
    Courses:
    
    <?php 
    $courses_sql = "SELECT * FROM menu_lunch_items WHERE menu_id=".$menu_week_res['id']." ORDER BY `order`, id ASC";
    $courses_qry = mysql_query($courses_sql);
    $courses_num = mysql_num_rows($courses_qry);
    
	if($courses_num>0){
		while($courses_res = mysql_fetch_assoc($courses_qry)){
		?>
	
		<div class="menu_item" id="existing_course_<?php echo $courses_res['id']; ?>">
		
			<div class="menu_item_actions">
				<img src="images/delete.png" alt="Radera" onclick="remove_existing_course(<?php echo $courses_res['id']; ?>)"><br />
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
		
			<div class="menu_item_desc">
				<textarea class="course_desc_existing" id="e_<?php echo $courses_res['id']; ?>"><?php echo stripslashes($courses_res['description']); ?></textarea>
			</div>
			Pris: 
			<input type="text" class="unit_price_existing" value="<?php echo $courses_res['price']; ?>" id="course_price_existing_<?php echo $courses_res['id']; ?>" data-rel="<?php echo $courses_res['id']; ?>" /> <?php echo $currency_res['shortname']; ?>
		</div>
	
		<?php
		}//looping thru courses for this menu

	}else{
    ?>
	
    <div class="menu_item" id="additional_course_'+add_count+'"><div class="menu_item_actions"><br /></div><div class="menu_item_desc"><textarea class="additional_course_desc" id="additionalTextarea_0"></textarea></div>Pris:<input type="text" class="additional_course_price" id="additional_course_price_0" /> <?php echo $currency_res['shortname']; ?></div>
    
    <?php
	}
	?>
    <div id="menu_items_additional"></div>
    
    
    <input type="button" id="add_course" value="+ Add Course" />

    
    <br /><br /><br />
    
    Text nedanför menyn: <input name="text" id="text_after_menu" value="<?php echo stripslashes($menu_week_res['note_footer']); ?>" style="width:600px;" />
                 

</div>

<script>
var add_count = 0;
$('#add_course').on('click', function (e) {
	add_count+=1;
	$( "#menu_items_additional" ).append('<div class="menu_item" id="additional_course_'+add_count+'"><div class="menu_item_actions"><img src="images/delete.png" alt="Radera" onClick="remove_additional_course('+add_count+')"><br /></div><div class="menu_item_desc"><textarea class="additional_course_desc" id="additionalTextarea_'+add_count+'" style="width: 795px; height: 150px;"></textarea></div>Pris:<input type="text" class="additional_course_price" id="additional_course_price_'+add_count+'" /> <?php echo $currency_res['shortname']; ?></div>');

});


function remove_additional_course(id){

	$( "#additional_course_"+id ).css( "background-color","red" );

	$( "#additional_course_"+id ).toggle( "scale", function() {
		// Animation complete.
		$( "#additional_course_"+id ).remove();
	});

}


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

	var text_over_menu = $('#text_over_menu').val().trim();
		
	if(text_over_menu==''){
		errors++;
		$('#text_over_menu').addClass('warning');
	}else{
		$('#text_over_menu').removeClass('warning');
	}
	
	//***variable
	var menu_main_description = $('.menu_main_description').val().trim();
		
	if(menu_main_description==''){
		errors++;
		$('.menu_main_description').addClass('warning');
	}else{
		$('.menu_main_description').removeClass('warning');
	}


	var items = '';
	var existing_courses = '';
	var existing_prices = '';
	var existing_sorts = '';
	var existing_separator = '';
	var cnt=0;

	$(".course_desc_existing").each(function(){
	
		var val_desc = $(this).val().trim();
		var id = $(this).attr('id');
		var id_data = id.split('_');
		var price = $('#course_price_existing_'+id_data[1]).val();		
				
		if($('.course_desc_existing').length>1 && cnt>0){
			existing_separator = '<^>';	
		}

		items = items+existing_separator+id_data[1];
		existing_sorts = existing_sorts+existing_separator+$('#sort_'+id_data[1]).val(); 
		
			
		if(price>0){
			$('#course_price_existing_'+id_data[1]).removeClass('warning');		
			existing_prices = existing_prices+existing_separator+price; 		
		}else{
			errors++;
			$('#course_price_existing_'+id_data[1]).addClass('warning');
			$(this).addClass('warning');
		}
		
		if(val_desc==''){
			errors++;
			$(this).addClass('warning');
		}else{
			$(this).removeClass('warning');			
			existing_courses = existing_courses+existing_separator+val_desc; 		
		}	
	
	cnt++;
	});		

	var courses = '';
	var prices = '';
	var separator = '';
	var cnt=0;

	$(".additional_course_desc").each(function(){
	
		var val_desc = $(this).val().trim();
		var id = $(this).attr('id');
		var id_data = id.split('_');
		var price = $('#additional_course_price_'+id_data[1]).val();		
				
		if($(".additional_course_desc").length && cnt>0){
			separator = '<^>';	
		}
					
		if(price>0){
			$('#additional_course_price_'+id_data[1]).removeClass('warning');		
			prices = prices+separator+price; 		
		}else{
			errors++;
			$('#additional_course_price_'+id_data[1]).addClass('warning');
			$(this).addClass('warning');
		}
		
		if(val_desc==''){
			errors++;
			$(this).addClass('warning');
		}else{
			$(this).removeClass('warning');			
			courses = courses+separator+val_desc; 		
		}	
	
	cnt++;
	});		

	var text_after_menu = $('#text_after_menu').val().trim();
		
	if(text_after_menu==''){
		errors++;
		$('#text_after_menu').addClass('warning');
	}else{
		$('#text_after_menu').removeClass('warning');
	}
	
	//alert('id:'+menu_id+',all_week:'+allweek+',menu_parameter:'+menu_parameter+',header_text:'+text_over_menu+',menu_description:'+menu_main_description+',courses:'+courses+',prices:'+prices+',e_courses:'+existing_courses+',e_prices:'+existing_prices+',e_items:'+items+',text_after_menu:'+text_after_menu);
	
	//return false;
		
	if(errors>0){
		alert('Some required fields are empty, they are highlighted with red color.');	
	}else{	
			
		var menu_parameter = $('#menu_parameter').val();


		$.post( "actions/menu_lunch_update.php", { 
				id:menu_id,
				all_week:allweek,
				menu_parameter: menu_parameter,  
				header_text: text_over_menu,
				menu_description: menu_main_description,
				courses: courses,
				prices: prices, 
				e_courses: existing_courses,
				e_prices: existing_prices, 
				e_items:items,
				e_sorts:existing_sorts,
				text_after_menu: text_after_menu
			})
			.done(function( data ){
				var resulta = data.split('|');
				//alert('Result: '+resulta[1]);
				if(resulta[0]==1){
					var menu_params = menu_parameter.split(' ');
					$.post( "pages/menu_lunch.php", { year_num: menu_params[1], week_num: menu_params[2] })
						.done(function( data_load ) {
						$( ".menu_main_box").html(data_load);
						$("#message").fadeIn('slow');
						setTimeout(
							function() 
							{
								$("#message").fadeOut('slow');
							}, 10000
						);
					});
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


$('#lunch_meny_save_all').on('click', function (e) {
	
	var errors = 0;
	//***variable
	
	var allweek=0;
	
	if($('#allweek').prop('checked')){ allweek=1; }

	var text_over_menu = $('#text_over_menu').val().trim();
		
	if(text_over_menu==''){
		errors++;
		$('#text_over_menu').addClass('warning');
	}else{
		$('#text_over_menu').removeClass('warning');
	}
	
	//***variable
	var menu_main_description = $('.menu_main_description').val().trim();
		
	if(menu_main_description==''){
		errors++;
		$('.menu_main_description').addClass('warning');
	}else{
		$('.menu_main_description').removeClass('warning');
	}


	var courses = '';
	var prices = '';
	var separator = '';
	

	$(".additional_course_desc").each(function(){
	
		var val_desc = $(this).val().trim();
		var id = $(this).attr('id');
		var id_data = id.split('_');
		var price = $('#additional_course_price_'+id_data[1]).val();		
				
		if(id_data[1]>0){
			separator = '<^>';	
		}
			
		if(price>0){
			$('#additional_course_price_'+id_data[1]).removeClass('warning');		
			prices = prices+separator+price; 		
		}else{
			errors++;
			$('#additional_course_price_'+id_data[1]).addClass('warning');
			$(this).addClass('warning');
		}
		
		if(val_desc==''){
			$(this).addClass('warning');
		}else{
			errors++;
			$(this).removeClass('warning');			
			courses = courses+separator+val_desc; 		
		}	
	
	});		


	var text_after_menu = $('#text_after_menu').val().trim();
		
	if(text_after_menu==''){
		errors++;
		$('#text_after_menu').addClass('warning');
	}else{
		$('#text_after_menu').removeClass('warning');
	}
		
	if(errors>0){
		alert('Some required fields are empty, they are highlighted with red color.');	
	}else{	
		//alert(courses+'\n\n'+prices);
			
		var menu_parameter = $('#menu_parameter').val();

		$.post( "actions/menu_lunch_save.php", { 
				all_week:allweek,
				menu_parameter: menu_parameter,  
				header_text: text_over_menu,
				menu_description: menu_main_description,
				courses: courses,
				prices: prices, 
				text_after_menu: text_after_menu
			})
			.done(function( data ){
				var resulta = data.split('|');
				alert(resulta[1]);
				if(resulta[0]==1){
					var menu_params = menu_parameter.split(' ');
					$.post( "pages/menu_lunch.php", { year_num: menu_params[1], week_num: menu_params[2] })
						.done(function( data_load ) {
						$( ".menu_main_box").html(data_load);
					});
				}
		});		

	}
});

</script>