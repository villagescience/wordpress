<?php
/**
 * Loop - Archive
 *
 * This is the loop logic used on all archive screens.
 *
 * To override this loop in a particular archive type (in all categories, for example), 
 * duplicate the `archive.php` file and rename the duplicate to `category.php`.
 * In the code of `category.php`, change `get_template_part( 'loop', 'archive' );` to 
 * `get_template_part( 'loop', 'category' );` and save the file.
 *
 * Create a duplicate of this file and rename it to `loop-category.php`.
 * Make any changes to this new file and they will be reflected on all your category screens.
 *
 * @package WooFramework
 * @subpackage Template
 */
 global $more; $more = 0;
 
woo_loop_before();
if (have_posts()) { $count = 0;

	$title_before = '<h1 class="archive_header">';
	$title_after = '</h1>';
	
	woo_archive_title( $title_before, $title_after );
	
	// Display the description for this archive, if it's available.
	woo_archive_description();
?>

<div class="fix"></div>

<?php
	while (have_posts()) { the_post(); $count++;

		woo_get_template_part( 'content', get_post_type() );

	} // End WHILE Loop
} else {
	get_template_part( 'content', 'noposts' );
} // End IF Statement

woo_loop_after();

woo_pagenav();
?>