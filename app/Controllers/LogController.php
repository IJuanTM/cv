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
        // Open log file
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
