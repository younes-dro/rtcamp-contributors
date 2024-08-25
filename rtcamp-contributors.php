<?php
/**
 * Plugin Name:     Challenge-2: WordPress-Contributors Plugin
 * Plugin URI:      https://github.com/younes-dro/
 * Description:     A plugin to display more than one author name on a post.
 * Author:          Younes DRO
 * Author URI:      https://github.com/younes-dro/
 * Text Domain:     rtcamp-contributors
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         rtcamp-contributors
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'RTCAMP_CONTRIBUTORS_VERSION', '1.0.0' );

/**
 * Registers the built-in autoloader for the plugin.
 *
 * This function sets up the autoloader using spl_autoload_register,
 * which loads classes as needed based on their naming conventions.
 *
 * @since 1.0.0
 * @codeCoverageIgnore
 */
function rtcamp_register_autoloader() {
	spl_autoload_register( 'rtcamp_autoloader' );
}

/**
 * Autoloads class files based on class name.
 *
 * This function translates a class name into a file path and loads
 * the corresponding class file from the /includes/ directory.
 *
 * @since 1.0.0
 *
 * @param string $class_name The name of the class to load.
 */
function rtcamp_autoloader( $class_name ) {
	$class = strtolower( str_replace( '_', '-', $class_name ) );
	$file  = plugin_dir_path( __FILE__ ) . 'includes/class-' . $class . '.php';
	if ( file_exists( $file ) ) {
		require_once $file;
	}
}

/**
 * Returns the main instance of RTCamp_Contributors.
 *
 * This function initializes the plugin by first registering the autoloader,
 * and then calling the start method of the RTCamp_Contributors class.
 *
 * @since 1.0.0
 *
 * @return RTCamp_Contributors The main instance of the plugin class.
 */
function rtcamp_contributors_starter() {
	rtcamp_register_autoloader();
	return RTCamp_Contributors::start_instance( new RTCamp_Contributors_Dependencies() );
}

// Initialize the plugin.
rtcamp_contributors_starter();
