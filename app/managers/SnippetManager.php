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
    public static function getLastSnippet(string $user_id)
    {
        $sql = "SELECT * FROM snippets WHERE user_id = ? ORDER BY time DESC LIMIT 1";
        $stmt = DB::getConnection()->prepare($sql);
        $stmt->bindValue(1, $user_id);
        $stmt->execute();
        return $stmt->fetch();
    }
}