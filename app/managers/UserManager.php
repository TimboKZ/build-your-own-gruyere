<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG\Managers;

use BYOG\Components\DB;

/**
 * Class UserManager
 * @package BYOG\Managers
 */
class UserManager
{
    /**
     * @return array
     */
    public static function getOverview()
    {
        $result = [];
        $users = self::getUsers();
        foreach ($users as $user) {
            $user['last_snippet'] = SnippetManager::getLastSnippet($user['id']);
            $result[] = $user;
        }
        return $result;
    }

    /**
     * @return array
     */
    public static function getUsers()
    {
        $sql = "SELECT * FROM users ORDER BY name ASC";
        $stmt = DB::getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}