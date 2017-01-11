<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG;

use BYOG\Components\DB;
use BYOG\Components\Helper;
use BYOG\Components\View;

/**
 * Class App
 * @package BYOG
 */
class App
{
    public function start()
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = DB::getConnection()->prepare($sql);
        $stmt->bindValue(1, '123s');
        $stmt->execute();

        View::render('404');

    }
}
