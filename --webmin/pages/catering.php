<?php include_once('redirect.php'); ?>
<div class="page menu-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
                	echo 'Catering';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
        <!--<div class="page-header-right">
        	<a href="?page=catering&subpage=add-catering" class="add-catering">Skapa ny</a>
        </div>-->
        <!-- end .page-header-right -->
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
    	
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="thecatering">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Catering Date & Time</th>
                        <th>Total</th>
                        <th>Transaction Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
					
					
                        $q=mysql_query("select * from catering_detail where status=1 and deleted=0 order by date,time asc") or die(mysql_error());
                        
                        $count=0;
                        while($r=mysql_fetch_assoc($q)){
                            $count++;
                    ?>
                        <tr class="gradeX gradeX-<?php echo $r['id'];?>" align="center">
                            <td><?php echo $count; ?></td>
                            <td><?php echo 'Catering '.$count;?></td>
                            <td><?php echo date("M d, Y",strtotime($r['date'])); ?><br><?php echo $r['time']; ?></td>
                            <td><?php echo $r['total'];?></td>
                            <td><?php echo date("M d, Y",strtotime($r['date_time'])); ?><br><?php echo date("H:i",strtotime($r['date_time'])); ?></td>
                        </tr>
                    <?php		
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Catering Date & Time</th>
                        <th>Total</th>
                        <th>Transaction Date & Time</th>
                    </tr>
                </tfoot>
            </table>
        
    </div>
</div>

<div class="fade"></div>
<div class="delete-catering-box modalbox">
	<h2>Bekräfta borttagning<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Vill du fortsätta?</p>
        <input type="button" value="Utför">
    </div>
</div>