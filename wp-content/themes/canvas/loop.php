<?php
/**
 * Loop
 *
 * This is the default loop file, containing the looping logic for use in all templates
 * where a loop is required.
 *
 * To override this loop in a particular context (in all archives, for example), create a
 * duplicate of this file and rename it to `loop-archive.php`. Make any changes to this
 * new file and they will be reflected on all your archive screens.
 *
 * @package WooFramework
 * @subpackage Template
 */
 global $more; $more = 0;
 
woo_loop_before();
		
if (have_posts()) { $count = 0;
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