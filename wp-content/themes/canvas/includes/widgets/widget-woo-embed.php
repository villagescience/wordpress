<?php
/*-----------------------------------------------------------------------------------

CLASS INFORMATION

Description: A custom WooThemes Embed widget.
Date Created: 2010.
Last Modified: 2011-05-05.
Author: WooThemes.
Since: 1.0.0


TABLE OF CONTENTS

- function (constructor)
- function widget ()
- function update ()
- function form ()

- Register the widget on `widgets_init`.

-----------------------------------------------------------------------------------*/


class Woo_Widget_Embed extends WP_Widget {

	/*----------------------------------------
	  Constructor.
	  ----------------------------------------
	  
	  * The constructor. Sets up the widget.
	----------------------------------------*/
	
	function Woo_Widget_Embed () {
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_woo_embed', 'description' => __( 'This is a WooThemes standardized embed widget. It displays the video embed codes from your posts in a tab-like fashion.', 'woothemes' ) );

		/* Widget control settings. */
		$control_ops = array( 'id_base' => 'woo_embed' );

		/* Create the widget. */
		$this->WP_Widget( 'woo_embed', __('Woo - Embed/Video', 'woothemes' ), $widget_ops, $control_ops );
		
	} // End Constructor

	/*----------------------------------------
	  widget()
	  ----------------------------------------
	  
	  * Displays the widget on the frontend.
	----------------------------------------*/

	function widget( $args, $instance ) {
	 
		extract( $args ); 
		$title = $instance['title'];
		$limit = $instance['limit'];
		
		$cat_id = $instance['cat_id'];
		$tag = $instance['tag'];
		
		$width = 100;
				
		if(!empty($tag))
			$myposts = get_posts( "numberposts=$limit&tag=$tag" );
		else
			$myposts = get_posts( "numberposts=$limit&cat=$cat_id" );

		$post_list = '';
		$count = 0;
		$active = "active";
		$display = "";

        echo $before_widget; ?>
       
        <?php

			echo $before_title .$title. $after_title; ?>

            <?php    
		
			// Add actions for plugins/themes to hook onto.
			do_action( 'widget_woo_embed_top' );
		
			if(isset($myposts)) {
			
				foreach($myposts as $mypost) {
				
					$embed = woo_embed( 'width='.$width.'&key=embed&class=widget_video&id='.$mypost->ID);

					if($embed) {
						$count++;
						if($count > 1) {$active = ''; $display = "style='display:none'"; }
						?>
						<div class="widget-video-unit" <?php echo $display; ?> >
						<?php
							echo '<h4>' . get_the_title($mypost->ID)  . "</h4>\n";
							
							echo $embed;
							
							$post_list .= "<li class='$active'><a href='#'>" . get_the_title($mypost->ID) . "</a></li>\n";
						?>
						</div>
						<?php
					}
				}
			}
		?>
        <ul class="widget-video-list">
        	<?php echo $post_list; ?>
        </ul>

        <?php
			
		// Add actions for plugins/themes to hook onto.
		do_action( 'widget_woo_embed_bottom' );
			
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
		
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['limit'] = intval( $new_instance['limit'] );
		$instance['cat_id'] = esc_attr( $new_instance['cat_id'] );
		$instance['tag'] = esc_attr( $new_instance['tag'] );
		
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
						'title' => __( 'Recent Videos', 'woothemes' ), 
						'limit' => 10, 
						'cat_id' => '', 
						'tag' => ''
					);

		$instance = wp_parse_args( (array) $instance, $defaults );
?>
        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'woothemes' ); ?></label>
            <input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" />
        </p>
        <!-- Widget Category ID: Select Input -->
        <p>
	   	   <label for="<?php echo $this->get_field_id( 'cat_id' ); ?>"><?php _e( 'Category:', 'woothemes' ); ?></label>
	       <?php $cats = get_categories(); ?>
	       <select name="<?php echo $this->get_field_name( 'cat_id' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'cat_id' ); ?>">
           <option value="">Disabled</option>
			<?php
			
           	foreach ($cats as $cat){
           	?><option value="<?php echo $cat->cat_ID; ?>" <?php selected( $cat->cat_ID, $instance['cat_id'] ); ?>><?php echo $cat->cat_name . ' ( ' . $cat->category_count . ')'; ?></option><?php
           	}
           ?>
           </select>
        </p>
       <!-- Widget Tag: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id( 'tag' ); ?>">Or <?php _e( 'Tag:', 'woothemes' ); ?></label>
            <input type="text" name="<?php echo $this->get_field_name( 'tag' ); ?>" value="<?php echo $instance['tag']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'tag' ); ?>" />
        </p>
        <!-- Widget Limit: Text Input -->
         <p>
            <label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Limit (optional):', 'woothemes' ); ?></label>
            <input type="text" name="<?php echo $this->get_field_name( 'limit' ); ?>" value="<?php echo $instance['limit']; ?>" class="" id="<?php echo $this->get_field_id( 'limit' ); ?>" />
        </p>

<?php
	} // End form()
	
} // End Class

/*----------------------------------------
  Register the widget on `widgets_init`.
  ----------------------------------------
  
  * Registers this widget.
----------------------------------------*/

add_action( 'widgets_init', create_function( '', 'return register_widget("Woo_Widget_Embed");' ), 1 ); 
?>
<?php
if(is_active_widget( null,null,'woo_embed' ) == true) {
	add_action( 'wp_footer','woo_widget_embed_js' );
}

function woo_widget_embed_js(){
?>
<!-- Woo Video Player Widget -->
<script type="text/javascript">
	jQuery(document).ready(function(){
		var list = jQuery('ul.widget-video-list');
		list.find('a').click(function(){
			var clickedTitle = jQuery(this).text();
			jQuery(this).parent().parent().find('li').removeClass('active');
			jQuery(this).parent().addClass('active');
			var videoHolders = jQuery(this).parent().parent().parent().children('.widget-video-unit');
			videoHolders.each(function(){
				if(clickedTitle == jQuery(this).children('h4').text()){
					videoHolders.hide();
					jQuery(this).show();
				}
			})
			return false;
		})
	})
</script>
<?php
}


?>