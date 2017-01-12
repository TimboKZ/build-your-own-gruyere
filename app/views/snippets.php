<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

use BYOG\Managers\SnippetManager;

$ownSnippets = $GLOBALS['snippet_user']['id'] === $_SESSION['user_id'];
if ($ownSnippets) {
    $GLOBALS['page_title'] = ['Snippets', 'Your snippets'];
} else {
    $GLOBALS['page_title'] = ['Snippets', $GLOBALS['snippet_user']['name'] . '\'s snippets'];
}
include 'includes/header.php';

if($ownSnippets):
?>

    <h4>Add a snippet:</h4>
    <h4>Snippets:</h4>

<?php
endif;
$snippets = SnippetManager::getSnippets($GLOBALS['snippet_user']['id']);
if (count($snippets) === 0) :
    ?>
    <h5 class="center-align">No snippets to display.</h5>
    <?php
else:
    ?>
    <ul class="collection">
        <?php
        foreach ($snippets as $snippet) {
            ?>
            <li class="collection-item">
                <span class="snippet-title"> Snippet added on <?= $snippet['time'] ?>:</span>
                <div class="snippet-content"
                     style="border-left-color: <?= $GLOBALS['snippet_user']['colour']; ?>"><?= $snippet['content']; ?></div>
            </li>
            <?php
        }
        ?>
    </ul>
    <?php
endif;
?>

<?php
include 'includes/footer.php';
