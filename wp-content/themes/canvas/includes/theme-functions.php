<?php

/*-------------------------------------------------------------------------------------

TABLE OF CONTENTS

- Page / Post navigation
- WooTabs - Popular Posts
- WooTabs - Latest Posts
- WooTabs - Latest Comments
- Post Meta
- WordPress 3.0 New Features Support
- Custom Post Type: Slides (Business Slider)
- Custom Post Type: Portfolio Item (Portfolio Component)
- Custom Post Type: Feedback (Testimonials Component)
- Subscribe & Connect
- Archive Title
- Get Post image attachments
- Woo Portfolio Navigation
- Woo Portfolio Item Extras (Testimonial and Link)
- Woo Portfolio Item Settings
- Woo Portfolio, show portfolio galleries in portfolio item breadcrumbs
- Woo Portfolio, change the "post more" content for portfolio items.
- Woo Portfolio, get image dimensions based on layout and website width settings.
- Woo Feedback, woo_get_feedback_entries()
- Exclude categories from displaying on the "Blog" page template.
- Exclude categories from displaying on the homepage.
- Add custom CSS class to the <body> tag if the lightbox option is enabled.
- Load PrettyPhoto JavaScript and CSS if the lightbox option is enabled.
- Google maps (for contact template)
- Add custom CSS class to the <body> tag if the boxed layout option is enabled.
- Is IE
- Check if WooCommerce is activated
- Get a menu name

-------------------------------------------------------------------------------------*/



/*-----------------------------------------------------------------------------------*/
/* Page / Post navigation */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('woo_pagenav')) {
	function woo_pagenav() {

		global $woo_options, $wp_query, $paged, $page;

		// If the user has set the option to use simple paging links, display those. By default, display the pagination.
		if ( @$woo_options['woo_pagination_type'] == 'simple' ) {

			if ( get_next_posts_link() || get_previous_posts_link() ) {
		?>
            <div class="nav-entries">
                <?php next_posts_link( '<span class="nav-prev fl"><i class="icon-angle-left"></i> '. __( 'Older posts', 'woothemes' ) . '</span>' ); ?>
                <?php previous_posts_link( '<span class="nav-next fr">'. __( 'Newer posts', 'woothemes' ) . ' <i class="icon-angle-right"></i></span>' ); ?>
                <div class="fix"></div>
            </div>
		<?php
			} // End IF Statement

		} else {

			woo_pagination();

		} // End IF Statement

	} // End woo_pagenav()
} // End IF Statement

if (!function_exists('woo_postnav')) {
	function woo_postnav() {
		if ( is_single() ) {
		?>
	        <div class="post-entries">
	            <div class="nav-prev fl"><?php previous_post_link( '%link', '<i class="icon-angle-left"></i> %title' ) ?></div>
	            <div class="nav-next fr"><?php next_post_link( '%link', '%title <i class="icon-angle-right">' ) ?></i></div>
	            <div class="fix"></div>
	        </div>

		<?php
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* WooTabs - Popular Posts */
/* See /includes/widgets/widget-woo-tabs.php */
/*-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* WooTabs - Latest Posts */
/* See /includes/widgets/widget-woo-tabs.php */
/*-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* WooTabs - Latest Comments */
/* See /includes/widgets/widget-woo-tabs.php */
/*-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* WordPress 3.0 New Features Support */
/*-----------------------------------------------------------------------------------*/

if ( function_exists('wp_nav_menu') ) {
	add_theme_support( 'nav-menus' );
	register_nav_menus( array( 'primary-menu' => __( 'Primary Menu', 'woothemes' ) ) );
	register_nav_menus( array( 'top-menu' => __( 'Top Menu', 'woothemes' ) ) );
}

/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Slides (Business Slider) */
/*-----------------------------------------------------------------------------------*/

if ( !function_exists('woo_add_slides') ) {
	function woo_add_slides() {

		global $woo_options;

		if ( isset( $woo_options['woo_biz_slides_disable'] ) && ( $woo_options['woo_biz_slides_disable'] == 'true' ) ) { return; }

		// "Slides" Custom Post Type
		$labels = array(
			'name' => _x( 'Slides', 'post type general name', 'woothemes' ),
			'singular_name' => _x( 'Slide', 'post type singular name', 'woothemes' ),
			'add_new' => _x( 'Add New', 'slide', 'woothemes' ),
			'add_new_item' => __( 'Add New Slide', 'woothemes' ),
			'edit_item' => __( 'Edit Slide', 'woothemes' ),
			'new_item' => __( 'New Slide', 'woothemes' ),
			'view_item' => __( 'View Slide', 'woothemes' ),
			'search_items' => __( 'Search Slides', 'woothemes' ),
			'not_found' =>  __( 'No slides found', 'woothemes' ),
			'not_found_in_trash' => __( 'No slides found in Trash', 'woothemes' ),
			'parent_item_colon' => ''
		);

		$args = array(
			'labels' => $labels,
			'public' => false,
			'publicly_queryable' => false,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_icon' => get_template_directory_uri() .'/includes/images/slides.png',
			'menu_position' => null,
			'taxonomies' => array( 'slide-page' ),
			'supports' => array( 'title','editor','thumbnail','excerpt' )
		);

		register_post_type( 'slide', $args );

		// "Slide Pages" Custom Taxonomy
		$labels = array(
			'name' => _x( 'Slide Pages', 'taxonomy general name', 'woothemes' ),
			'singular_name' => _x( 'Slide Pages', 'taxonomy singular name', 'woothemes' ),
			'search_items' =>  __( 'Search Slide Pages', 'woothemes' ),
			'all_items' => __( 'All Slide Pages', 'woothemes' ),
			'parent_item' => __( 'Parent Slide Page', 'woothemes' ),
			'parent_item_colon' => __( 'Parent Slide Page:', 'woothemes' ),
			'edit_item' => __( 'Edit Slide Page', 'woothemes' ),
			'update_item' => __( 'Update Slide Page', 'woothemes' ),
			'add_new_item' => __( 'Add New Slide Page', 'woothemes' ),
			'new_item_name' => __( 'New Slide Page Name', 'woothemes' ),
			'menu_name' => __( 'Slide Pages', 'woothemes' )
		);

		$args = array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'slide-page' )
		);

		register_taxonomy( 'slide-page', array( 'slide' ), $args );
	}

	add_action( 'init', 'woo_add_slides' );
}

/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Portfolio Item (Portfolio Component) */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_add_portfolio' ) ) {
	function woo_add_portfolio() {

		global $woo_options;

		// Sanity check.
		if (
			( isset( $woo_options['woo_portfolio_disable'] ) && $woo_options['woo_portfolio_disable'] == 'true' )
		   ) { return; }

		// "Portfolio Item" Custom Post Type
		$labels = array(
			'name' => _x( 'Portfolio', 'post type general name', 'woothemes' ),
			'singular_name' => _x( 'Portfolio Item', 'post type singular name', 'woothemes' ),
			'add_new' => _x( 'Add New', 'slide', 'woothemes' ),
			'add_new_item' => __( 'Add New Portfolio Item', 'woothemes' ),
			'edit_item' => __( 'Edit Portfolio Item', 'woothemes' ),
			'new_item' => __( 'New Portfolio Item', 'woothemes' ),
			'view_item' => __( 'View Portfolio Item', 'woothemes' ),
			'search_items' => __( 'Search Portfolio Items', 'woothemes' ),
			'not_found' =>  __( 'No portfolio items found', 'woothemes' ),
			'not_found_in_trash' => __( 'No portfolio items found in Trash', 'woothemes' ),
			'parent_item_colon' => ''
		);

		$portfolioitems_rewrite = get_option( 'woo_portfolioitems_rewrite' );
 		if( empty( $portfolioitems_rewrite ) ) { $portfolioitems_rewrite = 'portfolio-items'; }

		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => $portfolioitems_rewrite ),
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_icon' => get_template_directory_uri() .'/includes/images/portfolio.png',
			'menu_position' => null,
			'has_archive' => true,
			'taxonomies' => array( 'portfolio-gallery' ),
			'supports' => array( 'title','editor','thumbnail' )
		);

		if ( isset( $woo_options['woo_portfolio_excludesearch'] ) && ( $woo_options['woo_portfolio_excludesearch'] == 'true' ) ) {
			$args['exclude_from_search'] = true;
		}

		register_post_type( 'portfolio', $args );

		// "Portfolio Galleries" Custom Taxonomy
		$labels = array(
			'name' => _x( 'Portfolio Galleries', 'taxonomy general name', 'woothemes' ),
			'singular_name' => _x( 'Portfolio Gallery', 'taxonomy singular name','woothemes' ),
			'search_items' =>  __( 'Search Portfolio Galleries', 'woothemes' ),
			'all_items' => __( 'All Portfolio Galleries', 'woothemes' ),
			'parent_item' => __( 'Parent Portfolio Gallery', 'woothemes' ),
			'parent_item_colon' => __( 'Parent Portfolio Gallery:', 'woothemes' ),
			'edit_item' => __( 'Edit Portfolio Gallery', 'woothemes' ),
			'update_item' => __( 'Update Portfolio Gallery', 'woothemes' ),
			'add_new_item' => __( 'Add New Portfolio Gallery', 'woothemes' ),
			'new_item_name' => __( 'New Portfolio Gallery Name', 'woothemes' ),
			'menu_name' => __( 'Portfolio Galleries', 'woothemes' )
		);

		$args = array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'portfolio-gallery' )
		);

		register_taxonomy( 'portfolio-gallery', array( 'portfolio' ), $args );
	}

	add_action( 'init', 'woo_add_portfolio' );
}

