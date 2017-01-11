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
use BYOG\Controllers\AuthController;

/**
 * Class App
 * @package BYOG
 */
class App
{
    public function start()
    {
        $comps = Helper::uriComps();

        $GLOBALS['current_page'] = $comps[0];

        switch ($comps[0]) {
            case '':
                View::render('home');
                break;
            case 'login':
                AuthController::login();
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
