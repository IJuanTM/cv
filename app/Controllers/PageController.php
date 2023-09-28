<?php

namespace app\Controllers;

use app\Models\PageModel;

/**
 * The PageController class is the controller for the pages. It parses the URL and loads the page.
 * It also contains the methods for loading the needed HTML parts.
 */
class PageController extends PageModel
{
    public function __construct()
    {
        // Get the current url
        $urlArr = explode('/', rtrim(strtolower($_REQUEST['__uri'] ?? REDIRECT), '/'));

        // Create the a page object from the current url, with the page name, subpages and parameters
        parent::__construct(
            array_shift($urlArr),
            $urlArr,
            array_slice($_GET, 1)
        );

        // Load the page
        $this->load();
    }

    /**
     * Method for loading the page. It loads the needed PHP class (Page) that corresponds with the page and loads the needed HTML parts.
     * It also checks if the page exists. If not, it redirects to the 404 page.
     *
     * @return void
     */
    private function load(): void
    {
        // Get start of HTML and the HEAD
        $this->part('top');

        // Get the page name
        $page = $this->getUrl()['page'];

        // Load the PHP class that corresponds with the page, give the current page object as parameter
        $obj = 'app\Pages\\' . str_replace(' ', '', ucwords(str_replace('-', ' ', $page))) . 'Page';
        if (class_exists($obj)) $this->setObj(new $obj($this));

        // Get the file from the views folder
        $file = BASEDIR . "/views/$page.phtml";

        // Get the content of the BODY -> SECTION
        if (file_exists($file)) require_once $file;
        else {
            // Log the error if the environment is development
            if (DEV) LogController::log("Could not find view \"$page\"", 'debug');

            // Redirect to the 404 page
            self::redirect('error/404');
        }

        // Get the footer part and end of HTML
        $this->part('bottom');
    }

    /**
     * Method for loading a part from the parts folder.
     *
     * @param string $name
     *
     * @return void
     */
    private function part(string $name): void
    {
        // Get the file from the parts folder
        $file = BASEDIR . "/views/parts/$name.phtml";

        // Check if the file exists, if so load the file
        if (file_exists($file)) require_once $file;
        else {
            if (DEV) {
                // Log the error
                LogController::log("Could not load part \"$name\"", 'debug');

                // Print an error message
                echo "Part \"$name\" not found";
            } else echo "<!-- Part \"$name\" not found -->";
        }
    }

    /**
     * Method for redirecting to another page. It takes a delay and a location as input and redirects to the given location with the given delay.
     *
     * @param string $location
     * @param int|null $refresh
     *
     * @return void
     */
    public static function redirect(string $location, ?int $refresh = 0): void
    {
        // Redirect to the given location with the given delay
        header("refresh: $refresh; url=" . self::url($location));
    }

    /**
     * Method for creating a URL. It takes a sub URL as input and returns a complete URL path.
     *
     * @param string $subUrl
     *
     * @return string
     */
    public static function url(string $subUrl = ''): string
    {
        // Make a static variable $baseUrl
        static $baseUrl;

        // Check if http or https, then take the host, root directory and the base directory and return a complete URL path
        if (!$baseUrl) $baseUrl = 'http' . (!empty($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . preg_replace('@^' . preg_quote(rtrim(BASEDIR, '/')) . '@', '', BASEDIR);

        // Create the url
        $url = trim($baseUrl, '/') . '/' . ltrim($subUrl, '/');

        // Check if it is a file, if so add the filemtime to the url
        if (is_file(rtrim(BASEDIR, '/') . '/' . $subUrl)) {
            // Explode the url in a page and a fragment
            [$page, $fragment] = explode('#', $url . '#', 2);

            // Add the filemtime to the url
            $page .= (!str_contains($page, '?')) ? '?' : '&' . http_build_query(['_' => filemtime(rtrim(BASEDIR, '/') . '/' . $subUrl)]);

            // Return the url
            return $page . ($fragment ? '#' . $fragment : '');
        } else $url = rtrim($url, '/');

        // Return the URL
        return $url;
    }
}
