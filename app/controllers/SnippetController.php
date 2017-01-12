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
            $user = Auth::getUserById($_SESSION['user_id']);
            if ($user) {
                self::addSnippet($_SESSION['user_id']);
                self::renderSnippets($user);
                return;
            } else {
                View::error(500);
            }
        }
        if (count($uriComps) == 2) {
            if (strtolower($uriComps[1]) === $_SESSION['user_name']) {
                Helper::redirect('/snippets');
            }
            $user = Auth::getUserByName($uriComps[1]);
            if ($user) {
                self::renderSnippets($user);
                return;
            }
        }
        View::error(404);
    }

    public static function addSnippet(string $user_id)
    {
        if (isset($_POST['content']) && isset($_POST['token'])) {
            $content = $_POST['content'];
            $token = $_POST['token'];
            if (!CSRFProtection::checkToken('add_snippet', $token)) {
                $GLOBALS['error'] = 'Form token is invalid! Please try again.';
                return;
            }
            if (empty($content)) {
                $GLOBALS['error'] = 'Snippet content cannot be empty.';
                return;
            }
            SnippetManager::addSnippet($user_id, HTML::purify($content));
            Helper::redirect('/snippets');
        }
    }

    public static function renderSnippets($user)
    {
        $GLOBALS['snippet_user'] = $user;
        View::render('snippets');
    }
}
