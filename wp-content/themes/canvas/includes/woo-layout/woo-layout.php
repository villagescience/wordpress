<?php
	require_once( 'classes/woo-layout.class.php' );
	$woo_layout_manager = new Woo_Layout( 'woo_layout_', dirname( __FILE__ ), trailingslashit( get_template_directory_uri() . '/includes/woo-layout/' ), '1.0.0' );
?>