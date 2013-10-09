<?php
/*-----------------------------------------------------------------------------------*/
/* This theme supports WooCommerce, woo! */
/*-----------------------------------------------------------------------------------*/

add_theme_support( 'woocommerce' );


// Load WooCommerce stylsheet
if ( ! is_admin() ) { add_action( 'get_header', 'woo_load_woocommerce_css', 20 ); }

if ( ! function_exists( 'woo_load_woocommerce_css' ) ) {
	function woo_load_woocommerce_css () {
		wp_register_style( 'woocommerce', get_template_directory_uri() . '/css/woocommerce.css' );
		wp_enqueue_style( 'woocommerce' );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Hook in on activation */
/*-----------------------------------------------------------------------------------*/

global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) add_action( 'init', 'woo_install_theme', 1 );

/*-----------------------------------------------------------------------------------*/
/* Install */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_install_theme' ) ) {
	function woo_install_theme() {

		update_option( 'woocommerce_thumbnail_image_width', '200' );
		update_option( 'woocommerce_thumbnail_image_height', '200' );
		update_option( 'woocommerce_single_image_width', '500' ); // Single
		update_option( 'woocommerce_single_image_height', '500' ); // Single
		update_option( 'woocommerce_catalog_image_width', '400' ); // Catalog
		update_option( 'woocommerce_catalog_image_height', '400' ); // Catlog

	}
}

if ( ! function_exists( 'woocommerce_html5' ) ) {
	// Insert HTML5 Shiv
	add_action('wp_head', 'woocommerce_html5');

	function woocommerce_html5() {
		echo '<!--[if lt IE 9]><script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->';
	}
}

if ( ! function_exists( 'checked_environment' ) ) {
	// Check WooCommerce is installed first
	add_action('plugins_loaded', 'checked_environment');

	function checked_environment() {
		if (!class_exists('woocommerce')) wp_die('WooCommerce must be installed');
	}
}

// Disable WooCommerce styles
if ( !defined( 'WOOCOMMERCE_USE_CSS' ) ) { define( 'WOOCOMMERCE_USE_CSS', false ); }

// Remove WC sidebar
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// WooCommerce layout overrides
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'woocommerce_canvas_before_content' ) ) {
	// WooCommerce layout overrides
	add_action( 'woocommerce_before_main_content', 'woocommerce_canvas_before_content', 10 );
	function woocommerce_canvas_before_content() {
	?>
		<!-- #content Starts -->
		<?php woo_content_before(); ?>
	    <div id="content" class="col-full">

	    	<div id="main-sidebar-container">

	            <!-- #main Starts -->
	            <?php woo_main_before(); ?>
	            <section id="main" class="col-left">
	    <?php
	}
}

if ( ! function_exists( 'woocommerce_canvas_after_content' ) ) {
	// WooCommerce layout overrides
	add_action( 'woocommerce_after_main_content', 'woocommerce_canvas_after_content', 20 );
	function woocommerce_canvas_after_content() {
	?>
				</section><!-- /#main -->
	            <?php woo_main_after(); ?>

			</div><!-- /#main-sidebar-container -->

			<?php get_sidebar( 'alt' ); ?>

	    </div><!-- /#content -->
		<?php woo_content_after(); ?>
	    <?php
	}
}

// Add the WC sidebar in the right place
add_action( 'woo_main_after', 'woocommerce_get_sidebar', 10 );

if ( ! function_exists( 'woocommerceframework_breadcrumb' ) ) {
	// Remove breadcrumb (we're using the WooFramework default breadcrumb)
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
	add_action( 'woocommerce_before_main_content', 'woocommerceframework_breadcrumb', 20, 0 );
	function woocommerceframework_breadcrumb() {
		global  $woo_options;
		if ( $woo_options['woo_breadcrumbs_show'] == 'true' ) {
			woo_breadcrumbs();
		}
	}
}

if ( ! function_exists( 'canvas_commerce_pagination' ) ) {
	add_action( 'woocommerce_after_main_content', 'canvas_commerce_pagination', 01, 0 );

	function canvas_commerce_pagination() {
		if ( is_search() && is_post_type_archive() ) {
			add_filter( 'woo_pagination_args', 'woocommerceframework_add_search_fragment', 10 );
		}
		woo_pagenav();
	}
}

