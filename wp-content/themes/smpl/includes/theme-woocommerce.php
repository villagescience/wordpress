<?php
/*-----------------------------------------------------------------------------------*/
/* This theme supports WooCommerce, woo! */
/*-----------------------------------------------------------------------------------*/

add_theme_support( 'woocommerce' );


/*-------------------------------------------------------------------------------------------*/
/* WOOCOMMERCE OVERRIDES */
/*-------------------------------------------------------------------------------------------*/

// Disable WooCommerce styles
define('WOOCOMMERCE_USE_CSS', false);

/*-------------------------------------------------------------------------------------------*/
/* GENERAL LAYOUT */
/*-------------------------------------------------------------------------------------------*/

// Hide the admin bar
add_action('init','smpl_disable_admin_bar');
function smpl_disable_admin_bar() {
	if (!is_admin()) {
		add_filter( 'show_admin_bar', '__return_false' );
		wp_deregister_style( 'admin-bar' );
		remove_action('wp_head', '_admin_bar_bump_cb');
	}
}

// Adjust markup on all WooCommerce pages
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

add_action( 'woocommerce_before_main_content', 'smpl_before_content', 10 );
add_action( 'woocommerce_after_main_content', 'smpl_after_content', 20 );

// Fix the layout etc
if (!function_exists('smpl_before_content')) {
	function smpl_before_content() {
	?>
		<!-- #content Starts -->
		<?php woo_content_before(); ?>
	    <div id="content" class="col-full">
			<?php
				global  $woo_options;
				if ( $woo_options[ 'woo_breadcrumbs_show' ] == 'true' ) {
					echo '<div id="breadcrumbs">';
					woo_breadcrumbs();
					echo '</div>';
				}
			?>
	        <!-- #main Starts -->
	        <?php woo_main_before(); ?>
	        <div id="main" class="col-left">
	    <?php
	}
}

if (!function_exists('smpl_after_content')) {
	function smpl_after_content() {
	?>
			</div><!-- /#main -->
	        <?php woo_main_after(); ?>
			<?php woocommerce_get_sidebar(); ?>
	    </div><!-- /#content -->
		<?php woo_content_after(); ?>
	    <?php
	}
}

// Only display sidebar on product archives if instructed to do so via woo_shop_archives_fullwidth
if (!function_exists('woocommerce_get_sidebar')) {
	function woocommerce_get_sidebar() {
		global $woo_options;

		if (!is_woocommerce()) {
			get_sidebar();
		} elseif ( $woo_options[ 'woo_shop_archives_fullwidth' ] == "false" && (is_woocommerce()) || (is_product()) ) {
			get_sidebar();
		} elseif ( $woo_options[ 'woo_shop_archives_fullwidth' ] == "true" && (is_archive(array('product'))) ) {
			// no sidebar
		}
	}
}

// Add a class to the body if full width shop archives are specified
add_filter( 'body_class','smpl_woocommerce_layout_body_class', 10 );		// Add layout to body_class output

if ( ! function_exists( 'smpl_woocommerce_layout_body_class' ) ) {
	function smpl_woocommerce_layout_body_class( $wc_classes ) {

		global $woo_options;

		$layout = '';

		// Add woocommerce-fullwidth class if full width option is enabled
		if ( $woo_options[ 'woo_shop_archives_fullwidth' ] == "true" && (is_shop() || is_product_category())) {
			$layout = 'woocommerce-fullwidth';
		}

		// Add classes to body_class() output
		$wc_classes[] = $layout;
		return $wc_classes;

	} // End woocommerce_layout_body_class()
}

/*-------------------------------------------------------------------------------------------*/
/* PRODUCTS LOOP */
/*-------------------------------------------------------------------------------------------*/

// Add the inner div in product loop
add_action( 'woocommerce_before_shop_loop_item', 'smpl_product_inner_open', 5, 2);
add_action( 'woocommerce_after_shop_loop_item', 'smpl_product_inner_close', 12, 2);
add_action( 'woocommerce_before_subcategory', 'smpl_product_inner_open', 5, 2);
add_action( 'woocommerce_after_subcategory', 'smpl_product_inner_close', 12, 2);

