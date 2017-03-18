<?php
use classes\core\helpers\Request;
use classes\core\repository\RepositoryManager;
use classes\support\repository\SupporterRepository;

$page_title = 'Manage Supporters';
$contentHeader = $page_title;
if(Request::isPost())
{

}
$supporterRepository = RepositoryManager::GetRepository('classes\support\data\Supporter');
/* @var SupporterRepository $supporterRepository */

$onlyActive = Request::getValue('active', '1') == 1;
$supporters = $supporterRepository->ListSupporters($onlyActive);

/* @var array $supporters */

ob_start();
?>
<a href="support.php?action=add" class="button">Add Supporter</a>
<a href="support.php?action=manage&active=<?php echo !$onlyActive; ?>" class="button">Toggle Active</a>
<table>
    <tr>
        <th>
            User
        </th>
        <th>
            # of Chars
        </th>
        <th>
            Awarded
        </th>
        <th>
            Paid
        </th>
        <th>
            Expires
        </th>
        <th>
            Updated By
        </th>
        <th>
            Updated
        </th>
        <th>

        </th>
    </tr>
    <?php foreach($supporters as $supporter): ?>
        <tr>
            <td>
                <a href="support.php?action=edit&id=<?php echo $supporter['id']; ?>"><?php echo $supporter['username']; ?></a>
            </td>
            <td>
                <?php echo $supporter['number_of_characters']; ?>
            </td>
            <td>
                <?php echo $supporter['characters_awarded']; ?>
            </td>
            <td>
                <?php echo $supporter['amount_paid']; ?>
            </td>
            <td>
                <?php echo date('m/d/Y', strtotime($supporter['expires_on'])); ?>
            </td>
            <td>
                <?php echo $supporter['updated_by_username']; ?>
            </td>
            <td>
                <?php echo date('m/d/Y h:i:sa', strtotime($supporter['updated_on'])); ?>
            </td>
            <td>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script>
    $(function() {
        $(".button").button();
    });
</script>
<?php
$page_content = ob_get_clean();