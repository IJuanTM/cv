<?php
// Environment configuration
define('DEV', $_ENV['DEV']);

// Timezone configuration
const TIMEZONE = 'Europe/Amsterdam'; // https://www.php.net/manual/en/timezones.php

// App configuration
define('APP_NAME', $_ENV['APP_NAME']);
define('APP_VERSION', $_ENV['APP_VERSION']);
define('APP_URL', $_ENV['APP_URL']);

// Last update
define('LAST_UPDATE', $_ENV['LAST_UPDATE']);

// Redirect configuration
const REDIRECT = 'home';
const ERROR_AUTO_REDIRECT = true;
