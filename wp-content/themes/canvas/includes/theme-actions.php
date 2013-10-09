<?php
	$woo_options = get_option( 'woo_options' );

/*------------------------------------------------------------------------------------

TABLE OF CONTENTS

- Theme Setup
- Woo Conditionals
- Add Google Maps to HEAD
- Load style.css in the <head>
- Add custom styling
- Add layout to body_class output
- WooSlider Setup
- WooSlider Magazine template
- Navigation
- Post More
- Video Embed
- Single Post Author
- Yoast Breadcrumbs
- Subscribe & Connect
- Optional Top Navigation (WP Menus)
- Footer Widgetized Areas
- Add customisable footer areas
- Add customisable post meta
- Add Post Thumbnail to Single posts on Archives
- Post Inside After
- Modify the default "comment" form field.
- Add theme default comment form fields.
- Add theme default comment form arguments.
- Activate shortcode compatibility in our new custom areas.
- woo_content_templates_magazine()
- woo_feedburner_link()
- Help WooTumblog to recognise if it's on the "Magazine" page template
- Enqueue Dynamic CSS
- Load responsive IE scripts
- Load site width CSS in the header
- Function to optionally remove responsive design and load in fallback CSS styling.
- Remove responsive design in IE8
- Adjust the homepage query, if using the "Magazine" page template as the homepage.
- Enable Tumblog
- Full width header
- Full width footer
- Full Width Markup Functions
- Full width body classes
- Optionally load custom logo.
- Optionally load the mobile navigation toggle.
- Optionally load top ad section into the header.

------------------------------------------------------------------------------------*/

add_action( 'wp_head', 'woo_enqueue_custom_styling' );	 				// Check for an enqueue custom styles, if necessary.
add_filter( 'body_class','woo_layout_body_class', 10 );					// Add layout to body_class output
add_action( 'woo_head','woo_slider', 10 );								// WooSlider Setup
add_action( 'woo_header_after','woo_nav', 10 );							// Navigation
add_action( 'woo_nav_inside','woo_nav_subscribe', 20 );					// Subscribe links in navigation
add_action( 'woo_head', 'woo_conditionals', 10 );						// Woo Conditionals
add_action( 'wp_head', 'woo_author', 10 );								// Author Box
add_action( 'woo_post_after', 'woo_postnav', 10 );						// Single post navigation
add_action( 'wp_head', 'woo_google_webfonts', 10 );						// Add Google Fonts output to HEAD

if ( isset( $woo_options['woo_breadcrumbs_show'] ) && $woo_options['woo_breadcrumbs_show'] == 'true' ) {
	add_action( 'woo_loop_before', 'woo_breadcrumbs', 10 );				// Breadcrumbs
}

add_action( 'wp_head', 'woo_subscribe_connect_action', 10 );			// Subscribe & Connect
add_action( 'woo_top', 'woo_top_navigation', 10 );						// Optional Top Navigation (WP Menus)

if ( isset( $woo_options['woo_remove_responsive'] ) && $woo_options['woo_remove_responsive'] == 'true' ) {
	add_action( 'init', 'woo_remove_responsive_design', 10 );			// Remove responsive design
}

/* Remove the banner warning about static home page */
if ( is_admin() && current_user_can( 'manage_options' ) && ( 0 < intval( get_option( 'page_on_front' ) ) ) ) {
	remove_action( 'wooframework_container_inside', 'wooframework_add_static_front_page_banner' );
}

// Output stylesheet and custom.css after Canvas custom styling
if ( ! is_admin() )
	add_action( 'woo_head', 'woo_load_custom_css_last', 20 );

function woo_load_custom_css_last() {
	remove_action( 'wp_head', 'woothemes_wp_head' );
	add_action( 'woo_head', 'woothemes_wp_head' );
}


/*-----------------------------------------------------------------------------------*/
/* Theme Setup */
/*-----------------------------------------------------------------------------------*/
/**
 * Theme Setup
 *
 * This is the general theme setup, where we add_theme_support(), create global variables
 * and setup default generic filters and actions to be used across our theme.
 *
 * @package WooFramework
 * @subpackage Logic
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */

if ( ! isset( $content_width ) ) $content_width = 640;

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support for post thumbnails.
 *
 * To override woothemes_setup() in a child theme, add your own woothemes_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses add_editor_style() To style the visual editor.
 */

add_action( 'after_setup_theme', 'woothemes_setup' );

if ( ! function_exists( 'woothemes_setup' ) ) {
	function woothemes_setup () {
		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();

		// This theme uses post thumbnails
		add_theme_support( 'post-thumbnails' );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );
	} // End woothemes_setup()
}

/*-----------------------------------------------------------------------------------*/
/* Woo Conditionals */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_conditionals' ) ) {
	function woo_conditionals () {
		// Video Embed
		if( is_single() && ( 'portfolio' != get_post_type() ) ) {
			add_action( 'woo_post_inside_before', 'canvas_get_embed' );
		}

		// Post More
		if ( ! is_singular() && ! is_404() || is_page_template( 'template-blog.php' ) || is_page_template( 'template-magazine.php' ) ) {
			add_action( 'woo_post_inside_after', 'woo_post_more' );
		}

		// Tumblog Content
		if ( 'true' == get_option( 'woo_woo_tumblog_switch' ) ) {
			add_action( 'woo_tumblog_content_before', 'woo_tumblog_content' );
			add_action( 'woo_tumblog_content_after', 'woo_tumblog_content' );
		}
	} // End woo_conditionals()
}

/*-----------------------------------------------------------------------------------*/
/* Add Google Maps to HEAD */
/*-----------------------------------------------------------------------------------*/

add_action( 'woo_head', 'woo_google_maps', 10 ); // Add custom styling to HEAD

if ( ! function_exists( 'woo_google_maps' ) ) {
	function woo_google_maps() {
		if ( is_page_template( 'template-contact.php' ) ) {
			$maps_url = 'http://maps.google.com/maps/api/js?sensor=false';
			if ( is_ssl() ) $maps_url = str_replace( 'http://', 'https://', $maps_url );
		?>
			<script type="text/javascript" src="<?php echo esc_url( $maps_url ); ?>"></script>
		<?php
		}
	} // End woo_google_maps()
}

/*-----------------------------------------------------------------------------------*/
/* Load style.css in the <head> */
/*-----------------------------------------------------------------------------------*/

if ( ! is_admin() ) { add_action( 'wp_enqueue_scripts', 'woo_load_frontend_css', 20 ); }

if ( ! function_exists( 'woo_load_frontend_css' ) ) {
function woo_load_frontend_css () {
	wp_register_style( 'theme-stylesheet', get_stylesheet_uri(), array(), '5.2.0', 'all' );
	wp_enqueue_style( 'theme-stylesheet' );
	wp_register_style( 'woo-layout', get_template_directory_uri() . '/css/layout.css' );
	wp_enqueue_style( 'woo-layout' );
} // End woo_load_frontend_css()
}

/*-----------------------------------------------------------------------------------*/
/* Load responsive <meta> tags in the <head> */
/*-----------------------------------------------------------------------------------*/

add_action( 'wp_head', 'woo_load_responsive_meta_tags', 10 );

if ( ! function_exists( 'woo_load_responsive_meta_tags' ) ) {
function woo_load_responsive_meta_tags () {
	$html = '';

	$html .= "\n" . '<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->' . "\n";
	$html .= '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />' . "\n";

	/* Remove this if not responsive design */
	$html .= "\n" . '<!--  Mobile viewport scale -->' . "\n";
	$html .= '<meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" name="viewport"/>' . "\n";

	echo $html;
} // End woo_load_responsive_meta_tags()
}


