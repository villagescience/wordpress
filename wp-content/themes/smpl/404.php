<?php get_header(); ?>
       
    <div id="content" class="col-full">
		<section id="main" class="col-left">
                                                                                
            <div class="type-page">
				
				<header>
                	<h1><?php _e( 'Error 404 - Page not found!', 'woothemes' ); ?></h1>
                </header>
                <section class="entry">
                	<p><?php _e( 'The page you trying to reach does not exist, or has been moved. Please use the menus or the search box to find what you are looking for.', 'woothemes' ); ?></p>
                </section>

            </div><!-- /.post -->
                                                
        </section><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>