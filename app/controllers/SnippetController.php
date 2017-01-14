<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG\Controllers;

use BYOG\Components\Auth;
use BYOG\Components\CSRFProtection;
use BYOG\Components\Helper;
use BYOG\Components\HTML;
use BYOG\Components\View;
use BYOG\Managers\SnippetManager;
use BYOG\Managers\UserManager;

/**
 * Class SnippetController
 * @package BYOG\Controllers
 */
class SnippetController
{
    public static function handle($uriComps)
    {
        if (count($uriComps) == 1) {
            if (Auth::isGuest()) {
                Helper::redirect('/login');
            }
            $user = UserManager::getUserById($_SESSION['user_id']);
            if ($user) {
                self::addSnippet($user);
                self::renderSnippets($user);
                return;
            } else {
                View::error(500);
            }
        }
        if (count($uriComps) == 2) {
            if (strtolower($uriComps[1]) === strtolower($_SESSION['user_name'])) {
                Helper::redirect('/snippets');
            }
            $user = UserManager::getUserByName($uriComps[1]);
            if ($user) {
                self::renderSnippets($user);
                return;
            }
        }
        View::error(404);
    }

    public static function addSnippet(array $user)
    {
        if (isset($_POST['content']) && isset($_POST['token'])) {
            $content = $_POST['content'];
            $token = $_POST['token'];
            if (!CSRFProtection::checkToken('add_snippet_' . $_SESSION['user_id'], $token)) {
                $GLOBALS['error'] = 'Form token is invalid! Please try again.';
                return;
            }
            if($user['is_disabled']) {
                $GLOBALS['error'] = 'Your account has been locked by admins. You cannot add new snippets.';
                return;
            }
            if (empty($content)) {
                $GLOBALS['error'] = 'Snippet content cannot be empty.';
                return;
            }
            SnippetManager::addSnippet($user['id'], nl2br(HTML::purify($content)));
            Helper::redirect('/snippets');
        }
    }

    public static function renderSnippets($user)
    {
        $GLOBALS['snippet_user'] = $user;
        View::render('snippets');
    }
}
