<?php
/*-----------------------------------------------------------------------------------

CLASS INFORMATION

Description: A custom WooThemes WooTabs widget.
Date Created: 2011.
Last Modified: 2011-04-15.
Author: WooThemes.
Since: 1.0.0


TABLE OF CONTENTS

- function (constructor)
- function widget ()
- function update ()
- function form ()

- Register the widget on `widgets_init`.

-----------------------------------------------------------------------------------*/

class Woo_Widget_WooTabs extends WP_Widget {
	var $settings = array( 'number', 'thumb_size', 'order', 'pop', 'latest', 'comments', 'tags', 'days' );

	/*----------------------------------------
	  Constructor.
	  ----------------------------------------

	  * The constructor. Sets up the widget.
	----------------------------------------*/

	function Woo_Widget_WooTabs () {

		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_woo_tabs', 'description' => __( 'This widget is the Tabs that classically goes into the sidebar. It contains the Popular posts, Latest Posts, Recent comments and a Tag cloud.', 'woothemes' ) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'woo_tabs' );

		/* Create the widget. */
		$this->WP_Widget( 'woo_tabs', __('Woo - Tabs', 'woothemes' ), $widget_ops, $control_ops );

	} // End Constructor

	function widget($args, $instance) {
		extract( $args, EXTR_SKIP );
		$instance = $this->woo_enforce_defaults( $instance );
		extract( $instance, EXTR_SKIP );
		echo $before_widget;
		?>

<div id="tabs">

    <ul class="wooTabs">
    
    <?php if ( $order == "latest" && !$latest == "on") { ?>
    	<li class="latest"><a href="#tab-latest"><?php _e( 'Latest', 'woothemes' ); ?></a></li>
    <?php } elseif ( $order == "comments" && !$comments == "on") { ?>
    	<li class="comments"><a href="#tab-comm"><?php _e( 'Comments', 'woothemes' ); ?></a></li>
    <?php } elseif ( $order == "tags" && !$tags == "on") { ?>
    	<li class="tags"><a href="#tab-tags"><?php _e( 'Tags', 'woothemes' ); ?></a></li>
    <?php } ?>
    <?php if (!$pop == "on") { ?>
    	<li class="popular"><a href="#tab-pop"><?php _e( 'Popular', 'woothemes' ); ?></a></li><?php } ?>
    <?php if ($order <> "latest" && !$latest == "on") { ?>
    	<li class="latest"><a href="#tab-latest"><?php _e( 'Latest', 'woothemes' ); ?></a></li><?php } ?>
    <?php if ($order <> "comments" && !$comments == "on") { ?>
    	<li class="comments"><a href="#tab-comm"><?php _e( 'Comments', 'woothemes' ); ?></a></li><?php } ?>
    <?php if ($order <> "tags" && !$tags == "on") { ?>
    	<li class="tags"><a href="#tab-tags"><?php _e( 'Tags', 'woothemes' ); ?></a></li>
    <?php } ?>
    
    </ul>

    <div class="fix"></div>

    <div class="boxes box inside">

        <?php if ( $order == "latest" && !$latest == "on") { ?>
        <ul id="tab-latest" class="list">
            <?php if ( function_exists( 'woo_widget_tabs_latest') ) woo_widget_tabs_latest($number, $thumb_size); ?>
        </ul>
        <?php } elseif ( $order == "comments" && !$comments == "on") { ?>
		<ul id="tab-comm" class="list">
            <?php if ( function_exists( 'woo_widget_tabs_comments') ) woo_widget_tabs_comments($number, $thumb_size); ?>
        </ul>
        <?php } elseif ( $order == "tags" && !$tags == "on") { ?>
        <div id="tab-tags" class="list">
            <?php wp_tag_cloud( 'smallest=12&largest=20' ); ?>
        </div>
        <?php } ?>

        <?php if (!$pop == "on") { ?>
        <ul id="tab-pop" class="list">
            <?php if ( function_exists( 'woo_widget_tabs_popular') ) woo_widget_tabs_popular($number, $thumb_size, $days); ?>
        </ul>
        <?php } ?>
        <?php if ($order <> "latest" && !$latest == "on") { ?>
        <ul id="tab-latest" class="list">
            <?php if ( function_exists( 'woo_widget_tabs_latest') ) woo_widget_tabs_latest($number, $thumb_size); ?>
        </ul>
        <?php } ?>
        <?php if ($order <> "comments" && !$comments == "on") { ?>
		<ul id="tab-comm" class="list">
            <?php if ( function_exists( 'woo_widget_tabs_comments') ) woo_widget_tabs_comments($number, $thumb_size); ?>
        </ul>
        <?php } ?>
        <?php if ($order <> "tags" && !$tags == "on") { ?>
        <div id="tab-tags" class="list">
            <?php wp_tag_cloud( 'smallest=12&largest=20' ); ?>
        </div>
        <?php } ?>

    </div><!-- /.boxes -->

</div><!-- /wooTabs -->

         <?php
         echo $after_widget;
   }

