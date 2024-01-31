<?php

namespace app\Controllers;

/**
 * The LogController class is used for logging different types of messages.
 */
class LogController
{
    /**
     * This method is for logging different types of messages.
     *
     * @param $message
     * @param $type
     *
     * @return void
     */
    public static function log($message, $type): void
    {
        // Get the Logs directory
        $dir = BASEDIR . '/app/Logs';

        // Check if the Logs directory exists and create it if it doesn't
        if (!is_dir($dir)) mkdir($dir);

        // Open log file or create it if it doesn't exist
        $file = fopen(BASEDIR . "/app/Logs/$type.log", 'a');

        // Get debug backtrace
        $trace = debug_backtrace();

        // Get the caller
        $caller = array_shift($trace);

        // Write message to file
        fwrite($file, '[' . date('Y-m-d H:i:s') . "] $message ($caller[file] on line $caller[line])" . PHP_EOL);

        // Close log file
        fclose($file);
    }
}