/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Feedback (Feedback Component) */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_add_feedback' ) ) {
	function woo_add_feedback() {
		global $woo_options;

		if ( ( isset( $woo_options['woo_feedback_disable'] ) && $woo_options['woo_feedback_disable'] == 'true' ) ) { return; }

		$labels = array(
			'name' => _x( 'Feedback', 'post type general name', 'woothemes' ),
			'singular_name' => _x( 'Feedback Item', 'post type singular name', 'woothemes' ),
			'add_new' => _x( 'Add New', 'slide', 'woothemes' ),
			'add_new_item' => __( 'Add New Feedback Item', 'woothemes' ),
			'edit_item' => __( 'Edit Feedback Item', 'woothemes' ),
			'new_item' => __( 'New Feedback Item', 'woothemes' ),
			'view_item' => __( 'View Feedback Item', 'woothemes' ),
			'search_items' => __( 'Search Feedback Items', 'woothemes' ),
			'not_found' =>  __( 'No Feedback Items found', 'woothemes' ),
			'not_found_in_trash' => __( 'No Feedback Items found in Trash', 'woothemes' ),
			'parent_item_colon' => ''
		);

		$args = array(
			'labels' => $labels,
			'public' => false,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'_builtin' => false,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_icon' => get_template_directory_uri() .'/includes/images/feedback.png',
			'menu_position' => null,
			'supports' => array( 'title', 'editor'/*, 'author', 'thumbnail', 'excerpt', 'comments'*/ ),
		);

		register_post_type( 'feedback', $args );

	} // End woo_add_feedback()
}

add_action( 'init', 'woo_add_feedback', 10 );

