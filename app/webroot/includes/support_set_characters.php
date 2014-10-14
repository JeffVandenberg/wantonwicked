<?php
use classes\character\data\Character;
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\repository\RepositoryManager;
use classes\support\data\Supporter;
use classes\support\repository\SupporterRepository;

/* @var array $userdata */
$page_title = $contentHeader = 'Support Status';

$characterRepository = RepositoryManager::GetRepository('classes\character\data\Character');
$supporterRepository = RepositoryManager::GetRepository('classes\support\data\Supporter');
/* @var SupporterRepository $supporterRepository */

if(Request::IsPost())
{
    if(Request::GetValue('action') == 'Cancel') {
        Response::Redirect('support.php');
    }
    if(Request::GetValue('action') == 'Update') {
        $characterIds = Request::GetValue('character_id');
        if(is_array($characterIds)) {
            $supporterRepository->UpdateCharactersForSupporter($userdata['user_id'], Request::GetValue('character_id'));
            SessionHelper::SetFlashMessage('Your characters have been set.');
            Response::Redirect('support.php');
        }
        else {
            SessionHelper::SetFlashMessage('You didn\'t select any characters.');
        }
    }
}

$supporter = $supporterRepository->FindByUserId($userdata['user_id']);
/* @var Supporter $supporter */

if($supporter->Id == 0) {
    SessionHelper::SetFlashMessage('You are not a supporter');
    Response::Redirect('support.php');
}
if($supporter->ExpiresOn < date('Y-m-d H:i:s')) {
    SessionHelper::SetFlashMessage('Your support has expired');
    Response::Redirect('support.php');
}
$characters = $characterRepository->ListByUserIdAndIsSanctionedAndIsDeleted($userdata['user_id'], 'Y', 'N');
/* @var Character[] $characters */

$characterList = array();
foreach($characters as $character)
{
    $characterList[$character->Id] = $character->CharacterName;
}

$selectedCharacters = $supporterRepository->ListSelectedCharactersForSupporter($supporter->Id);
$selectedList = array();
foreach($selectedCharacters as $item)
{
    $selectedList[] = $item->Id;
}
ob_start();
?>

<form method="post">
    <table>
        <tr>
            <td>
                You may select up to <?php echo $supporter->NumberOfCharacters; ?> character(s) to be awarded Bonus XP.<br />
                You currently have <?php echo count($selectedCharacters); ?> characters(s) selected.<br />
                Your support will run out on <?php echo date('m/d/Y', strtotime($supporter->ExpiresOn)); ?>.
            </td>
        </tr>
        <tr>
            <td>
                <?php echo FormHelper::Multiselect($characterList, 'character_id[]', $selectedList); ?>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">
                <?php echo FormHelper::Hidden('num_of_characters', $supporter->NumberOfCharacters); ?>
                <?php echo FormHelper::Button('action', 'Update'); ?>
                <?php echo FormHelper::Button('action', 'Cancel'); ?>
            </td>
        </tr>
    </table>
</form>
<script>
    $(function() {
        $("form").submit(function(e) {
            var charCount = $("#character-id").find(":selected").length;
            if(charCount > parseInt($("#num-of-characters").val())) {
                alert('You have too many characters selected!');
                e.preventDefault();
            }
        });
    })
</script>
<?php
$page_content = ob_get_clean();