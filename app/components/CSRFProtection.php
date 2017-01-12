<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG\Components;

use EasyCSRF\EasyCSRF;
use EasyCSRF\NativeSessionProvider;

/**
 * Class CSRFProtection
 * @package BYOG\Components
 */
class CSRFProtection
{
    /**
     * @var \EasyCSRF\EasyCSRF
     */
    private static $easyCSRF = null;

    private static function getInstance()
    {
        if (self::$easyCSRF === null) {
            $sessionProvider = new NativeSessionProvider();
            self::$easyCSRF = new EasyCSRF($sessionProvider);
        }
        return self::$easyCSRF;
    }

    public static function genToken(string $name): string
    {
        return self::getInstance()->generate($name);
    }

    public static function checkToken(string $name, string $token, bool $multiple = false): bool
    {
        try {
            self::getInstance()->check($name, $token, 3600, $multiple);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
