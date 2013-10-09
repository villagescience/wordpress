<?php
/**
 * Single Portfolio Item Template
 *
 * This template is the default portfolio item template. It is used to display content when someone is viewing a
 * singular view of a portfolio item ('portfolio' post_type).
 * @link http://codex.wordpress.org/Post_Types#Post
 *
 * @package WooFramework
 * @subpackage Template
 */

get_header();
global $woo_options;

$post_settings = woo_portfolio_item_settings( $post->ID );
?>
       
    <!-- #content Starts -->
	<?php woo_content_before(); ?>
    <div id="content" class="col-full">
    
    	<div id="main-sidebar-container">    

            <!-- #main Starts -->
            <?php woo_main_before(); ?>
            <section id="main">                       
<?php
	woo_loop_before();
	
	if ( have_posts() ) { $count = 0;
		while ( have_posts() ) { the_post(); $count++;
			
			/* If we have a video embed code. */
			if ( $post_settings['embed'] != '' ) {
				canvas_get_embed();
			}
			
			/* If we have a gallery and don't have a video embed code. */
			if ( ( $post_settings['enable_gallery'] == 'true' ) && ( $post_settings['embed'] == '' ) ) {
				locate_template( array( 'includes/gallery.php' ), true );
			}
			
			/* If we don't have a gallery and don't have a video embed code. */
			if ( ( $post_settings['enable_gallery'] == 'false' ) && ( $post_settings['embed'] == '' ) ) {
				echo '<div id="post-gallery" class="portfolio-img">' . woo_image( 'noheight=true&return=true&key=portfolio-image&width=' . $post_settings['width'] . '&class=portfolio-img' ) . '</div><!--/#post-gallery .portfolio-img-->' . "\n";
			}
?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<h2><?php the_title(); ?></h2>
		<?php woo_post_meta(); ?>
    	<section class="entry">	
    	<?php the_content(); ?>
		<?php
			/* Portfolio item extras (testimonial, website button, etc). */
			woo_portfolio_item_extras( $post_settings );
		?>
   		</section><!--/.entry-->
   	</div><!--/#post-->
<?php	
		}
	}
	
	woo_loop_after();
?>     
            </section><!-- /#main -->
            <?php woo_main_after(); ?>
    
            <?php get_sidebar(); ?>

		</div><!-- /#main-sidebar-container -->         

		<?php get_sidebar( 'alt' ); ?>

    </div><!-- /#content -->
	<?php woo_content_after(); ?>

<?php get_footer(); ?>