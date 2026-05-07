<?php
/**
 * Weather Data Job class
 *
 * @package ChoctawNation
 * @subpackage WeatherWidget
 */

namespace ChoctawNation\WeatherWidget\Jobs;

use ChoctawNation\WeatherWidget\Data\Weather;
use ChoctawNation\WeatherWidget\Data\Weather_Handler;
use ChoctawNation\WeatherWidget\Http\API;
use ChoctawNation\WeatherWidget\Plugin_Loader;
use ChoctawNation\WeatherWidget\WP\Notifier;

/**
 * Handles fetching weather data and storing it in a transient
 */
class Weather_Data {
	/**
	 * The API instance to use for fetching weather data
	 *
	 * @var API $api
	 */
	private API $api;

	/**
	 * The Notifier instance for sending notifications
	 *
	 * @var Notifier $notifier
	 */
	private Notifier $notifier;

	/**
	 * Transient key for storing weather data
	 *
	 * @var string $transient_key
	 */
	private string $transient_key;

	/**
	 * The length of time to cache the weather data
	 *
	 * @var int $cache_length
	 */
	private int $cache_length;

	/**
	 * Constructor
	 *
	 * @param API      $api The API instance to use for fetching weather data
	 * @param Notifier $notifier The Notifier instance for sending notifications
	 */
	public function __construct( API $api, Notifier $notifier ) {
		$this->api           = $api;
		$this->notifier      = $notifier;
		$this->transient_key = Plugin_Loader::TRANSIENT_KEY;
		$this->cache_length  = DAY_IN_SECONDS;
	}

	/**
	 * Fetches weather data from the API and stores it in a transient
	 */
	public function fetch_and_store_weather_data() {
		$weather_data = get_transient( $this->transient_key );
		if ( false === $weather_data ) {
			try {
				$data         = $this->api->get_weather_data();
				$weather_data = $this->create_data_array( $data );
			} catch ( \Exception $e ) {
				$this->notifier->send_notification( 'Error fetching weather data: ' . $e->getMessage() );
				$weather_data = array();
			} finally {
				set_transient( $this->transient_key, $weather_data, $this->cache_length );
			}
		}
	}


	/**
	 * Handles the data from the API
	 *
	 * @param array $data_points the data from the API
	 * @return array<string, Weather_Handler> the data as an associative array
	 */
	private function create_data_array( array $data_points ): array {
		$weather_data = array();
		foreach ( $data_points as $data_point ) {
			$weather = new Weather( $data_point );
			$day     = $weather->date_obj->format( 'D' );
			if ( array_key_exists( $day, $weather_data ) ) {
				array_push( $weather_data[ $day ], $weather );
			} else {
				$weather_data[ $day ] = array( $weather );
			}
		}
		foreach ( $weather_data as $day => $weather ) {
			$weather_data[ $day ] = new Weather_Handler( $weather_data[ $day ] );
		}
		return $weather_data;
	}
}
