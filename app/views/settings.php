<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

use BYOG\Components\Helper;
use BYOG\Components\CSRFProtection;

$GLOBALS['page_title'] = 'Settings';
include 'includes/header.php';

$user = $GLOBALS['settings_user'];
$ownSettings = $user['id'] === $_SESSION['user_id'];
?>

    <h4><?php echo $ownSettings ? 'Your' : $user['display_name'] . '\'s'; ?> settings</h4>

    <div class="row">
        <form class="col s12" method="post">
            <div class="card-panel white">

                <input type="hidden" name="token"
                       value="<?= CSRFProtection::genToken('settings_' . $_SESSION['user_id']) ?>">

                <div class="row">
                    <div class="input-field col s12">
                        <input id="display_name" type="text" name="display_name" class="validate"
                               value="<?= $user['display_name']; ?>" required
                               autocomplete="off">
                        <label for="display_name">Display name</label>
                        <p class="input-comment">Displayed to other users of the website. Maximum length is 20
                            characters.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="icon_url" type="text" name="icon_url" class="validate"
                               value="<?= $user['icon_url']; ?>">
                        <label for="icon_url">Icon URL</label>
                        <p class="input-comment">Displayed near your name on the main page. Must be uploaded through the
                            <code>Files</code> section, external sources are not allowed. (i.e. URL should begin with
                            <code><?= Helper::getHost(); ?></code>). Only <code>jpg</code>, <code>jpeg</code> and <code>png</code> extensions are
                            allowed. All URL queries will be stripped.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="colour" type="text" name="colour" class="validate"
                               value="<?= $user['colour']; ?>" required>
                        <label for="colour">Profile colour</label>
                        <p class="input-comment">Colour that will be used for some elements of your profile, such as
                            snippet box borders. Must be a HEX colour (as seen in CSS), e.g. <code>#09f</code> or
                            <code>#f0f0f0</code>.
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="website" type="text" name="website" class="validate"
                               value="<?= $user['website']; ?>">
                        <label for="website">Personal homepage</label>
                        <p class="input-comment">Address of your personal homepage, only visible to you.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <textarea id="snippet" name="snippet"
                                  class="materialize-textarea"><?= $user['snippet']; ?></textarea>
                        <label for="snippet">Private snippet</label>
                        <p class="input-comment">Snippet that is only visible to you. All HTML will be displayed as
                            plain text.</p>
                    </div>
                </div>
                <?php
                if (isset($GLOBALS['error'])) :
                    ?>
                    <blockquote class="red-text text-darken-2"><?= $GLOBALS['error']; ?></blockquote>
                    <?php
                endif;
                ?>
                <button class="btn waves-effect waves-light" type="submit" name="action">Save settings
                    <i class="material-icons right">settings</i>
                </button>
            </div>
        </form>
    </div>

<?php
if ($ownSettings) :
    ?>

    <h4>Change password</h4>

    <div class="row">
        <form class="col s12" method="post">
            <div class="card-panel white">
                <input type="hidden" name="token"
                       value="<?= CSRFProtection::genToken('password_' . $_SESSION['user_id']) ?>">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="current_pass" type="password" name="current_pass" class="validate" required
                               autocomplete="off">
                        <label for="current_pass">Current Password</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="new_pass" type="password" name="new_pass" class="validate" required
                               autocomplete="off">
                        <label for="new_pass">New password</label>
                        <p class="input-comment">Must be at least 5 characters long, and contain a lowercase letter, an
                            uppercase letter and a number.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="repeat_pass" type="password" name="repeat_pass" class="validate" required
                               autocomplete="off">
                        <label for="repeat_pass">Repeat new password</label>
                    </div>
                </div>
                <?php
                if (isset($GLOBALS['password_error'])) :
                    ?>
                    <blockquote class="red-text text-darken-2"><?= $GLOBALS['password_error']; ?></blockquote>
                    <?php
                endif;
                ?>
                <button class="btn waves-effect waves-light" type="submit" name="action">Change password
                    <i class="material-icons right">lock_open</i>
                </button>
            </div>
        </form>
    </div>

    <?php
endif;
?>

<?php
include 'includes/footer.php';
