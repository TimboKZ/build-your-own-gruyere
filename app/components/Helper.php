<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG\Components;

/**
 * Class Helper
 * @package BYOG\Components
 */
class Helper
{
    public static function uriComps()
    {
        $uri = mb_ereg_replace('\\?.*?$', '', $_SERVER['REQUEST_URI']);
        return explode('/', strtolower(trim($uri, '/')));
    }
}