<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG;

use BYOG\Components\Auth;
use BYOG\Components\Helper;
use BYOG\Components\View;
use BYOG\Controllers\APIController;
use BYOG\Controllers\AuthController;
use BYOG\Controllers\FileController;
use BYOG\Controllers\SettingsController;
use BYOG\Controllers\SnippetController;

/**
 * Class App
 * @package BYOG
 */
class App
{
    public function start()
    {

        header('X-Frame-Options: DENY');

        $comps = Helper::uriComps();

        $GLOBALS['current_page'] = $comps[0];

        switch (strtolower($comps[0])) {
            case '':
                View::render('home');
                break;
            case 'api':
                APIController::handle($comps);
                break;
            case 'snippets':
                SnippetController::handle($comps);
                break;
            case 'files':
                FileController::file();
                break;
            case 'settings':
                SettingsController::settings();
                break;
            case 'login':
                AuthController::login();
                break;
            case 'sign-up':
                AuthController::signUp();
                break;
            case 'logout':
                Auth::logout();
                Helper::redirect('/');
                break;
            default:
                http_response_code(404);
                View::render('404');
        }
    }
}