if ( ! function_exists( 'woocommerceframework_add_search_fragment' ) ) {
	function woocommerceframework_add_search_fragment ( $settings ) {
		$settings['add_fragment'] = '&post_type=product';
		return $settings;
	} // End woocommerceframework_add_search_fragment()
}

if ( ! function_exists( 'woocommerce_output_related_products' ) ) {
	// Change columns in related products output to 3
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

	function woocommerce_output_related_products() {
		woocommerce_related_products( 3, 3 ); // 3 products, 3 columns
	}
}

// Change columns in upsells to 3
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'woo_wc_upsell_display', 15 );
if (!function_exists('woo_wc_upsell_display')) {
	function woo_wc_upsell_display() {
	    woocommerce_upsell_display( -1, 3 );
	}
}

if ( ! function_exists( 'loop_columns' ) ) {
	// Change columns in product loop to 3
	function loop_columns() {
		return 3;
	}

	add_filter( 'loop_shop_columns', 'loop_columns' );
}

// Remove pagination - we're using WF pagination.
remove_action( 'woocommerce_pagination', 'woocommerce_pagination', 10 ); // < 2.0
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 ); // 2.0 +

// Display 12 products per page
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 12;' ) );

// Fix sidebar on shop page
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

if ( ! function_exists( 'woostore_star_sidebar' ) ) {
	// Adjust the star rating in the sidebar
	add_filter( 'woocommerce_star_rating_size_sidebar', 'woostore_star_sidebar' );

	function woostore_star_sidebar() {
		return 12;
	}
}

if ( ! function_exists( 'woostore_star_reviews' ) ) {
	// Adjust the star rating in the recent reviews
	add_filter( 'woocommerce_star_rating_size_recent_reviews', 'woostore_star_reviews' );

	function woostore_star_reviews() {
		return 12;
	}
}

// Custom place holder
add_filter( 'woocommerce_placeholder_img_src', 'wooframework_wc_placeholder_img_src' );

if ( ! function_exists( 'wooframework_wc_placeholder_img_src' ) ) {
function wooframework_wc_placeholder_img_src( $src ) {
	$settings = array( 'placeholder_url' => get_template_directory_uri() . '/images/wc-placeholder.gif' );
	$settings = woo_get_dynamic_values( $settings );

	return esc_url( $settings['placeholder_url'] );
} // End wooframework_wc_placeholder_img_src()
}

if ( ! function_exists( 'woo_add_nav_cart_link' ) ) {
/**
 * Optionally display a header cart link next to the navigation menu.
 * @since  5.1.0
 * @return void
 */
function woo_add_nav_cart_link () {
	global $woocommerce;
	$settings = array( 'header_cart_link' => 'false', 'nav_rss' => 'false', 'header_cart_total' => 'false' );
	$settings = woo_get_dynamic_values( $settings );

	$class = 'cart fr';
	if ( 'false' == $settings['nav_rss'] ) { $class .= ' no-rss-link'; }
	if ( is_woocommerce_activated() && 'true' == $settings['header_cart_link'] ) { ?>
    	<ul class="<?php echo esc_attr( $class ); ?>">
    		<li>
    			<a class="cart-contents" href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'woothemes' ); ?>">
    				<?php if ( $settings['header_cart_total'] == 'true' ) { echo sprintf( _n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes' ), $woocommerce->cart->cart_contents_count );?> - <?php echo $woocommerce->cart->get_cart_total(); } ?>
    			</a>
    		</li>
   		</ul>
    <?php }
} // End woo_add_nav_cart_link()
}

add_action( 'woo_nav_inside', 'woo_add_nav_cart_link', 10);

// Ensure cart contents update when products are added to the cart via AJAX
add_filter( 'add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );

if ( ! function_exists( 'woocommerce_header_add_to_cart_fragment' ) ) {
	function woocommerce_header_add_to_cart_fragment( $fragments ) {
		global $woocommerce;
		$settings = array( 'header_cart_link' => 'false', 'nav_rss' => 'false', 'header_cart_total' => 'false' );
		$settings = woo_get_dynamic_values( $settings );

		ob_start();
	?>
		<a class="cart-contents" href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>">
			<?php if ( $settings['header_cart_total'] == 'true' ) { echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); } ?>
		</a>
	<?php

		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	} // End woocommerce_header_add_to_cart_fragment()
}
?>