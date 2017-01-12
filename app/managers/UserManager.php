<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG\Managers;

use BYOG\Components\Auth;
use BYOG\Components\DB;

/**
 * Class UserManager
 * @package BYOG\Managers
 */
class UserManager
{
    public static function getUserById(string $id)
    {
        $conn = DB::getConnection();
        return $conn->fetchAssoc("SELECT * FROM users WHERE id = ? LIMIT 1", [$id]);
    }

    public static function getUserByName(string $username)
    {
        if (!preg_match(Auth::$usernameRegex, $username)) {
            return null;
        }
        $conn = DB::getConnection();
        return $conn->fetchAssoc("SELECT * FROM users WHERE name = ? LIMIT 1", [$username]);
    }

    public static function getOverview(): array
    {
        $result = [];
        $users = self::getUsers();
        foreach ($users as $user) {
            $user['last_snippet'] = SnippetManager::getLastSnippet($user['id']);
            $result[] = $user;
        }
        return $result;
    }

    public static function getUsers(): array
    {
        $sql = "SELECT * FROM users ORDER BY name ASC";
        $stmt = DB::getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function updateUser(string $userId, array $updates)
    {
        $conn = DB::getConnection();
        $conn->update('users', $updates, [
            'id' => $userId,
        ]);
    }
}