<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace BYOG\Controllers;

use BYOG\Components\Auth;
use BYOG\Components\CSRFProtection;
use BYOG\Components\Helper;
use BYOG\Components\View;
use BYOG\Managers\UserManager;
use finfo;

/**
 * Class FileController
 * @package BYOG\Controllers
 */
class FileController
{
    public static function file()
    {
        if (Auth::isGuest()) {
            Helper::redirect('/login');
        }
        $user = UserManager::getUserById($_SESSION['user_id']);
        if ($user) {
            self::uploadFile();
            $GLOBALS['file_user'] = $user;
            View::render('files');
            return;
        } else {
            View::error(500);
        }
    }

    public static function uploadFile()
    {
        if (isset($_FILES['file'])
            && isset($_POST['token'])
        ) {
            echo 123;
            $token = $_POST['token'];
            if (!CSRFProtection::checkToken('add_file_' . $_SESSION['user_id'], $token)) {
                $GLOBALS['error'] = 'Form token is invalid! Please try again.';
                return;
            }
            $file = $_FILES['file'];
            if (!isset($file['error']) ||
                is_array($file['error'])
            ) {
                $GLOBALS['error'] = 'Invalid parameters.';
            }
            switch ($file['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $GLOBALS['error'] = 'No file was uploaded.';
                    return;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $GLOBALS['error'] = 'File is too big!';
                    return;
                default:
                    $GLOBALS['error'] = 'An unknown error has occurred.';
                    return;
            }

            $info = new finfo(FILEINFO_MIME_TYPE);
            $ext = array_search($info->file($file['tmp_name']), [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'html' => 'text/html',
            ], true);
            if (false === $ext) {
                $GLOBALS['error'] = 'File format is not supported.';
                return;
            }

            if (!move_uploaded_file(
                $file['tmp_name'],
                UPLOAD_DIR . '/' . $_SESSION['user_name']
                . '/' . mb_ereg_replace('[^A-Za-z0-9._\-]', '-', $file['name'])
            )
            ) {
                $GLOBALS['error'] = 'Could not move uploaded file.';
                return;
            }

            Helper::redirect('/files');
        }
    }
}
