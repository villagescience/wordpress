<?php
	require_once( 'classes/woo-meta.class.php' );
	$woo_meta_manager = new Woo_Meta( 'woo_meta_', dirname( __FILE__ ), trailingslashit( get_template_directory_uri() . '/includes/woo-meta/' ), '1.0.0' );
?>