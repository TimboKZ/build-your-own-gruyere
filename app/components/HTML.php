<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG\Components;

use HTMLPurifier;
use HTMLPurifier_Config;

/**
 * Class HTMLPurifier
 * @package BYOG\Components
 */
class HTML
{
    /**
     * @var \HTMLPurifier
     */
    private static $purifier = null;

    private static function getInstance()
    {
        if (self::$purifier === null) {
            $config = HTMLPurifier_Config::createDefault();
            $config->set('HTML.Allowed', 'b,i');
            self::$purifier = new HTMLPurifier($config);
        }
        return self::$purifier;
    }

    public static function purify(string $unsafeHtml): string
    {
        return self::getInstance()->purify($unsafeHtml);
    }
}
