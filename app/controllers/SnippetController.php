<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG\Controllers;

use BYOG\Components\Auth;
use BYOG\Components\Helper;
use BYOG\Components\View;

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
            $user = Auth::getUserByName($_SESSION['user_name']);
            if ($user) {
                self::addSnippet();
                self::renderSnippets($user);
                return;
            } else {
                View::error(500);
            }
        }
        if (count($uriComps) == 2) {
            if (strtolower($uriComps[1]) === $_SESSION['user_id']) {
                Helper::redirect('/snippets');
            }
            $user = Auth::getUserById($uriComps[1]);
            if ($user) {
                self::renderSnippets($user);
                return;
            }
        }
        View::error(404);
    }

    public static function addSnippet()
    {

    }

    public static function renderSnippets($user)
    {
        $GLOBALS['snippet_user'] = $user;
        View::render('snippets');
    }
}
