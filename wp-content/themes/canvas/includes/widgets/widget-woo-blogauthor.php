<?php
/*-----------------------------------------------------------------------------------

CLASS INFORMATION

Description: A custom WooThemes Blog Author Info widget.
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

class Woo_Widget_BlogAuthorInfo extends WP_Widget {

	/*----------------------------------------
	  Constructor.
	  ----------------------------------------
	  
	  * The constructor. Sets up the widget.
	----------------------------------------*/
	
	function Woo_Widget_BlogAuthorInfo () {
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_woo_blogauthorinfo', 'description' => __( 'This is a WooThemes Blog Author Info widget.', 'woothemes' ) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'woo_blogauthorinfo' );

		/* Create the widget. */
		$this->WP_Widget( 'woo_blogauthorinfo', __('Woo - Blog Author Info', 'woothemes' ), $widget_ops, $control_ops );
		
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
		
		$bio = $instance['bio'];
		$custom_email = $instance['custom_email'];
		$avatar_size = $instance['avatar_size']; if ( ! $avatar_size ) { $avatar_size = 48; }
		$avatar_align = $instance['avatar_align']; if ( ! $avatar_align ) { $avatar_align = 'left'; }
		$read_more_text = $instance['read_more_text'];
		$read_more_url = $instance['read_more_url'];
		$page = $instance['page'];
		
		/* Determine whether or not to display the widget, depending on the "page" setting. */
		$display_widget = false;
		
		if ( ( $page == 'home' && ( is_home() || is_front_page() ) ) || ( $page == 'single' && is_singular() ) || $page == 'all' ) {
			$display_widget = true;
		} // End IF Statement
		
		if ( $display_widget == true ) { 
		
			/* Before widget (defined by themes). */
			echo $before_widget;
	
			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title ) {
			
				echo $before_title . $title . $after_title;
			
			} // End IF Statement
			
			/* Widget content. */
			
			// Add actions for plugins/themes to hook onto.
			do_action( 'woo_widget_blogauthorinfo_top' );
			
			$html = '';
			
			/* Optionally display the Gravatar. */
			if ( $custom_email != '' && is_email( $custom_email ) ) {
				$html .=  '<span class="' . $avatar_align . '">' . get_avatar( $custom_email, $avatar_size ) . '</span>' . "\n";
			}
			
			/* Optionally display the bio. */
			if ( $bio != '' ) {
				$html .= '<p>' . $bio . '</p>' . "\n";
			}
			
			/* Optionally display the "read more" link. */
			if ( $read_more_url != '' ) {
				$html .= '<p><a href="' . $read_more_url . '">' . $read_more_text . '</a></p>' . "\n";
			}

			$html .= '<div class="fix"></div>' . "\n";
			
			echo $html;
			
			// Add actions for plugins/themes to hook onto.
			do_action( 'woo_widget_blogauthorinfo_bottom' );
	
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
		$instance['bio'] = wp_kses_post( $new_instance['bio'] );
		$instance['custom_email'] = esc_attr( $new_instance['custom_email'] );
		$instance['avatar_size'] = esc_attr( $new_instance['avatar_size'] );
		$instance['avatar_align'] = esc_attr( $new_instance['avatar_align'] );
		$instance['read_more_text'] = esc_attr( $new_instance['read_more_text'] );
		$instance['read_more_url'] = esc_attr( $new_instance['read_more_url'] );
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

   function form( $instance ) {        
   
		/* Set up some default widget settings. */
		$defaults = array(
						'title' => __( 'About The Author', 'woothemes' ), 
						'bio' => '', 
						'custom_email' => '', 
						'avatar_size' => 48, 
						'avatar_align' => 'left', 
						'read_more_text' => __( 'Read More', 'woothemes' ), 
						'read_more_url' => '', 
						'page' => 'all'
					);

		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<!-- Widget Title: Text Input -->
		<p>
		   <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'woothemes' ); ?></label>
		   <input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>"  value="<?php echo $instance['title']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" />
		</p>
		<!-- Widget Bio: Textarea -->
		<p>
		   <label for="<?php echo $this->get_field_id( 'bio' ); ?>"><?php _e( 'Bio:', 'woothemes' ); ?></label>
			<textarea name="<?php echo $this->get_field_name( 'bio' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'bio' ); ?>"><?php echo $instance['bio']; ?></textarea>
		</p>
		<!-- Widget Custom Email: Text Input -->
		<p>
		   <label for="<?php echo $this->get_field_id( 'custom_email' ); ?>"><?php _e( '<a href="http://www.gravatar.com/">Gravatar</a> E-mail:', 'woothemes' ); ?></label>
		   <input type="text" name="<?php echo $this->get_field_name( 'custom_email' ); ?>"  value="<?php echo esc_attr( $instance['custom_email'] ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'custom_email' ); ?>" />
		</p>
		<!-- Widget Avatar Size: Text Input -->
		<p>
		   <label for="<?php echo $this->get_field_id( 'avatar_size' ); ?>"><?php _e( 'Gravatar Size:', 'woothemes' ); ?></label>
		   <input type="text" name="<?php echo $this->get_field_name( 'avatar_size' ); ?>"  value="<?php echo $instance['avatar_size']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'avatar_size' ); ?>" />
		</p>
		<!-- Widget Avatar Align: Select Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'avatar_align' ); ?>"><?php _e( 'Gravatar Alignment:', 'woothemes' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'avatar_align' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'avatar_align' ); ?>">
				<option value="left"<?php selected( $instance['avatar_align'], 'left' ); ?>><?php _e( 'Left', 'woothemes' ); ?></option>
				<option value="right"<?php selected( $instance['avatar_align'], 'right' ); ?>><?php _e( 'Right', 'woothemes' ); ?></option>            
			</select>
		</p>
		<!-- Widget Read More Text: Text Input -->
		<p>
		   <label for="<?php echo $this->get_field_id( 'read_more_text' ); ?>"><?php _e( 'Read More Text (optional):', 'woothemes' ); ?></label>
		   <input type="text" name="<?php echo $this->get_field_name( 'read_more_text' ); ?>"  value="<?php echo $instance['read_more_text']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'read_more_text' ); ?>" />
		</p>
		<!-- Widget Read More URL: Text Input -->
		<p>
		   <label for="<?php echo $this->get_field_id( 'read_more_url' ); ?>"><?php _e( 'Read More URL (optional):', 'woothemes' ); ?></label>
		   <input type="text" name="<?php echo $this->get_field_name( 'read_more_url' ); ?>"  value="<?php echo $instance['read_more_url']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'read_more_url' ); ?>" />
		</p>
		<!-- Widget Page: Select Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'page' ); ?>"><?php _e( 'Visible Pages:', 'woothemes' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'page' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'page' ); ?>">
				<option value="all"<?php selected( $instance['page'], 'all' ); ?>><?php _e( 'All', 'woothemes' ); ?></option>
				<option value="home"<?php selected( $instance['page'], 'home' ); ?>><?php _e( 'Home only', 'woothemes' ); ?></option>            
				<option value="single"<?php selected( $instance['page'], 'single' ); ?>><?php _e( 'Single only', 'woothemes' ); ?></option>            
			</select>
		</p>
<?php
	} // End form()
	
} // End Class

/*----------------------------------------
  Register the widget on `widgets_init`.
  ----------------------------------------
  
  * Registers this widget.
----------------------------------------*/

add_action( 'widgets_init', create_function( '', 'return register_widget("Woo_Widget_BlogAuthorInfo");' ), 1 ); 
?>