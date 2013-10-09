<?php
/**
 * Theme-specific shortcodes.
 *
 * @package WordPress
 * @subpackage Canvas
 * @category Core
 * @author WooThemes
 * @since 4.0.0
 *
 * TABLE OF CONTENTS
 *
 * 1. View Full Article
 * 2. Custom Field
 * 3. Post Date
 * 4. Post Time
 * 5. Post Author
 * 6. Post Author Link
 * 7. Post Author Posts Link
 * 8. Post Comments
 * 9. Post Tags
 * 10. Post Categories
 * 11. Post Edit
 * 12. "Back to Top" Link
 * 13. Child Theme Link
 * 14. WordPress Link
 * 15. WooThemes Link (with optional affiliate link)
 * 16. Login/Logout Link
 * 17. Copyright Text
 * 18. Credit Text
 */
/**
 * 1. View Full Article
 *
 * This function produces a link to view the full article.
 *
 * @example <code>[view_full_article]</code> is the default usage
 */
if ( ! function_exists( 'woo_shortcode_view_full_article' ) ) {
function woo_shortcode_view_full_article ( $atts ) {
	$defaults = array(
		'label' => __( 'Continue Reading', 'woothemes' ),
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );

	$atts = array_map( 'wp_kses_post', $atts );

	$output = sprintf( '<span class="read-more">%1$s<a href="%3$s" title="%4$s">%4$s</a>%2$s</span> ', $atts['before'], $atts['after'], get_permalink( get_the_ID() ), $atts['label'] );
	return apply_filters( 'woo_shortcode_view_full_article', $output, $atts );
} // End woo_shortcode_view_full_article()
}

add_shortcode( 'view_full_article', 'woo_shortcode_view_full_article' );

/**
 * 2. Custom Field
 *
 * This function produces the value of a specified custom field.
 *
 * @example <code>[woo_custom_field name="test"]</code> is the default usage
 */
if ( ! function_exists( 'woo_shortcode_custom_field' ) ) {
function woo_shortcode_custom_field ( $atts ) {
	$defaults = array(
		'name' => '',
		'before' => '',
		'after' => '',
		'id' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );

	foreach ( array( 'before', 'after' ) as $k => $v  ) {
		if ( ! empty( $atts[$v] ) ) {
			$atts[$v] = wp_kses_post( $atts[$v] );
		}
	}

	$post_id = get_the_ID();
	if ( is_numeric( $id ) ) { $post_id = $atts['id']; }

	$custom_field = get_post_meta( $post_id, esc_attr( $atts['name'] ), true );

	$output = '';

	if ( $custom_field ) {
		$output = esc_attr( $custom_field );
	}
	return apply_filters('woo_shortcode_custom_field', $output, $atts);
} // End woo_shortcode_custom_field()
}

add_shortcode( 'custom_field', 'woo_shortcode_custom_field' );

/**
 * 3. Post Date
 *
 * This function produces the date the post in question was published.
 *
 * @example <code>[post_date]</code> is the default usage
 */
if ( ! function_exists( 'woo_shortcode_post_date' ) ) {
function woo_shortcode_post_date ( $atts ) {
	$defaults = array(
		'format' => get_option( 'date_format' ),
		'before' => '',
		'after' => '',
		'label' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );

	$atts = array_map( 'wp_kses_post', $atts );

	$output = sprintf( '<abbr class="date time published" title="%5$s">%1$s%3$s%4$s%2$s</abbr> ', $atts['before'], $atts['after'], $atts['label'], get_the_time($atts['format']), get_the_time('Y-m-d\TH:i:sO') );
	return apply_filters( 'woo_shortcode_post_date', $output, $atts );
} // End woo_shortcode_post_date()
}

add_shortcode( 'post_date', 'woo_shortcode_post_date' );

/**
 * 4. Post Time
 *
 * This function produces the time the post in question was published.
 *
 * @example <code>[post_time]</code> is the default usage
 * @example <code>[post_time format="g:i a" before="<b>" after="</b>"]</code>
 */
