<?php
/*-----------------------------------------------------------------------------------

CLASS INFORMATION

Description: A custom WooThemes Feedback widget.
Date Created: 2011-08-18.
Last Modified: 2011-08-18.
Author: Matty @ WooThemes.
Since: 4.5.0


TABLE OF CONTENTS

- function (constructor)
- function widget ()
- function update ()
- function form ()

- Register the widget on `widgets_init`.

-----------------------------------------------------------------------------------*/

class Woo_Widget_Feedback extends WP_Widget {

	/*----------------------------------------
	  Constructor.
	  ----------------------------------------

	  * The constructor. Sets up the widget.
	----------------------------------------*/

	function Woo_Widget_Feedback () {

		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_woo_feedback', 'description' => __( 'Display your feedback in a customised widget.', 'woothemes' ) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'woo_feedback' );

		/* Create the widget. */
		$this->WP_Widget( 'woo_feedback', __('Woo - Feedback', 'woothemes' ), $widget_ops, $control_ops );

		/* Make sure the JavaScript for this widget loads. */
		if ( is_active_widget( false, false, 'woo_feedback' ) ) add_filter( 'woo_load_feedback_js', '__return_true', 10 );

	} // End Constructor

 	/*----------------------------------------
	  widget()
	  ----------------------------------------

	  * Displays the widget on the frontend.
	----------------------------------------*/

	function widget( $args, $instance ) {

		$html = '';

		extract( $args, EXTR_SKIP );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base );

		$limit = $instance['limit']; if ( ! intval( $limit ) ) { $limit = 5; }

		$effect = $instance['effect'];
		$pagination = $instance['pagination'];
		$display_author = $instance['display_author'];
		$display_url = $instance['display_url'];
		$unique_id = $args['widget_id'];

		// Make sure our checkboxes are either true if available or false if empty.
		foreach ( array( 'display_author', 'display_url' ) as $k ) {
			if ( isset( $instance[$k] ) ) {
				if ( $instance[$k] == true ) {
					${$k} = true;
				} else {
					${$k} = false;
				}
			} else {
				${$k} = false;
			}
		}

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title ) {

			echo $before_title . $title . $after_title;

		} // End IF Statement

		/* Widget content. */

		// Add actions for plugins/themes to hook onto.
		do_action( 'woo_widget_feedback_top' );

		$html = '';

		$query_args = array(
			'echo' => false
			);

		if ( $limit > 0 ) {
			$query_args['limit'] = $limit;
		}

		$query_args['effect'] = $effect;
		$query_args['pagination'] = false;
		$query_args['display_author'] = $display_author;
		$query_args['display_url'] = $display_url;

		$html .= woo_display_feedback_entries( $query_args );

		$html .= '<input type="hidden" name="auto_speed" value="' . esc_attr( floatval( $instance['auto_speed'] * 1000 ) ) . '" />' . "\n";
		$html .= '<input type="hidden" name="fade_speed" value="' . esc_attr( floatval( $instance['fade_speed'] * 1000 ) ) . '" />' . "\n";

		echo $html;

		// Add actions for plugins/themes to hook onto.
		do_action( 'woo_widget_feedback_bottom' );

		/* After widget (defined by themes). */
		echo $after_widget;

	} // End widget()

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

		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );

		$instance['limit'] = esc_attr( $new_instance['limit'] );

		$instance['auto_speed'] = esc_attr( $new_instance['auto_speed'] );
		$instance['fade_speed'] = esc_attr( $new_instance['fade_speed'] );

		/* The select box is returning a text value, so we escape it. */
		$instance['effect'] = esc_attr( $new_instance['effect'] );

		/* The checkbox is returning a Boolean (true/false), so we check for that. */
		$instance['pagination'] = (bool) esc_attr( $new_instance['pagination'] );
		$instance['display_author'] = (bool) esc_attr( $new_instance['display_author'] );
		$instance['display_url'] = (bool) esc_attr( $new_instance['display_url'] );

		return $instance;

	} // End update()

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

       /* Set up some default widget settings. */
		$defaults = array(
						'title' => __( 'Feedback', 'woothemes' ),
						'limit' => 5,
						'effect' => 'fade',
						'pagination' => false,
						'display_author' => false,
						'display_url' => false,
						'auto_speed' => 5,
						'fade_speed' => 0.3
					);

		$instance = wp_parse_args( (array) $instance, $defaults );
