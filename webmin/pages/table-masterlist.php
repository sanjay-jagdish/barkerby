<?php include_once('redirect.php'); ?>
<div class="page currency-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
					echo 'Bordsinställning';
				?>
            </h2>
        </div>
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
    	Fyll i bordsnummer och andtal platser för varje bord.<br />
        <table cellpadding="0" cellspacing="0" border="0" align="left" class="display" id="themasterlist" style="width: 200px; text-align:left;">
            <thead>
                <tr>
                    <th>Bordsnummer</th>
                    <th>Platser</th>
                    <th>Åtgärd</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $q=mysql_query("select * from table_masterlist where deleted=0") or die(mysql_error());
                    
                    while($r=mysql_fetch_assoc($q)){
                ?>
                    <tr class="gradesX gradesX-<?php echo $r['id'];?>" align="center">
                        <td><input type="text" class="txt masterlistname mln<?php echo $r['id'];?>" value="<?php echo ucwords($r['name']);?>" style="text-align:center" data-rel="<?php echo $r['id'];?>" /></td>
                        <td><input type="text" class="txt masterlistseat mls<?php echo $r['id']; ?>" value="<?php echo strtoupper($r['seats']);?>" style="text-align:center" data-rel="<?php echo $r['id'];?>"  /></td>
                        <td><a href="javascript:void(0)" class="delete-table-masterlist" title="Delete Table Masterlist" data-rel="<?php echo $r['id']; ?>"><img src="images/delete.png" alt="Delete Settings"></a>&nbsp;&nbsp;<a href="javascript:void(0)" class="savesettingsbtn" data-id="<?php echo $r['id']; ?>"><img src="images/savebtn.png" alt="Save Settings" style="position: relative; top: -3px;"></a></td>
                    </tr>
                    
                <?php		
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Bordsnummer</th>
                    <th>Platser</th>
                    <th>Åtgärd</th>
                </tr>
            </tfoot>
        </table>
        
        <div style="clear:both;">&nbsp;</div>
        <div class="displaymsg" id="err"></div>
        <div style="clear:both;">&nbsp;</div>

		
		<style>
			.thetablename1{ width:150px !important; } 
			.thetable1{ width:55px !important; }
		</style>
		
        <div class="settable1">
            <strong>Lägg till fler bord:</strong><br />
            <span class="tables1 the-tables1" id="table1-1"><input type="text" class="thetablename1 txt" style="width: 90px; margin-right: 15px;" placeholder="Bordsnummer"><input type="text" class="thetable1 txt" style="margin: 0 10px 0 0;" placeholder="Platser"><a href="javascript:void;" class="add-table1"><img src="images/add-small.png" style="position: relative; top: 7px;"></a></span>
            <div class="settable-inner1">
            
            </div>
            <input type="button" class="btn addmasterlist" value="Utför" style="margin-top:10px;">
        </div>
        <div class="preloader" style="width:25%; text-align:center;"></div>
    </div>
</div>

<div class="fade"></div>
<div class="delete-table-masterlist-box modalbox">
	<h2>Confirm Delete<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Are you sure you want to proceed?</p>
        <input type="button" value="Delete">
    </div>
</div>

<script type="text/javascript">

function deleteTables(id){
		
		jQuery('#table1-'+id).fadeOut().removeClass('the-tables1').attr('id','');
		
		
		jQuery('span.the-tables1 .delete-table').each(function(i){
			jQuery(this).attr('onclick','deleteTables('+(i+2)+')');
		});
		
		jQuery('span.the-tables1').each(function(i){
			jQuery(this).attr('id','table1-'+(i+1));
		});
		
	}

jQuery(function(){
	
	jQuery('.masterlistseat, .thetable1').numeric();
	
	jQuery('.add-table1').click(function(){
		
		var table_count=Number(jQuery('span.the-tables1').length);
		
		jQuery('.settable-inner1').append('<span class="tables1 the-tables1" id="table1-'+(table_count+1)+'"><input type="text" class="thetablename1 txt" style="width: 90px; margin-right: 15px;" placeholder="Bordsnummer"><input type="text" class="thetable1 txt" style="margin: 0 10px 0 0;" placeholder="Platser"><a href="javascript:void;" class="delete-table" onclick="deleteTables('+(table_count+1)+')"><img src="images/delete-small.png" style="position: relative; top: 7px;"></a></span><div class="clear"></div>');
		
		jQuery('.thetable1').numeric();
		
	});
	
	
	jQuery('.masterlistseat, .thetable1').keyup(function(){
		jQuery(this).val(jQuery(this).val().replace('.',''));
	});
	
	$('.savesettingsbtn').click(function(){
		var id = $(this).attr('data-id');
		var mln = $('.mln'+id).val();
		var mls = Number($('.mls'+id).val());
		
		jQuery('.displaymsg').fadeOut();
		
		if(mln!='' & mls>0){
			
			jQuery.ajax({
				 url: "actions/update-name-masterlist.php",
				 type: 'POST',
				 data: 'id='+encodeURIComponent(id)+'&mln='+encodeURIComponent(mln)+'&mls='+encodeURIComponent(mls),
				 success: function(value){
					
					value=value.trim();
					
					if(value!='Invalid'){

						jQuery('.mln'+id).removeClass('redborder');	

						jQuery('html, body').animate({
					        scrollTop: jQuery('#err').offset().top
					    }, 500);
	
						jQuery('.displaymsg').fadeIn().addClass('successmsg').html('Table name successfully updated.');
						setTimeout("jQuery('.displaymsg').fadeOut('fast').html('');",2000); 
						
						
					}
					else{

						jQuery('.mln'+id).addClass('redborder');	
						
						jQuery('.displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html('Table name already exists.');

						
						jQuery('html, body').animate({
					        scrollTop: jQuery('#err').offset().top
					    }, 500);
						
					}
					
				 }
			});
			
		}
		else{
			
			jQuery('html, body').animate({
				scrollTop: jQuery('#err').offset().top
			}, 500);
			
			jQuery('.displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html('Table name should not be emtpy and number of seats should be greater than zero(0).');
		}
		
	});
	
	/*jQuery('.masterlistseat').focusout(function(){
		var val=Number(jQuery(this).val());
		var id=jQuery(this).attr('data-rel');
		
		jQuery('.displaymsg').fadeOut();
		
		if(val>0){
		
			jQuery.ajax({
				 url: "actions/update-seat-masterlist.php",
				 type: 'POST',
				 data: 'id='+encodeURIComponent(id)+'&val='+encodeURIComponent(val),
				 success: function(value){
					
					jQuery('.displaymsg').fadeIn().addClass('successmsg').html('Number of seats successfully updated.');
					setTimeout("jQuery('.displaymsg').fadeOut('fast').html('');",2000); 
					
				 }
			});
		
		}
		else{
			jQuery('.displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html('Number of seats should be greater than <strong>ZERO</strong>.');
		}
		
	});

	jQuery('.masterlistname').focusout(function(){
		var val=jQuery(this).val();
		var id=jQuery(this).attr('data-rel');
		var prevval=jQuery(this).attr('data-aria');
		
		jQuery('.displaymsg').fadeOut();
		
		if(val!=''){
		
			jQuery.ajax({
				 url: "actions/update-name-masterlist.php",
				 type: 'POST',
				 data: 'id='+encodeURIComponent(id)+'&val='+encodeURIComponent(val),
				 success: function(value){

				 	value=value.trim();
					
					if(value!='Invalid'){

						jQuery('.mln'+id).removeClass('redborder');	

						jQuery('.displaymsg').fadeIn().addClass('successmsg').html('Table name successfully updated.');
						setTimeout("jQuery('.displaymsg').fadeOut('fast').html('');",2000); 
					}
					else{

						jQuery('.mln'+id).addClass('redborder');	
						
						jQuery('.displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html('Table name already exists.');

						
						jQuery('html, body').animate({
					        scrollTop: jQuery('#err').offset().top
					    }, 500);
						
					}

				 }
			});
		
		}
		else{
			jQuery('.displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html('Table name should not be empty.');
		}
		
	});*/
	
	
	jQuery('.delete-table-masterlist').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .modalbox').fadeIn();
		gotoModal();
		
		var id=jQuery(this).attr('data-rel');
		jQuery('.delete-table-masterlist-box input').attr('data-rel',id);
		
		
	});
	
	jQuery('.delete-table-masterlist-box input[type=button]').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		var id=jQuery(this).attr('data-rel');
		
		jQuery.ajax({
			 url: "actions/delete-table-masterlist.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){
				 
				 jQuery('.fade, .modalbox, .gradesX-'+id).fadeOut();
				 //setTimeout("window.location.reload()",1000);
			 }
		});
		
	});
	
	jQuery('.addmasterlist').click(function(){
	
		var count=jQuery('.the-tables1 .txt').length;
		var counter=0;
		var pax='';
		var loop=0;
		jQuery('.the-tables1 .txt').each(function(){
			loop+=1;
			if(jQuery(this).val()!=''){
				counter+=1;
				
				var val=jQuery(this).val();
				if(loop%2==0){
					sep='*';
				}
				else{
					sep='^';
				}
				
				pax+=jQuery(this).val()+sep;
			}
		});
	
		if(count==counter){
			pax=pax.substr(0,pax.length-1);
			
			jQuery('.preloader').html('<img src="images/loader.gif" style="margin: 30px 0 0;">');
			jQuery.ajax({
				 url: "actions/add-table-masterlist.php",
				 type: 'POST',
				 data: 'pax='+encodeURIComponent(pax),
				 success: function(value){
					 
					 value=value.trim();

				 	 if(value=='good'){
   						

   						 jQuery('.thetablename1').removeClass('redborder'); 	 

   						 jQuery('.displaymsg').fadeOut();

						 jQuery('.preloader').html('');
						 setTimeout("window.location.reload()",1000);
					 }
					 else{

					 	jQuery('.preloader').html('');
					 	var exists=value.split('*');

					 	jQuery('.thetablename1').removeClass('redborder'); 	 

					 	for(i=0;i<exists.length;i++){
					 		jQuery('#table1-'+exists[i]+' .thetablename1').addClass('redborder');
					 	}

					 	jQuery('.displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html("Table(s) already exists.");

					 	jQuery('html, body').animate({
					        scrollTop: jQuery('#err').offset().top
					    }, 500);

					 }
				 }
			});
			
		}
		else{

			jQuery('.displaymsg').fadeIn().removeClass('successmsg').addClass('errormsg').html("All fields are required.");

						
			jQuery('html, body').animate({
		        scrollTop: jQuery('#err').offset().top
		    }, 500);

		}
	
	});


});
</script>