if ( ! function_exists( 'woo_shortcode_post_time' ) ) {
function woo_shortcode_post_time ( $atts ) {
	$defaults = array(
		'format' => get_option( 'time_format' ),
		'before' => '',
		'after' => '',
		'label' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );

	$atts = array_map( 'wp_kses_post', $atts );

	$output = sprintf( '<abbr class="time published" title="%5$s">%1$s%3$s%4$s%2$s</abbr> ', $atts['before'], $atts['after'], $atts['label'], get_the_time($atts['format']), get_the_time('Y-m-d\TH:i:sO') );
	return apply_filters( 'woo_shortcode_post_time', $output, $atts );
} // End woo_shortcode_post_time()
}

add_shortcode( 'post_time', 'woo_shortcode_post_time' );

/**
 * 5. Post Author
 *
 * This function produces the author of the post (display name)
 *
 * @example <code>[post_author]</code> is the default usage
 * @example <code>[post_author before="<b>" after="</b>"]</code>
 */
if ( ! function_exists( 'woo_shortcode_post_author' ) ) {
function woo_shortcode_post_author ( $atts ) {
	$defaults = array(
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );

	$atts = array_map( 'wp_kses_post', $atts );

	$output = sprintf('<span class="author vcard">%2$s<span class="fn">%1$s</span>%3$s</span>', esc_html( get_the_author() ), $atts['before'], $atts['after']);
	return apply_filters( 'woo_shortcode_post_author', $output, $atts );
} // End woo_shortcode_post_author()
}

add_shortcode( 'post_author', 'woo_shortcode_post_author' );

/**
 * 6. Post Author Link
 *
 * This function produces the author of the post (link to author URL)
 *
 * @example <code>[post_author_link]</code> is the default usage
 * @example <code>[post_author_link before="<b>" after="</b>"]</code>
 */
if ( ! function_exists( 'woo_shortcode_post_author_link' ) ) {
function woo_shortcode_post_author_link ( $atts ) {
	$defaults = array(
		'nofollow' => FALSE,
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );

	$atts = array_map( 'wp_kses_post', $atts );

	$author = get_the_author();

	//	Link?
	if ( '' != get_the_author_meta( 'url' ) ) {
		//	Build the link
		$author = '<a href="' . esc_url( get_the_author_meta( 'url' ) ) . '" title="' . esc_attr( sprintf( __( 'Visit %s&#8217;s website', 'woothemes' ), $author ) ) . '" rel="external">' . esc_html( $author ) . '</a>';
	}

	$output = sprintf('<span class="author vcard">%2$s<span class="fn">%1$s</span>%3$s</span>', $author, $atts['before'], $atts['after']);
	return apply_filters( 'woo_shortcode_post_author_link', $output, $atts );
} // End woo_shortcode_post_author_link()
}

add_shortcode( 'post_author_link', 'woo_shortcode_post_author_link' );

/**
 * 7. Post Author Posts Link
 *
 * This function produces the display name of the post's author, with a link to their
 * author archive screen.
 *
 * @example <code>[post_author_posts_link]</code> is the default usage
 * @example <code>[post_author_posts_link before="<b>" after="</b>"]</code>
 */
if ( ! function_exists( 'woo_shortcode_post_author_posts_link' ) ) {
function woo_shortcode_post_author_posts_link ( $atts ) {
	$defaults = array(
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );

	$atts = array_map( 'wp_kses_post', $atts );

	// Darn you, WordPress!
	ob_start();
	the_author_posts_link();
	$author = ob_get_clean();

	$output = sprintf('<span class="author vcard">%2$s<span class="fn">%1$s</span>%3$s</span>', $author, $atts['before'], $atts['after']);
	return apply_filters( 'woo_shortcode_post_author_posts_link', $output, $atts );
} // End woo_shortcode_post_author_posts_link()
}

add_shortcode( 'post_author_posts_link', 'woo_shortcode_post_author_posts_link' );

/**
 * 8. Post Comments
 *
 * This function produces the comment link, or a message if comments are closed.
 *
 * @example <code>[post_comments]</code> is the default usage
 * @example <code>[post_comments zero="No Comments" one="1 Comment" more="% Comments"]</code>
 */
