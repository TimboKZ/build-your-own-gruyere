<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG\Managers;

use BYOG\Components\Auth;
use BYOG\Components\DB;
use BYOG\Components\Helper;

/**
 * Class UserManager
 * @package BYOG\Managers
 */
class UserManager
{
    public static function createUser(string $username, string $displayName, string $password)
    {
        $id = Helper::genId();
        $conn = DB::getConnection();
        $conn->insert('users', [
            'id' => $id,
            'name' => $username,
            'display_name' => Helper::escapeHTML($displayName),
            'website' => Helper::getHost() . 'snippets/' . $username,
            'pass' => Auth::getSalt($id, $password),
        ]);
    }

    public static function deleteUser(string $userId)
    {
        $conn = DB::getConnection();
        $conn->delete('snippets', [
            'user_id' => $userId,
        ]);
        $conn->delete('users', [
            'id' => $userId,
        ]);
        $user = self::getUserById($userId);
        $directory = UPLOAD_DIR . '/' . $user['name'];
        foreach (glob("{$directory}/*") as $file) {
            unlink($file);
        }
        rmdir($directory);
    }

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