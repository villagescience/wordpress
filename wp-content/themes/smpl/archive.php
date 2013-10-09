<?php get_header(); ?>
    
    <div id="content" class="col-full">
		
		<?php if ( $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<section id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</section><!--/#breadcrumbs -->
		<?php } ?> 
		
		<section id="main" class="col-left">

		<?php if (have_posts()) : $count = 0; ?>
        
            <?php if (is_category()) { ?>
        	<header class="archive-header">
        		<h1 class="fl"><?php _e( 'Archive', 'woothemes' ); ?> | <?php echo single_cat_title(); ?></h1> 
        		<span class="fr archive-rss"><?php $cat_id = get_cat_ID( single_cat_title( '', false ) ); echo '<a href="' . get_category_feed_link( $cat_id, '' ) . '">' . __( "RSS feed for this section", "woothemes" ) . '</a>'; ?></span>
        	</header>        
        
            <?php } elseif (is_day()) { ?>
            <header class="archive-header">
            	<h1><?php _e( 'Archive', 'woothemes' ); ?> | <?php the_time( get_option( 'date_format' ) ); ?></h1>
            </header>

            <?php } elseif (is_month()) { ?>
            <header class="archive-header">
            	<h1><?php _e( 'Archive', 'woothemes' ); ?> | <?php the_time( 'F, Y' ); ?></h1>
            </header>

            <?php } elseif (is_year()) { ?>
            <header class="archive-header">
            	<h1><?php _e( 'Archive', 'woothemes' ); ?> | <?php the_time( 'Y' ); ?></h1>
            </header>

            <?php } elseif (is_author()) { ?>
            <header class="archive-header">
            	<h1><?php _e( 'Archive by Author', 'woothemes' ); ?></h1>
            </header>

            <?php } elseif (is_tag()) { ?>
            <header class="archive-header">
            	<h1><?php _e( 'Tag Archives:', 'woothemes' ); ?> <?php echo single_tag_title( '', true ); ?></h1>
            </header>
            
            <?php } ?>

        <?php
        	// Display the description for this archive, if it's available.
        	woo_archive_description();
        ?>
        
	        <div class="fix"></div>
        
			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); $count++; ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to overload this in a child theme then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );
				?>

			<?php endwhile; ?>
            
        <?php else: ?>
        
            <article <?php post_class(); ?>>
                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            </article><!-- /.post -->
        
        <?php endif; ?>  
    
			<?php woo_pagenav(); ?>
                
		</section><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>