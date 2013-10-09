<?php

if (!function_exists('woo_options')) {
function woo_options() {

// THEME VARIABLES
$themename = "Canvas";
$themeslug = "canvas";

// STANDARD VARIABLES. DO NOT TOUCH!
$shortname = "woo";
$manualurl = 'http://www.woothemes.com/support/theme-documentation/'.$themeslug.'/';

//Access the WordPress Categories via an Array
$woo_categories = array();
$woo_categories_obj = get_categories('hide_empty=0');
foreach ($woo_categories_obj as $woo_cat) {
    $woo_categories[$woo_cat->cat_ID] = $woo_cat->cat_name;}
$categories_tmp = array_unshift($woo_categories, "Select a category:");

//Access the WordPress Pages via an Array
$woo_pages = array();
$woo_pages_obj = get_pages('sort_column=post_parent,menu_order');
foreach ($woo_pages_obj as $woo_page) {
    $woo_pages[$woo_page->ID] = $woo_page->post_name; }
$woo_pages_tmp = array_unshift($woo_pages, "Select a page:");

//Stylesheets Reader
$alt_stylesheet_path = get_template_directory() . '/styles/';
$alt_stylesheets = array();

if ( is_dir($alt_stylesheet_path) ) {
    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) {
        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
            if(stristr($alt_stylesheet_file, ".css") !== false) {
                $alt_stylesheets[] = $alt_stylesheet_file;
            }
        }
    }
}

//More Options
$options_pixels = array("0px","1px","2px","3px","4px","5px","6px","7px","8px","9px","10px","11px","12px","13px","14px","15px","16px","17px","18px","19px","20px");
$other_entries = array("Select a number:","0","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
$other_entries_2 = array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
$tumblog_options = array("Disabled","Before","After");
$options_image_link_to = array("image" => __( 'The Image', 'woothemes' ),"post" => __( 'The Post', 'woothemes' ) );

// Below are the various theme options.
/* General Settings */
$options = array();

$options[] = array( "name" => __( 'General Settings', 'woothemes' ),
					"icon" => "general",
                    "type" => "heading");

$options[] = array( 'name' => __( 'Quick Start', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'Custom Logo', 'woothemes' ),
					"desc" => __( 'Upload a logo for your theme, or specify an image URL directly.', 'woothemes' ),
					"id" => $shortname."_logo",
					"std" => "",
					"type" => "upload");

$options[] = array( "name" => __( 'Custom Favicon', 'woothemes' ),
					"desc" => __( 'Upload a 16px x 16px Png/Gif image that will represent your website\'s favicon.', 'woothemes' ),
					"id" => $shortname."_custom_favicon",
					"std" => "",
					"type" => "upload");

$options[] = array( "name" => __( 'Tracking Code', 'woothemes' ),
					"desc" => __( 'Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.', 'woothemes' ),
					"id" => $shortname."_google_analytics",
					"std" => "",
					"type" => "textarea");

$options[] = array( 'name' => __( 'Subscription Settings', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'RSS URL', 'woothemes' ),
					"desc" => __( 'Enter your preferred RSS URL. (Feedburner or other)', 'woothemes' ),
					"id" => $shortname."_feed_url",
					"std" => "",
					"type" => "text");

$options[] = array( 'name' => __( 'Display Options', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'Custom CSS', 'woothemes' ),
                    "desc" => __( 'Quickly add some CSS to your theme by adding it to this block.', 'woothemes' ),
                    "id" => $shortname."_custom_css",
                    "std" => "",
                    "type" => "textarea");

$options[] = array( "name" => __( 'Post/Page Comments', 'woothemes' ),
					"desc" => __( 'Select if you want to comments on posts and/or pages.', 'woothemes' ),
					"id" => $shortname."_comments",
					"type" => "select2",
					"options" => array( "post" => __( 'Posts Only', 'woothemes' ), "page" => __( 'Pages Only', 'woothemes' ), "both" => __( 'Pages / Posts', 'woothemes' ), "none" => __( 'None', 'woothemes' ) ) );

$options[] = array( "name" => __( 'Post Content', 'woothemes' ),
					"desc" => __( 'Select if you want to show the full content or the excerpt on posts.', 'woothemes' ),
					"id" => $shortname."_post_content",
					"type" => "select2",
					"options" => array("excerpt" => __( 'The Excerpt', 'woothemes' ), "content" => __( 'Full Content', 'woothemes' ) ) );

$options[] = array( "name" => __( 'Display Breadcrumbs', 'woothemes' ),
					"desc" => __( 'Display dynamic breadcrumbs on each page of your website.', 'woothemes' ),
					"id" => $shortname."_breadcrumbs_show",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => __( 'Pagination Style', 'woothemes' ),
					"desc" => __( 'Select the style of pagination you would like to use on the blog.', 'woothemes' ),
					"id" => $shortname."_pagination_type",
					"type" => "select2",
					"options" => array("paginated_links" => __( 'Numbers', 'woothemes' ), "simple" => __( 'Next/Previous', 'woothemes' ) ) );

/* Advertising */
$options[] = array( 'name' => __( 'Advertising', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'Enable Ad (Ad - Top (468x60))', 'woothemes' ),
					"desc" => __( 'Enable the ad space', 'woothemes' ),
					"id" => $shortname."_ad_top",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => __( 'Adsense code', 'woothemes' ),
					"desc" => __( 'Enter your adsense code (or other ad network code) here.', 'woothemes' ),
					"id" => $shortname."_ad_top_adsense",
					"std" => "",
					"type" => "textarea");

$options[] = array( "name" => __( 'Image Location', 'woothemes' ),
					"desc" => __( 'Enter the URL to the banner ad image location.', 'woothemes' ),
					"id" => $shortname."_ad_top_image",
					"std" => "http://www.woothemes.com/ads/468x60b.jpg",
					"type" => "upload");

$options[] = array( "name" => __( 'Destination URL', 'woothemes' ),
					"desc" => __( 'Enter the URL where this banner ad points to.', 'woothemes' ),
					"id" => $shortname."_ad_top_url",
					"std" => "http://www.woothemes.com",
					"type" => "text");

/* General Styling */

$options[] = array( "name" => __( 'Styling &amp; Layout', 'woothemes' ),
					"icon" => "styling",
					"type" => "heading");

$options[] = array( 'name' => __( 'General Styling', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'Disable ALL Custom Styling', 'woothemes' ),
					"desc" => __( 'Disable output of all custom styling (CSS) from the theme options and use default styles from the stylesheet.', 'woothemes' ),
					"id" => $shortname."_style_disable",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" =>  __( 'Background Color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for site background or add a hex color code e.g. #e6e6e6', 'woothemes' ),
					"id" => $shortname."_style_bg",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'Background Image', 'woothemes' ),
					"desc" => __( 'Upload a background image, or specify the image address of your image. (http://yoursite.com/image.png)', 'woothemes' ),
					"id" => $shortname."_style_bg_image",
					"std" => "",
					"type" => "upload");

$options[] = array( "name" => __( 'Background Image Repeat', 'woothemes' ),
					"desc" => __( 'Select how you want your background image to display.', 'woothemes' ),
					"id" => $shortname."_style_bg_image_repeat",
					"type" => "select",
					"options" => array( "No Repeat" => "no-repeat", "Repeat" => "repeat","Repeat Horizontally" => "repeat-x", "Repeat Vertically" => "repeat-y" ) );

$options[] = array( 'name' => __( 'Background image position', 'woothemes' ),
					'desc' => __( 'Select how you would like to position the background', 'woothemes' ),
					'id' => $shortname . '_style_bg_image_pos',
					'std' => 'top left',
					'type' => 'select',
					'options' => array( "top left", "top center", "top right", "center left", "center center", "center right", "bottom left", "bottom center", "bottom right" ) );

$options[] = array( "name" => __( 'Background Attachment', 'woothemes' ),
            		"desc" => __( 'Select whether the background should be fixed or move when the user scrolls', 'woothemes' ),
            		"id" => $shortname."_style_bg_image_attach",
            		"std" => "scroll",
            		"type" => "select",
            		"options" => array( "scroll","fixed" ) );

$options[] = array( "name" => __( 'Top Border', 'woothemes' ),
					"desc" => __( 'Specify border properties for the top border.', 'woothemes' ),
					"id" => $shortname."_border_top",
					"std" => array('width' => '0','style' => 'solid','color' => '#000000'),
					"type" => "border");
/*
$options[] = array( 'name' => __( 'Links', 'woothemes' ),
					'type' => 'subheading' );
*/
$options[] = array( "name" => __( 'Link Color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for links or add a hex color code e.g. #697e09', 'woothemes' ),
					"id" => $shortname."_link_color",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'Link Hover Color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for links hover or add a hex color code e.g. #697e09', 'woothemes' ),
					"id" => $shortname."_link_hover_color",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'Button Color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for buttons or add a hex color code e.g. #697e09', 'woothemes' ),
					"id" => $shortname."_button_color",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'Button Hover Color', 'woothemes' ),
					"desc" => __( 'Pick a custom hover color for buttons or add a hex color code e.g. #697e09', 'woothemes' ),
					"id" => $shortname."_button_hover_color",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'General Border Color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for general border colors or add a hex color code e.g. #e6e6e6', 'woothemes' ),
					"id" => $shortname."_style_border",
					"std" => "",
					"type" => "color");

$options[] = array( 'name' => __( 'General Layout', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'Layout Manager', 'woothemes' ),
					"desc" => "",
					"id" => $shortname."_layout_manager_notice",
					"std" => sprintf( __( 'Below you can set the general site width and layout. To control the width of the columns in your themes layout, please visit the <a href="%s">Layout Manager</a>.', 'woothemes' ), admin_url( 'admin.php?page=woo-layout-manager' ) ),
					"type" => "info");

$options[] = array( "name" => __( 'Site Width', 'woothemes' ),
		                    "desc" => "Set the width (in px) that you would like your content column to be (recommended max-width is 1600px)",
		                    "id" => $shortname."_layout_width",
		                    "std" => "960",
							"min" => "600",
							"max" => "1600",
							"increment" => "10",
							"type" => 'slider' );

$images_dir =  get_template_directory_uri() . '/functions/images/';

$options[] = array( "name" => __( 'Main Layout', 'woothemes' ),
						"desc" => __( 'Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.', 'woothemes' ),
						"id" => $shortname . "_layout",
						"std" => "two-col-left",
						"type" => "images",
						"options" => array(
							'one-col' => $images_dir . '1c.png',
							'two-col-left' => $images_dir . '2cl.png',
							'two-col-right' => $images_dir . '2cr.png',
							'three-col-left' => $images_dir . '3cl.png',
							'three-col-middle' => $images_dir . '3cm.png',
							'three-col-right' => $images_dir . '3cr.png')
						);


$url =  get_template_directory_uri() . '/functions/images/';
$options[] = array( "name" => __( 'Footer Widget Areas', 'woothemes' ),
					"desc" => __( 'Select how many footer widget areas you want to display.', 'woothemes' ),
					"id" => $shortname."_footer_sidebars",
					"std" => "4",
					"type" => "images",
					"options" => array(
						'0' => $url . 'footer-widgets-0.png',
						'1' => $url . 'footer-widgets-1.png',
						'2' => $url . 'footer-widgets-2.png',
						'3' => $url . 'footer-widgets-3.png',
						'4' => $url . 'footer-widgets-4.png')
					);

$options[] = array( "name" => __( 'Enable Fixed Mobile Layout', 'woothemes' ),
					"desc" => __( 'Canvas is responsive, meaning it adapts its layout on mobile devices. Enabling Fixed Layout will remove the responsive layout on mobile devices.', 'woothemes' ),
					"id" => $shortname."_remove_responsive",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => __( 'Category Exclude - Homepage', 'woothemes' ),
					"desc" => __( 'Specify a comma seperated list of category IDs or slugs that you\'d like to exclude from your homepage (eg: uncategorized).', 'woothemes' ),
					"id" => $shortname."_exclude_cats_home",
					"std" => "",
					"type" => "text" );

