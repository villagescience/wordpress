<?php
/**
 * Template Name: Feedback
 *
 * The feedback page template displays a list of the
 * feedback entries.
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options, $paged, $wp_query;
 get_header();
?>
    <!-- #content Starts -->
	<?php woo_content_before(); ?>
    <div id="content" class="col-full">

    	<div id="main-sidebar-container">

            <!-- #main Starts -->
            <?php woo_main_before(); ?>
            <section id="main">

				<?php woo_loop_before(); ?>
                <!-- Post Starts -->
                <?php woo_post_before(); ?>
                <article <?php post_class( 'post' ); ?>>

                    <?php woo_post_inside_before(); ?>

                    <h1 class="title"><?php the_title(); ?></h1>

                    <section class="entry">
					<?php the_content(); ?>
					<?php
						$page = $wp_query->query_vars['page'];

						$query_args = array( 'post_type' => 'feedback' );
						if ( $paged > 0 ) {
							$query_args['paged'] = $paged;
						}

						// Correct the pagination logic if this page is used as a static front page.
						if ( $page > 0 && is_front_page() ) {
							$query_args['paged'] = $page;
						}

						$loop = new WP_Query( $query_args );

						if ( $loop->have_posts() ) {
							while ( $loop->have_posts() ) {
								$loop->the_post();

								$meta = get_post_custom( $post->ID );
					?>
						<div id="quote-<?php echo $post->ID; ?>" class="quote">
							<blockquote class="feedback-text"><?php the_content(); ?></blockquote>
					<?php
						if ( ( isset( $meta['feedback_author'] ) && ( $meta['feedback_author'][0] != '' ) ) || ( isset( $meta['feedback_url'] ) && ( $meta['feedback_url'][0] != '' ) ) ) {
					?>
						<cite class="feedback-author">
						<?php echo $meta['feedback_author'][0]; ?>
						<?php
							if ( isset( $meta['feedback_url'] ) && ( $meta['feedback_url'][0] != '' ) ) {
								echo '<a href="' . esc_url( $meta['feedback_url'][0] ) . '" title="' . esc_attr( $author_text ) . '" class="feedback-url">' . esc_url( $meta['feedback_url'][0] ) . '</a> &rarr;';
							}
						?>
						</cite><!--/.feedback-author-->
					<?php
						}
					?>
						</div><!--/.quote-->
					<?php
							}
							wp_reset_postdata();
						}
					?>
                    </section><!-- /.entry -->

                    <?php woo_post_inside_after(); ?>

                </article><!-- /.post -->
                <?php woo_post_after(); ?>
                <div class="fix"></div>
                 <?php woo_pagenav(); ?>
            </section><!-- /#main -->
            <?php woo_main_after(); ?>

            <?php get_sidebar(); ?>

		</div><!-- /#main-sidebar-container -->

		<?php get_sidebar( 'alt' ); ?>

    </div><!-- /#content -->
	<?php woo_content_after(); ?>

<?php get_footer(); ?>