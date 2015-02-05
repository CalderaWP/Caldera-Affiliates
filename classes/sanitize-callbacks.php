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
	 * Sanitize name sub field.
	 *
	 * @since 0.1.0
	 *
	 * @uses "caldera_affiliates_name" filter
	 */
	public static function name( $value ) {
		$value = sanitize_text_field( $value );

		return $value;

	}

	/**
	 * Sanitize url sub field.
	 *
	 * @since 0.1.0
	 *
	 * @uses "caldera_affiliates_links_url" filter
	 */
	public static function links_url( $value ) {
		$value = esc_url_raw( $value, array( 'http', 'https' ) );

		return $value;

	}

	/**
	 * Sanitize title_text sub field.
	 *
	 * @since 0.1.0
	 *
	 * @uses "caldera_affiliates_links_title_text" filter
	 */
	public static function title_text( $value ) {
		$value = sanitize_text_field( $value );

		return $value;

	}



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



