<?php
/**
 * Template Name: Tags
 *
 * The tags page template displays a user-friendly tag cloud of the
 * post tags used on your website.
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options; 
 get_header();
?>
       
    <div id="content" class="page col-full">
		
		<?php if ( isset( $woo_options['woo_breadcrumbs_show'] ) && $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<section id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</section><!--/#breadcrumbs -->
		<?php } ?>  
		
		<section id="main" class="fullwidth">
            
            <article <?php post_class(); ?>>
				
				<header>
					<h1><?php the_title(); ?></h1>
				</header>
                
	            <?php if ( have_posts() ) { the_post(); ?>
            	<section class="entry">
            		<?php the_content(); ?>
            	</section>	            	
	            <?php } ?>  
	            
                <div class="tag_cloud">
        			<?php wp_tag_cloud( 'number=0' ); ?>
    			</div><!--/.tag-cloud-->

            </article><!-- /.post -->
        
		</section><!-- /#main -->
		
    </div><!-- /#content -->
		
<?php get_footer(); ?>