function smpl_product_inner_open() {
	echo '<div class="inner">';
}
function smpl_product_inner_close() {
	echo '</div> <!--/.inner-->';
}

// Add the subcat div for subcats
add_action( 'woocommerce_before_subcategory', 'smpl_subcat_inner_open', 4, 2);
add_action( 'woocommerce_after_subcategory', 'smpl_subcat_inner_close', 13, 2);
function smpl_subcat_inner_open() {
	echo '<div class="subcat">';
}
function smpl_subcat_inner_close() {
	echo '</div> <!--/.subcat-->';
}


// Add the more details link in product loop
add_action( 'woocommerce_after_shop_loop_item', 'smpl_product_more_details', 11);
function smpl_product_more_details() {
	?>
	<a href="<?php echo get_permalink() ?>" title="<?php echo the_title(); ?>" class="button">
		<?php _e('More Details','woothemes'); ?>
	</a>
	<?php
}

// Change columns in product loop to 3
add_filter('loop_shop_columns', 'loop_columns');

if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3;
	}
}

// Display x products per page based on user input
add_filter('loop_shop_per_page', 'products_per_page');
if (!function_exists('products_per_page')) {
	function products_per_page() {
		global $woo_options;
		if ( isset( $woo_options['woo_products_per_page'] ) ) {
			return $woo_options['woo_products_per_page'];
		}
	}
}

// Remove pagination (we're using the WooFramework default pagination)
// < 2.0
remove_action( 'woocommerce_pagination', 'woocommerce_pagination', 10 );
add_action( 'woocommerce_pagination', 'woocommerceframework_pagination', 10 );
//   2.0 +
if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '>=' ) ) {
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
	add_action( 'woocommerce_after_shop_loop', 'woocommerceframework_pagination', 10 );
}

function woocommerceframework_pagination() {
	if ( is_search() && is_post_type_archive() ) {
		add_filter( 'woo_pagination_args', 'woocommerceframework_add_search_fragment', 10 );
		add_filter( 'woo_pagination_args_defaults', 'woocommerceframework_woo_pagination_defaults', 10 );
	}
	woo_pagination();
}

function woocommerceframework_add_search_fragment ( $settings ) {
	$settings['add_fragment'] = '&post_type=product';

	return $settings;
} // End woocommerceframework_add_search_fragment()

function woocommerceframework_woo_pagination_defaults ( $settings ) {
	$settings['use_search_permastruct'] = false;

	return $settings;
} // End woocommerceframework_woo_pagination_defaults()

// Add wrapping div around pagination
// < 2.0
add_action( 'woocommerce_pagination', 'woocommerce_pagination_wrap_open', 5 );
add_action( 'woocommerce_pagination', 'woocommerce_pagination_wrap_close', 25 );
//   2.0 +
add_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination_wrap_open', 5 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination_wrap_close', 25 );

if (!function_exists('woocommerce_pagination_wrap_open')) {
	function woocommerce_pagination_wrap_open() {
		echo '<section class="pagination-wrap">';
	}
}

if (!function_exists('woocommerce_pagination_wrap_close')) {
	function woocommerce_pagination_wrap_close() {
		echo '</section>';
	}
}

/*-------------------------------------------------------------------------------------------*/
/* BREADCRUMB */
/*-------------------------------------------------------------------------------------------*/

// Remove WC breadcrumb (we're using the WooFramework breadcrumb)
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

// Customise the breadcrumb
add_filter( 'woo_breadcrumbs_args', 'woo_custom_breadcrumbs_args', 10 );

if (!function_exists('woo_custom_breadcrumbs_args')) {
	function woo_custom_breadcrumbs_args ( $args ) {
		$textdomain = 'woothemes';
		$args = array('separator' => '/', 'before' => '', 'show_home' => __( 'Home', $textdomain ),);
		return $args;
	} // End woo_custom_breadcrumbs_args()
}

// Adjust the star rating in the sidebar
add_filter('woocommerce_star_rating_size_sidebar', 'woostore_star_sidebar');

if (!function_exists('woostore_star_sidebar')) {
	function woostore_star_sidebar() {
		return 12;
	}
}

/*-------------------------------------------------------------------------------------------*/
/* SINGLE PRODUCT */
/*-------------------------------------------------------------------------------------------*/

