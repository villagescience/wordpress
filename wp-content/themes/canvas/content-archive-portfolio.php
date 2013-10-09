<?php
/**
 * "Portfolio" Content Archive Template
 *
 * This template is the used to display "portfolio" posts when in a generic archive.
 *
 * @package WooFramework
 * @subpackage Template
 */

/**
 * Settings for this template file.
 *
 * This is where the specify the HTML tags for the title.
 * These options can be filtered via a child theme.
 *
 * @link http://codex.wordpress.org/Plugin_API#Filters
 */
 
 $title_before = '<h1 class="title">';
 $title_after = '</h1>';
 
 if ( ! is_single() ) {
 
	 $title_before = $title_before . '<a href="' . get_permalink( get_the_ID() ) . '" rel="bookmark" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">';
	 $title_after = '</a>' . $title_after;
 
 }
 
 $page_link_args = apply_filters( 'woothemes_pagelinks_args', array( 'before' => '<div class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</div>' ) );
 
 woo_post_before();
?>
<article <?php post_class( 'post' ); ?>>
<?php 
	woo_post_inside_before();	
?>
	<header>
		<?php the_title( $title_before, $title_after ); ?>
	</header>
	
	<section class="entry">
	    <?php
	    	echo '<div class="fl portfolio-img">' . woo_image( 'return=true&key=portfolio-image&width=100&height=100' ) . '</div><!--/.fl-->' . "\n";
	    	the_excerpt();
	    	wp_link_pages( $page_link_args );
	    ?>
	</section><!-- /.entry -->
<?php
	woo_post_inside_after();
?>
</article><!-- /.post -->
<?php
	woo_post_after();
	comments_template();
?>