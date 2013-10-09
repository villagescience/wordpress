<?php
/*---------------------------------------------------------------------------------*/
/* Twitter widget */
/*---------------------------------------------------------------------------------*/
class Woo_Twitter extends WP_Widget {
	var $settings = array( 'title', 'limit', 'username' );

	function Woo_Twitter() {
		$widget_ops = array( 'description' => 'Add your Twitter feed to your sidebar with this widget.' );
		parent::WP_Widget( false, __( 'Woo - Twitter Stream', 'woothemes' ), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		$instance = $this->woo_enforce_defaults( $instance );
		extract( $instance, EXTR_SKIP );

		$unique_id = $args['widget_id'];
		echo $before_widget;
		if ( $title ) {
			echo $before_title . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $after_title;
		} else {
			?><h3 class="tlogo"><img src="<?php bloginfo('template_directory'); ?>/images/twitter.png" alt="Twitter" /><?php _e(' Twitter','woothemes'); ?></h3><?php
		}
		?><div class="back"><ul id="twitter_update_list_<?php echo $unique_id; ?>"><li></li></ul>
			<p><?php _e('Follow','woothemes'); ?> <a href="http<?php ( is_ssl() ? 's' : '' ) ?>://twitter.com/<?php echo $username; ?>"><strong>@<?php echo $username; ?></strong></a> <?php _e('on Twitter','woothemes'); ?></p></div><div class="clear"></div><?php

		echo woo_twitter_script($unique_id,$username,$limit); //Javascript output function
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$new_instance = $this->woo_enforce_defaults( $new_instance );
		return $new_instance;
	}

	function woo_enforce_defaults( $instance ) {
		$defaults = $this->woo_get_settings();
		$instance = wp_parse_args( $instance, $defaults );
		$instance['title'] = strip_tags( $instance['title'] ); // Not for security so much as to give them feedback that HTML isn't allowed
		$instance['username'] = preg_replace( '|[^a-zA-Z0-9_]|', '', $instance['username'] );
		$instance['limit'] = intval( $instance['limit'] );
		if ( $instance['limit'] < 1 )
			$instance['limit'] = 5;
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
		$settings['limit'] = 5;
		return $settings;
	}

	function form( $instance ) {
		$instance = $this->woo_enforce_defaults( $instance );
		extract( $instance, EXTR_SKIP );
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (optional):','woothemes'); ?></label>
				<input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo esc_attr( $title ); ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Username:','woothemes'); ?></label>
				<input type="text" name="<?php echo $this->get_field_name('username'); ?>"  value="<?php echo esc_attr( $username ); ?>" class="widefat" id="<?php echo $this->get_field_id('username'); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit:','woothemes'); ?></label>
				<input type="text" name="<?php echo $this->get_field_name('limit'); ?>"  value="<?php echo esc_attr( $limit ); ?>" class="" size="3" id="<?php echo $this->get_field_id('limit'); ?>" />
			</p>
		<?php
	}

}

register_widget( 'Woo_Twitter' );
