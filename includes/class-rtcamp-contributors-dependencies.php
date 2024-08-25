<?php
/**
 * Manages the dependencies that the Plugin needs to operate.
 *
 * @package rtcamp-contributors
 * @since 1.0.0
 * @version 1.0.0
 * @author Younes DRO <younesdro@gmail.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Check the compatibility of the environment.
 *
 * @class RTCamp_Contributors_Dependencies
 * @author Younes DRO <younesdro@gmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class RTCamp_Contributors_Dependencies {

	/** Minimum PHP version required by this plugin */
	const MINIMUM_PHP_VERSION = '7.4';

	/** Minimum WordPress version required by this plugin */
	const MINIMUM_WP_VERSION = '6.6';

	/**
	 * Constructor for the RTCamp_Contributors_Dependencies class.
	 *
	 * Initializes the dependencies class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Initialize the dependencies class.
	}


	/**
	 * Checks the PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Return true if the PHP version is compatible.Otherwise, will return false.
	 */
	public static function check_php_version() {

		return version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '>=' );
	}

	/**
	 * Gets the message for display when PHP version is incompatible with this plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return string Return an informative message.
	 */
	public static function get_php_notice() {
		return sprintf(
			/* translators: 1: Minimum required PHP version, 2: Current PHP version */
			esc_html__( 'The minimum PHP version required for this plugin is %1$s. You are running %2$s.', 'rtcamp-contributors' ),
			self::MINIMUM_PHP_VERSION,
			PHP_VERSION
		);
	}

	/**
	 * Checks the WordPress version.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Return true if the WordPress version is compatible.Otherwise, will return false.
	 */
	public static function check_wp_version() {

		if ( ! self::MINIMUM_WP_VERSION ) {
			return true;
		}

		return version_compare( get_bloginfo( 'version' ), self::MINIMUM_WP_VERSION, '>=' );
	}

	/**
	 * Gets the message for display when WordPress version is incompatible with this plugin.
	 *
	 * @return string Return an informative message.
	 */
	public static function get_wp_notice() {
		return sprintf(
			/* translators: 1: Plugin name, 2: Minimum required WordPress version, 3: Opening anchor tag for update link, 4: Closing anchor tag */
			esc_html__( '%1$s is not active, as it requires WordPress version %2$s or higher. Please %3$supdate WordPress &raquo;%4$s', 'rtcamp-contributors' ),
			'<strong>' . RTCamp_Contributors()->get_plugin_name() . '</strong>',
			self::MINIMUM_WP_VERSION,
			'<a href="' . esc_url( admin_url( 'update-core.php' ) ) . '">',
			'</a>'
		);
	}


	/**
	 * Determines if all the requirements are valid .
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_compatible() {

		return ( self::check_php_version() && self::check_wp_version() );
	}
}
