jQuery(function( $ ){

	// Enable responsive menu icon for mobile
	$("header .menu-primary_menu-container").addClass("responsive-menu").before('<div id="responsive-menu-icon"></div>');

	$("#responsive-menu-icon").click(function(){
		$("header .menu-primary_menu-container").slideToggle();
	});

	$(window).resize(function(){
		if(window.innerWidth > 768) {
			$("header .menu-primary_menu-container").removeAttr("style");
		}
	});

});