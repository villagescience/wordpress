<?php
/**
 * Single Portfolio Template
 *
 * This template is the portfolio item page template. It is used to display content when someone is viewing a
 * singular view of a portfolio item ('portfolio' post_type).
 * @link http://codex.wordpress.org/Post_Types#Post
 *
 * @package WooFramework
 * @subpackage Template
 */
	get_header();
	global $woo_options;
	
/**
 * The Variables
 *
 * Setup default variables, overriding them if the "Theme Options" have been saved.
 */
	
	$settings = array(
					'thumb_single' => 'false', 
					'single_w' => 200, 
					'single_h' => 200, 
					'thumb_single_align' => 'alignright'
					);
					
	$settings = woo_get_dynamic_values( $settings );
?>
       
    <div id="content" class="col-full">
    	<?php if ( isset( $woo_options['woo_breadcrumbs_show'] ) && $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<section id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</section><!--/#breadcrumbs -->
		<?php } ?>
    	
    	<div class="col-full single-portfolio">
			<section id="main" class="fullwidth post">			           
			
	        <?php
	        	if ( have_posts() ) { $count = 0;
	        		while ( have_posts() ) { the_post(); $count++;
	        ?>
				
	            
	            <div class="single-portfolio-gallery fix">
				
				<?php
					$width = 864;
					$args = 'width=' . $width;
					$embed = woo_embed( $args );
					
					if ( $embed != '' ) {
						echo $embed;
					} else {
						
						$args .= '&return=true&link=img&noheight=true';
	
						$html = '';
						$rel = 'lightbox';
						
						// Get the other images.
						$images = woo_get_post_images( 0, 'full' );

						if ( count ( $images ) > 0 ) 
							$rel = 'lightbox[' . $post->ID . ']'; 					
						
						// Store featured image ID for exclusion
						if ( isset( $woo_options['woo_post_image_support'] ) && ( $woo_options['woo_post_image_support'] == 'true' ) && current_theme_supports( 'post-thumbnails' ) && function_exists('get_post_thumbnail_id') ) {
							$featured_image_id = get_post_thumbnail_id( $post->ID );
						} else {
							$featured_image_id = '';
						}
						
						
						if ( $featured_image_id != '' ) {
							$html .= '<div class="portfolio-item single-portfolio-image ">';
							$image_data = wp_get_attachment_image_src( $featured_image_id, 'full' );
							$image_url = $image_data[0];
							
							$html .= '<a href="' . $image_url . '" rel="' . $rel . '">' . woo_image( $args ) . '</a>' . "\n";
							$html .= '</div>';
						}
						
						
						
						
						
						if ( count ( $images ) > 0 ) {	
							$html .= '<div class="gallery-wrap">';
							foreach ( $images as $k => $v ) {
								$pos = false;
								
								// Skip if the image is used as the posts featured image
								if ( $featured_image_id == $v['id'] ) continue;
								
								if ( isset($image_url) && $image_url != '' && $v['url'] != '' ) {
									$pos = strpos( $v['url'], $image_url );
								}
								
								if ( $pos === false || ( $v['url'] != $image_url ) ) {
									$html .= '<div class="portfolio-item single-portfolio-image gallery">';
									
									// Use vt_resize to dynamically resize attached images
									$image = vt_resize( $v['id'], null, $width, null, true );						
									$html .= '<a href="' . $v['url'] . '" rel="' . $rel . ']"><img src="' . $image['url'] . '" width="' . $image['width'] . '" /></a>' . "\n";
									
									$html .= '</div>';
								}
								
							}
							$html .= '</div>';
						}
						echo $html;
					}
				?>
				
				</div><!-- /.single-portfolio-gallery -->
				
				<article <?php post_class(); ?>>
	
	
	                <header class="portfolio-header">
	                
		                <h1 class="title"><?php the_title(); ?></h1>
		                <?php woo_portfolio_meta( '' ); ?>
		                                	
	                </header>
	                
	                <section class="entry">
	                	<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</div>' ) ); ?>
					</section>
	
	            </article><!-- .post -->
	            
	            <nav id="post-entries" class="fix">
		            <div class="nav-prev fl"><?php previous_post_link('%link'); ?></div>
		            <div class="nav-next fr"><?php next_post_link('%link'); ?></div>
		        </nav>
				            
	            <div class="fix"></div>
	            <?php
	            	// Determine wether or not to display comments here, based on "Theme Options".
	            	if ( isset( $woo_options['woo_comments'] ) && in_array( $woo_options['woo_comments'], array( 'post', 'both' ) ) ) {
	            		comments_template();
	            	}
	
					} // End WHILE Loop
				} else {
			?>
				<article <?php post_class(); ?>>
	            	<p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
				</article><!-- .post -->             
	       	<?php } ?>  
	        
			</section><!-- #main -->
		</div>
    </div><!-- #content -->
		
<?php get_footer(); ?>