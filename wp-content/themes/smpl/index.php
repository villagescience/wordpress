<?php
/**
 * Index Template
 *
 * Here we setup all logic and XHTML that is required for the index template, used as both the homepage
 * and as a fallback template, if a more appropriate template file doesn't exist for a specific context.
 *
 * @package WooFramework
 * @subpackage Template
 */
	get_header();
	global $woo_options;
	global $woocommerce_loop;
	
?>

	<!-- The stand first -->
	<?php if( isset( $woo_options['woo_stand_first'] ) ) {
		echo '<h1 class="stand-first">';
        echo stripslashes( $woo_options['woo_stand_first'] );
        echo '</h1>';
	} ?>

    <div id="content" class="col-full">
    	
    	<!-- The slider -->
    	<?php
			if ( isset( $woo_options['woo_slider'] ) && $woo_options['woo_slider'] == 'true' ) { ?>		
				<?php $slides = get_posts('suppress_filters=0&post_type=slide&showposts='.$woo_options[ 'woo_slider_entries' ]); ?>
				<?php if (!empty($slides)) { ?>
				    
					<div id="slides">
					    <ul class="slides fix">
					        
					            <?php foreach($slides as $post) : setup_postdata($post); //$count++; ?>    
					            
					            <li id="slide-<?php echo $count; ?>" class="slide slide-id-<?php the_ID(); ?>">
					        		<?php
					    	    		$slide_url = get_post_meta($post->ID, 'url', true );
					    	    		if ( isset($slide_url) && $slide_url != '' ) { ?>
					    	    		<a href="<?php echo $slide_url; ?>" title="<?php the_title_attribute(); ?>">
					    	    		<?php } // End If Statement ?>
					        		<div class="slide-img">
					    	    		<?php
					    	    		$has_embed = woo_embed( 'width=864&key=embed&class=slide-video&id='.$post->ID );
					        			if ( $has_embed ) {
					        				echo $has_embed; // Minus 6px off the width to cater for the 3px border.
					        			} else {
					        				woo_image( 'key=image&width=864&h=&class=slide-image&link=img' );
					        			}
					        			
					        		?>
					    	    	</div>
					    	    	<?php if ( isset($slide_url) && $slide_url != '' ) { ?>
					    	    		</a>
					    	    		<?php } // End If Statement ?>
					    	    	
					    	    	<div class="slide-content">
					    	    	
					    	    		<h2 class="title">
					    	    		<?php if ( isset($slide_url) && $slide_url != '' ) { ?><a href="<?php echo $slide_url; ?>" title="<?php the_title_attribute(); ?>"><?php } // End If Statement ?><?php the_title(); ?><?php if ( isset($slide_url) && $slide_url != '' ) { ?></a><?php } // End If Statement ?>
					    	    		</h2>
					       		     		
					       		     	<div class="entry">
					           		     	<?php the_excerpt(); ?>
										</div>
					    	    	
					    	    	</div>
					            	
					            </li><!--/.slide-->
					            
							<?php endforeach; ?> 
							
					    </ul><!-- /.slides -->
					    
					</div><!-- /#slides -->
				
				<?php } else {
					$panel_error_message = __('Please add some slides in order to display the slider correctly.','woothemes');
				    get_template_part( 'includes/panel-error' );
				} ?>
								
				<?php 
				// Slider Settings
				if ( isset($woo_options['woo_slider_hover']) ) { $pauseOnHover = $woo_options['woo_slider_hover']; } else { $pauseOnHover = 'false'; }
				if ( isset($woo_options['woo_slider_touchswipe']) ) { $touchSwipe = $woo_options['woo_slider_touchswipe']; } else { $touchSwipe = 'true'; }
				if ( isset($woo_options['woo_slider_speed']) ) { $slideshowSpeed = $woo_options['woo_slider_speed']; } else { $slideshowSpeed = '7000'; } // milliseconds
				if ( isset($woo_options['woo_fade_speed']) ) { $animationDuration = $woo_options['woo_fade_speed']; } else { $animationDuration = '600'; } // milliseconds
				?>	  
				<script type="text/javascript">
				   jQuery(window).load(function() {
				   	jQuery('#slides').flexslider({
				   		directionNav: false,
				   		touchSwipe: <?php echo $touchSwipe; ?>,
				   		pauseOnHover: <?php echo $pauseOnHover; ?>,
				   		slideshowSpeed: <?php echo $slideshowSpeed; ?>, 
				   		animationDuration: <?php echo $animationDuration; ?>
				   	});
				   	jQuery('#slides').addClass('loaded');
				   });
				</script>
			<?php }
		?>
    	<!-- /The slider -->
    	
    	<!-- Recent Products -->
    	
    	<?php if ( $woo_options[ 'woo_homepage_product_tabs' ] == "true" ) { ?>
    	
    	<div class="woocommerce_tabs home_tabs">
		
			<ul class="tabs">
				<?php if ( $woo_options[ 'woo_homepage_best_sellers' ] == "true" ) { ?>
					<li><a href="#tab-best-sellers"><?php _e('Best Sellers' , 'woothemes'); ?></a></li>
				<?php } ?>
				<?php if ( $woo_options[ 'woo_homepage_staff_picks' ] == "true" ) { ?>
					<li><a href="#tab-staff-picks"><?php _e('Staff Picks' , 'woothemes'); ?></a></li>
				<?php } ?>
				<?php if ( $woo_options[ 'woo_homepage_new_in' ] == "true" ) { ?>
					<li><a href="#tab-new-in"><?php _e('New In' , 'woothemes'); ?></a></li>
				<?php } ?>
			</ul>
			
			<?php if ( $woo_options[ 'woo_homepage_best_sellers' ] == "true" ) { ?>
				<div class="panel" id="tab-best-sellers">
								
					<ul class="bestselling-products products">
					
					<?php
					$args = array( 'post_type' => 'product', 'posts_per_page' => 6, 'meta_key' => 'total_sales', 'orderby' => 'meta_value' );
					$i = 0;					
					


					$loop = new WP_Query( $args );
					
					while ( $loop->have_posts() ) : $loop->the_post(); $_product; $i++; 

					if ( function_exists( 'get_product' ) ) {
						$_product = get_product( $loop->post->ID );
					} else { 
						$_product = new WC_Product( $loop->post->ID );
					}

					?>
							
							<li class="product <?php if ($i%3==0) echo ' last'; if (($i-1)%3==0) echo ' first'; ?>">
								<div class="inner">
								<?php woocommerce_show_product_sale_flash( $post, $_product ); ?>
								<a href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">
									<?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.woocommerce_placeholder_img_src().'" alt="Placeholder" />'; ?>
									<h3><?php the_title(); ?></h3>
									<span class="price"><?php echo $_product->get_price_html(); ?></span>
								</a>
								<?php woocommerce_template_loop_add_to_cart( $loop->post, $_product ); ?>
								<?php smpl_product_more_details(); ?>
								</div>
							</li>
						
					<?php endwhile; ?>
										
					</ul>
				
				</div>
			<?php } ?>
			
			<?php if ( $woo_options[ 'woo_homepage_staff_picks' ] == "true" ) { ?>
				<div class="panel" id="tab-staff-picks">
				
					<ul class="featured-products products">
					
					<?php
					$args = array( 'post_type' => 'product', 'posts_per_page' => 6, 'meta_query' => array( array('key' => '_visibility','value' => array('catalog', 'visible'),'compare' => 'IN'),array('key' => '_featured','value' => 'yes')) );
					$i = 0;					
					$loop = new WP_Query( $args );
					while ( $loop->have_posts() ) : $loop->the_post(); $_product; $i++; 

					if ( function_exists( 'get_product' ) ) {
						$_product = get_product( $loop->post->ID );
					} else { 
						$_product = new WC_Product( $loop->post->ID );
					}

					?>
							
							<li class="product <?php if ($i%3==0) echo ' last'; if (($i-1)%3==0) echo ' first'; ?>">
								<div class="inner">
								<?php woocommerce_show_product_sale_flash( $post, $_product ); ?>
								<a href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">
									<?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.woocommerce_placeholder_img_src().'" alt="Placeholder" />'; ?>
									<h3><?php the_title(); ?></h3>
									<span class="price"><?php echo $_product->get_price_html(); ?></span>
								</a>
								<?php woocommerce_template_loop_add_to_cart( $loop->post, $_product ); ?>
								<?php smpl_product_more_details(); ?>
								</div>
							</li>
						
					<?php endwhile; ?>
										
					</ul>
				
				</div>
			<?php } ?>
			
			<?php if ( $woo_options[ 'woo_homepage_new_in' ] == "true" ) { ?>
			<div class="panel" id="tab-new-in">
			
				<ul class="recent-products products">
					
					<?php
					$args = array( 'post_type' => 'product', 'posts_per_page' => 6, 'meta_query' => array( array('key' => '_visibility','value' => array('catalog', 'visible'),'compare' => 'IN'),array('key' => '_featured','value' => 'no')) );
					$i = 0;					
					$loop = new WP_Query( $args );
					while ( $loop->have_posts() ) : $loop->the_post(); $_product; $i++; 

					if ( function_exists( 'get_product' ) ) {
						$_product = get_product( $loop->post->ID );
					} else { 
						$_product = new WC_Product( $loop->post->ID );
					}

					?>
							
							<li class="product <?php if ($i%3==0) echo ' last'; if (($i-1)%3==0) echo ' first'; ?>">
								<div class="inner">
								<?php woocommerce_show_product_sale_flash( $post, $_product ); ?>
								<a href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">
									<?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.woocommerce_placeholder_img_src().'" alt="Placeholder" />'; ?>
									<h3><?php the_title(); ?></h3>
									<span class="price"><?php echo $_product->get_price_html(); ?></span>
								</a>
								<?php woocommerce_template_loop_add_to_cart( $loop->post, $_product ); ?>
								<?php smpl_product_more_details(); ?>
								</div>
							</li>
						
					<?php endwhile; ?>
										
					</ul>
			
			</div>
			<?php } ?>
			
		</div><!--/.woocommerce_tabs -->
		
		<?php } ?>
    	
    	<!-- /Recent Products -->
    
    	<!-- The latest tweet -->
		<?php if ( $woo_options[ 'woo_homepage_tweet' ] == "true" ) { ?>
			<?php smpl_tweet(); ?>
		<?php } ?>
		<!-- /The latest tweet -->
		
		<!-- Portfolio items -->
    	<?php if ( $woo_options[ 'woo_homepage_portfolio' ] == "true" ) { ?>
    		<?php get_template_part( 'includes/homepage-portfolio-panel' ); ?>
    	<?php } ?>
    	<!-- /Porfolio items -->
		
		<section id="main" class="col-left">      

		<?php
			
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; query_posts( array( 'post_type' => 'post', 'paged' => $paged, 'posts_per_page' => 3 ) );
			
        	if ( have_posts() ) : $count = 0;
        ?>
        
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

		<?php else : ?>
        
            <article <?php post_class(); ?>>
                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            </article><!-- /.post -->
        
        <?php endif; ?>

		</section><!-- /#main -->

        <?php get_sidebar('home'); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>