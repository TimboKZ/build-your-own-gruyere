<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2017
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

use BYOG\Managers\SnippetManager;
use BYOG\Components\CSRFProtection;

ob_start();
?>
    <script>
        $(document).ready(function () {
            $('a.snippet-delete-button').click(function (event) {
                var that = $(this);
                event.preventDefault();
                $.ajax({
                    url: '/api/snippets/' + that.data('id'),
                    type: 'DELETE',
                    success: function () {
                        $('#snippet-' + that.data('id')).slideUp(500);
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

$ownSnippets = $GLOBALS['snippet_user']['id'] === $_SESSION['user_id'];
if ($ownSnippets) {
    $GLOBALS['page_title'] = ['Snippets', 'Your snippets'];
} else {
    $GLOBALS['page_title'] = ['Snippets', $GLOBALS['snippet_user']['display_name'] . '\'s snippets'];
}
include 'includes/header.php';

if ($ownSnippets) :
    ?>

    <h4>Add a snippet</h4>

    <div class="row">
        <form class="col s12" method="post">
            <div class="card-panel white">
                <input type="hidden" name="token"
                       value="<?= CSRFProtection::genToken('add_snippet_' . $_SESSION['user_id']) ?>">
                <div class="row">
                    <div class="input-field col s12">
                        <?php
                        $content = isset($_POST['content']) ? $_POST['content'] : '';
                        ?>
                        <textarea id="content" name="content" class="materialize-textarea"
                                  required><?= $content; ?></textarea>
                        <label for="content">Snippet content</label>
                        <p class="input-comment">You can use <code>&lt;b></code> and <code>&lt;i></code> tags, all other
                            HTML will be stripped.</p>
                    </div>
                </div>
                <?php
                if (isset($GLOBALS['error'])) :
                    ?>
                    <blockquote class="red-text text-darken-2"><?= $GLOBALS['error']; ?></blockquote>
                    <?php
                endif;
                ?>
                <button class="btn waves-effect waves-light" type="submit" name="action">Add snippet
                    <i class="material-icons right">send</i>
                </button>
            </div>
        </form>
    </div>

    <h4>Your snippets</h4>

    <?php
endif;
$snippets = SnippetManager::getSnippets($GLOBALS['snippet_user']['id']);
if (count($snippets) === 0) :
    ?>
    <h6 class="center-align">No snippets to display.</h6>
    <?php
else:
    ?>
    <ul class="collection">
        <?php
        foreach ($snippets as $snippet) {
            ?>
            <li id="snippet-<?= $snippet['id']; ?>" class="collection-item">
                <?php
                if ($ownSnippets) :
                    ?>
                    <div class="right snippet-button">
                        <a href="#" data-id="<?= $snippet['id']; ?>"
                           class="snippet-delete-button waves-effect red-text text-darken-3 waves-light btn btn-flat"><i
                                    class="material-icons left">delete</i>Delete</a>
                    </div>
                    <?php
                endif;
                ?>
                <span class="snippet-title">
                    Snippet added on <?= $snippet['time'] ?>:
                </span>
                <div class="snippet-content"
                     style="border-left-color: <?= $GLOBALS['snippet_user']['colour']; ?>">
                    <?= $snippet['content']; ?>
                </div>
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
