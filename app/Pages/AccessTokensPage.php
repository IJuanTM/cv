<?php

namespace app\Pages;

use app\Controllers\{AppController, FormController, PageController, UserController};
use app\Database\Database;
use app\Models\PageModel;
use Exception;

class AccessTokensPage
{
    public int $page = 0;

    public array $accessToken;
    public array $accessTokens;

    /**
     * @throws Exception
     */
    public function __construct(PageModel $page)
    {
        // Only allow admins to access this page
        UserController::access([1]);

        // Get the page number from the url
        if (isset($page->getUrl()['params']['page'])) $this->page = (int)$page->getUrl()['params']['page'];

        // Get all access tokens from the database
        $db = new Database();
        $db->query('SELECT * FROM access_tokens');

        // Store the access tokens in an array
        $this->accessTokens = $db->fetchAll();

        // Check if the user wants to perform a specific action
        if (isset($page->getUrl()['subpages'][0])) {
            // Check if the user wants to create an access token and if the form is submitted
            if ($page->getUrl()['subpages'][0] == 'create' && isset($_POST['submit'])) {
                // Check if the token field is entered
                if (empty($_POST['token'])) {
                    FormController::alert('Please enter a token!', 'warning', 'access-tokens/create');
                    return;
                }

                // Check if the expiration date is entered
                if (empty($_POST['expires_at'])) {
                    FormController::alert('Please enter an expiration date!', 'warning', 'access-tokens/create');
                    return;
                }

                // Check if the token field is not too long
                if (strlen($_POST['token']) > 6) {
                    FormController::alert('The token is too long!', 'warning', 'access-tokens/create');
                    return;
                }

                // Convert the expiration date to timestamp
                $_POST['expires_at'] = strtotime($_POST['expires_at']);

                // Check if the expiration date is not in the past
                if ($_POST['expires_at'] < time()) {
                    FormController::alert('The expiration date is in the past!', 'warning', 'access-tokens/create');
                    return;
                }

                // Create the access token
                self::create(AppController::sanitize($_POST['token']), AppController::sanitize(date('Y-m-d H:i:s', $_POST['expires_at'])));
            }

            // Check if the user wants to edit an access token, delete an access token or restore an access token
            if (in_array($page->getUrl()['subpages'][0], ['edit', 'delete', 'restore'])) {
                // Check if the access token id is given in the url
                if (isset($page->getUrl()['params']['id'])) {
                    // Get the index of the access token in the access tokens array
                    $index = array_search((int)$page->getUrl()['params']['id'], array_column($this->accessTokens, 'id'));

                    // Check if the access token exists in the access tokens array
                    if ($index === false) {
                        PageController::redirect('access-tokens', 2);
                        return;
                    }

                    // Store the access token in a variable
                    $this->accessToken = $this->accessTokens[$index];
                } else PageController::redirect('access-tokens', 2);
            }

            // Check if the user wants to edit an access token and if the form is submitted
            if ($page->getUrl()['subpages'][0] == 'edit' && isset($_POST['submit'])) {
                // Check if the expiration date is entered
                if (empty($_POST['expires_at'])) {
                    FormController::alert('Please enter an expiration date!', 'warning', 'access-tokens/edit/' . $_POST['id']);
                    return;
                }

                // Convert the expiration date to timestamp
                $_POST['expires_at'] = strtotime($_POST['expires_at']);

                // Check if the expiration date is not in the past
                if ($_POST['expires_at'] < time()) {
                    FormController::alert('The expiration date is in the past!', 'warning', 'access-tokens/edit/' . $_POST['id']);
                    return;
                }

                // Update the access token
                self::update($_POST['id'], AppController::sanitize(date('Y-m-d H:i:s', $_POST['expires_at'])));
            }

            // Check if the user wants to delete an access token and if the form is submitted
            if ($page->getUrl()['subpages'][0] == 'delete' && isset($_POST['submit'])) $this->delete($_POST['id']);

            // Check if the user wants to restore an access token and if the form is submitted
            if ($page->getUrl()['subpages'][0] == 'restore' && isset($_POST['submit'])) {
                // Check if the access token is deleted or not
                if (isset($this->accessToken) && !$this->accessToken['is_active']) $this->restore($_POST['id']);
                else PageController::redirect('access-tokens', 2);
            }
        }
    }

    /**
     * This method is for creating a new access token.
     * The access token is generated and stored in the database.
     *
     * @param string $token
     * @param string $expires_at
     *
     * @return void
     */
    private function create(string $token, string $expires_at): void
    {
        $db = new Database();

        // Push the new access token to the database
        $db->query('INSERT INTO access_tokens (token, expires_at) VALUES(:token, :expires_at)');
        $db->bind(':token', $token);
        $db->bind(':expires_at', $expires_at);
        $db->execute();

        // Redirect to the access tokens page
        PageController::redirect('access-tokens');

        // Show the success message
        AppController::alert('Success! The access token has been created!', ['success', 'global'], 4);
    }

    /**
     * This method is for updating an access token in the database.
     *
     * @param int $id
     * @param string $expires_at
     *
     * @return void
     */
    public static function update(int $id, string $expires_at): void
    {
        $db = new Database();

        // Get the access token from the database
        $db->query('SELECT * FROM access_tokens WHERE id = :id');
        $db->bind(':id', $id);
        $accessToken = $db->single();

        // Check if the expiration date has changed
        if ($accessToken['expires_at'] !== $expires_at) {
            // Update the expiration date in the database
            $db->query('UPDATE access_tokens SET expires_at = :expires_at WHERE id = :id');
            $db->bind(':expires_at', $expires_at);
            $db->bind(':id', $id);
            $db->execute();
        }

        // Redirect to the access tokens page
        PageController::redirect('access-tokens');

        // Show the success message
        AppController::alert('Success! The access token has been updated!', ['success', 'global'], 4);
    }

    /**
     * This method is for deleting the access token in the database.
     *
     * @param int $id
     *
     * @return void
     */
    private function delete(int $id): void
    {
        $db = new Database();

        // Delete the access token in the database
        $db->query('UPDATE access_tokens SET is_active = 0, deleted_at = NOW() WHERE id = :id');
        $db->bind(':id', $id);
        $db->execute();

        // Redirect to the access tokens page
        PageController::redirect('access-tokens');

        // Show the success message
        AppController::alert('Access token successfully deleted!', ['success', 'global'], 4);
    }

    /**
     * This method is for restoring the access token after it has been deleted.
     *
     * @param int $id
     *
     * @return void
     */
    private function restore(int $id): void
    {
        $db = new Database();

        // Restore the access token in the database
        $db->query('UPDATE access_tokens SET is_active = 1, deleted_at = NULL WHERE id = :id');
        $db->bind(':id', $id);
        $db->execute();

        // Redirect to the access tokens page
        PageController::redirect('access-tokens');

        // Show the success message
        AppController::alert('Access token successfully restored!', ['success', 'global'], 4);
    }
}
