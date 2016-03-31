<?php include_once('redirect.php'); ?>
<script type="text/javascript">
	jQuery(function(){
		
		jQuery('.add-table').click(function(){
			
			var table_count=Number(jQuery('span.the-tables').length);
			
			jQuery('.settable-inner').append('<span class="tables the-tables" id="table-'+(table_count+1)+'"><label>Table '+ (table_count+1) +'</label><input type="text" class="thetable txt" style="margin: 0 10px 0 0;"><a href="javascript:void;" class="delete-table" onclick="deleteTable('+(table_count+1)+')"><img src="images/delete-small.png" style="position: relative; top: 7px;"></a></span><div class="clear"></div>');
			
			jQuery('.thetable').numeric();
			
		});
		
		jQuery('.table-pax').click(function(){
			jQuery('.fade,.table-box').fadeIn();
			
			var id=jQuery(this).attr('data-rel');
			
			jQuery('.table-box .box-content').html('<img src="images/loader.gif" style="margin: 30px 0 0;">');
			
			jQuery.ajax({
				 url: "actions/table-availability.php",
				 type: 'POST',
				 data: 'id='+encodeURIComponent(id),
				 success: function(value){
					 jQuery('.table-box .box-content').html(value);
				 }
			});
			
		});
		
		
	});
	
	function deleteTable(id){
		
		jQuery('#table-'+id).fadeOut().removeClass('the-tables').attr('id','');
		
		jQuery('span.the-tables label').each(function(i){
			jQuery(this).html('Table '+(i+1));
		});
		
		jQuery('span.the-tables .delete-table').each(function(i){
			jQuery(this).attr('onclick','deleteTable('+(i+2)+')');
		});
		
		jQuery('span.the-tables').each(function(i){
			jQuery(this).attr('id','table-'+(i+1));
		});
		
	}
	
</script>

<div class="page settings-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                	if(isset($_GET['page'])){
						if(isset($_GET['subpage'])){
							echo ucwords(removeDashTitle($_GET['subpage']));
						}
						else{
							echo ucwords(removeDashTitle($_GET['page']));
						}
					}
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
       
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
    	
    	<table>
        	<tr>
            	<td>Set Start Date :</td>
                <td><input type="text" class="startdate txt" style="width:295px;"></td>
            </tr>
            <tr>
            	<td>Set End Date :</td>
                <td><input type="text" class="enddate txt" style="width:295px;"></td>
            </tr>
        	<tr>
            	<td>Set Day :</td>
                <td>
                	<input type="checkbox" id="mon" class="thedays" value="Mon"> <label for="mon">Mon</label>
                    <input type="checkbox" id="tue" class="thedays" value="Tue"> <label for="tue">Tue</label>
                    <input type="checkbox" id="wed" class="thedays" value="Wed"> <label for="wed">Wed</label>
                    <input type="checkbox" id="thu" class="thedays" value="Thu"> <label for="thu">Thu</label>
                    <input type="checkbox" id="fri" class="thedays" value="Fri"> <label for="fri">Fri</label>
                    <input type="checkbox" id="sat" class="thedays" value="Sat"> <label for="sat">Sat</label>
                    <input type="checkbox" id="sun" class="thedays" value="Sun"> <label for="sun">Sun</label>
                </td>
            </tr>
        	<tr>
            	<td>Set Start Time :</td>
                <td><input type="text" class="starttime txt" style="width:295px;"></td>
            </tr>
            <tr>
            	<td>Set End Time :</td>
                <td><input type="text" class="endtime txt" style="width:295px;"></td>
            </tr>
            <tr>
            	<td><strong>Tables</strong></td>
                <td><strong>Maximum Pax</strong></td>
            </tr>
            <tr>
            	<td colspan="2">
                	<div class="settable">
                    	<span class="tables the-tables" id="table-1"><label>Table 1</label><input type="text" class="thetable txt" style="margin: 0 10px 0 0;"><a href="javascript:void;" class="add-table"><img src="images/add-small.png" style="position: relative; top: 7px;"></a></span>
                        <div class="settable-inner">
                        
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
            	<td colspan="2" align="right"><input type="button" class="btn settings-btn" value="Submit"></td>
            </tr>
        </table>
        <div class="displaymsg"></div>
        <br>
        <!-- settings output -->
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="thesettings">
                <thead>
                    <tr>
                    	<th>Start Date</th>
                        <th>End Date</th>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Total Number of Tables</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
					<?php
                        $q=mysql_query("select days, start_time, end_time, id, start_date, end_date from restaurant_detail where deleted=0") or die(mysql_error());
                        
                        while($r=mysql_fetch_array($q)){
                    ?>
                        <tr class="gradeX gradeX-<?php echo $r[3];?>" align="center">
                        	<td><?php echo date("M d, Y",strtotime($r[4])); ?></td>
                            <td><?php echo date("M d, Y",strtotime($r[5])); ?></td>
                            <td><?php echo $r[0];?></td>
                            <td><?php echo $r[1];?></td>
                            <td><?php echo $r[2];?></td>
                            <td><a href="javascript:void" class="table-pax" data-rel="<?php echo $r[3]; ?>"><?php echo maxPax($r[3]);?></a></td>
                            <td>
                            <?php if($_SESSION['login']['type']<3){ ?>
                               <a href="javascript:void" class="delete-setting" title="Delete Settings" data-rel="<?php echo $r[3]; ?>"><img src="images/delete.png" alt="Delete User"></a>
                            <?php }?> 
                            </td>
                        </tr>
                    <?php		
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                    	<th>Start Date</th>
                        <th>End Date</th>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Total Number of Tables</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        
    </div>
</div>

<div class="fade"></div>
<div class="delete-setting-box modalbox">
	<h2>Confirm Delete<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Are you sure you want to proceed?</p>
        <input type="button" value="Delete">
    </div>
</div>

<div class="table-box orderbox">
	<h2>Table Availability<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <!-- content here -->
    </div>
</div>