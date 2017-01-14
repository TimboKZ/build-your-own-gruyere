<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

// Setup PSR-4 auto-loading
require_once '../vendor/autoload.php';

// Import constants and config
require_once '../app/Constants.php';

// Reduce session fingerprinting
session_name('ID');

// Start session and app
session_start();
$app = new \BYOG\App();
$app->start();

// Unlock session file
session_write_close();
