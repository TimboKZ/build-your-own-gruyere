<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG\Components;

/**
 * Class View
 * @package BYOG\Components
 */
class View
{
    public static function render(string $view)
    {
        include __DIR__ . '/../views/' . $view . '.php';
    }
    public static function error(int $errorCode)
    {
        http_response_code($errorCode);
        include __DIR__ . '/../views/' . $errorCode . '.php';
        die();
    }
}