add_action('wp_head','woocommerce_tab_check');
function woocommerce_tab_check() {
	global $woo_options;
	if ( $woo_options[ 'woo_product_tabs' ] == "false" ) {
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
	}
}

// Change columns in upsells output to 3 and move below the product summary
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product', 'woocommerceframework_upsell_display', 10);

if (!function_exists('woocommerceframework_upsell_display')) {
	function woocommerceframework_upsell_display() {
	    woocommerce_upsell_display(3,3); // 3 products, 3 columns
	}
}

// Redefine woocommerce_output_related_products()
function woocommerce_output_related_products() {
	woocommerce_related_products(3,3); // Display 3 products in rows of 3
}

// If theme lightbox is enabled, disable the WooCommerce lightbox and make product images prettyPhoto galleries
add_action( 'wp_footer', 'woocommerce_prettyphoto' );
function woocommerce_prettyphoto() {
	global $woo_options;
	if ( $woo_options[ 'woo_enable_lightbox' ] == "true" ) {
		update_option( 'woocommerce_enable_lightbox', false );
		?>
			<script>
				jQuery(document).ready(function(){
					jQuery('.images a').attr('rel', 'prettyPhoto[product-gallery]');
				});
			</script>
		<?php
	}
}

/*-------------------------------------------------------------------------------------------*/
/* SHORTCODES */
/*-------------------------------------------------------------------------------------------*/

// Sticky shortcode
function woo_shortcode_sticky( $atts, $content = null ) {
   extract( shortcode_atts( array(
      'class' => '',
      ), $atts ) );

   return '<div class="shortcode-sticky ' . esc_attr($class) . '">' . $content . '</div><!--/shortcode-sticky-->';
}

add_shortcode( 'sticky', 'woo_shortcode_sticky' );

// Sale shortcode
function woo_shortcode_sale ( $atts, $content = null ) {
	$defaults = array();
	extract( shortcode_atts( $defaults, $atts ) );
	return '<div class="shortcode-sale"><span>' . $content . '</span></div><!--/.shortcode-sale-->';
}

add_shortcode( 'sale', 'woo_shortcode_sale' );

// Mini features wrap
function woo_shortcode_mini_feature_wrap( $atts, $content = null ) {
   return '<ul class="mini-features">' . do_shortcode($content) . '</ul>';
}

add_shortcode( 'mini-feature-wrap', 'woo_shortcode_mini_feature_wrap' );

// Mini features shortcode
function woo_shortcode_mini_feature( $atts, $content = null ) {
   extract( shortcode_atts( array(
      'icon' => '',
      'title' => '',
      ), $atts ) );

   return '<li class="mini-feature"><img src="' . esc_attr($icon) . '" alt="' . esc_attr($title) . '" /><div class="feature-content"><h3>' . esc_attr($title) . '</h3>' . wpautop($content). '</div></li><!--/mini-feature-->';
}

add_shortcode( 'mini-feature', 'woo_shortcode_mini_feature' );

/*-------------------------------------------------------------------------------------------*/
/* WIDGETS */
/*-------------------------------------------------------------------------------------------*/

// Adjust the star rating in the recent reviews widget
add_filter('woocommerce_star_rating_size_recent_reviews', 'woostore_star_reviews');

if (!function_exists('woostore_star_reviews')) {
	function woostore_star_reviews() {
		return 12;
	}
}

/*-------------------------------------------------------------------------------------------*/
/* AJAX FRAGMENTS */
/*-------------------------------------------------------------------------------------------*/

// Handle cart in header fragment for ajax add to cart
add_filter('add_to_cart_fragments', 'header_add_to_cart_fragment');

function header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;

	ob_start();

	woocommerce_cart_link();

	$fragments['a.cart-button'] = ob_get_clean();

	return $fragments;

}
function woocommerce_cart_link() {
	global $woocommerce;
	?>
	<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?> <?php _e('in your shopping cart', 'woothemes'); ?>" class="cart-button "><?php echo $woocommerce->cart->get_cart_total(); ?></a>
	<script>
		if(jQuery().tipsy) {
			jQuery(document).ready(function() {
				jQuery('.cart-button').tipsy({gravity: 'e', fade: true});
			});
		}
	</script>
	<?php
}

?>