/*-----------------------------------------------------------------------------------*/
/* // Add custom styling */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_custom_styling' ) ) {
function woo_custom_styling() {
	global $woo_options;

	$output = '';

	// Logo
	if ( isset( $woo_options['woo_logo'] ) && ! $woo_options['woo_logo'] ) $output .= '#logo .site-title, #logo .site-description { display:block!important; }' . "\n";

	// Check if we are wanting to generate the custom styling or not.
	if ( isset( $woo_options['woo_style_disable'] ) && $woo_options['woo_style_disable'] != 'true' ) {} else {
		if ( $output != '' ) { echo $output; }
		return;
	}

		// Layout styling
		$bg = $woo_options['woo_style_bg'];
		$bg_image = $woo_options['woo_style_bg_image'];
		$bg_image_repeat = $woo_options['woo_style_bg_image_repeat'];
		$bg_image_pos = $woo_options['woo_style_bg_image_pos'];
		$bg_image_attach = $woo_options['woo_style_bg_image_attach'];
		$border_top = $woo_options['woo_border_top'];
		$border_general = $woo_options['woo_style_border'];

		$body = '';
		if ($bg)
			$body .= 'background-color:'.$bg.';';
		if ($bg_image)
			$body .= 'background-image:url('.$bg_image.');';
		if ($bg_image_repeat)
			$body .= 'background-repeat:'.$bg_image_repeat.';';
		if ($bg_image_pos)
			$body .= 'background-position:'.$bg_image_pos.';';
		if ($bg_image_attach)
			$body .= 'background-attachment:'.$bg_image_attach.';';
		if ($border_top && $border_top['width'] >= 0)
			$body .= 'border-top:'.$border_top["width"].'px '.$border_top["style"].' '.$border_top["color"].';';

		if ( $body != '' )
			$output .= 'body {'. $body . '}'. "\n";

		if ( $border_general )
			$output .= 'hr, .entry img, img.thumbnail, .entry .wp-caption, #footer-widgets, #comments, #comments .comment.thread-even, #comments ul.children li, .entry h1{border-color:'. $border_general . '}'. "\n";


		// General styling
		$link = $woo_options['woo_link_color'];
		$hover = $woo_options['woo_link_hover_color'];
		$button = $woo_options['woo_button_color'];
		$button_hover = $woo_options['woo_button_hover_color'];

		if ($link)
			$output .= 'a:link, a:visited {color:'.$link.'}' . "\n";
		if ($hover)
			$output .= 'a:hover, .post-more a:hover, .post-meta a:hover, .post p.tags a:hover {color:'.$hover.'}' . "\n";
		if ($button)
			$output .= 'body #wrapper #content .button, body #wrapper #content .button:visited, body #wrapper #content .reply a, body #wrapper #content #respond .form-submit input#submit {border: none; background:'.$button.'}' . "\n";
		if ($button_hover)
			$output .= 'body #wrapper #content .button:hover, body #wrapper #content .reply a:hover, body #wrapper #content #respond .form-submit input#submit:hover {border: none; background:'.$button_hover.'}' . "\n";

		// Header styling
		$header_bg = $woo_options['woo_header_bg'];
		$header_bg_image = $woo_options['woo_header_bg_image'];
		$header_bg_image_repeat = $woo_options['woo_header_bg_image_repeat'];
		$header_border = $woo_options['woo_header_border'];
		$header_margin_top = $woo_options['woo_header_margin_top'];
		$header_margin_bottom = $woo_options['woo_header_margin_bottom'];
		$header_padding_top = $woo_options['woo_header_padding_top'];
		$header_padding_bottom = $woo_options['woo_header_padding_bottom'];
		$header_padding_left = $woo_options['woo_header_padding_left'];
		$header_padding_right = $woo_options['woo_header_padding_right'];
		$font_logo = $woo_options['woo_font_logo'];
		$font_desc = $woo_options['woo_font_desc'];

		$header_css = '';
		if ( $header_bg )
			$header_css .= 'background-color:'.$header_bg.';';
		if ( $header_bg_image )
			$header_css .= 'background-image:url('.$header_bg_image.');';
		if ( $header_bg_image_repeat )
			$header_css .= 'background-repeat:'.$header_bg_image_repeat.';background-position:top center;';
		if ( $header_margin_top <> '' || $header_margin_bottom <> '' )
			$header_css .= 'margin-top:'.$header_margin_top.'px;margin-bottom:'.$header_margin_bottom.'px;';
		if ( $header_padding_top <> '' || $header_padding_bottom <> '' )
			$header_css .= 'padding-top:'.$header_padding_top.'px;padding-bottom:'.$header_padding_bottom.'px;';
		if ( $header_border && $header_border['width'] >= 0)
			$header_css .= 'border:'.$header_border["width"].'px '.$header_border["style"].' '.$header_border["color"].';';
		/*if ( $header_border && $header_border['width'] > 0) {
			$width = get_option('woo_layout_width') - $header_border['width']*2;
			if ( $width > 0 )
				$header_css .= 'width:'.$width.'px;';
		}*/
		if ( $header_css != '' )
			$output .= '#header {'. $header_css . '}'. "\n";

		if ( $header_padding_left <> '' )
			$output .= '#logo {padding-left:'.$header_padding_left.'px;}';
		if ( $header_padding_right <> '' )
			$output .= '#topad {padding-right:'.$header_padding_right.'px;}'. "\n";
		if ( $font_logo )
			$output .= '#logo .site-title a {' . woo_generate_font_css( $font_logo ) . '}' . "\n";
		if ( $font_desc )
			$output .= '#logo .site-description {' . woo_generate_font_css( $font_desc ) . '}' . "\n";


		// Boxed styling
		$boxed = $woo_options['woo_layout_boxed'];
		$box_bg = $woo_options['woo_style_box_bg'];
		$box_margin_top = $woo_options['woo_box_margin_top'];
		$box_margin_bottom = $woo_options['woo_box_margin_bottom'];
		$box_border_tb = $woo_options['woo_box_border_tb'];
		$box_border_lr = $woo_options['woo_box_border_lr'];
		$box_border_radius = $woo_options['woo_box_border_radius'];
		$box_shadow = $woo_options['woo_box_shadow'];

		$wrapper = '';
		if ($boxed == "true") {
			//$wrapper .= 'margin:0 auto;padding:0 0 20px 0;width:'.get_option('woo_layout_width').';';
			if ( get_option('woo_layout_width') == '940px' )
				$wrapper .= 'padding-left:20px; padding-right:20px;';
			else
				$wrapper .= 'padding-left:30px; padding-right:30px;';
		}
		if ($boxed == "true" && $box_bg)
			$wrapper .= 'background-color:'.$box_bg.';';
		if ($boxed == "true" && ($box_margin_top || $box_margin_bottom) )
			$wrapper .= 'margin-top:'.$box_margin_top.'px;margin-bottom:'.$box_margin_bottom.'px;';
		if ($boxed == "true" && $box_border_tb["width"] > 0 )
			$wrapper .= 'border-top:'.$box_border_tb["width"].'px '.$box_border_tb["style"].' '.$box_border_tb["color"].';border-bottom:'.$box_border_tb["width"].'px '.$box_border_tb["style"].' '.$box_border_tb["color"].';';
		if ($boxed == "true" && $box_border_lr["width"] > 0 )
			$wrapper .= 'border-left:'.$box_border_lr["width"].'px '.$box_border_lr["style"].' '.$box_border_lr["color"].';border-right:'.$box_border_lr["width"].'px '.$box_border_lr["style"].' '.$box_border_lr["color"].';';
		if ( $boxed == "true" && $box_border_radius )
			$wrapper .= 'border-radius:'.$box_border_radius.';-moz-border-radius:'.$box_border_radius.';-webkit-border-radius:'.$box_border_radius.';';
		if ( $boxed == "true" && $box_shadow == "true" )
			$wrapper .= 'box-shadow: 0px 1px 5px rgba(0,0,0,.1);';

		if ( $wrapper != '' )
			$output .= '#wrapper {'. $wrapper . '} @media only screen and (max-width:767px) { #wrapper { padding-left: 0; padding-right: 0;} } '. "\n";


		// Full width layout
		if ( $boxed != "true" && (isset( $woo_options['woo_header_full_width'] ) && ( $woo_options['woo_header_full_width']  == 'true'  )  ||  isset( $woo_options['woo_footer_full_width'] ) && ( $woo_options['woo_footer_full_width'] == 'true' ) ) ) {

			if ( isset( $woo_options['woo_header_full_width'] ) && $woo_options['woo_header_full_width'] == 'true' ) {

				$full_head_bg = $woo_options['woo_full_header_full_width_bg'];
				$full_header_bg_image = $woo_options['woo_full_header_bg_image'];
				$full_header_bg_image_repeat = $woo_options['woo_full_header_bg_image_repeat'];
				$full_nav_bg = $woo_options['woo_nav_bg'];
				$full_nav_border = $woo_options['woo_nav_divider_border'];
				$full_nav_border_top = $woo_options['woo_nav_border_top'];
				$full_nav_border_bot = $woo_options['woo_nav_border_bot'];

				if ( $full_head_bg )
					$output .= '#header-container{background-color:' . $full_head_bg . ';}';

				if ( $full_header_bg_image )
					$output .= '#header-container{background-image:url('.$full_header_bg_image.');background-repeat:'.$full_header_bg_image_repeat.';background-position:top center;}';
				
				if ( $full_nav_bg )
					$output .= '#nav-container{background:' . $full_nav_bg . ';}';

				if ( $full_nav_border_top && $full_nav_border_top["width"] >= 0 )
					$output .= '#nav-container{border-top:'.$full_nav_border_top["width"].'px '.$full_nav_border_top["style"].' '.$full_nav_border_top["color"].';border-bottom:'.$full_nav_border_bot["width"].'px '.$full_nav_border_bot["style"].' '.$full_nav_border_bot["color"].';border-left:none;border-right:none;}';

				if ( $full_nav_border && $full_nav_border["width"] >= 0 )
					$output .= '#nav-container #navigation ul#main-nav > li:first-child{border-left: '.$full_nav_border["width"].'px '.$full_nav_border["style"].' '.$full_nav_border["color"].';}';

			}

			if ( isset( $woo_options['woo_footer_full_width'] ) && ( 'true' == $woo_options['woo_footer_full_width'] ) ) {

				$full_foot_widget_bg = $woo_options['woo_foot_full_width_widget_bg'];
				$full_foot_bg = $woo_options['woo_footer_full_width_bg'];
				$footer_bg = $woo_options['woo_footer_bg'];
				$full_footer_border_top = $woo_options['woo_footer_border_top'];

				if ( $full_foot_widget_bg )
					$output .= '#footer-widgets-container{background-color:' . $full_foot_widget_bg . '}#footer-widgets{border:none;}';

				if ( $full_foot_bg )
					$output .= '#footer-container{background-color:' . $full_foot_bg . '}';

				if ( $full_footer_border_top && $full_footer_border_top["width"] >= 0 )
					$output .= '#footer-container{border-top:'.$full_footer_border_top["width"].'px '.$full_footer_border_top["style"].' '.$full_footer_border_top["color"].';}#footer {border-width: 0 !important;}';

			}
			$output .= "\n";

		}

		// General Typography
		$font_text = $woo_options['woo_font_text'];
		$font_h1 = $woo_options['woo_font_h1'];
		$font_h2 = $woo_options['woo_font_h2'];
		$font_h3 = $woo_options['woo_font_h3'];
		$font_h4 = $woo_options['woo_font_h4'];
		$font_h5 = $woo_options['woo_font_h5'];
		$font_h6 = $woo_options['woo_font_h6'];

		if ( $font_text )
			$output .= 'body, p { ' . woo_generate_font_css( $font_text, 1.5 ) . ' }' . "\n";
		if ( $font_h1 )
			$output .= 'h1 { ' . woo_generate_font_css( $font_h1, 1.2 ) . ' }';
		if ( $font_h2 )
			$output .= 'h2 { ' . woo_generate_font_css( $font_h2, 1.2 ) . ' }';
		if ( $font_h3 )
			$output .= 'h3 { ' . woo_generate_font_css( $font_h3, 1.2 ) . ' }';
		if ( $font_h4 )
			$output .= 'h4 { ' . woo_generate_font_css( $font_h4, 1.2 ) . ' }';
		if ( $font_h5 )
			$output .= 'h5 { ' . woo_generate_font_css( $font_h5, 1.2 ) . ' }';
		if ( $font_h6 )
			$output .= 'h6 { ' . woo_generate_font_css( $font_h6, 1.2 ) . ' }' . "\n";

		// Post Styling
		$font_post_title = $woo_options['woo_font_post_title'];
		$font_post_meta = $woo_options['woo_font_post_meta'];
		$font_post_text = $woo_options['woo_font_post_text'];
		$font_post_more = $woo_options['woo_font_post_more'];
		$post_more_border_top = $woo_options['woo_post_more_border_top'];
		$post_more_border_bottom = $woo_options['woo_post_more_border_bottom'];
		$post_comments_bg = $woo_options['woo_post_comments_bg'];
		$post_author_border_top = $woo_options['woo_post_author_border_top'];
		$post_author_border_bottom = $woo_options['woo_post_author_border_bottom'];
		$post_author_border_lr = $woo_options['woo_post_author_border_lr'];
		$post_author_border_radius = $woo_options['woo_post_author_border_radius'];
		$post_author_bg = $woo_options['woo_post_author_bg'];

		if ( $font_post_title )
			$output .= '.post .title, .page .title, .post .title a:link, .post .title a:visited, .page .title a:link, .page .title a:visited {'.woo_generate_font_css( $font_post_title, 1.1 ).'}' . "\n";
		if ( $font_post_meta )
			$output .= '.post-meta { ' . woo_generate_font_css( $font_post_meta, 1.5 ) . ' }' . "\n";
		if ( $font_post_text )
			$output .= '.entry, .entry p{ ' . woo_generate_font_css( $font_post_text, 1.5 ) . ' }' . "\n";
		$post_more_border = '';
		if ( $font_post_more )
			$post_more_border .= 'font:'.$font_post_more["style"].' '.$font_post_more["size"].$font_post_more["unit"].'/1.5em '.stripslashes($font_post_more["face"]).';color:'.$font_post_more["color"].';';
		if ( $post_more_border_top )
			$post_more_border .= 'border-top:'.$post_more_border_top["width"].'px '.$post_more_border_top["style"].' '.$post_more_border_top["color"].';';
		if ( $post_more_border_bottom )
			$post_more_border .= 'border-bottom:'.$post_more_border_bottom["width"].'px '.$post_more_border_bottom["style"].' '.$post_more_border_bottom["color"].';';
		if ( $post_more_border )
		$output .= '.post-more {'.$post_more_border .'}' . "\n";

		if ( $post_comments_bg )
			$output .= '#comments .comment.thread-even {background-color:'.$post_comments_bg.';}' . "\n";

		$post_author = '';
		if ( $post_author_border_top )
			$post_author .= 'border-top:'.$post_author_border_top["width"].'px '.$post_author_border_top["style"].' '.$post_author_border_top["color"].';';
		if ( $post_author_border_bottom )
			$post_author .= 'border-bottom:'.$post_author_border_bottom["width"].'px '.$post_author_border_bottom["style"].' '.$post_author_border_bottom["color"].';';
		if ( $post_author_border_lr )
			$post_author .= 'border-left:'.$post_author_border_lr["width"].'px '.$post_author_border_lr["style"].' '.$post_author_border_lr["color"].';border-right:'.$post_author_border_lr["width"].'px '.$post_author_border_lr["style"].' '.$post_author_border_lr["color"].';';
		if ( $post_author_border_radius )
			$post_author .= 'border-radius:'.$post_author_border_radius.';-moz-border-radius:'.$post_author_border_radius.';-webkit-border-radius:'.$post_author_border_radius.';';
		if ( $post_author_bg )
			$post_author .= 'background-color:'.$post_author_bg;

		if ( $post_author )
			$output .= '#post-author, #connect {'.$post_author .'}' . "\n";

		if ( $post_comments_bg )
			$output .= '#comments .comment.thread-even {background-color:'.$post_comments_bg.';}' . "\n";

		// Page Nav Styling
		$pagenav_font = $woo_options['woo_pagenav_font'];
		$pagenav_bg = $woo_options['woo_pagenav_bg'];
		$pagenav_border_top = $woo_options['woo_pagenav_border_top'];
		$pagenav_border_bottom = $woo_options['woo_pagenav_border_bottom'];

		$pagenav_css = '';
		if ( $pagenav_bg )
			$pagenav_css .= 'background-color:'.$pagenav_bg.';';
		if ( $pagenav_border_top && $pagenav_border_top["width"] > 0 )
			$pagenav_css .= 'border-top:'.$pagenav_border_top["width"].'px '.$pagenav_border_top["style"].' '.$pagenav_border_top["color"].';';
		if ( $pagenav_border_bottom && $pagenav_border_bottom["width"] > 0 )
			$pagenav_css .= 'border-bottom:'.$pagenav_border_bottom["width"].'px '.$pagenav_border_bottom["style"].' '.$pagenav_border_bottom["color"].';';
		if ( $pagenav_css != '' )
			$output .= '.nav-entries, .woo-pagination {'. $pagenav_css . ' padding: 12px 0px; }'. "\n";
		if ( $pagenav_font ) {
			$output .= '.nav-entries a, .woo-pagination { ' . woo_generate_font_css( $pagenav_font ) . ' }' . "\n";
			$output .= '.woo-pagination a, .woo-pagination a:hover {color:'.$pagenav_font["color"].'!important}' . "\n";
		}
		// Widget Styling
		$widget_font_title = $woo_options['woo_widget_font_title'];
		$widget_font_text = $woo_options['woo_widget_font_text'];
		$widget_padding_tb = $woo_options['woo_widget_padding_tb'];
		$widget_padding_lr = $woo_options['woo_widget_padding_lr'];
		$widget_bg = $woo_options['woo_widget_bg'];
		$widget_border = $woo_options['woo_widget_border'];
		$widget_title_border = $woo_options['woo_widget_title_border'];
		$widget_border_radius = $woo_options['woo_widget_border_radius'];

		$h3_css = '';
		if ( $widget_font_title )
			$h3_css .= 'font:'.$widget_font_title["style"].' '.$widget_font_title["size"].$widget_font_title["unit"].'/1.2em '.stripslashes($widget_font_title["face"]).';color:'.$widget_font_title["color"].';';
		if ( $widget_title_border )
			$h3_css .= 'border-bottom:'.$widget_title_border["width"].'px '.$widget_title_border["style"].' '.$widget_title_border["color"].';';
		if ( isset( $widget_title_border["width"] ) AND $widget_title_border["width"] == 0 )
			$h3_css .= 'margin-bottom:0;';

		if ( $h3_css != '' )
			$output .= '.widget h3 {'. $h3_css . '}'. "\n";

		if ( $widget_title_border )
			$output .= '.widget_recent_comments li, #twitter li { border-color: '.$widget_title_border["color"].';}'. "\n";

		if ( $widget_font_text )
			$output .= '.widget p, .widget .textwidget { ' . woo_generate_font_css( $widget_font_text, 1.5 ) . ' }' . "\n";

		$widget_css = '';
		if ( $widget_font_text )
			$widget_css .= 'font:'.$widget_font_text["style"].' '.$widget_font_text["size"].$widget_font_text["unit"].'/1.5em '.stripslashes($widget_font_text["face"]).';color:'.$widget_font_text["color"].';';
		if ( $widget_padding_tb || $widget_padding_lr )
			$widget_css .= 'padding:'.$widget_padding_tb.'px '.$widget_padding_lr.'px;';
		if ( $widget_bg )
			$widget_css .= 'background-color:'.$widget_bg.';';
		if ( $widget_border["width"] > 0 )
			$widget_css .= 'border:'.$widget_border["width"].'px '.$widget_border["style"].' '.$widget_border["color"].';';
		if ( $widget_border_radius )
			$widget_css .= 'border-radius:'.$widget_border_radius.';-moz-border-radius:'.$widget_border_radius.';-webkit-border-radius:'.$widget_border_radius.';';

		if ( $widget_css != '' )
			$output .= '.widget {'. $widget_css . '}'. "\n";

		if ( $widget_border["width"] > 0 )
			$output .= '#tabs {border:'.$widget_border["width"].'px '.$widget_border["style"].' '.$widget_border["color"].';}'. "\n";

		// Tabs Widget
		$widget_tabs_bg = $woo_options['woo_widget_tabs_bg'];
		$widget_tabs_bg_inside = $woo_options['woo_widget_tabs_bg_inside'];
		$widget_tabs_font = $woo_options['woo_widget_tabs_font'];
		$widget_tabs_font_meta = $woo_options['woo_widget_tabs_font_meta'];

		if ( $widget_tabs_bg )
			$output .= '#tabs, .widget_woodojo_tabs .tabbable {background-color:'.$widget_tabs_bg.';}'. "\n";
		if ( $widget_tabs_bg_inside )
			$output .= '#tabs .inside, #tabs ul.wooTabs li a.selected, #tabs ul.wooTabs li a:hover {background-color:'.$widget_tabs_bg_inside.';}'. "\n";
		if ( $widget_tabs_font )
			$output .= '#tabs .inside li a, .widget_woodojo_tabs .tabbable .tab-pane li a { ' . woo_generate_font_css( $widget_tabs_font, 1.5 ) . ' }'. "\n";
		if ( $widget_tabs_font_meta )
			$output .= '#tabs .inside li span.meta, .widget_woodojo_tabs .tabbable .tab-pane li span.meta { ' . woo_generate_font_css( $widget_tabs_font_meta, 1.5 ) . ' }'. "\n";
			$output .= '#tabs ul.wooTabs li a, .widget_woodojo_tabs .tabbable .nav-tabs li a { ' . woo_generate_font_css( $widget_tabs_font_meta, 2 ) . ' }'. "\n";

		//Navigation
		$nav_bg = $woo_options['woo_nav_bg'];
		$nav_font = $woo_options['woo_nav_font'];
		$nav_hover = $woo_options['woo_nav_hover'];
		$nav_hover_bg = $woo_options['woo_nav_hover_bg'];
		$nav_currentitem = $woo_options['woo_nav_currentitem'];
		$nav_currentitem_bg = $woo_options['woo_nav_currentitem_bg'];
		$nav_divider_border = $woo_options['woo_nav_divider_border'];
		$nav_dropdown_border = $woo_options['woo_nav_dropdown_border'];
		$nav_border_lr = $woo_options['woo_nav_border_lr'];
		$nav_border_radius = $woo_options['woo_nav_border_radius'];
		$nav_border_top = $woo_options['woo_nav_border_top'];
		$nav_border_bot = $woo_options['woo_nav_border_bot'];

		$top_nav_bg = $woo_options['woo_top_nav_bg'];
		$top_nav_hover = $woo_options['woo_top_nav_hover'];
		$top_nav_hover_bg = $woo_options['woo_top_nav_hover_bg'];
		$top_nav_font = $woo_options['woo_top_nav_font'];

		$output .= '@media only screen and (min-width:768px) {' . "\n";
		if ( $nav_font )
			$output .= 'ul.nav li a, #navigation ul.rss a, #navigation .cart-contents, #navigation ul.rss { ' . woo_generate_font_css( $nav_font ) . ' }' . "\n";
		if ( $nav_hover )
			$output .= '#navigation ul.nav > li a:hover, #navigation ul.nav > li:hover a, #navigation ul.nav li ul li a { color:'.$nav_hover.'!important; }' . "\n";
		if ( $nav_hover_bg )
			$output .= '#navigation ul.nav > li a:hover, #navigation ul.nav > li:hover, #navigation ul.nav li ul {background-color:'.$nav_hover_bg.'!important}' . "\n";

		if ( $nav_dropdown_border && $nav_dropdown_border["width"] >= 0 ) {
			$output .= '#navigation ul.nav li ul  { border: '.$nav_dropdown_border["width"].'px '.$nav_dropdown_border["style"].' '.$nav_dropdown_border["color"].'; }' . "\n";
			if ($nav_dropdown_border["width"] == 0) {
				$output .= '#navigation ul.nav > li > ul  { left: 0; }' . "\n";
			}
		}

		if ( $nav_divider_border && $nav_divider_border["width"] >= 0 ) {
			$output .= '#navigation ul.nav > li  { border-right: '.$nav_divider_border["width"].'px '.$nav_divider_border["style"].' '.$nav_divider_border["color"].'; }';
			if ($nav_divider_border["width"] == 0) {
				$output .= '#navigation ul.nav > li > ul  { left: 0; }' . "\n";
			}
		}

		// If we have a hover colour and don't have a current item colour, we use the hover colour as current item colour.
		if ( $nav_currentitem_bg == '' && $nav_hover != '' ) { $nav_currentitem_bg = $nav_hover; }

		if ( $nav_currentitem_bg ) {
			$output .= '#navigation ul.nav li.current_page_item a, #navigation ul.nav li.current_page_parent a, #navigation ul.nav li.current-menu-ancestor a, #navigation ul.nav li.current-cat a, #navigation ul.nav li.current-menu-item a { background-color:' . $nav_currentitem_bg . '; }' . "\n";
		}

		if ( $nav_currentitem ) {
			$output .= '#navigation ul.nav li.current_page_item a, #navigation ul.nav li.current_page_parent a, #navigation ul.nav li.current-menu-ancestor a, #navigation ul.nav li.current-cat a, #navigation ul.nav li.current-menu-item a { color:' . $nav_currentitem . '; }' . "\n";
		}

		$navigation_css = '';
		if ( $nav_bg )
			$navigation_css .= 'background:'.$nav_bg.';';
		if ( $nav_border_top && $nav_border_top["width"] >= 0 )
			$navigation_css .= 'border-top:'.$nav_border_top["width"].'px '.$nav_border_top["style"].' '.$nav_border_top["color"].';border-bottom:'.$nav_border_bot["width"].'px '.$nav_border_bot["style"].' '.$nav_border_bot["color"].';border-left:'.$nav_border_lr["width"].'px '.$nav_border_lr["style"].' '.$nav_border_lr["color"].';border-right:'.$nav_border_lr["width"].'px '.$nav_border_lr["style"].' '.$nav_border_lr["color"].';';
		if ( $nav_border_bot && $nav_border_bot["width"] == 0 )
			$output .= '#navigation  { box-shadow: none; -moz-box-shadow: none; -webkit-box-shadow: none; }';
		if ( $nav_border_radius )
			$navigation_css .= 'border-radius:'.$nav_border_radius.'; -moz-border-radius:'.$nav_border_radius.'; -webkit-border-radius:'.$nav_border_radius.';';

		if ( $nav_border_radius )
			$output .= '#navigation ul li:first-child, #navigation ul li:first-child a { border-radius:'.$nav_border_radius.' 0 0 '.$nav_border_radius.'; -moz-border-radius:'.$nav_border_radius.' 0 0 '.$nav_border_radius.'; -webkit-border-radius:'.$nav_border_radius.' 0 0 '.$nav_border_radius.'; }' . "\n";

		if ( $navigation_css != '' )
			$output .= '@media only screen and (min-width: 768px) { #navigation {'. $navigation_css . '} }'. "\n";

		if ( $top_nav_bg )
			$output .= '#top, #top ul.nav li ul li a:hover { background:'.$top_nav_bg.';}'. "\n";

		if ( $top_nav_hover )
			$output .= '#top ul.nav li a:hover, #top ul.nav li.current_page_item a, #top ul.nav li.current_page_parent a,#top ul.nav li.current-menu-ancestor a,#top ul.nav li.current-cat a,#top ul.nav li.current-menu-item a,#top ul.nav li.sfHover, #top ul.nav li ul, #top ul.nav > li:hover a, #top ul.nav li ul li a { color:'.$top_nav_hover.'!important;}'. "\n";

		if ( $top_nav_hover_bg )
			$output .= '#top ul.nav li a:hover, #top ul.nav li.current_page_item a, #top ul.nav li.current_page_parent a,#top ul.nav li.current-menu-ancestor a,#top ul.nav li.current-cat a,#top ul.nav li.current-menu-item a,#top ul.nav li.sfHover, #top ul.nav li ul, #top ul.nav > li:hover { background:'.$top_nav_hover_bg.';}'. "\n";

		if ( $top_nav_font ) {
			$output .= '#top ul.nav li a { ' . woo_generate_font_css( $top_nav_font, 1.6 ) . ' }' . "\n";
			if ( isset( $top_nav_font['color'] ) && strlen( $top_nav_font['color'] ) == 7 ) {
				$output .= '#top ul.nav li.parent > a:after { border-top-color:'. esc_attr( $top_nav_font['color'] ) .';}'. "\n";
			}
		}
		$output .= '}' . "\n";

		// Footer
		$footer_font = $woo_options['woo_footer_font'];
		$footer_bg = $woo_options['woo_footer_bg'];
		$footer_border_top = $woo_options['woo_footer_border_top'];
		$footer_border_bottom = $woo_options['woo_footer_border_bottom'];
		$footer_border_lr = $woo_options['woo_footer_border_lr'];
		$footer_border_radius = $woo_options['woo_footer_border_radius'];

		if ( $footer_font )
			$output .= '#footer, #footer p { ' . woo_generate_font_css( $footer_font, 1.4 ) . ' }' . "\n";
		$footer_css = '';
		if ( $footer_bg )
			$footer_css .= 'background-color:'.$footer_bg.';';
		if ( $footer_border_top )
			$footer_css .= 'border-top:'.$footer_border_top["width"].'px '.$footer_border_top["style"].' '.$footer_border_top["color"].';';
		if ( $footer_border_bottom )
			$footer_css .= 'border-bottom:'.$footer_border_bottom["width"].'px '.$footer_border_bottom["style"].' '.$footer_border_bottom["color"].';';
		if ( $footer_border_lr )
			$footer_css .= 'border-left:'.$footer_border_lr["width"].'px '.$footer_border_lr["style"].' '.$footer_border_lr["color"].';border-right:'.$footer_border_lr["width"].'px '.$footer_border_lr["style"].' '.$footer_border_lr["color"].';';
		if ( $footer_border_radius )
			$footer_css .= 'border-radius:'.$footer_border_radius.'; -moz-border-radius:'.$footer_border_radius.'; -webkit-border-radius:'.$footer_border_radius.';';

		if ( $footer_css != '' )
			$output .= '#footer {'. $footer_css . '}' . "\n";

		// Magazine Template
		$slider_magazine_font_title = $woo_options['woo_slider_magazine_font_title'];
		$slider_magazine_font_excerpt = $woo_options['woo_slider_magazine_font_excerpt'];

		if ( $slider_magazine_font_title )
			$output .= '.magazine #loopedSlider .content h2.title a { ' . woo_generate_font_css( $slider_magazine_font_title ) . ' }'. "\n";
		if ( $slider_magazine_font_excerpt )
			$output .= '.magazine #loopedSlider .content .excerpt p { ' . woo_generate_font_css( $slider_magazine_font_excerpt, 1.5 ) . ' }'. "\n";

		// Business Template
		$slider_biz_font_title = $woo_options['woo_slider_biz_font_title'];
		$slider_biz_font_excerpt = $woo_options['woo_slider_biz_font_excerpt'];

		if ( $slider_biz_font_title )
			$output .= '.business #loopedSlider .content h2 { ' . woo_generate_font_css( $slider_biz_font_title ) . ' }'. "\n";
			$output .= '.business #loopedSlider .content h2.title a { ' . woo_generate_font_css( $slider_biz_font_title ) . ' }'. "\n";
		if ( $slider_biz_font_excerpt )
			$output .= '#wrapper .business #loopedSlider .content p { ' . woo_generate_font_css( $slider_biz_font_excerpt, 1.5 ) . ' }'. "\n";

		// Archive Header
		$woo_archive_header_font = $woo_options['woo_archive_header_font'];
		if ( $woo_archive_header_font )
			$output .= '.archive_header { ' . woo_generate_font_css( $woo_archive_header_font ) . ' }'. "\n";
			$output .= '.archive_header {border-bottom:'.$woo_options['woo_archive_header_border_bottom']["width"].'px '.$woo_options['woo_archive_header_border_bottom']["style"].' '.$woo_options['woo_archive_header_border_bottom']["color"].';}'. "\n";
		if ( $woo_options['woo_archive_header_disable_rss'] == "true" )
			$output .= '.archive_header .catrss { display:none; }' . "\n";

	// Output styles
	if (isset($output)) {
		// $output = "\n<!-- Woo Custom Styling -->\n<style type=\"text/css\">\n" . $output . "</style>\n<!-- /Woo Custom Styling -->\n\n";
		echo $output;
	}
} // End woo_custom_styling()
}

