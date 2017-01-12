<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG\Controllers;

use BYOG\Components\Auth;
use BYOG\Managers\SnippetManager;

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
            die('Token deleted.');
        }

        http_response_code(404);
        die('API endpoint was not found.');
    }
}
