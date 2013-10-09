<?php
	global $woo_options;
	
	$layout_width = '940px';
	$layout_type = 'two-col-left';
	$images_dir =  get_template_directory_uri() . '/functions/images/';
	$l_w_test = get_option('woo_layout_width');
	$l_t_test = get_option('woo_layout');
	
	if ( isset( $l_w_test ) && $l_w_test != '' ) {
		$layout_width = get_option('woo_layout_width');
	}
	
	if ( isset( $l_t_test ) && $l_t_test != '' ) {
		$layout_type = get_option('woo_layout');
	}
	
	// Setup settings to be loaded via woothemes_machine().
	
	$options = array();
	
	$options[] = array( "name" => "Layout",
						"icon" => "layout",
						"type" => "heading");
		
		$options[] = array( "name" => __( 'Enable Layout Manager', 'woothemes' ),
					"desc" => __( 'Enable the Layout Manager which will output CSS based on selections below.', 'woothemes' ),
					"id" => $this->woo_options_prefix."_layout_manager_enable",
					"std" => "false",
					"type" => "checkbox");
								
	// Run the woothemes_machine() to generate the XHTML.
	
	$settings_output = woothemes_machine( $options );
?>
<div class="wrap" id="woo_container">
<form method="post" action="<?php echo admin_url( 'admin.php?page=woo-layout-manager&updated=true' ); ?>" id="wooform">
<?php
	// Add nonce for added security.
	if ( function_exists( 'wp_nonce_field' ) ) { wp_nonce_field( 'woolayout-options-update' ); } // End IF Statement