if ( ! function_exists( 'woo_shortcode_post_comments' ) ) {
function woo_shortcode_post_comments ( $atts ) {
	global $post;

	$defaults = array(
		'zero' => '<i class="icon-comment"></i> 0',
		'one' => '<i class="icon-comment"></i> 1',
		'more' => '<i class="icon-comment"></i> %',
		'hide_if_off' => 'enabled',
		'closed_text' => apply_filters( 'woo_post_more_comment_closed_text', __( 'Comments are closed', 'woothemes' ) ),
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );

	$atts = array_map( 'wp_kses_post', $atts );

	if ( ( ! get_option( 'woo_comments' ) || ! comments_open() ) && $atts['hide_if_off'] === 'enabled' )
		return;

	if ( $post->comment_status == 'open' ) {
		// Darn you, WordPress!
		ob_start();
		comments_number( $atts['zero'], $atts['one'], $atts['more'] );
		$comments = ob_get_clean();
		$comments = sprintf( '<a href="%s">%s</a>', get_comments_link(), $comments );
	} else {
		$comments = $atts['closed_text'];
	}

	$output = sprintf('<span class="post-comments comments">%2$s%1$s%3$s</span>', $comments, $atts['before'], $atts['after']);
	return apply_filters( 'woo_shortcode_post_comments', $output, $atts );
} // End woo_shortcode_post_comments()
}

add_shortcode( 'post_comments', 'woo_shortcode_post_comments' );

/**
 * 9. Post Tags
 *
 * This function produces a collection of tags for this post, linked to their
 * appropriate archive screens.
 *
 * @example <code>[post_tags]</code> is the default usage
 * @example <code>[post_tags sep=", " before="Tags: " after="bar"]</code>
 */
if ( ! function_exists( 'woo_shortcode_post_tags' ) ) {
function woo_shortcode_post_tags ( $atts ) {
	$defaults = array(
		'sep' => ', ',
		'before' => __('Tags: ', 'woothemes'),
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );

	$atts = array_map( 'wp_kses_post', $atts );

	$tags = get_the_tag_list( $atts['before'], trim($atts['sep']) . ' ', $atts['after'] );

	if ( !$tags ) return;

	$output = sprintf('<p class="tags"><i class="icon-tag"></i> %s</p> ', $tags);
	return apply_filters( 'woo_shortcode_post_tags', $output, $atts );
} // End woo_shortcode_post_tags()
}

add_shortcode( 'post_tags', 'woo_shortcode_post_tags' );

/**
 * 10. Post Categories
 *
 * This function produces the category link list
 *
 * @example <code>[post_categories]</code> is the default usage
 * @example <code>[post_categories sep=", "]</code>
 */
if ( ! function_exists( 'woo_shortcode_post_categories' ) ) {
function woo_shortcode_post_categories ( $atts ) {
	$defaults = array(
		'sep' => ', ',
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );

	$atts = array_map( 'wp_kses_post', $atts );

	$cats = get_the_category_list( trim($atts['sep']) . ' ' );

	$cats = str_replace( ' rel="category tag"', '', $cats );

	$output = sprintf('<span class="categories">%2$s%1$s%3$s</span> ', $cats, $atts['before'], $atts['after']);
	return apply_filters( 'woo_shortcode_post_categories', $output, $atts );
} // End woo_shortcode_post_categories()
}

add_shortcode( 'post_categories', 'woo_shortcode_post_categories' );

/**
 * 11. Post Edit
 *
 * This function produces the "edit post" link for logged in users.
 *
 * @example <code>[post_edit]</code> is the default usage
 * @example <code>[post_edit link="Edit", before="<b>" after="</b>"]</code>
 */
