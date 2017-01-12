<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

use Gregwar\Captcha\CaptchaBuilder;

$captcha = new CaptchaBuilder();
$captcha->build();
$_SESSION['registration_captcha'] = $captcha->getPhrase();

$GLOBALS['page_title'] = 'Sign Up';
include 'includes/header.php';
?>

    <div class="row">
        <form class="col offset-l4 l4 offset-m3 m6 s12" method="post">
            <input style="display:none" type="text" name="username"/>
            <input style="display:none" type="password" name="password"/>
            <div class="card-panel white">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="username" type="text" name="username" class="validate"
                               value="<?= isset($_POST['username']) ? $_POST['username'] : ''; ?>" required
                               autocomplete="off">
                        <label for="username">Username</label>
                        <p class="input-comment">Used to login. Only lowercase and uppercase alphanumeric characters, 3
                            to 12 characters long.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="username" type="text" name="display_name" class="validate"
                               value="<?= isset($_POST['display_name']) ? $_POST['display_name'] : ''; ?>" required
                               autocomplete="off">
                        <label for="display_name">Display name</label>
                        <p class="input-comment">Displayed to other users of the website. Maximum length is 20
                            characters.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="password" type="password" name="password" class="validate" required
                               autocomplete="off">
                        <label for="password">Password</label>
                        <p class="input-comment">Must be at least 5 characters long, and contain a lowercase letter, an
                            uppercase letter and a number.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="captcha" type="text" name="captcha" class="validate" required autocomplete="off">
                        <label for="captcha">Captcha</label>
                        <img src="<?= $captcha->inline(); ?>">
                    </div>
                </div>
                <?php
                if (isset($GLOBALS['error'])) :
                    ?>
                    <blockquote class="red-text text-darken-2"><?= $GLOBALS['error']; ?></blockquote>
                    <?php
                endif;
                ?>
                <button class="btn waves-effect waves-light" type="submit" name="action">Sign Up
                    <i class="material-icons right">send</i>
                </button>
            </div>
        </form>
    </div>


<?php
include 'includes/footer.php';