// Returns proper font css output
if ( ! function_exists( 'woo_generate_font_css' ) ) {
	function woo_generate_font_css( $option, $em = '1' ) {

		// Test if font-face is a Google font
		global $google_fonts;
		foreach ( $google_fonts as $google_font ) {

			// Add single quotation marks to font name and default arial sans-serif ending
			if ( $option['face'] == $google_font['name'] )
				$option['face'] = "'" . $option['face'] . "', arial, sans-serif";

		} // END foreach

		if ( !@$option['style'] && !@$option['size'] && !@$option['unit'] && !@$option['color'] )
			return 'font-family: '.stripslashes($option["face"]).';';
		else
			return 'font:'.$option['style'].' '.$option['size'].$option['unit'].'/'.$em.'em '.stripslashes($option['face']).';color:'.$option['color'].';';
	} // End woo_generate_font_css()
}


/*-----------------------------------------------------------------------------------*/
/* Add layout to body_class output */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_layout_body_class' ) ) {
	function woo_layout_body_class( $classes ) {
		global $post, $wp_query, $woo_options;

		$layout = '';
		// Set main layout
		if ( is_singular() ) {
			$layout = get_post_meta( $post->ID, 'layout', true );
			if ( $layout != '' ) {
				$woo_options['woo_layout'] = $layout;
			}
		}

		// Add support for WooCommerce "Shop" landing page body CSS class
		if ( class_exists( 'woocommerce' ) && function_exists( 'is_shop' ) && $layout == '' && is_shop() ) {
			$page_id = get_option( 'woocommerce_shop_page_id' );
			$layout = get_post_meta( $page_id, 'layout', true );
			if ( $layout != '' ) {
				$woo_options['woo_layout'] = $layout;
			}
		}

		if ( $layout == '' ) {
			$layout = get_option( 'woo_layout' );
				if ( $layout == '' )
					$layout = 'two-col-left';
		}

		// Cater for custom portfolio gallery layout option.
		if ( is_tax( 'portfolio-gallery' ) || is_post_type_archive( 'portfolio' ) ) {
			$portfolio_gallery_layout = get_option( 'woo_portfolio_layout' );

			if ( $portfolio_gallery_layout != '' ) { $layout = $portfolio_gallery_layout; }
		}

		if ( is_tax( 'portfolio-gallery' ) || is_post_type_archive( 'portfolio' ) || is_page_template( 'template-portfolio.php' ) || ( is_singular() && get_post_type() == 'portfolio' ) ) {
			$classes[] = 'portfolio-component';
		}

		// Specify site width
		$width = intval( str_replace( 'px', '', get_option( 'woo_layout_width', '940' ) ) );
		// Add classes to body_class() output
		$classes[] = $layout;
		$classes[] = 'width-' . $width;
		$classes[] = $layout . '-' . $width;
		return $classes;
	} // End woo_layout_body_class()
}