if ( ! function_exists( 'woo_shortcode_post_edit' ) ) {
function woo_shortcode_post_edit ( $atts ) {
	$defaults = array(
		'link' => '<i class="icon-edit"></i>',
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );

	$atts = array_map( 'wp_kses_post', $atts );

	// Darn you, WordPress!
	ob_start();
	edit_post_link( $atts['link'], $atts['before'], $atts['after'] ); // if logged in
	$edit = ob_get_clean();

	$output = $edit;
	return apply_filters( 'woo_shortcode_post_edit', $output, $atts );
} // End woo_shortcode_post_edit()
}

add_shortcode( 'post_edit', 'woo_shortcode_post_edit' );

/**
 * 12. "Back to Top" Link
 *
 * This function produces a "back to top" link, which links to a specified ID on the
 * current page.
 *
 * @example <code>[footer_backtotop]</code> is the default usage
 */
if ( ! function_exists( 'woo_shortcode_footer_backtotop' ) ) {
function woo_shortcode_footer_backtotop ( $atts ) {
	$defaults = array(
		'text' => __( 'Back to top', 'woothemes' ),
		'href' => '#wrapper',
		'nofollow' => true,
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );

	$atts = array_map( 'wp_kses_post', $atts );

	$nofollow = $atts['nofollow'] ? 'rel="nofollow"' : '';

	$output = sprintf( '%s<a href="%s" %s class="backtotop">%s</a>%s', $atts['before'], esc_url( $atts['href'] ), $nofollow, $atts['text'], $atts['after'] );
	return apply_filters( 'woo_shortcode_footer_backtotop', $output, $atts );
} // End woo_shortcode_footer_backtotop()
}

add_shortcode( 'footer_backtotop', 'woo_shortcode_footer_backtotop' );

/**
 * 13. Child Theme Link
 *
 * This function produces a link to the child theme's URL, if one is specified.
 *
 * @example <code>[footer_childtheme_link]</code> is the default usage
 */
if ( ! function_exists( 'woo_shortcode_footer_childtheme_link' ) ) {
function woo_shortcode_footer_childtheme_link ( $atts ) {
	$defaults = array(
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );

	$atts = array_map( 'wp_kses_post', $atts );

	if ( is_child_theme() ) {
		$theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );
		define( 'CHILD_THEME_URL', $theme_data['URI'] );
		define( 'CHILD_THEME_NAME', $theme_data['Name'] );
	}
	if ( ! isset( $theme_data['URI'] ) ) { return; }

	$output = sprintf( '%s<a href="%s" title="%s">%s</a>%s', $atts['before'], esc_url( $theme_data['URI'] ), esc_attr( $theme_data['Name'] ), esc_html( $theme_data['Name'] ), $atts['after'] );
	return apply_filters( 'woo_shortcode_footer_childtheme_link', $output, $atts );
} // End woo_shortcode_footer_childtheme_link()
}

add_shortcode( 'footer_childtheme_link', 'woo_shortcode_footer_childtheme_link' );

/**
 * 14. WordPress Link
 *
 * This function produces a link back to WordPress.org.
 *
 * @example <code>[footer_wordpress_link]</code> is the default usage
 */
if ( ! function_exists( 'woo_shortcode_footer_wordpress_link' ) ) {
function woo_shortcode_footer_wordpress_link ( $atts ) {
	$defaults = array(
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );

	$atts = array_map( 'wp_kses_post', $atts );

	$output = sprintf( '%s<a href="%s" title="%s">%s</a>%s', $atts['before'], 'http://wordpress.org/', 'WordPress', 'WordPress', $atts['after'] );
	return apply_filters( 'woo_shortcode_footer_wordpress_link', $output, $atts );
} // End woo_shortcode_footer_wordpress_link()
}

add_shortcode( 'footer_wordpress_link', 'woo_shortcode_footer_wordpress_link' );

/**
 * 15. WooThemes Link (with optional affiliate link)
 *
 * This function produces link back to WooThemes, with an affiliate link if you've
 * specified one in the WooFramework.
 *
 * @example <code>[footer_woothemes_link]</code> is the default usage
 */
