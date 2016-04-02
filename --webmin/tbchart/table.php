<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
		<table cellpadding="0" cellspacing="0" border="1" class="table table-condensed avails-table table-bordered">
        	<thead>
            	<tr>
                	<th>&nbsp;</th>
				<?php 
				$time = strtotime('08:00:00');
				$end = strtotime('09:00:00');
				
				while($time<=$end){
					?>
                    <th><?php echo date('H:i',$time); ?></th>
                    <?php
					$time = strtotime(date('H:i:s',$time).' + 15 mins');
				}
				?>	
                	<th>&nbsp;</th>
        		</tr>
            </thead>
            <tbody>
            	<?php
				$tables = range(1,5); //30 tables
				foreach($tables as $tbl_num){
				?>
                <tr>
                	<td align="center">Table# <?php echo $tbl_num; ?></td>
				<?php
				$time = strtotime('08:00:00');
				$end = strtotime('09:00:00');
				
				while($time<=$end){
					?>
                    <td><?php echo date('H:i',$time); ?></td>
                    <?php
					$time = strtotime(date('H:i:s',$time).' + 15 mins');
				}
				?>	
                	<th>&nbsp;</th>
                </tr>	
                <?php	
				}
				?>
            </tbody>
        </table>	    
</body>
</html>