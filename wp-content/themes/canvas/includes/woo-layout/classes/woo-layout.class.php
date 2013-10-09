<?php
/**
 * Layout Manager
 *
 * This is the layout manager class, containing all processing and setup functionality
 * for managing the dimensions of the various layout types available.
 *
 * @package WooFramework
 * @subpackage Module
 * 
 *-----------------------------------------------------------------------------------
 *
 * CLASS INFORMATION
 *
 * Date Created: 2011-03-21.
 * Author: Matty.
 * Since: 4.0.0
 *
 *
 * TABLE OF CONTENTS
 *
 * - var $plugin_prefix
 * - var $plugin_path
 * - var $plugin_url
 * - var $version
 * 
 * - var $woo_options_prefix
 * 
 * - var $admin_page
 * 
 * - var $layouts
 * - var $layouts_info
 * 
 * - var $gutter
 * 
 * - function Woo_Layout () (Constructor)
 * - function init ()
 * - function register_admin_screen ()
 * - function admin_screen ()
 * - function admin_screen_help ()
 * - function enqueue_scripts ()
 * - function enqueue_styles ()
 * - function enqueue_frontend_styles ()
 * - function get_layout_info ()
 * - function load_dynamic_css ()
 * - function setup_layouts ()
 * - function setup_layout_information ()
 * - function add_exporter_data ()
 *
 *-----------------------------------------------------------------------------------*/

class Woo_Layout {

	/*----------------------------------------
	  Class Variables
	  ----------------------------------------
	  
	  * Setup of variable placeholders, to be
	  * populated when the constructor runs.
	----------------------------------------*/

	var $plugin_prefix;
	var $plugin_path;
	var $plugin_url;
	var $version;

	var $woo_options_prefix;
	
	var $admin_page;

	var $layouts;
	var $layouts_info;
	
	var $gutter;

	/*----------------------------------------
 	  Constructor Function
 	  ----------------------------------------
 	  
 	  * Constructor function.
 	  * Sets up the class and registers
 	  * variable action hooks.
 	  
 	  * Params:
 	  * - String $plugin_prefix
 	  * - String $plugin_path
 	  * - String $plugin_url
 	----------------------------------------*/

	function Woo_Layout ( $plugin_prefix, $plugin_path, $plugin_url, $version ) {
	
		$this->plugin_prefix = $plugin_prefix;
		$this->plugin_path = $plugin_path;
		$this->plugin_url = $plugin_url;
		$this->version = $version;
		$this->woo_options_prefix = 'woo';
	
		$this->init();
	
	} // End Constructor
	
	/*----------------------------------------
 	  init()
 	  ----------------------------------------
 	  
 	  * This guy runs the show.
 	  * Rocket boosters... engage!
 	----------------------------------------*/
	
	function init () {
		
		// Enqueue the dynamic CSS data.
		if ( get_option( $this->woo_options_prefix . '_layout_manager_enable' ) == 'true' )
			add_action( 'wp_print_styles', array( &$this, 'enqueue_frontend_styles' ), 10 );
		
		if ( is_admin() ) {
	
			// Register the admin screen.
			add_action( 'admin_menu', array( &$this, 'register_admin_screen' ), 11 );
			
			// Execute certain code only on the specific admin screen.
			if ( is_admin( $this->admin_page ) ) {
			
				// Add contextual help.
				add_action( 'contextual_help', array( &$this, 'admin_screen_help' ), 10, 3 );
			
			}
			
			// Make sure our data is added to the WooFramework settings exporter.
			add_filter( 'wooframework_export_query_inner', array( &$this, 'add_exporter_data' ) );
		
		} // End IF Statement
		
		// Setup default layouts.
		$this->setup_layouts();
		
		// Setup default layout information.
		$this->setup_layout_information();
		
		// Generate the dynamic CSS data.
		if ( get_option( $this->woo_options_prefix . '_layout_manager_enable') == 'true' )
			add_action( 'template_redirect', array( &$this, 'load_dynamic_css' ) );
	} // End init()

	/*----------------------------------------
 	  register_admin_screen()
 	  ----------------------------------------
 	  
 	  * Register the admin screen in WordPress.
 	----------------------------------------*/

