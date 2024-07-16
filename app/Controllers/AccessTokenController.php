<?php

namespace app\Controllers;

use app\Database\Database;
use Exception;

class AccessTokenController
{

    /**
     * This method is for generating a verification code.
     *
     * @return string
     */
    public static function generateToken(): string
    {
        try {
            // Generate a random string
            $token = strtoupper(bin2hex(random_bytes(3)));
        } catch (Exception $e) {
            // Log the error
            LogController::log($e->getMessage(), 'error');

            // Return an error message
            return FormController::alert('Error! Something went wrong! Please try again or contact an admin.', 'error', 'home', 2);
        }

        // Check if the token already exists, if it does, generate a new one
        if (self::checkToken($token)) return self::generateToken();

        return $token;
    }

    /**
     * This method is for checking if the token already exists.
     *
     * @param string $token
     *
     * @return bool
     */
    public static function checkToken(string $token): bool
    {
        $db = new Database();

        // Check if the token already exists
        $db->query('SELECT * FROM access_tokens WHERE token = :token');
        $db->bind(':token', $token);

        return $db->rowCount() > 0;
    }

    /**
     * This method is for validating the token.
     * It checks if the token exists and has not expired.
     * It then increments the usage count for the token.
     *
     * @param string $token
     *
     * @return void
     */
    public static function validateToken(string $token): void
    {
        $db = new Database();

        // Check if the token exists
        $db->query('SELECT * FROM access_tokens WHERE token = :token');
        $db->bind(':token', $token);

        // Check if the token exists
        if (!$db->rowCount()) {
            // Return an error message
            AppController::alert('De ingevoerde code is ongeldig! Probeer opnieuw.', ['warning', 'global'], 4);
            return;
        }

        // Get the token
        $token = $db->single();

        // Check if the token is active
        if (!$token['is_active']) {
            // Return an error message
            AppController::alert('Deze code is niet meer actief! Vraag een nieuwe aan.', ['warning', 'global'], 4);
            return;
        }

        // Check if the token has expired
        if (strtotime($token['expires_at']) < time()) {
            // Return an error message
            AppController::alert('Deze code is verlopen! Vraag een nieuwe aan.', ['warning', 'global'], 4);
            return;
        }

        // Increment the usage count
        $db->query('UPDATE access_tokens SET uses = uses + 1 WHERE token = :token');
        $db->bind(':token', $token['token']);
        $db->execute();

        // Set the session variables
        $_SESSION['token'] = $token['token'];

        // Redirect to the home page
        PageController::redirect('home');

        // Set the success message
        AppController::alert('Code succesvol gevalideerd!', ['success', 'global'], 4);
    }
}
