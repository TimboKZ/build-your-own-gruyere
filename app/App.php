<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG;

use BYOG\Components\Helper;

/**
 * Class App
 * @package BYOG
 */
class App
{
    public function start()
    {
        var_dump(Helper::uriComps());
    }
}
