<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

$GLOBALS['page_title'] = 'Login';
include 'includes/header.php';
?>

    <div class="row">
        <form class="col offset-l4 l4 offset-m3 m6 s12" method="post">
            <div class="card-panel white">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="username" type="text" name="username" class="validate" required>
                        <label for="username">Username</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="password" type="password" name="password" class="validate" required>
                        <label for="password">Password</label>
                    </div>
                </div>
                <?php
                if (isset($GLOBALS['error'])) :
                    ?>
                    <blockquote class="red-text text-darken-2"><?= $GLOBALS['error']; ?></blockquote>
                    <?php
                endif;
                ?>
                <button class="btn waves-effect waves-light" type="submit" name="action">Login
                    <i class="material-icons right">input</i>
                </button>
            </div>
        </form>
    </div>


<?php
include 'includes/footer.php';
