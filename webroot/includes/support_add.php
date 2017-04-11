<?php
/* @var array $userdata */
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\repository\RepositoryManager;
use classes\support\data\Supporter;
use classes\support\SupportManager;

$page_title = $contentHeader = 'Add Supporter';

if(Request::isPost())
{
    $userId = Request::getValue('user_id', 0);

    if($userId !== 0) {
        $supporterRepository = RepositoryManager::GetRepository('classes\support\data\Supporter');
        $supporter = $supporterRepository->FindByUserId($userId);
        /* @var Supporter $supporter */
        $supporter->UserId = $userId;
        $supporter->AmountPaid = Request::getValue('amount_paid', 0);
        $supporter->ExpiresOn = date('Y-m-d', strtotime(Request::getValue('expires_on')));
        $supporter->NumberOfCharacters = Request::getValue('number_of_characters', 0);
        $supporter->UpdatedById = $userdata['user_id'];
        $supporter->UpdatedOn = date('Y-m-d H:i:s');

        if($supporterRepository->save($supporter)) {
            $supporterManager = new SupportManager();
            $supporterManager->GrantSupporterStatus($supporter->UserId);
            $message = <<<EOQ
Your account has been added as a Wicked Supporter! Thanks!

For your records:
Amount Paid: $supporter->AmountPaid
Number of Characters: $supporter->NumberOfCharacters
Expires On: $supporter->ExpiresOn

You can visit: http://wantonwicked.gamingsandbox.com/support.php?action=setCharacters to select which characters will get the monthly XP.

You will receive a reminder email two weeks before your support expires.


Thank you for your support of GamingSandbox!
EOQ;

            mail($supporter->User->UserEmail, 'Wicked Support', $message, 'from: support@gamingsandbox.com');
            SessionHelper::SetFlashMessage('Updated Supporter');
            Response::redirect('support.php?action=manage');
        }
        else {
            SessionHelper::SetFlashMessage('Failed to update supporter');
        }
    }
}

ob_start()
?>

<form method="post">
    <table>
        <tr>
            <td>
                <?php echo FormHelper::Hidden('user_id', ''); ?>
                <?php echo FormHelper::Text('username', '', array('label' => true)); ?>
            </td>
            <td>
                <?php echo FormHelper::Text('number_of_characters', 1, array('label' => 'Characters', 'style' => 'width:40px;')); ?>
            </td>
            <td>
                <?php echo FormHelper::Text('expires_on', date('m/d/Y', strtotime('+1 month')), array('label' => true)); ?>
            </td>
            <td>
                <?php echo FormHelper::Text('amount_paid', '5', array('label' => true, 'style' => 'width:40px;')); ?>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;">
                <?php echo FormHelper::Button('action', 'Add Supporter'); ?>
            </td>
        </tr>
    </table>
</form>
<script>
    $(function() {
        $("#expires-on").datepicker();
        $("#username").autocomplete({
            source: 'users.php?action=search',
            minLength: 2,
            focus: function() {
                return false;
            },
            select: function(e, ui) {
                $("#user-id").val(ui.item.value);
                console.debug(ui);
                $("#username").val(ui.item.label);
                return false;
            }
        });

        $('form').submit(function(e) {
            if(!(parseInt($("#user-id").val()) > 0)) {
                alert('Please make sure to select a user')
                e.preventDefault();
            }
        });
    });
</script>
<?php
$page_content = ob_get_clean();