/*-----------------------------------------------------------------------------------*/
/* Subscribe / Connect */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('woo_subscribe_connect')) {
	function woo_subscribe_connect($widget = 'false', $title = '', $form = '', $social = '') {

		//Setup default variables, overriding them if the "Theme Options" have been saved.
		$settings = array(
						'connect' => 'false',
						'connect_title' => __('Subscribe' , 'woothemes'),
						'connect_related' => 'true',
						'connect_content' => __( 'Subscribe to our e-mail newsletter to receive updates.', 'woothemes' ),
						'connect_newsletter_id' => '',
						'connect_mailchimp_list_url' => '',
						'feed_url' => '',
						'connect_rss' => '',
						'connect_twitter' => '',
						'connect_facebook' => '',
						'connect_youtube' => '',
						'connect_flickr' => '',
						'connect_linkedin' => '',
						'connect_delicious' => '',
						'connect_rss' => '',
						'connect_googleplus' => '',
						'connect_dribbble' => '',
						'connect_instagram' => '',
						'connect_vimeo' => '',
						'connect_pinterest' => ''
						);
		$settings = woo_get_dynamic_values( $settings );

		// Setup title
		if ( $widget != 'true' )
			$title = $settings[ 'connect_title' ];

		// Setup related post (not in widget)
		$related_posts = '';
		if ( $settings[ 'connect_related' ] == "true" AND $widget != "true" )
			$related_posts = do_shortcode( '[related_posts limit="5"]' );

?>
	<?php if ( $settings[ 'connect' ] == "true" OR $widget == 'true' ) : ?>
	<aside id="connect">
		<h3><?php if ( $title ) echo stripslashes( $title ); else _e('Subscribe','woothemes'); ?></h3>

		<div <?php if ( $related_posts != '' ) echo 'class="col-left"'; ?>>
			<p><?php if ( $settings['connect_content'] != '') echo stripslashes( $settings['connect_content'] ); else _e('Subscribe to our e-mail newsletter to receive updates.', 'woothemes'); ?></p>

			<?php if ( $settings['connect_newsletter_id'] != "" AND $form != 'on' ) : ?>
			<form class="newsletter-form<?php if ( $related_posts == '' ) echo ' fl'; ?>" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $settings['connect_newsletter_id']; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
				<input class="email" type="text" name="email" value="<?php _e('E-mail','woothemes'); ?>" onfocus="if (this.value == '<?php _e('E-mail','woothemes'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('E-mail','woothemes'); ?>';}" />
				<input type="hidden" value="<?php echo $settings['connect_newsletter_id']; ?>" name="uri"/>
				<input type="hidden" value="<?php echo esc_attr( get_bloginfo('name') ); ?>" name="title"/>
				<input type="hidden" name="loc" value="en_US"/>
				<input class="submit button" type="submit" name="submit" value="<?php _e('Submit', 'woothemes'); ?>" />
			</form>
			<?php endif; ?>

			<?php if ( $settings['connect_mailchimp_list_url'] != "" AND $form != 'on' AND $settings['connect_newsletter_id'] == "" ) : ?>
			<!-- Begin MailChimp Signup Form -->
			<div id="mc_embed_signup">
				<form class="newsletter-form<?php if ( $related_posts == '' ) echo ' fl'; ?>" action="<?php echo $settings['connect_mailchimp_list_url']; ?>" method="post" target="popupwindow" onsubmit="window.open('<?php echo $settings['connect_mailchimp_list_url']; ?>', 'popupwindow', 'scrollbars=yes,width=650,height=520');return true">
					<input type="text" name="EMAIL" class="required email" value="<?php _e('E-mail','woothemes'); ?>"  id="mce-EMAIL" onfocus="if (this.value == '<?php _e('E-mail','woothemes'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('E-mail','woothemes'); ?>';}">
					<input type="submit" value="<?php _e('Submit', 'woothemes'); ?>" name="subscribe" id="mc-embedded-subscribe" class="btn submit button">
				</form>
			</div>
			<!--End mc_embed_signup-->
			<?php endif; ?>

			<?php if ( $social != 'on' ) : ?>
			<div class="social<?php if ( $related_posts == '' AND $settings['connect_newsletter_id' ] != "" ) echo ' fr'; ?>">
		   		<?php if ( $settings['connect_rss' ] == "true" ) { ?>
		   		<a href="<?php if ( $settings['feed_url'] ) { echo esc_url( $settings['feed_url'] ); } else { echo get_bloginfo_rss('rss2_url'); } ?>" class="subscribe" title="RSS"></a>

		   		<?php } if ( $settings['connect_twitter' ] != "" ) { ?>
		   		<a target="_blank" href="<?php echo esc_url( $settings['connect_twitter'] ); ?>" class="twitter" title="Twitter"></a>

		   		<?php } if ( $settings['connect_facebook' ] != "" ) { ?>
		   		<a target="_blank" href="<?php echo esc_url( $settings['connect_facebook'] ); ?>" class="facebook" title="Facebook"></a>

		   		<?php } if ( $settings['connect_youtube' ] != "" ) { ?>
		   		<a target="_blank" href="<?php echo esc_url( $settings['connect_youtube'] ); ?>" class="youtube" title="YouTube"></a>

		   		<?php } if ( $settings['connect_flickr' ] != "" ) { ?>
		   		<a target="_blank" href="<?php echo esc_url( $settings['connect_flickr'] ); ?>" class="flickr" title="Flickr"></a>

		   		<?php } if ( $settings['connect_linkedin' ] != "" ) { ?>
		   		<a target="_blank" href="<?php echo esc_url( $settings['connect_linkedin'] ); ?>" class="linkedin" title="LinkedIn"></a>

		   		<?php } if ( $settings['connect_delicious' ] != "" ) { ?>
		   		<a target="_blank" href="<?php echo esc_url( $settings['connect_delicious'] ); ?>" class="delicious" title="Delicious"></a>

		   		<?php } if ( $settings['connect_googleplus' ] != "" ) { ?>
		   		<a target="_blank" href="<?php echo esc_url( $settings['connect_googleplus'] ); ?>" class="googleplus" title="Google+"></a>

				<?php } if ( $settings['connect_dribbble' ] != "" ) { ?>
		   		<a target="_blank" href="<?php echo esc_url( $settings['connect_dribbble'] ); ?>" class="dribbble" title="Google+"></a>

				<?php } if ( $settings['connect_instagram' ] != "" ) { ?>
		   		<a target="_blank" href="<?php echo esc_url( $settings['connect_instagram'] ); ?>" class="instagram" title="Google+"></a>

				<?php } if ( $settings['connect_vimeo' ] != "" ) { ?>
		   		<a target="_blank" href="<?php echo esc_url( $settings['connect_vimeo'] ); ?>" class="vimeo" title="Google+"></a>

				<?php } if ( $settings['connect_pinterest' ] != "" ) { ?>
		   		<a target="_blank" href="<?php echo esc_url( $settings['connect_pinterest'] ); ?>" class="pinterest" title="Google+"></a>

				<?php } ?>
			</div>
			<?php endif; ?>

		</div><!-- col-left -->

		<?php if ( $settings['connect_related'] == "true" AND $related_posts != '' ) : ?>
		<div class="related-posts col-right">
			<h4><?php _e('Related Posts:', 'woothemes'); ?></h4>
			<?php echo $related_posts; ?>
		</div><!-- col-right -->
		<?php wp_reset_query(); endif; ?>

        <div class="fix"></div>
	</aside>
	<?php endif; ?>
<?php
	}
}
/*-----------------------------------------------------------------------------------*/
/* Archive Title */
/*-----------------------------------------------------------------------------------*/
/**
 * Archive Title
 *
 * The main page title, used on the various post archive templates.
 *
 * @since 4.0
 *
 * @param string $before Optional. Content to prepend to the title.
 * @param string $after Optional. Content to append to the title.
 * @param bool $echo Optional, default to true.Whether to display or return.
 * @return null|string Null on no title. String if $echo parameter is false.
 *
 * @package WooFramework
 * @subpackage Template
 */

 if ( ! function_exists( 'woo_archive_title' ) ) {

 	function woo_archive_title ( $before = '', $after = '', $echo = true ) {

 		global $wp_query;

 		if ( is_category() || is_tag() || is_tax() ) {

 			$taxonomy_obj = $wp_query->get_queried_object();
			$term_id = $taxonomy_obj->term_id;
			$taxonomy_short_name = $taxonomy_obj->taxonomy;

			$taxonomy_raw_obj = get_taxonomy( $taxonomy_short_name );

 		} // End IF Statement

		$title = '';
		$delimiter = ' | ';
		$date_format = get_option( 'date_format' );

		// Category Archive
		if ( is_category() ) {

			$title = '<span class="fl cat">' . __( 'Archive', 'woothemes' ) . $delimiter . single_cat_title( '', false ) . '</span> <span class="fr catrss">';
			$cat_obj = $wp_query->get_queried_object();
			$cat_id = $cat_obj->cat_ID;
			$title .= '<a href="' . get_term_feed_link( $term_id, $taxonomy_short_name, '' ) . '" class="icon-rss icon-large" ></a></span>';

			$has_title = true;
		}

		// Day Archive
		if ( is_day() ) {

			$title = __( 'Archive', 'woothemes' ) . $delimiter . get_the_time( $date_format );
		}

		// Month Archive
		if ( is_month() ) {

			$date_format = apply_filters( 'woo_archive_title_date_format', 'F, Y' );
			$title = __( 'Archive', 'woothemes' ) . $delimiter . get_the_time( $date_format );
		}

		// Year Archive
		if ( is_year() ) {

			$date_format = apply_filters( 'woo_archive_title_date_format', 'Y' );
			$title = __( 'Archive', 'woothemes' ) . $delimiter . get_the_time( $date_format );
		}

		// Author Archive
		if ( is_author() ) {

			$title = __( 'Author Archive', 'woothemes' ) . $delimiter . get_the_author_meta( 'display_name', get_query_var( 'author' ) );
		}

		// Tag Archive
		if ( is_tag() ) {

			$title = __( 'Tag Archives', 'woothemes' ) . $delimiter . single_tag_title( '', false );
		}

		// Post Type Archive
		if ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) {

			/* Get the post type object. */
			$post_type_object = get_post_type_object( get_query_var( 'post_type' ) );

			$title = $post_type_object->labels->name . ' ' . __( 'Archive', 'woothemes' );
		}

		// Post Format Archive
		if ( get_query_var( 'taxonomy' ) == 'post_format' ) {

			$post_format = str_replace( 'post-format-', '', get_query_var( 'post_format' ) );

			$title = get_post_format_string( $post_format ) . ' ' . __( ' Archives', 'woothemes' );
		}

		// General Taxonomy Archive
		if ( is_tax() ) {

			$title = sprintf( __( '%1$s Archives: %2$s', 'woothemes' ), $taxonomy_raw_obj->labels->name, $taxonomy_obj->name );

		}

		if ( strlen($title) == 0 )
		return;

		$title = $before . $title . $after;

		// Allow for external filters to manipulate the title value.
		$title = apply_filters( 'woo_archive_title', $title, $before, $after );

		if ( $echo )
			echo $title;
		else
			return $title;

 	} // End woo_archive_title()

 } // End IF Statement

