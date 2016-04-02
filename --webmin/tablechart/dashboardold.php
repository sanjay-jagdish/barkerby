<?php

	$type_id = $_SESSION['login']['type'];

function query_status($number){

	$query = mysql_query("SELECT count(id) AS bookings FROM reservation WHERE date = '".date("m/d/Y")."' AND status = '".$number."' AND deleted = '0' ");

	while($row = mysql_fetch_assoc($query)){
		$seated = $row['bookings'];
	}

	return $seated;
}


	$query=mysql_query("select * from type where id=".$type_id) or die(mysql_error());
		$row=mysql_fetch_assoc($query);

		$nav_item['menu'] = $row['menu'];
		$nav_item['category']=$row['category'];
		$nav_item['sub_category']=$row['sub_category'];
		$nav_item['reservation']=$row['reservation'];
		$nav_item['overview']=$row['overview'];
		$nav_item['order_status']=$row['order_status'];
		$nav_item['customers']=$row['customers'];
		$nav_item['the_staff']=$row['the_staff'];
		$nav_item['users']=$row['users'];
		$nav_item['announcement']=$row['announcement'];
		$nav_item['shift_request']=$row['shift_request'];
		$nav_item['scheduler']=$row['scheduler'];
		$nav_item['scheduler_chart']=$row['scheduler_chart'];
		$nav_item['reports']=$row['reports'];
		$nav_item['logs']=$row['logs'];
		$nav_item['booking_report']=$row['booking_report'];
		$nav_item['booking_report']=$row['booking_report'];
		$nav_item['settings']=$row['settings'];
		$nav_item['tables']=$row['tables'];		
		$nav_item['table_masterlist']=$row['table_masterlist'];			
		$nav_item['floorplan']=$row['floorplan'];		
		$nav_item['account']=$row['account'];
		$nav_item['advanced_settings']=$row['advanced_settings'];	
		$nav_item['notifications']=$row['notifications'];

		


		?>
	

<div class="page dashboard-page">


<div id="left-content">

	
		<div class="border-padding">
			<div class="dashboard-icons">
			   
			           <a href="?page=controlpanel"> <h2 class="item-control-panel">  Kontrollpanel  </h2> </a>
			 </div>
		 </div>
	

    
	    <div class="border-padding">
			<div class="dashboard-icons">
			   
			           <a class="menu" href="?page=menu"> <h2 class="item-menu parent">  Menyer  </h2> </a>
			 </div>

			 <ul class="sub-link">
			 	<li><a class="category" href="?page=category&parent=menu">Kategori</a></li>
			 	<li><a class="sub_category" href="?page=subcategory&parent=menu">Sub Kategori</a></li>
			 </ul>

		 </div>
	 

	 
		 <div class="border-padding">
			 <div class="dashboard-icons" >
			   
			           <a class="reservation" href="?page=orders"> <h2 class="item-orders parent">  Bokningar  </h2> </a>
			 </div>

			 <ul class="sub-link">
			 	<li><a class="overview" href="?page=dashboard&overview=1">Boken</a></li>
			 	<li><a class="order_status" href="?page=order-status&parent=orders">Beställningsstatus</a></li>
			 </ul>

		 </div>
	 

	 
		 <div class="border-padding">
			 <div class="dashboard-icons" >
			   
			            <a class="customers" href="?page=customers"> <h2 class="item-customers parent">  Gäster  </h2> </a>
			 </div>
		 </div>
	 

	 
		 <div class="border-padding">
			 <div class="dashboard-icons" >
			   
			            <a class="s" href="?page=users&parent=staff"> <h2 class="item-users  parent"> Personal </h2> </a>
			 </div>

			  <ul class="sub-link">
			 	<li><a class="users" href="?page=users&parent=staff">Användare</a></li>
			 	<li><a class="announcement" href="?page=announcement&parent=staff">Utskick</a></li>
			 	<li><a class="shift_request" href="?page=shift&parent=staff">Skiftbyte</a></li>
			 	<li><a class="scheduler" href="?page=scheduler&parent=staff">Schemaläggaren</a></li>
			 	<li><a class="scheduler_chart" href="?page=scheduler-chart&parent=staff">Schema</a></li>
			 </ul>

		 </div>
	 

	 
		 <div class="border-padding">
			 <div class="dashboard-icons" >
			   
			            <a class="reports" href="?page=reports"> <h2 class="item-reports  parent"> Rapporter </h2> </a>
			 </div>

			 <ul class="sub-link">
			 	<li><a class="logs" href="?page=logs&parent=reports">Logg</a></li>
			 	<li><a class="booking_report" href="?page=booking_report&parent=reports">Booking Report</a></li>
			 </ul>

		 </div>
	 

	 
		 <div class="border-padding">
			 <div class="dashboard-icons" >
			   
			            <a class="settings" href="?page=tables&parent=settings"> <h2 class="item-settings  parent"> Inställningar </h2> </a>
			 </div>

			 <ul class="sub-link">
			 	<li><a class="tables" href="?page=tables&parent=settings">Bordsintervaller</a></li>
			 	<li><a class="table_masterlist" href="?page=table-masterlist&parent=settings">Bordsinställning</a></li>
			 	<li><a class="floorplan" href="?page=floorplan&parent=settings">Bordsplan</a></li>
			 	<li><a class="account" href="?page=account&parent=settings">Rättigheter</a></li>
			 	<li><a class="advanced_settings" href="?page=advanced-settings&parent=settings">Avancerade inställningar</a></li>
			 </ul>

		 </div>
	

	 
		 <div class="border-padding">
			 <div class="dashboard-icons" >
			   
			            <a class="notifications" href="?page=notifications"> <h2 class="item-announcement parent">  Push-notiser </h2> </a>  
			 </div>
		 </div>
	 
	 


</div><!-- end left-content -->

<div id="right-content">
	
	<div class="sidebar-widget widget">
		<div class="booking-report widget-wrap">
			<h4>Booking Status for Today</h4>
			<ul>
			<li><?php echo query_status(1); ?> seated</li>
			<li><?php echo query_status(2); ?> waiting to be seated</li>
			<li><?php echo query_status(3); ?> no show</li>
			<li><?php echo query_status(0); ?> Booked-Not Arrived Yet</li>
			</ul>
		</div>
	</div><!-- end sidebar widget -->
    <br /><br />
    <div class="sidebar-widget widget">
		<div class="booking-report widget-wrap">
			<h4>Support</h4>
			<ul>
            <li>Supporten har öppet alla dagar i veckan mellan 09:00 och 21:00 </li>
			<li>Telefon: 072-200 37 99</li>
			<li>E-post: <a href="mailto:support@icington.com">support@icington.com</a></li>
			</ul>
		</div>
	</div><!-- end sidebar widget -->

</div><!-- end right content -->


</div> <!-- end menu-page	 -->

	<script type="text/javascript">
		<?php
		foreach ($nav_item as $item => $value) {

							# code...
					if( $value == 0){

					?>
						if ( jQuery( ".<?php echo $item;?> h2" ).hasClass( "parent" ) ){

								jQuery(".<?php echo $item;?> h2").addClass('blur');
								jQuery(".<?php echo $item;?>").removeAttr( "href" );


						}else{

					<?php

						echo 'jQuery( ".'.$item.'" ).remove();';
						

					?>	
						} //end inner if
					<?php

					} //end if
				}
		?>


	</script>

