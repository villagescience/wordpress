<?php
/*-------------------------------------------------------------------------------------

NOTE: These functions are for internal use within the theme and are not intended for
manipulation via a child theme.

TABLE OF CONTENTS

- 1. Get the current page template
- 2. Load a template part into a template

-------------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* 1. Get the current page template */
/*-----------------------------------------------------------------------------------*/
/**
 * Get the current page template
 *
 * Get the current page template in use.
 *
 * @since 4.0
 *
 * @package WooFramework
 * @subpackage Template
 */
 
function woo_get_page_template() {

	global $wp_query;

	$page = $wp_query->get_queried_object();
	$custom_fields = get_post_custom_values('_wp_page_template',$page->ID);
	$page_template = $custom_fields[0];

	return $page_template;

} // End woo_get_page_template()

/*-----------------------------------------------------------------------------------*/
/* 2. Load a template part into a template */
/*-----------------------------------------------------------------------------------*/
/**
 * Load a template part into a template
 *
 * Makes it easy for a theme to reuse sections of code in a easy to overload way
 * for child themes.
 *
 * Includes the named template part for a theme or if a name is specified then a
 * specialised part will be included. If the theme contains no {slug}.php file
 * then no template will be included.
 *
 * The template is included using require, not require_once, so you may include the
 * same template part multiple times.
 *
 * For the parameter, if the file is called "{slug}-special.php" then specify
 * "special".
 *
 * This version of the `get_template_part()` function has been enhanced to include
 * additional checks for "{slug}-POST_SLUG.php" and "{slug}-POST_ID.php" if within
 * the loop. It also checks for "{slug}-POST_FORMAT.php" if using WordPress 3.1+.
 *
 * @uses locate_template()
 * @since 4.0.0
 * @uses do_action() Calls 'get_template_part_{$slug}' action.
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialised template.
 */

function woo_get_template_part ( $slug, $name = null ) {

	global $post, $wp_version, $wp_query;

	do_action( "get_template_part_{$slug}", $slug, $name );
	
	$templates = array();

	// Template files specific to this post in single and archive scenarios.
	if ( isset( $post ) ) {
	
		$post_type = get_post_type();
	
		// Specific files.
		
		// Archive.
		if ( is_archive() || is_home() || is_search() ) {
			$templates[] = "{$slug}-archive-{$post->post_name}.php";
			$templates[] = "{$slug}-archive-" . $post_type . "-{$post->ID}.php";
			$templates[] = "{$slug}-archive-" . $post_type . ".php";
		}
		
		// Single.
		if ( is_singular() ) {
			$templates[] = "{$slug}-single-{$post->post_name}.php";
			$templates[] = "{$slug}-single-" . $post_type . "-{$post->ID}.php";
		}
		
		$templates[] = "{$slug}-{$post->post_name}.php";
		if ( $post_type != 'post' ) { $templates[] = "{$slug}-" . $post_type . "-{$post->ID}.php"; }
		$templates[] = "{$slug}-post-{$post->ID}.php";
		
		// More generic files.
		
		// Archive.
		if ( is_archive() || is_home() ) {
			$templates[] = "{$slug}-archive-" . $post_type . ".php";
			$templates[] = "{$slug}-archive.php";
		}
		
		// Single.
		if ( is_singular() ) {
			$templates[] = "{$slug}-single-" . $post_type . ".php";
			$templates[] = "{$slug}-single.php";
		}
	
	}

	// Template file for post format.
	if ( $wp_version >= '3.1' )
		if ( isset( $post ) && get_post_format( $post ) )
			$templates[] = "{$slug}-format-" . get_post_format( $post ) . '.php';

	// If it's a tumblog post, and WooTumblog is present.
	if ( function_exists( 'woo_tumblog_test' ) && defined( 'WOOTUMBLOG_ACTIVE' ) ) {
	
		$is_tumblog = woo_tumblog_test();
		if ( $is_tumblog ) {
			$templates[] = "{$slug}-tumblog.php";
		}
	}
	
	// Template file as per the parameters.
	if ( isset( $name ) )
		$templates[] = "{$slug}-{$name}.php";

	// Generic $slug template file.
	$templates[] = "{$slug}.php";
	
	$templates = apply_filters( 'woo_template_parts', $templates );
	
	locate_template( $templates, true, false );

} // End woo_get_template_part()

/*-----------------------------------------------------------------------------------*/
/* 3. Re-order Theme menu items in WordPress admin */
/*-----------------------------------------------------------------------------------*/

add_action( 'admin_menu', 'canvas_reorder_admin_menu', 99 );

function canvas_reorder_admin_menu () {
	global $submenu;
	
	if ( ! array_key_exists( 'woothemes', $submenu ) ) { return ; }
	
	$items_to_move = array();
	$first_item = array();
	
	foreach ( $submenu['woothemes'] as $k => $s ) {
		if ( in_array( $s[2], array( 'woo-layout-manager', 'woo-hook-manager', 'woo-meta-manager', 'woothemes_sbm' ) ) ) {
			$items_to_move[] = $s;
			unset( $submenu['woothemes'][$k] );
		}
		
		if ( $k == 0 ) { $first_item[] = $s; unset( $submenu['woothemes'][$k]); }
	}
	
	sort( $items_to_move );
	
	$remaining_items = $submenu['woothemes'];
	
	// Grab the first item and unset it from the main array.
	$submenu['woothemes'] = array_merge( $first_item, $items_to_move, $remaining_items );

} // End canvas_reorder_admin_menu()
?>