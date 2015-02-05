<?php
/**
 * Does the replacements
 *
 * @package   Caldera_Affiliates
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 Josh Pollock
 */

/**
 * Class Caldera_Affiliates_Render
 */
class Caldera_Affiliates_Render {
	/**
	 * Cache group for front-end cache
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	public static $cache_group = 'caldera_affiliates';

	/**
	 * Transient key that stores replacements array
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	protected static $replacements_cache_key = 'caldera_affiliates_render_replacements';

	/**
	 * Does replacements and renders content
	 *
	 * @since 0.0.1
	 *
	 * @uses "the_content" filter
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public static function render( $content ) {
		global $post;
		$cache_key = self::post_cache_key( $post->ID );
		$cached = wp_cache_get( $cache_key, self::$cache_group );

		if ( is_string( $cached ) ) {
			$content = $cached;
		} else {
			$replacements = self::get_replacements();
			foreach ( $replacements as $replacement ) {
				if ( strpos( $content, $replacement['name'] ) ) {
					$link = sprintf(
						'<a href="%1s" title="%2s" target="_blank">%4s</a>',
						esc_url( $replacement['url'] ),
						esc_attr( $replacement['title_text'] ),
						$replacement['name']
					);

					$content = str_replace( $replacement['name'], $link, $content );
				}

			}

			wp_cache_set( $cache_key, $content, self::$cache_group, HOUR_IN_SECONDS );

		}

		return $content;

	}

	/**
	 * Get the replacements
	 *
	 * @since 0.0.1
	 *
	 * @access protected
	 *
	 * @return array|mixed
	 */
	protected static function get_replacements() {


		if ( ! $replacements = get_transient( self::$replacements_cache_key ) ) {
			$groups  = get_option( '_caldera_affliates_registry' );
			$the_groups = $replacements = array();
			foreach ( $groups as $group ) {
				$the_groups[] = get_option( $group['id'] );
			}

			if ( ! empty ( $the_groups ) ) {
				foreach ( $the_groups as $node => $group ) {
					if ( isset( $group['links'] ) && is_array( $group['links'] ) ) {
						foreach ( $group['links'] as $node  ) {
							unset( $node[ '_id' ] );
							$replacements[] = $node;
						}
					}

				}

			}

		}

		if ( is_array( $replacements ) && ! empty( $replacements ) ) {
			set_transient( self::$replacements_cache_key, $replacements, WEEK_IN_SECONDS  );
			return $replacements;

		}

	}

	/**
	 * Clears cache on post save
	 *
	 * @since 0.0.1
	 *
	 * @uses "save_post" action
	 *
	 * @param int $post_id
	 */
	public static function clear_cache( $post_id ) {
		$cache_key = self::post_cache_key( $post_id );
		wp_cache_delete( $cache_key, self::$cache_group );
		delete_transient( self::$replacements_cache_key );

	}

	/**
	 * Create a cache key by post ID
	 *
	 * @since 0.0.1
	 *
	 * @access protected
	 *
	 * @param int $post_id Post ID
	 *
	 * @return string
	 */
	protected static function post_cache_key( $post_id ) {
		$cache_key = 'caldera_affiliates_rendered_' . $post_id;

		return $cache_key;

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
