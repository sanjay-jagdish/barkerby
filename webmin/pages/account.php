<?php include_once('redirect.php'); ?>
<script type="text/javascript">
	jQuery(function(){
		
		jQuery('.privilege').click(function(){
			var val=jQuery(this).attr('data-rel').split('*');
			var col=val[0];
			var id=val[1];
			
			var str=jQuery(this).prop('checked');
			var check=0;
			if(str){
				check=1;
			}
		
			jQuery.ajax({
				 url: "actions/privilege.php",
				 type: 'POST',
				 data: 'id='+encodeURIComponent(id)+'&col='+encodeURIComponent(col)+'&check='+encodeURIComponent(check),
				 success: function(value){}
			});
			
		});
	});
</script>

<div class="page logs-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                	/*if(isset($_GET['page'])){
						if(isset($_GET['subpage'])){
							echo ucwords(removeDashTitle($_GET['subpage']));
						}
						else{
							echo ucwords(removeDashTitle($_GET['page']));
						}
					}*/
					echo 'Rättigheter';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
       
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
    	<table cellpadding="0" cellspacing="0" border="0" class="display" id="theaccounts">
            <thead>
                <tr>
                	<th>#</th>
                	<th>Pages</th>
                    <?php
                    	$q=mysql_query("select description, id from type order by id");
						while($r=mysql_fetch_assoc($q)){
					?>
                    <th aria-label="<?php echo $r['id']; ?>"><?php echo $r['description']; ?></th>
                    <?php		
						}
					?>
                </tr>
            </thead>
            <tbody>
               <?php
			   	
               		$pages=array('menu'=>'Menyer','category'=>'Kategori','sub_category'=>'Sub Kategori','reservation'=>'Bokningar','overview'=>'Boken','order_status'=>'Beställningsstatus','customers'=>'Gäster','the_staff'=>'Personal','users'=>'Användare','announcement'=>'Utskick','shift_request'=>'Skiftbyte','scheduler'=>'Schemaläggaren','scheduler_chart'=>'Schema','reports'=>'Rapporter','logs'=>'Logg', 'bookin_report'=>'Booking Report','settings'=>'Bordsintervall','tables'=>'Bordsintervall','table_masterlist'=>'Bordsinställning','floorplan'=>'Bordsplan','account'=>'Rättigheter','advanced_settings'=>'Avancerade inställningar','notifications'=>'Push-notiser','catering'=>'Catering','catering_category'=>'Catering Category','catering_subcategory'=>'Catering Subcategory','catering_menu'=>'Catering Menu','catering_settings'=>'Catering_settings','signatory'=>'Signatory');
					
					
					function setValue($column,$id){
						$q=mysql_query("select $column from type where id=".$id);
						$r=mysql_fetch_array($q);
						
						return $r[0];
					}
					
					$count=0;
					
					
					foreach($pages as $key=>$values){
						$count++;
			   ?>
              		 <tr class="gradeX" align="center">
                     	<td><?php echo $count; ?></td>
                    	<td><?php echo $values; ?></td>
                        
                        <?php
                        	$qq=mysql_query("select id from type order by id");
							while($rr=mysql_fetch_assoc($qq)){
						?>
                        <td><input type="checkbox" class="privilege" <?php if(setValue($key,$rr['id'])==1) echo 'checked="checked"'; if($rr['id']==1) echo 'disabled="disabled"';?> data-rel="<?php echo $key;?>*<?php echo $rr['id']?>"></td>
                        <?php	
							}
						?>
                      
                    </tr>
                <?php }?>
            </tbody>
            <tfoot>
                <tr>
                	<th>#</th>
                    <th>Pages</th>
                    <?php
                    	$q=mysql_query("select description from type");
						while($r=mysql_fetch_assoc($q)){
					?>
                    <th><?php echo $r['description']; ?></th>
                    <?php		
						}
					?>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="fade"></div>
<div class="delete-log-box modalbox">
	<h2>Confirm Delete<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Are you sure you want to proceed?</p>
        <input type="button" value="Delete">
    </div>
</div>