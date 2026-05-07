<?php
/**
 * Admin screen and settings registration for Artist API.
 *
 * @package ChoctawNation
 * @subpackage Artist_API
 */

namespace ChoctawNation\Artist_API\WP\AdminScreen;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Admin_Screen
 *
 * Handles the admin menu and settings for the Artist API plugin.
 */
class Admin_Screen {
	/**
	 * Register admin menu and submenu pages.
	 */
	public function register_menus() {
		$cap = 'manage_options';
		add_menu_page(
			'Weather Widget API',
			'Weather Widget API',
			$cap,
			'cno-weather-widget-api',
			array( $this, 'render_overview' ),
			'dashicons-cloud',
			75
		);
		add_submenu_page(
			'cno-weather-widget-api',
			'Settings',
			'Settings',
			$cap,
			'cno-weather-widget-api-settings',
			array( $this, 'render_settings_page' )
		);

		// Remove the automatically added parent duplicate submenu so the menu
		// reads "Weather Widget API -> Settings" with a single child entry.
		remove_submenu_page( 'cno-weather-widget-api', 'cno-weather-widget-api' );
	}

	/**
	 * Enqueue admin screen assets, but only on our plugin's settings page.
	 *
	 * @param string $hook_suffix The current admin page hook suffix.
	 */
	public function load_required_assets( string $hook_suffix ) {
		if ( 'weather-widget-api_page_cno-weather-widget-api-settings' !== $hook_suffix ) {
			return;
		}

		$asset_file         = require_once dirname( __DIR__, 3 ) . '/build/index.asset.php';
		$plugin_assets_path = dirname( __DIR__, 2 );
		$asset_name         = 'cno-weather-widget-api-admin';
		wp_enqueue_script(
			$asset_name,
			plugin_dir_url( $plugin_assets_path ) . 'build/index.js',
			$asset_file['dependencies'],
			$asset_file['version'],
			array( 'strategy' => 'defer' )
		);
		wp_add_inline_script(
			$asset_name,
			'const cnoWeatherWidgetApiSettings = ' . wp_json_encode(
				array(
					'restBase' => rest_url( 'cno-weather-widget-api/v1' ),
					'nonce'    => wp_create_nonce( 'wp_rest' ),
				)
			),
			'before'
		);
	}

	/**
	 * Render the overview page content.
	 */
	public function render_overview() {
		echo '<div class="wrap"><h1>Weather Widget API</h1><p>Welcome to the Weather Widget API plugin! Use the menu on the left to navigate to the settings page.</p></div>';
	}


	/**
	 * Render the settings page content.
	 */
	public function render_settings_page() {
		ob_start();
		require_once __DIR__ . '/settings-page-render-callback.php';
		echo ob_get_clean();
	}
}
