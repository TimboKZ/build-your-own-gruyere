<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

use BYOG\Managers\UserManager;
use BYOG\Components\Auth;

if (Auth::isAdmin()) {
    ob_start();
    ?>
    <script>
        $(document).ready(function () {
            $('a.admin-button').click(function (event) {
                event.preventDefault()
                var that = $(this);
                $.ajax({
                    url: '/api/admin/' + that.data('id'),
                    type: 'POST',
                    data: {
                        action: that.data('action'),
                    },
                    success: function () {
                        location.reload();
                    },
                    error: function (jqxhr) {
                        console.log(jqxhr.responseText);
                    }
                });
            });
        });
    </script>
    <?php
    $GLOBALS['scripts'] = ob_get_clean();
}

$GLOBALS['page_title'] = 'Home';
include 'includes/header.php';

if (!Auth::isGuest()) :
    $profile = UserManager::getUserById($_SESSION['user_id']);
    ?>
    <h4>Your profile</h4>
    <div class="row">
        <form class="col s12" method="post">
            <div class="card-panel white">
                <?php
                if (!empty($profile['icon_url'])) :
                    ?>
                    <img src="<?= !empty($profile['icon_url']) ? $profile['icon_url'] : '/assets/profile.jpg'; ?>"
                         alt=""
                         style="max-width: 100px;margin-right: 20px"
                         class="circle left">
                    <?php
                endif;
                ?>
                <h5>
                    <?= $profile['display_name']; ?>
                    <span class="grey-text">@<?= $profile['name']; ?></span>
                </h5>
                <?php
                if (!empty($profile['website'])) :
                    ?>
                    <p>Your personal homepage: <a href="<?= $profile['website']; ?>"><?= $profile['website']; ?></a></p>
                    <?php
                else:
                    ?>
                    <p>No personal homepage to display.</p>
                    <?php
                endif;
                ?>
                <?php
                if (!empty($profile['snippet'])) :
                    ?>
                    <p>Your private snippet:</p>
                    <div class="snippet-content"
                         style="border-left-color: <?= $user['colour']; ?>;margin-top: 20px">
                        <?= $profile['snippet']; ?></div>
                    <?php
                else:
                    ?>
                    <p>No private snippet to display.</p>
                    <?php
                endif;
                ?>
            </div>
        </form>
    </div>


    <?php
endif;
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
                <img src="<?= !empty($user['icon_url']) ? $user['icon_url'] : '/assets/profile.jpg'; ?>" alt=""
                     class="circle">
                <?php
                if (Auth::isAdmin() && $user['id'] !== $_SESSION['user_id']) :
                    $adminButtons = [
                        ['delete', 'Delete', 'delete', 'red darken-2'],
                        ['delete', $user['is_locked'] ? 'Unlock' : 'Lock', 'lock', 'yellow darken-4'],
                        ['delete', $user['is_disabled'] ? 'Enable' : 'Disable', 'disable', 'yellow darken-4'],
                        ['delete', $user['is_admin'] ? 'User' : 'Admin', 'admin', ''],
                    ];
                    foreach ($adminButtons as $adminButton):
                        ?>
                        <div class="right">
                            <a href="#" data-id="<?= $user['id']; ?>" data-action="<?= $adminButton[2]; ?>"
                               class="admin-button waves-effect <?= $adminButton[3]; ?> waves-light btn"><?= $adminButton[1]; ?>
                            </a>
                        </div>
                        <?php
                    endforeach;
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
                <?php
                if (AUth::isAdmin()) :
                    ?>
                    <p>
                        Admin:
                        <a href="/settings/<?= $user['name']; ?>">Edit <?= $user['display_name']; ?>'s settings</a>
                    </p>
                    <?php
                endif;
                ?>
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
