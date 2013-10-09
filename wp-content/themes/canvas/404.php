<?php
/**
 * 404 Template
 *
 * This template is displayed when the page being requested by the viewer cannot be found
 * or doesn't exist. From here, we'll try to assist the user and keep them browsing the website.
 * @link http://codex.wordpress.org/Pages
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
            <section id="main" class="col-left">
<?php
	woo_loop_before();
		woo_get_template_part( 'content', '404' ); // Get the 404 content template file, contextually.
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