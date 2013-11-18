<?php
/* @var array $userdata */
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\repository\RepositoryManager;
use classes\support\data\Supporter;
use classes\support\repository\SupporterRepository;

$page_title = $contentHeader = 'Update Supporter';

$supporterRepository = RepositoryManager::GetRepository('classes\support\data\Supporter');
/* @var SupporterRepository $supporterRepository */
$supporter = $supporterRepository->GetById(Request::GetValue('id', 0));
/* @var Supporter $supporter */

if(Request::IsPost())
{
    $supporter->AmountPaid = Request::GetValue('amount_paid', 0);
    $supporter->ExpiresOn = date('Y-m-d', strtotime(Request::GetValue('expires_on')));
    $supporter->NumberOfCharacters = Request::GetValue('number_of_characters', 0);
    $supporter->UpdatedById = $userdata['user_id'];
    $supporter->UpdatedOn = date('Y-m-d H:i:s');

    if($supporterRepository->Save($supporter)) {
        $message = <<<EOQ
Your Wicked Supporter account has been updated.

For your records:
Amount Paid: $supporter->AmountPaid
Number of Characters: $supporter->NumberOfCharacters
Expires On: $supporter->ExpiresOn

You will receive a reminder email two weeks before your support expires.


Thank you for your support of GamingSandbox!
EOQ;

        mail($supporter->User->UserEmail, 'Wicked Support', $message, 'from: support@gamingsandbox.com');
        SessionHelper::SetFlashMessage('Updated Supporter');
        Response::Redirect('support.php?action=manage');
    }
    else {
        SessionHelper::SetFlashMessage('Failed to update supporter');
    }
}

ob_start()
?>

    <form method="post">
        <table>
            <tr>
                <td>
                    <label>Username</label>
                    <?php echo $supporter->User->Username; ?>
                </td>
                <td>
                    <?php echo FormHelper::Text('number_of_characters', $supporter->NumberOfCharacters, array('label' => 'Characters', 'style' => 'width:40px;')); ?>
                </td>
                <td>
                    <?php echo FormHelper::Text('expires_on', date('m/d/Y', strtotime($supporter->ExpiresOn)), array('label' => true)); ?>
                </td>
                <td>
                    <?php echo FormHelper::Text('amount_paid', $supporter->AmountPaid, array('label' => true, 'style' => 'width:40px;')); ?>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;">
                    <?php echo FormHelper::Button('action', 'Update Supporter'); ?>
                </td>
            </tr>
            <tr>
                <th colspan="4">
                    Selected Characters
                </th>
            </tr>
            <?php foreach($supporter->Characters as $character): ?>
                <tr>
                    <td colspan="4">
                        <?php echo $character->CharacterName; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </form>
    <script>
        $(function() {
            $("#expires-on").datepicker();
        });
    </script>
<?php
$page_content = ob_get_clean();
