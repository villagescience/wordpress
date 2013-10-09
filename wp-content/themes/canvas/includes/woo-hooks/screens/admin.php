<div class="wrap" id="woo_container">
<form method="post" action="<?php echo admin_url( 'admin.php?page=woo-hook-manager&updated=true' ); ?>" id="wooform">
<?php
	// Add nonce for added security.
	if ( function_exists( 'wp_nonce_field' ) ) { wp_nonce_field( 'woohooks-options-update' ); } // End IF Statement
?>
<?php if ( isset( $_GET['updated'] ) && $_GET['updated'] == 'true' ) { echo '<div class="updated fade"><p><strong>' . __( 'Woo Hook Manager Settings Updated.', 'woothemes' ) . '</strong></p></div>'; } ?>
<?php if ( isset( $_GET['reset'] ) && $_GET['reset'] == 'true' ) { echo '<div class="updated fade"><p><strong>' . __( 'Woo Hook Manager Settings Reset.', 'woothemes' ) . '</strong></p></div>'; } ?>
<div id="header">
           <div class="logo">
				<?php if(get_option('framework_woo_backend_header_image')) { ?>
                <img alt="" src="<?php echo get_option('framework_woo_backend_header_image'); ?>"/>
                <?php } else { ?>
                <img alt="WooThemes" src="<?php echo get_template_directory_uri(); ?>/functions/images/logo.png"/>
                <?php } ?>
            </div>
             <div class="theme-info">
             	<span class="theme"><?php echo __( 'WooThemes Hook Manager', 'woothemes' ); ?></span>
				<span class="framework"><?php printf( __( 'Version %s', 'woothemes' ), $this->version ); ?></span>
			</div>
			<div class="clear"></div>
		</div>
		<div id="support-links">
			<ul>
				<li class="forum"><a href="<?php echo esc_url( 'http://www.woothemes.com/support-forum/' ); ?>" target="_blank"><?php _e( 'Visit Forum', 'woothemes' ); ?></a></li>
                <li class="right"><img style="display:none" src="<?php echo get_template_directory_uri(); ?>/functions/images/loading-top.gif" class="ajax-loading-img ajax-loading-img-top" alt="<?php echo esc_attr( __( 'Working...', 'woothemes' ) ); ?>" /><input name="woohooks_update" type="submit" value="Save All Changes" class="button submit-button" /></li>
			</ul>
		</div>
        <div id="main">
	        <div id="woo-nav">
				<ul>
					<?php
						// Output navigation menu.
						
						$menu = '';
						$count = 0;
						
						foreach ( array_keys( $this->hooks ) as $k ) {
							if ( in_array( $k, array_keys( $this->hook_titles ) ) ) {
								$title = $this->hook_titles[$k];
							} else {
								$title = str_replace( '_', ' ', $k );
							}
							
							$css_class = $k . ' general';
							
							if ( $count == 0 ) { $css_class .= ' current'; }
							
							$menu .= '<li class="' . $css_class . '"><a href="#' . $k . '-section">' . apply_filters( 'the_title', ucwords( $title ) ) . '</a></li>' . "\n";
							
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
				
				foreach ( $this->hooks as $k => $v ) {
				
					$title = str_replace( '_', ' ', $k );
				
					$html .= '<div id="' . $k . '-section" class="content-section group">' . "\n";
						$html .= '<h2 class="title">' . ucwords( $title ) . '</h2>' . "\n";
						foreach ( $v as $i => $j ) {
							$html .= '<div class="section">' . "\n";
								$html .= '<h3 class="heading"><code>' . $i . '</code></h3><!--/.heading-->' . "\n";
								$html .= '<div class="controls">' . "\n";
									$html .= '<textarea id="' . $i . '-content" name="' . $i . '[content]" rows="5" cols="40">' . stripslashes( $j['content'] ) . '</textarea>' . "\n";
									$html .= '<input id="' . $i . '-shortcodes" name="' . $i . '[shortcodes]" type="checkbox" value="1"' . checked( $j['shortcodes'], 1, false ) . ' class="checkbox woo-input alignleft" /><label for="' . $i . '[shortcodes]" class="explain">' . __( 'Execute Shortcodes on this Hook', 'woothemes' ) . '</label>' . "\n";
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
        </div>
        		<div class="clear"></div>
        </div>		
        <div class="save_bar_top">
        <img style="display:none" src="<?php echo get_template_directory_uri(); ?>/functions/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="<?php echo esc_attr( __( 'Working...', 'woothemes' ) ); ?>" />
        <input name="woohooks_update" type="submit" value="<?php echo esc_attr( __( 'Save All Changes', 'woothemes' ) ); ?>" class="button submit-button" />        
        
            <span class="submit-footer-reset">
            <input name="woohooks_reset" type="submit" value="<?php echo esc_attr( __( 'Reset Hook Options', 'woothemes' ) ); ?>" class="button submit-button reset-button alignleft" />
            </span>
       
        </div>
	</form>
</div><!--wrap-->