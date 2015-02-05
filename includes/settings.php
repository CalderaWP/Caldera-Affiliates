<?php
/**
 * Caldera Affiliates Setting.
 *
 * @package   Caldera_Affiliates
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 Josh Pollock <Josh@CalderaWP.com>
 */

/**
 * Plugin class.
 * @package Caldera_Affiliates
 * @author  Josh Pollock <Josh@CalderaWP.com>
 */
class Caldera_Affiliates_Settings extends Caldera_Affiliates{


	/**
	 * Constructor for class
	 *
	 * @since 0.0.1
	 */
	public function __construct(){

		// add admin page
		add_action( 'admin_menu', array( $this, 'add_settings_pages' ), 25 );
		// save config
		add_action( 'wp_ajax_caldera_affiliates_save_config', array( $this, 'save_config') );
		// creat new
		add_action( 'wp_ajax_caldera_affiliates_create_caldera_affiliates', array( $this, 'create_new_caldera_affiliates') );
		// delete
		add_action( 'wp_ajax_caldera_affiliates_delete_caldera_affiliates', array( $this, 'delete_caldera_affiliates') );

	}

	/**
	 * Saves a config
	 *
	 * @since 0.0.1
	 *
	 * @uses "wp_ajax_caldera_affiliates_save_config" hook
	 */
	public function save_config(){

		if( empty( $_POST['caldera-affiliates-setup'] ) || !wp_verify_nonce( $_POST['caldera-affiliates-setup'], 'caldera-affiliates' ) ){
			if( empty( $_POST['config'] ) ){
				return;
			}
		}

		if( !empty( $_POST['caldera-affiliates-setup'] ) && empty( $_POST['config'] ) ){
			$config = stripslashes_deep( $_POST );
			$config = $this->add_sanitization_and_validation( $config );
			$caldera_affiliatess = get_option( '_caldera_affliates_registry' );

			if( isset( $config['id'] ) && !empty( $caldera_affiliatess[ $config['id'] ] ) ){
				$updated_registery = array(
					'id'	=>	$config['id'],
					'name'	=>	$config['name'],
					'slug'	=>	$config['slug']
				);
				// add search form to registery
				if( !empty( $config['search_form'] ) ){
					$updated_registery['search_form'] = $config['search_form'];
				}
				
				$caldera_affiliatess[$config['id']] = $updated_registery;
				update_option( '_caldera_affliates_registry', $caldera_affiliatess );
			}
			update_option( $config['id'], $config );

			wp_redirect( '?page=caldera_affiliates&updated=true' );
			exit;
		}

		if( !empty( $_POST['config'] ) ){
			$config = json_decode( stripslashes_deep( $_POST['config'] ), true );
			$config = $this->add_sanitization_and_validation( $config );
			if(	wp_verify_nonce( $config['caldera-affiliates-setup'], 'caldera-affiliates' ) ){
				$caldera_affiliatess = get_option( '_caldera_affliates_registry' );

			if( isset( $config['id'] ) && !empty( $caldera_affiliatess[ $config['id'] ] ) ){
				$updated_registery = array(
					'id'	=>	$config['id'],
					'name'	=>	$config['name'],
					'slug'	=>	$config['slug']
				);
				// add search form to registery
				if( !empty( $config['search_form'] ) ){
					$updated_registery['search_form'] = $config['search_form'];
				}
				
				$caldera_affiliatess[$config['id']] = $updated_registery;
				update_option( '_caldera_affliates_registry', $caldera_affiliatess );
			}
			update_option( $config['id'], $config );

				wp_send_json_success( $config );
			}
		}

		// nope
		wp_send_json_error( $config );

	}

