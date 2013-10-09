<?php
/**
 * "Portfolio Gallery" Taxonomy Archive Template
 *
 * This template file is used when displaying an archive of posts in the
 * "portfolio-gallery" taxonomy. This is used with WooTumblog.
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options; 
 get_header();
?>
    <div id="content" class="col-full">
		<section id="portfolio-gallery" class="page">
		           
		<?php if ( isset( $woo_options['woo_breadcrumbs_show'] ) && $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<section id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</section><!--/#breadcrumbs -->
		<?php } ?>  			

        <?php if ( have_posts() ) : $count = 0; ?>                                                           
            <article <?php post_class(); ?>>
				<?php get_template_part( 'loop', 'portfolio' ); ?>
            </article><!-- /.post -->
            
        <?php else : ?>
			<article <?php post_class(); ?>>
            	<p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            </article><!-- /.post -->
        <?php endif; ?>  
        
		</section><!-- /#portfolio-gallery -->

    </div><!-- /#content -->
		
<?php get_footer(); ?>