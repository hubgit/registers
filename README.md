Fetch the contents of [registers](http://www.openregister.org/), save them as NDJSON.

## Usage

1. Run `composer install` to install dependencies (Guzzle).
2. Edit the list of registers in `fetch.php`, if desired.
3. Run `php fetch.php` to fetch all the registers to the `data` folder.

## TODO

* How to fetch incremental updates
