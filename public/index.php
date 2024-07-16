<?php

use app\Controllers\AppController;

// Start the output buffer
ob_start();

// Start the session
session_start();

// Extecute the start script
require_once '../app/Scripts/start.php';

// Make a new AppController object
new AppController();