	function register_admin_screen () {
		
		if ( function_exists( 'add_submenu_page' ) ) {	
			
			$this->admin_page = add_submenu_page('woothemes', __( 'Layout Manager', 'woothemes' ), __( 'Layout Manager', 'woothemes' ), 'manage_options', 'woo-layout-manager', array( &$this, 'admin_screen' ) );
			
			// Admin screen logic.
			add_action( 'load-' . $this->admin_page, array( &$this, 'admin_screen_logic' ) );
			
			// Admin screen JavaScript.
			add_action( 'admin_print_scripts-' . $this->admin_page, array( &$this, 'enqueue_scripts' ) );
			
			// Admin screen CSS.
			add_action( 'admin_print_styles-' . $this->admin_page, array( &$this, 'enqueue_styles' ) );
			
		}
	
	} // End register_admin_screen()
	
	/*----------------------------------------
 	  admin_screen()
 	  ----------------------------------------
 	  
 	  * Load the admin screen.
 	----------------------------------------*/
	
	function admin_screen () {
	
		// Keep the screen XHTML separate and load it from that file.
		include_once( $this->plugin_path . '/screens/admin.php' );
	
	} // End admin_screen()
	
	/*----------------------------------------
 	  admin_screen_help()
 	  ----------------------------------------
 	  
 	  * Contextual help on the admin screen.
 	----------------------------------------*/
	
	function admin_screen_help ( $contextual_help, $screen_id, $screen ) {
	
		// $contextual_help .= var_dump($screen); // use this to help determine $screen->id
		
		if ( $this->admin_page == $screen->id ) {
		
		$contextual_help =
		  '<p>' . __('Welcome to the Woo Layout Manager!', 'woothemes') . '</p>' .
		  '<p>' . __('Here are a few notes on using this screen.', 'woothemes') . '</p>' .
		  '<p>' . __('Fill in the area you\'d like to customise and hit the "Save All Changes" button. It\'s as easy as that!', 'woothemes') . '</p>' .
		  '<p><strong>' . __('For more information:', 'woothemes') . '</strong></p>' .
		  '<p>' . sprintf( __('<a href="%s" target="_blank">WooThemes Support Forums</a>', 'woothemes'), 'http://forum.woothemes.com/' ) . '</p>';
		
		} // End IF Statement
		
		return $contextual_help;
	
	} // End admin_screen_help()
	
	/*----------------------------------------
 	  admin_screen_logic()
 	  ----------------------------------------
 	  
 	  * The processing logic used on the
 	  * admin screen.
 	----------------------------------------*/
	
	function admin_screen_logic () {
		
		// Reset logic.
		
		$is_processed = false;
		
		if ( isset( $_POST['woolayout_reset'] ) && check_admin_referer( 'woolayout-options-update' ) ) {
			update_option( $this->plugin_prefix . 'stored_layouts' );
			update_option( $this->woo_options_prefix . '_layout_manager_enable', 'false');
			$is_processed = true;
			
			wp_redirect( admin_url( 'admin.php?page=woo-layout-manager&reset=true' ) );
		}
		
		// Save logic.
		
		if ( isset( $_POST['woolayout_update'] ) && check_admin_referer( 'woolayout-options-update' ) ) {
			
			$fields_to_skip = array( 'woolayout_update', '_wp_http_referer', '_wpnonce' );
			
			$posted_data = $_POST;
			
			// Update Layout Manager Enable
			if ( ( isset($_POST['woo_layout_manager_enable']) ) && ( $_POST['woo_layout_manager_enable'] == 'true'  )  ) {
				update_option( $this->woo_options_prefix . '_layout_manager_enable', 'true');
			} else {
				update_option( $this->woo_options_prefix . '_layout_manager_enable', 'false');
			}
			
			// Make sure we skip over the fields we don't need,
			// and validate the values that we do need to make sure
			// that they're all numeric values less than or equal to 100.
			
			foreach ( $posted_data as $k => $v ) {
				if ( in_array( $k, $fields_to_skip ) ) {
					unset( $posted_data[$k] );
				} else {
					
					// Get the woo_options array and update the necessary fields.
					$options = get_option( $this->woo_options_prefix . '_options' );
					$has_new_options = false;
					
					if ( is_array( $posted_data[$k] ) ) {
						
						$has_valid_values = true;
						
						// Validate the values.
						foreach ( $posted_data[$k] as $i => $j ) {
							foreach ( $posted_data[$k][$i] as $l => $m ) {
								if ( is_numeric( $m ) && ( $m <= 100 ) ) {} else { $has_valid_values = false; break; }
							}
						}
						
						// Set anything greater than 100 equal to 100.
						foreach ( $posted_data[$k] as $i => $j ) {
							foreach ( $posted_data[$k][$i] as $l => $m ) {
								if ( is_numeric( $m ) && ( $m <= 100 ) ) {} else { $posted_data[$k][$i][$l] = 100; }
							}
						}
						
						// Setup the values to be saved.
						if ( $has_valid_values ) {
							$posted_data[$k] = $v;
						}
						
						// Make sure that all values provided for each section add up to 100.
						foreach ( $posted_data[$k] as $i => $j ) {
							$total = 0;
							foreach ( $posted_data[$k][$i] as $l => $m ) {
								$total += $m;
							}
							
							if ( $total < 100 ) {
								$remainder = 100 - $total;
								$posted_data[$k][$i]['content'] += $remainder;
							}
						}
					} else {
					
						// Update non-layout options.
						update_option( $k, $v );
						
						if ( is_array( $options ) ) {
							$options[$k] = $v;
							$has_new_options = true;
						}
					
					}
					
					// If options in woo_options have been changed, update the woo_options array.
					if ( $has_new_options ) {
					
						update_option( $this->woo_options_prefix . '_options', $options );
					}
				}
			}
			
			if ( is_array( $posted_data ) ) {
				$is_updated = update_option( $this->plugin_prefix . 'stored_layouts', $posted_data );
				
				// Redirect to make sure the latest changes are reflected.
				wp_redirect( admin_url( 'admin.php?page=woo-layout-manager&updated=true' ) );
			}
			$is_processed = true;
		}
	
	} // End admin_screen_logic()
	
