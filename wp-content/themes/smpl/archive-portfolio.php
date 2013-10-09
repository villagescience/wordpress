<?php
/**
 * "Portfolio" Post Type Archive
 *
 * The portfolio post type archive template displays your portfolio items with
 * a switcher to quickly filter between the various portfolio galleries. 
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options; 
 get_header();
?>
    <div id="content" class="col-full">
    
    	<div class="page">
    
			<?php if ( isset( $woo_options['woo_breadcrumbs_show'] ) && $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
				<section id="breadcrumbs">
					<?php woo_breadcrumbs(); ?>
				</section><!--/#breadcrumbs -->
			<?php } ?>  
			
			<section id="portfolio-gallery" class="type-page portfolio-archive">
			           
	        <?php if ( have_posts() ) : $count = 0; ?>                                                           
	            <div <?php post_class(); ?>>
					<?php get_template_part( 'loop', 'portfolio' ); ?>
	            </div><!-- /.post -->
	            
	        <?php else : ?>
				<article <?php post_class(); ?>>
	            	<p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
	            </article><!-- /.post -->
	        <?php endif; ?>  
	        
			</section><!-- /#portfolio-gallery -->
		
		</div>

    </div><!-- /#content -->
		
<?php get_footer(); ?>