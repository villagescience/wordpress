<?php
/**
 * Hook Manager
 *
 * This is the hook manager class, containing all processing and setup functionality
 * for inputting custom content at the various WooFramework hook calls.
 *
 * @package WooFramework
 * @subpackage Module
 * 
 *-----------------------------------------------------------------------------------

 CLASS INFORMATION

 Date Created: 2011-04-19.
 Author: Matty.
 Since: 4.0.0


 TABLE OF CONTENTS

 - var $plugin_prefix
 - var $plugin_path
 - var $plugin_url
 - var $version
 
 - var $admin_page
 - var $hooks
 - var $hook_titles
 
 - var $stored_data
 
 - function Woo_Hook_Manager () (Constructor)
 - function init ()
 - function register_admin_screen ()
 - function admin_screen ()
 - function admin_screen_help ()
 - function enqueue_scripts ()
 - function enqueue_styles ()
 - function create_hooks ()
 - function execute_hook ()
 - function setup_hook_data ()
 - function setup_hook_titles ()
 - function add_exporter_data ()

-----------------------------------------------------------------------------------*/

class Woo_Hook_Manager {

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

	var $admin_page;
	var $hooks;
	var $hook_titles;
	
	var $stored_data;

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

	function Woo_Hook_Manager ( $plugin_prefix, $plugin_path, $plugin_url, $version ) {
	
		$this->plugin_prefix = $plugin_prefix;
		$this->plugin_path = $plugin_path;
		$this->plugin_url = $plugin_url;
		$this->version = $version;
	
		$this->init();
	
	} // End Constructor
	
	/*----------------------------------------
 	  init()
 	  ----------------------------------------
 	  
 	  * This guy runs the show.
 	  * Rocket boosters... engage!
 	----------------------------------------*/
	
	function init () {
		
		// Create the necessary filters.
		add_action( 'after_setup_theme', array( &$this, 'create_hooks' ), 10 );
		
		if ( is_admin() ) {
	
			// Setup hook areas.
			$this->setup_hook_data();
	
			// Register the admin screen.
			add_action( 'admin_menu', array( &$this, 'register_admin_screen' ), 20 );
			
			// Execute certain code only on the specific admin screen.
			if ( is_admin( $this->admin_page ) ) {
			
				// Add contextual help.
				add_action( 'contextual_help', array( &$this, 'admin_screen_help' ), 10, 3 );
			
				$this->setup_hook_titles();
			
			}
			
			// Make sure our data is added to the WooFramework settings exporter.
			add_filter( 'wooframework_export_query_inner', array( &$this, 'add_exporter_data' ) );
		
		} // End IF Statement
	
	} // End init()