	/*----------------------------------------
 	  enqueue_scripts()
 	  ----------------------------------------
 	  
 	  * Enqueue the necessary JavaScript files.
 	----------------------------------------*/
	
	function enqueue_scripts () {

		wp_register_script( 'jquery-layout-min', $this->plugin_url . 'assets/js/jquery.layout.min.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-resizable' ), '1.2.0', true );

		wp_register_script( 'woo-layout-functions', $this->plugin_url . 'assets/js/functions.js', array( 'jquery', 'jquery-layout-min' ), '1.0.0', true );
		
		wp_enqueue_script( 'woo-layout-functions' ); // The dependant JavaScript files will be enqueued automatically.
	
	} // End enqueue_scripts()
	
	/*----------------------------------------
 	  enqueue_styles()
 	  ----------------------------------------
 	  
 	  * Enqueue the necessary CSS files.
 	----------------------------------------*/
	
	function enqueue_styles () {
	
		wp_register_style( 'woo-admin-interface', get_template_directory_uri() . '/functions/admin-style.css' );
		
		wp_register_style( 'woo-layout-interface', $this->plugin_url . '/assets/css/interface.css' );
		
		wp_enqueue_style( 'woo-admin-interface' );
		
		wp_enqueue_style( 'woo-layout-interface' );
	
	} // End enqueue_styles()
	
	/*----------------------------------------
 	  enqueue_frontend_styles()
 	  ----------------------------------------
 	  
 	  * Enqueue the dynamic CSS to be used on
 	  * the theme's frontend.
 	----------------------------------------*/
	
	function enqueue_frontend_styles () {
	
		$url = home_url();
		$sep = '?';
		if ( ! get_option( 'permalink_structure' ) || ( isset( $_GET['lang'] ) ) ) { $sep = '&'; }
		
		if ( is_singular() ) {
			global $post;
			
			$url = get_permalink( $post->ID );
		}	
		
		if ( stristr( $url, '?' ) ) {
			$sep = '&';
		} else {
			$sep = '?';
		}

		wp_register_style( 'woo-layout-dynamic', urldecode( trailingslashit( $url ) . $sep . 'woo-layout-css=load' ) );
		
		wp_enqueue_style( 'woo-layout-dynamic' );
	
	} // End enqueue_frontend_styles()
	
	/*----------------------------------------
 	  get_layout_info()
 	  ----------------------------------------
 	  
 	  * Get layout info for the current screen.
 	----------------------------------------*/
	
	function get_layout_info () {
	
		global $post, $woo_layout_manager;
		
		// Determine the width and layout type in use.
		$layout = 'two-col-left';
		$container_width = 940;
		$container_class = '';
		
		if ( is_singular() ) {
			$stored_layout = get_post_meta( $post->ID, 'layout', true );
			
			if ( $stored_layout ) { $layout = $stored_layout; }
		}

		$woo_layouts = get_option( 'woo_layout_' . 'stored_layouts' );
		$woo_options = get_option( 'woo_options' );
		
		$db_layout = get_option( 'woo_layout' );
		$db_layout_width = get_option( 'woo_layout_width' );
		
		if ( ! empty( $db_layout_width ) ) {
			$container_width = intval( $db_layout_width );
			if ( $container_width != '940px' ) { $container_class = '-' . $container_width; }
		}
		
		$data = array();
		$data['layout'] = $layout;
		$data['container_width'] = $container_width;
		$data['container_class'] = $container_class;
		$data['one_percent'] = ( $container_width / 100 );
		$data['gutter'] = 10;
		$data['width_main'] = ceil( ( $data['one_percent'] * $woo_layout_manager->layouts[$layout]['content'] ) - $data['gutter'] );
		if ( isset( $woo_layout_manager->layouts[$layout] ) && array_key_exists( 'primary', $woo_layout_manager->layouts[$layout] ) ) {
			$data['width_primary'] = ceil( ( $data['one_percent'] * $woo_layout_manager->layouts[$layout]['primary'] ) - $data['gutter'] );
		} else {
			$data['width_primary'] = 0;
		}
		if ( isset( $woo_layout_manager->layouts[$layout] ) && array_key_exists( 'secondary', $woo_layout_manager->layouts[$layout] ) ) {
			$data['width_secondary'] = ceil( ( $data['one_percent'] * $woo_layout_manager->layouts[$layout]['secondary'] ) - $data['gutter'] );
		} else {
			$data['width_secondary'] = 0;
		}
		
		if ( is_array( $woo_layouts ) && is_array( $woo_options ) ) {
			
			// If the selected page doesn't have a specific layout, use the default.
			if ( ! $stored_layout ) {
			
				if ( ! empty( $db_layout ) ) {
					$layout = $db_layout;
				}
				
			}
			
			$data['layout'] = $layout;
			$data['container_width'] = $container_width;
			$data['container_class'] = $container_class;
			$data['one_percent'] = ( $container_width / 100 );
			$data['gutter'] = 5;
			
			$data['width_main'] = intval( $woo_layouts['layouts'][$layout]['content'] );
			$data['width_primary'] = intval( $woo_layouts['layouts'][$layout]['primary'] );
			$data['width_secondary'] = intval( $woo_layouts['layouts'][$layout]['secondary'] );
		
		}
		
		return $data;
	
	} // End get_layout_info()
	
	/*----------------------------------------
 	  load_dynamic_css()
 	  ----------------------------------------
 	  
 	  * Load the dynamic CSS data.
 	----------------------------------------*/
	
	function load_dynamic_css () {
		
		if ( isset( $_GET['woo-layout-css'] ) && $_GET['woo-layout-css'] == 'load' ) {
			
			header( 'Content-Type: text/css' );
			
			// Determine the width and layout type in use.
			$layout = 'two-col-left';
			$container_width = 940;
			$container_class = '';
			
			$data = $this->get_layout_info();
			
			$layout = $data['layout'];
			$container_width = $data['container_width'];
			$container_class = $data['container_class'];
			
			$one_percent = $data['one_percent'];
			
			// Setup the default gutter spacing.
			$gutter = $data['gutter'];
			
			// Begin output of dynamic CSS.
			$css = '';
			
			// Begin media query
			$css .= '@media only screen and (min-width: 768px) {' . "\n";
			
			$width_main = $data['width_main'];
			$width_primary = $data['width_primary'];
			$width_secondary = $data['width_secondary'];
			$width_maincontainer = 0;
			// if ( $width_secondary ) { $width_maincontainer = ceil( ( $container_width - $width_secondary ) - $gutter ); }

			$width_maincontainer = $width_main + $width_primary;

			if ( $width_maincontainer ) {
				if ( $layout == 'three-col-middle' ) {
					$width_maincontainer -= 5;
				}
				$css .= '.' . $layout . $container_class . ' #main-sidebar-container { width: ' . $width_maincontainer . '%' . '; }' . "\n";
			}
			if ( $width_main ) {
				if ( $layout == 'two-col-left' ) {
					$width_main -= 5;
				}
				if ( $layout == 'two-col-right' ) {
					$width_main -= 5;
				}
				$css .= '.' . $layout . $container_class . ' #main-sidebar-container #main { width: ' . $width_main . '%' . '; }' . "\n";
			}
			if ( $width_primary ) {
				$css .= '.' . $layout . $container_class . ' #main-sidebar-container #sidebar { width: ' . $width_primary . '%' . '; }' . "\n";
				
				if ( $layout == 'three-col-left' ) {
					$css .= '.' . $layout . $container_class . ' #main-sidebar-container #sidebar { padding-right: ' . '5' . '%' . '; }' . "\n";
				}
				if ( $layout == 'three-col-right' ) {
					$css .= '.' . $layout . $container_class . ' #main-sidebar-container #sidebar { padding-left: ' . '5' . '%' . '; }' . "\n";
				}
			}
			if ( $width_secondary ) { $css .= '.' . $layout . $container_class . ' #sidebar-alt { width: ' . $width_secondary . '%' . '; }' . "\n"; }
			
			$css .= '}' . "\n";
			
			echo $css;			
			die();
		}
	} // End load_dynamic_css()
	
	/*----------------------------------------
 	  setup_layouts()
 	  ----------------------------------------
 	  
 	  * Setup layouts.
 	----------------------------------------*/
	
	function setup_layouts() {
		$this->gutter = 10;
	
		$this->layouts = array();
		
		// One Column
		$one_col = array( 'content' => '100' );
		
		$this->layouts['one-col'] = $one_col;
		
		// Two Columns Left
		$two_col_left = array( 'content' => '65', 'primary' => '30' );
		
		$this->layouts['two-col-left'] = $two_col_left;
		
		// Two Columns Right
		$two_col_right = array( 'primary' => '30', 'content' => '65' );
		
		$this->layouts['two-col-right'] = $two_col_right;
		
		// Three Columns Left
		$three_col_left = array( 'content' => '55', 'primary' => '30', 'secondary' => '15' );
		
		$this->layouts['three-col-left'] = $three_col_left;
		
		// Three Columns Middle
		$three_col_middle = array( 'secondary' => '15', 'content' => '55', 'primary' => '30' );
		
		$this->layouts['three-col-middle'] = $three_col_middle;
		
		// Three Columns Right
		$three_col_right = array( 'secondary' => '15', 'primary' => '30', 'content' => '55' );
		
		$this->layouts['three-col-right'] = $three_col_right;
		
		// Merge the stored layout information with our current defaults.
		$stored_values = get_option( $this->plugin_prefix . 'stored_layouts' );
		
		if ( is_array( $stored_values ) && is_array( $stored_values['layouts'] ) && count( $stored_values['layouts'] ) ) {
			foreach ( $stored_values['layouts'] as $k => $v ) {
				if ( is_array( $v ) ) {
					$this->layouts[$k] = $v;
				}
			}
		}
	} // End setup_layouts()
	
	/*----------------------------------------
 	  setup_layout_information()
 	  ----------------------------------------
 	  
 	  * Setup layout meta information
 	  * (name, description, etc).
 	----------------------------------------*/
	
	function setup_layout_information() {
		$this->layouts_info = array();
		
		// One Column
		$one_col = array( 'name' => __( 'One Column', 'woothemes' ), 'description' => __( 'One column with no sidebars.', 'woothemes' ) );
		
		$this->layouts_info['one-col'] = $one_col;
		
		// Two Columns Left
		$two_col_left = array( 'name' => __( 'Two Columns (Content on the Left)', 'woothemes' ), 'description' => __( 'Two columns with the main content column on the left.', 'woothemes' ) );
		
		$this->layouts_info['two-col-left'] = $two_col_left;
		
		// Two Columns Right
		$two_col_right = array( 'name' => __( 'Two Columns (Content on the Right)', 'woothemes' ), 'description' => __( 'Two columns with the main content column on the right.', 'woothemes' ) );
		
		$this->layouts_info['two-col-right'] = $two_col_right;
		
		// Three Columns Left
		$three_col_left = array( 'name' => __( 'Three Columns (Content on the Left)', 'woothemes' ), 'description' => __( 'Three columns with the main content column on the left.', 'woothemes' ) );
		
		$this->layouts_info['three-col-left'] = $three_col_left;
		
		// Three Columns Middle
		$three_col_middle = array( 'name' => __( 'Three Columns (Content in the Middle)', 'woothemes' ), 'description' => __( 'Three columns with the main content column in the middle.', 'woothemes' ) );
		
		$this->layouts_info['three-col-middle'] = $three_col_middle;
		
		// Three Columns Right
		$three_col_right = array( 'name' => __( 'Three Columns (Content on the Right)', 'woothemes' ), 'description' => __( 'Three columns with the main content column on the right.', 'woothemes' ) );
		
		$this->layouts_info['three-col-right'] = $three_col_right;
	} // End setup_layout_information()
	
	/*----------------------------------------
 	  add_exporter_data()
 	  ----------------------------------------
 	  
 	  * Add our saved data to the WooFramework
 	  * data exporter.
 	----------------------------------------*/
	
	function add_exporter_data ( $data ) {
		$data .= " OR option_name = '" . $this->plugin_prefix . "stored_layouts" . "'";
		
		return $data;
	} // End add_exporter_data()

} // End Class
?>