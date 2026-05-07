<?php
/**
 * Settings page for Choctaw Weather Widget API.
 * Called via Admin_Screen::render_settings_page() callback.
 *
 * @package ChoctawNation
 * @subpackage WeatherWidget
 */

?>
<div class="wrap">
	<h1>Choctaw Weather Widget API Settings</h1>

	<!-- React app mounts here -->
	<div id="cno-weather-widget-api-settings" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp_rest' ) ); ?>" data-rest-url="<?php echo 'cno-weather-widget-api/v1/settings'; ?>"></div>

	<noscript>
		This plugin relies on JavaScript to function properly. Please enable JavaScript in your browser settings and refresh the page.
	</noscript>
</div>
<?php