$options[] = array( "name" => __( 'Category Exclude - Blog Page Template', 'woothemes' ),
					"desc" => __( 'Specify a comma seperated list of category IDs or slugs that you\'d like to exclude from your \'Blog\' page template (eg: uncategorized).', 'woothemes' ),
					"id" => $shortname."_exclude_cats_blog",
					"std" => "",
					"type" => "text" );

/* Boxed Layout */
$options[] = array( 'name' => __( 'Boxed Layout', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'Boxed Layout Style', 'woothemes' ),
					"desc" => __( 'Enable the boxed layout style.', 'woothemes' ),
					"id" => $shortname."_layout_boxed",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => __( 'Box Background Color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for the boxed background or add a hex color code e.g. #ffffff', 'woothemes' ),
					"id" => $shortname."_style_box_bg",
					"std" => "#ffffff",
					"type" => "color");

$options[] = array( "name" => __( 'Box Margin', 'woothemes' ),
					"desc" => __( 'Enter an integer value i.e. 20 for the desired top and bottom margin.', 'woothemes' ),
					"id" => $shortname."_box_margin",
					"std" => "",
					"type" => array(
									array(  'id' => $shortname. '_box_margin_top',
											'type' => 'text',
											'std' => '0',
											'meta' => __( 'Top', 'woothemes' ) ),
									array(  'id' => $shortname. '_box_margin_bottom',
											'type' => 'text',
											'std' => '0',
											'meta' => __( 'Bottom', 'woothemes' ) )
								  ));

$options[] = array( "name" => __( 'Box Border Top/Bottom', 'woothemes' ),
					"desc" => __( 'Specify border properties for the boxed layout.', 'woothemes' ),
					"id" => $shortname."_box_border_tb",
					"std" => array('width' => '1','style' => 'solid','color' => '#dbdbdb'),
					"type" => "border");

$options[] = array( "name" => __( 'Box Border Left/Right', 'woothemes' ),
					"desc" => __( 'Specify border properties for the boxed layout.', 'woothemes' ),
					"id" => $shortname."_box_border_lr",
					"std" => array('width' => '1','style' => 'solid','color' => '#dbdbdb'),
					"type" => "border");

$options[] = array( "name" => __( 'Box Rounded Corners', 'woothemes' ),
					"desc" => __( 'Set amount of pixels for border radius (rounded corners). Will only show in CSS3 compatible browser.', 'woothemes' ),
					"id" => $shortname."_box_border_radius",
					"type" => "select",
					"std" => "0px",
					"options" => $options_pixels);

$options[] = array( "name" => __( 'Box Shadow', 'woothemes' ),
					"desc" => __( 'Enable box shadow. Will only show in CSS3 compatible browser.', 'woothemes' ),
					"id" => $shortname."_box_shadow",
					"std" => "true",
					"type" => "checkbox");

/* Full width Layout */

$options[] = array( 'name' => __( 'Full Width Layout', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'Full Width Layout', 'woothemes' ),
					"desc" => "",
					"id" => $shortname."_full_width_notice",
					"std" => __( 'Below you can enable full width header and footer areas and set the background. You can set the styling options for the full width navigation under the Primary Navigation options. Please note that Boxed Layout must be disabled.', 'woothemes' ),
					"type" => "info");

$options[] = array( "name" => __( 'Enable Full Width Header', 'woothemes' ),
					"desc" => __( 'Set header container to display full width.', 'woothemes' ),
					"id" => $shortname."_header_full_width",
					"std" => "false",
					"type" => "checkbox" );

$options[] = array( "name" => __( 'Header Background Color', 'woothemes' ),
					"desc" => __( 'Select the background color you want for your full width header.', 'woothemes' ),
					"id" => $shortname."_full_header_full_width_bg",
					"std" => "#fefefe",
					"type" => "color");
					
$options[] = array( "name" => __( 'Header Background Image', 'woothemes' ),
					"desc" => __( 'Upload a background image, or specify the image address of your image (http://yoursite.com/image.png). <br/>Image should be same width as your site width.', 'woothemes' ),
					"id" => $shortname."_full_header_bg_image",
					"std" => "",
					"type" => "upload");

$options[] = array( "name" => __( 'Header Background Image Repeat', 'woothemes' ),
					"desc" => __( 'Select how you want your background image to display.', 'woothemes' ),
					"id" => $shortname."_full_header_bg_image_repeat",
					"type" => "select",
					"options" => array("No Repeat" => "no-repeat", "Repeat" => "repeat","Repeat Horizontally" => "repeat-x", "Repeat Vertically" => "repeat-y",) );				

$options[] = array( "name" => __( 'Enable Full Width Footer', 'woothemes' ),
					"desc" => __( 'Set footer widget area and footer container to display full width.', 'woothemes' ),
					"id" => $shortname."_footer_full_width",
					"std" => "false",
					"type" => "checkbox" );

$options[] = array( "name" => __( 'Footer Widget Area Background Color', 'woothemes' ),
					"desc" => __( 'Select the background color you want for your full width widget area.', 'woothemes' ),
					"id" => $shortname."_foot_full_width_widget_bg",
					"std" => "#f0f0f0",
					"type" => "color");

$options[] = array( "name" => __( 'Footer Background Color', 'woothemes' ),
					"desc" => __( 'Select the background color you want for your full width footer.', 'woothemes' ),
					"id" => $shortname."_footer_full_width_bg",
					"std" => "#222222",
					"type" => "color");

/* Top Navigation */
$options[] = array( 'name' => __( 'Top Navigation', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'Top Navigation - Background Color', 'woothemes' ),
					"desc" => sprintf( __( 'Pick a custom color for the top navigation background or add a hex color code e.g. #000.<br />Top Navigation can be added with <a href="%s">WP Menus</a>', 'woothemes' ), admin_url( 'nav-menus.php' ) ),
					"id" => $shortname."_top_nav_bg",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'Top Navigation - Hover Color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for the top navigation hover text color or add a hex color code e.g. #000', 'woothemes' ),
					"id" => $shortname."_top_nav_hover",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'Top Navigation - Hover Background Color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for the top navigation hover background color or add a hex color code e.g. #000', 'woothemes' ),
					"id" => $shortname."_top_nav_hover_bg",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'Top Navigation Font Style', 'woothemes' ),
					"desc" => __( 'Select typography for navigation.', 'woothemes' ),
					"id" => $shortname."_top_nav_font",
					"std" => array('size' => '12','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'thin','color' => '#ddd'),
					"type" => "typography");

/* Header Styling */
$options[] = array( 'name' => __( 'Header', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'Header Background Color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for header background or add a hex color code e.g. #e6e6e6', 'woothemes' ),
					"id" => $shortname."_header_bg",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'Header Background Image', 'woothemes' ),
					"desc" => __( 'Upload a background image, or specify the image address of your image (http://yoursite.com/image.png). <br/>Image should be same width as your site width.', 'woothemes' ),
					"id" => $shortname."_header_bg_image",
					"std" => "",
					"type" => "upload");

$options[] = array( "name" => __( 'Header Background Image Repeat', 'woothemes' ),
					"desc" => __( 'Select how you want your background image to display.', 'woothemes' ),
					"id" => $shortname."_header_bg_image_repeat",
					"type" => "select",
					"options" => array("No Repeat" => "no-repeat", "Repeat" => "repeat","Repeat Horizontally" => "repeat-x", "Repeat Vertically" => "repeat-y",) );

$options[] = array( "name" => __( 'Header Border', 'woothemes' ),
					"desc" => __( 'Specify border properties for the header.', 'woothemes' ),
					"id" => $shortname."_header_border",
					"std" => array('width' => '0','style' => 'solid','color' => ''),
					"type" => "border");

$options[] = array( "name" => __( 'Header Margin Top/Bottom', 'woothemes' ),
					"desc" => __( 'Enter an integer value i.e. 20 for the desired header margin.', 'woothemes' ),
					"id" => $shortname."_header_margin_tb",
					"std" => "",
					"type" => array(
									array(  'id' => $shortname. '_header_margin_top',
											'type' => 'text',
											'std' => '0',
											'meta' => __( 'Top', 'woothemes' ) ),
									array(  'id' => $shortname. '_header_margin_bottom',
											'type' => 'text',
											'std' => '0',
											'meta' => __( 'Bottom', 'woothemes' ) )
								  ));
$options[] = array( "name" => __( 'Header Padding Top/Bottom', 'woothemes' ),
					"desc" => __( 'Enter an integer value i.e. 20 for the desired header padding.', 'woothemes' ),
					"id" => $shortname."_header_padding_tb",
					"std" => "",
					"type" => array(
									array(  'id' => $shortname. '_header_padding_top',
											'type' => 'text',
											'std' => '40',
											'meta' => __( 'Top', 'woothemes' ) ),
									array(  'id' => $shortname. '_header_padding_bottom',
											'type' => 'text',
											'std' => '40',
											'meta' => __( 'Bottom', 'woothemes' ) )
								  ));

$options[] = array( "name" => __( 'Header Padding Left/Right', 'woothemes' ),
					"desc" => __( 'Enter an integer value i.e. 20 for the desired header padding.', 'woothemes' ),
					"id" => $shortname."_header_padding_lr",
					"std" => "",
					"type" => array(
									array(  'id' => $shortname. '_header_padding_left',
											'type' => 'text',
											'std' => '',
											'meta' => __( 'Left', 'woothemes' ) ),
									array(  'id' => $shortname. '_header_padding_right',
											'type' => 'text',
											'std' => '',
											'meta' => __( 'Right', 'woothemes' ) )
								  ));

$options[] = array( "name" => __( 'Site Title Font Style', 'woothemes' ),
					"desc" => __( 'Select typography for site title.', 'woothemes' ),
					"id" => $shortname."_font_logo",
					"std" => array('size' => '40','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'bold','color' => '#222222'),
					"type" => "typography");

$options[] = array( "name" => __( 'Site Description Font Style', 'woothemes' ),
					"desc" => __( 'Select typography for site description.', 'woothemes' ),
					"id" => $shortname."_font_desc",
					"std" => array('size' => '13','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'thin','color' => '#999999'),
					"type" => "typography");

/* Navigation Styling */
$options[] = array( 'name' => __( 'Primary Navigation', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'Background Color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for the navigation background or add a hex color code e.g. #cccccc', 'woothemes' ),
					"id" => $shortname."_nav_bg",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'Navigation Font Style', 'woothemes' ),
					"desc" => __( 'Select typography for navigation.', 'woothemes' ),
					"id" => $shortname."_nav_font",
					"std" => array('size' => '14','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => '','color' => '#666666'),
					"type" => "typography");

$options[] = array( "name" => __( 'Hover Text Color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for the navigation hover text color or add a hex color code e.g. #eeeeee', 'woothemes' ),
					"id" => $shortname."_nav_hover",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'Hover Background Color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for the navigation hover background color or add a hex color code e.g. #eeeeee', 'woothemes' ),
					"id" => $shortname."_nav_hover_bg",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'Current Item Text Color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for the text color of the current menu item in the navigation, or add a hex color code e.g. #eeeeee', 'woothemes' ),
					"id" => $shortname."_nav_currentitem",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'Current Item Background Color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for the background of the current menu item in the navigation, or add a hex color code e.g. #eeeeee', 'woothemes' ),
					"id" => $shortname."_nav_currentitem_bg",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'Divider', 'woothemes' ),
					"desc" => __( 'Specify border properties for the menu items dividers.', 'woothemes' ),
					"id" => $shortname."_nav_divider_border",
					"std" => array('width' => '1','style' => 'solid','color' => '#dbdbdb'),
					"type" => "border");

$options[] = array( "name" => __( 'Dropdown menu border', 'woothemes' ),
					"desc" => __( 'Specify border properties for the navigation dropdown menu.', 'woothemes' ),
					"id" => $shortname."_nav_dropdown_border",
					"std" => array('width' => '1','style' => 'solid','color' => '#dbdbdb'),
					"type" => "border");

