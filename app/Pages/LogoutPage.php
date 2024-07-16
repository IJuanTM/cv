<?php

namespace app\Pages;

use app\Controllers\PageController;

/**
 * The LogoutPage class is the controller for the logout page.
 * It unsets all session variables and redirects the user back.
 */
class LogoutPage
{
    public function __construct()
    {
        // Unset the user session
        unset($_SESSION['user']);

        // Unset the remember cookie
        setcookie('remember', '', time() - 3600, '/');

        // Redirect the user back to the homepage
        PageController::redirect(REDIRECT, 2);
    }
}
