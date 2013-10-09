<?php
/*-----------------------------------------------------------------------------------

CLASS INFORMATION

Description: A custom WooThemes Flickr widget.
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

class Woo_Widget_Flickr extends WP_Widget {

	/*----------------------------------------
	  Constructor.
	  ----------------------------------------
	  
	  * The constructor. Sets up the widget.
	----------------------------------------*/
	
	function Woo_Widget_Flickr () {
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_woo_flickr', 'description' => __( 'This Flickr widget populates photos from a Flickr ID.', 'woothemes' ) );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'woo_flickr' );

		/* Create the widget. */
		$this->WP_Widget( 'woo_flickr', __('Woo - Flickr', 'woothemes' ), $widget_ops, $control_ops );
		
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
		
		$number = $instance['number']; if ( ! intval( $number ) ) { $number = 5; }
		$id = $instance['id'];
		$sorting = $instance['sorting'];
		$type = $instance['type'];
		$size = $instance['size'];
		
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Use the default title if no title is set. */
		if ( ! $title ) { $title = __( 'Photos on', 'woothemes' ) . ' <span>flick<span>r</span></span>'; }

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title ) {
		
			echo $before_title . $title . $after_title;
		
		} // End IF Statement
		
		/* Widget content. */
		
		// Add actions for plugins/themes to hook onto.
		do_action( 'widget_woo_flickr_top' );
		
		$html = '';
		
		/* Construct the remainder of the query string, using only the non-empty fields. */
		$fields = array(
						'count' => $number, 
						'display' => $sorting, 
						'source' => $type, 
						$type => $id, 
						'size' => $size
					);
					
		$query_string = '';
		
		foreach ( $fields as $k => $v ) {
			if ( $v == '' ) {} else {
				$query_string .= '&amp;' . $k . '=' . $v;
			}
		}
		
		$html .= '<div class="wrap">' . "\n";
			$html .= '<div class="fix"></div><!--/.fix-->' . "\n";
				$html .= '<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?layout=x' . $query_string . '"></script>' . "\n";
			$html .= '<div class="fix"></div><!--/.fix-->' . "\n";
		$html .= '</div><!--/.wrap-->' . "\n";
		
		echo $html;
		
		// Add actions for plugins/themes to hook onto.
		do_action( 'widget_woo_flickr_bottom' );

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
		$instance['id'] = strip_tags( $new_instance['id'] );
		$instance['number'] = intval( esc_attr( $new_instance['number'] ) );
		$instance['type'] = esc_attr( $new_instance['type'] );
		$instance['sorting'] = esc_attr( $new_instance['sorting'] );
		$instance['size'] = esc_attr( $new_instance['size'] );
		
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
						'title' => '', 
						'id' => '', 
						'number' => '', 
						'type' => 'user', 
						'sorting' => 'latest', 
						'size' => 's'
					);

		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (optional):', 'woothemes' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>"  value="<?php echo $instance['title']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" />
		</p>
		<!-- Widget Flickr ID: Text Input -->
		<p>
		    <label for="<?php echo $this->get_field_id( 'id' ); ?>"><?php _e( 'Flickr ID (<a href="http://www.idgettr.com">idGettr</a>):', 'woothemes' ); ?></label>
		    <input type="text" name="<?php echo $this->get_field_name( 'id' ); ?>" value="<?php echo $instance['id']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'id' ); ?>" />
		</p>
		<!-- Widget Number: Select Input -->
		<p>
		    <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number:', 'woothemes' ); ?></label>
		    <select name="<?php echo $this->get_field_name( 'number' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>">
		        <?php for ( $i = 1; $i <= 10; $i += 1) { ?>
		        <option value="<?php echo $i; ?>"<?php selected( $instance['number'], $i ); ?>><?php echo $i; ?></option>
		        <?php } ?>
		    </select>
		</p>
		<!-- Widget Type: Select Input -->
		<p>
		    <label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Type:', 'woothemes' ); ?></label>
		    <select name="<?php echo $this->get_field_name( 'type' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'type' ); ?>">
		        <option value="user"<?php selected( $instance['type'], 'user' ); ?>><?php _e( 'User', 'woothemes' ); ?></option>
		        <option value="group"<?php selected( $instance['type'], 'group' ); ?>><?php _e( 'Group', 'woothemes' ); ?></option>            
		    </select>
		</p>
		<!-- Widget Sorting: Select Input -->
		<p>
		    <label for="<?php echo $this->get_field_id( 'sorting' ); ?>"><?php _e( 'Sorting:', 'woothemes' ); ?></label>
		    <select name="<?php echo $this->get_field_name( 'sorting' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'sorting' ); ?>">
		        <option value="latest"<?php selected( $instance['sorting'], 'latest' ); ?>><?php _e( 'Latest', 'woothemes' ); ?></option>
		        <option value="random"<?php selected( $instance['sorting'], 'random' ); ?>><?php _e( 'Random', 'woothemes' ); ?></option>            
		    </select>
		</p>
		<!-- Widget Size: Select Input -->
		<p>
		    <label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Size:', 'woothemes' ); ?></label>
		    <select name="<?php echo $this->get_field_name( 'size' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'size' ); ?>">
		        <option value="s"<?php selected( $instance['size'], 's' ); ?>><?php _e( 'Square', 'woothemes' ); ?></option>
		        <option value="m"<?php selected( $instance['size'], 'm' ); ?>><?php _e( 'Medium', 'woothemes' ); ?></option>
		        <option value="t"<?php selected( $instance['size'], 't' ); ?>><?php _e( 'Thumbnail', 'woothemes' ); ?></option>
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

add_action( 'widgets_init', create_function( '', 'return register_widget("Woo_Widget_Flickr");' ), 1 ); 
?>