$options[] = array( "name" => __( 'Border Top', 'woothemes' ),
					"desc" => __( 'Specify border properties for the navigation.', 'woothemes' ),
					"id" => $shortname."_nav_border_top",
					"std" => array('width' => '1','style' => 'solid','color' => '#dbdbdb'),
					"type" => "border");

$options[] = array( "name" => __( 'Border Bottom', 'woothemes' ),
					"desc" => __( 'Specify border properties for the navigation.', 'woothemes' ),
					"id" => $shortname."_nav_border_bot",
					"std" => array('width' => '1','style' => 'solid','color' => '#dbdbdb'),
					"type" => "border");

$options[] = array( "name" => __( 'Border Left/Right', 'woothemes' ),
					"desc" => __( 'Specify border properties for the navigation.', 'woothemes' ),
					"id" => $shortname."_nav_border_lr",
					"std" => array('width' => '1','style' => 'solid','color' => '#dbdbdb'),
					"type" => "border");

$options[] = array( "name" => __( 'Navigation Rounded Corners', 'woothemes' ),
					"desc" => __( 'Set amount of pixels for border radius (rounded corners). Will only show in CSS3 compatible browser.', 'woothemes' ),
					"id" => $shortname."_nav_border_radius",
					"type" => "select",
					"std" => "5px",
					"options" => $options_pixels);

$options[] = array( "name" => __( 'Enable Subscribe Icon', 'woothemes' ),
					"desc" => __( 'Enable the Subscribe to RSS icon in right navigation.', 'woothemes' ),
					"id" => $shortname."_nav_rss",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => __( 'Enable E-mail Icon', 'woothemes' ),
					"desc" => __( 'Enter an URL for the mail icon in the right navigation', 'woothemes' ),
					"id" => $shortname."_subscribe_email",
					"std" => "",
					"type" => "text");

