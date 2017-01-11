<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG\Components;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

/**
 * Class DB
 * @package BYOG\Components
 */
class DB
{
    /**
     * @var  \Doctrine\DBAL\Connection
     */
    private static $connection = null;

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public static function getConnection()
    {
        if (self::$connection === null) {
            $config = new Configuration();
            $connectionParams = array(
                'dbname' => DB_NAME,
                'user' => DB_USER,
                'password' => DB_PASS,
                'host' => DB_HOST,
                'driver' => 'pdo_mysql',
            );
            self::$connection = DriverManager::getConnection($connectionParams, $config);
        }
        return self::$connection;
    }
}
