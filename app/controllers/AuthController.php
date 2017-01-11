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
 * Class AuthController
 * @package BYOG\Controllers
 */
class AuthController
{
    public static function login()
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $name = $_POST['username'];
            $pass = $_POST['password'];
            if (Auth::login($name, $pass)) {
                Helper::redirect('/');
            } else {
                $GLOBALS['error'] = 'Either the details you entered are incorrect or your account has been locked.';
            }
        }

        View::render('login');
    }

    public static function signUp()
    {

    }
}