   /*----------------------------------------
	  update()
	  ----------------------------------------

	* Function to update the settings from
	* the form() function.

	* Params:
	* - Array $new_instance
	* - Array $old_instance
	----------------------------------------*/

	function update ( $new_instance, $old_instance ) {
		$new_instance = $this->woo_enforce_defaults( $new_instance );
		return $new_instance;
	} // End update()

	function woo_enforce_defaults( $instance ) {
		$defaults = $this->woo_get_settings();
		$instance = wp_parse_args( $instance, $defaults );
		$instance['number'] = intval( $instance['number'] );
		if ( $instance['number'] < 1 )
			$instance['number'] = $defaults['number'];
		$instance['thumb_size'] = absint( $instance['thumb_size'] );
		if ( empty( $instance['order'] ) )
			$instance['order'] = $defaults['order'];
		return $instance;
	}

	/**
	 * Provides an array of the settings with the setting name as the key and the default value as the value
	 * This cannot be called get_settings() or it will override WP_Widget::get_settings()
	 */
	function woo_get_settings() {
		// Set the default to a blank string
		$settings = array_fill_keys( $this->settings, '' );
		// Now set the more specific defaults
		$settings['number'] = 5;
		$settings['thumb_size'] = 45;
		$settings['order'] = 'pop';
		return $settings;
	}

   /*----------------------------------------
	 form()
	 ----------------------------------------

	  * The form on the widget control in the
	  * widget administration area.

	  * Make use of the get_field_id() and
	  * get_field_name() function when creating
	  * your form elements. This handles the confusing stuff.

	  * Params:
	  * - Array $instance
	----------------------------------------*/

   function form( $instance ) {
		$instance = $this->woo_enforce_defaults( $instance );
		extract( $instance, EXTR_SKIP );
?>
       <p>
	       <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts:', 'woothemes' ); ?>
	       <input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo esc_attr( $instance['number'] ); ?>" />
	       </label>
       </p>
       <p>
	       <label for="<?php echo $this->get_field_id( 'thumb_size' ); ?>"><?php _e( 'Thumbnail Size (0=disable):', 'woothemes' ); ?>
	       <input class="widefat" id="<?php echo $this->get_field_id( 'thumb_size' ); ?>" name="<?php echo $this->get_field_name( 'thumb_size' ); ?>" type="text" value="<?php echo esc_attr( $instance['thumb_size'] ); ?>" />
	       </label>
       </p>
       <p>
	       <label for="<?php echo $this->get_field_id( 'days' ); ?>"><?php _e( 'Popular limit (days):', 'woothemes' ); ?>
	       <input class="widefat" id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="text" value="<?php echo esc_attr( $instance['days'] ); ?>" />
	       </label>
       </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'First Visible Tab:', 'woothemes' ); ?></label>
            <select name="<?php echo $this->get_field_name( 'order' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'order' ); ?>">
                <option value="pop" <?php selected( $instance['order'], 'pop' ); ?>><?php _e( 'Popular', 'woothemes' ); ?></option>
                <option value="latest" <?php selected( $instance['order'], 'latest' ); ?>><?php _e( 'Latest', 'woothemes' ); ?></option>
                <option value="comments" <?php selected( $instance['order'], 'comments' ); ?>><?php _e( 'Comments', 'woothemes' ); ?></option>
                <option value="tags" <?php selected( $instance['order'], 'tags' ); ?>><?php _e( 'Tags', 'woothemes' ); ?></option>
            </select>
        </p>
       <p><strong><?php _e( 'Hide Tabs:', 'woothemes' ); ?></strong></p>
       <p>
        <input id="<?php echo $this->get_field_id( 'pop' ); ?>" name="<?php echo $this->get_field_name( 'pop' ); ?>" type="checkbox" <?php checked( $instance['pop'], 'on' ); ?>><?php _e( 'Popular', 'woothemes' ); ?></input>
	   </p>
	   <p>
	       <input id="<?php echo $this->get_field_id( 'latest' ); ?>" name="<?php echo $this->get_field_name( 'latest' ); ?>" type="checkbox" <?php checked( $instance['latest'], 'on' ); ?>><?php _e( 'Latest', 'woothemes' ); ?></input>
	   </p>
	   <p>
	       <input id="<?php echo $this->get_field_id( 'comments' ); ?>" name="<?php echo $this->get_field_name( 'comments' ); ?>" type="checkbox" <?php checked( $instance['comments'], 'on' ); ?>><?php _e( 'Comments', 'woothemes' ); ?></input>
	   </p>
	   <p>
	       <input id="<?php echo $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ); ?>" type="checkbox" <?php checked( $instance['tags'], 'on' ); ?>><?php _e( 'Tags', 'woothemes' ); ?></input>
       </p>
<?php
	} // End form()

} // End Class

