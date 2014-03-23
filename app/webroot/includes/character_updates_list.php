<?php
$page_content = 'Character Update List';

$characterId = (isset($_GET['character_id'])) ? (int) $_GET['character_id'] : 0;
$characterId = (isset($_POST['character_id'])) ? (int) $_POST['character_id'] : $characterId;

$userId = $userdata['user_id'];

$characterQuery = <<<EOQ
SELECT
    WC.*
FROM
    characters AS WC
WHERE
    WC.character_id = $characterId
EOQ;

$characterResult = mysql_query($characterQuery);
$character = array();
if(mysql_num_rows($characterResult))
{
    $character = mysql_fetch_assoc($characterResult);
}
else
{
    die('Unknown Character');
}
$page_title = 'Character Updates for ' . $character['Character_Name'];

ob_start();
?>
<h3>Updates for <?php echo $character['Character_Name']; ?></h3>
<div class="paragraph">
    <a href="/character_updates.php?action=create&character_id=<?php echo $characterId; ?>">Create Update</a>
</div>

<table id="update_list" style="width:100%;">
    <tr>
        <th>Title</th>
        <th>Update Type</th>
        <th>Status</th>
        <th>Updated By</th>
        <th>Updated On</th>
        <th>Actions</th>
    </tr>
</table>
<?php
$page_content = ob_get_contents();
ob_end_clean();
