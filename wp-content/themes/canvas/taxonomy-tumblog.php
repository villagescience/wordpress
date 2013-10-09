<?php
/**
 * "Tumblog" Taxonomy Archive Template
 *
 * This template file is used when displaying an archive of posts in the
 * "tumblog" taxonomy. This is used with WooTumblog.
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
            	<?php get_template_part( 'loop', 'tumblog' ); ?>
            </section><!-- /#main -->
            <?php woo_main_after(); ?>
    
            <?php get_sidebar(); ?>
    
		</div><!-- /#main-sidebar-container -->         

		<?php get_sidebar( 'alt' ); ?>       

    </div><!-- /#content -->
	<?php woo_content_after(); ?>
	
<?php get_footer(); ?>