if ( ! function_exists( 'woo_shortcode_footer_woothemes_link' ) ) {
function woo_shortcode_footer_woothemes_link ( $atts ) {
	global $woo_options;

	$defaults = array(
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );

	$atts = array_map( 'wp_kses_post', $atts );

	$woo_link = 'http://www.woothemes.com/';
	if ( isset( $woo_options['woo_footer_aff_link'] ) && '' != $woo_options['woo_footer_aff_link'] ) {
		$woo_link = $woo_options['woo_footer_aff_link'];
	}

	$output = sprintf( '%s<a href="%s" title="%s">%s</a>%s', $atts['before'], esc_url( $woo_link ), 'WooThemes', '<img src="' . esc_url( get_template_directory_uri() . '/images/woothemes.png' ) . '" width="74" height="19" alt="Woo Themes" />', $atts['after'] );
	return apply_filters( 'woo_shortcode_footer_woothemes_link', $output, $atts );
} // End woo_shortcode_footer_woothemes_link()
}

add_shortcode( 'footer_woothemes_link', 'woo_shortcode_footer_woothemes_link' );

/**
 * 16. Login/Logout Link
 *
 * This function produces a login or logout link, depending on the user's login status.
 *
 * @example <code>[footer_loginout]</code> is the default usage
 */
if ( ! function_exists( 'woo_shortcode_footer_loginout' ) ) {
function woo_shortcode_footer_loginout ( $atts ) {
	$defaults = array(
		'redirect' => '',
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );

	$atts = array_map( 'wp_kses_post', $atts );

	if ( ! is_user_logged_in() )
		$link = '<a href="' . esc_url( wp_login_url($atts['redirect']) ) . '">' . __('Log in', 'woothemes') . '</a>';
	else
		$link = '<a href="' . esc_url( wp_logout_url($atts['redirect']) ) . '">' . __('Log out', 'woothemes') . '</a>';


	$output = $atts['before'] . apply_filters('loginout', $link) . $atts['after'];
	return apply_filters( 'woo_shortcode_footer_loginout', $output, $atts );
} // End woo_shortcode_footer_loginout()
}

add_shortcode( 'footer_loginout', 'woo_shortcode_footer_loginout' );

/**
 * 17. Copyright Text
 *
 * This function produces the default footer copyright text.
 *
 * @example <code>[site_copyright]</code> is the default usage
 */
if ( ! function_exists( 'woo_shortcode_site_copyright' ) ) {
function woo_shortcode_site_copyright ( $atts ) {
	$defaults = array(
		'before' => '<p>',
		'after' => '</p>'
	);
	$atts = shortcode_atts( $defaults, $atts );

	$atts = array_map( 'wp_kses_post', $atts );

	$output = sprintf( '%1$s%3$s %4$s %5$s %2$s', $atts['before'], $atts['after'], "&copy; " . date( 'Y' ), get_bloginfo( 'name' ) . '.', __( 'All Rights Reserved.', 'woothemes' ) );
	return apply_filters( 'woo_shortcode_site_copyright', $output, $atts );
} // End woo_shortcode_site_copyright()
}

add_shortcode( 'site_copyright', 'woo_shortcode_site_copyright' );

/**
 * 18. Credit Text
 *
 * This function produces the default footer credit text.
 *
 * @example <code>[site_credit]</code> is the default usage
 */
if ( ! function_exists( 'woo_shortcode_site_credit' ) ) {
function woo_shortcode_site_credit ( $atts ) {
	$defaults = array(
		'before' => '<p>',
		'after' => '</p>'
	);
	$atts = shortcode_atts( $defaults, $atts );

	$atts = array_map( 'wp_kses_post', $atts );

	$output = sprintf( '%1$s%3$s %4$s %5$s %6$s%2$s', $atts['before'], $atts['after'], __( 'Powered by', 'woothemes' ), '[footer_wordpress_link]' . '.', __( 'Designed by', 'woothemes' ), '[footer_woothemes_link]' );
	return do_shortcode( apply_filters( 'woo_shortcode_site_credit', $output, $atts ) );
} // End woo_shortcode_site_credit()
}

add_shortcode( 'site_credit', 'woo_shortcode_site_credit' );
?>