/* Post Styling */
$options[] = array( 'name' => __( 'Posts / Pages', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'Post/Page Title Font Style', 'woothemes' ),
					"desc" => __( 'Specify typography for post/page title text.', 'woothemes' ),
					"id" => $shortname."_font_post_title",
					"std" => array('size' => '28','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'bold','color' => '#222222'),
					"type" => "typography");

$options[] = array( "name" => __( 'Post Meta Font Style', 'woothemes' ),
					"desc" => __( 'Specify typography for post meta.', 'woothemes' ),
					"id" => $shortname."_font_post_meta",
					"std" => array('size' => '12','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'thin','color' => '#999999'),
					"type" => "typography");

$options[] = array( "name" => __( 'Post/Page Text Font Style', 'woothemes' ),
					"desc" => __( 'Specify typography for post/page content text.', 'woothemes' ),
					"id" => $shortname."_font_post_text",
					"std" => array('size' => '15','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => '300','color' => '#555555'),
					"type" => "typography");

$options[] = array( "name" => __( 'Post More (bottom) Font Style', 'woothemes' ),
					"desc" => __( 'Specify typography for post bottom text.', 'woothemes' ),
					"id" => $shortname."_font_post_more",
					"std" => array('size' => '13','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'thin','color' => ''),
					"type" => "typography");

$options[] = array( "name" => __( 'Post More (bottom) Border Top', 'woothemes' ),
					"desc" => __( 'Specify border properties for post more section.', 'woothemes' ),
					"id" => $shortname."_post_more_border_top",
					"std" => array('width' => '0','style' => 'solid','color' => '#e6e6e6'),
					"type" => "border");

$options[] = array( "name" => __( 'Post More (bottom) Border Bottom', 'woothemes' ),
					"desc" => __( 'Specify border properties for post more section.', 'woothemes' ),
					"id" => $shortname."_post_more_border_bottom",
					"std" => array('width' => '0','style' => 'solid','color' => '#e6e6e6'),
					"type" => "border");

$options[] = array( "name" => __( 'Post Author Background Color', 'woothemes' ),
					"desc" => __( 'Pick a custom background color for the post author section or add a hex color code e.g. #fafafa', 'woothemes' ),
					"id" => $shortname."_post_author_bg",
					"std" => "#fafafa",
					"type" => "color");

$options[] = array( "name" => __( 'Post Author Border Top', 'woothemes' ),
					"desc" => __( 'Specify border properties for post author section.', 'woothemes' ),
					"id" => $shortname."_post_author_border_top",
					"std" => array('width' => '1','style' => 'solid','color' => '#e6e6e6'),
					"type" => "border");

$options[] = array( "name" => __( 'Post Author Border Bottom', 'woothemes' ),
					"desc" => __( 'Specify border properties for post author section.', 'woothemes' ),
					"id" => $shortname."_post_author_border_bottom",
					"std" => array('width' => '1','style' => 'solid','color' => '#e6e6e6'),
					"type" => "border");

$options[] = array( "name" => __( 'Post Author Border Left/Right', 'woothemes' ),
					"desc" => __( 'Specify border properties for the navigation.', 'woothemes' ),
					"id" => $shortname."_post_author_border_lr",
					"std" => array('width' => '1','style' => 'solid','color' => '#e6e6e6'),
					"type" => "border");

$options[] = array( "name" => __( 'Post Author Rounded Corners', 'woothemes' ),
					"desc" => __( 'Set amount of pixels for border radius (rounded corners). Will only show in CSS3 compatible browser.', 'woothemes' ),
					"id" => $shortname."_post_author_border_radius",
					"type" => "select",
					"std" => "5px",
					"options" => $options_pixels);

$options[] = array( "name" => __( 'Disable Post Author', 'woothemes' ),
					"desc" => __( 'Disable post author below post?', 'woothemes' ),
					"id" => $shortname."_disable_post_author",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => __( 'Comments Background Color (even threads)', 'woothemes' ),
					"desc" => __( 'Pick a custom background color for the post comments even threads or add a hex color code e.g. #fafafa', 'woothemes' ),
					"id" => $shortname."_post_comments_bg",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'Page Navigation Font Style', 'woothemes' ),
					"desc" => __( 'Select typography for Page Navigation text.', 'woothemes' ),
					"id" => $shortname."_pagenav_font",
					"std" => array('size' => '13','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'thin','color' => '#888'),
					"type" => "typography");

$options[] = array( "name" => __( 'Page Navigation Background Color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for the Page Navigation background or add a hex color code e.g. #fafafa', 'woothemes' ),
					"id" => $shortname."_pagenav_bg",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'Page Navigation Border Top', 'woothemes' ),
					"desc" => __( 'Specify border properties for Page Navigation section.', 'woothemes' ),
					"id" => $shortname."_pagenav_border_top",
					"std" => array('width' => '0','style' => 'solid','color' => '#e6e6e6'),
					"type" => "border");

$options[] = array( "name" => __( 'Page Navigation Border Bottom', 'woothemes' ),
					"desc" => __( 'Specify border properties for Page Navigation section.', 'woothemes' ),
					"id" => $shortname."_pagenav_border_bottom",
					"std" => array('width' => '0','style' => 'solid','color' => '#e6e6e6'),
					"type" => "border");

$options[] = array( 'name' => __( 'Archives', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'Archive Header Font Style', 'woothemes' ),
					"desc" => __( 'Select typography for Archive header.', 'woothemes' ),
					"id" => $shortname."_archive_header_font",
					"std" => array('size' => '18','unit' => 'px', 'face' => 'Arial, sans-serif','style' => 'bold','color' => '#222222'),
					"type" => "typography");

$options[] = array( "name" => __( 'Archive Header Border Bottom', 'woothemes' ),
					"desc" => __( 'Specify border properties for Archive header', 'woothemes' ),
					"id" => $shortname."_archive_header_border_bottom",
					"std" => array('width' => '1','style' => 'solid','color' => '#e6e6e6'),
					"type" => "border");

$options[] = array( "name" => __( 'Disable Archive Header RSS link', 'woothemes' ),
					"desc" => __( 'Disable RSS link in Archive header', 'woothemes' ),
					"id" => $shortname."_archive_header_disable_rss",
					"std" => "false",
					"type" => "checkbox");

/* Widget Styling */
$options[] = array( 'name' => __( 'Widgets', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'Widget Background Color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for the widget background or add a hex color code e.g. #cccccc', 'woothemes' ),
					"id" => $shortname."_widget_bg",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'Widget Border', 'woothemes' ),
					"desc" => __( 'Specify border properties for widgets.', 'woothemes' ),
					"id" => $shortname."_widget_border",
					"std" => array('width' => '0','style' => 'solid','color' => '#dbdbdb'),
					"type" => "border");

$options[] = array( "name" => __( 'Widget Padding', 'woothemes' ),
					"desc" => __( 'Enter an integer value i.e. 20 for the desired widget padding.', 'woothemes' ),
					"id" => $shortname."_widget_padding",
					"std" => "",
					"type" => array(
									array(  'id' => $shortname. '_widget_padding_tb',
											'type' => 'text',
											'std' => '',
											'meta' => __( 'Top/Bottom', 'woothemes' ) ),
									array(  'id' => $shortname. '_widget_padding_lr',
											'type' => 'text',
											'std' => '',
											'meta' => __( 'Left/Right', 'woothemes' ) )
								  ));

$options[] = array( "name" => __( 'Widget Title', 'woothemes' ),
					"desc" => __( 'Select the typography you want for the widget title.', 'woothemes' ),
					"id" => $shortname."_widget_font_title",
					"std" => array('size' => '14','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'bold','color' => '#555555'),
					"type" => "typography");

$options[] = array( "name" => __( 'Widget Title Bottom Border', 'woothemes' ),
					"desc" => __( 'Specify border property for the widget title.', 'woothemes' ),
					"id" => $shortname."_widget_title_border",
					"std" => array('width' => '1','style' => 'solid','color' => '#e6e6e6'),
					"type" => "border");

$options[] = array( "name" => __( 'Widget Text', 'woothemes' ),
					"desc" => __( 'Select the typography you want for the widget text.', 'woothemes' ),
					"id" => $shortname."_widget_font_text",
					"std" => array('size' => '13','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'thin','color' => '#555555'),
					"type" => "typography");

$options[] = array( "name" => __( 'Widget Rounded Corners', 'woothemes' ),
					"desc" => __( 'Set amount of pixels for border radius (rounded corners). Will only show in CSS3 compatible browser.', 'woothemes' ),
					"id" => $shortname."_widget_border_radius",
					"type" => "select",
					"options" => $options_pixels);

$options[] = array( "name" => __( 'Tabs Widget Background color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for the tabs widget or add a hex color code e.g. #cccccc', 'woothemes' ),
					"id" => $shortname."_widget_tabs_bg",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'Tabs Widget Inside Background Color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for the tabs widget or add a hex color code e.g. #cccccc', 'woothemes' ),
					"id" => $shortname."_widget_tabs_bg_inside",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'Tabs Widget Title', 'woothemes' ),
					"desc" => __( 'Select the typography you want for the widget text.', 'woothemes' ),
					"id" => $shortname."_widget_tabs_font",
					"std" => array('size' => '12','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'bold','color' => '#555555'),
					"type" => "typography");

$options[] = array( "name" => __( 'Tabs Widget Meta / Tabber Font', 'woothemes' ),
					"desc" => __( 'Select the typography you want for the widget text.', 'woothemes' ),
					"id" => $shortname."_widget_tabs_font_meta",
					"std" => array('size' => '11','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'thin','color' => '#999999'),
					"type" => "typography");

$options[] = array( 'name' => __( 'Footer', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'Footer Font Style', 'woothemes' ),
					"desc" => __( 'Select typography for footer.', 'woothemes' ),
					"id" => $shortname."_footer_font",
					"std" => array('size' => '13','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'thin','color' => '#999999'),
					"type" => "typography");

$options[] = array( "name" => __( 'Footer Background', 'woothemes' ),
					"desc" => __( 'Select the background color you want for your footer.', 'woothemes' ),
					"id" => $shortname."_footer_bg",
					"std" => "",
					"type" => "color");

$options[] = array( "name" => __( 'Footer Border Top', 'woothemes' ),
					"desc" => __( 'Specify top border properties for the footer.', 'woothemes' ),
					"id" => $shortname."_footer_border_top",
					"std" => array('width' => '1','style' => 'solid','color' => '#dbdbdb'),
					"type" => "border");

$options[] = array( "name" => __( 'Footer Border Bottom', 'woothemes' ),
					"desc" => __( 'Specify bottom border properties for the footer.', 'woothemes' ),
					"id" => $shortname."_footer_border_bottom",
					"std" => array('width' => '0','style' => 'solid','color' => ''),
					"type" => "border");

$options[] = array( "name" => __( 'Footer Border Left/Right', 'woothemes' ),
					"desc" => __( 'Specify left/right border properties for the footer.', 'woothemes' ),
					"id" => $shortname."_footer_border_lr",
					"std" => array('width' => '0','style' => 'solid','color' => ''),
					"type" => "border");

$options[] = array( "name" => __( 'Footer Rounded Corners', 'woothemes' ),
					"desc" => __( 'Set amount of pixels for border radius (rounded corners). Will only show in CSS3 compatible browser.', 'woothemes' ),
					"id" => $shortname."_footer_border_radius",
					"type" => "select",
					"options" => $options_pixels);
/*
$options[] = array( "name" => __( 'Footer Customisation', 'woothemes' ),
					"icon" => "footer",
					"type" => "subheading");
*/
$options[] = array( "name" => __( 'Custom Affiliate Link', 'woothemes' ),
					"desc" => __( 'Add an affiliate link to the WooThemes logo in the footer of the theme.', 'woothemes' ),
					"id" => $shortname."_footer_aff_link",
					"std" => "",
					"type" => "text");

$options[] = array( "name" => __( 'Enable Custom Footer (Left)', 'woothemes' ),
					"desc" => __( 'Activate to add the custom text below to the theme footer.', 'woothemes' ),
					"id" => $shortname."_footer_left",
					"class" => "collapsed",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => __( 'Custom Text (Left)', 'woothemes' ),
					"desc" => __( 'Custom HTML and Text that will appear in the footer of your theme.', 'woothemes' ),
					"id" => $shortname."_footer_left_text",
					"class" => "hidden last",
					"std" => "<p></p>",
					"type" => "textarea");

$options[] = array( "name" => __( 'Enable Custom Footer (Right)', 'woothemes' ),
					"desc" => __( 'Activate to add the custom text below to the theme footer.', 'woothemes' ),
					"id" => $shortname."_footer_right",
					"class" => "collapsed",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => __( 'Custom Text (Right)', 'woothemes' ),
					"desc" => __( 'Custom HTML and Text that will appear in the footer of your theme.', 'woothemes' ),
					"id" => $shortname."_footer_right_text",
					"class" => "hidden last",
					"std" => "<p></p>",
					"type" => "textarea");

/* Misc Typography */
$options[] = array( 'name' => __( 'Misc Typography', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'Misc Typography', 'woothemes' ),
					"desc" => "",
					"id" => $shortname."_general_font_notice",
					"std" => __( 'The misc typography options below only control typography not covered by other typography options. You can control specific typography on post title, post content, widget titles etc. in the other sections in the options panel.', 'woothemes' ),
					"type" => "info");

$options[] = array( "name" => __( 'General Text Font Style', 'woothemes' ),
					"desc" => __( 'Select typography for general text.', 'woothemes' ),
					"id" => $shortname."_font_text",
					"std" => array('size' => '14','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'thin','color' => '#555555'),
					"type" => "typography");

$options[] = array( "name" => __( 'H1 Font Style', 'woothemes' ),
					"desc" => __( 'Select the typography you want for header H1.', 'woothemes' ),
					"id" => $shortname."_font_h1",
					"std" => array('size' => '28','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'bold','color' => '#222222'),
					"type" => "typography");

$options[] = array( "name" => __( 'H2 Font Style', 'woothemes' ),
					"desc" => __( 'Select the typography you want for header H2.', 'woothemes' ),
					"id" => $shortname."_font_h2",
					"std" => array('size' => '24','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'bold','color' => '#222222'),
					"type" => "typography");

$options[] = array( "name" => __( 'H3 Font Style', 'woothemes' ),
					"desc" => __( 'Select the typography you want for header H3.', 'woothemes' ),
					"id" => $shortname."_font_h3",
					"std" => array('size' => '20','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'bold','color' => '#222222'),
					"type" => "typography");

$options[] = array( "name" => __( 'H4 Font Style', 'woothemes' ),
					"desc" => __( 'Select the typography you want for header H4.', 'woothemes' ),
					"id" => $shortname."_font_h4",
					"std" => array('size' => '16','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'bold','color' => '#222222'),
					"type" => "typography");

$options[] = array( "name" => __( 'H5 Font Style', 'woothemes' ),
					"desc" => __( 'Select the typography you want for header H5.', 'woothemes' ),
					"id" => $shortname."_font_h5",
					"std" => array('size' => '14','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'bold','color' => '#222222'),
					"type" => "typography");

$options[] = array( "name" => __( 'H6 Font Style', 'woothemes' ),
					"desc" => __( 'Select the typography you want for header H6.', 'woothemes' ),
					"id" => $shortname."_font_h6",
					"std" => array('size' => '12','unit' => 'px', 'face' => 'Helvetica, Arial, sans-serif','style' => 'bold','color' => '#222222'),
					"type" => "typography");


/* Template: Magazine */
	$options[] = array( "name" => __( 'Magazine Template', 'woothemes' ),
						"icon" => "layout",
						"type" => "heading");

	$options[] = array( 'name' => __( '"Magazine" Setup', 'woothemes' ),
					'type' => 'subheading' );

	$options[] = array( "name" => __( 'Magazine Page Template', 'woothemes' ),
						"desc" => "",
						"id" => $shortname."_woo_magazine_notice",
						"std" => sprintf( __( 'Below you can control settings for the Magazine page template. Please refer to <a href="%s">documentation</a> on how to setup the page template.' ), 'http://www.woothemes.com/support/theme-documentation/canvas/' ),
						"type" => "info");

	$options[] = array( "name" => __( 'Exclude Categories From Loop', 'woothemes' ),
						"desc" => sprintf( __( 'Enter a comma-separated list of category <a href="%s">ID\'s</a> that you\'d like to exclude from the post loop. (e.g. 12,23,27,44)', 'woothemes' ), 'http://support.wordpress.com/pages/8/' ),
						"id" => $shortname."_magazine_exclude",
						"std" => "",
						"type" => "text");

	$per_page = array();
	for ( $i = 1; $i <= 20; $i++ ) {
		$per_page[] = $i;
	}

	$options[] = array( "name" => __( 'Number of Posts To Display', 'woothemes' ),
						"desc" => __( 'The number of posts to display on the "Magazine" page template.', 'woothemes' ),
						"id" => $shortname."_magazine_limit",
						"std" => get_option( 'posts_per_page' ),
						"type" => "select",
						"options" => $per_page );

	$options[] = array( 'name' => __( 'Posts Slider', 'woothemes' ),
					'type' => 'subheading' );

	$options[] = array( "name" => __( 'Enable Featured Slider', 'woothemes' ),
						"desc" => __( 'Enable the featured slider on the "Magazine" page template.', 'woothemes' ),
						"id" => $shortname."_slider_magazine",
						"std" => "false",
						"type" => "checkbox");

		$options[] = array( "name" => __( 'Post Tag(s)', 'woothemes' ),
						"desc" => __( 'Add comma separated list for the tags that you would like to have displayed in the featured slider on the "Magazine" page template. For example, if you add "tag1, tag3" here, then all posts tagged with either "tag1" or "tag3" will be shown in the featured area. These posts will be excluded from normal posts below slider.', 'woothemes' ),
						"id" => $shortname."_slider_magazine_tags",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => __( 'Number Of Posts To Display', 'woothemes' ),
						"desc" => __( 'Select the number of entries that should appear in the Featured Slider.', 'woothemes' ),
						"id" => $shortname."_slider_magazine_entries",
						"std" => "3",
						"type" => "select",
						"options" => $other_entries_2);

	$options[] = array( "name" => __( 'Exclude Posts', 'woothemes' ),
						"desc" => __( 'Exclude the slider posts from the posts grid below slider.', 'woothemes' ),
						"id" => $shortname."_slider_magazine_exclude",
						"std" => "true",
						"type" => "checkbox");

	$options[] = array( "name" => __( 'Display The Post Titles', 'woothemes' ),
						"desc" => __( 'Show the post title in the "Posts" slider.', 'woothemes' ),
						"id" => $shortname."_slider_magazine_title",
						"std" => "true",
						'class' => 'collapsed',
						"type" => "checkbox");

	$options[] = array( "name" => __( 'Title Font Style', 'woothemes' ),
						"desc" => __( 'Select typography for title.', 'woothemes' ),
						"id" => $shortname."_slider_magazine_font_title",
						"std" => array('size' => '24','unit' => 'px', 'face' => 'Arial, sans-serif','style' => 'bold','color' => '#ffffff'),
						'class' => 'hidden last',
						"type" => "typography");

	$options[] = array( "name" => __( 'Display The Post Excerpts', 'woothemes' ),
						"desc" => __( 'Show the post excerpt in the "Posts" slider.', 'woothemes' ),
						"id" => $shortname."_slider_magazine_excerpt",
						"std" => "true",
						'class' => 'collapsed',
						"type" => "checkbox");

	$options[] = array( "name" => __( 'Excerpt Font Style', 'woothemes' ),
						"desc" => __( 'Select typography for excerpt text.', 'woothemes' ),
						"id" => $shortname."_slider_magazine_font_excerpt",
						"std" => array('size' => '13','unit' => 'px', 'face' => 'Arial, sans-serif','style' => 'thin','color' => '#cccccc'),
						'class' => 'hidden',
						"type" => "typography");

	$options[] = array( "name" => __( 'Excerpt Length', 'woothemes' ),
						"desc" => __( 'Total number of words to show in the excerpt.', 'woothemes' ),
						"id" => $shortname."_slider_magazine_excerpt_length",
						"std" => "15",
						'class' => 'hidden last',
						"type" => "text");

	$options[] = array( "name" => __( 'Slider Height', 'woothemes' ),
						"desc" => __( 'Set a manual height for the slider e.g. 250. This setting is ignored when the "Auto Height" option is enabled under "Slider Settings". The image itself will only be cropped when the width of the image is the width of your website.', 'woothemes' ),
						"id" => $shortname."_slider_magazine_height",
						"std" => "300",
						"type" => "text");

	$options[] = array( 'name' => __( 'Featured Posts', 'woothemes' ),
					'type' => 'subheading' );

	$options[] = array( "name" => __( 'Number Of Featured Posts', 'woothemes' ),
						"desc" => __( 'Select how many featured (full width) posts you would like to show before your two-column posts. Set total number of posts in Settings > Reading.', 'woothemes' ),
						"id" => $shortname."_magazine_feat_posts",
						"type" => "select",
						"options" => $other_entries);

	$options[] = array( "name" => __( 'Featured Image Dimensions', 'woothemes' ),
						"desc" => __( 'Enter an integer value i.e. 250 for the image size.', 'woothemes' ),
						"id" => $shortname."_image_dimensions",
						"std" => "",
						"type" => array(
										array(  'id' => $shortname. '_magazine_f_w',
												'type' => 'text',
												'std' => 100,
												'meta' => __( 'Width', 'woothemes' ) ),
										array(  'id' => $shortname. '_magazine_f_h',
												'type' => 'text',
												'std' => 100,
												'meta' => __( 'Height', 'woothemes' ) )
									  ));

	$options[] = array( "name" => __( 'Featured Post Image Alignment', 'woothemes' ),
						"desc" => __( 'Select how to align your featured post images.', 'woothemes' ),
						"id" => $shortname."_magazine_f_align",
						"std" => "alignleft",
						"type" => "radio",
						"options" => array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center"));

	$options[] = array( "name" => __( 'Post Content for "Featured" Posts', 'woothemes' ),
					"desc" => __( 'Select if you want to show the full content or the excerpt on posts in the "Featured" section.', 'woothemes' ),
					"id" => $shortname."_magazine_featured_post_content",
					"std" => 'excerpt',
					"type" => "select2",
					"options" => array("excerpt" => __( 'The Excerpt', 'woothemes' ), "content" => __( 'Full Content', 'woothemes' ) ) );

	$options[] = array( 'name' => __( 'Posts Grid', 'woothemes' ),
					'type' => 'subheading' );

	$options[] = array( "name" => __( 'Normal Post Image Dimensions', 'woothemes' ),
						"desc" => __( 'Enter an integer value i.e. 250 for the image size.', 'woothemes' ),
						"id" => $shortname."_image_dimensions",
						"std" => "",
						"type" => array(
										array(  'id' => $shortname. '_magazine_b_w',
												'type' => 'text',
												'std' => 100,
												'meta' => __( 'Width', 'woothemes' ) ),
										array(  'id' => $shortname. '_magazine_b_h',
												'type' => 'text',
												'std' => 100,
												'meta' => __( 'Height', 'woothemes' ) )
									  ));

	$options[] = array( "name" => __( 'Normal Post Image Alignment', 'woothemes' ),
						"desc" => __( 'Select how to align your normal post images.', 'woothemes' ),
						"id" => $shortname."_magazine_b_align",
						"std" => "alignleft",
						"type" => "radio",
						"options" => array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center"));

	$options[] = array( "name" => __( 'Post Content for "Grid" Posts', 'woothemes' ),
					"desc" => __( 'Select if you want to show the full content or the excerpt on posts in the "Grid" section.', 'woothemes' ),
					"id" => $shortname."_magazine_grid_post_content",
					"std" => 'excerpt',
					"type" => "select2",
					"options" => array("excerpt" => __( 'The Excerpt', 'woothemes' ), "content" => __( 'Full Content', 'woothemes' ) ) );

/* Template: Business */
	$options[] = array( "name" => __( 'Business Template', 'woothemes' ),
						"icon" => "layout",
						"type" => "heading");

	$options[] = array( 'name' => __( '"Business" Setup', 'woothemes' ),
					'type' => 'subheading' );

	$options[] = array( "name" => __( 'Business Page Template', 'woothemes' ),
						"desc" => "",
						"id" => $shortname."_woo_biz_notice",
						"std" => sprintf( __( 'Below you can control settings for the Business page template. Please refer to <a href="%s">documentation</a> on how to setup the page template. You can add slider posts with the <strong><em>Slides</em></strong> custom post type.' ), 'http://www.woothemes.com/support/theme-documentation/canvas/' ),
						"type" => "info");

	$options[] = array( "name" => __( 'Disable Footer Widgets', 'woothemes' ),
							"desc" => __( 'Disable the footer widgets on this template.', 'woothemes' ),
							"id" => $shortname."_biz_disable_footer_widgets",
							"std" => "true",
							"type" => "checkbox");

	$options[] = array( "name" => __( 'Disable Slides Admin Menu', 'woothemes' ),
					"desc" => __( 'Disable the slides admin menu functionality.', 'woothemes' ),
					"id" => $shortname."_biz_slides_disable",
					"std" => "false",
					"type" => "checkbox");

	$options[] = array( 'name' => __( 'Featured Slider', 'woothemes' ),
					'type' => 'subheading' );

	$options[] = array( "name" => __( 'Featured Slider', 'woothemes' ),
						"desc" => __( 'Enable the featured slider.', 'woothemes' ),
						"id" => $shortname."_slider_biz",
						"std" => "false",
						"type" => "checkbox");

	$options[] = array( "name" => __( 'Number of Slides', 'woothemes' ),
						"desc" => __( 'Select how many slides you would like to show in the slider.', 'woothemes' ),
						"id" => $shortname."_slider_biz_number",
						"std" => "10",
						"type" => "select",
						"options" => $other_entries_2);

	$options[] = array( "name" => __( 'Display Order', 'woothemes' ),
						"desc" => __( 'Select the order in which you want to show your slides.', 'woothemes' ),
						"id" => $shortname."_slider_biz_order",
						"type" => "select2",
						"std" => "DESC",
						"options" => array("DESC" => __( 'Newest first', 'woothemes' ), "ASC" => __( 'Oldest first', 'woothemes' ) ) );

	$options[] = array( "name" => __( 'Featured Slider Title', 'woothemes' ),
						"desc" => __( 'Show the page title in slider when using Featured Image as background image.', 'woothemes' ),
						"id" => $shortname."_slider_biz_title",
						"std" => "true",
						'class' => 'collapsed',
						"type" => "checkbox");

	$options[] = array( "name" => __( 'Featured Slider Title Font Style', 'woothemes' ),
						"desc" => __( 'Select typography for title (when using image background).', 'woothemes' ),
						"id" => $shortname."_slider_biz_font_title",
						"std" => array('size' => '24','unit' => 'px', 'face' => 'Arial, sans-serif','style' => 'bold','color' => '#ffffff'),
						'class' => 'hidden last',
						"type" => "typography");

	$options[] = array( "name" => __( 'Featured Slider Content Font Style', 'woothemes' ),
						"desc" => __( 'Select typography for content text (when using image background).', 'woothemes' ),
						"id" => $shortname."_slider_biz_font_excerpt",
						"std" => array('size' => '13','unit' => 'px', 'face' => 'Arial, sans-serif','style' => 'thin','color' => '#cccccc'),
						"type" => "typography");

	$options[] = array( "name" => __( 'Featured Slider Height', 'woothemes' ),
						"desc" => __( 'Set a manual height for the slider e.g. 250. Default height is 350px.', 'woothemes' ),
						"id" => $shortname."_slider_biz_height",
						"std" => "",
						"type" => "text");

/* Slider Settings */
	$options[] = array( "name" => __( 'Slider Settings', 'woothemes' ),
						"icon" => "slider",
						"type" => "heading");

	$options[] = array( "name" => __( 'Slider Settings', 'woothemes' ),
						"desc" => "",
						"id" => $shortname."_woo_slider_notice",
						"std" => __( 'Below you can control the generic slider settings which will apply to both Business and Magazine templates.', 'woothemes' ),
						"type" => "info");

	$options[] = array( "name" => __( 'Auto Start', 'woothemes' ),
						"desc" => __( 'Set the slider to start sliding automatically. Adjust the speed of sliding underneath.', 'woothemes' ),
						"id" => $shortname."_slider_auto",
						"std" => "true",
						"type" => "checkbox");

	$options[] = array( "name" => __( 'Auto Height', 'woothemes' ),
						"desc" => __( 'Set the slider to adjust automatically depending on the height of the current slide contents.', 'woothemes' ),
						"id" => $shortname."_slider_autoheight",
						"std" => "true",
						"type" => "checkbox");

	$options[] = array( "name" => __( 'Hover Pause', 'woothemes' ),
                    	"desc" => __( 'Hovering over slideshow will pause it', 'woothemes' ),
                    	"id" => $shortname."_slider_hover",
                    	"std" => "false",
                    	"type" => "checkbox");

	$options[] = array( "name" => __( 'Animation Speed', 'woothemes' ),
						"desc" => __( 'The time in <b>seconds</b> the animation between frames will take e.g. 0.6', 'woothemes' ),
						"id" => $shortname."_slider_speed",
						"std" => 0.6,
						"type" => "select",
						"options" => array( '0.0', '0.1', '0.2', '0.3', '0.4', '0.5', '0.6', '0.7', '0.8', '0.9', '1.0', '1.1', '1.2', '1.3', '1.4', '1.5', '1.6', '1.7', '1.8', '1.9', '2.0' ) );

	$options[] = array( "name" => __( 'Auto Slide Interval', 'woothemes' ),
						"desc" => __( 'The time in <b>seconds</b> each slide pauses for, before sliding to the next. Only when using Auto Start option above.', 'woothemes' ),
						"id" => $shortname."_slider_interval",
						"std" => "4",
						"type" => "select",
						"options" => array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10' ) );

	$options[] = array( "name" => __( 'Features Slider Effect', 'woothemes' ),
						"desc" => __( 'Select the effect used when transitioning between posts (default: <strong>slide</strong>).', 'woothemes' ),
						"id" => $shortname."_slider_effect",
						"type" => "select2",
						"std" => "slide",
						"options" => array("slide" => __( 'Slide', 'woothemes' ), "fade" => __( 'Fade', 'woothemes' ) ) );

	$options[] = array( "name" => __( 'Slider Pagination', 'woothemes' ),
						"desc" => __( 'Enable/disable the display of pagination in the sliders.', 'woothemes' ),
						"id" => $shortname."_slider_pagination",
						"std" => "false",
						"type" => "checkbox");

if ( is_woocommerce_activated() ) {

	$options[] = array( "name" => __( 'WooCommerce', 'woothemes' ),
                   	 	"icon" => "woocommerce",
						"type" => "heading");

	$options[] = array( "name" => __( 'Search scope', 'woothemes' ),
						"desc" => __( 'Select whether you want the search in the header to search for products or posts', 'woothemes' ),
						"id" => $shortname."_header_search_scope",
						"type" => "select2",
						"options" => array( 'products' => __( 'Products', 'woothemes' ), 'posts' => __( 'Posts', 'woothemes' ) ) );

	$options[] = array( 'name' => __( 'Custom Placeholder', 'woothemes' ),
                        'desc' => __( 'Upload a custom placeholder to be displayed when there is no product image.', 'woothemes' ),
                        'id' => $shortname . '_placeholder_url',
                        'std' => '',
                        'type' => 'upload' );

    $options[] = array( 'name' => __( 'Header Cart Link', 'woothemes' ),
                        'desc' => __( 'Display a link to the cart in the main navigation', 'woothemes' ),
                        'id' => $shortname.'_header_cart_link',
                        'std' => 'true',
                        'type' => 'checkbox' );

    $options[] = array( 'name' => __( 'Header Cart Totals', 'woothemes' ),
                        'desc' => __( 'Display item and amount totals in the cart in the main navigation', 'woothemes' ),
                        'id' => $shortname.'_header_cart_total',
                        'std' => 'false',
                        'type' => 'checkbox' );
}

/* Testimonials */

$options[] = array( "name" => __( 'Components', 'woothemes' ),
                    "icon" => "misc",
					"type" => "heading");

/* Portfolio */

$options[] = array( 'name' => __( 'Portfolio', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'Portfolio Manager', 'woothemes' ),
					"desc" => "",
					"id" => $shortname."_portfolio_notice",
					"std" => sprintf( __( 'Below you can setup and enable/disable the portfolio settings. When modifying the portfolio settings, please visit the <a href="%s">Settings- Permalinks</a> screen to refresh your WordPress URLs.', 'woothemes' ), admin_url( 'options-permalink.php' ) ),
					"type" => "info");

$options[] = array( "name" => __( 'Disable Portfolio', 'woothemes' ),
					"desc" => __( 'Disable the portfolio functionality.', 'woothemes' ),
					"id" => $shortname."_portfolio_disable",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => __( 'Enable Single Portfolio Gallery', 'woothemes' ),
					"desc" => __( 'Enable the gallery feature in the single portfolio page layout.', 'woothemes' ),
					"id" => $shortname."_portfolio_gallery",
					"std" => "true",
					"type" => "checkbox");

$options[] = array( "name" => __( 'Portfolio Items URL Base', 'woothemes' ),
						"desc" => sprintf( __( 'The base of all portfolio item URLs (visit the %s screen after changing this setting).', 'woothemes' ), '<a href="' . admin_url( 'options-permalink.php' ) . '">' . __( 'Settings- Permalinks', 'woothemes' ) . '</a>' ),
						"id" => $shortname."_portfolioitems_rewrite",
						"std" => "portfolio-items",
						"type" => "text");

$options[] = array( "name" => __( 'Exclude Galleries from the Portfolio Navigation', 'woothemes' ),
						"desc" => __( 'Optionally exclude portfolio galleries from the portfolio gallery navigation switcher. Place the gallery slugs here, separated by commas <br />(eg: one,two,three)', 'woothemes' ),
						"id" => $shortname."_portfolio_excludenav",
						"std" => "",
						"type" => "text");

$options[] = array( "name" => __( 'Portfolio Thumbnail Dimensions', 'woothemes' ),
						"desc" => __( 'Enter an integer value i.e. 250 for the image size.', 'woothemes' ),
						"id" => $shortname."_portfolio_thumb_dimensions",
						"std" => "",
						"type" => array(
										array(  'id' => $shortname. '_portfolio_thumb_width',
												'type' => 'text',
												'std' => 210,
												'meta' => __( 'Width', 'woothemes' ) ),
										array(  'id' => $shortname. '_portfolio_thumb_height',
												'type' => 'text',
												'std' => 157,
												'meta' => __( 'Height', 'woothemes' ) )
									  ));

$options[] = array( "name" => __( 'Portfolio Galleries Page Layout', 'woothemes' ),
						"desc" => __( 'Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.', 'woothemes' ),
						"id" => $shortname . "_portfolio_layout",
						"std" => "one-col",
						"type" => "images",
						"options" => array(
							'one-col' => $images_dir . '1c.png',
							'two-col-left' => $images_dir . '2cl.png',
							'two-col-right' => $images_dir . '2cr.png',
							'three-col-left' => $images_dir . '3cl.png',
							'three-col-middle' => $images_dir . '3cm.png',
							'three-col-right' => $images_dir . '3cr.png')
						);

$options[] = array( "name" => __( 'Exclude Portfolio Items from Search Results', 'woothemes' ),
					"desc" => __( 'Exclude portfolio items from results when searching your website.', 'woothemes' ),
					"id" => $shortname."_portfolio_excludesearch",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => __( 'Portfolio Items Link To', 'woothemes' ),
                    "desc" => __( 'Do the portfolio items link to the lightbox, or to the single portfolio item screen?', 'woothemes' ),
                    "id" => $shortname."_portfolio_linkto",
                    "std" => "lightbox",
					"type" => "select2",
					"options" => array( 'lightbox' => __( 'Lightbox', 'woothemes' ), 'post' => __( 'Portfolio Item', 'woothemes' ) ) );

/* Feedback */

$options[] = array( 'name' => __( 'Feedback', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'Disable Feedback Manager', 'woothemes' ),
					"desc" => __( 'Disable the feedback functionality.', 'woothemes' ),
					"id" => $shortname."_feedback_disable",
					"std" => "false",
					"type" => "checkbox");

/* Dynamic Images */
$options[] = array( "name" => __( 'Dynamic Images', 'woothemes' ),
					"icon" => "image",
				    "type" => "heading");

$options[] = array( 'name' => __( 'Resizer Settings', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'Dynamic Image Resizing', 'woothemes' ),
					"desc" => "",
					"id" => $shortname."_wpthumb_notice",
					"std" => __( 'There are two alternative methods of dynamically resizing the thumbnails in the theme, <strong>WP Post Thumbnail</strong> (default) or <strong>TimThumb</strong>.', 'woothemes' ),
					"type" => "info");

$options[] = array( "name" => __( 'WP Post Thumbnail', 'woothemes' ),
					"desc" => __( 'Use WordPress post thumbnail to assign a post thumbnail. Will enable the <strong>Featured Image panel</strong> in your post sidebar where you can assign a post thumbnail.', 'woothemes' ),
					"id" => $shortname."_post_image_support",
					"std" => "true",
					"class" => "collapsed",
					"type" => "checkbox" );

$options[] = array( "name" => __( 'WP Post Thumbnail - Dynamic Image Resizing', 'woothemes' ),
					"desc" => __( 'The post thumbnail will be dynamically resized using native WP resize functionality. <em>(Requires PHP 5.2+)</em>', 'woothemes' ),
					"id" => $shortname."_pis_resize",
					"std" => "true",
					"class" => "hidden",
					"type" => "checkbox" );

$options[] = array( "name" => __( 'WP Post Thumbnail - Hard Crop', 'woothemes' ),
					"desc" => __( 'The post thumbnail will be cropped to match the target aspect ratio (only used if <em>Dynamic Image Resizing</em> is enabled).', 'woothemes' ),
					"id" => $shortname."_pis_hard_crop",
					"std" => "true",
					"class" => "hidden last",
					"type" => "checkbox" );

$options[] = array( "name" => __( 'TimThumb', 'woothemes' ),
					"desc" => __( 'This will enable the <a href="http://code.google.com/p/timthumb/">TimThumb</a> (thumb.php) script which dynamically resizes images added through the <strong>custom settings panel</strong>  below the post editor. Make sure your themes <em>cache</em> folder is writable.', 'woothemes' ),
					"id" => $shortname."_resize",
					"std" => "false",
					"type" => "checkbox" );

$options[] = array( "name" => __( 'Automatic Image Thumbnail', 'woothemes' ),
					"desc" => __( 'If no thumbnail is specified then the first uploaded image in the post is used.', 'woothemes' ),
					"id" => $shortname."_auto_img",
					"std" => "false",
					"type" => "checkbox" );

$options[] = array( 'name' => __( 'Thumbnail Settings', 'woothemes' ),
					'type' => 'subheading' );

$options[] = array( "name" => __( 'Thumbnail Dimensions', 'woothemes' ),
					"desc" => __( 'Enter an integer value i.e. 250 for the desired size which will be used when dynamically creating the images.', 'woothemes' ),
					"id" => $shortname."_image_dimensions",
					"std" => "",
					"type" => array(
									array(  'id' => $shortname. '_thumb_w',
											'type' => 'text',
											'std' => "100",
											'meta' => __( 'Width', 'woothemes' ) ),
									array(  'id' => $shortname. '_thumb_h',
											'type' => 'text',
											'std' => 100,
											'meta' => __( 'Height', 'woothemes' ) )
								  ));

$options[] = array( "name" => __( 'Thumbnail Alignment', 'woothemes' ),
					"desc" => __( 'Select how to align your thumbnails with posts.', 'woothemes' ),
					"id" => $shortname."_thumb_align",
					"std" => "alignleft",
					"type" => "radio",
					"options" => array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center"));

$options[] = array( "name" => __( 'Single Post - Show Thumbnail', 'woothemes' ),
					"desc" => __( 'Show the thumbnail in the single post page.', 'woothemes' ),
					"id" => $shortname."_thumb_single",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => __( 'Single Post - Thumbnail Dimensions', 'woothemes' ),
					"desc" => __( 'Enter an integer value i.e. 250 for the image size.', 'woothemes' ),
					"id" => $shortname."_image_dimensions",
					"std" => "",
					"type" => array(
									array(  'id' => $shortname. '_single_w',
											'type' => 'text',
											'std' => 200,
											'meta' => __( 'Width', 'woothemes' ) ),
									array(  'id' => $shortname. '_single_h',
											'type' => 'text',
											'std' => 200,
											'meta' => __( 'Height', 'woothemes' ) )
								  ));

$options[] = array( "name" => __( 'Single Post - Thumbnail Alignment', 'woothemes' ),
					"desc" => __( 'Select how to align your thumbnails with single posts.', 'woothemes' ),
					"id" => $shortname."_thumb_align_single",
					"std" => "alignright",
					"type" => "radio",
					"options" => array( "alignleft" => "Left","alignright" => "Right","aligncenter" => "Center" ) );


$options[] = array( "name" => __( 'Add Featured Image to RSS feed', 'woothemes' ),
					"desc" => __( 'Add the featured image to your RSS feed', 'woothemes' ),
					"id" => $shortname."_rss_thumb",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => __( 'Enable Lightbox', 'woothemes' ),
					"desc" => __( 'Enable the PrettyPhoto lighbox script on images within your website\'s content.', 'woothemes' ),
					"id" => $shortname."_enable_lightbox",
					"std" => "false",
					"type" => "checkbox" );

/* Tumblog Settings - will be depreceated. Hide if not already active. */
if ( get_option( $shortname . '_woo_tumblog_switch' ) == "true" ) {

	$options[] = array( "name" => __( 'Tumblog', 'woothemes' ),
						"icon" => "tumblog",
						"type" => "heading");

	$options[] = array( 'name' => __( 'Tumblog Setup', 'woothemes' ),
					'type' => 'subheading' );

	$options[] = array( "name" => __( 'Tumblog Functionality', 'woothemes' ),
						"desc" => "",
						"id" => $shortname."_woo_tumblog_notice",
						"std" => sprintf( __( 'Tumblog will allow you to publish content using the WooTumblog functionality, including the Express for WordPress iPhone App. If you would like to use the iPhone app, you will need to enable XML-RPC publishing under Settings->Writing. Find out more at %s.', 'woothemes' ), '<a href="http://express-app.com/" target="_blank">Express-App.com</a>' ),
						"type" => "info");

	$options[] = array( "name" => __( 'Enable Tumblog Functionality', 'woothemes' ),
						"desc" => __( 'Enable Tumblog functionality in Canvas.', 'woothemes' ),
						"id" => $shortname."_woo_tumblog_switch",
						"std" => "false",
						"type" => "checkbox");

	$content_option_array = array( 	'taxonomy' 	=> __( 'Taxonomy', 'woothemes' ),
								'post_format' => __( 'Post Formats', 'woothemes' )
									);

	$options[] = array( "name" => __( 'Tumblog Content Method', 'woothemes' ),
					"desc" => __( 'Select if you would like to use a Taxonomy of Post Formats to categorize your Tumblog content.', 'woothemes' ),
					"id" => $shortname."_tumblog_content_method",
					"std" => "post_format",
					"type" => "select2",
					"options" => $content_option_array);

	$options[] = array( "name" => __( 'Use Custom Tumblog RSS Feed', 'woothemes' ),
						"desc" => __( 'Replaces the default WordPress RSS feed output with Tumblog RSS output.', 'woothemes' ),
						"id" => $shortname."_custom_rss",
						"std" => "true",
						"type" => "checkbox");

	$options[] = array( "name" => __( 'Tumblog Media Widths on the "Magazine" Page Template\'s Grid', 'woothemes' ),
						"desc" => __( 'The output width for Tumblog media (images, videos, audio) on the "Magazine" page template\'s grid.', 'woothemes' ),
						"id" => $shortname."_tumblog_magazine_media_width",
						"std" => "300",
						"type" => "text");

	$options[] = array( 'name' => __( 'Content Formats', 'woothemes' ),
					'type' => 'subheading' );

	$options[] = array( "name" => __( 'Images Link to', 'woothemes' ),
						"desc" => __( 'Select where your Tumblog Images will link to when clicked.', 'woothemes' ),
						"id" => $shortname."_image_link_to",
						"std" => "post",
						"type" => "radio",
						"options" => $options_image_link_to);

	$options[] = array( "name" => __( 'Tumblog Images Width', 'woothemes' ),
						"desc" => __( 'The output width for Tumblog image post images.', 'woothemes' ),
						"id" => $shortname."_tumblog_image_width",
						"std" => "610",
						"type" => "text");

	$options[] = array( "name" => __( 'Tumblog Content Position: Images', 'woothemes' ),
						"desc" => __( 'Select where you would like the Tumblog Specific content to be output around the standard content.', 'woothemes' ),
						"id" => $shortname."_woo_tumblog_images_content",
						"std" => 'Before',
						"type" => "select",
						"options" => $tumblog_options);

	$options[] = array( "name" => __( 'Tumblog Audio Width', 'woothemes' ),
						"desc" => __( 'The output width for Tumblog Audio player.', 'woothemes' ),
						"id" => $shortname."_tumblog_audio_width",
						"std" => "440",
						"type" => "text");

	$options[] = array( "name" => __( 'Tumblog Content Position: Audio', 'woothemes' ),
						"desc" => __( 'Select where you would like the Tumblog Specific content to be output around the standard content.', 'woothemes' ),
						"id" => $shortname."_woo_tumblog_audio_content",
						"std" => 'Before',
						"type" => "select",
						"options" => $tumblog_options);

	$options[] = array( "name" => __( 'Tumblog Video Width', 'woothemes' ),
						"desc" => __( 'The output width for Tumblog Videos.', 'woothemes' ),
						"id" => $shortname."_tumblog_video_width",
						"std" => "610",
						"type" => "text");

	$options[] = array( "name" => __( 'Tumblog Content Position: Video', 'woothemes' ),
						"desc" => __( 'Select where you would like the Tumblog Specific content to be output around the standard content.', 'woothemes' ),
						"id" => $shortname."_woo_tumblog_videos_content",
						"std" => "Before",
						"type" => "select",
						"options" => $tumblog_options);

	$options[] = array( "name" => __( 'Tumblog Content Position: Quotes', 'woothemes' ),
						"desc" => __( 'Select where you would like the Tumblog Specific content to be output around the standard content.', 'woothemes' ),
						"id" => $shortname."_woo_tumblog_quotes_content",
						"std" => "Before",
						"type" => "select",
						"options" => $tumblog_options);

} // ENDIF Tumblog section

/* Subscribe & Connect */

	$options[] = array( "name" => __( 'Subscribe & Connect', 'woothemes' ),
						"type" => "heading",
						"icon" => "connect");

	$options[] = array( 'name' => __( 'S&C Setup', 'woothemes' ),
					'type' => 'subheading' );

	$options[] = array( "name" => __( 'Enable Subscribe & Connect - Single Post', 'woothemes' ),
						"desc" => sprintf( __( 'Enable the subscribe & connect area on single posts. You can also add this as a <a href="%s">widget</a> in your sidebar.', 'woothemes' ), admin_url( 'widgets.php' ) ),
						"id" => $shortname."_connect",
						"std" => 'true',
						"type" => "checkbox");

	$options[] = array( "name" => __( 'Subscribe Title', 'woothemes' ),
						"desc" => __( 'Enter the title to show in your subscribe & connect area.', 'woothemes' ),
						"id" => $shortname."_connect_title",
						"std" => '',
						"type" => "text");

	$options[] = array( "name" => __( 'Text', 'woothemes' ),
						"desc" => __( 'Change the default text in this area.', 'woothemes' ),
						"id" => $shortname."_connect_content",
						"std" => '',
						"type" => "textarea");

	$options[] = array( "name" => __( 'Enable Related Posts', 'woothemes' ),
						"desc" => __( 'Enable related posts in the subscribe area. Uses posts with the same <strong>tags</strong> to find related posts. Note: Will not show in the Subscribe widget.', 'woothemes' ),
						"id" => $shortname."_connect_related",
						"std" => 'true',
						"type" => "checkbox");

	$options[] = array( 'name' => __( 'Subscribe', 'woothemes' ),
					'type' => 'subheading' );

	$options[] = array( "name" => __( 'Subscribe By E-mail ID (Feedburner)', 'woothemes' ),
						"desc" => sprintf( __( 'Enter your <a href="%s">Feedburner ID</a> for the e-mail subscription form.', 'woothemes' ), 'http://www.woothemes.com/tutorials/how-to-find-your-feedburner-id-for-email-subscription/' ),
						"id" => $shortname."_connect_newsletter_id",
						"std" => '',
						"type" => "text");

	$options[] = array( "name" => __( 'Subscribe By E-mail to MailChimp', 'woothemes' ),
						"desc" => sprintf( __( 'If you have a MailChimp account you can enter the <a href="%s" target="_blank">MailChimp List Subscribe URL</a> to allow your users to subscribe to a MailChimp List.', 'woothemes' ), 'http://woochimp.heroku.com' ),
						"id" => $shortname."_connect_mailchimp_list_url",
						"std" => '',
						"type" => "text");

	$options[] = array( 'name' => __( 'Connect', 'woothemes' ),
					'type' => 'subheading' );

	$options[] = array( "name" => __( 'Enable RSS', 'woothemes' ),
						"desc" => __( 'Enable the subscribe and RSS icon.', 'woothemes' ),
						"id" => $shortname."_connect_rss",
						"std" => 'true',
						"type" => "checkbox");

	$options[] = array( "name" => __( 'Twitter URL', 'woothemes' ),
						"desc" => __( 'Enter your  <a href="http://www.twitter.com/">Twitter</a> URL e.g. http://www.twitter.com/woothemes', 'woothemes' ),
						"id" => $shortname."_connect_twitter",
						"std" => '',
						"type" => "text");

	$options[] = array( "name" => __( 'Facebook URL', 'woothemes' ),
						"desc" => __( 'Enter your  <a href="http://www.facebook.com/">Facebook</a> URL e.g. http://www.facebook.com/woothemes', 'woothemes' ),
						"id" => $shortname."_connect_facebook",
						"std" => '',
						"type" => "text");

	$options[] = array( "name" => __( 'YouTube URL', 'woothemes' ),
						"desc" => __( 'Enter your  <a href="http://www.youtube.com/">YouTube</a> URL e.g. http://www.youtube.com/woothemes', 'woothemes' ),
						"id" => $shortname."_connect_youtube",
						"std" => '',
						"type" => "text");

	$options[] = array( "name" => __( 'Flickr URL', 'woothemes' ),
						"desc" => __( 'Enter your  <a href="http://www.flickr.com/">Flickr</a> URL e.g. http://www.flickr.com/woothemes', 'woothemes' ),
						"id" => $shortname."_connect_flickr",
						"std" => '',
						"type" => "text");

	$options[] = array( "name" => __( 'LinkedIn URL', 'woothemes' ),
						"desc" => __( 'Enter your  <a href="http://www.www.linkedin.com.com/">LinkedIn</a> URL e.g. http://www.linkedin.com/in/woothemes', 'woothemes' ),
						"id" => $shortname."_connect_linkedin",
						"std" => '',
						"type" => "text");

	$options[] = array( "name" => __( 'Delicious URL', 'woothemes' ),
						"desc" => __( 'Enter your <a href="http://www.delicious.com/">Delicious</a> URL e.g. http://www.delicious.com/woothemes', 'woothemes' ),
						"id" => $shortname."_connect_delicious",
						"std" => '',
						"type" => "text");

	$options[] = array( "name" => __( 'Google+ URL', 'woothemes' ),
						"desc" => __( 'Enter your <a href="http://plus.google.com/">Google+</a> URL e.g. https://plus.google.com/104560124403688998123/', 'woothemes' ),
						"id" => $shortname."_connect_googleplus",
						"std" => '',
						"type" => "text" );

	$options[] = array( 'name' => __( 'Dribbble', 'woothemes' ),
	    				'desc' => sprintf( __( 'Enter your %1$s URL e.g. http://dribbble.com/woothemes', 'woothemes' ), '<a href="http://dribbble.com/">'.__( 'Dribbble', 'woothemes' ).'</a>' ),
	    				'id' => $shortname . '_connect_dribbble',
	    				'std' => '',
	    				'type' => 'text' );
	
	$options[] = array( 'name' => __( 'Instagram', 'woothemes' ),
	    				'desc' => sprintf( __( 'Enter your %1$s URL e.g. http://instagram.com/woothemes', 'woothemes' ), '<a href="http://instagram.com">'.__( 'Instagram', 'woothemes' ).'</a>' ),
	    				'id' => $shortname . '_connect_instagram',
	    				'std' => '',
	    				'type' => 'text' );
	
	$options[] = array( 'name' => __( 'Vimeo', 'woothemes' ),
	    				'desc' => sprintf( __( 'Enter your %1$s URL e.g. http://vimeo.com/woothemes', 'woothemes' ), '<a href="http://vimeo.com/">'.__( 'Vimeo', 'woothemes' ).'</a>' ),
	    				'id' => $shortname . '_connect_vimeo',
	    				'std' => '',
	    				'type' => 'text' );
	
	$options[] = array( 'name' => __( 'Pinterest', 'woothemes' ),
	    				'desc' => sprintf( __( 'Enter your %1$s URL e.g. http://pinterest.com/woothemes', 'woothemes' ), '<a href="http://pinterest.com/">'.__( 'Pinterest', 'woothemes' ).'</a>' ),
	    				'id' => $shortname . '_connect_pinterest',
	    				'std' => '',
	    				'type' => 'text' );


/* Contact Template Settings */

$options[] = array( 'name' => __( 'Contact Page', 'woothemes' ),
					'icon' => 'maps',
				    'type' => 'heading');

$options[] = array( 'name' => __( 'Contact Information', 'woothemes' ),
					'type' => 'subheading');

$options[] = array( "name" => __( 'Contact Information Panel', 'woothemes' ),
					"desc" => __( 'Enable the contact information panel on your contact page template.', 'woothemes' ),
					"id" => $shortname."_contact_panel",
					"std" => "false",
					"class" => 'collapsed',
					"type" => "checkbox" );

$options[] = array( 'name' => __( 'Location Name', 'woothemes' ),
					'desc' => __( 'Enter the location name. Example: London Office', 'woothemes' ),
					'id' => $shortname . '_contact_title',
					'std' => '',
					'class' => 'hidden',
					'type' => 'text' );

$options[] = array( 'name' => __( 'Location Address', 'woothemes' ),
					'desc' => __( 'Enter your company\'s address', 'woothemes' ),
					'id' => $shortname . '_contact_address',
					'std' => '',
					'class' => 'hidden',
					'type' => "textarea" );

$options[] = array( 'name' => __( 'Telephone', 'woothemes' ),
					'desc' => __( 'Enter your telephone number', 'woothemes' ),
					'id' => $shortname . '_contact_number',
					'std' => '',
					'class' => 'hidden',
					'type' => 'text' );

$options[] = array( 'name' => __( 'Fax', 'woothemes' ),
					'desc' => __( 'Enter your fax number', 'woothemes' ),
					'id' => $shortname . '_contact_fax',
					'std' => '',
					'class' => 'hidden last',
					'type' => 'text' );

$options[] = array( "name" => __( 'Contact Form E-Mail', 'woothemes' ),
					"desc" => __( 'Enter your E-mail address to use on the "Contact Form" page Template.', 'woothemes' ),
					"id" => $shortname."_contactform_email",
					"std" => "",
					"type" => "text" );

$options[] = array( 'name' => __( 'Your Twitter username', 'woothemes' ),
					'desc' => __( 'Enter your Twitter username. Example: woothemes', 'woothemes' ),
					'id' => $shortname . '_contact_twitter',
					'std' => '',
					'type' => 'text' );

$options[] = array( "name" => __( 'Enable Subscribe and Connect', 'woothemes' ),
					"desc" => __( 'Enable the subscribe and connect functionality on the contact page template', 'woothemes' ),
					"id" => $shortname."_contact_subscribe_and_connect",
					"std" => "false",
					"type" => "checkbox" );

$options[] = array( 'name' => __( 'Maps', 'woothemes' ),
					'type' => 'subheading');

$options[] = array( 'name' => __( 'Contact Form Google Maps Coordinates', 'woothemes' ),
					'desc' => sprintf( __( 'Enter your Google Map coordinates to display a map on the Contact Form page template and a link to it on the Contact Us widget. You can get these details from %sGoogle Maps%s', 'woothemes' ), '<a href="' . esc_url( 'http://itouchmap.com/latlong.html' ) . '" target="_blank">', '</a>' ),
					'id' => $shortname . '_contactform_map_coords',
					'std' => '',
					'type' => 'text' );

$options[] = array( 'name' => __( 'Disable Mousescroll', 'woothemes' ),
					'desc' => __( 'Turn off the mouse scroll action for all the Google Maps on the site. This could improve usability on your site.', 'woothemes' ),
					'id' => $shortname . '_maps_scroll',
					'std' => '',
					'type' => 'checkbox');

$options[] = array( 'name' => __( 'Map Height', 'woothemes' ),
					'desc' => __( 'Height in pixels for the maps displayed on Single.php pages.', 'woothemes' ),
					'id' => $shortname . '_maps_single_height',
					'std' => "250",
					'type' => 'text');

$options[] = array( 'name' => __( 'Default Map Zoom Level', 'woothemes' ),
					'desc' => __( 'Set this to adjust the default in the post & page edit backend.', 'woothemes' ),
					'id' => $shortname . '_maps_default_mapzoom',
					'std' => "9",
					'type' => 'select2',
					'options' => $other_entries);

$options[] = array( 'name' => __( 'Default Map Type', 'woothemes' ),
					'desc' => __( 'Set this to the default rendered in the post backend.', 'woothemes' ),
					'id' => $shortname . '_maps_default_maptype',
					'std' => 'G_NORMAL_MAP',
					'type' => 'select2',
					'options' => array( 'G_NORMAL_MAP' => __( 'Normal', 'woothemes' ), 'G_SATELLITE_MAP' => __( 'Satellite', 'woothemes' ), 'G_HYBRID_MAP' => __( 'Hybrid', 'woothemes' ), 'G_PHYSICAL_MAP' => __( 'Terrain', 'woothemes' ) ) );

$options[] = array( 'name' => __( 'Map Callout Text', 'woothemes' ),
					'desc' => __( 'Text or HTML that will be output when you click on the map marker for your location.', 'woothemes' ),
					'id' => $shortname . '_maps_callout_text',
					'std' => "",
					'type' => 'textarea');


// Add extra options through function
if ( function_exists("woo_options_add") )
	$options = woo_options_add($options);

if ( get_option('woo_template') != $options) update_option('woo_template',$options);
if ( get_option('woo_themename') != $themename) update_option('woo_themename',$themename);
if ( get_option('woo_shortname') != $shortname) update_option('woo_shortname',$shortname);
if ( get_option('woo_manual') != $manualurl) update_option('woo_manual',$manualurl);

// Woo Metabox Options
$woo_metaboxes = array();

if( get_post_type() == 'post' || !get_post_type() ){

	// TimThumb is enabled in options
	if ( get_option( 'woo_resize') == 'true' ) {

		$woo_metaboxes[] = array (	'name' => 'image',
									'label' => __( 'Image', 'woothemes' ),
									'type' => 'upload',
									'desc' => __( 'Upload an image or enter an URL.', 'woothemes' ) );

		$woo_metaboxes[] = array (	'name' => '_image_alignment',
									'std' => __( 'Center', 'woothemes' ),
									'label' => __( 'Image Crop Alignment', 'woothemes' ),
									'type' => 'select2',
									'desc' => __( 'Select crop alignment for resized image', 'woothemes' ),
									'options' => array(	'c' => 'Center',
														't' => 'Top',
														'b' => 'Bottom',
														'l' => 'Left',
														'r' => 'Right'));
	}

	$url =  get_template_directory_uri() . '/functions/images/';
	$woo_metaboxes[] = array (	"name" => "layout",
								"label" => __( 'Layout', 'woothemes' ),
								"type" => "images",
								"desc" => __( 'Select a specific layout for this post/page. Overrides default site layout.', 'woothemes' ),
								"options" => array(	'' => $url . 'layout-off.png',
													'one-col' => $url . '1c.png',
													'two-col-left' => $url . '2cl.png',
													'two-col-right' => $url . '2cr.png',
													'three-col-left' => $url . '3cl.png',
													'three-col-middle' => $url . '3cm.png',
													'three-col-right' => $url . '3cr.png'));

	$woo_metaboxes[] = array (	"name" => "embed",
								"label" => __( 'Embed', 'woothemes' ),
								"type" => "textarea",
								"desc" => __( 'Enter embed code for use on single posts and with the Video widget.', 'woothemes' ) );

	if (get_option('woo_woo_tumblog_switch') == 'true') {

		$woo_metaboxes[] = array (	"name" => "video-embed",
									"label" => __( 'Tumblog : Embed Code (Videos)', 'woothemes' ),
									"type" => "textarea",
									"desc" => __( 'Add embed code for video services like Youtube or Vimeo - Tumblog only.', 'woothemes' ) );

    	$woo_metaboxes[] = array (	"name" => "quote-author",
									"std" => "Unknown",
									"label" => __( 'Tumblog : Quote Author', 'woothemes' ),
									"type" => "text",
									"desc" => __( 'Enter the name of the Quote Author.', 'woothemes' ) );

    	$woo_metaboxes[] = array (	"name" => "quote-url",
									"std" => "http://",
									"label" => __( 'Tumblog : Link to Quote', 'woothemes' ),
									"type" => "text",
									"desc" => __( 'Enter the url/web address of the Quote if available.', 'woothemes' ) );

    	$woo_metaboxes[] = array (	"name" => "quote-copy",
    								"std"  => "Unknown",
									"label" => __( 'Tumblog : Quote', 'woothemes' ),
									"type" => "textarea",
									"desc" => __( 'Enter the Quote.', 'woothemes' ) );

		$woo_metaboxes[] = array (	"name" => "audio",
									"std" => "http://",
									"label" => __( 'Tumblog : Audio URL', 'woothemes' ),
									"type" => "text",
									"desc" => __( 'Enter the url/web address of the Audio file.', 'woothemes' ) );

    	$woo_metaboxes[] = array (	"name" => "link-url",
									"std" => "http://",
									"label" => __( 'Tumblog : Link URL', 'woothemes' ),
									"type" => "text",
									"desc" => __( 'Enter the url/web address of the Link.', 'woothemes' ) );

	}

} // End post

if( get_post_type() == 'slide' || ! get_post_type() ) {

	// TimThumb is enabled in options
	if ( get_option( 'woo_resize') == 'true' ) {

		$woo_metaboxes[] = array (	"name" => "image",
									"label" => __( 'Image', 'woothemes' ),
									"type" => "upload",
									"desc" => __( 'Upload an image to be used as background of this slide. (optional)', 'woothemes' ) );

	}

	$woo_metaboxes[] = array (	"name" => "url",
								"label" => __( 'URL', 'woothemes' ),
								"type" => "text",
								"desc" => __( 'Enter URL if you want to add a link to the uploaded image. (optional)', 'woothemes' ) );

} // End slide

/* "portfolio" Custom Post Type. */

if ( get_post_type() == 'portfolio' || !get_post_type() ) {

	// TimThumb is enabled in options
	if ( get_option( 'woo_resize') == 'true' ) {

		$woo_metaboxes[] = array (	"name" => "portfolio-image",
									"label" => __( 'Portfolio Image', 'woothemes' ),
									"type" => "upload",
									"desc" => __( 'Upload an image or enter an URL to your portfolio image', 'woothemes' ));

		$woo_metaboxes[] = array (	"name" => "_image_alignment",
									"std" => "c",
									"label" => __( 'Image Crop Alignment', 'woothemes' ),
									"type" => "select2",
									"desc" => __( 'Select crop alignment for resized image', 'woothemes' ),
									"options" => array(	"c" => __( 'Center', 'woothemes' ),
														"t" => __( 'Top', 'woothemes' ),
														"b" => __( 'Bottom', 'woothemes' ),
														"l" => __( 'Left', 'woothemes' ),
														"r" => __( 'Right', 'woothemes' ) ) );

	}

	$woo_metaboxes[] = array (  "name"  => "embed",
					            "std"  => "",
					            "label" => __( 'Video Embed Code', 'woothemes' ),
					            "type" => "textarea",
					            "desc" => __( 'Enter the video embed code for your video (YouTube, Vimeo or similar). Will show instead of your image.', 'woothemes' ) );

	$woo_metaboxes['lightbox-url'] = array (
								"name" => "lightbox-url",
								"label" => __( 'Lightbox URL', 'woothemes' ),
								"type" => "text",
								"desc" => sprintf( __( 'Enter an optional URL to show in the %s for this portfolio item.', 'woothemes' ), '<a href="http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/">' . __( 'PrettyPhoto lightbox', 'woothemes' ) . '</a>' ) );

	$woo_metaboxes['testimonial'] = array (
								"name" => "testimonial",
								"label" => __( 'Testimonial', 'woothemes' ),
								"type" => "textarea",
								"desc" => __( 'Enter a testimonial from your client to be displayed on the single portfolio page', 'woothemes' ) );

	$woo_metaboxes['testimonial_author'] = array (
								"name" => "testimonial_author",
								"label" => __( 'Testimonial Author', 'woothemes' ),
								"type" => "text",
								"desc" => __( 'Enter the name of the author of the testimonial e.g. Joe Bloggs', 'woothemes' ) );

	$woo_metaboxes[] = array (	"name" => "url",
								"label" => __( 'URL', 'woothemes' ),
								"type" => "text",
								"desc" => __( 'Enter URL of your clients site. (optional)', 'woothemes' ) );

} //End portfolio

if( get_post_type() == 'feedback' || !get_post_type()){

	$woo_metaboxes['feedback_author'] = array (
								"name" => "feedback_author",
								"label" => __( 'Feedback Author', 'woothemes' ),
								"type" => "text",
								"desc" => __( 'Enter the name of the author of the feedback e.g. Joe Bloggs', 'woothemes' )
			);

	$woo_metaboxes['feedback_url'] = array (
								"name" => "feedback_url",
								"label" => __( 'Feedback URL', 'woothemes' ),
								"type" => "text",
								"desc" => __( '(optional) Enter the URL to the feedback author e.g. http://www.woothemes.com', 'woothemes' )
			);


} // End feedback

// Page fields.
if( get_post_type() == 'page' || ! get_post_type() ) {

	// Create an array of the available "Slide Pages".
	$slide_pages = array(
						'all' => __( 'All', 'woothemes' )
						);

	$terms = get_terms( 'slide-page' );

	if ( is_array( $terms ) && ( count( $terms ) > 0 ) ) {
		foreach ( $terms as $k => $v ) {
			$slide_pages[$v->slug] = $v->name;
		}
	}

	$woo_metaboxes[] = array (	"name" => "_slide-page",
									"std" => "",
									"label" => __( 'Slide Group', 'woothemes' ),
									"type" => "select2",
									"desc" => __( 'Optionally select a "Slide Group" to show slides from only that "Slide Group".', 'woothemes' ),
									"options" => $slide_pages );

} // End slide

// Show layout option on all pages
if ( get_post_type() != 'post' && get_post_type() != 'slide' && get_post_type() != 'feedback' ) {

	$url =  get_template_directory_uri() . '/functions/images/';
	$woo_metaboxes[] = array (	"name" => "layout",
								"label" => __( 'Layout', 'woothemes' ),
								"type" => "images",
								"desc" => __( 'Select a specific layout for this post/page. Overrides default site layout.', 'woothemes' ),
								"options" => array(	'' => $url . 'layout-off.png',
													'one-col' => $url . '1c.png',
													'two-col-left' => $url . '2cl.png',
													'two-col-right' => $url . '2cr.png',
													'three-col-left' => $url . '3cl.png',
													'three-col-middle' => $url . '3cm.png',
													'three-col-right' => $url . '3cr.png'));

}


// Add extra metaboxes through function
if ( function_exists("woo_metaboxes_add") )
	$woo_metaboxes = woo_metaboxes_add($woo_metaboxes);

if ( get_option('woo_custom_template') != $woo_metaboxes) update_option('woo_custom_template',$woo_metaboxes);

} // END woo_options()
} // END function_exists()

// Add options to admin_head
add_action( 'admin_head','woo_options' );

//Enable WooSEO on these Post types
$seo_post_types = array( 'post','page' );
define( "SEOPOSTTYPES", serialize($seo_post_types));

//Global options setup
add_action( 'init','woo_global_options' );
function woo_global_options(){
	// Populate WooThemes option in array for use in theme
	global $woo_options;
	$woo_options = get_option( 'woo_options' );
}

?>