/*-----------------------------------------------------------------------------------*/
/* Get Post image attachments */
/*-----------------------------------------------------------------------------------*/
/*
Description:

This function will get all the attached post images that have been uploaded via the
WP post image upload and return them in an array.

*/
function woo_get_post_images($offset = 1) {

	// Arguments
	$repeat = 100; 				// Number of maximum attachments to get
	$photo_size = 'large';		// The WP "size" to use for the large image

	global $post;

	$output = array();

	$id = get_the_id();
	$attachments = get_children( array(
	'post_parent' => $id,
	'numberposts' => $repeat,
	'post_type' => 'attachment',
	'post_mime_type' => 'image',
	'order' => 'ASC',
	'orderby' => 'menu_order date' )
	);
	if ( !empty($attachments) ) :
		$output = array();
		$count = 0;
		foreach ( $attachments as $att_id => $attachment ) {
			$count++;
			if ($count <= $offset) continue;
			$url = wp_get_attachment_image_src($att_id, $photo_size, true);
				$output[] = array( 'url' => $url[0], 'caption' => $attachment->post_excerpt, 'id' => $att_id, 'alt' => get_post_meta( $att_id, '_wp_attachment_image_alt', true ) );
		}
	endif;
	return $output;
} // End woo_get_post_images()

/*-----------------------------------------------------------------------------------*/
/* Woo Portfolio Navigation */
/*-----------------------------------------------------------------------------------*/

	if ( ! function_exists( 'woo_portfolio_navigation' ) ) {
		function woo_portfolio_navigation ( $galleries ) {

			// Sanity check.
			if ( ! is_array( $galleries ) || ( count( $galleries ) <= 0 ) ) { return; }

			global $woo_options;

			$settings = array(
							'id' => 'port-tags',
							'label' => __( 'Select a category:', 'woothemes' ),
							'display_all' => true
							 );

			$settings = apply_filters( 'woo_portfolio_navigation_args', $settings );

			// Prepare the anchor tags of the various gallery items.
			$gallery_anchors = '';

			foreach ( $galleries as $g ) {
				$gallery_anchors .= '<a href="#' . $g->slug . '" rel="' . $g->slug . '" class="navigation-slug-' . $g->slug . ' navigation-id-' . $g->term_id . '">' . $g->name . '</a>' . "\n";
			}

			$html = '<div id="' . $settings['id'] . '" class="port-tags">' . "\n";
				$html .= '<div class="fl">' . "\n";
					$html .= '<span class="port-cat">' . "\n";

					// Display label, if one is set.
					if ( $settings['label'] != '' ) { $html .= $settings['label'] . ' '; }

					// Display "All", if set to "true".
					if ( $settings['display_all'] == 'all' ) { $html .= '<a href="#" rel="all" class="current">' . __( 'All', 'woothemes' ) . '</a> '; }

					// Add the gallery anchors in.
					$html .= $gallery_anchors;

					$html .= '</span>' . "\n";
				$html .= '</div><!--/.fl-->' . "\n";
				$html .= '<div class="fix"></div>' . "\n";
			$html .= '</div><!--/#' . $settings['id'] . ' .port-tags-->' . "\n";


			$html = apply_filters( 'woo_portfolio_navigation', $html );

			echo $html;

		} // End woo_portfolio_navigation()
	}

/*-----------------------------------------------------------------------------------*/
/* Woo Portfolio Item Extras (Testimonial and Link) */
/*-----------------------------------------------------------------------------------*/

	if ( ! function_exists( 'woo_portfolio_item_extras' ) ) {
		function woo_portfolio_item_extras ( $data ) {

			$settings = array(
								'id' => 'extras',
								'display_button' => true
							 );

			// Allow child themes/plugins to filter these settings.
			$settings = apply_filters( 'woo_portfolio_item_extras_settings', $settings, $data );

			$html = '';

			$html .= '<div id="' . $settings['id'] . '">' . "\n";

			if ( $data['display_url'] != '' ) { $html .= '<a class="button" href="' . $data['display_url'] . '">' . __( 'Visit Website', 'woothemes' ) . '</a>' . "\n"; }

			if ( $data['testimonial'] != '' ) { $html .= '<blockquote>' . $data['testimonial'] . '</blockquote>' . "\n"; } // End IF Statement

			if ( $data['testimonial_author'] != '' ) {
				$html .= '<cite>&ndash; ' . $data['testimonial_author'] . "\n";
					if ( $data['display_url'] != '' ) { $html .= ' - <a href="' . $data['display_url'] . '" target="_blank">' . $data['display_url'] . '</a>' . "\n"; }
				$html .= '</cite>' . "\n";
			} // End IF Statement

   			$html .= '</div><!--/#extras-->' . "\n";

   			// Allow child themes/plugins to filter this HTML.
   			$html = apply_filters( 'woo_portfolio_item_extras_html', $html, $data );

   			echo $html;

		} // End woo_portfolio_item_extras()
	}

