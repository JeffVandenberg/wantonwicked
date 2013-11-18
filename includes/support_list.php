<?php
/* @var array $userdata */
use classes\core\repository\RepositoryManager;
use classes\support\repository\SupporterRepository;

$contentHeader = $page_title = 'Supporters';

$supporterRepository = RepositoryManager::GetRepository('classes\support\data\Supporter');
/* @var SupporterRepository $supporterRepository */
$supporters = $supporterRepository->ListCurrentSupporters();

$isSupporter = $supporterRepository->CheckIsCurrentSupporter($userdata['user_id']);

ob_start();
?>

<a href="support.php?action=contribute" class="button">Contribute</a>
<?php if($isSupporter): ?>
    <a href="support.php?action=setCharacters" class="button">Update Support Status</a>
<?php endif; ?>
<a href="/wiki/index.php?n=GameRef.5For5Offer" class="button">View Supporter Information</a>
<div>
    <img src="http://wantonwicked.gamingsandbox.com/wiki/images/wickedsupporter.jpg" />
</div>
<table>
    <tr>
        <th>
            User
        </th>
    </tr>
    <?php foreach($supporters as $supporter): ?>
        <tr>
            <td>
                <?php echo $supporter['username']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script>
    $(function() {
        $(".button").button();
    })
</script>
<?php
$page_content = ob_get_clean();