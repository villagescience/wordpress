<?php
if ( ! is_admin() ) { add_action( 'wp_enqueue_scripts', 'woothemes_add_javascript' ); }

if ( ! function_exists( 'woothemes_add_javascript' ) ) {
	function woothemes_add_javascript() {
		wp_enqueue_script( 'jquery' );    
		wp_enqueue_script( 'third party', get_template_directory_uri() . '/includes/js/third-party.js', array( 'jquery' ) );
		wp_enqueue_script( 'general', get_template_directory_uri() . '/includes/js/general.js', array( 'jquery' ) );
		wp_register_script( 'flexslider', get_template_directory_uri() . '/includes/js/jquery.flexslider.min.js', array( 'jquery' ) );
		wp_register_script( 'prettyPhoto', get_template_directory_uri() . '/includes/js/jquery.prettyPhoto.js', array( 'jquery' ) );
		wp_register_script( 'portfolio', get_template_directory_uri() . '/includes/js/portfolio.js', array( 'jquery', 'prettyPhoto' ) );
		wp_register_script( 'tabs', get_template_directory_uri() . '/includes/js/tabs.js', array( 'jquery' ) );
		
		
	
		do_action( 'woothemes_add_javascript' );
		
		// Load the tabs on the homepage
		if (is_home()) {
			wp_enqueue_script( 'tabs' ); 
		}
		
		// Load portfolio scripts on portfolio pages
		
		if ( is_page_template( 'template-portfolio.php' ) || is_front_page() || ( is_singular() && get_post_type() == 'portfolio' ) || is_tax( 'portfolio-gallery' ) || is_post_type_archive( 'portfolio' ) ) {			
			wp_enqueue_script( 'portfolio' );
			wp_enqueue_script( 'prettyPhoto' );
		}
		
		// Only load the slider on the homepage
		
		if ( is_home() OR is_front_page() ){
			global $woo_options;
			if ( isset( $woo_options['woo_slider'] ) && ( $woo_options['woo_slider'] == 'true' ) ) {
				wp_enqueue_script( 'flexslider' );
			}
			
			// Load the custom slider settings.
			
			$autoStart = false;
			$autoSpeed = 6;
			$slideSpeed = 0.5;
			$effect = 'slide';
			$nextprev = 'true';
			$pagination = 'true';
			$hoverpause = 'true';
			$autoheight = 'false';
			
			// Get our values from the database and, if they're there, override the above defaults.
			$fields = array(
							'autoStart' => 'auto', 
							'autoSpeed' => 'interval', 
							'slideSpeed' => 'speed', 
							'effect' => 'effect', 
							'nextprev' => 'nextprev', 
							'pagination' => 'pagination', 
							'hoverpause' => 'hover', 
							'autoHeight' => 'autoheight'
							);
			
			foreach ( $fields as $k => $v ) {
				if ( is_array( $woo_options ) && isset( $woo_options['woo_slider_' . $v] ) && $woo_options['woo_slider_' . $v] != '' ) {
					${$k} = $woo_options['woo_slider_' . $v];
				}
			}
			
			// Set auto speed to 0 if we want to disable automatic sliding.
			if ( $autoStart == 'false' ) {
				$autoSpeed = 0;
			}
			
			$data = array(
						'speed' => $slideSpeed, 
						'auto' => $autoSpeed, 
						'effect' => $effect, 
						'nextprev' => $nextprev, 
						'pagination' => $pagination, 
						'hoverpause' => $hoverpause, 
						'autoheight' => true
						);
						
			wp_localize_script( 'general', 'woo_slider_settings', $data );
		}
		
		// Only load selectBox on certain pages
		if ( class_exists( 'woocommerce' ) ) { 
			$editaddress = woocommerce_get_page_id('edit_address');
			if (!is_checkout() && !is_cart() && !is_product() && !is_page($editaddress)) {
				
				// Add selectBox on all pages except cart and checkout. Scroll bar fix if needed; https://github.com/claviska/jquery-selectBox/issues/53#issuecomment-3470736
				wp_enqueue_script( 'selectbox', get_template_directory_uri() . '/includes/js/jquery.selectBox.min.js', array( 'jquery' ) );
				wp_enqueue_script( 'mousewheel', get_template_directory_uri() . '/includes/js/jquery.mousewheel.min.js', array( 'jquery' ) );
				add_action('wp_head','smpl_noncheckout_scripts');
			}
			
			if (!function_exists('smpl_noncheckout_scripts')) {
				function smpl_noncheckout_scripts() { 
				// Fire these scripts on the product pages
					?>
						
						<script>
							jQuery(document).ready(function() {
								jQuery("#content select").selectBox({
									'menuTransition': 'fade',
									'menuSpeed' : 'fast'
								});
							});
						</script>
						
					<?php
				}
			}
		}
		
		if ( ! is_admin() ) { add_action( 'wp_print_styles', 'woothemes_add_css' ); }

		if ( ! function_exists( 'woothemes_add_css' ) ) {
			function woothemes_add_css () {
				
				wp_register_style( 'prettyPhoto', get_template_directory_uri().'/includes/css/prettyPhoto.css' );
			
				if ( is_page_template('template-portfolio.php') || is_front_page() || ( is_singular() && get_post_type() == 'portfolio' ) || is_tax( 'portfolio-gallery' ) || is_post_type_archive( 'portfolio' ) ) {
					wp_enqueue_style( 'prettyPhoto' );
				}
			
				do_action( 'woothemes_add_css' );
			
			} // End woothemes_add_css()
		}
	}
}
?>