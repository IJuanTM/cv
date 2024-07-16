<?php

namespace app\Pages;

use app\Controllers\AccessTokenController;
use app\Controllers\AppController;
use app\Controllers\PageController;
use app\Models\PageModel;

class HomePage
{
    public function __construct(PageModel $page)
    {
        // Check if the url contains a token
        if (isset($page->getUrl()['params']['token'])) {
            // Check if the token is valid
            AccessTokenController::validateToken($page->getUrl()['params']['token']);
        }

        // Check if the form is submitted
        if (isset($_POST['submit'])) {
            // Check if all the required fields are entered
            if (empty($_POST['token'])) {
                // Redirect to the home page
                PageController::redirect('home');

                // Show the alert
                AppController::alert('Vul een code in!', ['warning', 'global'], 4);
            } else {
                // Check if the token is valid
                AccessTokenController::validateToken($_POST['token']);
            }
        }
    }
}
