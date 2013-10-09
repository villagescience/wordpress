<?php
/**
 * Template Name: Archives Page
 *
 * The archives page template displays a conprehensive archive of the current
 * content of your website on a single page.
 *
 * @package WooFramework
 * @subpackage Template
 */

 get_header();
?>
    <!-- #content Starts -->
	<?php woo_content_before(); ?>
    <div id="content" class="page col-full">

    	<div id="main-sidebar-container">

            <!-- #main Starts -->
            <?php woo_main_before(); ?>
            <section id="main">

				<?php woo_loop_before(); ?>
                <!-- Post Starts -->
                <?php woo_post_before(); ?>
                <article <?php post_class(); ?>>

                    <?php woo_post_inside_before(); ?>

                    <h2 class="title"><?php the_title(); ?></h2>

                    <section class="entry">

                        <h3><?php _e( 'The Last 30 Posts', 'woothemes' ); ?></h3>
                        <ul>
                            <?php $loop = new WP_Query( array( 'posts_per_page' => 30 ) ); ?>
                            <?php if ( $loop->have_posts() ) { while ( $loop->have_posts() ) { $loop->the_post(); ?>
                                <?php $loop->is_home = false; ?>
                                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> - <?php the_time( get_option( 'date_format' ) ); ?> - <?php echo $post->comment_count; ?> <?php _e( 'comments', 'woothemes' ); ?></li>
                            <?php } } wp_reset_postdata(); ?>
                        </ul>

                        <h3><?php _e( 'Categories', 'woothemes' ); ?></h3>

                        <ul>
                            <?php wp_list_categories( 'title_li=&hierarchical=0&show_count=1' ); ?>
                        </ul>

                        <h3><?php _e( 'Monthly Archives', 'woothemes' ); ?></h3>

                        <ul>
                            <?php wp_get_archives( 'type=monthly&show_post_count=1' ); ?>
                        </ul>

                    </section><!-- /.entry -->

                    <?php woo_post_inside_after(); ?>

                </article><!-- /.post -->
                <?php woo_post_after(); ?>

            </section><!-- /#main -->
            <?php woo_main_after(); ?>

            <?php get_sidebar(); ?>

		</div><!-- /#main-sidebar-container -->

		<?php get_sidebar( 'alt' ); ?>

    </div><!-- /#content -->
	<?php woo_content_after(); ?>

<?php get_footer(); ?>