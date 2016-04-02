<?php include_once('redirect.php'); ?>
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
					echo 'Logg';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
       
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
    	<table cellpadding="0" cellspacing="0" border="0" class="display" id="thelogs">
            <thead>
                <tr>
                    <th>Användare</th>
                    <th>Beskrivning </th>
                    <th>Tid och datum</th>
                    <th>IP-Adress</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $q=mysql_query("select concat(a.lname,', ',a.fname), l.description, l.date_time, l.id, l.ip_address from log as l, account as a where a.id=l.account_id order by date_time desc") or die(mysql_error());
                    
                    while($r=mysql_fetch_array($q)){
                ?>
                    <tr class="gradeX gradeX-<?php echo $r[3];?>" align="center">
                        <td><?php echo ucwords($r[0]);?></td>
                        <td><?php echo $r[1];?></td>
                        <td><?php echo date('M d, Y G:i:s',strtotime($r[2])); ?></td>
                        <td><?php echo $r[4];?></td>
                    </tr>
                <?php		
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Account</th>
                    <th>Description</th>
                    <th>Date and Time</th>
                    <th>IP Address</th>
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