/*-----------------------------------------------------------------------------------*/
/* Woo Slider Setup */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_slider' ) ) {
	function woo_slider( $load_slider_js = false ) {
		global $woo_options;

		$load_slider_js = false;

		if ( ( is_page_template( 'template-biz.php' ) && isset( $woo_options['woo_slider_biz'] ) && $woo_options['woo_slider_biz'] == 'true' ) ||
			 ( is_page_template( 'template-magazine.php' ) && isset( $woo_options['woo_slider_magazine'] ) && $woo_options['woo_slider_magazine'] == 'true' ) ||
			   is_page_template( 'template-widgets.php' ) ||
			   is_active_sidebar( 'homepage' ) ) { $load_slider_js = true; }

		// Allow child themes/plugins to load the slider JavaScript when they need it.
		$load_slider_js = (bool)apply_filters( 'woo_load_slider_js', $load_slider_js );


		if ( $load_slider_js != false ) {

		// Default slider settings.
		$defaults = array(
							'autoStart' => 0,
							'autoHeight' => 'false',
							'hoverPause' => 'false',
							'containerClick' => 'false',
							'slideSpeed' => 600,
							'canAutoStart' => 'false',
							'next' => 'next',
							'prev' => 'previous',
							'container' => 'slides',
							'generatePagination' => 'false',
							'crossfade' => 'true',
							'fadeSpeed' => 600,
							'effect' => 'slide'
						 );

		// Dynamic settings from the "Theme Options" screen.
		$args = array();

		if ( isset( $woo_options['woo_slider_pagination'] ) && $woo_options['woo_slider_pagination'] == 'true' ) { $args['generatePagination'] = 'true'; }
		if ( isset( $woo_options['woo_slider_effect'] ) && $woo_options['woo_slider_effect'] != '' ) { $args['effect'] = $woo_options['woo_slider_effect']; }
		if ( isset( $woo_options['woo_slider_autoheight'] ) && $woo_options['woo_slider_autoheight'] == 'true' ) { $args['autoHeight'] = 'true'; }
		if ( isset( $woo_options['woo_slider_hover'] ) && $woo_options['woo_slider_hover'] == 'true' ) { $args['hoverPause'] = 'true'; }
		if ( isset( $woo_options['woo_slider_containerclick'] ) && $woo_options['woo_slider_containerclick'] == 'true' ) { $args['containerClick'] = 'true'; }
		if ( isset( $woo_options['woo_slider_speed'] ) && $woo_options['woo_slider_speed'] != '' ) { $args['slideSpeed'] = $woo_options['woo_slider_speed'] * 1000; }
		if ( isset( $woo_options['woo_slider_speed'] ) && $woo_options['woo_slider_speed'] != '' ) { $args['fadeSpeed'] = $woo_options['woo_slider_speed'] * 1000; }
		if ( isset( $woo_options['woo_slider_auto'] ) && $woo_options['woo_slider_auto'] == 'true' ) {
			$args['canAutoStart'] = 'true';
			$args['autoStart'] = $woo_options['woo_slider_interval'] * 1000;
		}

		// Merge the arguments with defaults.
		$args = wp_parse_args( $args, $defaults );

		// Allow child themes/plugins to filter these arguments.
		$args = apply_filters( 'woo_slider_args', $args );

	?>
	<!-- Woo Slider Setup -->
	<script type="text/javascript">
	jQuery(window).load(function() {
		var args = {};
		args.useCSS = false;
		<?php if ( $args['effect'] == 'fade' ) { ?>args.animation = 'fade';
		<?php } else { ?>args.animation = 'slide';<?php } ?>
		<?php echo "\n"; ?>
		<?php if ( $args['canAutoStart'] == 'true' ) { ?>args.slideshow = true;
		<?php } else { ?>args.slideshow = false;<?php } ?>
		<?php echo "\n"; ?>
		<?php if ( intval( $args['autoStart'] ) > 0 ) { ?>args.slideshowSpeed = <?php echo intval( $args['autoStart'] ) ?>;<?php } ?>
		<?php echo "\n"; ?>
		<?php if ( intval( $args['slideSpeed'] ) >= 0 ) { ?>args.animationSpeed = <?php echo intval( $args['slideSpeed'] ) ?>;<?php } ?>
		<?php echo "\n"; ?>
		<?php if ( $args['generatePagination'] == 'true' ) { ?>args.controlNav = true;
		<?php } else { ?>args.controlNav = false;<?php } ?>
		<?php echo "\n"; ?>
		<?php if ( $args['hoverPause'] == 'true' ) { ?>args.pauseOnHover = true;
		<?php } else { ?>args.pauseOnHover = false;<?php } ?>
		<?php echo "\n"; ?>
		<?php if ( $args['autoHeight'] == 'true' ) { ?>args.smoothHeight = true;<?php } ?>
		args.manualControls = '.pagination-wrap .flex-control-nav > li';
		args.start = function ( slider ) {
			slider.next( '.slider-pagination' ).fadeIn();
		}

		jQuery( '.woo-slideshow' ).each( function ( i ) {
			jQuery( this ).flexslider( args );
			jQuery( this ) .find( 'a.flex-prev, a.flex-next' ).addClass( 'icon' );
		});
	});
	</script>
	<!-- /Woo Slider Setup -->
	<?php
		}
	} // End woo_slider()
}

