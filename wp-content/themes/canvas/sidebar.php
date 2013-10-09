<?php
/**
 * Sidebar Template
 *
 * If a `primary` widget area is active and has widgets, display the sidebar.
 *
 * @package WooFramework
 * @subpackage Template
 */
	global $woo_options;
	
	$layout = $woo_options['woo_layout'];
	// Cater for custom portfolio gallery layout option.
	if ( is_tax( 'portfolio-gallery' ) || is_post_type_archive( 'portfolio' ) ) {
		$portfolio_gallery_layout = get_option( 'woo_portfolio_layout' );
		
		if ( $portfolio_gallery_layout != '' ) { $layout = $portfolio_gallery_layout; }
	}
	
	if ( $layout != 'one-col' ) {

		if ( woo_active_sidebar( 'primary' ) ) {
	
			woo_sidebar_before();
?>
<aside id="sidebar">
	<?php
		woo_sidebar_inside_before();
		woo_sidebar('primary');
		woo_sidebar_inside_after();
	?>
</aside><!-- /#sidebar -->
<?php
			woo_sidebar_after();
		} // End IF Statement
	} // End IF Statement
?>