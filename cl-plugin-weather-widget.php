<?php
/**
 * Plugin Name: [Choctaw Landing] Weather Widget
 * Description: Weather Widget plugin syncs weather data from Open Weather API for Choctaw Landing (today's forecast).
 * Plugin URI: https://github.com/choctaw-nation/cl-plugin-weather-widget
 * Version: 1.0.1
 * Author: Choctaw Nation of Oklahoma
 * Author URI: https://www.choctawnation.com
 * Text Domain: cno
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Requires PHP: 8.2
 * Requires at least: 6.0
 * Tested up to: 6.9.4
 * Requires Plugins: advanced-custom-fields-pro
 *
 * @package ChoctawNation
 * @subpackage WeatherWidget
 */

use ChoctawNation\WeatherWidget\Plugin_Loader;

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

$cno_autoload_path = __DIR__ . '/vendor/autoload.php';

if ( ! file_exists( $cno_autoload_path ) ) {
	add_action(
		'admin_notices',
		static function () {
			echo '<div class="notice notice-error"><p>Choctaw Landing Weather Widget is missing required dependencies. Please run Composer install or deploy the plugin with its vendor directory included.</p></div>';
		}
	);

	return;
}

require_once $cno_autoload_path;
$cno_plugin = new Plugin_Loader();

// Plugin Lifecycle Hooks
register_activation_hook( __FILE__, array( $cno_plugin, 'activate' ) );

// Static method for uninstall since the plugin can't rely on instance methods.
register_uninstall_hook( __FILE__, array( 'ChoctawNation\WeatherWidget\Plugin_Loader', 'uninstall' ) );

// Load the Plugin
add_action( 'plugins_loaded', array( $cno_plugin, 'load_plugin' ) );