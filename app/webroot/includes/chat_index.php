<?php
use classes\character\repository\CharacterRepository;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\UserdataHelper;
use classes\core\repository\RepositoryManager;

/* @var array $userdata */

$contentHeader = "Characters";
$page_title = "Wanton Wicked Chat Interface";

if (!UserdataHelper::IsLoggedIn($userdata)) {
    Response::redirect('/', 'You are not logged in');
}

$characterRepository = RepositoryManager::GetRepository('classes\character\data\Character');
/* @var CharacterRepository $characterRepository */

$id = $userdata['user_id'];
if (UserdataHelper::IsAdmin($userdata)) {
    $id = Request::getValue('u', $id);
}
$characters = $characterRepository->ListCharactersByPlayerId($id);

ob_start();
?>
    <div class="callout-navigation">
        <a href="view_sheet.php?action=create_xp" target="_blank" class="button add">New Character</a>
        <a href="/chat" target="_blank" class="button">Log in OOC</a>
        <a href="/view_sheet.php?action=view_other_xp" target="viewother" class="button view">View Another Character
            Sheet</a>
        <a href="/dieroller.php?action=ooc" target="ooc_dieroller" class="button">OOC Die Roller</a>
        <a href="/dieroller.php?action=custom" target="_blank" class="button">Side Game Die Roller</a>
    </div>

<?php if (count($characters) > 0): ?>
    <table>
        <tr>
            <th>
                Name
            </th>
            <th>
                Sanctioned
            </th>
            <th>
                Updated By
            </th>
            <th>
                Updated On
            </th>
            <th>
                Actions
            </th>
        </tr>
        <?php foreach ($characters as $character): ?>
            <tr>
                <td>
                    <?php echo $character['character_name']; ?>
                </td>
                <td>
                    <?php if ($character['is_sanctioned'] == 'Y'): ?>
                        Sanctioned
                    <?php elseif ($character['is_sanctioned'] == 'N'): ?>
                        Unsanctioned
                    <?php else: ?>
                        New
                    <?Php endif; ?>
                </td>
                <td>
                    <?php echo $character['updated_by_name']; ?>
                </td>
                <td>
                    <?php echo $character['updated_on']; ?>
                </td>
                <td>
                    <a href="/view_sheet.php?action=view_own_xp&character_id=<?php echo $character['id']; ?>"
                       target="_blank" class="button view no-text">View Sheet for <?php echo $character['character_name']; ?>
                    </a>
                    <a href="/wiki/?n=Players.<?php echo $character['character_name']; ?>"
                       target="_blank" class="button view no-text">View Profile for <?php echo $character['character_name']; ?>
                    </a>
                    <a href="/character.php?action=interface&character_id=<?php echo $character['id']; ?>"
                       target="_blank" class="button gear no-text">Interface for <?php echo $character['character_name']; ?>
                    </a>
                    <a href="/chat?character_id=<?php echo $character['id']; ?>" target="_blank"
                        class="button chat no-text">Chat as <?php echo $character['character_name']; ?>
                    </a>
                    <a href="/chat.php?action=delete&character_id=<?php echo $character['id']; ?>"
                       class="delete-link button delete no-text">Delete <?php echo $character['character_name']; ?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <div class="paragraph">
        You have no characters currently.
    </div>
<?php endif; ?>
    <script>
        $(function () {
            $(".delete-link").click(function () {
                return confirm('Are you sure you want to delete ' + $.trim($(this).closest('tr').find('td:first').text()) + '?');
            });
            $(".button.chat").button({
                icons: {
                    primary: 'ui-icon-comment'
                },
                text: false
            })
        })
    </script>
<?php
$page_content = ob_get_clean();
