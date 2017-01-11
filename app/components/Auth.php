<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG\Components;

/**
 * Class Auth
 * @package BYOG\Components
 */
class Auth
{
    private static $authorised = null;

    public static function login(string $username, string $password): bool
    {
        $sql = "SELECT * FROM users WHERE name = ? LIMIT 1";
        $stmt = DB::getConnection()->prepare($sql);
        $stmt->bindValue(1, $username);
        $stmt->execute();
        if ($stmt->rowCount() !== 1) {
            return false;
        }
        $user = $stmt->fetch();
        if (!self::verifySalt($user['id'], $password, $user['pass']) || $user['is_locked']) {
            return false;
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['is_admin'] = $user['is_admin'];
        $_SESSION['token'] = self::token($user['name'] . $user['pass']);
        return true;
    }

    public static function logout()
    {
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