/*-----------------------------------------------------------------------------------*/
/* Woo Slider Magazine */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_slider_magazine' ) ) {
	function woo_slider_magazine( $args = null, $tags = null ) {
		global $woo_options, $wp_query;

		// This is where our output will be added.
		$html = '';

		// Default slider settings.
		$defaults = array(
							'id' => 'loopedSlider',
							'echo' => true,
							'excerpt_length' => '15',
							'pagination' => false,
							'width' => '940',
							'height' => '350',
							'order' => 'ASC',
							'posts_per_page' => '5'
						 );

		// Setup width of slider and images
		$width = '610';

		$layout_width = get_option( 'woo_layout_width' );
		if ( $layout_width != '' ) $layout_width = intval( str_replace( 'px', '', $layout_width ) );

		$width = $layout_width;

		// Setup slider tags array
		$slider_tags = array();
		if ( is_array( $tags ) && ( 0 < count( $tags ) ) ) {
			$slider_tags = $tags;
		}

		if ( ! is_array( $tags ) && '' != $tags && ! is_null( $tags ) ) {
			$slider_tags = explode( ',', $tags );
		}

		if ( 0 >= count( $slider_tags ) ) {
			$slider_tags = explode( ',', $woo_options['woo_slider_magazine_tags'] ); // Tags to be shown
		}

		if ( 0 < count( $slider_tags ) ) {
			foreach ( $slider_tags as $tags ) {
				$tag = get_term_by( 'name', trim($tags), 'post_tag', 'ARRAY_A' );
				if ( $tag['term_id'] > 0 )
					$tag_array[] = $tag['term_id'];
			}
		}

		if ( empty( $tag_array ) ) {
			echo '<p class="woo-sc-box note">Please setup Featured Slider Tag(s) in your options panel. You must setup tags that are used on active posts.</p>';
			return;
		}

		// Setup the slider CSS class.
		$slider_css = '';

		if ( isset( $woo_options['woo_slider_pagination'] ) && $woo_options['woo_slider_pagination'] == 'true' ) {
			$slider_css = ' class="has-pagination woo-slideshow"';
		} else {
			$slider_css = ' class="woo-slideshow"';
		}

		// Setup height of slider.
		$height = $woo_options['woo_slider_magazine_height'];
		if ( $height != '' ) { $defaults['height'] = $height; }

		// Setup the number of posts to show.
		$posts_per_page = $woo_options['woo_slider_magazine_entries'];
		if ( $posts_per_page != '' ) { $defaults['posts_per_page'] = $posts_per_page; }

		// Setup the excerpt length.
		$excerpt_length = $woo_options['woo_slider_magazine_excerpt_length'];
		if ( $excerpt_length != '' ) { $defaults['excerpt_length'] = $excerpt_length; }

		if ( $width > 0 && $args['width'] == '' ) { $defaults['width'] = $width; }

		// Merge the arguments with defaults.
		$args = wp_parse_args( $args, $defaults );

		if ( ( ( isset($args['width']) ) && ( ( $args['width'] <= 0 ) || ( $args['width'] == '')  ) ) || ( !isset($args['width']) ) ) {	$args['width'] = '100'; }
		if ( ( isset($args['height']) ) && ( $args['height'] <= 0 )  ) { $args['height'] = '100'; }

		// Allow child themes/plugins to filter these arguments.
		$args = apply_filters( 'woo_magazine_slider_args', $args );

		// Begin setting up HTML output.
		$image_args = 'width=' . $args['width'] . '&link=img&return=true&noheight=true';

		if ( isset( $woo_options['woo_slider_autoheight'] ) && $woo_options['woo_slider_autoheight'] != 'true' ) {
			 $html .= '<div id="' . $args['id'] . '"' . $slider_css . ' style="max-height:' . $args['height'] . 'px;">' . "\n";
			 $image_args .= '&height=' . $args['height'];
	    } else {
			 $html .= '<div id="' . $args['id'] . '"' . $slider_css . ' style="height:auto;">' . "\n";
		}

	$saved = $wp_query; $query = new WP_Query( array( 'tag__in' => $tag_array, 'posts_per_page' => $args['posts_per_page'] ) );

	if ( $query->have_posts() ) : $count = 0;

	     if ( isset( $woo_options['woo_slider_autoheight'] ) && $woo_options['woo_slider_autoheight'] != 'true' )
	         $html .= '<ul class="slides" style="max-height:' . esc_attr( $args['height'] ) . 'px;">' . "\n";
	     else
	         $html .= '<ul class="slides">' . "\n";

	        while ( $query->have_posts() ) : $query->the_post(); global $post; $shownposts[$count] = $post->ID; $count++;

	           $styles = 'width: ' . $args['width'] . 'px;';
				if ( $count >= 2 ) { $styles .= ' display:none;'; } else { $styles = ''; }

				$url = get_permalink( $post->ID );

	            $html .= '<li id="slide-' . esc_attr( $post->ID ) . '" class="slide slide-number-' . esc_attr( $count ) . '" style="' . $styles . '">' . "\n";
					$html .= '<a href="' . $url . '" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">' . woo_image( $image_args ) . '</a>' . "\n";
	                $html .= '<div class="content">' . "\n";
	                if ( $woo_options['woo_slider_magazine_title'] == 'true' ) {
	                	$html .= '<h2 class="title"><a href="' . $url . '" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">' . get_the_title( $post->ID ) . '</a></h2>'; }

	                if ( $woo_options['woo_slider_magazine_excerpt'] == 'true' ) {
	                	$html .= '<div class="excerpt"><p>' . woo_text_trim( get_the_excerpt(), $excerpt_length ) . '</p></div>' . "\n";
	                }

	                $html .= '</div>' . "\n";

	            $html .= '</li>' . "\n";

	       endwhile;
		endif; $wp_query = $saved;

	    $html .= '</ul><!-- /.slides -->' . "\n";
	    $html .= '</div>' . "\n";

	if ( isset( $woo_options['woo_slider_pagination'] ) && $woo_options['woo_slider_pagination'] == 'true' ) {
		$html .= '<div class="pagination-wrap slider-pagination"><ol class="flex-control-nav flex-control-paging">';
		for ( $i = 0; $i < $count; $i++ ) {
			$html .= '<li><a>' . ( $i + 1 ) . '</a></li>';
		}
		$html .= '</ol></div>';
	}

    	if ( get_option( 'woo_exclude' ) != $shownposts ) { update_option( "woo_exclude", $shownposts ); }

		if ( $args['echo'] ) {
			echo $html;
		}

		return $html;
	} // End woo_slider_magazine()
}

/*-----------------------------------------------------------------------------------*/
/* Woo Slider Business */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_slider_biz' ) ) {
	function woo_slider_biz( $args = null ) {

		global $woo_options, $post;

		// This is where our output will be added.
		$html = '';

		// Default slider settings.
		$defaults = array(
							'id' => 'loopedSlider',
							'echo' => true,
							'excerpt_length' => '15',
							'pagination' => false,
							'width' => '940',
							'height' => '350',
							'order' => 'ASC',
							'posts_per_page' => '5',
							'slide_page' => 'all',
							'use_slide_page' => false
						 );

		// Setup the "Slide Page", if one is set.
		if ( isset( $post->ID ) ) {
			$slide_page = 'all';
			$stored_slide_page = get_post_meta( $post->ID, '_slide-page', true );

			if ( $stored_slide_page != '' && $stored_slide_page != 'all' ) {
				$slide_page = $stored_slide_page;
				$defaults['use_slide_page'] = true; // Instruct the slider to apply the necessary conditional.
				$defaults['slide_page'] = $slide_page;
			}
		}

		// Setup height of slider.
		$height = $woo_options['woo_slider_biz_height'];
		if ( $height != '' ) { $defaults['height'] = $height; }

		// Setup width of slider and images.
		$layout = $woo_options['woo_layout'];
		if ( !$layout )
			$layout = get_option( 'woo_layout' );
		$layout_width = get_option('woo_layout_width');

		// Setup the number of posts to show.
		$posts_per_page = $woo_options['woo_slider_biz_number'];
		if ( $posts_per_page != '' ) { $defaults['posts_per_page'] = $posts_per_page; }

		// Setup the order of posts.
		$post_order = $woo_options['woo_slider_biz_order'];
		if ( $post_order != '' ) { $defaults['order'] = $post_order; }

		// Setup the excerpt length.
		if ( isset($woo_options['woo_slider_biz_excerpt_length']) ) {
			$excerpt_length = $woo_options['woo_slider_biz_excerpt_length'];
			if ( $excerpt_length != '' ) { $defaults['excerpt_length'] = $excerpt_length; }
		}

		$width = intval( $layout_width );

		if ( $width > 0 && $args['width'] == '' ) { $defaults['width'] = $width; }

		// Merge the arguments with defaults.
		$args = wp_parse_args( $args, $defaults );

		if ( ( ( isset($args['width']) ) && ( ( $args['width'] <= 0 ) || ( $args['width'] == '')  ) ) || ( !isset($args['width']) ) ) {	$args['width'] = '100'; }
		if ( ( isset($args['height']) ) && ( $args['height'] <= 0 )  ) { $args['height'] = '100'; }

		// Allow child themes/plugins to filter these arguments.
		$args = apply_filters( 'woo_biz_slider_args', $args );

		// Setup slider page id's
		$query_args = array(
							'post_type' => 'slide',
							'order' => $args['order'],
							'orderby' => 'date',
							'posts_per_page' => $args['posts_per_page']
						);

		if ( $args['use_slide_page'] == true ) {

			$query_args['tax_query'] = array( array(
								'taxonomy' => 'slide-page',
								'field' => 'slug',
								'terms' => $args['slide_page']
							) );

		}

		$slide_query = new WP_Query( $query_args );

		if ( ( ! $slide_query->have_posts() ) ) {
			echo '<p class="note">' . __( 'Please add some slider posts using the Slide custom post type.', 'woothemes' ) . '</p>';
			return;
		}

		if ( ( $slide_query->found_posts < 1 ) ) {
			echo '<p class="note">' . __( 'Please note that this slider requires 2 or more slides in order to function. Please assign another slide.', 'woothemes' ) . '</p>';
			return;
		}

		// Setup the slider CSS class.
		$slider_css = '';

		if ( isset( $woo_options['woo_slider_pagination'] ) && $woo_options['woo_slider_pagination'] == 'true' ) {
			$slider_css = ' class="has-pagination woo-slideshow"';
		} else {
			$slider_css = ' class="woo-slideshow"';
		}

	// Begin setting up HTML output.

	if ( isset( $woo_options['woo_slider_autoheight'] ) && $woo_options['woo_slider_autoheight'] != 'true' ) {
		 $html .= '<div id="' . $args['id'] . '"' . $slider_css . ' style="height:' . $args['height'] . 'px;">' . "\n";
	     // $html .= '<div class="container" style="height:' . $args['height'] . 'px">' . "\n";
    } else {
		 $html .= '<div id="' . $args['id'] . '"' . $slider_css . ' style="height:auto;">' . "\n";
	     // $html .= '<div class="container" style="height:auto;">' . "\n";
	}

	  	global $post;

	     if ( isset( $woo_options['woo_slider_autoheight'] ) && $woo_options['woo_slider_autoheight'] != 'true' )
	         $html .= '<ul class="slides" style="height:' . $args['height'] . 'px;">' . "\n";
	     else
	         $html .= '<ul class="slides">' . "\n";

	       	 if ( $slide_query->have_posts() ) { $count = 0; while ( $slide_query->have_posts() ) { $slide_query->the_post(); $count++;

				$styles = 'width: ' . $args['width'] . 'px;';
				if ( $count >= 2 ) { $styles .= ' display:none;'; } else { $styles = ''; }

	            $html .= '<li id="slide-' . esc_attr( $post->ID ) . '" class="slide slide-number-' . esc_attr( $count ) . '" style="' . $styles . '">' . "\n";

				$type = woo_image('return=true');

				if ( $type ) {
					$url = get_post_meta( $post->ID, 'url', true );

					if ( $url ) {
						$html .= '<a href="' . $url . '" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">' . woo_image( 'width=' . $args['width'] . '&height=' . $args['height'] . '&link=img&return=true&noheight=true' ) . '</a>' . "\n";
					} else {
						$html .= woo_image( 'width=' . $args['width'] . '&height=' . $args['height'] . '&link=img&return=true&noheight=true' );
					}

					$html .= '<div class="content">' . "\n";

	                if ( $woo_options['woo_slider_biz_title'] == 'true' ) {
	                	if ( $url ) {
		                	$html .= '<div class="title"><h2 class="title"><a href="' . $url . '">' . get_the_title( $post->ID ) . '</a></h2></div>' . "\n";
	                	} else {
	                		$html .= '<div class="title"><h2 class="title">' . get_the_title( $post->ID ) . '</h2></div>' . "\n";

	                	}
	                }
	                	$content = get_the_excerpt();
						$content = do_shortcode( $content );

	                	$html .= '<div class="excerpt">' . wpautop( $content ) . '</div>' . "\n";
	                    $html .= '</div>' . "\n";

	                } else {

	                	$content = get_the_content( $post->ID );
						$content = do_shortcode( $content );

	                	$html .= '<section class="entry">' . wpautop( $content ) . '</section>' . "\n";

	               }

	            $html .= '</li>' . "\n";

	        } // End WHILE Loop

	       } // End IF Statement

	        $html .= '</ul><!-- /.slides -->' . "\n";
	   //$html .= '</div><!-- /.container --> ' . "\n";
	$html .= '</div><!-- /#' . $args['id'] . ' -->' . "\n";
	if ( isset( $woo_options['woo_slider_pagination'] ) && $woo_options['woo_slider_pagination'] == 'true' ) {
		$html .= '<div class="pagination-wrap slider-pagination"><ol class="flex-control-nav flex-control-paging">';
		for ( $i = 0; $i < $count; $i++ ) {
			$html .= '<li><a>' . ( $i + 1 ) . '</a></li>';
		}
		$html .= '</ol></div>';
	}

		if ( $args['echo'] ) { echo $html; }

		return $html;
	} // End woo_slider_biz()
}

