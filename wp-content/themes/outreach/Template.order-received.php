<?php
/*
Template Name: Order Received
*/
get_header();

?>

<div class="thankyoupage">
	<div class="thankyoupage-inner">
		<p style="color:white;"><strong>Bekräftelse </strong><br> Tack för din beställning. <br> Om beställningen har gjorts under våra öppettider så kommer en bekräftelse på din beställning att mails till dig inom kort. Vänligen kontrollera din skräpkorg om du inte har fått någon bekräftelse inom 5 minuter. <br> Om du har lagt din beställning under tiden vi har stängt så skickas mailas en bekräftelse till dig så snart vi har öppnat.</p>
    </div>
</div>

<script>
jQuery(function($){
	$("#menu-primary_menu li > a").each(function() {	
	  	var hr = $(this).attr('href');
	  	if(hr.charAt(0) == "#"){		 
			$(this).attr('href','<?php echo home_url(); ?>/'+ hr);
	  	}
	});
})
</script>
<?php get_footer(); ?>