/*-----------------------------------------------------------------------------------*/
/* Woo Portfolio Item Settings */
/* @uses woo_portfolio_image_dimensions() */
/*-----------------------------------------------------------------------------------*/

 	if ( ! function_exists( 'woo_portfolio_item_settings' ) ) {
		function woo_portfolio_item_settings ( $id ) {

			global $woo_options;

			// Sanity check.
			if ( ! is_numeric( $id ) ) { return; }

			$website_layout = 'two-col-left';
			$website_width = '960px';

			if ( isset( $woo_options['woo_layout'] ) ) { $website_layout = $woo_options['woo_layout']; }
			if ( isset( $woo_options['woo_layout_width'] ) ) { $website_width = $woo_options['woo_layout_width']; }

			$dimensions = woo_portfolio_image_dimensions( $website_layout, $website_width );

			$width = $dimensions['width'];
			$height = $dimensions['height'];

			$enable_gallery = false;
			if ( isset( $woo_options['woo_portfolio_gallery'] ) ) { $enable_gallery = $woo_options['woo_portfolio_gallery']; }

			$settings = array(
								'large' => '',
								'caption' => '',
								'rel' => '',
								'gallery' => array(),
								'css_classes' => 'group post portfolio-img',
								'embed' => '',
								'enable_gallery' => $enable_gallery,
								'testimonial' => '',
								'testimonial_author' => '',
								'display_url' => '',
								'width' => $width,
								'height' => $height
							 );

			$meta = get_post_custom( $id );

			// Check if there is a gallery in post.
			// woo_get_post_images is offset by 1 by default. Setting to offset by 0 to show all images.

        	$large = '';
        	if ( isset( $meta['portfolio-image'][0] ) ) {
        		$large = $meta['portfolio-image'][0];
        	}

        	$caption = '';

        	if ( $settings['enable_gallery'] == 'true' ) {

	        	$gallery = woo_get_post_images( '0' );
	        	if ( $gallery ) {
	        		// Get first uploaded image in gallery
	        		$large = $gallery[0]['url'];
	        		$caption = $gallery[0]['caption'];
	            }

            } // End IF Statement

            // If we only have one image, disable the gallery functionality.
            if ( is_array( $gallery ) && ( count( $gallery ) <= 1 ) ) {
           		$settings['enable_gallery'] = 'false';
            }

            // Check for a post thumbnail, if support for it is enabled.
            if ( ( $woo_options['woo_post_image_support'] == 'true' ) && current_theme_supports( 'post-thumbnails' ) ) {
            	$image_id = get_post_thumbnail_id( $id );
            	if ( intval( $image_id ) > 0 ) {
            		$large_data = wp_get_attachment_image_src( $image_id, 'large' );
            		if ( is_array( $large_data ) ) {
            			$large = $large_data[0];
            		}
            	}
            }

            // See if lightbox-url custom field has a value
            if ( isset( $meta['lightbox-url'] ) && ( $meta['lightbox-url'][0] != '' ) ) {
            	$large = $meta['lightbox-url'][0];
            }

	        // Set rel on anchor to show lightbox
	        if ( is_array( $gallery ) && ( count( $gallery ) <= 1 ) ) {
	      		$rel = 'rel="lightbox"';
			} else {
		  		$rel = 'rel="lightbox['. $id .']"';
			}

			// Create CSS classes string.
			$css = '';
			$galleries = array();
			$terms = get_the_terms( $id, 'portfolio-gallery' );
			if ( is_array( $terms ) && ( count( $terms ) > 0 ) ) { foreach ( $terms as $t ) { $galleries[] = $t->slug; } }
			$css = join( ' ', $galleries );

			// If on the single item screen, check for a video.
			if ( is_singular() ) { $settings['embed'] = woo_embed( 'width=540' ); }

			// Add testimonial information.
			if ( isset( $meta['testimonial'] ) && ( $meta['testimonial'][0] != '' ) ) {
				$settings['testimonial'] = $meta['testimonial'][0];
			}

			if ( isset( $meta['testimonial_author'] ) && ( $meta['testimonial_author'][0] != '' ) ) {
				$settings['testimonial_author'] = $meta['testimonial_author'][0];
			}

			// Look for a custom display URL of the portfolio item (used if it's a website, for example)
			if ( isset( $meta['url'] ) && ( $meta['url'][0] != '' ) ) {
				$settings['display_url'] = $meta['url'][0];
			}

			// Assign the values we have to our array.
			$settings['large'] = $large;
			$settings['caption'] = $caption;
			$settings['rel'] = $rel;
			$settings['gallery'] = $gallery;
			$settings['css_classes'] .= ' ' . $css;

			// Disable "enable_gallery" option is gallery is empty.
			if ( ! is_array( $settings['gallery'] ) || ( $settings['gallery'] == '' ) || ( count( $settings['gallery'] ) <= 0 ) ) {
				$settings['enable_gallery'] = 'false';
			}

			// Allow child themes/plugins to filter these settings.
			$settings = apply_filters( 'woo_portfolio_item_settings', $settings, $id );

			return $settings;

		} // End woo_portfolio_item_settings()
	}

/*-----------------------------------------------------------------------------------*/
/* Woo Portfolio, show portfolio galleries in portfolio item breadcrumbs */
/* Modify woo_breadcrumbs() Arguments Specific to this Theme */
/*-----------------------------------------------------------------------------------*/

add_filter( 'woo_breadcrumbs_args', 'woo_portfolio_filter_breadcrumbs_args', 10 );

if ( ! function_exists( 'woo_portfolio_filter_breadcrumbs_args' ) ) {
	function woo_portfolio_filter_breadcrumbs_args( $args ) {

		$args['singular_portfolio_taxonomy'] = 'portfolio-gallery';

		return $args;

	} // End woo_portfolio_filter_breadcrumbs_args()
}

/*-----------------------------------------------------------------------------------*/
/* Woo Portfolio, change the "post more" content for portfolio items. */
/*-----------------------------------------------------------------------------------*/

add_filter( 'woo_post_more', 'woo_portfolio_post_more', 20 );

function woo_portfolio_post_more ( $content ) {

	global $post;

	$new_content = $content;

	if ( get_post_type() != 'portfolio' ) { return $new_content; } // Skip the functionality if it's not a portfolio item.

	$taxonomy = 'portfolio-gallery';

	$terms = get_the_terms( $post->ID, $taxonomy );
	$term_links = array();
	$term_text = '';

	if ( is_array( $terms ) && ( count( $terms ) > 0 ) ) {
		foreach ( $terms as $t ) {
			$term_links[] = '<a href="' . get_term_link( $t->slug, $taxonomy ) . '">' . $t->name . '</a>';
		}

		$term_text = join( ', ', $term_links );
	}

	if ( $term_text != '' ) { $new_content = __( 'Posted In ', 'woothemes' ) . $term_text; }

	return $new_content;

} // End woo_portfolio_post_more()

/*-----------------------------------------------------------------------------------*/
/* Woo Portfolio, change the "post meta" content for portfolio items. */
/*-----------------------------------------------------------------------------------*/

