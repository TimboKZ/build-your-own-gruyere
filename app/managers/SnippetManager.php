<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG\Managers;

use BYOG\Components\DB;

/**
 * Class SnippetManager
 * @package BYOG\Managers
 */
class SnippetManager
{
    public static function getLastSnippet(string $userId)
    {
        $sql = "SELECT * FROM snippets WHERE user_id = ? ORDER BY time DESC LIMIT 1";
        $stmt = DB::getConnection()->prepare($sql);
        $stmt->bindValue(1, $userId);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getSnippets(string $userId): array
    {
        $conn = DB::getConnection();
        return $conn->fetchAll("SELECT * FROM snippets WHERE user_id = ? ORDER BY time DESC", [$userId]);
    }
}