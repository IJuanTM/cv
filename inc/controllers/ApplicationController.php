<?php

/**
 * ApplicationController is the main class needed for the system to function.
 * It is here where the top-level functions and methods are written to use in the program.
 * It's also here where the PageController object is created.
 */
class ApplicationController
{
    public function __construct()
    {
        new PageController();
    }

    /**
     * @param $name // Name of svg
     * @return bool|string // Returns the svg
     *
     * This method returns and loads a svg by given name from the img/svg folder.
     */
    public static function svg($name): bool|string
    {
        $file = BASEDIR . IMG . "svg/$name.svg";
        if (file_exists($file)) return file_get_contents($file);
        else return 'Svg not found';
    }

    /**
     * @param $data // Input data
     * @return string // Output sting
     *
     * Check the string and sanitize it for non-html characters.
     */
    public static function sanitize($data): string
    {
        return htmlspecialchars(trim($data), ENT_QUOTES);
    }
}
