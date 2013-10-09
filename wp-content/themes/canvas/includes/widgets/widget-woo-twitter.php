<?php
/*-----------------------------------------------------------------------------------

CLASS INFORMATION

Description: A custom WooThemes Twitter widget.
Date Created: 2010.
Last Modified: 2011-04-14.
Author: WooThemes.
Since: 1.0.0


TABLE OF CONTENTS

- function (constructor)
- function widget ()
- function update ()
- function form ()

- Register the widget on `widgets_init`.

-----------------------------------------------------------------------------------*/

class Woo_Widget_Twitter extends WP_Widget {

	/*----------------------------------------
	  Constructor.
	  ----------------------------------------
	  
	  * The constructor. Sets up the widget.
	----------------------------------------*/
	
	function Woo_Widget_Twitter () {
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_woo_twitter', 'description' => __( 'Add your Twitter feed to your sidebar with this widget.', 'woothemes' ) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'woo_twitter' );

		/* Create the widget. */
		$this->WP_Widget( 'woo_twitter', __('Woo - Twitter Stream', 'woothemes' ), $widget_ops, $control_ops );
		
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
		$username = $instance['username'];
		$unique_id = $args['widget_id'];
		
		/* If we don't have a username, don't display the widget. */
		if ( $username != '' ) {
			
			/* Before widget (defined by themes). */
			echo $before_widget;
		
			/* Replace the title with a Twitter image, if one exists and no title is set. */
			if ( ! $title && file_exists( get_template_directory() . '/images/twitter.png' ) ) {
				
				$title = '<img src="' . get_template_directory_uri() . '/images/twitter.png" alt="' . __( 'Recent Tweets', 'woothemes' ) . '" />' . __( ' Twitter', 'woothemes' ) . "\n";
			}
	
			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title ) {
			
				echo $before_title . $title . $after_title;
			
			} // End IF Statement
			
			/* Widget content. */
			
			// Add actions for plugins/themes to hook onto.
			do_action( 'woo_widget_twitter_top' );
			
			$html = '';
			
			$html .= '<div class="back">' . "\n";
			$html .= '<ul id="twitter_update_list_' . $unique_id . '">' . "\n";
			$html .= '<li></li>' . "\n";
			$html .= '</ul>' . "\n";
			
			$html .= '<p>' . __( 'Follow', 'woothemes' ) . ' <a href="http'.( is_ssl() ? 's' : '' ).'://twitter.com/' . $username . '"><strong>@' . $username . '</strong></a> ' . __( 'on Twitter', 'woothemes' ) . '</p>' . "\n";
			$html .= '</div>' . "\n";
			$html .= '<div class="clear"></div>' . "\n";
			
			echo $html;
			
			echo woo_twitter_script( $unique_id, $username,$limit ); //Javascript output function	 
			
			// Add actions for plugins/themes to hook onto.
			do_action( 'woo_widget_twitter_bottom' );
	
			/* After widget (defined by themes). */
			echo $after_widget;
		
		} // End IF Statement

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
		$instance['username'] = esc_attr( $new_instance['username'] );
		$instance['limit'] = esc_attr( $new_instance['limit'] );
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
						'title' => __( 'Recent Tweets', 'woothemes' ), 
						'username' => '', 
						'limit' => 5
					);

		$instance = wp_parse_args( (array) $instance, $defaults );
?>
       <!-- Widget Title: Text Input -->
       <p>
	   	   <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (optional):', 'woothemes' ); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>"  value="<?php echo $instance['title']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" />
       </p>
       <!-- Widget Username: Text Input -->
       <p>
	   	   <label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e( 'Username:', 'woothemes' ); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name( 'username' ); ?>"  value="<?php echo $instance['username']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" />
       </p>
       <!-- Widget Limit: Text Input -->
       <p>
	   	   <label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Limit:', 'woothemes' ); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name( 'limit' ); ?>"  value="<?php echo $instance['limit']; ?>" class="" size="3" id="<?php echo $this->get_field_id( 'limit' ); ?>" />

       </p>
<?php
	} // End form()
	
} // End Class

/*----------------------------------------
  Register the widget on `widgets_init`.
  ----------------------------------------
  
  * Registers this widget.
----------------------------------------*/

add_action( 'widgets_init', create_function( '', 'return register_widget("Woo_Widget_Twitter");' ), 1 ); 
?>