add_filter( 'woo_filter_post_meta', 'woo_portfolio_post_meta', 20 );

function woo_portfolio_post_meta ( $content ) {

	global $post;

	if ( get_post_type() != 'portfolio' ) { return $content; } // Skip the functionality if it's not a portfolio item.

	$taxonomy = 'portfolio-gallery';

	$terms = get_the_terms( $post->ID, $taxonomy );
	$term_links = array();
	$term_text = '';

	if ( is_array( $terms ) && ( count( $terms ) > 0 ) ) {
		foreach ( $terms as $t ) {
			$term_links[] = '<a href="' . get_term_link( $t->slug, $taxonomy ) . '">' . $t->name . '</a>';
		}

		$term_text = join( ', ', $term_links );
	}

	$post_info = '<span class="small">' . __( 'By', 'woothemes' ) . '</span> [post_author_posts_link] <span class="small">' . _x( 'on', 'post datetime', 'woothemes' ) . '</span> [post_date]';

	if ( $term_text != '' ) { $post_info .= ' <span class="small">' . __( 'in', 'woothemes' ) . '</span> ' . $term_text; }

	$content = sprintf( '<div class="post-meta">%s</div>' . "\n", apply_filters( 'woo_filter_portfolio_post_meta', $post_info ) );

	return $content;

} // End woo_portfolio_post_meta()

/*-----------------------------------------------------------------------------------*/
/* Woo Portfolio, get image dimensions based on layout and website width settings. */
/*-----------------------------------------------------------------------------------*/

function woo_portfolio_image_dimensions ( $layout = 'one-col', $width = '960' ) {
	$dimensions = array( 'width' => 520, 'height' => 0, 'thumb_width' => 150, 'thumb_height' => 150 );

	// One Column.
	if ( 'one-col' == $layout ) {
		$dimensions['width'] = intval( $width );
	}

	// Two Column.
	if ( 'two-col' == substr( $layout, 0, 7 ) ) {
		$dimensions['width'] = 800;
	}

	// Three Column.
	if ( 'three-col' == substr( $layout, 0, 9 ) ) {
		$dimensions['width'] = 680;
	}

	// Allow child themes/plugins to filter these dimensions.
	$dimensions = apply_filters( 'woo_portfolio_gallery_dimensions', $dimensions );

	return $dimensions;
} // End woo_post_gallery_dimensions()

/*-----------------------------------------------------------------------------------*/
/* Woo Feedback, woo_get_feedback_entries() */
/*
/* Get feedback entries.
/*
/* @param array/string $args
/* @since 4.5.0
/* @return array/boolean
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_get_feedback_entries' ) ) {
	function woo_get_feedback_entries ( $args = '' ) {
		$defaults = array(
			'limit' => 5,
			'orderby' => 'post_date',
			'order' => 'DESC',
			'id' => 0
		);

		$args = wp_parse_args( $args, $defaults );

		// Allow child themes/plugins to filter here.
		$args = apply_filters( 'woo_get_feedback_args', $args );

		// The Query Arguments.
		$query_args = array();
		$query_args['post_type'] = 'feedback';
		$query_args['numberposts'] = $args['limit'];
		$query_args['orderby'] = $args['orderby'];
		$query_args['order'] = $args['order'];

		if ( is_numeric( $args['id'] ) && ( intval( $args['id'] ) > 0 ) ) {
			$query_args['p'] = intval( $args['id'] );
		}

		// Whitelist checks.
		if ( ! in_array( $query_args['orderby'], array( 'none', 'ID', 'author', 'title', 'date', 'modified', 'parent', 'rand', 'comment_count', 'menu_order', 'meta_value', 'meta_value_num' ) ) ) {
			$query_args['orderby'] = 'date';
		}

		if ( ! in_array( $query_args['order'], array( 'ASC', 'DESC' ) ) ) {
			$query_args['order'] = 'DESC';
		}

		if ( ! in_array( $query_args['post_type'], get_post_types() ) ) {
			$query_args['post_type'] = 'feedback';
		}

		// The Query.
		$query = get_posts( $query_args );

		// The Display.
		if ( ! is_wp_error( $query ) && is_array( $query ) && count( $query ) > 0 ) {} else {
			$query = false;
		}

		return $query;

	} // End woo_get_feedback_entries()
}

/*-----------------------------------------------------------------------------------*/
/* Woo Feedback, woo_display_feedback_entries() */
/*
/* Display posts of the "feedback" post type.
/*
/* @param array/string $args
/* @since 4.5.0
/* @return string $html (if "echo" not set to true)
/* @uses woo_get_feedback_entries()
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_display_feedback_entries' ) ) {
	function woo_display_feedback_entries ( $args = '' ) {
		$defaults = array(
			'limit' => 5,
			'orderby' => 'rand',
			'order' => 'DESC',
			'id' => 0,
			'display_author' => true,
			'display_url' => true,
			'effect' => 'fade', // Options: 'fade', 'none'
			'pagination' => false,
			'echo' => true
		);

		$args = wp_parse_args( $args, $defaults );

		// Allow child themes/plugins to filter here.
		$args = apply_filters( 'woo_display_feedback_args', $args );

		$html = '';

		woo_do_atomic( 'woo_feedback_before', $args );

		// The Query.
		$query = woo_get_feedback_entries( $args );

		// The Display.
		if ( ! is_wp_error( $query ) && is_array( $query ) && count( $query ) > 0 ) {

			if ( $args['effect'] != 'none' ) {
				$effect = ' ' . $args['effect'];
			}

			$html .= '<div class="feedback ' . $effect . '">' . "\n";
			$html .= '<div class="feedback-list">' . "\n";

			foreach ( $query as $post ) {
				setup_postdata( $post );

				$author = '';
				$author_text = '';

				// If we need to display either the author, URL or both, get the data.
				if ( $args['display_author'] == true || $args['display_url'] == true ) {
					$meta = get_post_custom( $post->ID );

					if ( isset( $meta['feedback_author'] ) && ( $meta['feedback_author'][0] != '' ) && $args['display_author'] == true ) {
						$author .= '<cite class="feedback-author">' . $meta['feedback_author'][0] . '</cite><!--/.feedback-author-->' . "\n";
					}

					if ( isset( $meta['feedback_url'] ) && ( $meta['feedback_url'][0] != '' ) && $args['display_url'] == true ) {
						$author .= '<a href="' . esc_url( $meta['feedback_url'][0] ) . '" title="' . esc_attr( $author_text ) . '" class="feedback-url">' . esc_url( $meta['feedback_url'][0] ) . '</a>';
					}
				}

				$html .= '<div id="quote-' . $post->ID . '" class="quote">' . "\n";
					$html .= '<blockquote class="feedback-text">' . get_the_content() . '</blockquote>' . "\n";
					$html .= $author;
				$html .= '</div>' . "\n";
			}

			$html .= '</div><!--/.feedback-list-->' . "\n";

			$html .= '<div class="fix"></div>' . "\n";

			$html .= '</div><!--/.feedback-->' . "\n";
		}

		// Allow child themes/plugins to filter here.
		$html = apply_filters( 'woo_feedback_html', $html, $query );

		if ( $args['echo'] != true ) { return $html; }

		// Should only run is "echo" is set to true.
		echo $html;

		woo_do_atomic( 'woo_feedback_after', $args ); // Only if "echo" is set to true.

		wp_reset_query();

	} // End woo_display_feedback_entries()
}

/*-----------------------------------------------------------------------------------*/
/* Exclude categories from displaying on the "Blog" page template.
/*-----------------------------------------------------------------------------------*/

