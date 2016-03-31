<?php include_once('redirect.php'); ?>
<div class="page reports-page">
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
					echo 'Rapporter';
				?>
            </h2>
        </div>
        <!-- end .page-header-left -->
       
    </div>
    <!-- end .page-header -->
    
    <div class="clear"></div>
    
    <div class="page-content">
 	   	
     <!-- content here -->
     <link rel="stylesheet" href="css/reports.css" />
    
      <div class="report_header">Gäster</div>
        	<div class="report_options">
				<ul>            	
                	<!-- li><a href="reports/top_customers_by_sales.php" target="_blank">Top Customers by Sales</a></li>
                	<li><a href="reports/customer_sign_ups.php" target="_blank">Customer Sign-Ups</a></li -->
                	<li><a href="reports/customer_list_by_names.php" target="_blank">Namnlista</a></li>
                	<li>Statistik</li>
                </ul>
            </div>
 
      <div class="report_header">Bordsbokningar</div>
        	<div class="report_options">
				<ul>            	
                	<li><a href="reports/reservations_upcoming.php" target="_blank">Kommande</a></li>
                	<li>No show</li>
                	<li>Avklarade</li>
                	<li>Avbokade</li>
                	<li>Statistik</li>
                </ul>
            </div>

      <div class="report_header">Take Away</div>
        	<div class="report_options">
				<ul>            	
                	<li><a href="reports/orders_upcoming.php" target="_blank">Kommande</a></li>
                    <li>Avklarade</li>
                    <li>Avbokade</li>
                    <li>Statistik</li>
                </ul>
            </div>


  </div>
</div>

<div class="fade"></div>
<div class="delete-report-box modalbox">
	<h2>Confirm Delete<a href="#" class="closebox">X</a></h2>
    <div class="box-content">
        <p>Are you sure you want to proceed?</p>
        <input type="button" value="Delete">
    </div>
</div>