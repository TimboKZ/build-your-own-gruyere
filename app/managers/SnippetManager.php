<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG\Managers;

use BYOG\Components\DB;
use BYOG\Components\Helper;

/**
 * Class SnippetManager
 * @package BYOG\Managers
 */
class SnippetManager
{
    public static function addSnippet(string $user_id, string $content)
    {
        $conn = DB::getConnection();
        $conn->insert('snippets', [
            'id' => Helper::genId(),
            'user_id' => $user_id,
            'content' => $content,
        ]);
    }

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

    public static function getSnippet(string $snippetId)
    {
        $conn = DB::getConnection();
        return $conn->fetchAssoc("SELECT * FROM snippets WHERE id = ? LIMIT 1", [$snippetId]);
    }

    public static function removeSnippet(string $snippetId)
    {
        $conn = DB::getConnection();
        $conn->delete('snippets', [
            'id' => $snippetId,
        ]);
    }
}