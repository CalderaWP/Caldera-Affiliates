<?php
/**
 * Caldera Affiliates.
 *
 * @package   Caldera_Affiliates
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Josh Pollock <Josh@CalderaWP.com>
 */

/**
 * Plugin class.
 * @package Caldera_Affiliates
 * @author  Josh Pollock <Josh@CalderaWP.com>
 */
class Caldera_Affiliates {

	/**
	 * @var      string
	 */
	protected $plugin_slug = 'caldera-affiliates';
	/**
	 * @var      object
	 */
	protected static $instance = null;
	/**
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;
	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_stylescripts' ) );

	}


	/**
	 * Return an instance of this class.
	 *
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain( $this->plugin_slug, FALSE, basename( CALDERA_AFFILIATES_PATH ) . '/languages');

	}
	
	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 *
	 * @return    null
	 */
	public function enqueue_admin_stylescripts() {

		$screen = get_current_screen();

		
		if( false !== strpos( $screen->base, 'caldera_affiliates' ) ){

			wp_enqueue_style( 'caldera_affiliates-core-style', CALDERA_AFFILIATES_URL . '/assets/css/styles.css' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'caldera_affiliates-baldrick-modals', CALDERA_AFFILIATES_URL . '/assets/css/modals.css' );
			wp_enqueue_script( 'caldera_affiliates-wp-baldrick', CALDERA_AFFILIATES_URL . '/assets/js/wp-baldrick-full.js', array( 'jquery' ) , false, true );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'wp-color-picker' );
						
			if( !empty( $_GET['edit'] ) ){
				wp_enqueue_style( 'caldera_affiliates-codemirror-style', CALDERA_AFFILIATES_URL . '/assets/css/codemirror.css' );
				wp_enqueue_script( 'caldera_affiliates-codemirror-script', CALDERA_AFFILIATES_URL . '/assets/js/codemirror.js', array( 'jquery' ) , false );
			}

			wp_enqueue_script( 'caldera_affiliates-core-script', CALDERA_AFFILIATES_URL . '/assets/js/scripts.js', array( 'caldera_affiliates-wp-baldrick' ) , false );

		
		}


	}


}















