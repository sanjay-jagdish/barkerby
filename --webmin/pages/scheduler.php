<?php include_once('redirect.php'); ?>
<script type="text/javascript">
	function copySchedule(aid,start_date,end_date,days,start_time,end_time){
		jQuery('.empname').val(aid);
		jQuery('.startdate').val(start_date);
		jQuery('.enddate').val(end_date);
		jQuery('.starttime').val(start_time);
		jQuery('.endtime').val(end_time);
		
		jQuery('.thedays').each(function(){
			jQuery(this).prop('checked',false);
		});
		
		jQuery('.thedays').each(function(){
            var val=jQuery(this).val();
			
			var hold=days.split(", ");
			
			for(i=0;i<hold.length;i++){
			
				if(val==hold[i]){
					jQuery(this).prop('checked',true);
				}
			}
			
        });
	}
	
	function modifySchedule(aid,start_date,end_date,days,start_time,end_time,id){
		jQuery('.empname').val(aid);
		jQuery('.startdate').val(start_date);
		jQuery('.enddate').val(end_date);
		jQuery('.starttime').val(start_time);
		jQuery('.endtime').val(end_time);
		
		jQuery('.thedays').each(function(){
			jQuery(this).prop('checked',false);
		});
		
		jQuery('.thedays').each(function(){
            var val=jQuery(this).val();
			
			var hold=days.split(", ");
			
			for(i=0;i<hold.length;i++){
			
				if(val==hold[i]){
					jQuery(this).prop('checked',true);
				}
			}
			
        });
		
		jQuery('.foreditbtn').html('<input type="button" class="btn edit-scheduler-btn" data-rel="'+id+'" onclick="editSchedule()" value="Utför">');
	}
	
	
</script>

