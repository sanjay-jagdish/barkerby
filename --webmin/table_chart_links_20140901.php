<meta charset="utf-8">
<link rel="stylesheet" href="tablechart/css/bootstrap.css">
<link rel="stylesheet" href="tablechart/css/bootstrap-responsive.css">
<link rel="stylesheet" href="tablechart/styles.css">
<script src="tablechart/libs/underscore-min.js"></script>
<script src="tablechart/libs/jquery.min.js"></script>
<script src="tablechart/libs/jquery-migrate-1.2.1.min.js"></script>
<script src="tablechart/libs/jquery.scrollTo.js"></script>
<script src="tablechart/libs/bootstrap.js"></script>

<!-- for localization(swedish) -->
<script type="text/javascript" src="tablechart/libs/jquery.ui.datepicker-sv.js"></script>

<script src="tablechart/libs/jquery.dataTables.js"></script>
<script src="tablechart/libs/dataTables.scroller.js"></script>
<script src="tablechart/libs/FixedColumns.js"></script>
<!--script src="tablechart/main.js"></script -->

<script src="tablechart/js/jQuerytypeahead/typeahead.js"></script>
<link href="tablechart/js/jQuerytypeahead/examples.css" rel="stylesheet" type="text/css" />

<script src="tablechart/js/jquery-ui-timepicker-0.3.3/jquery.ui.timepicker.js"></script>

<link rel="stylesheet" href="tablechart/js_css/jquery-ui.css">
<script src="tablechart/js_css/jquery-ui.js"></script>

<link rel="stylesheet" media="all" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<script type="text/javascript">
	
		$(document).ready(function(){

			setInterval(function() {

					var date_now = jQuery('#get_date').val();
				
			  					jQuery.ajax({

												url: "tablechart/calendar-data.php",
												type: 'GET',
												 data: 'date='+date_now,
												success: function(value){
													jQuery('.fadediv, .loaddiv').fadeOut();
													jQuery('#calendar-wrapper').html(value);
													// alert(value);
												}
											});
			},180000);


			});

</script>