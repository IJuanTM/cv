<?php

namespace app\Controllers;

/**
 * The ApplicationController class is the base class for all controllers.
 * It contains methods that are used by all controllers. It also contains the autoloader.
 */
class AppController
{
    public function __construct()
    {
        // Clear the alert if the timeout has passed
        if (isset($_SESSION['alert']) && $_SESSION['alert']['timeout'] < time()) unset($_SESSION['alert']);

        // Create a new PageController object
        new PageController();
    }

    /**
     * Method for loading an svg file and returning it as a string.
     *
     * @param string $name
     *
     * @return bool|string
     */
    public static function svg(string $name): bool|string
    {
        // Get the svg file
        $file = BASEDIR . "/public/img/svg/$name.svg";

        // Return the svg file if it exists, else return an error message
        if (file_exists($file)) return file_get_contents($file);
        else {
            if (DEV) {
                // Log the error
                LogController::log("Could not find SVG \"$name\"", 'debug');

                // Return an error message
                return "SVG \"$name\" not found";
            } else return "<!-- SVG \"$name\" not found -->";
        }
    }

    /**
     * Method for sanitizing data to prevent XSS attacks.
     *
     * @param $data
     *
     * @return string
     */
    public static function sanitize($data): string
    {
        // Sanitize the data
        return htmlspecialchars(trim($data), ENT_QUOTES);
    }

    /**
     * Method for showing a global alert at the bottom of the page.
     *
     * @param string $message
     * @param array $types
     * @param int $timeout
     *
     * @return void
     */
    public static function alert(string $message, array $types, int $timeout = 0): void
    {
        // Set the alert
        $_SESSION['alert'] = [
            'message' => $message,
            'types' => $types,
            'timeout' => time() + $timeout
        ];
    }
}
