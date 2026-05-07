<?php
/**
 * Handles the Weather Data.
 * Represents one object of weather data from the Open Weather API Response's $list array.
 *
 * @package ChoctawNation
 * @subpackage WeatherWidget
 * @since 0.2
 */

namespace ChoctawNation\WeatherWidget\Data;

use ChoctawNation\WeatherWidget\Public\Bootstrap_Icons;
use DateTime;
use DateTimeZone;

/** Handles a single object of weather data from the Open Weather API Response's $list array. */
class Weather {
	/**
	 * The pretty date format for the date
	 *
	 * @var string $pretty_date_format
	 */
	private string $pretty_date_format = 'l, F j';

	/**
	 * The pretty full date for the date
	 *
	 * @var string $pretty_full_date
	 */
	private string $pretty_full_date;

	/**
	 * The temperature in Fahrenheit
	 *
	 * @var int $temp
	 */
	private int $temp;

	/**
	 * The humidity percentage
	 *
	 * @var int $humidity
	 */
	private int $humidity;

	/**
	 * The wind speed in mph
	 *
	 * @var int $wind
	 */
	private int $wind;

	/**
	 * The day of the week
	 *
	 * @var string $day
	 */
	private string $day;

	/**
	 * The icon for the weather
	 *
	 * @var string $icon
	 */
	private string $icon;

	/**
	 * The description of the weather
	 *
	 * @var string $description
	 */
	private string $description;

	/**
	 * The chance of rain as a percentage
	 *
	 * @var int $chance_of_rain
	 */
	private int $chance_of_rain;

	/**
	 * The date as a PHP DateTime object if it needs further manipulation
	 *
	 * @var DateTime $date_obj;
	 */
	public DateTime $date_obj;

	/**
	 * Inits the class
	 *
	 * @param array $data the weather data from the Open Weather API
	 */
	public function __construct( array $data ) {
		$this->set_the_date( $data['dt_txt'] );
		$this->set_main_props( $data['main'] );
		$this->set_the_weather( $data['weather'][0] );
		if ( isset( $data['wind'] ) ) {
			$this->set_the_wind( $data['wind'] );
		}
		if ( isset( $data['pop'] ) ) {
			$this->chance_of_rain = ( 100 * $data['pop'] );
		}
	}

	/**
	 * Sets the date properties
	 *
	 * @param string $date the text date from the Open Weather API
	 */
	private function set_the_date( string $date ) {
		$utc                    = DateTime::createFromFormat( 'Y-m-d H:i:s', $date, new DateTimeZone( 'UTC' ) );
		$local                  = wp_timezone();
		$central                = $utc->setTimezone( $local );
		$this->pretty_full_date = $central->format( $this->pretty_date_format );
		$this->day              = $central->format( 'D' );
		$this->date_obj         = $central;
	}

	/**
	 * Sets the main properties
	 *
	 * @param array $main the main data from the Open Weather API
	 */
	private function set_main_props( array $main ) {
		if ( isset( $main['temp_max'] ) ) {
			$this->temp = round( $main['temp_max'] );
		}
		if ( isset( $main['humidity'] ) ) {
			$this->humidity = round( $main['humidity'] );
		}
	}

	/**
	 * Sets the weather properties
	 *
	 * @param array $weather the weather data from the Open Weather API
	 */
	private function set_the_weather( array $weather ) {
		$this->icon        = $this->set_the_icon( $weather['main'] );
		$this->description = $weather['description'];
	}

	/**
	 * Sets the icon
	 *
	 * @param string $icon_name the icon name from the Open Weather API
	 */
	private function set_the_icon( string $icon_name ): string {
		$bs_icon = new Bootstrap_Icons();
		return $bs_icon->get_the_icon( $icon_name );
	}

	/**
	 * Sets the wind properties
	 *
	 * @param array $wind the wind data from the Open Weather API
	 */
	private function set_the_wind( array $wind ) {
		if ( isset( $wind['speed'] ) ) {
			$this->wind = round( $wind['speed'] );
		}
	}

	/**
	 * Returns the Temperature
	 *
	 * @return int
	 */
	public function get_the_temp(): int {
		return $this->temp;
	}

	/**
	 * Echoes the Temperature
	 */
	public function the_temp() {
		echo $this->temp;
	}

	/**
	 * Returns the pretty full date as "10 Jul, 2023"
	 *
	 * @return string
	 */
	public function get_the_full_date(): string {
		return $this->pretty_full_date;
	}

	/**
	 * Echoes the pretty full date as "10 Jul, 2023"
	 */
	public function the_full_date() {
		echo $this->pretty_full_date;
	}

	/**
	 * Returns the Humidity
	 *
	 * @return int
	 */
	public function get_the_humidity(): int {
		return $this->humidity;
	}

	/**
	 * Echoes the Humidity
	 */
	public function the_humidity() {
		echo $this->humidity;
	}

	/**
	 * Returns the Wind Speed
	 *
	 * @return int
	 */
	public function get_the_wind(): int {
		return $this->wind;
	}

	/**
	 * Echoes the Wind Speed
	 */
	public function the_wind() {
		echo $this->wind;
	}

	/**
	 * Returns the Day of the Week
	 *
	 * @return string
	 */
	public function get_the_day(): string {
		return $this->day;
	}

	/**
	 * Echoes the Day of the Week
	 */
	public function the_day() {
		echo $this->day;
	}

	/**
	 * Returns the Icon
	 *
	 * @return string
	 */
	public function get_the_icon(): string {
		return $this->icon;
	}

	/**
	 * Echoes the Icon
	 */
	public function the_icon() {
		echo $this->icon;
	}

	/**
	 * Returns the Description
	 *
	 * @return string
	 */
	public function get_the_description(): string {
		return $this->description;
	}

	/**
	 * Echoes the Description
	 */
	public function the_description() {
		echo $this->description;
	}

	/**
	 * Returns the Chance of Rain
	 *
	 * @return int
	 */
	public function get_the_chance_of_rain(): int {
		return $this->chance_of_rain;
	}

	/**
	 * Echoes the Chance of Rain
	 */
	public function the_chance_of_rain() {
		echo $this->chance_of_rain;
	}
}