// Exclude categories on the "Blog" page template.
add_filter( 'woo_blog_template_query_args', 'woo_exclude_categories_blogtemplate', 10 );

function woo_exclude_categories_blogtemplate ( $args ) {

	if ( ! function_exists( 'woo_prepare_category_ids_from_option' ) ) { return $args; }

	$excluded_cats = array();

	// Process the category data and convert all categories to IDs.
	$excluded_cats = woo_prepare_category_ids_from_option( 'woo_exclude_cats_blog' );

	// Homepage logic.
	if ( count( $excluded_cats ) > 0 ) {

		// Setup the categories as a string, because "category__not_in" doesn't seem to work
		// when using query_posts().

		foreach ( $excluded_cats as $k => $v ) { $excluded_cats[$k] = '-' . $v; }
		$cats = join( ',', $excluded_cats );

		$args['cat'] = $cats;
	}

	return $args;

} // End woo_exclude_categories_blogtemplate()

/*-----------------------------------------------------------------------------------*/
/* Exclude categories from displaying on the homepage.
/*-----------------------------------------------------------------------------------*/

// Exclude categories on the homepage.
add_filter( 'pre_get_posts', 'woo_exclude_categories_homepage', 10 );

function woo_exclude_categories_homepage ( $query ) {
	if ( ! function_exists( 'woo_prepare_category_ids_from_option' ) ) { return $query; }

	$excluded_cats = array();

	// Process the category data and convert all categories to IDs.
	$excluded_cats = woo_prepare_category_ids_from_option( 'woo_exclude_cats_home' );

	// Homepage logic.
	if ( $query->is_home() && ( count( $excluded_cats ) > 0 ) ) {
		$query->set( 'category__not_in', $excluded_cats );
	}

	$query->parse_query();

	return $query;
} // End woo_exclude_categories_homepage()

/*-----------------------------------------------------------------------------------*/
/* Add custom CSS class to the <body> tag if the lightbox option is enabled. */
/*-----------------------------------------------------------------------------------*/

add_filter( 'body_class', 'woo_add_lightbox_body_class', 10 );

function woo_add_lightbox_body_class ( $classes ) {
	global $woo_options;

	if ( isset( $woo_options['woo_enable_lightbox'] ) && $woo_options['woo_enable_lightbox'] == 'true' ) {
		$classes[] = 'has-lightbox';
	}

	return $classes;
} // End woo_add_lightbox_body_class()

/*-----------------------------------------------------------------------------------*/
/* Load PrettyPhoto JavaScript and CSS if the lightbox option is enabled. */
/*-----------------------------------------------------------------------------------*/

add_action( 'woothemes_add_javascript', 'woo_load_prettyphoto', 10 );
add_action( 'woothemes_add_css', 'woo_load_prettyphoto', 10 );

function woo_load_prettyphoto () {
	global $woo_options;

	if ( ! isset( $woo_options['woo_enable_lightbox'] ) || $woo_options['woo_enable_lightbox'] == 'false' ) { return; }

	$filter = current_filter();

	switch ( $filter ) {
		case 'woothemes_add_javascript':
			wp_enqueue_script( 'prettyPhoto' );
		break;

		case 'woothemes_add_css':
			wp_enqueue_style( 'prettyPhoto' );
		break;
	}
} // End woo_load_prettyphoto()

/*-----------------------------------------------------------------------------------*/
/* Google Maps */
/*-----------------------------------------------------------------------------------*/

