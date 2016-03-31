<?php include_once('redirect.php'); ?>
<script type="text/javascript">
	jQuery(function(){
		jQuery('.viewdetail').click(function(){
			jQuery('body').scrollTop( 0 );
			
			var customer = jQuery(this).attr('data-rel');
			var id = jQuery(this).attr('data-id');
			
			jQuery('.customer-order-detail h2').html(customer+" - Beställningshistorik");
			
			jQuery('.odoutput').html('<center><img src="images/loader.gif" style="margin: 50px 0 0;"></center>');
			
			jQuery.ajax({
				 url: "pages/customer-order-detail.php",
				 type: 'POST',
				 data: 'id='+encodeURIComponent(id),
				 success: function(value){
					jQuery('.odoutput').html(value);		
				 }
			});
			
			
			jQuery('.fade2, .customer-order-detail').fadeIn();
		});
		
		jQuery('.odclose').click(function(){
			jQuery('.fade2, .customer-order-detail').fadeOut();
		});
	});
</script>
<style type="text/css">
	
	.viewdetail:hover{
		cursor:pointer;
	}
	
	.customer-order-detail{
		position:absolute;
		width:900px;
		min-height:300px;
		background:#fff;
		margin:0 auto;
		left:0;
		right:0;
		top:20%;
		padding:20px;
		display:none;
		z-index:999;
	}
	
	.odheader h2{
		color: #e67e22;
		font-size:20px;
		font-weight:normal;
		float:left;
	}
	
	.odheader{
		padding:10px;
		background:#eee;
		margin-bottom:30px;
	}
	
	.odclose{
		float:right;
		font-weight:bold;
		text-decoration:none;
		color:#000;
		font-size:20px;
	}
	
	.odoutput{
		overflow:scroll;
		overflow-x:hidden;
		max-height:400px;
	}
	
</style>
<div class="page customers-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<span>&nbsp;</span>
            <h2>
            	<?php
					echo 'Gäster';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
        <div class="page-header-right">
        	<a href="?page=customers&subpage=add-customer" class="add-customer">Skapa ny</a>
        </div>
        <!-- end .page-header-right -->
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
    	<table cellpadding="0" cellspacing="0" border="0" class="display" id="thecustomers">
            <thead>
                <tr>
                	<th>#</th>
                    <th>E-post</th>
                    <th>Namn</th>
                    <th>Adress</th>
                    <th>Mobilnummer</th>
                    <th>Åtgärd</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $q=mysql_query("select email, concat(fname,' ', mname,' ', lname), street_name,city,state,zip, phone_number, mobile_number, id, country from account where type_id=5 and deleted=0 order by id desc") or die(mysql_error());
					
					$count=0;
                    
                    while($r=mysql_fetch_array($q)){
						
						$count++;
						
						$address='';
						
						if($r[2]!=''){
							$address.=$r[2].',';
						}
						if($r[3]!=''){
							$address.=$r[3];
						}
						if($r[4]!=''){
							
							$comma='';
							
							if($address!=''){
								$comma=',';
							}
							
							$address.=$comma.$r[4];
							
						}
						if($r[5]!=''){
							
							$comma='';
							
							if($address!=''){
								$comma=',';
							}
							
							$address.=$comma.$r[5];
							
						}
						if($r[9]!=''){
							
							$comma='';
							
							if($address!=''){
								$comma=',';
							}
							
							$address.=$comma.$r[9];
						}
						
                ?>
                    <tr class="gradeX gradeX-<?php echo $r[8];?> viewdetail" align="center" data-rel="<?php echo $r[1];?>" data-id="<?php echo $r[8];?>">
                    	<td><?php echo $count; ?></td>
                        <td><?php echo $r[0];?></td>
                        <td><?php echo $r[1];?></td>
                        <td><?php echo $address;?></td>
                        <td><?php echo $r[6];?></td>
                        <td>
                        <a href="?page=customers&subpage=edit-customer&id=<?php echo $r[8]; ?>&parent=customers" class="edit-customer" title="Redigera Gäster"><img src="images/edit.png" alt="Redigera Gäster"></a>
                        <?php if($_SESSION['login']['type']<3){ ?>	
                        <a href="javascript:void" class="delete-customer" title="Radera Gäster" data-rel="<?php echo $r[8]; ?>"><img src="images/delete.png" alt="Radera Gäster"></a>
                        <?php } ?>
                        </td>
                    </tr>
                <?php		
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                	<th>#</th>
                    <th>E-post</th>
                    <th>Namn</th>
                    <th>Adress</th>
                    <th>Mobilnummer</th>
                    <th>Åtgärd</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="fade2"></div>
<div class="delete-customer-box modalbox">
	<h2>Confirm Delete<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Are you sure you want to proceed?</p>
        <input type="button" value="Delete">
    </div>
</div>

<div class="customer-order-detail">
	<div class="wrap">
    	<div class="odheader">
    		<h2>Customer Order Detail</h2>
            <a href="javascript:void(0);" class="odclose">X</a>
            <div class="clear"></div>
        </div>
        <div class="odoutput">
        	<center><img src="images/loader.gif" style="margin: 50px 0 0;"></center>
        </div>
    </div>
</div>