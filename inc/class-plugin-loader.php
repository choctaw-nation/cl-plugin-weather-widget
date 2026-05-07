<?php
/**
 * Plugin Loader
 *
 * @package ChoctawNation
 * @subpackage WeatherWidget
 */

namespace ChoctawNation\WeatherWidget;

use ChoctawNation\Artist_API\WP\AdminScreen\Admin_Screen;
use ChoctawNation\WeatherWidget\WP\AdminScreen\Settings_Rest_Controller;
use ChoctawNation\WeatherWidget\WP\Plugin_Settings;

/** Inits the Plugin */
class Plugin_Loader {
	/**
	 * Transient key for storing weather data
	 */
	const TRANSIENT_KEY = 'weather_widget_weather_data';

	/**
	 * The Plugin Settings instance
	 *
	 * @var Plugin_Settings $settings
	 */
	private Plugin_Settings $settings;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->settings = new Plugin_Settings();
	}

	/**
	 * Initializes the Plugin
	 *
	 * @return void
	 */
	public function activate(): void {
		$this->settings->register();
		// add plugin settings screen
	}

	/**
	 * Handles Plugin Deactivation
	 * (this is a callback function for the `register_deactivation_hook` function)
	 *
	 * @return void
	 */
	public function deactivate(): void {
		// remove cron
	}

	/**
	 * Handles Plugin Uninstallation
	 * (this is a callback function for the `register_uninstall_hook` function)
	 */
	public static function uninstall(): void {
		// delete transients
		// delete options
	}

	/**
	 * Loads the Plugin
	 */
	public function load_plugin(): void {
		// init admin rest api routes
		$router = new Settings_Rest_Controller( $this->settings );
		add_action( 'rest_api_init', array( $router, 'register_routes' ) );
		$admin_screen = new Admin_Screen();
		add_action( 'admin_menu', array( $admin_screen, 'register_menus' ) );
		add_action( 'admin_enqueue_scripts', array( $admin_screen, 'load_required_assets' ) );

		// schedule cron
		$cron_hook = 'choctaw_weather_widget_update';
		if ( ! wp_next_scheduled( $cron_hook ) ) {
			wp_schedule_event( time(), 'twicedaily', $cron_hook );
		}
		// wire callback
		$api_key      = $this->settings->get_settings()['apiKey'] ?? '';
		$api          = new Http\API( $api_key );
		$weather_data = new Jobs\Weather_Data( $api, new WP\Notifier( 'kroelke@choctawnation.com' ) );
		add_action( $cron_hook, array( $weather_data, 'fetch_and_store_weather_data' ) );
	}
}