/*-----------------------------------------------------------------------------------*/
/* Navigation */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_nav' ) ) {
function woo_nav() {
	global $woo_options;
	woo_nav_before();
?>
<nav id="navigation" class="col-full" role="navigation">

	<section class="menus">

	<?php woo_nav_inside(); ?>

	<a href="<?php echo home_url(); ?>" class="nav-home"><span><?php _e( 'Home', 'woothemes' ); ?></span></a>

	<?php
	if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'primary-menu' ) ) {
		echo '<h3>' . woo_get_menu_name( 'primary-menu' ) . '</h3>';
		wp_nav_menu( array( 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'main-nav', 'menu_class' => 'nav fl', 'theme_location' => 'primary-menu' ) );
	} else {
	?>
		<ul id="main-nav" class="nav fl">
			<?php
			if ( get_option( 'woo_custom_nav_menu' ) == 'true' ) {
				if ( function_exists( 'woo_custom_navigation_output' ) ) { woo_custom_navigation_output( 'name=Woo Menu 1' ); }
			} else { ?>

				<?php if ( is_page() ) { $highlight = 'page_item'; } else { $highlight = 'page_item current_page_item'; } ?>
				<li class="<?php echo esc_attr( $highlight ); ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'woothemes' ); ?></a></li>
				<?php wp_list_pages( 'sort_column=menu_order&depth=6&title_li=&exclude=' ); ?>
			<?php } ?>
		</ul><!-- /#nav -->
	<?php } ?>
	</section><!-- /.menus -->

	<a href="#top" class="nav-close"><span><?php _e('Return to Content', 'woothemes' ); ?></span></a>

</nav>
<?php
	woo_nav_after();
} // End woo_nav()
}

/*-----------------------------------------------------------------------------------*/
/* Add subscription links to the navigation bar */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_nav_subscribe' ) ) {
function woo_nav_subscribe() {
	global $woo_options;
	$class = '';
	if ( isset( $woo_options['woo_header_cart_link'] ) && 'true' == $woo_options['woo_header_cart_link'] )
		$class = ' cart-enabled';

	if ( ( isset( $woo_options['woo_nav_rss'] ) ) && ( $woo_options['woo_nav_rss'] == 'true' ) || ( isset( $woo_options['woo_subscribe_email'] ) ) && ( $woo_options['woo_subscribe_email'] ) ) { ?>
	<ul class="rss fr<?php echo $class; ?>">
		<?php if ( ( isset( $woo_options['woo_subscribe_email'] ) ) && ( $woo_options['woo_subscribe_email'] ) ) { ?>
		<li class="sub-email"><a href="<?php echo esc_url( $woo_options['woo_subscribe_email'] ); ?>" target="_blank"></a></li>
		<?php } ?>
		<?php if ( isset( $woo_options['woo_nav_rss'] ) && ( $woo_options['woo_nav_rss'] == 'true' ) ) { ?>
		<li class="sub-rss"><a href="<?php if ( $woo_options['woo_feed_url'] ) { echo esc_url( $woo_options['woo_feed_url'] ); } else { echo esc_url( get_bloginfo_rss( 'rss2_url' ) ); } ?>"></a></li>
		<?php } ?>
	</ul>
	<?php }
} // End woo_nav_subscribe()
}

/*-----------------------------------------------------------------------------------*/
/* Post More  */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_post_more' ) ) {
function woo_post_more() {
	if ( get_option( 'woo_disable_post_more' ) != 'true' ) {

	$html = '';

	if ( get_option('woo_post_content') == 'excerpt' ) { $html .= '[view_full_article] '; }
	$html .= '[post_comments]';

	$html = apply_filters( 'woo_post_more', $html );

		if ( $html != '' ) {
?>
	<div class="post-more">
		<?php
			echo $html;
		?>
	</div>
<?php
		}
	}
} // End woo_post_more()
}

/*-----------------------------------------------------------------------------------*/
/* Video Embed  */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'canvas_get_embed' ) ) {
function canvas_get_embed() {
	// Setup height & width of embed
	$width = '610';
	$height = '343';
	$embed = woo_embed( 'width=' . $width . '&height=' . $height );
	if ( '' != $embed ) {
?>
<div class="post-embed">
	<?php echo $embed; ?>
</div><!-- /.post-embed -->
<?php
	}
} // End canvas_get_embed()
}


/*-----------------------------------------------------------------------------------*/
/* Author Box */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_author' ) ) {
function woo_author () {
	// Author box single post page
	if ( is_single() && get_option( 'woo_disable_post_author' ) != 'true' ) { add_action( 'woo_post_inside_after', 'woo_author_box', 10 ); }
	// Author box author page
	if ( is_author() ) { add_action( 'woo_loop_before', 'woo_author_box', 10 ); }
} // End woo_author()
}


/*-----------------------------------------------------------------------------------*/
/* Single Post Author */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_author_box' ) ) {
function woo_author_box () {
	global $post;
	$author_id=$post->post_author;
?>
<aside id="post-author">
	<div class="profile-image"><?php echo get_avatar( $author_id, '80' ); ?></div>
	<div class="profile-content">
		<h4><?php printf( esc_attr__( 'About %s', 'woothemes' ), get_the_author_meta( 'display_name', $author_id ) ); ?></h4>
		<?php echo get_the_author_meta( 'description', $author_id ); ?>
		<?php if ( is_singular() ) { ?>
		<div class="profile-link">
			<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID', $author_id ) ) ); ?>">
				<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'woothemes' ), get_the_author_meta( 'display_name', $author_id ) ); ?>
			</a>
		</div><!--#profile-link-->
		<?php } ?>
	</div>
	<div class="fix"></div>
</aside>
<?php
} // End woo_author_box()
}


/*-----------------------------------------------------------------------------------*/
/* Yoast Breadcrumbs */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( '_dep_woo_breadcrumbs' ) ) {
function _dep_woo_breadcrumbs() {
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<div id="breadcrumb"><p>', '</p></div>' );
	}
} // End _dep_woo_breadcrumbs()
}


/*-----------------------------------------------------------------------------------*/
/* Subscribe & Connect  */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_subscribe_connect_action' ) ) {
function woo_subscribe_connect_action() {
	if ( is_single() && 'true' == get_option( 'woo_connect' ) ) { add_action('woo_post_inside_after', 'woo_subscribe_connect'); }
} // End woo_subscribe_connect_action()
}


/*-----------------------------------------------------------------------------------*/
/* Optional Top Navigation (WP Menus)  */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_top_navigation' ) ) {
function woo_top_navigation() {
	if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'top-menu' ) ) {
?>
	<div id="top">
		<div class="col-full">
			<?php
				echo '<h3 class="top-menu">' . woo_get_menu_name( 'top-menu' ) . '</h3>';
				wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'top-nav', 'menu_class' => 'nav top-navigation fl', 'theme_location' => 'top-menu' ) );
			?>
		</div>
	</div><!-- /#top -->
<?php
	}
} // End woo_top_navigation()
}

/*-----------------------------------------------------------------------------------*/
/* Footer Widgetized Areas  */
/*-----------------------------------------------------------------------------------*/

add_action( 'woo_footer_top', 'woo_footer_sidebars', 30 );

if ( ! function_exists( 'woo_footer_sidebars' ) ) {
function woo_footer_sidebars() {
	$settings = woo_get_dynamic_values( array( 'biz_disable_footer_widgets' => '', 'footer_sidebars' => '4' ) );

	$footer_sidebar_total = 4;
	$has_footer_sidebars = false;

	// Check if we have footer sidebars to display.
	for ( $i = 1; $i <= $footer_sidebar_total; $i++ ) {
		if ( woo_active_sidebar( 'footer-' . $i ) && ( $has_footer_sidebars == false ) ) {
			$has_footer_sidebars = true;
		}
	}

	// If footer sidebars are available, we're on the "Business" page template and we want to disable them, do so.
	if ( $has_footer_sidebars && is_page_template( 'template-biz.php' ) && ( 'true' == $settings['biz_disable_footer_widgets'] ) ) {
		$has_footer_sidebars = false;
	}

	$total = $settings['footer_sidebars'];
	if ( '0' == $settings['footer_sidebars'] ) { $total = 0; } // Make sure the footer widgets don't display if the "none" option is set under "Theme Options".

	// Lastly, we display the sidebars.
	if ( $has_footer_sidebars &&  $total > 0 ) {
?>
<section id="footer-widgets" class="col-full col-<?php echo esc_attr( intval( $total ) ); ?>">
	<?php $i = 0; while ( $i < intval( $total ) ) { $i++; ?>
		<?php if ( woo_active_sidebar( 'footer-' . $i ) ) { ?>
	<div class="block footer-widget-<?php echo $i; ?>">
    	<?php woo_sidebar( 'footer-' . $i ); ?>
	</div>
        <?php } ?>
	<?php } // End WHILE Loop ?>
	<div class="fix"></div>
</section><!--/#footer-widgets-->
<?php

	} // End IF Statement
} // End woo_footer_sidebars()
}

/*-----------------------------------------------------------------------------------*/
/* Add customisable footer areas */
/*-----------------------------------------------------------------------------------*/

/**
 * Add customisable footer areas.
 *
 * @package WooFramework
 * @subpackage Actions
 */

if ( ! function_exists( 'woo_footer_left' ) ) {
function woo_footer_left () {
	$settings = woo_get_dynamic_values( array( 'footer_left' => 'true', 'footer_left_text' => '[site_copyright]' ) );

	woo_do_atomic( 'woo_footer_left_before' );

	$html = '';

	if( 'true' == $settings['footer_left'] ) {
		$html .= '<p>' . stripslashes( $settings['footer_left_text'] ) . '</p>';
	} else {
		$html .= '[site_copyright]';
	}

	$html = apply_filters( 'woo_footer_left', $html );

	echo $html;

	woo_do_atomic( 'woo_footer_left_after' );
} // End woo_footer_left()
}

if ( ! function_exists( 'woo_footer_right' ) ) {
function woo_footer_right () {
	$settings = woo_get_dynamic_values( array( 'footer_right' => 'true', 'footer_right_text' => '[site_credit]' ) );

	woo_do_atomic( 'woo_footer_right_before' );

	$html = '';

	if( 'true' == $settings['footer_right'] ) {
		$html .= '<p>' . stripslashes( $settings['footer_right_text'] ) . '</p>';
	} else {
		$html .= '[site_credit]';
	}

	$html = apply_filters( 'woo_footer_right', $html );

	echo $html;

	woo_do_atomic( 'woo_footer_right_after' );
} // End woo_footer_right()
}

/*-----------------------------------------------------------------------------------*/
/* Add customisable post meta */
/*-----------------------------------------------------------------------------------*/

/**
 * Add customisable post meta.
 *
 * Add customisable post meta, using shortcodes,
 * to be added/modified where necessary.
 *
 * @package WooFramework
 * @subpackage Actions
 */

if ( ! function_exists( 'woo_post_meta' ) ) {
function woo_post_meta() {
	if ( is_page() ) { return; }

	$post_info = '<span class="small">' . __( 'By', 'woothemes' ) . '</span> [post_author_posts_link] <span class="small">' . _x( 'on', 'post datetime', 'woothemes' ) . '</span> [post_date] <span class="small">' . __( 'in', 'woothemes' ) . '</span> [post_categories before=""] ';
printf( '<div class="post-meta">%s</div>' . "\n", apply_filters( 'woo_filter_post_meta', $post_info ) );

} // End woo_post_meta()
}