<div class="page scheduler-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
					$a_type = $_SESSION['login']['type'];
					$a_id = $_SESSION['login']['id'];
				
					echo 'Schemaläggaren';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
        <!--<div class="page-header-right">
        	<a href="?page=shift&subpage=add-shift" class="add-shift">Add Shift</a>
        </div>-->
        <!-- end .page-header-right -->
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
        <div class="shcl-table">
        	<table>
            	<tr>
                	<td>Personal :</td>
                    <td>
                    	<select class="empname">
                        	<option value="">Välj</option>
    			<?php
                                $q=mysql_query("SELECT CONCAT( lname,  ', ', fname ) , email, a.id, description 
    											FROM account a, `type` t
    											WHERE  a.deleted=0 AND  type_id=t.id AND type_id
    												IN (SELECT id FROM `type` WHERE staff =1) 
    											ORDER BY description, CONCAT( lname,  ', ', fname ) ASC");

    							while($r=mysql_fetch_array($q)){
    						?>
                            <option value="<?php echo $r[2];?>"><?php echo '['.$r[3].'] &raquo; '.$r[0].' - '.$r[1];?></option>
                            <?php		
    							}
                            ?>
                        </select>
                    </td>
                </tr>
            	<tr>
                	<td>Startdatum :</td>
                    <td><input type="text" class="startdate txt" style="width:295px;"></td>
                </tr>
                <tr>
                	<td>Slutdatum :</td>
                    <td><input type="text" class="enddate txt" style="width:295px;"></td>
                </tr>
            	<tr>
                	<td>Dag :</td>
                    <td>
                    	<input type="checkbox" id="mon" class="thedays" value="Mon"> <label for="mon">Mån</label>
                        <input type="checkbox" id="tue" class="thedays" value="Tue"> <label for="tue">Tis</label>
                        <input type="checkbox" id="wed" class="thedays" value="Wed"> <label for="wed">Ons</label>
                        <input type="checkbox" id="thu" class="thedays" value="Thu"> <label for="thu">Tor</label>
                        <input type="checkbox" id="fri" class="thedays" value="Fri"> <label for="fri">Fre</label>
                        <input type="checkbox" id="sat" class="thedays" value="Sat"> <label for="sat">Lör</label>
                        <input type="checkbox" id="sun" class="thedays" value="Sun"> <label for="sun">Sön</label>
                    </td>
                </tr>
            	<tr>
                	<td>Starttid :</td>
                    <td><input type="text" class="starttime txt" style="width:295px;"></td>
                </tr>
                <tr>
                	<td>Sluttid :</td>
                    <td><input type="text" class="endtime txt" style="width:295px;"></td>
                </tr>
                <tr>
                	<td colspan="2" align="right" class="foreditbtn"><input type="button" class="btn scheduler-btn" value="Utför"></td>
                </tr>
            </table>
        </div>
        <div class="displaymsg"></div>
        <br>
        
        <!-- schedules -->
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="theschedules">
                <thead>
                    <tr>
                    	<th>Roll</th>
                    	<th>Namn</th>
                    	<th>Startdatum</th>
                        <th>Slutdatum</th>
                        <th>Notering</th>
                        <th>Dagar</th>
                        <th>Starttid</th>
                        <th>Sluttid</th>
                        <th>Åtgärd</th>
                    </tr>
                </thead>
                <tbody>
					<?php
                        $q=mysql_query("select s.id, concat(a.lname,' ',a.fname), s.start_time, s.end_time, s.days, 
                        			s.valid_from, s.valid_until, s.account_id, t.description 
                        		from schedule as s, account as a, `type` as t 
                        		where a.id=s.account_id and s.deleted=0 and a.type_id=t.id") 
                        		or die(mysql_error());
                        
                        while($r=mysql_fetch_array($q)){
                    ?>
                        <tr class="gradeX gradeX-<?php echo $r[0];?>" align="center">
                       	    <td><?php echo $r[8];?></td>
                       	    <td><?php echo $r[1];?></td>
                        	<td><?php echo date("M d, Y",strtotime($r[5])); ?></td>
                            <td><?php echo date("M d, Y",strtotime($r[6])); ?></td>
                            <td>
                            <?php
							$sr_sql = "SELECT id, sched_01, sched_02 FROM shift_request WHERE approve=1 AND (sched_01=".$r[0]." OR sched_02=".$r[0].")";
							$sr_qry = mysql_query($sr_sql);
							$sr_num = mysql_num_rows($sr_qry);
							if($sr_num>0){
							?>
                            <img src="images/info.png">                            
                            <?php	
							}
							?>
                            </td>
                            <td><?php echo replaceDayname($r[4]);?></td>
                            <td><?php echo $r[2];?></td>
                            <td><?php echo $r[3];?></td>
                            <td>
                               <a href="javascript:void" class="copy-schedule" title="Kopiera Schedule" onclick="copySchedule('<?php echo $r[7];?>','<?php echo $r[5];?>','<?php echo $r[6];?>','<?php echo $r[4];?>','<?php echo $r[2];?>','<?php echo $r[3];?>')"><img src="images/copy.png" alt="Kopiera Schedule"></a>
                               <a href="javascript:void" class="edit-schedule" title="Redigera Schedule" onclick="modifySchedule('<?php echo $r[7];?>','<?php echo $r[5];?>','<?php echo $r[6];?>','<?php echo $r[4];?>','<?php echo $r[2];?>','<?php echo $r[3];?>', '<?php echo $r[0];?>')"><img src="images/edit.png" alt="Redigera Schedule"></a>
                               <?php if($_SESSION['login']['type']<3){ ?>
                               <a href="javascript:void" class="delete-schedule" title="Radera Schedule" data-rel="<?php echo $r[0]; ?>"><img src="images/delete.png" alt="Radera Schedule"></a>
                               <?php } ?>
                                
                            </td>
                        </tr>
                    <?php		
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                    	<th>Roll</th>
                    	<th>Namn</th>
                    	<th>Startdatum</th>
                        <th>Slutdatum</th>
                        <th>Notering</th>
                        <th>Dagar</th>
                        <th>Starttid</th>
                        <th>Sluttid</th>
                        <th>Åtgärd</th>
                    </tr>
                </tfoot>
            </table>
        
    </div>
</div>

<div class="fade"></div>
<div class="delete-schedule-box modalbox">
	<h2>Bekräfta borttagning<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Vill du fortsätta?</p>
        <input type="button" value="Utför">
    </div>
</div>