?>
<?php if ( isset( $_GET['updated'] ) && $_GET['updated'] == 'true' ) { echo '<div class="updated fade"><p><strong>' . __( 'Woo Layout Manager Settings Updated.', 'woothemes' ) . '</strong></p></div>'; } ?>
<?php if ( isset( $_GET['reset'] ) && $_GET['reset'] == 'true' ) { echo '<div class="updated fade"><p><strong>' . __( 'Woo Layout Manager Settings Reset.', 'woothemes' ) . '</strong></p></div>'; } ?>
<div id="header">
           <div class="logo">
				<?php if(get_option('framework_woo_backend_header_image')) { ?>
                <img alt="" src="<?php echo get_option('framework_woo_backend_header_image'); ?>"/>
                <?php } else { ?>
                <img alt="WooThemes" src="<?php echo get_template_directory_uri(); ?>/functions/images/logo.png" />
                <?php } ?>
            </div>
             <div class="theme-info">
             	<span class="theme"><?php echo __( 'WooThemes Layout Manager', 'woothemes' ); ?></span>
				<span class="framework"><?php printf( __( 'Version %s', 'woothemes' ), $this->version ); ?></span>
			</div>
			<div class="clear"></div>
		</div>
		<div id="support-links">
			<ul>
				<li class="forum"><a href="<?php echo esc_url( 'http://www.woothemes.com/support-forum/' ); ?>" target="_blank"><?php _e( 'Visit Forum', 'woothemes' ); ?></a></li>
                <li class="right"><img style="display:none" src="<?php echo get_template_directory_uri(); ?>/functions/images/loading-top.gif" class="ajax-loading-img ajax-loading-img-top" alt="<?php echo esc_attr( __( 'Working...', 'woothemes' ) ); ?>" /><input name="woolayout_update" type="submit" value="Save All Changes" class="button submit-button" /></li>
			</ul>
		</div>
        <div id="main">
        	<div id="woo-nav">
				<ul>
					<?php
						// Output navigation menu.
						$menu = '';
						$count = 0;
						
						$menu_items = array();
						$menu_items['layout'] = __( 'Layouts', 'woothemes' );
						
						foreach ( $menu_items as $k => $v ) {
							
							$css_class = $k . ' general';
							
							if ( $count == 0 ) { $css_class .= ' current'; }	
							
							$menu .= '<li class="' . $css_class . '">' . "\n";
							$menu .= '<div class="arrow"><div></div></div>' . "\n";
							$menu .= '<span class="icon"></span>' . "\n";
							$menu .= '<a href="#' . $k . '">' . $v . '</a></li>' . "\n";
							
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
						
					$html .= '<h3 class="heading custom-info-heading">Please Note:</h3>';
					$html .= '<div class="custom-info-content"><p><strong>' . __( 'The Layout Manager is still in BETA stage. Please use at own risk and report any possible bugs in our support forum.', 'woothemes' ) . '</strong></p>' . "\n";
					$html .= '<p>' . __( 'You can select the column widths for every layout in Canvas with the Layout Manager. To specify your column widths, choose one of the available layouts, and drag the column border to set the width', 'woothemes' ) . '.</p>' . "\n";
					$html .= '<p>' . sprintf( __( 'You can choose which layout to use in your <a href="%s">Options Panel</a> under Layout or on a specific post or page in the Custom Settings panel.', 'woothemes' ), admin_url( 'admin.php?page=woothemes' ) ) . '.</p></div>' . "\n";
					
					$html .= $settings_output[0];
						
					$html .= '<div id="layout" class="content-section group layout-group">' . "\n";
						$html .= '<div id="layout-width-notice">' . "\n";
						$html .= '<p><em>' . __( 'Your current layout width is', 'woothemes' ) . ' <strong class="layout-width-value">' . $layout_width . '</strong> ' . __( 'and your current layout type is', 'woothemes' ) . ' <strong id="layout-type" class="' . $layout_type . '">' . $this->layouts_info[$layout_type]['name'] . '</strong>.</em></p>' . "\n";
						$html .= '<input type="hidden" name="woo-framework-image-dir" value="' . $images_dir . '" />' . "\n";
						$html .= '<input type="hidden" name="woo-gutter" value="' . $this->gutter . '" />' . "\n";
					$html .= '</div><!--/#layout-width-notice-->' . "\n";

						foreach ( $this->layouts_info as $k => $v ) {
							
							$html .= '<div id="' . $k . '" class="section">' . "\n";
								$html .= '<h3 class="heading">' . $v['name'] . '</h3><!--/.heading-->' . "\n";
								$html .= '<div class="controls">' . "\n";
									
									if ( array_key_exists( $k, (array)$this->layouts ) ) {
										
										foreach ( $this->layouts[$k] as $i => $j ) {
											$html .= '<label for="">"' . ucwords( $i ) . '" ' . __( 'Column', 'woothemes' ) . '</label>' . "\n";
											$html .= '<input id="layouts-' . $k . '-' . $i . '" name="layouts[' . $k . '][' . $i . ']" value="' . intval( $j ) . '" maxlength="3" class="input-text-small woo-input" />%' . "\n";
											$html .= '<div class="clear"></div><!--/.clear-->';
										}
										
									}
								
								$html .= '</div><!--/.controls-->' . "\n";
								
								if ( array_key_exists( 'description', $v ) ) {
									$html .= '<div class="explain">' . "\n";
										$html .= $v['description'] . "\n";
									$html .= '</div><!--/.explain-->' . "\n";
								}
								
							$html .= '</div><!--/.section-->' . "\n";
							$html .= '<div class="clear"></div><!--/.clear-->' . "\n";
						}
					$html .= '</div>' . "\n";
				
				echo $html;
			?>
        </div>
        		<div class="clear"></div>
        </div>		
        <div class="save_bar_top">
        <img style="display:none" src="<?php echo get_template_directory_uri(); ?>/functions/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="<?php echo esc_attr( __( 'Working...', 'woothemes' ) ); ?>" />
        <input name="woolayout_update" type="submit" value="<?php echo esc_attr( __( 'Save All Changes', 'woothemes' ) ); ?>" class="button submit-button" />        
        
            <span class="submit-footer-reset">
            <input name="woolayout_reset" type="submit" value="<?php echo esc_attr( __( 'Reset Layout Options', 'woothemes' ) ); ?>" class="button submit-button reset-button alignleft" />
            </span>
       
        </div>
	</form>
</div><!--wrap-->