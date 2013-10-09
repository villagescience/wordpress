<?php
/*-----------------------------------------------------------------------------------

CLASS INFORMATION

Description: A custom WooThemes Subcribe & Connect widget.
Date Created: 2011.
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

class Woo_Widget_Subscribe extends WP_Widget {

	/*----------------------------------------
	  Constructor.
	  ----------------------------------------
	  
	  * The constructor. Sets up the widget.
	----------------------------------------*/
	
	function Woo_Widget_Subscribe () {
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_woo_subscribe', 'description' => __( 'Add a subscribe/connect widget.', 'woothemes' ) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'woo_subscribe' );

		/* Create the widget. */
		$this->WP_Widget( 'woo_subscribe', __('Woo - Subscribe / Connect', 'woothemes' ), $widget_ops, $control_ops );
		
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
		
		$form = $instance['form'];
	   	$social = $instance['social'];
	   	$single = $instance['single'];		
	   	$page = $instance['page'];
		
		/* Determine whether or not to display the widget. */
		
		if ( ! is_singular() || ( $single == 'on' && is_single() ) || ( $page == 'on' && is_page() ) ) {
		
			/* Before widget (defined by themes). */
			echo $before_widget;
			
			/* Widget content. */
			
			// Add actions for plugins/themes to hook onto.
			do_action( 'widget_woo_subscribe_top' );
			
			woo_subscribe_connect( 'true', $title, $form, $social );
			
			// Add actions for plugins/themes to hook onto.
			do_action( 'widget_woo_subscribe_bottom' );
		
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
		$instance['form'] = esc_attr( $new_instance['form'] );
		$instance['social'] = esc_attr( $new_instance['social'] );
		$instance['single'] = esc_attr( $new_instance['single'] );
		$instance['page'] = esc_attr( $new_instance['page'] );
		
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

	function form ( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __( 'Subscribe / Connect', 'woothemes' ), 'form' => '', 'social' => '', 'single' => '', 'page' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults );      
       	?>
		<!-- No options -->
		<p><em><?php printf( __( 'Setup this widget in your <a href="%s">options panel</a> under <strong>Subscribe &amp; Connect</strong>', 'woothemes' ), admin_url( 'admin.php?page=woothemes' ) ); ?></em>.</p>
        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (optional):', 'woothemes' ); ?></label>
            <input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" />
        </p>
       	<!-- Widget Subscribe Form: Checkbox Input -->
       	<p>
        	<input id="<?php echo $this->get_field_id( 'form' ); ?>" name="<?php echo $this->get_field_name( 'form' ); ?>" type="checkbox"<?php checked( $instance['form'], 'on' ); ?> />
        	<label for="<?php echo $this->get_field_id( 'form' ); ?>"><?php _e( 'Disable Subscription Form', 'woothemes' ); ?></label>
	   	</p>
	   	<!-- Widget Social Icons: Checkbox Input -->
       	<p>
        	<input id="<?php echo $this->get_field_id( 'social' ); ?>" name="<?php echo $this->get_field_name( 'social' ); ?>" type="checkbox"<?php checked( $instance['social'], 'on' ); ?> />
        	<label for="<?php echo $this->get_field_id( 'social' ); ?>"><?php _e( 'Disable Social Icons', 'woothemes' ); ?></label>
	   	</p>
	   	<!-- Widget Enable In Posts: Checkbox Input -->
       	<p>
        	<input id="<?php echo $this->get_field_id( 'single' ); ?>" name="<?php echo $this->get_field_name( 'single' ); ?>" type="checkbox"<?php checked( $instance['single'], 'on' ); ?> />
        	<label for="<?php echo $this->get_field_id( 'form' ); ?>"><?php _e( 'Enable in Posts', 'woothemes' ); ?></label>
	   	</p>
	   	<!-- Widget Enable In Pages: Checkbox Input -->
       	<p>
        	<input id="<?php echo $this->get_field_id( 'page' ); ?>" name="<?php echo $this->get_field_name( 'page' ); ?>" type="checkbox"<?php checked( $instance['page'], 'on' ); ?> />
        	<label for="<?php echo $this->get_field_id( 'form' ); ?>"><?php _e( 'Enable in Pages', 'woothemes' ); ?></label>
	   	</p>
<?php
	} // End form()
	
} // End Class

/*----------------------------------------
  Register the widget on `widgets_init`.
  ----------------------------------------
  
  * Registers this widget.
----------------------------------------*/

add_action( 'widgets_init', create_function( '', 'return register_widget("Woo_Widget_Subscribe");' ), 1 ); 
?>