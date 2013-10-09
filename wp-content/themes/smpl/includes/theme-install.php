<?php

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Hook in on activation
- Install

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Hook in on activation */
/*-----------------------------------------------------------------------------------*/

global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) add_action('init', 'woo_install_theme', 1);

/*-----------------------------------------------------------------------------------*/
/* Install */
/*-----------------------------------------------------------------------------------*/

function woo_install_theme() {

// Image sizes
update_option( 'woocommerce_thumbnail_image_width', '200' );
update_option( 'woocommerce_thumbnail_image_height', '200' );
update_option( 'woocommerce_single_image_width', '600' ); 
update_option( 'woocommerce_single_image_height', '600' ); 
update_option( 'woocommerce_catalog_image_width', '265' );
update_option( 'woocommerce_catalog_image_height', '265' );

// Hard Crop
update_option( 'woocommerce_thumbnail_image_crop', 1 );
update_option( 'woocommerce_single_image_crop', 1 ); 
update_option( 'woocommerce_catalog_image_crop', 1 );

}