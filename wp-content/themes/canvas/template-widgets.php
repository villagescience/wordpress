<?php
/**
 * Template Name: Widgets
 *
 * This template displays content from the "Widgets Page Template" registered sidebar.
 * If no widgets are present in this registered sidebar, the default page content is displayed instead.
 *
 * It is possible to override this registered sidebar for multiple pages using the WooSidebars plugin ( http://woothemes.com/woosidebars ).
 *
 * @package WooFramework
 * @subpackage Template
 */

get_header();
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
	
	if ( is_active_sidebar( 'widgets-page-template' ) ) {
?>
<div id="widgets-container">
<?php dynamic_sidebar( 'widgets-page-template' ); ?>
</div><!--/#widgets-container-->
<?php
	} else {
		if ( have_posts() ) { $count = 0;
			while ( have_posts() ) { the_post(); $count++;
				woo_get_template_part( 'content', 'page' ); // Get the page content template file, contextually.
			}
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