	function register_admin_screen () {
		
		if ( function_exists( 'add_submenu_page' ) ) {	
			
			$this->admin_page = add_submenu_page('woothemes', __( 'Hook Manager', 'woothemes' ), __( 'Hook Manager', 'woothemes' ), 'manage_options', 'woo-hook-manager', array( &$this, 'admin_screen' ) );
			
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
		  '<p>' . __('Welcome to the Woo Hook Manager!', 'woothemes') . '</p>' .
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
		
		if ( isset( $_POST['woohooks_reset'] ) && check_admin_referer( 'woohooks-options-update' ) ) {
			update_option( $this->plugin_prefix . 'stored_hooks' );
			
			$is_processed = true;
			
			wp_redirect( admin_url( 'admin.php?page=woo-hook-manager&reset=true' ) );
		}
		
		// Save logic.
		
		if ( isset( $_POST['woohooks_update'] ) && check_admin_referer( 'woohooks-options-update' ) ) {
			
			$fields_to_skip = array( 'woohooks_update', '_wp_http_referer', '_wpnonce' );
			
			$posted_data = $_POST;
			
			foreach ( $posted_data as $k => $v ) {
				
				// Remove the fields we want to skip.
				if ( in_array( $k, $fields_to_skip ) ) {
					unset( $posted_data[$k] );
				} else {
					
					// Make sure the "shortcodes" and "php" keys are available, even if not posted.
					if ( ! array_key_exists( 'shortcodes', $v ) ) { $posted_data[$k]['shortcodes'] = 0; }
					if ( ! array_key_exists( 'php', $v ) ) { $posted_data[$k]['php'] = 0; }
					
				}
				
			}
			
			if ( is_array( $posted_data ) ) {
				$is_updated = update_option( $this->plugin_prefix . 'stored_hooks', $posted_data );
				
				// Redirect to make sure the latest changes are reflected.
				wp_redirect( admin_url( 'admin.php?page=woo-hook-manager&updated=true' ) );
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

		wp_register_script( 'woo-hook-functions', $this->plugin_url . 'assets/js/functions.js', array( 'jquery' ), '1.0.0', true );
		
		wp_enqueue_script( 'woo-hook-functions' ); // The dependant JavaScript files will be enqueued automatically.
	
	} // End enqueue_scripts()
	
	/*----------------------------------------
 	  enqueue_styles()
 	  ----------------------------------------
 	  
 	  * Enqueue the necessary CSS files.
 	----------------------------------------*/
	
	function enqueue_styles () {
	
		wp_register_style( 'woo-admin-interface', get_template_directory_uri() . '/functions/admin-style.css' );
		wp_register_style( 'woo-hooks-interface', $this->plugin_url . '/assets/css/admin.css' );
		
		wp_enqueue_style( 'woo-admin-interface' );
		wp_enqueue_style( 'woo-hooks-interface' );
	
	} // End enqueue_styles()
	
	/*----------------------------------------
 	  create_filters()
 	  ----------------------------------------
 	  
 	  * Create hooks using our saved content.
 	----------------------------------------*/
	
	function create_hooks () {
	
		if ( ! is_admin() ) {
		
			$stored_hooks = get_option( $this->plugin_prefix . 'stored_hooks' );
			
			// Create the hooks, using an internal function to create the hook data.
			if ( is_array( $stored_hooks ) ) {
				
				$this->stored_data = $stored_hooks; // Store this data locally to avoid a second query in $this->execute_hook().
				
				foreach ( $stored_hooks as $k => $v ) {
				
					add_action($k, array( &$this, 'execute_hook' ) );	
				}
			}
		
		} // End IF Statement
	
	} // End create_hooks()
	
	/*----------------------------------------
 	  execute_hook()
 	  ----------------------------------------
 	  
 	  * Executes the necessary hooks.
 	----------------------------------------*/
	
	function execute_hook () {
	
		$hook = current_filter();
		$content = $this->stored_data[$hook]['content'];
		
		if( ! $hook || ! $content ) return;
		
		// Moved stripslashes here so that the do_shortcode function will accept parameters
		$content = stripslashes( $content );
		
		// If we are being instructed to execute shortcodes, execute them.
		if ( array_key_exists( 'shortcodes', $this->stored_data[$hook] ) && $this->stored_data[$hook]['shortcodes'] ) {
			$content = do_shortcode( $content );
		}
		
		echo $content;
	
	} // End execute_hook()
	
	/*----------------------------------------
 	  setup_hook_data()
 	  ----------------------------------------
 	  
 	  * Sets up the default and saved data
 	  * for the various hook areas.
 	----------------------------------------*/
	
	function setup_hook_data () {
	
		// Stored data.
		$stored_values = get_option( $this->plugin_prefix . 'stored_hooks' );
		
		$this->hooks = array();
		
		// Header Hooks
		$this->hooks['header'] = array(
								'woo_top' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed below the opening <code>&lt;body&gt;</code> tag.', 'woothemes' )
									),
								'woo_header_before' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed before the opening <code>#header</code> DIV tag.', 'woothemes' )
									)
,
								'woo_header_inside' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed at the top, inside the <code>#header</code> DIV tag.', 'woothemes' )
									),
								'woo_header_after' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed after the closing <code>#header</code> DIV tag.', 'woothemes' )
									)
							);
							
		// Navigation Hooks
		$this->hooks['nav'] = array(
								'woo_nav_before' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed before the opening <code>#navigation</code> DIV tag.', 'woothemes' )
									),
								'woo_nav_inside' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed at the top, inside the <code>#navigation</code> DIV tag.', 'woothemes' )
									), 
								'woo_nav_after' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed after the closing <code>#navigation</code> DIV tag.', 'woothemes' )
									)
							);
							
		// Main Content Area Hooks
		$this->hooks['main'] = array(
								'woo_content_before' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed before the opening <code>#content</code> DIV tag.', 'woothemes' )
									)
