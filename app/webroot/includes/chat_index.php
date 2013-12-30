<?php
use classes\character\repository\CharacterRepository;
use classes\core\repository\RepositoryManager;

/* @var array $userdata */

$contentHeader = "Chat Interface";
$page_title = "Wanton Wicked Chat Interface";
$user_id = (isset($_GET['user_id'])) ? $_GET['user_id'] : $userdata['user_id'];

$characterRepository = RepositoryManager::GetRepository('classes\character\data\Character');
/* @var CharacterRepository $characterRepository */

$characters = $characterRepository->ListCharactersByPlayerId($userdata['user_id']);

ob_start();
?>

    <h2>
        Characters
    </h2>
    <a href="view_sheet.php?action=create_xp" target="_blank" class="button">New Character</a>
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
                <?php if($character['is_sanctioned'] == 'Y'): ?>
                    Sanctioned
                <?php elseif($character['is_sanctioned'] == 'N'): ?>
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
                <a href="/view_sheet.php?action=view_own_xp&character_id=<?php echo $character['character_id']; ?>" target="_blank">
                    <img src="/img/gp_view.png" alt="View <?php echo $character['character_name']; ?>" title="View <?php echo $character['character_name']; ?>"/>
                </a>
                <a href="/character.php?action=interface&character_id=<?php echo $character['character_id']; ?>" target="_blank">
                    <img src="/img/gp_tools.png" alt="Interface for <?php echo $character['character_name']; ?>" title="Interface for <?php echo $character['character_name']; ?>"/>
                </a>
                <a href="/chat?character_id=<?php echo $character['character_id']; ?>" target="_blank">
                    <img src="/img/gp_chat.png" alt="Chat as <?php echo $character['character_name']; ?>" title="Chat as <?php echo $character['character_name']; ?>"/>
                </a>
                <a href="/chat.php?action=delete&character_id=<?php echo $character['character_id']; ?>" class="delete-link">
                    <img src="/img/gp_delete.png" alt="Delete <?php echo $character['character_name']; ?>" title="Delete <?php echo $character['character_name']; ?>"/>
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
    <h2>
        Other Tools
    </h2>
    <a href="/chat" target="_blank" class="button">Log in OOC</a>
    <a href="/view_sheet.php?action=view_other_xp" target="viewother" class="button">View Another Character Sheet</a>
    <a href="/dieroller.php?action=ooc" target="ooc_dieroller" class="button">OOC Die Roller</a>
    <a href="/dieroller.php?action=custom" target="_blank" class="button">Side Game Die Roller</a>
<script>
    $(function() {
        $('.button').button();
        $(".delete-link").click(function() {
           return confirm('Are you sure you want to delete ' + $.trim($(this).closest('tr').find('td:first').text()) + '?');
        });
    })
</script>
<?php
$page_content = ob_get_clean();