<?php
/**
 * API Class
 *
 * @package ChoctawNation
 * @subpackage WeatherWidget
 */

namespace ChoctawNation\WeatherWidget\Http;

use Exception;

/**
 * Handles the API
 */
class API {
	/**
	 * The Base Open Weather API endpoint
	 *
	 * @var string $base_url
	 */
	protected string $base_url = 'https://api.openweathermap.org/data/2.5/forecast?lat=34.1418916&lon=-94.7446644&units=imperial';

	/**
	 * The API key for the Open Weather API
	 *
	 * @var string $api_key
	 */
	private string $api_key;

	/**
	 * API constructor.
	 *
	 * @param string $api_key  The API key for the Open Weather API.
	 */
	public function __construct( string $api_key ) {
		$this->api_key = $api_key;
	}

	/**
	 * Fetches weather data from the Open Weather API
	 *
	 * @return array|null The weather data as an associative array, or null if there was an error
	 * @throws Exception If there was an error fetching the weather data.
	 */
	public function get_weather_data(): array|null {
		if ( empty( $this->api_key ) ) {
			throw new Exception( 'API key is required to fetch weather data.' );
		}
		$weather_url = $this->base_url . '&appid=' . $this->api_key;
		$response    = wp_remote_get( $weather_url );
		if ( is_wp_error( $response ) ) {
			throw new Exception( 'Error fetching weather data: ' . esc_textarea( $response->get_error_message() ) );
		}
		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			throw new Exception(
				sprintf(
					'Error fetching weather data: %d: %s',
					absint( wp_remote_retrieve_response_code( $response ) ),
					esc_textarea( wp_remote_retrieve_response_message( $response ) )
				)
			);
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( gettype( $data ) === 'string' ) {
			throw new Exception( 'Error fetching weather data: ' . esc_textarea( $data ) );
		} elseif ( is_array( $data ) && '200' !== $data['cod'] ) {
			throw new Exception( 'Error fetching weather data: ' . esc_textarea( $data['message'] ) );
		}
		return $data['list'];
	}
}
