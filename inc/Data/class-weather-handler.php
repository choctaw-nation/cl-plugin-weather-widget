<?php
/**
 * Weather Handler
 * Handles calculations and data for multiple Weather objects
 *
 * @package ChoctawNation
 * @subpackage WeatherWidget
 * @since 1.6.3
 */

namespace ChoctawNation\WeatherWidget\Data;

use ChoctawNation\WeatherWidget\Data\Weather;

/**
 * Weather Handler
 */
class Weather_Handler {
	/**
	 * The Weather data
	 *
	 * @var Weather[] $data
	 */
	private array $data;

	/**
	 * The wind speed
	 *
	 * @var int $wind
	 */
	private int $wind;

	/**
	 * The chance of rain
	 *
	 * @var int $chance_of_rain
	 */
	private int $chance_of_rain;

	/**
	 * The humidity
	 *
	 * @var int $humidity
	 */
	private int $humidity;

	/**
	 * The temperature
	 *
	 * @var int $temp
	 */
	private int $temp;

	/**
	 * The weather description
	 *
	 * @var string $description
	 */
	private string $description;

	/**
	 * The weather icon
	 *
	 * @var string $icon
	 */
	private string $icon;

	/**
	 * The Weather_Handler constructor.
	 *
	 * @param Weather[] $data The Weather data
	 */
	public function __construct( array $data ) {
		$this->data = $data;
		$this->set_the_weather_conditions();
		$this->set_props();
	}

	/**
	 * Sets the weather conditions
	 */
	private function set_the_weather_conditions() {
		$descriptions = array();
		foreach ( $this->data as $index => $weather ) {
			$descriptions[ $index ] = $weather->get_the_description();
		}
		$selected_index    = $this->select_index( $descriptions );
		$this->description = $this->data[ $selected_index ]->get_the_description();
		$this->icon        = $this->data[ $selected_index ]->get_the_icon();
	}

	/**
	 * Sets the weather properties to the most common values or the highest value
	 */
	private function set_props() {
		$keys = array(
			'wind',
			'chance_of_rain',
			'humidity',
			'temp',
		);
		foreach ( $keys as $key ) {
			$values = array();
			foreach ( $this->data as $index => $weather ) {
				$values[ $index ] = $weather->{"get_the_$key"}();
			}
			$selected_index = $this->select_index( $values );
			if ( 'temp' === $key || false === $selected_index ) {
				$this->{$key} = max( $values );
			} else {
				$most_common  = $this->data[ $this->select_index( $values ) ]->{"get_the_$key"}();
				$this->{$key} = $most_common;
			}
		}
	}

	/**
	 * Returns an index based on the most repeated value in an array
	 *
	 * @param array $values The array of values to search
	 * @return int|false The index of the most repeated value or false if all values are the same
	 */
	private function select_index( array $values ): int|false {
		$counts   = array_count_values( $values );
		$all_same = count( array_unique( $values ) ) === 1;
		if ( $all_same ) {
			return false;
		}
		arsort( $counts );
		$most_repeated_value = array_key_first( $counts );
		$selected_index      = array_search( $most_repeated_value, $values, true );
		return $selected_index;
	}

	/**
	 * Returns the weather icon
	 *
	 * @return string
	 */
	public function get_the_icon(): string {
		return $this->icon;
	}

	/**
	 * Echoes the weather icon
	 */
	public function the_icon() {
		echo $this->get_the_icon();
	}

	/**
	 * Returns the temperature
	 *
	 * @return int
	 */
	public function get_the_temp(): int {
		return $this->temp;
	}

	/**
	 * Echoes the temperature
	 */
	public function the_temp() {
		echo $this->get_the_temp();
	}

	/**
	 * Returns the full date
	 *
	 * @return string
	 */
	public function get_the_full_date(): string {
		return $this->data[0]->get_the_full_date();
	}

	/**
	 * Echoes The full date
	 */
	public function the_full_date() {
		echo $this->get_the_full_date();
	}

	/**
	 * Returns the day as a 3-letter abbreviation
	 *
	 * @return string
	 */
	public function get_the_day(): string {
		return $this->data[0]->get_the_day();
	}

	/**
	 * Echoes The day
	 */
	public function the_day() {
		echo $this->get_the_day();
	}

	/**
	 * Returns the description
	 *
	 * @return string
	 */
	public function get_the_description(): string {
		return $this->description;
	}

	/**
	 * Echoes the selected data's weather description
	 */
	public function the_description() {
		echo $this->get_the_description();
	}

	/**
	 * Returns the selected data's wind speed
	 *
	 * @return int
	 */
	public function get_the_wind(): int {
		return $this->wind;
	}

	/**
	 * Echoes the selected data's wind speed
	 */
	public function the_wind() {
		echo $this->get_the_wind();
	}

	/**
	 * Returns the selected data's chance of rain
	 *
	 * @return int
	 */
	public function get_the_chance_of_rain(): int {
		return $this->chance_of_rain;
	}

	/**
	 * Echoes the selected data's chance of rain
	 */
	public function the_chance_of_rain() {
		echo $this->get_the_chance_of_rain();
	}

	/**
	 * Returns the selected data's humidity
	 *
	 * @return int
	 */
	public function get_the_humidity(): int {
		return $this->humidity;
	}

	/**
	 * Echoes the selected data's humidity
	 */
	public function the_humidity() {
		echo $this->get_the_humidity();
	}
}
