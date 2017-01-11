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
    /**
     * @return bool
     */
    public static function isGuest()
    {
        return true;
    }
}