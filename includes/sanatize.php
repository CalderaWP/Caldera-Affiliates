<?php
/**
 * Handles sanitizion of settings
 *
 * @package Caldera_Affiliates
 * @author  Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 Josh Pollock
 */

/**
 * Plugin class.
 * @package Caldera_Affiliates
 * @author  Josh Pollock <Josh@CalderaWP.com>
 */
class Caldera_Affiliates_Settings_Sanitize {

	/**
	 * Prepares for application of the sanization and/ or validation filter based on setting type
	 *
	 * @since 0.0.1
	 *
	 * @access protected
	 *
	 * @param string $setting The name of the setting being saved.
	 * @param mixed $value The value being saved
	 * @param array $config Data being saved
	 *
	 * @return array The configuration with a specific setting sanatized.
	 */
	public static function apply_sanitization_and_validation( $setting, $value, $config ) {

		$stored = Caldera_Affiliates_Options::get_all();
		// check value exists and if its changed
		if ( isset( $stored[ $setting ] ) && $value == $stored[ $setting ] ) {
			return $config;
		}

		if ( is_array( $value) ) {
			//do for full array
			$filtered = self::apply_sanitization_filter( $setting, $value, $config );
			$config[ $setting ]  = $filtered;

			foreach( $value as $sub_setting => $val ) {
				if ( is_array( $val ) ) {

					//do for parts of field groups
					foreach ( $val as $k => $v  ) {
						if( isset( $stored[ $setting ][ $sub_setting ][ $k ] ) && $stored[ $setting ][ $sub_setting ][ $k ] != $v ){
							$v = self::apply_sanitization_filter( $k, $v, $config, $setting );
							$config[ $setting][ $sub_setting ][ $k ] = $v;
						}

					}

				}

			}



		}else {
			$config [ $setting ] =  self::apply_sanitization_filter( $setting, $value, $config );
		}


		return $config;

	}

	/**
	 * Actually applies the sanization and/ or validation filter
	 *
	 * @since 0.0.1
	 *
	 * @access protected
	 *
	 * @param string $setting The name of the setting being saved.
	 * @param mixed $value The value being saved
	 * @param array $config Data being saved
	 * @param string|bool $sub_setting Optional. The "sub_setting" when saving fields that are arrays.
	 */
	protected static function apply_sanitization_filter( $setting, $value, $config, $sub_setting = false ) {
		$filter_name = "{{core_class}}_{$setting}";
		if (  $sub_setting ) {
			$filter_name = "{{core_class}}_{$sub_setting}_{$setting}";
		}

		/**
		 * Hook here to sanatize/validate settings
		 *
		 * @since 0.0.1
		 *
		 * @param string $setting The name of the setting being saved.
		 * @param mixed $value The value being saved
		 * @param array $config Data being saved
		 */
		return apply_filters( $filter_name, $value, $config );

	}


}
