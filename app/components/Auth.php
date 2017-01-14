<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG\Components;

use BYOG\Managers\UserManager;

/**
 * Class Auth
 * @package BYOG\Components
 */
class Auth
{
    private static $authorised = null;

    public static $usernameRegex = '/^[A-Za-z0-9]+$/';

    public static function login(string $username, string $password): bool
    {
        $conn = DB::getConnection();
        $user = UserManager::getUserByName($username);
        if (!$user) {
            return false;
        }
        if (!self::verifySalt($user['id'], $password, $user['pass'])) {
            $fails = $user['failed_attempts'] + 1;
            $lock = $fails >= 3 ? 1 : 0;
            $conn->update('users', [
                'failed_attempts' => $fails,
                'is_locked' => $lock,
            ], [
                'id' => $user['id'],
            ]);
            return false;
        }
        if ($user['is_locked']) {
            return false;
        } else {
            $conn->update('users', [
                'failed_attempts' => 0,
            ], [
                'id' => $user['id'],
            ]);
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['is_admin'] = $user['is_admin'];
        $_SESSION['token'] = self::token($user['name'] . $user['pass']);
        return true;
    }

    public static function signUp(string $username, string $displayName, string $password): string
    {
        if (strlen($username) < 3 || strlen($username) > 12) {
            return 'Username length is incorrect.';
        }
        if (!preg_match(self::$usernameRegex, $username)) {
            return 'Username contains invalid symbols.';
        }
        $conn = DB::getConnection();
        $users = $conn->fetchAll("SELECT * FROM users WHERE name = ?", [$username]);
        if (count($users) > 0) {
            return 'This username is already taken.';
        }
        if (empty($displayName)) {
            return 'Display name cannot be empty.';
        }
        if (strlen($displayName) > 20) {
            return 'Display name is too long.';
        }
        if (strlen($password) < 5) {
            return 'Password is too short.';
        }
        if (strtolower($password) === $password || !preg_match('/\d/', $password)) {
            return 'Password must contain at least one uppercase, one lowercase character and a number.';
        }
        $id = Helper::genId();
        $conn->insert('users', [
            'id' => $id,
            'name' => $username,
            'display_name' => Helper::escapeHTML($displayName),
            'pass' => Auth::getSalt($id, $password),
        ]);
        return '';
    }

    public static function changePass(string $userId, string $plainPass)
    {
        $conn = DB::getConnection();
        $conn->update('users', [
            'pass' => self::getSalt($userId, $plainPass),
        ], [
            'id' => $userId,
        ]);
    }

    public static function logout()
    {
        session_unset();
        session_destroy();
    }

    public static function getSalt(string $user_id, string $password): string
    {
        return password_hash('SaltyServant.' . $user_id . $password, PASSWORD_DEFAULT);
    }

    public static function verifySalt(string $user_id, string $password, string $hash)
    {
        return password_verify('SaltyServant.' . $user_id . $password, $hash);
    }

    public static function token(string $string): string
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $agent = $_SERVER['HTTP_USER_AGENT'];
        return md5($ip . $agent . $string);
    }

    public static function authorise(): bool
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['token'])) {
            return false;
        } else {
            $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
            $stmt = DB::getConnection()->prepare($sql);
            $stmt->bindValue(1, $_SESSION['user_id']);
            $stmt->execute();
            if ($stmt->rowCount() !== 1) {
                self::logout();
                return false;
            }
            $user = $stmt->fetch();
            if (self::token($user['name'] . $user['pass']) !== $_SESSION['token']) {
                self::logout();
                return false;
            }
            if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {
                self::logout();
                return false;
            }
            $_SESSION['last_activity'] = time();
            return true;
        }
    }

    public static function isAdmin(): bool
    {
        return self::isGuest() && isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
    }

    public static function isGuest(): bool
    {
        if (self::$authorised === null) {
            self::$authorised = self::authorise();
        }
        return !self::$authorised;
    }
}
