# Laravel Query Log Tracker

The Laravel package which log all executed queries.

## Features

Laravel Query Log Tracker has the following features:

* Log all executed queries with the following items:
  * SQL with bound parameters
  * Bind parameters
  * Execution time
  * Connection name
* Disable/Enable query logging at runtime
* More configurations:
  * Log level
  * Exclude patterns
  * Channel
  * Stacks

## Installation

Execute the Composer `require` command:
```
composer require ngmy/laravel-query-log-tracker
```
This will update your `composer.json` file and install this package into the `vendor` directory.

### Publishing Configuration

Execute the Artisan `vendor:publish` command:
```
php artisan vendor:publish
```
This will publish the configuration file to the `config/ngmy-laravel-query-log-tracker.php` file.

You can also use the tag to execute the command:
```
php artisan vendor:publish --tag=ngmy-laravel-query-log-tracker
```

You can also use the service provider to execute the command:
```
php artisan vendor:publish --provider="Ngmy\LaravelQueryLogTracker\QueryLogTrackerServiceProvider"
```

## Usage

### Log Queries

Execute queries with Laravel. That's all.

### Disable Log

There are two ways to disable log.
```php
QueryLogTracker::beginDisable();
// Execute queries for which you want to disable log
QueryLogTracker::endDisable();
```
```php
QueryLogTracker::disable(function () {
    // Execute queries for which you want to disable log
});
```