function woo_maps_contact_output($args){

	$key = get_option('woo_maps_apikey');

	// No More API Key needed

	if ( !is_array($args) )
		parse_str( $args, $args );

	extract($args);
	$mode = '';
	$streetview = 'off';
	$map_height = get_option('woo_maps_single_height');
	$featured_w = get_option('woo_home_featured_w');
	$featured_h = get_option('woo_home_featured_h');
	$zoom = get_option('woo_maps_default_mapzoom');
	$type = get_option('woo_maps_default_maptype');
	$marker_title = get_option('woo_contact_title');
	if ( $zoom == '' ) { $zoom = 6; }
	$lang = get_option('woo_maps_directions_locale');
	$locale = '';
	if(!empty($lang)){
		$locale = ',locale :"'.$lang.'"';
	}
	$extra_params = ',{travelMode:G_TRAVEL_MODE_WALKING,avoidHighways:true '.$locale.'}';

	if(empty($map_height)) { $map_height = 250;}

	if(is_home() && !empty($featured_h) && !empty($featured_w)){
	?>
    <div id="single_map_canvas" style="width:<?php echo intval( $featured_w ); ?>px; height: <?php echo intval( $featured_h ); ?>px"></div>
    <?php } else { ?>
    <div id="single_map_canvas" style="width:100%; height: <?php echo $map_height; ?>px"></div>
    <?php } ?>
    <script src="<?php echo esc_attr( esc_url( get_template_directory_uri() . '/includes/js/markers.js' ) ); ?>" type="text/javascript"></script>
    <script type="text/javascript">
		jQuery(document).ready(function(){
			function initialize() {


			<?php if($streetview == 'on'){ ?>


			<?php } else { ?>

			  	<?php switch ($type) {
			  			case 'G_NORMAL_MAP':
			  				$type = 'ROADMAP';
			  				break;
			  			case 'G_SATELLITE_MAP':
			  				$type = 'SATELLITE';
			  				break;
			  			case 'G_HYBRID_MAP':
			  				$type = 'HYBRID';
			  				break;
			  			case 'G_PHYSICAL_MAP':
			  				$type = 'TERRAIN';
			  				break;
			  			default:
			  				$type = 'ROADMAP';
			  				break;
			  	} ?>

			  	var myLatlng = new google.maps.LatLng(<?php echo $geocoords; ?>);
				var myOptions = {
				  zoom: <?php echo $zoom; ?>,
				  center: myLatlng,
				<?php if(get_option('woo_maps_scroll') == 'true'){ ?>
				  scrollwheel: false,
			  	<?php } ?>
				  mapTypeId: google.maps.MapTypeId.<?php echo $type; ?>
				};
			  	var map = new google.maps.Map(document.getElementById( 'single_map_canvas' ),  myOptions);

				<?php if($mode == 'directions'){ ?>
			  	directionsPanel = document.getElementById("featured-route");
 				directions = new GDirections(map, directionsPanel);
  				directions.load("from: <?php echo esc_js( $from ); ?> to: <?php echo esc_js( $to ); ?>" <?php if($walking == 'on'){ echo $extra_params;} ?>);
			  	<?php
			 	} else { ?>

			  		var point = new google.maps.LatLng(<?php echo $geocoords; ?>);
	  				var root = "<?php echo esc_js( esc_url( get_template_directory_uri() ) ); ?>";
	  				var callout = '<?php echo preg_replace("/[\n\r]/","<br/>",get_option('woo_maps_callout_text')); ?>';
	  				var the_link = '<?php echo get_permalink(get_the_id()); ?>';
	  				<?php $title = str_replace(array('&#8220;','&#8221;'),'"', $marker_title); ?>
	  				<?php $title = str_replace('&#8211;','-',$title); ?>
	  				<?php $title = str_replace('&#8217;',"`",$title); ?>
	  				<?php $title = str_replace('&#038;','&',$title); ?>
	  				var the_title = '<?php echo html_entity_decode($title) ?>';

	  			<?php
			 	if(is_page()){
			 		$custom = get_option('woo_cat_custom_marker_pages');
					if(!empty($custom)){
						$color = $custom;
					}
					else {
						$color = get_option('woo_cat_colors_pages');
						if (empty($color)) {
							$color = 'red';
						}
					}
			 	?>
			 		var color = '<?php echo $color; ?>';
			 		createMarker(map,point,root,the_link,the_title,color,callout);
			 	<?php } else { ?>
			 		var color = '<?php echo get_option('woo_cat_colors_pages'); ?>';
	  				createMarker(map,point,root,the_link,the_title,color,callout);
				<?php
				}
					if(isset($_POST['woo_maps_directions_search'])){ ?>

					directionsPanel = document.getElementById("featured-route");
 					directions = new GDirections(map, directionsPanel);
  					directions.load("from: <?php echo htmlspecialchars($_POST['woo_maps_directions_search']); ?> to: <?php echo $address; ?>" <?php if($walking == 'on'){ echo $extra_params;} ?>);



					directionsDisplay = new google.maps.DirectionsRenderer();
					directionsDisplay.setMap(map);
    				directionsDisplay.setPanel(document.getElementById("featured-route"));

					<?php if($walking == 'on'){ ?>
					var travelmodesetting = google.maps.DirectionsTravelMode.WALKING;
					<?php } else { ?>
					var travelmodesetting = google.maps.DirectionsTravelMode.DRIVING;
					<?php } ?>
					var start = '<?php echo htmlspecialchars($_POST['woo_maps_directions_search']); ?>';
					var end = '<?php echo $address; ?>';
					var request = {
       					origin:start,
        				destination:end,
        				travelMode: travelmodesetting
    				};
    				directionsService.route(request, function(response, status) {
      					if (status == google.maps.DirectionsStatus.OK) {
        					directionsDisplay.setDirections(response);
      					}
      				});

  					<?php } ?>
				<?php } ?>
			<?php } ?>


			  }
			  function handleNoFlash(errorCode) {
				  if (errorCode == FLASH_UNAVAILABLE) {
					alert("Error: Flash doesn't appear to be supported by your browser");
					return;
				  }
				 }



		initialize();

		});
	jQuery(window).load(function(){

		var newHeight = jQuery('#featured-content').height();
		newHeight = newHeight - 5;
		if(newHeight > 300){
			jQuery('#single_map_canvas').height(newHeight);
		}

	});

	</script>

<?php
}

/*-----------------------------------------------------------------------------------*/
/* Add custom CSS class to the <body> tag if the boxed layout option is enabled. */
/*-----------------------------------------------------------------------------------*/

add_filter( 'body_class', 'woo_add_boxedlayout_body_class', 10 );

function woo_add_boxedlayout_body_class ( $classes ) {
	global $woo_options;

	if ( isset( $woo_options['woo_style_disable'] ) && $woo_options['woo_style_disable'] != 'true' && isset( $woo_options['woo_layout_boxed'] ) && $woo_options['woo_layout_boxed'] == 'true' ) {
		$classes[] = 'boxed-layout';
	}

	return $classes;
} // End woo_add_boxedlayout_body_class()


/*-----------------------------------------------------------------------------------*/
/* Is IE */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'is_ie' ) ) {
	function is_ie ( $version = '6.0' ) {
		$supported_versions = array( '6.0', '7.0', '8.0', '9.0' );
		$agent = substr( $_SERVER['HTTP_USER_AGENT'], 25, 4 );
		$current_version = substr( $_SERVER['HTTP_USER_AGENT'], 30, 3 );
		$response = false;
		if ( in_array( $version, $supported_versions ) && 'MSIE' == $agent && ( $version == $current_version ) ) {
			$response = true;
		}

		return $response;
	} // End is_ie()
}

/*-----------------------------------------------------------------------------------*/
/* Check if WooCommerce is activated */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	function is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
	}
}

/*-----------------------------------------------------------------------------------*/
/**
 * woo_archive_description()
 *
 * Display a description, if available, for the archive being viewed (category, tag, other taxonomy).
 *
 * @since V1.0.0
 * @uses do_atomic(), get_queried_object(), term_description()
 * @echo string
 * @filter woo_archive_description
 */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_archive_description' ) ) {
	function woo_archive_description ( $echo = true ) {
		do_action( 'woo_archive_description' );

		// Archive Description, if one is available.
		$term_obj = get_queried_object();
		$description = term_description( $term_obj->term_id, $term_obj->taxonomy );

		if ( $description != '' ) {
			// Allow child themes/plugins to filter here ( 1: text in DIV and paragraph, 2: term object )
			$description = apply_filters( 'woo_archive_description', '<div class="archive-description">' . $description . '</div><!--/.archive-description-->', $term_obj );
		}

		if ( $echo != true ) { return $description; }

		echo $description;
	} // End woo_archive_description()
}

/*-----------------------------------------------------------------------------------*/
/* Add body class if Fixed Mobile width enabled */
/*-----------------------------------------------------------------------------------*/

add_filter( 'body_class', 'woo_add_fixed_mobile_class', 10 );

function woo_add_fixed_mobile_class ( $classes ) {
	global $woo_options;

	if ( isset( $woo_options['woo_remove_responsive'] ) && $woo_options['woo_remove_responsive'] == 'true' ) {
		$classes[] = 'fixed-mobile';
	}

	return $classes;
} // End woo_add_fixed_mobile_class()

/*-----------------------------------------------------------------------------------*/
/* Get a menu name */
/*-----------------------------------------------------------------------------------*/

function woo_get_menu_name( $location ){
    if( ! has_nav_menu( $location ) ) return false;
    $menus = get_nav_menu_locations();
    $menu_title = wp_get_nav_menu_object( $menus[$location] ) -> name;
    return $menu_title;
}


/*-----------------------------------------------------------------------------------*/
/* END */
/*-----------------------------------------------------------------------------------*/
?>