/*-----------------------------------------------------------------------------------*/
/* Add Post Thumbnail to Single posts on Archives */
/*-----------------------------------------------------------------------------------*/

/**
 * Add Post Thumbnail to Single posts on Archives
 *
 * Add code to the woo_post_inside_before() hook.
 *
 * @package WooFramework
 * @subpackage Actions
 */

 add_action( 'woo_post_inside_before', 'woo_display_post_image', 10 );

if ( ! function_exists( 'woo_display_post_image' ) ) {
function woo_display_post_image() {
	global $woo_options;

	$display_image = false;

	$width = $woo_options['woo_thumb_w'];
	$height = $woo_options['woo_thumb_h'];
	$align = $woo_options['woo_thumb_align'];

	if ( is_single() && isset( $woo_options['woo_thumb_single'] ) && ( $woo_options['woo_thumb_single'] == 'true' ) ) {
		$width = $woo_options['woo_single_w'];
		$height = $woo_options['woo_single_h'];
		$align = $woo_options['woo_thumb_align_single'];
		$display_image = true;
	}

	if ( get_option('woo_woo_tumblog_switch') == 'true' ) { $is_tumblog = woo_tumblog_test(); } else { $is_tumblog = false; }
	if ( $is_tumblog || ( is_single() && @$woo_options['woo_thumb_single'] == 'false' ) ) { $display_image = false; }
	if ( $display_image == true && ! woo_embed( '' ) ) { woo_image( 'width=' . $width . '&height=' . $height . '&class=thumbnail ' . $align ); }
} // End woo_display_post_image()
}

/*-----------------------------------------------------------------------------------*/
/* Post Inside After */
/*-----------------------------------------------------------------------------------*/
/**
 * Post Inside After
 *
 * Add code to the woo_post_inside_after() hook.
 *
 * @package WooFramework
 * @subpackage Actions
 */

 add_action( 'woo_post_inside_after_singular-post', 'woo_post_inside_after_default', 10 );

if ( ! function_exists( 'woo_post_inside_after_default' ) ) {
function woo_post_inside_after_default() {

	$post_info ='[post_tags before=""]';
	printf( '<div class="post-utility">%s</div>' . "\n", apply_filters( 'woo_post_inside_after_default', $post_info ) );

} // End woo_post_inside_after_default()
}

/*-----------------------------------------------------------------------------------*/
/* Modify the default "comment" form field. */
/*-----------------------------------------------------------------------------------*/
/**
 * Modify the default "comment" form field.
 *
 * @package WooFramework
 * @subpackage Filters
 */

  add_filter( 'comment_form_field_comment', 'woo_comment_form_comment', 10 );

if ( ! function_exists( 'woo_comment_form_comment' ) ) {
function woo_comment_form_comment ( $field ) {
	$field = str_replace( '<label ', '<label class="hide" ', $field );
	$field = str_replace( 'cols="45"', 'cols="50"', $field );
	$field = str_replace( 'rows="8"', 'rows="10"', $field );

	return $field;
} // End woo_comment_form_comment()
}

/*-----------------------------------------------------------------------------------*/
/* Add theme default comment form fields. */
/*-----------------------------------------------------------------------------------*/
/**
 * Add theme default comment form fields.
 *
 * @package WooFramework
 * @subpackage Filters
 */

add_filter( 'comment_form_default_fields', 'woo_comment_form_fields', 10 );

if ( ! function_exists( 'woo_comment_form_fields' ) ) {
function woo_comment_form_fields ( $fields ) {
	$commenter = wp_get_current_commenter();

$req = get_option( 'require_name_email' );
$aria_req = ( $req ? " aria-required='true'" : '' );

	$fields =  array(
	'author' => '<p class="comment-form-author"><input id="author" name="author" type="text" class="txt" tabindex="1" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />' .
				'<label for="author">' . __( 'Name', 'woothemes' ) . ( $req ? ' <span class="required">(' . __( 'required', 'woothemes' ) . ')</span>' : '' ) . '</label> ' . '</p>',
	'email'  => '<p class="comment-form-email"><input id="email" name="email" type="text" class="txt" tabindex="2" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />' .
				'<label for="email">' . __( 'Email (will not be published)', 'woothemes' ) . ( $req ? ' <span class="required">(' . __( 'required', 'woothemes' ) . ')</span>' : '' ) . '</label> ' . '</p>',
	'url'    => '<p class="comment-form-url"><input id="url" name="url" type="text" class="txt" tabindex="3" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />' .
	            '<label for="url">' . __( 'Website', 'woothemes' ) . '</label></p>',
);

	return $fields;
} // End woo_comment_form_fields()
}

/*-----------------------------------------------------------------------------------*/
/* Add theme default comment form arguments. */
/*-----------------------------------------------------------------------------------*/
/**
 * Add theme default comment form arguments.
 *
 * @package WooFramework
 * @subpackage Filters
 */

add_filter( 'comment_form_defaults', 'woo_comment_form_args', 10 );

if ( ! function_exists( 'woo_comment_form_args' ) ) {
	function woo_comment_form_args ( $args ) {
		// Add tabindex of "field count + 1" to the comment textarea. This lets us cater for additional fields and have a dynamic tab index.
		$tabindex = count( $args['fields'] ) + 1;
		$args['comment_field'] = str_replace( '<textarea ', '<textarea tabindex="' . $tabindex . '" ', $args['comment_field'] );

		// Adjust tabindex for "submit" button.
		$tabindex++;

		$args['label_submit'] = __( 'Submit Comment', 'woothemes' );
		$args['comment_notes_before'] = '';
		$args['comment_notes_after'] = '';
		$args['cancel_reply_link'] = __( 'Click here to cancel reply.', 'woothemes' );

		return $args;
	} // End woo_comment_form_args()
}

/*-----------------------------------------------------------------------------------*/
/* Activate shortcode compatibility in our new custom areas. */
/*-----------------------------------------------------------------------------------*/
/**
 * Activate shortcode compatibility in our new custom areas.
 *
 * @package WooFramework
 * @subpackage Filters
 */
 	$sections = array( 'woo_filter_post_meta', 'woo_post_inside_after_default', 'woo_post_more', 'woo_footer_left', 'woo_footer_right' );

 	foreach ( $sections as $s ) { add_filter( $s, 'do_shortcode', 20 ); }

/*-----------------------------------------------------------------------------------*/
/* woo_content_templates_magazine() */
/*-----------------------------------------------------------------------------------*/
/**
 * woo_content_templates_magazine()
 *
 * Remove the tumblog content template from the templates
 * to search through, if on the "Magazine" page template.
 *
 * @package WooFramework
 * @subpackage Filters
 */

add_filter( 'woo_content_templates', 'woo_content_templates_magazine', 10 );

if ( ! function_exists( 'woo_content_templates_magazine' ) ) {
	function woo_content_templates_magazine ( $templates ) {
		global $page_template;

		if ( $page_template == 'template-magazine.php' ) {
			foreach ( $templates as $k => $v ) {
				$v = str_replace( '.php', '', $v );
				$bits = explode( '-', $v );
				if ( $bits[1] == 'tumblog' ) {
					unset( $templates[$k] );
				}
			}
		}

		return $templates;
	} // End woo_content_templates_magazine()
}

/*-----------------------------------------------------------------------------------*/
/* woo_feedburner_link() */
/*-----------------------------------------------------------------------------------*/
/**
 * woo_feedburner_link()
 *
 * Replace the default RSS feed link with the Feedburner URL, if one
 * has been provided by the user.
 *
 * @package WooFramework
 * @subpackage Filters
 */

add_filter( 'feed_link', 'woo_feedburner_link', 10 );

if ( ! function_exists( 'woo_feedburner_link' ) ) {
function woo_feedburner_link ( $output, $feed = null ) {
	global $woo_options;

	$default = get_default_feed();

	if ( ! $feed ) $feed = $default;

	if ( isset( $woo_options['woo_feed_url'] ) && $woo_options['woo_feed_url'] && ( $feed == $default ) && ( ! stristr( $output, 'comments' ) ) ) $output = $woo_options['woo_feed_url'];

	return esc_url( $output );
} // End woo_feedburner_link()
}

/*-----------------------------------------------------------------------------------*/
/* Help WooTumblog to recognise if it's on the "Magazine" page template */
/*-----------------------------------------------------------------------------------*/

add_action( 'get_template_part_content', 'woo_magazine_adjust_tumblog_widths', 2, 10 );

/**
 * woo_magazine_adjust_tumblog_widths function.
 *
 * @access public
 * @param string $slug
 * @param string $name
 * @return void
 */
if ( ! function_exists( 'woo_magazine_adjust_tumblog_widths' ) ) {
function woo_magazine_adjust_tumblog_widths ( $slug, $name ) {
	if ( $name == 'magazine-grid' ) {
		woo_magazine_apply_tumblog_width_adjustments();
	}
} // End woo_magazine_adjust_tumblog_widths()
}

/**
 * woo_magazine_apply_tumblog_width_adjustments function.
 *
 * @access public
 * @return void
 */
if ( ! function_exists( 'woo_magazine_apply_tumblog_width_adjustments' ) ) {
function woo_magazine_apply_tumblog_width_adjustments () {
	add_filter( 'option_woo_tumblog_image_width', 'woo_magazine_tumblog_adjust_width_grid', 10 );
	add_filter( 'option_woo_tumblog_video_width', 'woo_magazine_tumblog_adjust_width_grid', 10 );
	add_filter( 'option_woo_tumblog_audio_width', 'woo_magazine_tumblog_adjust_width_grid', 10 );
} // End woo_magazine_apply_tumblog_width_adjustments()
}

/**
 * woo_magazine_tumblog_adjust_width_grid function.
 *
 * @access public
 * @param string $width
 * @return int $width
 */
if ( ! function_exists( 'woo_magazine_tumblog_adjust_width_grid' ) ) {
function woo_magazine_tumblog_adjust_width_grid ( $width ) {
	return woo_magazine_determine_tumblog_width( current_filter() );
} // End woo_magazine_tumblog_adjust_width_grid()
}

/**
 * woo_magazine_determine_tumblog_width function.
 *
 * @access public
 * @param string $filter
 * @return int $width
 */
if ( ! function_exists( 'woo_magazine_determine_tumblog_width' ) ) {
function woo_magazine_determine_tumblog_width ( $filter ) {
	global $woo_options;
	$width = 300;

	if ( isset( $woo_options['woo_tumblog_magazine_media_width'] ) && ( $woo_options['woo_tumblog_magazine_media_width'] != '' ) ) {
		$width = $woo_options['woo_tumblog_magazine_media_width'];
	}

	return apply_filters( 'woo_magazine_tumblog_width', $width, $filter );
} // End woo_magazine_determine_tumblog_width()
}

/*-----------------------------------------------------------------------------------*/
/* Enqueue dynamic CSS */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_enqueue_custom_styling' ) ) {
function woo_enqueue_custom_styling () {
	echo "\n" . '<!-- Custom CSS Styling -->' . "\n";
	echo '<style type="text/css">' . "\n";
	woo_custom_styling();
	echo '</style>' . "\n";
} // End woo_enqueue_custom_styling()
}

/*-----------------------------------------------------------------------------------*/
/* Load site width CSS in the header */
/*-----------------------------------------------------------------------------------*/

add_action( 'wp_head', 'woo_load_site_width_css', 10 );

if ( ! function_exists( 'woo_load_site_width_css' ) ) {
function woo_load_site_width_css () {
	$settings = woo_get_dynamic_values( array( 'layout_width' => 940 ) );
    $layout_width = intval( $settings['layout_width'] );
    if ( 0 < $layout_width ) { /* Has legitimate width */ } else { $layout_width = 940; } // Default Width
?>

<!-- Adjust the website width -->
<style type="text/css">
	.col-full, #wrapper { max-width: <?php echo intval( $layout_width ); ?>px !important; }
</style>
<?php
} // End woo_load_site_width_css()
}

/*-----------------------------------------------------------------------------------*/
/* Function to optionally remove responsive design and load in fallback CSS styling. */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_remove_responsive_design' ) ) {
/**
 * Trigger items for removing responsive design from Canvas.
 * @since  5.0.13
 * @return void
 */
