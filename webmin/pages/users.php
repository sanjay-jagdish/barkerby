<?php include_once('redirect.php'); ?>
<div class="page users-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
					echo 'Personalkonton';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
        <div class="page-header-right">
        	<a href="?page=users&subpage=add-user&parent=staff" class="add-user">Skapa ny</a>
        </div>
        <!-- end .page-header-right -->
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
    	<table cellpadding="0" cellspacing="0" border="0" class="display" id="theusers">
            <thead>
                <tr>
                    <th>Roll</th>
                    <th>E-post</th>
                    <th>Namn</th>
                    <th>Mobilnummer</th>
                    <th>Åtgärd</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $q=mysql_query("select t.description, a.email, concat(a.fname,' ',a.lname), a.phone_number, a.mobile_number, a.id from type as t, account as a where t.id<>5 and t.id=a.type_id and a.deleted=0") or die(mysql_error());
                    
                    while($r=mysql_fetch_array($q)){
                ?>
                    <tr class="gradeX gradeX-<?php echo $r[5];?>" align="center">
                        <td><?php echo $r[0];?></td>
                        <td><?php echo $r[1];?></td>
                        <td><?php echo ucwords($r[2]);?></td>
                        <td><?php echo $r[3];?></td>
                        <td>
                        	<?php if($r[5]<>1){ ?>
                        	<a href="?page=users&subpage=edit-user&id=<?php echo $r[5]; ?>&parent=staff" class="edit-user" title="Redigera Användare"><img src="images/edit.png" alt="Redigera Användare"></a> <?php if($_SESSION['login']['type']<3){ ?><a href="javascript:void" class="delete-user" title="Radera Användare" data-rel="<?php echo $r[5]; ?>"><img src="images/delete.png" alt="Radera Användare"></a><?php } ?>
                        	<?php } else echo '-'; ?>
                        </td>
                    </tr>
                <?php		
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Roll</th>
                    <th>E-post</th>
                    <th>Namn</th>
                    <th>Mobilnummer</th>
                    <th>Åtgärd</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="fade"></div>
<div class="delete-user-box modalbox">
	<h2>Bekräfta borttagning<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Vill du fortsätta? </p>
        <input type="button" value="Utför">
    </div>
</div>