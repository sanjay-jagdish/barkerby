<?php include_once('redirect.php'); ?>
<div class="page currency-page">
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
					echo 'Valuta';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
        <div class="page-header-right">
        	<a href="?page=currency&subpage=add-currency&parent=settings" class="add-category">Skapa ny</a>
        </div>
        <!-- end .page-header-right -->
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
    	<table cellpadding="0" cellspacing="0" border="0" class="display" id="thecurrencies">
            <thead>
                <tr>
                    <th>Valuta</th>
                    <th>Förkortning</th>
                    <th>Standard</th>
                    <th>Åtgärd</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $q=mysql_query("select id,name,shortname, set_default from currency where deleted=0") or die(mysql_error());
                    
                    while($r=mysql_fetch_array($q)){
                ?>
                    <tr class="gradeX gradeX-<?php echo $r[0];?>" align="center">
                        <td><?php echo ucwords($r[1]);?></td>
                        <td><?php echo strtoupper($r[2]);?></td>
                        <td><input type="radio" name="currency" data-rel="<?php echo $r[0]; ?>" class="the_currency" <?php if($r[3]==1) echo 'checked="checked"';?>></td>
                        <td><a href="?page=currency&subpage=edit-currency&id=<?php echo $r[0]; ?>&parent=settings" class="edit-currency" title="Redigera Valuta"><img src="images/edit.png" alt="Redigera Valuta"></a> <?php if($_SESSION['login']['type']<3){ ?><a href="#" class="delete-currency" title="Radera Valuta" data-rel="<?php echo $r[0]; ?>"><img src="images/delete.png" alt="Radera Valuta"></a><?php } ?></td>
                    </tr>
                <?php		
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Valuta</th>
                    <th>Förkortning</th>
                    <th>Standard</th>
                    <th>Åtgärd</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="fade"></div>
<div class="delete-currency-box modalbox">
	<h2>Confirm Delete<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Are you sure you want to proceed?</p>
        <input type="button" value="Delete">
    </div>
</div>