<?php
/**
 * Handles the Weather Widget
 *
 * @package ChoctawNation
 * @subpackage WeatherWidget
 * @since 0.2
 */

namespace ChoctawNation\WeatherWidget\Public;

use ChoctawNation\WeatherWidget\Data\Weather_Handler;
use ChoctawNation\WeatherWidget\Plugin_Loader;

/**
 *  Gets the forecast and creates an accessible array of weather data.
 */
class Weather_Widget {
	/**
	 * The sorted Weather data as an associative array (e.g. 'Fri' => `Weather_Handler`)
	 *
	 * @var array $data
	 */
	private array $data;

	/**
	 * Whether or not there was an error
	 *
	 * @var bool $has_error
	 */
	private bool $has_error = false;

	/**
	 * The error message
	 *
	 * @var string $error
	 */
	private string $error;

	/**
	 * The Weather_Widget constructor.
	 */
	public function __construct() {
		$data = get_transient( Plugin_Loader::TRANSIENT_KEY );
		if ( false === $data || ! is_array( $data ) ) {
			$this->has_error = true;
			$this->error     = 'Unable to retrieve weather data. Please try again later.';
			if ( empty( $this->data ) ) {
				$this->has_error = true;
				$this->error     = 'Unable to retrieve weather data. Please try again later.';
			}
			return;
		}
		$this->data = $data;
	}

	/**
	 * Returns the error status
	 *
	 * @return bool the error status
	 */
	public function has_error(): bool {
		return $this->has_error;
	}

	/**
	 * Returns the error message
	 *
	 * @return string the error message
	 */
	public function get_error_message(): string {
		if ( $this->has_error ) {
			return $this->error;
		} else {
			return '';
		}
	}

	/**
	 * Returns the Weather data for today
	 *
	 * @return ?Weather_Handler the Weather data for today
	 */
	public function today(): ?Weather_Handler {
		if ( $this->has_error || empty( $this->data ) ) {
			return null;
		}
		$today_index = array_keys( $this->data )[0];
		return $this->data[ $today_index ];
	}

	/**
	 * Returns the Weather data
	 *
	 * @return array the Weather data
	 */
	public function get_the_weather_data(): array {
		return $this->data;
	}
}
