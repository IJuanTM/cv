<?php

namespace app\Controllers;

use app\Database\Database;
use Exception;

/**
 * The UserController class is the base class for all user related methods. It contains methods that are used in multiple user related pages.
 */
class UserController
{
    /**
     * This method checks if the user exists in the database.
     *
     * @param int $id
     *
     * @return bool
     */
    public static function exists(int $id): bool
    {
        $db = new Database();

        // Check if the user exists in the database
        $db->query('SELECT id FROM users WHERE id = :id');
        $db->bind(':id', $id);
        $db->execute();

        // Return true if the user exists in the database
        return $db->rowCount() > 0;
    }

    /**
     * This method is for generating a verification code.
     *
     * @param int $bytes
     *
     * @return string
     */
    public static function generateToken(int $bytes): string
    {
        try {
            // Generate a random string
            return strtoupper(bin2hex(random_bytes($bytes)));
        } catch (Exception $e) {
            // Log the error
            LogController::log($e->getMessage(), 'error');

            // Return an error message
            return FormController::alert('Error! Something went wrong! Please try again or contact an admin.', 'error', 'home', 2);
        }
    }

    /**
     * This method is for checking if the entered email exists in the database.
     * It is used for the login and reset password form.
     *
     * @param string $email
     *
     * @return bool
     */
    public static function checkEmail(string $email): bool
    {
        $db = new Database();

        // Check if the email exists in the database
        $db->query('SELECT * FROM users WHERE email = :email');
        $db->bind(':email', $email);

        // Return true if the email exists in the database
        return $db->rowCount() > 0;
    }

    /**
     * This method is for limiting the access to a page.
     * It checks if the current user role is in the array of roles.
     *
     * @param array $roles
     *
     * @return void
     */
    public static function access(array $roles): void
    {
        // If the current user role is not in the array, redirect to error page
        if (!is_null($_SESSION['user']['role']) && in_array($_SESSION['user']['role'], $roles)) return;
        else {
            // Redirect to error page
            PageController::redirect('error/403');
            exit;
        }
    }

    /**
     * Method for checking if the user is verified. Can either be called with the user id or email.
     *
     * @param int|null $id
     * @param string|null $email
     *
     * @return bool
     */
    public static function isVerified(int $id = null, string $email = null): bool
    {
        $db = new Database();

        if (!is_null($email)) {
            // Get the user id from the database
            $db->query('SELECT id FROM users WHERE email = :email');
            $db->bind(':email', $email);
            $id = $db->single()['id'];
        }

        // Check if the user is verified
        $db->query('SELECT * FROM tokens WHERE user_id = :id AND type = :type');
        $db->bind(':id', $id);
        $db->bind(':type', 'verification');

        // Return true if the user is verified
        return $db->rowCount() == 0;
    }

    public static function checkToken(int $id, string $token, string $type): bool
    {
        $db = new Database();

        // Get the token from the database
        $db->query('SELECT token FROM tokens WHERE user_id = :user_id AND type = :type');
        $db->bind(':user_id', $id);
        $db->bind(':type', $type);

        // Check if the token is valid
        return strcasecmp($db->single()['token'], $token) == 0;
    }

    /**
     * Method for logging in a user automatically when the remember me cookie is set.
     *
     * @param string $token
     *
     * @return void
     */
    public static function rememberLogin(string $token): void
    {
        $db = new Database();

        // Get the remember token from the database
        $db->query('SELECT * FROM tokens WHERE token = :token AND type = :type');
        $db->bind(':token', $token);
        $db->bind(':type', 'remember');
        $token = $db->single();

        // Check if the token exists
        if (!$token) {
            // Delete the cookie
            setcookie('remember', '', time() - 3600);

            return;
        }

        // Check if the token is expired
        if ($token['expires'] < time()) {
            // Delete the token from the database
            $db->query('DELETE FROM tokens WHERE token = :token');
            $db->bind(':token', $token['token']);
            $db->execute();

            // Delete the cookie
            setcookie('remember', '', time() - 3600);

            return;
        }

        // Get the user from the database
        $db->query('SELECT * FROM users WHERE id = :id');
        $db->bind(':id', $token['user_id']);

        // Set the session variables
        $_SESSION['user'] = $db->single();
    }
}
