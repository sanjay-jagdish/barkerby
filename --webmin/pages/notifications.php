<?php include_once('redirect.php'); ?>
<div class="page announcement-page">
<div class="page-header">
   	<div class="page-header-left">
       	<span>&nbsp;</span>
           <h2>
           	<?php
               /*	if(isset($_GET['page'])){
					if(isset($_GET['subpage'])){
						echo ucwords(removeDashTitle($_GET['subpage']));
					}
					else{
						echo ucwords(removeDashTitle($_GET['page']));
					}
				}*/
				echo 'Push-notiser';
			?>
           </h2>
       </div>
       <!-- end .page-header-left -->
       <div class="page-header-right">
       	<a href="?page=notifications&subpage=add-notification" class="add-notification">Skapa ny</a>
       </div>
       <!-- end .page-header-right -->
   </div>
   <!-- end .page-header -->
   
   <div class="clear"></div>
   
   <style>
   .draft td{ background-color: #F8F8F8; color:#000; }
   .sched td{ background-color: #FFCC99; color:#000;  }
   .pushed td{ background-color: #99FFCC; color:#000; }
   .draft{ background-color: #F8F8F8; padding:5px 8px; border-radius:15px; }
   .sched{ background-color: #FFCC99; padding:5px 8px; border-radius:15px; }
   .pushed{ background-color: #99FFCC; padding:5px 8px; border-radius:15px; }
   .gradeX:hover td{ background-color: #FFFF66; }
   .gradeX:hover .stats { border:solid thin #787878; }
  </style>
   
   <div class="page-content">
   	<table cellpadding="0" cellspacing="0" border="0" class="display" id="thenotifications">
           <thead>
               <tr>
               	   <th>ID</th>
               	   <th>Titel</th>
                   <th>Skapad av</th>
                   <th>Skapades</th>
                   <th>Push tid</th>
                   <th>Status</th>
                   <th>Åtgärd</th>
               </tr>
           </thead>
           <tbody>
               <?php
                   $q=mysql_query("SELECT n.id, concat(ac.fname, ' ', ac.mname, ' ' ,ac.lname) as name, 
                   			n.subject, n.created, n.date, n.time, n.status, n.delivered 
				   FROM notification n, account as ac 
				   WHERE ac.id=n.account_id and n.deleted=0 
				   ORDER BY id DESC") or die(mysql_error());
                   
                   while($r=mysql_fetch_array($q)){

		   switch($r['status']){
				case 0:
					$class_status = 'draft';
					$status_txt = '<div class="stats draft">Utkast</div>';
					$bgcolor = 'none;';
					break;
				case 1:
					$class_status = 'sched';
					$status_txt = '<div class="stats sched">Sched.</div>';
					$bgcolog = '';
					break;					   
				case 2:
					$class_status = 'pushed';
					$status_txt = '<div class="stats pushed">Pushad</div>';
					$bgcolor = 'lightgreen';
					break;
		   }                   
                   
               ?>
                   <tr class="gradeX gradeX-<?php echo $r[0]?> <?php echo $class_status; ?>" align="center">
                       <td><?php echo $r['id'];?></td>
                       <td align="left"><?php echo $r['subject'];?></td>
                       <td><?php echo $r['name'];?></td>
                       <td><?php echo date('m/d/y h:ia', strtotime($r[3]));?></td>
                       <td>
				<?php 
				if($r['status']==2){
						echo date("m/d/y h:ia",strtotime($r['delivered']));
				}else{
					if(trim($r['date'])!=''){
						echo date("m/d/y",strtotime($r['date'])).' '.date("h:ia",strtotime($r['time']));
					}else{
						//echo 'Not Set'; 
						echo 'Ej bestämd'; 
					}
				}
				?>
                       </td>
                       <td>
				<?php 
				echo $status_txt;
				?>
                       </td>
                       <td align="right" nowrap="nowrap">
                       <?php
                       if($r['status']<2){
                       ?>
                       <a href="?page=notifications&subpage=edit-notification&id=<?php echo $r[0]; ?>" class="edit-notitification" title="Redigera Push-notiser"><img src="images/edit.png" alt="Redigera Push-notiser"></a> 
                       <?php
                       }//if status not pushed yet
                       ?>
                       
                       <?php if($_SESSION['login']['type']<3){ ?>
                       <a href="javascript:void" class="delete-notification" title="Radera Push-notiser" data-rel="<?php echo $r[0]; ?>"><img src="images/delete.png" alt="Radera Push-notiser"></a> 
                       <?php } ?>
                       <a href="javascript:void" class="copy-notification" title="Kopiera Push-notiser" data-rel="<?php echo $r[0]; ?>"><img src="images/copy.png" alt="Kopiera Push-notiser" style="width:20px;"></a></td>
                   </tr>
               <?php	
                   }
               ?>
           </tbody>
           <tfoot>
               <tr>
               	   <th>ID</th>
               	   <th>Titel</th>
                   <th>Skapad av</th>
                   <th>Skapades</th>
                   <th>Push tid</th>
                   <th>Status</th>
                   <th>Åtgärd</th>
               </tr>
           </tfoot>
       </table>
   </div>
</div>

<div class="fade"></div>
<div class="delete-notification-box modalbox">
<h2>Confirm Delete<a href="#" class="closebox">X</a></h2>
   <div class="box-content">
       <p>Are you sure you want to proceed?</p>
       <input type="button" value="Delete">
   </div>
</div>

<div class="copy-notification-box modalbox">
<h2>Confirm Copy Notification<a href="#" class="closebox">X</a></h2>
   <div class="box-content">
       <p>Are you sure you want to proceed?</p>
       <input type="button" value="Copy">
   </div>
</div>