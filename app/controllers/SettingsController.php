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
use BYOG\Components\View;
use BYOG\Managers\UserManager;

/**
 * Class SettingsController
 * @package BYOG\Controllers
 */
class SettingsController
{
    public static function settings()
    {
        if (Auth::isGuest()) {
            Helper::redirect('/login');
        }

        self::changeSettings();
        self::changePassword();

        View::render('settings');
    }

    public static function changeSettings()
    {
        if (isset($_POST['display_name'])
            && isset($_POST['token'])
        ) {
            $token = $_POST['token'];
            if (!CSRFProtection::checkToken('settings_' . $_SESSION['user_id'], $token)) {
                $GLOBALS['error'] = 'Form token is invalid! Please try again.';
                return;
            }
            $user = UserManager::getUserById($_SESSION['user_id']);
            if (!$user) {
                View::error(500);
            }
            $displayName = $_POST['display_name'];
            $iconUrl = isset($_POST['icon_url']) ? $_POST['icon_url'] : $user['icon_url'];
            $colour = isset($_POST['colour']) ? $_POST['colour'] : $user['colour'];
            $website = isset($_POST['website']) ? $_POST['website'] : $user['website'];
            $snippet = isset($_POST['snippet']) ? $_POST['snippet'] : $user['snippet'];
            if (empty($displayName)) {
                $GLOBALS['error'] = 'Display name cannot be empty.';
                return;
            }
            if (strlen($displayName) > 20) {
                $GLOBALS['error'] = 'Display name is too long.';
                return;
            }
            $siteAddr = sprintf(
                "%s://%s/",
                isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
                $_SERVER['SERVER_NAME']
            );
            if (!empty($iconUrl) && strpos($iconUrl, $siteAddr) !== 0) {
                $GLOBALS['error'] = 'Icon URL must begin with <code>'.$siteAddr.'</code>!';
                return;
            }
            $iconUrl = Helper::stripQuery($iconUrl);
            if (!preg_match('/\\.(jpeg|jpg|png)$/', $iconUrl)) {
                $GLOBALS['error'] = 'Only jpeg, jpg and png extension are allowed for icon URL!';
                return;
            }
            if (!preg_match('/^(#[A-Fa-f0-9]{3}|#[A-Fa-f0-9]{6})$/', $colour)) {
                $GLOBALS['error'] = 'Invalid colour specified!';
                return;
            }
            UserManager::updateUser($user['id'], [
                'display_name' => Helper::escapeHTML($displayName),
                'icon_url' => Helper::escapeHTML($iconUrl),
                'colour' => $colour,
                'website' => Helper::escapeHTML($website),
                'snippet' => Helper::escapeHTML($snippet),
            ]);
            Helper::redirect('/settings');
        }
    }

    public static function changePassword()
    {
        if (isset($_POST['current_pass'])
            && isset($_POST['new_pass'])
            && isset($_POST['repeat_pass'])
            && isset($_POST['token'])
        ) {
            $current = $_POST['current_pass'];
            $new = $_POST['new_pass'];
            $repeat = $_POST['repeat_pass'];
            $token = $_POST['token'];
            if (!CSRFProtection::checkToken('password_' . $_SESSION['user_id'], $token)) {
                $GLOBALS['password_error'] = 'Form token is invalid! Please try again.';
                return;
            }
            if ($new !== $repeat) {
                $GLOBALS['password_error'] = 'Specified passwords do not match up!';
                return;
            }
            $user = UserManager::getUserById($_SESSION['user_id']);
            if (!$user) {
                View::error(500);
            }
            if (!Auth::verifySalt($user['id'], $current, $user['pass'])) {
                $GLOBALS['password_error'] = 'Incorrect current password.';
                return;
            }
            if (strlen($new) < 5) {
                $GLOBALS['password_error'] = 'Password is too short.';
                return;
            }
            if (strtolower($new) === $new || !preg_match('/\d/', $new)) {
                $GLOBALS['password_error'] =
                    'Password must contain at least one uppercase, one lowercase character and a number.';
                return;
            }
            Auth::changePass($user['id'], $new);
            Helper::redirect('/login');
        }
    }
}
