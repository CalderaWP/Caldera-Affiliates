<?php
/**
 * @package   Caldera_Affiliates
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 Josh Pollock <Josh@CalderaWP.com>
 *
 * @wordpress-plugin
 * Plugin Name: Caldera Affiliates
 * Plugin URI:  http://CalderaWP.com
 * Description: Easily swap out the name of an affiliate partner with your affiliate link.
 * Version:     0.0.1
 * Author:      Josh Pollock <Josh@CalderaWP.com>
 * Author URI:  http://JoshPress.net
 * Text Domain: caldera-affiliates
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('CALDERA_AFFILIATES_PATH',  plugin_dir_path( __FILE__ ) );
define('CALDERA_AFFILIATES_URL',  plugin_dir_url( __FILE__ ) );
define('CALDERA_AFFILIATES_VER',  '0.0.1' );



// load internals
require_once( CALDERA_AFFILIATES_PATH . '/classes/caldera-affiliates.php' );
require_once( CALDERA_AFFILIATES_PATH . '/classes/options.php' );
require_once( CALDERA_AFFILIATES_PATH . 'includes/settings.php' );

// Load instance
add_action( 'plugins_loaded', array( 'Caldera_Affiliates', 'get_instance' ) );
