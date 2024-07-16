<?php

namespace app\Controllers;

/**
 * The FormController class is used for showing feedback to the user after a form submit has been called.
 */
class FormController
{
    public static string $alert = '';

    /**
     * This method is used to give feedback to the user after a form submit has been called. It will show up above the form.
     *
     * @param string $message
     * @param string $type
     * @param string $location
     * @param int|null $refresh
     *
     * @return string
     */
    public static function alert(string $message, string $type, string $location, int $refresh = null): string
    {
        // Redirect to the given location after the given refresh time.
        PageController::redirect($location, $refresh);

        // Return the alert message.
        return static::$alert = "<div class='alert $type' role='alert'>$message</div>";
    }
}
