<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

require_once '../vendor/autoload.php';

require_once '../app/Constants.php';

session_name('ID');
session_start();
$app = new \BYOG\App();
$app->start();
session_write_close();
