<?php

use app\Controllers\AppController;

// Start the output buffer
ob_start();

// Start the session
session_start();

// Set the base directory
define('BASEDIR', realpath(dirname(__DIR__)));

// Require the composer autoloader
require_once BASEDIR . '/vendor/autoload.php';

// Load the .env file
Dotenv\Dotenv::createImmutable(BASEDIR)->load();

// Require the config files
foreach (glob(BASEDIR . '/app/Config/*.php') as $file) require_once $file;

// Set the timezone to be used
date_default_timezone_set(TIMEZONE);

// Enable PHP error logging
ini_set('log_errors', 'On');

// Set error reporting based on the environment
if (DEV) {
    ini_set('display_errors', 'On');
    ini_set('display_startup_errors', 'On');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 'Off');
    ini_set('display_startup_errors', 'Off');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
}

// Make a new AppController object
new AppController();
