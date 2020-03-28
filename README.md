# Laravel Query Log Tracker

[![Latest Stable Version](https://poser.pugx.org/ngmy/laravel-query-log-tracker/v/stable)](https://packagist.org/packages/ngmy/laravel-query-log-tracker)
[![Total Downloads](https://poser.pugx.org/ngmy/laravel-query-log-tracker/downloads)](https://packagist.org/packages/ngmy/laravel-query-log-tracker)
[![Latest Unstable Version](https://poser.pugx.org/ngmy/laravel-query-log-tracker/v/unstable)](https://packagist.org/packages/ngmy/laravel-query-log-tracker)
[![License](https://poser.pugx.org/ngmy/laravel-query-log-tracker/license)](https://packagist.org/packages/ngmy/laravel-query-log-tracker)
[![composer.lock](https://poser.pugx.org/ngmy/laravel-query-log-tracker/composerlock)](https://packagist.org/packages/ngmy/laravel-query-log-tracker)<br>
[![Build Status](https://travis-ci.org/ngmy/laravel-query-log-tracker.svg?branch=master)](https://travis-ci.org/ngmy/laravel-query-log-tracker)
[![Coverage Status](https://coveralls.io/repos/github/ngmy/laravel-query-log-tracker/badge.svg?branch=master)](https://coveralls.io/github/ngmy/laravel-query-log-tracker?branch=master)

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
