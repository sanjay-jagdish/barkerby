<?php include_once('redirect.php'); ?>
<style>
tr.sent td{ background-color:#9FC; }
tr.sent:hover td{ background-color:#FFC; }
</style>
<div class="page announcement-page">
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
				echo 'Utskick';
			?>
           </h2>
       </div>
       <!-- end .page-header-left -->
       <div class="page-header-right">
       	<a href="?page=announcement&subpage=add-announcement&parent=staff" class="add-announcement">Skapa ny</a>
       </div>
       <!-- end .page-header-right -->
   </div>
   <!-- end .page-header -->
   
   <div class="clear"></div>
   
   <div class="page-content">
   	<table cellpadding="0" cellspacing="0" border="0" class="display" id="theannouncements">
           <thead>
               <tr>
               	   <th>ID</th>
               	   <th>Skapad av</th>
                   <th>Ämne</th>
                   <th>Mottagare</th>
                   <th>Skapad</th>
                   <th>Status</th>
                   <th>Åtgärd</th>
               </tr>
           </thead>
           <tbody>
               <?php
                   $q=mysql_query("select a.id, concat(ac.fname, ' ', ac.mname, ' ' ,ac.lname) as name, a.subject, a.date, a.time, a.recipient, status, delivered 
				   				  from announcement a, account as ac where ac.id=a.account_id and a.deleted=0 order by a.id desc") or die(mysql_error());
                   
                   while($r=mysql_fetch_array($q)){
               ?>
                   <tr class="gradeX gradeX-<?php echo $r[0]; if($r['status']==1){ echo ' sent'; }?>" align="center">
                       <td width="1"><?php echo $r['id'];?></td>
                       <td><?php echo $r['name'];?></td>
                       <td align="left"><?php echo $r['subject'];?></td>
                       <td><?php echo getUsersName($r['recipient']);?></td>
                       <td>
					   <?php echo date('M. j, o', strtotime($r['date']));?><br />
                       <?php echo date("g:i:s A",strtotime($r['time']));?>
                       </td>
                       <td>
					   <?php
					   switch($r['status']){
						   case 0:
						   		echo 'Draft';
								break;
						   case 1:
						   		echo 'Sent<br>'.date('m/d/Y H:i:s A');
								break;
					   }				   
					   ?>
                       </td>
                       <td>
						   <?php
                           if($r['status']==0){
						   ?>
                                <a href="?page=announcement&subpage=edit-announcement&id=<?php echo $r['id']; ?>" class="edit-announcement" 
                                title="Redigera Utskick"><img src="images/edit.png" alt="Redigera Utskick"></a> 
                           <?php
                           }				   
                           ?>
                           
                           <?php if($_SESSION['login']['type']<3){ ?>
                       	   	<a href="#" class="delete-announcement" title="Radera Utskick" 
                       	   data-rel="<?php echo $r['id']; ?>"><img src="images/delete.png" alt="Radera Utskick"></a>
                           <?php } ?> 
                       </td>
                   </tr>
               <?php	
                   }
               ?>
           </tbody>
           <tfoot>
               <tr>
               	   <th>ID</th>
               	   <th>Skapad av</th>
                   <th>Ämne</th>
                   <th>Mottagare</th>
                   <th>Skapad</th>
                   <th>Status</th>
                   <th>Åtgärd</th>
               </tr>
           </tfoot>
       </table>
   </div>
</div>

<div class="fade"></div>
<div class="delete-announcement-box modalbox">
<h2>Confirm Delete<a href="#" class="closebox">X</a></h2>
   <div class="box-content">
       <p>Are you sure you want to proceed?</p>
       <input type="button" value="Delete">
   </div>
</div>