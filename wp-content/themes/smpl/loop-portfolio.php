<?php
/**
 * Loop - Portfolio
 *
 * This is a custom loop file, containing the looping logic for use in the "portfolio" page template, 
 * as well as the "portfolio-gallery" taxonomy archive screens. The custom query is only run on the page
 * template, as we already have the data we need when on the taxonomy archive screens.
 *
 * @package WooFramework
 * @subpackage Template
 */

global $woo_options;
global $more; $more = 0;

/* Setup parameters for this loop. */
/* Make sure we only run our custom query when using the page template, and not in a taxonomy. */

$thumb_width = 480;
$thumb_height = 480;

/* The Variables */
$settings = array(
					'portfolio_enable_pagination' => 'false', 
					'portfolio_posts_per_page' => get_option( 'posts_per_page' )
				);
				
$settings = woo_get_dynamic_values( $settings );

/* Setup array of booleans to make filtering of this setting easier. */
$settings_toggle = array( 'true' => true, 'false' => false );

/* Add toggle for the pagination vs portfolio filter bar. */
$toggle_pagination = apply_filters( 'woo_portfolio_toggle_pagination', $settings_toggle[$settings['portfolio_enable_pagination']] ); // Default: false
$filtering_css_class = 'has-filtering';
if ( $toggle_pagination == true || is_tax( 'portfolio-gallery' ) ) {
	$filtering_css_class = 'no-filtering';
}

/* Make sure our thumbnail dimensions come through from the theme options. */
if ( isset( $woo_options['woo_portfolio_thumb_width'] ) && ( $woo_options['woo_portfolio_thumb_width'] != '' ) ) {
	$thumb_width = $woo_options['woo_portfolio_thumb_width'];
}

if ( isset( $woo_options['woo_portfolio_thumb_height'] ) && ( $woo_options['woo_portfolio_thumb_height'] != '' ) ) {
	$thumb_height = $woo_options['woo_portfolio_thumb_height'];
}

/* Setup portfolio galleries navigation. */
$galleries = array();
$galleries = get_terms( 'portfolio-gallery' );
$to_exclude = array();

/* Optionally exclude navigation items. */
if ( isset( $woo_options['woo_portfolio_excludenav'] ) && ( $woo_options['woo_portfolio_excludenav'] != '' ) ) {
	$to_exclude = explode( ',', $woo_options['woo_portfolio_excludenav'] );
}

if ( is_array( $to_exclude ) ) {
	foreach ( $to_exclude as $k => $v ) {
		$to_exclude[$k] = str_replace( ' ', '', $v );
	}
	
	/* Remove the galleries to be excluded. */
	foreach ( $galleries as $k => $v ) {
		if ( in_array( $v->slug, $to_exclude ) ) {
			unset( $galleries[$k] );
		}
	}
}

/* Save the current state before we perform a new query. */
$screen_type = 'page';
$archive_title = '';
$current = 'all';
if ( is_post_type_archive() ) { $screen_type = 'post-type-archive'; }
if ( is_tax() ) {
	$screen_type = 'taxonomy';
	$archive_title = '';
	
	$obj = get_queried_object();
	if ( isset( $obj->name ) ) {
		$archive_title = $obj->name;
		$current = $obj->term_id;
	}
}

if ( ! is_tax() ) {

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; 
$query_args = array(
				'post_type' => 'portfolio', 
				'paged' => $paged, 
				'posts_per_page' => -1
			);

if ( $toggle_pagination == true ) {
	$query_args['posts_per_page'] = $settings['portfolio_posts_per_page'];
}

/* If we have galleries, make sure we only get items from those galleries. */
if ( count( $galleries ) > 0 ) {

$gallery_slugs = array();
foreach ( $galleries as $g ) { $gallery_slugs[] = $g->slug; }

$query_args['tax_query'] = array(
								array(
									'taxonomy' => 'portfolio-gallery',
									'field' => 'slug',
									'terms' => $gallery_slugs
								)
							);
}

wp_reset_query();

/* The Query. */			   
query_posts( $query_args );

} // End IF Statement ( is_tax() )

/* The Loop. */	
if ( have_posts() ) : $count = 0; ?>
<header class="<?php echo $filtering_css_class; ?>">
				    	
				    	
<?php
	if ( $screen_type == 'page' ) { ?><h1><?php the_title(); ?></h1><?php }
	else if ( $screen_type == 'post-type-archive' ) { echo '<h1>' . apply_filters( 'woo_portfolio_type_archive_title', __( 'Portfolio', 'woothemes' ) ) . '</h1>'; }
	else if ( $screen_type == 'taxonomy' ) { echo '<h1>' . $archive_title . '</h1>'; }

	/* Display the gallery navigation (from theme-functions.php). */
	woo_portfolio_navigation( $galleries, array( 'current' => $current ), $toggle_pagination );
?>
				    		
				    	
				    	
					</header>

	<div id="portfolio" class="portfolio-items fix">
<?php
	while ( have_posts() ) : the_post(); $count++;
	
		/* Get the settings for this portfolio item. */
		$settings = woo_portfolio_item_settings( $post->ID );
		
		/* If the theme option is set to link to the single portfolio item, adjust the $settings. */
		if ( isset( $woo_options['woo_portfolio_linkto'] ) && ( $woo_options['woo_portfolio_linkto'] == 'post' ) ) {
			$settings['large'] = get_permalink( $post->ID );
			$settings['rel'] = '';
		}

		// Check for custom URL on item
		$custom_url = get_post_meta( $post->ID, '_portfolio_url', true ); 
		if ( $custom_url != '' )
			$settings['large'] = $custom_url;

?>
		<article id="portfolio-item-id-<?php the_ID(); ?>" <?php post_class( 'portfolio-item' ); ?>>
		
			<?php
			/* Setup image for display and for checks, to avoid doing multiple queries. */
				$image = woo_image( 'width=480&link=img&return=true' ); 
				$image_src = woo_image( 'width=480&link=url&return=true' );
				if ( !$image )
					$image = '<img src="' . get_template_directory_uri() . '/images/temp-portfolio.png" alt="" />';
			?>
			<a <?php echo $settings['rel']; ?> title="<?php echo $settings['caption']; ?>" href="<?php echo $settings['large']; ?>" class="item drop-shadow curved curved-hz-1">
				<?php echo $image; ?>
			</a>
			
			<h3><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
			<?php 
			//setup terms
			$tag_list = get_the_term_list( $post->ID, 'portfolio-gallery', '' , ', ' , ''  );
			$tag_list = strip_tags($tag_list);
			?>
			<p class="category"><?php echo $tag_list; ?></p>

		<?php
			// Output image gallery for lightbox
        	if ( ! empty( $settings['gallery'] ) ) {
            	foreach ( array_slice( $settings['gallery'], 1 ) as $img => $attachment ) {
            		if ( $attachment['url'] != $image_src ) {
            			echo '<a ' . $settings['rel'] . ' title="' . $attachment['caption'] . '" href="' . $attachment['url'] . '" class="gallery-image"></a>' . "\n";	
            		}                    
            	}
            }
		?>
		</article>
		
<?php
	endwhile;
?>
	</div><!--/.portfolio-items-->
<?php
	if ( $toggle_pagination == true ) {
		woo_pagenav();
	}
?>
<?php else : ?>
	<div <?php post_class(); ?>>
		<p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
	</div><!-- .post -->
<?php
endif;
rewind_posts();
wp_reset_query();
?>