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
            $('a.file-delete-button').click(function (event) {
                var that = $(this);
                event.preventDefault();
                $.ajax({
                    url: '/api/files/' + that.data('id'),
                    type: 'DELETE',
                    success: function () {
                        $('#file-' + that.data('id').replace('.', '-')).slideUp(500);
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

$GLOBALS['page_title'] = ['Files', 'Your files'];
include 'includes/header.php';

?>

    <h4>Add a file</h4>

    <div class="row">
        <form class="col s12" method="post" enctype="multipart/form-data">
            <div class="card-panel white">
                <input type="hidden" name="token"
                       value="<?= CSRFProtection::genToken('add_file_' . $_SESSION['user_id']) ?>">
                <div class="row">
                    <div class="file-field input-field col s12">
                        <div class="btn">
                            <span>File</span>
                            <input type="file" name="file" required>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" placeholder="Upload your file">
                        </div>
                        <p class="input-comment">Upload a file with extension <code>jpg</code>, <code>jpeg</code>,
                            <code>png</code> or <code>html</code>. Maximum file size is 2mb. Everything that is not an
                            alphanumeric character, period, dash or underscore will be replaced with a dash
                            (<code>-</code>).</p>
                    </div>
                </div>
                <?php
                if (isset($GLOBALS['error'])) :
                    ?>
                    <blockquote class="red-text text-darken-2"><?= $GLOBALS['error']; ?></blockquote>
                    <?php
                endif;
                ?>
                <button class="btn waves-effect waves-light" type="submit" name="action">Upload file
                    <i class="material-icons right">send</i>
                </button>
            </div>
        </form>
    </div>

    <h4>Your files</h4>

<?php
$filePath = UPLOAD_DIR . '/' . $GLOBALS['file_user']['name'] . '/';
if (!file_exists($filePath)) {
    mkdir($filePath);
}
$files = array_diff(scandir($filePath), ['..', '.']);
if (count($files) === 0) :
    ?>
    <h6 class="center-align">No files to display.</h6>
    <?php
else:
    ?>
    <ul class="collection">
        <?php
        foreach ($files as $file) {
            $id = mb_ereg_replace('[.]', '-', $file);
            ?>
            <li id="file-<?= $id; ?>" class="collection-item">
                <div class="right snippet-button">
                    <a href="#" data-id="<?= $file ?>"
                       class="file-delete-button waves-effect red-text text-darken-3 waves-light btn btn-flat"><i
                                class="material-icons left">delete</i>Delete</a>
                </div>
                <span class="snippet-title">
                    File named <code><?= $file ?></code>:
                </span>
                <div class="snippet-content"
                     style="border-left-color: <?= $GLOBALS['file_user']['colour']; ?>">
                    <?php
                    echo sprintf(
                        "%s://%s/",
                        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
                        $_SERVER['SERVER_NAME']
                    );
                    ?>uploads/<?= $GLOBALS['file_user']['name'] . '/' . $file; ?>
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
