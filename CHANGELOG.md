# Changelog

## v1.0.2 - [August 31, 2026]

-   Fixed: If transient is deleted, do the action to get new data. This should fix the accidental infinite loop of fetching data, then running WP Engine's "Clear all caches" that would destroy the fetched data.
-   Chore: Update packages.
-   Chore: Add `.envrc` file.

## v1.0.1 - [May 7, 2026]

-   Fixed: Fired initial fetch on API key save to prevent "incomplete class" or no data error.

## v1.0.0

-   Init! Refactored from [Choctaw Landing theme](https://github.com/choctaw-nation/landing)