	/**
	 * Adds the filter for sanization and/ or validation of each setting when saving.
	 *
	 * @since 0.0.1
	 *
	 * @param array $config Data being saved
	 *
	 * @return array
	 */
	protected function add_sanitization_and_validation( $config ) {
		foreach( $config as $setting => $value ) {
			if ( ! in_array( $setting, $this->internal_config_fields() ) ) {
				include_once( dirname( __FILE__ ) . '/sanatize.php' );
				$filtered = Caldera_Affiliates_Settings_Sanitize::apply_sanitization_and_validation( $setting, $value, $config );
				$config = $filtered;
			}

		}

		return $config;

	}

	/**
	 * Array of "internal" fields not to mess with
	 *
	 * @since 0.0.1
	 *
	 * @return array
	 */
	protected function internal_config_fields() {
		return array( '_wp_http_referer', 'id', '_current_tab' );
	}


	/**
	 * Deletes a block
	 */
	public function delete_caldera_affiliates(){

		$search_blocks = get_option( '_caldera_affliates_registry' );
		if( isset( $search_blocks[ $_POST['block'] ] ) ){
			delete_option( $search_blocks[$_POST['block']]['id'] );

			unset( $search_blocks[ $_POST['block'] ] );
			update_option( '_caldera_affliates_registry', $search_blocks );

			wp_send_json_success( $_POST );
		}
		
		wp_send_json_error( $_POST );

	}
	/**
	 * create new caldera_affiliates
	 */
	public function create_new_caldera_affiliates(){
		
		$caldera_affiliatess = get_option('_caldera_affliates_registry');
		if( empty( $caldera_affiliatess ) ){
			$caldera_affiliatess = array();
		}

		$caldera_affiliates_id = uniqid('CALDERA_AFFILIATES').rand(100,999);
		if( !isset( $caldera_affiliatess[ $caldera_affiliates_id ] ) ){
			$new_caldera_affiliates = array(
				'id'		=>	$caldera_affiliates_id,
				'name'		=>	$_POST['name'],
				'slug'		=>	$_POST['slug'],
				'_current_tab' => '#caldera-affiliates-panel-general'
			);
			update_option( $caldera_affiliates_id, $new_caldera_affiliates );
			$caldera_affiliatess[ $caldera_affiliates_id ] = $new_caldera_affiliates;
		}

		update_option( '_caldera_affliates_registry', $caldera_affiliatess );

		// end
		wp_send_json_success( $new_caldera_affiliates );
	}

	/**
	 * Add options page
	 *
	 * @since 0.0.1
	 *
	 * @uses "admin_menu" hook
	 */
	public function add_settings_pages(){
		// This page will be under "Settings"
		
	
			$this->plugin_screen_hook_suffix['caldera_affiliates'] =  add_menu_page( __( 'Caldera Affiliates', $this->plugin_slug ), __( 'Affiliates', $this->plugin_slug ), 'manage_options', 'caldera_affiliates', array( $this, 'create_admin_page' ), 'dashicons-smiley' );
			add_action( 'admin_print_styles-' . $this->plugin_screen_hook_suffix['caldera_affiliates'], array( $this, 'enqueue_admin_stylescripts' ) );


	}


	/**
	 * Options page callback
	 *
	 * @since 0.0.1
	 */
	public function create_admin_page(){
		// Set class property        
		$screen = get_current_screen();
		$base = array_search($screen->id, $this->plugin_screen_hook_suffix);
			
		// include main template
		if( empty( $_GET['edit'] ) ){
			include CALDERA_AFFILIATES_PATH .'includes/admin.php';
		}else{
			include CALDERA_AFFILIATES_PATH .'includes/edit.php';
		}


		// php based script include
		if( file_exists( CALDERA_AFFILIATES_PATH .'assets/js/inline-scripts.php' ) ){
			echo "<script type=\"text/javascript\">\r\n";
				include CALDERA_AFFILIATES_PATH .'assets/js/inline-scripts.php';
			echo "</script>\r\n";
		}

	}


}

if( is_admin() ) {
	$settings_caldera_affliates = new Caldera_Affiliates_Settings();
}
