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

    <h4>All registered users</h4>

    <p>Users marked with <code>Admin</code> are the administrators. <code>Locked</code> are the users whose accounts
        were locked due to brute force attacks. <code>Disabled</code> are the users that were disabled by admins, they
        cannot add new snippets or files.</p>

    <ul class="collection">
        <?php
        $overview = UserManager::getOverview();
        function chip(string $string)
        {
            echo '<div class="chip">' . $string . '</div>';
        }

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
                    <strong class="user-title"><?= $user['display_name']; ?></strong>
                    <?php

                    if ($user['is_admin']) {
                        chip('Admin');
                    }
                    if ($user['is_disabled']) {
                        chip('Disabled');
                    }
                    if ($user['is_locked']) {
                        chip('Locked');
                    }
                    ?>
                </h5>
                <p><a href="/snippets/<?= $user['name']; ?>">All snippets by <?= $user['display_name']; ?>
                        (<?= $user['name']; ?>)</a></p>
                <p>
                    <?php
                    if ($user['last_snippet']) :
                    ?>
                    <strong>Latest snippet:</strong>
                <div class="snippet-content"
                     style="border-left-color: <?= $user['colour']; ?>"><?= $user['last_snippet']['content']; ?></div>
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
