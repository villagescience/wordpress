<?php
/**
 * Template Name: Timeline
 *
 * The timeline page template displays a user-friendly timeline of the
 * posts on your website.
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options;
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

                <?php
                	$loop = new WP_Query( array( 'posts_per_page' => '-1' ) );
					$dates_array 			= array();
					$year_array 			= array();
					$i 						= 0;
					$prev_post_ts    		= null;
					$prev_post_year  		= null;
					$distance_multiplier	=  9;
				?>

                <article class="post">

                    <?php woo_post_inside_before(); ?>

                    <h1 class="title"><?php the_title(); ?></h1>

                    <section id="archives" class="entry">

                        <?php while ( $loop->have_posts() ) { $loop->the_post();

							$post_ts    =  strtotime( $post->post_date );
							$post_year  =  date( 'Y', $post_ts );

							/* Handle the first year as a special case */
							if ( is_null( $prev_post_year ) ) {
								?>
								<h3 class="archive_year"><?php echo $post_year; ?></h3>
								<ul class="archives_list">
								<?php
							}
							else if ( $prev_post_year != $post_year ) {
								/* Close off the OL */
								?>
								</ul>
								<?php

								$working_year  =  $prev_post_year;

								/* Print year headings until we reach the post year */
								while ( $working_year > $post_year ) {
									$working_year--;
									?>
									<h3 class="archive_year"><?php echo $working_year; ?></h3>
									<?php
								}

								/* Open a new ordered list */
								?>
								<ul class="archives_list">
								<?php
							}

							/* Compute difference in days */
							if ( ! is_null( $prev_post_ts ) && $prev_post_year == $post_year ) {
								$dates_diff  =  ( date( 'z', $prev_post_ts ) - date( 'z', $post_ts ) ) * $distance_multiplier;
							}
							else {
								$dates_diff  =  0;
							}
						?>
							<li>
								<span class="date"><?php the_time( 'F j' ); ?><sup><?php the_time( 'S' ); ?></sup></span> <span class="linked"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span> <span class="comments icon comment_alt2_fill"><?php comments_popup_link( '0', '1', '%' ); ?></span>
							</li>
						<?php
								/* For subsequent iterations */
								$prev_post_ts    =  $post_ts;
								$prev_post_year  =  $post_year;
							} // End WHILE Loop

							wp_reset_postdata();

							/* If we've processed at least *one* post, close the ordered list */
							if ( ! is_null( $prev_post_ts ) ) {
						?>
						</ul>
					<?php } ?>

                    </section><!-- /.entry -->

                    <?php woo_post_inside_after(); ?>

                </article><!-- /.post -->
                <?php woo_post_after(); ?>
                <div class="fix"></div>

            </section><!-- /#main -->
            <?php woo_main_after(); ?>

            <?php get_sidebar(); ?>

		</div><!-- /#main-sidebar-container -->

		<?php get_sidebar( 'alt' ); ?>

    </div><!-- /#content -->
	<?php woo_content_after(); ?>

<?php get_footer(); ?>