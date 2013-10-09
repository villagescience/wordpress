<?php
/**
 * Template Name: Business
 *
 * The business page template displays your posts with a "business"-style
 * content slider at the top. 
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options, $wp_query; 
 get_header();
 
 $page_template = woo_get_page_template();
?>
    <!-- #content Starts -->
	<?php woo_content_before(); ?>
    <div id="content" class="col-full business">
    
    	<div id="main-sidebar-container">

			<?php if ( $woo_options['woo_slider_biz'] == 'true' ) { $saved = $wp_query; woo_slider_biz(); $wp_query = $saved; } ?>
            <!-- #main Starts -->
            <?php woo_main_before(); ?>
            <section id="main">      
<?php
	woo_loop_before();
	
	if ( have_posts() ) { $count = 0;
		while ( have_posts() ) { the_post(); $count++;
			woo_get_template_part( 'content', 'page-template-business' ); // Get the page content template file, contextually.
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