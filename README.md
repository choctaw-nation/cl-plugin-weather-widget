# Choctaw Landing Plugin: Weather Widget

Plugin that creates data fetching from [open-source Weather API](https://openweathermap.org/weather-conditions).

## Overview

1. API Key is set via custom Options Page (“Weather Widget API”)
2. Plugin schedules a cron to fetch data every 12 hours and store it in a transient (`Plugin_Loader::TRANSIENT_KEY`).
3. Classes in `inc/Public` are meant for use within the theme.

[View the Changelog here](./CHANGELOG.md)

## Links

-   https://openweathermap.org/forecast5#list
-   https://openweathermap.org/weather-conditions
