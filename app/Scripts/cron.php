<?php

use app\Database\Database;

/* ---------------------------------------------------------------- */

// Extecute the start script
require_once 'start.php';

/* ---------------------------------------------------------------- */

$db = new Database();

/*
 * Cron job to delete access tokens that have expired.
 */
$db->query('DELETE FROM access_tokens WHERE expires_at < NOW()');
$db->execute();

/*
 * Cron job to delete access tokens that have been deleted for more than a day.
 */
$db->query('DELETE FROM access_tokens WHERE deleted_at IS NOT NULL AND deleted_at < NOW() - INTERVAL 1 DAY');
$db->execute();