?>
       <!-- Widget Title: Text Input -->
       <p>
	   	   <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (optional):', 'woothemes' ); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>"  value="<?php echo $instance['title']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" />
       </p>
       <!-- Widget Limit: Text Input -->
       <p>
	   	   <label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Limit:', 'woothemes' ); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name( 'limit' ); ?>"  value="<?php echo $instance['limit']; ?>" class="" size="3" id="<?php echo $this->get_field_id( 'limit' ); ?>" />
       </p>
       <!-- Widget Effect: Select Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'effect' ); ?>"><?php _e( 'Effect:', 'woothemes' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'effect' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'effect' ); ?>">
				<option value="none"<?php selected( $instance['effect'], 'none' ); ?>><?php _e( 'None', 'woothemes' ); ?></option>
				<option value="fade"<?php selected( $instance['effect'], 'fade' ); ?>><?php _e( 'Fade', 'woothemes' ); ?></option>
			</select>
		</p>
		<!-- Widget Auto Play Speed: Text Input -->
       <p>
	   	   <label for="<?php echo $this->get_field_id( 'auto_speed' ); ?>"><?php _e( 'Auto-Fade Duration (in seconds):', 'woothemes' ); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name( 'auto_speed' ); ?>"  value="<?php echo $instance['auto_speed']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'auto_speed' ); ?>" />
       </p>
       <!-- Widget Fade Speed: Text Input -->
       <p>
	   	   <label for="<?php echo $this->get_field_id( 'fade_speed' ); ?>"><?php _e( 'Fade Speed (in seconds):', 'woothemes' ); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name( 'fade_speed' ); ?>"  value="<?php echo $instance['fade_speed']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'fade_speed' ); ?>" />
       </p>
		<!-- Widget Pagination: Checkbox Input -->
       	<p>
        	<input id="<?php echo $this->get_field_id( 'pagination' ); ?>" name="<?php echo $this->get_field_name( 'pagination' ); ?>" type="checkbox"<?php checked( $instance['pagination'], 1 ); ?> />
        	<label for="<?php echo $this->get_field_id( 'pagination' ); ?>"><?php _e( 'Enable Pagination', 'woothemes' ); ?></label>
        	<br /><small>(<?php _e( 'Disabled if the "limit" is 1', 'woothemes' ); ?>)</small>
	   	</p>

	   	<!-- Widget Display Author: Checkbox Input -->
       	<p>
        	<input id="<?php echo $this->get_field_id( 'display_author' ); ?>" name="<?php echo $this->get_field_name( 'display_author' ); ?>" type="checkbox"<?php checked( $instance['display_author'], 1 ); ?> />
        	<label for="<?php echo $this->get_field_id( 'display_author' ); ?>"><?php _e( 'Display Author', 'woothemes' ); ?></label>
	   	</p>

	   	<!-- Widget Display URL: Checkbox Input -->
       	<p>
        	<input id="<?php echo $this->get_field_id( 'display_url' ); ?>" name="<?php echo $this->get_field_name( 'display_url' ); ?>" type="checkbox"<?php checked( $instance['display_url'], 1 ); ?> />
        	<label for="<?php echo $this->get_field_id( 'display_url' ); ?>"><?php _e( 'Display URL', 'woothemes' ); ?></label>
	   	</p>
<?php
	} // End form()

} // End Class

/*----------------------------------------
  Register the widget on `widgets_init`.
  ----------------------------------------

  * Registers this widget.
----------------------------------------*/

add_action( 'widgets_init', create_function( '', 'return register_widget("Woo_Widget_Feedback");' ), 1 );
?>