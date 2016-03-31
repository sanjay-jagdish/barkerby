<?php include_once('redirect.php'); ?>
<style>
.approved td{ background-color:#9FC; }
</style>
<div class="page shift-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
					$a_type = $_SESSION['login']['type'];
					$a_id = $_SESSION['login']['id'];
					
                	/*if(isset($_GET['page'])){
						if(isset($_GET['subpage'])){
							echo ucwords(removeDashTitle($_GET['subpage']));
						}
						else{
							echo ucwords(removeDashTitle($_GET['page']))." Request";
						}
					}*/
					echo 'Skiftbyte';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
        <div class="page-header-right">
        	<a href="?page=shift&subpage=create-shift-request&parent=staff" class="add-shift">Skapa ny</a>
        </div>
        <!-- end .page-header-right -->
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
    	<table cellpadding="0" cellspacing="0" border="0" class="display" id="theshifts">
            <thead>
                <tr>
                	<th>SB</th>
                	<th>Vill byta</th>
                 	<th>Skift</th>
                 	<th>Typ</th>
                	<th>Byta med</th>
                 	<th>Skift</th>
                    <th>Notering</th>
                    <th>Bekräftat</th>
                    <th>Emot</th>
                    <th><?php if ($a_type == 1 || $a_type == 2) { echo 'Godkänn'; } else { echo 'Åtgärd'; } ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
					if ($a_type == 1 || $a_type == 2) {
                    	$q=mysql_query("SELECT id, account_id_1, account_id_2, description, remarks, sched_01, sched_02, date_1, date_2, `option`, other_employee, approve, accepted, accept_time 
										FROM shift_request sr 
										WHERE sr.deleted=0") or die(mysql_error());						
					} else {
						$q=mysql_query("SELECT id, account_id_1, account_id_2, description, remarks, sched_01, sched_02,  date_1, date_2, `option`, other_employee, approve, accepted, accept_time 
										FROM shift_request sr 
										WHERE sr.deleted=0 AND (account_id_1=".$a_id ." OR account_id_2=".$a_id ." OR find_in_set('".$a_id ."',other_employee) <> 0)") or die(mysql_error());
					}
                    
                    while($r=mysql_fetch_assoc($q)){
						
						$conflict = array();

						$status = "Ej bearbetad";
						
						if (intval($r['approve']) == 1) {
							$status = "Godkänd";
						} else if (intval($r['approve']) == 2) {
							$status = "Avslag";
						}
                ?>
                    <tr class="gradeX gradeX-<?php echo $r['id'];?> <?php if($r['approve']==1){ echo 'approved'; }?>" align="center">
						<td align="center" valign="top"><?php echo $r['id'];?></td>
                        <td valign="top">
						<?php 
						$name1 = acctid_name($r['account_id_1']);
						echo $name1;
						?>
                        </td>
                        <td valign="top">
						<?php 
							echo date('M. d, Y',strtotime($r['date_1']));
							$time = schedid_time($r['sched_01']);
							echo '<br>'.$time['start_time'].'&rarr;'.$time['end_time'];
						?>
                        </td>
                        <td valign="top">
						<?php 
						switch($r['option']){
							case 1:
								$txt = 'Byter';
								$img_file = 'swap';
								break;
							case 2:
								$txt = 'Droppa';
								$img_file = 'drop';
								break;
						}
						
					
						echo '<img src="images/shift-'.$img_file.'.png" style="width:20px;" />';
						echo ucwords($txt);
						?>
                        </td>
                        <td valign="top">
						<?php 
						if($r['option']==1 || $r['approve']==1){
							$name2 = acctid_name($r['account_id_2']);
							echo $name2;
						}else{
							$emp = explode(',',$r['other_employee']);
							$names='';
							foreach($emp as $k => $eid){
								$name = acctid_name($eid);
								$font = '<font>';
								if($r['accepted']>0 && $eid!=$r['accepted']){ $font='<font style="text-decoration:line-through; color:#999999;">'; }
								$names .= $font.$name.'</font><br>';
							}
							echo $names;
						?>
                        <div style="text-align:left;">
                        
                        </div>
                        <?php
						}
						?>
                        </td>
                        <td valign="top">
						<?php 
						if($r['option']==1){						
							echo date('M. d, Y',strtotime($r['date_2']));
							$time = schedid_time($r['sched_02']);
							echo '<br>'.$time['start_time'].'&rarr;'.$time['end_time'];						
						}else{
						
						}
						?>
                        </td>
                        <td valign="top"><?php echo $r['description'];?></td>
                        <td valign="top"><?php echo $status;?></td>
						<td valign="top" id="acceptance_<?php echo $r['id']; ?>">
                        <?php
						if($r['option']==1){ //if request is SHIFT	
							if (intval($r['account_id_2']) == $a_id) {
								
								if($r['accepted']>0){
									//echo 'Accepted<br>'.date('dMY H:ia',strtotime($r['accept_time']));
								}else{
									echo '<a href="#" class="accept_shift" data-rel="'.$r['id'].'">Accept</a>';									
								}
								
							}else{

								if($r['accepted']>0){
									$accepted=1;
								}else{
									$accepted=0;									
								}
						
							}
						
						}else{
							//DROP request
								
						}
						?>
                        </td>
                        <td valign="top" id="approval_<?php echo $r['id']; ?>">
							<?php
								if ($a_type == 1 || $a_type == 2) {
									// For admin or manager.
									
									//double check if no conflicting request exists
									//DISABLE  if the specific day has been swapped by a Shift Request (approved request)
									//1st on employee's request made
									$sr_s = "SELECT id FROM shift_request 
											 WHERE
											 
											 ( 
											 '".$r['account_id_1']."' IN
											 	(SELECT account_id_1 FROM shift_request WHERE date_1='".$r['date_1']."' AND account_id_1='".$r['account_id_1']."' AND approve=1 AND id<>".$r['id'].")
											 OR
											 '".$r['account_id_1']."' IN
											 	(SELECT account_id_2 FROM shift_request WHERE date_2='".$r['date_1']."' AND account_id_2='".$r['account_id_1']."' AND approve=1 AND id<>".$r['id'].")
							
											 )"
											;		
									
									//echo $sr_s;
									
									$sr_q = mysql_query($sr_s);
									$sr_n = mysql_num_rows($sr_q);
									if($sr_n>0){
										$sr_r = mysql_fetch_assoc($sr_q);		
										//$conflict[] = $sr_r['id'];
									}
									/*
									//2nd as requested by other employee
									$sr_s = "SELECT id FROM shift_request 
			 								 WHERE 
											 ( 
											 	(account_id_2=".$r['account_id_1']." AND date_1='".$r['date_1']."') 
											 	OR
											 	(account_id_2=".$r['account_id_1']." AND date_2='".$r['date_1']."') 
											 ) 
											AND approve=1 AND id<>".$r['id'];			
									$sr_q = mysql_query($sr_s);
									$sr_n = mysql_num_rows($sr_q);
									if($sr_n>0){
										$sr_r = mysql_fetch_assoc($sr_q);		
										$conflict[] = $sr_r['id'];
									}		
									*/
									if(count($conflict)==0){
										
										if($r['approve']==0 || $r['approve']==2){
											if($r['option']==1){ $class_approve = 'shift_approve_check'; }else{ $class_approve = 'approve-drop-shift'; }
										}else{
											$class_approve = 'shift_approve_check';
										}
							?>
                            			<input type="checkbox" class="<?php echo $class_approve; ?>" id="cbox-<?php echo $r['id'];?>" accepted="<?php echo $accepted; ?>" data-rel="<?php echo $r['id'];?>" <?php if($r['approve'] == 1) echo 'checked="checked"';?>>
                            <?php
									}else{
										echo 'Conflict w/ ';
										foreach($conflict as $k => $srid){
											echo $srid;
											if($k+1<count($conflict))
												echo ', ';
										}	
									}
							
								} else {
									// For user requesting shift.
									
									if($r['accepted']>0){
										echo 'Accepted<br>'.date('dMY H:ia',strtotime($r['accept_time']));							
									}else{
										//echo '<div style="float:left;">Waiting<br>Acceptance</div>';	
										$other_employees = explode(',',$r['other_employee']);
										if ( (intval($r['account_id_2']) == $a_id || in_array($a_id,$other_employees)) && $a_id && $r['approve']==0) {
											echo '<a href="#" class="accept_shift" data-rel="'.$r['id'].'">Accept</a>';	
										}else{
											if($a_id && $r['approve']==0)
												echo 'Awaiting Acceptance';	
										}
									}
									
									if (intval($r['account_id_1']) == $a_id && $r['approve']==0) {
                        				echo '<a href="#" class="delete-shift" title="Delete Shift" data-rel="' . $r['id'] . '"><img src="images/delete.png" alt="Delete Shift"></a>';
										//<a href="?page=shift&subpage=edit-shift&id=' . $r[0] . '" class="edit-shift" title="Edit Shift"><img src="images/edit.png" alt="Edit Shift"></a> 
									} else {
										//echo '';
									}
								}
							?>
                      </td>
              </tr>
                <?php		
                    }
                ?>
          </tbody>
            <tfoot>
                <tr>
                <tr>
                    <th>SB</th>
                	<th>Vill byta</th>
                 	<th>Skift</th>
                 	<th>Typ</th>
                	<th>Byta med</th>
                 	<th>Skift</th>
                    <th>Notering</th>
                    <th>Bekräftat</th>
                    <th>Emot</th>
                    <th><?php if ($a_type == 1 || $a_type == 2) { echo 'Godkänn'; } else { echo 'Åtgärd'; } ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div id="popup"></div>
<div class="fade"></div>
<div class="delete-shift-box modalbox">
	<h2>Confirm Delete<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Are you sure you want to proceed?</p>
        <input type="button" value="Delete">
    </div>
</div>

<script>
    jQuery('.shift_approve_check').click(function(e){
      		
	var val=jQuery(this).attr('checked');
    var id=jQuery(this).attr('data-rel');
	var accepted=jQuery(this).attr('accepted');
    
	e.preventDefault();
	e.stopPropagation();

	var process = '';

	if(accepted == 0 && val!='checked'){
		var r = confirm("You are about to approve a Request which have not been accepted yet.\n\nAre you sure you want to Approve this request?");
	}else{
		process = 1;	
	}
	
	if(r == true){	
		process = 1;			
	}
	
	if(process==1){
		
			jQuery('#approval_'+id).html('<font color="orange">processing<br>pls wait...<font>');
		
			if(val=='checked'){
				val=2; //reverse to Dissapproved
			}
			else{
				val=1; //not yet checked meaning still pending; go and approve
			}
			
			jQuery.ajax({
				 url: "actions/status-shift.php",
				 type: 'POST',
				 data: 'id='+id+'&val='+val,
				 success: function(value){
					 jQuery('.fade, .modalbox, .gradeX-' + id).fadeOut();
					 
					 setTimeout("window.location.reload()");
				}
			});
			
		}
        
    });





    jQuery('.accept_shift').click(function(e){
      		
    var id=jQuery(this).attr('data-rel');
    
	e.preventDefault();
	e.stopPropagation();
	
		jQuery('#acceptance_'+id).html('<font color="orange">processing<br>pls wait...<font>');
					
		jQuery.ajax({
			 url: "actions/accepted-shift.php",
			 type: 'POST',
			 data: 'id='+id,
			 success: function(value){
				       setTimeout("window.location.reload()");			
			 }
		});
		
    });




jQuery('.approve-drop-shift').click(function(e){
  e.preventDefault();
       
  jQuery.ajax({
    url: 'pages/shift-drop-employee-selection.php',
    type: 'GET',
    data: 'id='+jQuery(this).attr('data-rel'),//send some unique piece of data like the ID to retrieve the corresponding user information
    success: function(data){
      //construct the data however, update the HTML of the popup div 
      	  
	  $('#popup').html(data);
      $('#popup').dialog('open');
    }
  });
  
 
	jQuery('#popup').dialog({
	  autoOpen: 'false',
	  modal: 'true',
	  minHeight: '300px',
	  minWidth: '300px',
	  buttons: {
		'Godkänn': function(){
		  jQuery.ajax({
			url: 'actions/shift-approve-drop-request.php',
			type: 'POST',
			data: jQuery(this).find('form').serialize(),
			success: function(data){
              setTimeout("window.location.reload()");
			}
		  });
		},
		'Avbryt' : function(){
		  jQuery(this).dialog('close');
		}
	  }
	});  

});	
</script>
