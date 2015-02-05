<?php
/**
 * Handles the actual sanitization of fields
 *
 * @package   Caldera_Affiliates
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 Josh Pollock <Josh@CalderaWP.com>
 */

class Caldera_Affiliates_Sanitize_Callbacks {

	/**
	 * Holds The Class Instance
	 *
	 * object
	 */
	private static $instance;

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

}
