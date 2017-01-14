<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG\Controllers;

use BYOG\Components\Auth;
use BYOG\Managers\SnippetManager;
use BYOG\Managers\UserManager;

/**
 * Class APIController
 * @package BYOG\Controllers
 */
class APIController
{
    public static function handle(array $comps)
    {
        if (Auth::isGuest()) {
            http_response_code(401);
            die('Only authorised users can access the API.');
        }

        if (count($comps) === 3 && $comps[1] === 'snippets' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $snippetId = $comps[2];
            $snippet = SnippetManager::getSnippet($snippetId);
            if (!$snippet) {
                http_response_code(400);
                die('Specified snippet does not exist.');
            }
            if ($snippet['user_id'] !== $_SESSION['user_id']) {
                http_response_code(403);
                die('You cannot delete someone else\'s snippet.');
            }
            SnippetManager::removeSnippet($snippetId);
            http_response_code(200);
            die('Snippet deleted.');
        }


        if (count($comps) === 3 && $comps[1] === 'files' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $fileName = $comps[2];
            $filePath = UPLOAD_DIR . '/' . $_SESSION['user_name'] . '/' . $fileName;
            if (!file_exists($filePath)) {
                http_response_code(400);
                die('Specified file does not exist.');
            }
            unlink($filePath);
            http_response_code(200);
            die('File deleted.');
        }

        if (count($comps) === 3 && $comps[1] === 'admin' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Auth::isAdmin()) {
                http_response_code(403);
                die('Only admins can perform this action.');
            }
            $targetId = $comps[2];
            $user = UserManager::getUserById($targetId);
            if (!$user) {
                http_response_code(400);
                die('Specified user does not exist.');
            }
            if ($user['id'] === $_SESSION['user_id']) {
                http_response_code(400);
                die('You cannot alter your own parameters.');
            }
            if (!isset($_POST['action'])) {
                http_response_code(400);
                die('POST `action` is not set!');
            }
            $action = $_POST['action'];
            $updates = [];
            switch ($action) {
                case 'admin':
                    $updates['is_admin'] = 1 - $user['is_admin'];
                    break;
                case 'lock':
                    $updates['is_locked'] = 1 - $user['is_locked'];
                    break;
                case 'disable':
                    $updates['is_disabled'] = 1 - $user['is_disabled'];
                    break;
                case 'delete':
                    UserManager::deleteUser($targetId);
                    break;
                default:
                    http_response_code(400);
                    die('Unrecognised action.');
            }
            if ($updates != []) {
                UserManager::updateUser($targetId, $updates);
            }
            http_response_code(200);
            die('Action performed!');
        }

        http_response_code(404);
        die('API endpoint was not found.');
    }
}
