<?php
/**
 * Loop - Tumblog Taxonomy
 *
 * This is the tumblog taxonomy loop file, used on the `tumblog` taxonomy template.
 *
 * @package WooFramework
 * @subpackage Template
 */
 global $more; $more = 0;
 
// Get taxonomy query object
$taxonomy_archive_query_obj = $wp_query->get_queried_object();
// Taxonomy term name
$taxonomy_term_nice_name = $taxonomy_archive_query_obj->name;
// Taxonomy term slug
$taxonomy_term_slug = $taxonomy_archive_query_obj->slug;

woo_loop_before();
		
if (have_posts()) { $count = 0;

	$title_before = '<span class="archive_header">';
	$title_after = '</span>';
	
	woo_archive_title( $title_before, $title_after );
?>

<div class="fix"></div>

<?php
	while (have_posts()) { the_post(); $count++;

		woo_get_template_part( 'content', 'tumblog' );

	} // End WHILE Loop
} else {
	get_template_part( 'content', 'noposts' );
} // End IF Statement

woo_loop_after();

woo_pagenav();
?>