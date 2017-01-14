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
        $uri = self::stripQuery($_SERVER['REQUEST_URI']);
        return explode('/', trim($uri, '/'));
    }

    public static function getHost(): string
    {
        return sprintf(
            "%s://%s/",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME']
        );
    }

    public static function stripQuery(string $url): string
    {
        return mb_ereg_replace('\\?.*?$', '', $url);
    }

    public static function genId(): string
    {
        sleep(1);
        return substr(md5(time()), 0, 8);
    }

    public static function redirect($location)
    {
        header('Location: ' . $location);
        die();
    }

    public static function escapeHTML(string $html): string
    {
        $html = str_replace('&', '&amp;', $html);
        $html = str_replace('"', '&quot;', $html);
        $html = str_replace('\'', '	&apos;', $html);
        $html = str_replace('<', '&lt;', $html);
        $html = str_replace('>', '&gt;', $html);
        return $html;
    }
}