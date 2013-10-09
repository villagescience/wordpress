<?php
	require_once( 'classes/woo-hooks.class.php' );
	$woo_meta_manager = new Woo_Hook_Manager( 'woo_hooks_', dirname( __FILE__ ), trailingslashit( get_template_directory_uri() . '/includes/woo-hooks/' ), '1.1.0' );
?>