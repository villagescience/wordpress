<?php
/**
 * Template Name: Magazine
 *
 * The magazine page template displays your posts with a "magazine"-style
 * content slider at the top and a grid of posts below it. 
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options, $post; 
 get_header();

 if ( is_paged() ) $is_paged = true; else $is_paged = false;
 
 $page_template = woo_get_page_template();
?>

    <!-- #content Starts -->
	<?php woo_content_before(); ?>
    <div id="content" class="col-full magazine">
    
    	<div id="main-sidebar-container">

            <!-- #main Starts -->
            <?php woo_main_before(); ?>
            <section id="main">
            	<?php woo_loop_before(); ?>   
             	<?php if ( $woo_options['woo_slider_magazine'] == 'true' && ! $is_paged ) { if ( get_option( 'woo_exclude' ) ) update_option( 'woo_exclude', '' ); woo_slider_magazine(); } ?>
             	<div class="fix"></div>
				<?php get_template_part( 'loop', 'magazine' ); ?>  
            </section><!-- /#main -->
            <?php woo_main_after(); ?>
    
            <?php get_sidebar(); ?>
            
		</div><!-- /#main-sidebar-container -->         

		<?php get_sidebar( 'alt' ); ?>

    </div><!-- /#content -->
	<?php woo_content_after(); ?>
    
		
<?php get_footer(); ?>