/*----------------------------------------
  Register the widget on `widgets_init`.
  ----------------------------------------

  * Registers this widget.
----------------------------------------*/

add_action( 'widgets_init', create_function( '', 'return register_widget("Woo_Widget_WooTabs");' ), 1 );
?>
<?php
/*-----------------------------------------------------------------------------------*/
/* WooTabs - Javascript */
/*-----------------------------------------------------------------------------------*/
// Add Javascript
if(is_active_widget( null,null,'woo_tabs' ) == true) {
	add_action( 'wp_footer','woo_widget_tabs_js' );
}

function woo_widget_tabs_js(){
?>

<!-- Woo Tabs Widget -->
<script type="text/javascript">jQuery(document).ready(function(){var a="#tagcloud";var b=jQuery("#tagcloud").height();jQuery(".inside ul li:last-child").css("border-bottom","0px");jQuery(".wooTabs").each(function(){jQuery(this).children("li").children("a:first").addClass("selected")});jQuery(".inside > *").hide();jQuery(".inside > *:first-child").show();jQuery(".wooTabs li a").click(function(a){var b=jQuery(this).attr("href");jQuery(this).parent().parent().children("li").children("a").removeClass("selected");jQuery(this).addClass("selected");jQuery(this).parent().parent().parent().children(".inside").children("*").hide();jQuery(".inside "+b).fadeIn(500);a.preventDefault()})})</script>

<?php
}

/*-----------------------------------------------------------------------------------*/
/* WooTabs - Popular Posts */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'woo_widget_tabs_popular')) {
	function woo_widget_tabs_popular( $posts = 5, $size = 45, $days = null ) {
		global $post;

		if ( $days ) {
			global $popular_days;
			$popular_days = $days;

			// Register the filtering function
			add_filter('posts_where', 'filter_where');
		}

		$popular = get_posts( array( 'suppress_filters' => false, 'ignore_sticky_posts' => 1, 'orderby' => 'comment_count', 'numberposts' => $posts) );
		foreach($popular as $post) :
			setup_postdata($post);
	?>
	<li class="fix">
		<?php if ($size <> 0) woo_image( 'height='.$size.'&width='.$size.'&class=thumbnail&single=true' ); ?>
		<a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
		<span class="meta"><?php the_time( get_option( 'date_format' ) ); ?></span>
	</li>
	<?php endforeach; wp_reset_query();
	}
}

//Create a new filtering function that will add our where clause to the query
function filter_where($where = '') {
  global $popular_days;
  //posts in the last X days
  $where .= " AND post_date > '" . date('Y-m-d', strtotime('-'.$popular_days.' days')) . "'";
  return $where;
}

/*-----------------------------------------------------------------------------------*/
/* WooTabs - Latest Posts */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'woo_widget_tabs_latest')) {
	function woo_widget_tabs_latest( $posts = 5, $size = 45 ) {
		global $post;
		$latest = get_posts( 'ignore_sticky_posts=1&numberposts='. $posts .'&orderby=post_date&order=desc' );
		foreach($latest as $post) :
			setup_postdata($post);
	?>
	<li class="fix">
		<?php if ($size <> 0) woo_image( 'height='.$size.'&width='.$size.'&class=thumbnail&single=true' ); ?>
		<a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
		<span class="meta"><?php the_time( get_option( 'date_format' ) ); ?></span>
	</li>
	<?php endforeach; wp_reset_query();
	}
}



/*-----------------------------------------------------------------------------------*/
/* WooTabs - Latest Comments */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'woo_widget_tabs_comments')) {
	function woo_widget_tabs_comments( $posts = 5, $size = 35 ) {
		global $wpdb;

		$comments = get_comments( array( 'number' => $posts, 'status' => 'approve' ) );
		if ( $comments ) {
			foreach ( (array) $comments as $comment) {
			$post = get_post( $comment->comment_post_ID );
			?>
				<li class="recentcomments fix">
					<?php if ( $size > 0 ) echo get_avatar( $comment, $size ); ?>
					<a href="<?php echo get_comment_link($comment->comment_ID); ?>" title="<?php echo wp_filter_nohtml_kses($comment->comment_author); ?> <?php _e( 'on', 'woothemes' ); ?> <?php echo $post->post_title; ?>"><?php echo wp_filter_nohtml_kses($comment->comment_author); ?>: <?php echo stripslashes( substr( wp_filter_nohtml_kses( $comment->comment_content ), 0, 50 ) ); ?>...</a>
				</li>
			<?php
			}
 		}
	}
}

?>