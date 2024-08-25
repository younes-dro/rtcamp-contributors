<?php
/**
 * RTCamp Contributors Main Class File.
 *
 * This file contains the main class for the RTCamp Contributors plugin,
 * handling core functionalities such as initialization, environment checks,
 * and admin notices.
 *
 * @package rtcamp-contributors
 * @since 1.0.0
 * @version 1.0.0
 * @author Younes DRO <younesdro@gmail.com>
 */

/**
 * Main RTCamp_Contributors Class.
 *
 * Handles the core functionality of the RTCamp Contributors plugin.
 *
 * @class RTCamp_Contributors
 * @version 1.0.0
 * @since 1.0.0
 * @package rtcamp-contributors
 * @author Younes DRO <younesdro@gmail.com>
 */
class RTCamp_Contributors {

	/**
	 * The single instance of the class.
	 *
	 * @var RTCamp_Contributors
	 */
	protected static $instance;

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public $plugin_version;

	/**
	 * Plugin name.
	 *
	 * @var string
	 */
	public $plugin_name;

	/**
	 * Instance of the RTCamp_Contributors_Dependencies class.
	 *
	 * Verify the requirements.
	 *
	 * @var RTCamp_Contributors_Dependencies
	 */
	protected static $dependencies;

	/**
	 * Admin notices to display.
	 *
	 * @var array $notices Array of admin notices to be shown in the WordPress admin area.
	 */
	protected $notices = array();

	/**
	 * Constructor for the RTCamp_Contributors class.
	 *
	 * Checks the dependencies and initializes the plugin.
	 *
	 * @param RTCamp_Contributors_Dependencies $dependencies Dependency checker instance.
	 */
	public function __construct( RTCamp_Contributors_Dependencies $dependencies ) {
		self::$dependencies = $dependencies;

		$this->plugin_version = defined( 'RTCAMP_CONTRIBUTORS_VERSION' ) ? RTCAMP_CONTRIBUTORS_VERSION : '1.0.0';
		$this->plugin_name    = 'rtcamp-contributors';

		register_activation_hook( __FILE__, array( $this, 'activation_check' ) );

		add_action( 'admin_init', array( $this, 'check_environment' ) );
		add_action( 'admin_init', array( $this, 'add_plugin_notices' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ), 15 );
		add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
		add_action( 'init', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Gets the main RTCamp_Contributors instance.
	 *
	 * Ensures only one instance of RTCamp_Contributors is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @param RTCamp_Contributors_Dependencies $dependencies Dependency checker instance.
	 * @return RTCamp_Contributors
	 */
	public static function start( RTCamp_Contributors_Dependencies $dependencies ) {
		if ( null === self::$instance ) {
			self::$instance = new self( $dependencies );
		}

		return self::$instance;
	}

	/**
	 * Cloning is forbidden due to the singleton pattern.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'You cannot clone instances of this class.', 'rtcamp-contributors' ), esc_html( $this->plugin_version ) );
	}


	/**
	 * Unserializing instances is forbidden due to the singleton pattern.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'You cannot unserialize instances of this class.', 'rtcamp-contributors' ), esc_html( $this->plugin_version ) );
	}

	/**
	 * Checks the server environment and deactivates the plugin if necessary.
	 *
	 * @since 1.0.0
	 */
	public function activation_check() {
		if ( ! self::$dependencies->check_php_version() ) {
			$this->deactivate_plugin();
			$message = esc_html( $this->plugin_name ) . ' ' . esc_html__( 'could not be activated.', 'rtcamp-contributors' ) . ' ' . esc_html( self::$dependencies->get_php_notice() );
			wp_die( esc_html( $message ) );
		}
	}


	/**
	 * Checks the environment on loading WordPress, just in case the environment changes after activation.
	 *
	 * @since 1.0.0
	 */
	public function check_environment() {
		if ( ! self::$dependencies->check_php_version() && is_plugin_active( plugin_basename( __FILE__ ) ) ) {
			$this->deactivate_plugin();
			$this->add_admin_notice(
				'bad_environment',
				'error',
				esc_html( $this->plugin_name ) . esc_html__( ' has been deactivated. ', 'rtcamp-contributors' ) . self::$dependencies->get_php_notice()
			);
		}
	}

	/**
	 * Deactivates the plugin.
	 *
	 * @since 1.0.0
	 */
	protected function deactivate_plugin() {

		check_admin_referer( 'deactivate-plugin_' . plugin_basename( __FILE__ ) );

		deactivate_plugins( plugin_basename( __FILE__ ) );

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}


	/**
	 * Adds an admin notice to be displayed.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug    Message slug.
	 * @param string $css_class   CSS classes.
	 * @param string $message Notice message.
	 */
	public function add_admin_notice( $slug, $css_class, $message ) {
		$this->notices[ $slug ] = array(
			'class'   => $css_class,
			'message' => $message,
		);
	}

	/**
	 * Adds plugin-related admin notices.
	 *
	 * @return void
	 */
	public function add_plugin_notices() {
		if ( ! self::$dependencies->check_wp_version() ) {
			$this->add_admin_notice( 'update_wordpress', 'error', self::$dependencies->get_wp_notice() );
		}
	}

	/**
	 * Displays any admin notices added with RTCamp_Contributors::add_admin_notice().
	 *
	 * @since 1.0.0
	 */
	public function admin_notices() {
		foreach ( (array) $this->notices as $notice_key => $notice ) {
			printf(
				'<div class="%1$s"><p>%2$s</p></div>',
				esc_attr( $notice['class'] ),
				wp_kses(
					$notice['message'],
					array(
						'a'      => array( 'href' => array() ),
						'strong' => array(),
					)
				)
			);
		}
	}


	/**
	 * Initializes the plugin.
	 *
	 * @since 1.0.0
	 */
	public function init_plugin() {
		if ( ! self::$dependencies->is_compatible() ) {
			return;
		}

		if ( is_admin() ) {
			new RTCamp_Contributors_MetaBox();
		}

		$this->frontend_includes();
	}

	/**
	 * Include frontend template functions and hooks.
	 *
	 * @since 1.0.0
	 */
	public function frontend_includes() {
		new RTCamp_Contributors_Front();
	}

	/** -----------------------------------------------------------------------------------*/
	/** Helper Functions                                                                  */
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Get the plugin name.
	 *
	 * @return string Plugin name.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Get the plugin version.
	 *
	 * @return string Plugin version.
	 */
	public function get_plugin_version() {
		return $this->plugin_version;
	}

	/**
	 * Get the plugin URL.
	 *
	 * @since 1.0.0
	 *
	 * @return string Plugin URL.
	 */
	public function get_plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @since 1.0.0
	 *
	 * @return string Plugin path.
	 */
	public function get_plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get the plugin base path name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Plugin base path name.
	 */
	public function get_plugin_basename() {
		return plugin_basename( __FILE__ );
	}


	/**
	 * Load the plugin text domain.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'rtcamp-contributors', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
}
