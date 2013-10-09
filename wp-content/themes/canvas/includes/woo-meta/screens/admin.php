<div class="wrap" id="woo_container">
<form method="post" action="<?php echo admin_url( 'admin.php?page=woo-meta-manager&updated=true' ); ?>" id="wooform">
<?php
	// Add nonce for added security.
	if ( function_exists( 'wp_nonce_field' ) ) { wp_nonce_field( 'woometa-options-update' ); } // End IF Statement
?>
<?php if ( isset( $_GET['updated'] ) && $_GET['updated'] == 'true' ) { echo '<div class="updated fade"><p><strong>' . __( 'Woo Meta Manager Settings Updated.', 'woothemes' ) . '</strong></p></div>'; } ?>
<?php if ( isset( $_GET['reset'] ) && $_GET['reset'] == 'true' ) { echo '<div class="updated fade"><p><strong>' . __( 'Woo Meta Manager Settings Reset.', 'woothemes' ) . '</strong></p></div>'; } ?>
<div id="header">
           <div class="logo">
				<?php if(get_option('framework_woo_backend_header_image')) { ?>
                <img alt="" src="<?php echo get_option('framework_woo_backend_header_image'); ?>"/>
                <?php } else { ?>
                <img alt="WooThemes" src="<?php echo get_template_directory_uri(); ?>/functions/images/logo.png"/>
                <?php } ?>
            </div>
             <div class="theme-info">
             	<span class="theme"><?php echo __( 'WooThemes Meta Manager', 'woothemes' ); ?></span>
				<span class="framework"><?php printf( __( 'Version %s', 'woothemes' ), $this->version ); ?></span>
			</div>
			<div class="clear"></div>
		</div>
		<div id="support-links">
			<ul>
				<li class="forum"><a href="<?php echo esc_url( 'http://www.woothemes.com/support-forum/' ); ?>" target="_blank"><?php _e( 'Visit Forum', 'woothemes' ); ?></a></li>
                <li class="right"><img style="display:none" src="<?php echo get_template_directory_uri(); ?>/functions/images/loading-top.gif" class="ajax-loading-img ajax-loading-img-top" alt="<?php echo esc_attr( __( 'Working...', 'woothemes' ) ); ?>" /><input name="woometa_update" type="submit" value="Save All Changes" class="button submit-button" /></li>
			</ul>
		</div>
        <div id="main">
	        <div id="woo-nav">
				<ul>
					<?php
						// Output navigation menu.
						
						$menu = '';
						$count = 0;
						
						foreach ( array_keys( $this->meta_areas ) as $k ) {
							$title = str_replace( '_', ' ', $k );
							
							$css_class = $k . ' general';
							
							if ( $count == 0 ) { $css_class .= ' current'; }	
							
							$menu .= '<li class="' . $css_class . '"><a href="#' . $k . '">' . ucwords( $title ) . '</a></li>' . "\n";
							
							$count++;
						}
						
						echo $menu;
					?>
				</ul>		
			</div>
			<div id="content">
			<?php
				// Output sections.
				
				$html = '';
				
				foreach ( $this->meta_areas as $k => $v ) {
				
					$title = str_replace( '_', ' ', $k );
				
					$html .= '<div id="' . $k . '" class="content-section group">' . "\n";
						$html .= '<h2 class="title">' . ucwords( $title ) . '</h2>' . "\n";
						foreach ( $v as $i => $j ) {
							$html .= '<div class="section">' . "\n";
								$html .= '<h3 class="heading">' . $j['title'] . '</h3><!--/.heading-->' . "\n";
								$html .= '<div class="controls">' . "\n";
									$html .= '<textarea id="' . $i . '" name="' . $i . '" rows="5" cols="40">' . stripslashes( $j['stored_value'] ) . '</textarea>' . "\n";
								$html .= '</div><!--/.controls-->' . "\n";
								
								if ( array_key_exists( 'description', $j ) ) {
									$html .= '<div class="explain">' . "\n";
										$html .= $j['description'] . "\n";
									$html .= '</div><!--/.explain-->' . "\n";
								}
								
							$html .= '</div><!--/.section-->' . "\n";
							$html .= '<div class="clear"></div><!--/.clear-->' . "\n";
						}
					$html .= '</div>' . "\n";
				
				}
				
				echo $html;
			?>
			<div id="shortcode-reference" class="section">
				<h3 class="heading"><?php _e( 'Shortcode Reference', 'woothemes' ); ?></h3>
				<p><?php _e( 'Use these shortcodes to include dynamic data into your meta sections.', 'woothemes' ); ?></p>
				<ul>
					<?php
						$html = '';
						
						foreach ( $this->shortcodes as $k => $v ) {
							$html .= '<li><code>[' . $k . ']</code> - ' . $v . '</li>' . "\n";
						}
						
						echo $html;
					?>
				</ul>
        	</div><!--/#shortcode-reference-->	
        </div>
        		<div class="clear"></div>
        </div>		
        <div class="save_bar_top">
        <img style="display:none" src="<?php echo get_template_directory_uri(); ?>/functions/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="<?php echo esc_attr( __( 'Working...', 'woothemes' ) ); ?>" />
        <input name="woometa_update" type="submit" value="<?php echo esc_attr( __( 'Save All Changes', 'woothemes' ) ); ?>" class="button submit-button" />        
        
            <span class="submit-footer-reset">
            <input name="woometa_reset" type="submit" value="<?php echo esc_attr( __( 'Reset Meta Options', 'woothemes' ) ); ?>" class="button submit-button reset-button alignleft" />
            </span>
       
        </div>
	</form>
</div><!--wrap-->