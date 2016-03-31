<?php
add_action( 'genesis_meta', 'outreach_home_genesis_meta' );
/**
 * Add widget support for homepage. If no widgets active, display the default loop.
 *
 */
function outreach_home_genesis_meta() {

	if ( is_active_sidebar( 'hem' ) || is_active_sidebar( 'avhamtningsmeny-sections' ) || is_active_sidebar( 'bokabord-sections' ) || is_active_sidebar( 'lunchmeny-sections' ) || is_active_sidebar( 'vinlista-sections' ) || is_active_sidebar( 'kontakta-oss' ) ) {

		remove_action( 'genesis_loop', 'genesis_do_loop' );
		add_action( 'genesis_loop', 'outreach_home_featured' );
		add_action( 'genesis_before_footer', 'outreach_home_sections', 3 );
		add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
		add_filter( 'body_class', 'add_body_class' );

		function add_body_class( $classes ) {
   			$classes[] = 'outreach';
  			return $classes;
		}

	}
}

function outreach_home_featured() {

	if ( is_active_sidebar( 'home-featured' ) ) {
	   genesis_widget_area( 'home-featured', array(
	       'before' => '<div class="home-featured widget-area">'
	   ) );
	}

}

function outreach_home_sections() {

	if ( is_active_sidebar( 'hem' ) || is_active_sidebar( 'avhamtningsmeny-sections' ) || is_active_sidebar( 'bokabord-sections' ) || is_active_sidebar( 'catering-sections' ) ||  is_active_sidebar('veckanslunch-sections') || is_active_sidebar('lordagslunch-sections') || is_active_sidebar( 'lunchmeny-sections' ) || is_active_sidebar('omoss-sections') || is_active_sidebar( 'vinlista-sections' ) || is_active_sidebar( 'kontakta-oss' )) {
		
		
		echo '<div id="hem-sections" class="the-section scroll-section"><div class="wrap">';
		   genesis_widget_area( 'hem', array(
		       'before' => '<div class="hem widget-area">',
		   ) );
		echo '</div><!-- end .wrap --></div><!-- end #hem-sections -->';
		
		
		echo '<div id="avhamtningsmeny-sections" class="scroll-section"><div class="wrap">';
		   genesis_widget_area( 'avhamtningsmeny', array(
		       'before' => '<div class="avhamtningsmeny widget-area">',
		   ) );
		echo '</div><!-- end .wrap --></div><!-- end #avhamtningsmeny-sections -->';
		
		
		echo '<div id="bgimg1" class="scroll-section"><div class="wrap">';
		   genesis_widget_area( 'bgimg1', array(
		       'before' => '<div class="meny widget-area">',
		   ) );
		echo '</div><!-- end .wrap --></div><!-- end #meny-sections -->';
		
		
		
		echo '<div id="meny-sections" class="scroll-section"><div class="wrap">';
		   genesis_widget_area( 'meny', array(
		       'before' => '<div class="meny widget-area">',
		   ) );
		echo '</div><!-- end .wrap --></div><!-- end #meny-sections -->';
			
			echo '<div id="bgimg2" class="scroll-section"><div class="wrap">';
		   genesis_widget_area( 'bgimg2', array(
		       'before' => '<div class="bgimg2 widget-area">',
		   ) );
		echo '</div><!-- end .wrap --></div><!-- end #meny-sections -->';
		
		echo '<div id="catering-sections" class="scroll-section"><div class="wrap">';
		   genesis_widget_area( 'catering', array(
		       'before' => '<div class="catering widget-area">',
		   ) );
		echo '</div><!-- end .wrap --></div><!-- end #catering-sections -->';
		
	
		
		
		  echo '<div id="bgimg3" class="scroll-section"><div class="wrap">';
		   genesis_widget_area( 'bgimg3', array(
		       'before' => '<div class="meny widget-area">',
		   ) );
		echo '</div><!-- end .wrap --></div><!-- end #meny-sections -->';
		   
		
		echo '<div id="veckanslunch-sections" class="the-section scroll-section"><div class="wrap">';
		   genesis_widget_area( 'veckanslunch', array(
		       'before' => '<div class="veckanslunch widget-area">',
		   ) );
		   echo '</div><!-- end .wrap --></div><!-- end #veckanslunch-sections -->';
		   
		 
		   echo '<div id="lordagslunch-sections" class="the-section scroll-section"><div class="wrap">';
		   genesis_widget_area( 'lordagslunch', array(
		       'before' => '<div class="lordagslunch widget-area">',
		   ) );
		echo '</div><!-- end .wrap --></div><!-- end #lordagslunch-sections -->';
		
		echo '<div id="lunchmeny-sections" class="scroll-section"><div class="wrap">';
		   genesis_widget_area( 'lunchmeny', array(
		       'before' => '<div class="lunchmeny widget-area">',
		   ) );
		echo '</div><!-- end .wrap --></div><!-- end #lunchmeny-sections -->';
	    
		
		
		echo '<div id="serveringsmeny-sections" class="scroll-section"><div class="wrap">';
		   genesis_widget_area( 'serveringsmeny', array(
		       'before' => '<div class="serveringsmeny widget-area">',
		   ) );
		echo '</div><!-- end .wrap --></div><!-- end #serveringsmeny-sections -->';
		
		
		echo '<div id="vinlista-sections" class="scroll-section"><div class="wrap">';
		   genesis_widget_area( 'vinlista', array(
		       'before' => '<div class="vinlista widget-area">',
		   ) );
		echo '</div><!-- end .wrap --></div><!-- end #vinlista-sections -->';
		 echo '<div id="bgimg4" class="scroll-section"><div class="wrap">';
		   genesis_widget_area( 'bgimg4', array(
		       'before' => '<div class="meny widget-area">',
		   ) );
		echo '</div><!-- end .wrap --></div><!-- end #meny-sections -->';
		
		
		echo '<div id="omoss-sections" class="the-section scroll-section"><div class="wrap">';
		   genesis_widget_area( 'omoss', array(
		       'before' => '<div class="omoss widget-area">',
		   ) );
		   
		echo '</div><!-- end .wrap --></div><!-- end #omoss-sections -->';
		echo '<div id="bgimg5" class="scroll-section"><div class="wrap">';
		   genesis_widget_area( 'bgimg5', array(
		       'before' => '<div class="meny widget-area">',
		   ) );
		echo '</div><!-- end .wrap --></div><!-- end #meny-sections -->';
		
		echo '<div id="kontakta-oss-sections" class="scroll-section"><div class="wrap">';
		   genesis_widget_area( 'kontakta-oss', array(
		       'before' => '<div class="kontakta-oss widget-area">',
		   ) );
		echo '</div><!-- end .wrap --></div><!-- end #kontakta-oss-sections -->';
		
		echo '<div id="bgimg6" class="scroll-section"><div class="wrap">';
		   genesis_widget_area( 'bgimg6', array(
		       'before' => '<div class="kontakta-oss widget-area">',
		   ) );
		echo '</div><!-- end .wrap --></div><!-- end #kontakta-oss-sections -->';
		
		
		
		
	}

}

genesis();