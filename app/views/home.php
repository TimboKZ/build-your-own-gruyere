<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

use BYOG\Managers\UserManager;

$GLOBALS['page_title'] = 'Home';
include 'includes/header.php';
?>

    <h3>All registered users:</h3>

    <ul class="collection">
        <?php
        $overview = UserManager::getOverview();
        foreach ($overview as $user) {
            ?>
            <li class="collection-item avatar">
                <?php
                if ($user['icon_url']) :
                    ?>
                    <img src="<?= $user['icon_url']; ?>" alt="" class="circle">
                    <?php
                endif;
                ?>
                <h5>
                    <strong><?= $user['name']; ?></strong>
                    <?php
                    if ($user['is_admin']) {
                        echo '(Admin)';
                    }
                    ?>
                </h5>
                <p><a href="<?= $user['website']; ?>">User's homepage</a></p>
                <p>
                    <?php
                    if ($user['last_snippet']) :
                    ?>
                    <strong>Latest snippet:</strong>
                <div><?= $user['last_snippet']['content']; ?></div>
            <?php
            else :
                ?>
                No snippets to display.
                <?php
            endif;
            ?>
                </p>
            </li>
            <?php
        }
        ?>
    </ul>

<?php
include 'includes/footer.php';
