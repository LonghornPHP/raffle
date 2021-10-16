# Longhorn PHP Closing Raffle

Based off of work by Chris Cornutt aka enygma. Thanks!

## Setup

1. Clone
2. `composer install` (requires at least PHP 7.4)
3. From the Longhorn PHP Laravel app, save the results of `php artisan longhornphp:bingo:completed` to eligible.tsv
4. `php -S 0.0.0.0:9000`
5. Access at `localhost:9000`

Choose in-person-only vs. virtual using the links in the upper left corner of the screen. Refresh to go through another
iteration of the raffle with the current in-person-only vs. virtual setting. Winners, stored in raffle-filter.csv, will
be skipped in successive rounds.