function woo_remove_responsive_design () {
	remove_action( 'wp_head', 'woo_load_site_width_css', 10 );
	// Load in CSS file for non-responsive layouts.
	wp_enqueue_style( 'non-responsive' );
	// Load non-responsive site width CSS.
	add_action( 'wp_head', 'woo_load_site_width_css_nomedia' );
	// Remove mobile viewport scale meta tag
	remove_action( 'wp_head', 'woo_load_responsive_meta_tags', 10 );
} // End woo_remove_responsive_design()
}

if ( ! function_exists( 'woo_load_site_width_css_nomedia' ) ) {
/**
 * Load the layout width CSS without a media query wrapping it.
 * @since  5.0.13
 * @return void
 */
function woo_load_site_width_css_nomedia () {
	$settings = woo_get_dynamic_values( array( 'layout_width' => 940 ) );
    $layout_width = intval( $settings['layout_width'] );
    if ( 0 < $layout_width ) { /* Has legitimate width */ } else { $layout_width = 940; } // Default Width
?>
<style type="text/css">
.col-full, #wrapper { width: <?php echo intval( $layout_width ); ?>px; max-width: <?php echo intval( $layout_width ); ?>px; }
</style>
<?php
} // End woo_load_site_width_css_nomedia()
}

if ( ! function_exists( 'woo_load_responsive_design_removal' ) ) {
/**
 * Trigger the removal of the responsive design in Canvas. Must be hooked onto "init".
 * @since  5.0.13
 * @uses  woo_remove_responsive_design()
 * @return void
 */
function woo_load_responsive_design_removal () {
	add_action( 'wp_print_styles', 'woo_remove_responsive_design' );
} // End woo_load_responsive_design_removal()
}

/*-----------------------------------------------------------------------------------*/
/* Load non-responsive.css for IE8 */
/*-----------------------------------------------------------------------------------*/

add_action( 'wp_head', 'woo_load_non_responsive_css', 10 );

if ( ! function_exists( 'woo_load_non_responsive_css' ) ) {
	function woo_load_non_responsive_css() {
		// Load conditional CSS for IE8
		echo "\n" . '<!--[if lt IE 9]>'. "\n";
		echo '<link href="'. esc_url( get_template_directory_uri() . '/css/non-responsive.css' ) . '" rel="stylesheet" type="text/css" />' . "\n";
		// Load the site width in addition to max-width to make it fixed
		woo_load_site_width_css_nomedia();
		echo '<![endif]-->'. "\n";
	}
}

/*-----------------------------------------------------------------------------------*/
/* Adjust the homepage query, if using the "Magazine" page template as the homepage. */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_modify_magazine_homepage_query' ) ) {
function woo_modify_magazine_homepage_query ( $q ) {
	if ( ! is_admin() && $q->is_main_query() && ( 0 < $q->query_vars['page_id'] ) && ( $q->query_vars['page_id'] == get_option( 'page_on_front' ) ) && ( 'template-magazine.php' == get_post_meta( intval( $q->query_vars['page_id'] ), '_wp_page_template', true ) ) ) {

		$settings = woo_get_dynamic_values( array( 'magazine_limit' => get_option( 'posts_per_page' ) ) );

		$q->set( 'posts_per_page', intval( $settings['magazine_limit'] ) );
		$q->set( 'paged', intval( $q->query_vars['page'] ) );

		$q->parse_query();
	}
	return $q;
} // End woo_modify_magazine_homepage_query()
}

add_filter( 'pre_get_posts', 'woo_modify_magazine_homepage_query' );

/*-----------------------------------------------------------------------------------*/
/* WooTumblog Loader. */
/*-----------------------------------------------------------------------------------*/

if ( get_option( 'woo_woo_tumblog_switch' ) == 'true' ) {
	$includes_path = get_template_directory() . '/includes/';
	define( 'WOOTUMBLOG_ACTIVE', true ); // Define a constant for use in our theme's templating engine.
	require_once ( $includes_path . 'tumblog/theme-tumblog.php' );		// Tumblog Output Functions
	// Test for Post Formats
	if ( get_option( 'woo_tumblog_content_method' ) == 'post_format' ) {
		require_once( $includes_path . 'tumblog/wootumblog_postformat.class.php' );
	} else {
		require_once ( $includes_path . 'tumblog/theme-custom-post-types.php' );	// Custom Post Types and Taxonomies
	}

	// Test for Post Formats
	if ( get_option( 'woo_tumblog_content_method' ) == 'post_format' ) {
	    global $woo_tumblog_post_format;
	    $woo_tumblog_post_format = new WooTumblogPostFormat();
	    if ( $woo_tumblog_post_format->woo_tumblog_upgrade_existing_taxonomy_posts_to_post_formats()) {
	    	update_option( 'woo_tumblog_post_formats_upgraded', 'true' );
	    }
	}

	// Show in RSS feed
	if ( get_option( 'woo_custom_rss' ) == 'true' ) {
		add_filter( 'the_excerpt_rss', 'woo_custom_tumblog_rss_output' );
		add_filter( 'the_content_rss', 'woo_custom_tumblog_rss_output' );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Full width header */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_full_width_header' ) ) {
function woo_full_width_header() {
	$settings = woo_get_dynamic_values( array( 'header_full_width' => '', 'layout_boxed' => '' ) );

	if ( 'true' == $settings['layout_boxed'] ) return;
	if ( 'true' != $settings['header_full_width'] ) return;


	// Add header container
	add_action( 'woo_header_before', 'woo_header_container_start' );
	add_action( 'woo_header_after', 'woo_header_container_end', 8 );

	// Add navigation container
	add_action( 'woo_nav_before', 'woo_nav_container_start' );
	add_action( 'woo_nav_after', 'woo_nav_container_end' );
} // End woo_full_width_header()
}

add_action( 'get_header', 'woo_full_width_header', 10 );

/*-----------------------------------------------------------------------------------*/
/* Full width footer */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_full_width_footer' ) ) {
function woo_full_width_footer() {
	$settings = woo_get_dynamic_values( array( 'footer_full_width' => '', 'layout_boxed' => '' ) );

	if ( 'true' == $settings['layout_boxed'] ) return;
	if ( 'true' != $settings['footer_full_width'] ) return;

	// Add footer widget container
	add_action( 'woo_footer_top', 'woo_footer_widgets_container_start', 8 );
	add_action( 'woo_footer_before', 'woo_footer_widgets_container_end' );

	// Add footer container
	add_action( 'woo_footer_before', 'woo_footer_container_start' );
	add_action( 'woo_footer_after', 'woo_footer_container_end' );
} // End woo_full_width_footer()
}

add_action( 'get_header', 'woo_full_width_footer', 10 );

/*-----------------------------------------------------------------------------------*/
/* Full Width Markup Functions */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_header_container_start' ) ) {
function woo_header_container_start () {
?>
    <!--#header-container-->
    <div id="header-container">
<?php
} // End woo_header_container_start()
}

if ( ! function_exists( 'woo_header_container_end' ) ) {
function woo_header_container_end () {
?>
    </div><!--/#header-container-->
<?php
} // End woo_header_container_end()
}

if ( ! function_exists( 'woo_nav_container_start' ) ) {
function woo_nav_container_start () {
?>
    <!--#nav-container-->
    <div id="nav-container">
<?php
} // End woo_nav_container_start()
}

if ( ! function_exists( 'woo_nav_container_end' ) ) {
function woo_nav_container_end () {
?>
    </div><!--/#nav-container-->
<?php
} // End woo_nav_container_end()
}

if ( ! function_exists( 'woo_footer_widgets_container_start' ) ) {
function woo_footer_widgets_container_start () {
?>
    <!--#footer-widgets-container-->
    <div id="footer-widgets-container">
<?php
} // End woo_footer_widgets_container_start()
}

if ( ! function_exists( 'woo_footer_widgets_container_end' ) ) {
function woo_footer_widgets_container_end () {
?>
	</div><!--/#footer_widgets_container_end-->
<?php
}
}

if ( ! function_exists( 'woo_footer_container_start' ) ) {
function woo_footer_container_start () { ?>
    <!--#footer_container_start-->
    <div id="footer-container">
<?php
} // End woo_footer_container_start()
}

if ( ! function_exists( 'woo_footer_container_end' ) ) {
function woo_footer_container_end () { ?>
    </div><!--/#footer_container_end-->
<?php
} // End woo_footer_container_end()
}

/*-----------------------------------------------------------------------------------*/
/* Full width body classes */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_add_full_width_class' ) ) {
function woo_add_full_width_class ( $classes ) {
	$settings = woo_get_dynamic_values( array( 'header_full_width' => 'false', 'footer_full_width' => 'false', 'layout_boxed' => '' ) );
	if ( 'true' == $settings['layout_boxed'] ) return $classes; // Don't add the full width CSS classes if boxed layout is enabled.

	if ( 'true' == $settings['header_full_width'] || 'true' == $settings['footer_full_width'] ) {
		$classes[] = 'full-width';

		if ( 'true' == $settings['header_full_width'] ) {
			$classes[] = 'full-header';
		}

		if ( 'true' == $settings['footer_full_width'] ) {
			$classes[] = 'full-footer';
		}
	}

	return $classes;
} // End woo_add_full_width_class()
}

add_filter( 'body_class', 'woo_add_full_width_class', 10 );


/*-----------------------------------------------------------------------------------*/
/* Optionally load custom logo. */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_logo' ) ) {
function woo_logo () {
	$settings = woo_get_dynamic_values( array( 'logo' => '' ) );
	// Setup the tag to be used for the header area (`h1` on the front page and `span` on all others).
	$heading_tag = 'span';
	if ( is_home() || is_front_page() ) { $heading_tag = 'h1'; }

	// Get our website's name, description and URL. We use them several times below so lets get them once.
	$site_title = get_bloginfo( 'name' );
	$site_url = home_url( '/' );
	$site_description = get_bloginfo( 'description' );
?>
<hgroup id="logo">
<?php
	// Website heading/logo and description text.
	if ( ( '' != $settings['logo'] ) ) {
		$logo_url = $settings['logo'];
		if ( is_ssl() ) $logo_url = str_replace( 'http://', 'https://', $logo_url );

		echo '<a href="' . esc_url( $site_url ) . '" title="' . esc_attr( $site_description ) . '"><img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $site_title ) . '" /></a>' . "\n";
	} // End IF Statement

	echo '<' . $heading_tag . ' class="site-title"><a href="' . esc_url( $site_url ) . '">' . $site_title . '</a></' . $heading_tag . '>' . "\n";
	if ( $site_description ) { echo '<span class="site-description">' . $site_description . '</span>' . "\n"; }
?>
</hgroup>
<?php
} // End woo_logo()
}

add_action( 'woo_header_inside', 'woo_logo', 10 );

/*-----------------------------------------------------------------------------------*/
/* Optionally load the mobile navigation toggle. */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_nav_toggle' ) ) {
function woo_nav_toggle () {
?>
<h3 class="nav-toggle icon"><a href="#navigation"><?php _e( 'Navigation', 'woothemes' ); ?></a></h3>
<?php
} // End woo_nav_toggle()
}

add_action( 'woo_header_inside', 'woo_nav_toggle', 20 );

/*-----------------------------------------------------------------------------------*/
/* Optionally load top ad section into the header. */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_top_ad' ) ) {
function woo_top_ad () {
	$settings = woo_get_dynamic_values( array( 'ad_top' => 'false', 'ad_top_adsense' => '', 'ad_top_image' => '', 'ad_top_url' => '' ) );
	if ( 'true' == $settings['ad_top'] ) {
?>
        <div id="topad">
			<?php
				if ( '' != $settings['ad_top_adsense'] ) {
					echo stripslashes( $settings['ad_top_adsense'] );
				} else {
					if ( '' != $settings['ad_top_url'] && '' != $settings['ad_top_image'] ) {
						$top_ad_image = $settings['ad_top_image'];
        				if ( is_ssl() ) $top_ad_image = str_replace( 'http://', 'https://', $top_ad_image );
			?>
				<a href="<?php echo esc_url( $settings['ad_top_url'] ); ?>"><img src="<?php echo esc_url( $top_ad_image ); ?>" /></a>
			<?php } } ?>
		</div><!-- /#topad -->
<?php
	}
} // End woo_top_ad()
}

add_action( 'woo_header_inside', 'woo_top_ad', 30 );

/*-----------------------------------------------------------------------------------*/
/* END */
/*-----------------------------------------------------------------------------------*/
?>