,
								'woo_main_before' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed before the opening <code>#main</code> DIV tag.', 'woothemes' )
									),
								'woo_loop_before' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed before the WordPress Loop.', 'woothemes' )
									),
								'loop_start' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed at the start of the WordPress Loop.', 'woothemes' )
									),
								'loop_end' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed at the end of the WordPress Loop.', 'woothemes' )
									),
								'woo_loop_after' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed after the WordPress Loop.', 'woothemes' )
									),
								'woo_main_after' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed after the closing <code>#main</code> DIV tag.', 'woothemes' )
									),
								'woo_content_after' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed after the closing <code>#content</code> DIV tag.', 'woothemes' )
									)
							);
		
		// Post Hooks
		$this->hooks['post'] = array(
								'woo_post_before' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed before each post.', 'woothemes' )
									)
,
								'woo_post_inside_before' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed at the top, inside each post\'s DIV tag.', 'woothemes' )
									),
								'woo_post_inside_after' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed at the bottom, inside each post\'s DIV tag.', 'woothemes' )
									),
								'woo_post_after' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed after each post.', 'woothemes' )
									)
							);
				
		// Footer Hooks
		$this->hooks['footer'] = array(
								'woo_footer_top' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed at the top of the <code>footer.php</code> file.', 'woothemes' )
									),
								'woo_footer_before' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed before the opening <code>#footer</code> DIV tag.', 'woothemes' )
									),
								'woo_footer_inside' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed at the top, inside the <code>#footer</code> DIV tag.', 'woothemes' )
									),
								'woo_footer_left_before' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed before the opening <code>#footer .col-left</code> DIV tag.', 'woothemes' )
									),
								'woo_footer_left_after' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed after the ending <code>#footer .col-left</code> DIV tag.', 'woothemes' )
									),
								'woo_footer_right_before' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed before the opening <code>#footer .col-right</code> DIV tag.', 'woothemes' )
									),
								'woo_footer_right_after' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed after the ending <code>#footer .col-right</code> DIV tag.', 'woothemes' )
									),
								'woo_footer_after' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed after the closing <code>#footer</code> DIV tag.', 'woothemes' )
									)
							);
		
		// WordPress Native Hooks
		$this->hooks['wordpress'] = array(
								'wp_head' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed before the closing <code>&lt;/head&gt;</code> tag.', 'woothemes' )
									),
								'wp_footer' => array(
										'content' => '', 
										'shortcodes' => 0, 
										'php' => '', 
										'description' => __( 'Executed before the closing <code>&lt;/body&gt;</code> tag.', 'woothemes' )
									)
							);
		
		// Allow child themes/plugins to add their own hook sections.
		$this->hooks = apply_filters( 'woo_hook_manager_hooks', $this->hooks );
							
		// Assigned stored data to the appropriate hook area.
		foreach ( $this->hooks as $id => $arr ) {
			foreach ( $this->hooks[$id] as $k => $v ) {
				if ( is_array( $stored_values ) && array_key_exists( $k, $stored_values ) ) {
					if ( is_array( $stored_values[$k] ) ) {
						foreach ( $stored_values[$k] as $i => $j ) {
							$this->hooks[$id][$k][$i] = $j;
						}
					}
				}
			}
		}
	
	} // End setup_hook_data()
	
	/*----------------------------------------
 	  setup_hook_titles()
 	  ----------------------------------------
 	  
 	  * Setup custom titles for use on the
 	  * navigation menu.
 	----------------------------------------*/
	
	function setup_hook_titles () {
		
		$this->hook_titles = array();
		
		$this->hook_titles['nav'] = __( 'Navigation', 'woothemes' );
		
	} // End setup_hook_titles()
	
	/*----------------------------------------
 	  add_exporter_data()
 	  ----------------------------------------
 	  
 	  * Add our saved data to the WooFramework
 	  * data exporter.
 	----------------------------------------*/
	
	function add_exporter_data ( $data ) {
		
		$data .= " OR option_name = '" . $this->plugin_prefix . "stored_hooks" . "'";
		
		return $data;
		
	} // End add_exporter_data()

} // End Class
?>