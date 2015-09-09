<?php
// view a specific roll
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\repository\Database;

$rollId = Request::getValue('r', 0);
if ($rollId === 0) {
    SessionHelper::SetFlashMessage('No roll to look up');
    Response::redirect('');
}

$sql = "SELECT * FROM wod_dierolls where roll_id = ?";
$roll = Database::getInstance()->query($sql)->single(array($rollId));

if ($roll === false) {
    SessionHelper::SetFlashMessage('Unable to find that roll.');
    Response::redirect('');
}

$page_title = $contentHeader = "View Roll: #$rollId";

$wp = "";
$pp = "";
$chance = "";
$eight_again = "";
$nine_again = "";
$no_ten_again = "";
$rote_action = "";
$ones_remove = "";

if ($roll['Used_WP'] == 'Y') {
    $wp = "(WP)";
}

if ($roll['Used_PP'] == 'Y') {
    $pp = "(BP)";
}

if ($roll['Chance_Die'] == 'Y') {
    $chance = "(Chance Die)";
}

if ($roll['1_Cancel'] == 'Y') {
    $ones_remove = "(1's Remove)";
}

if ($roll['Is_Rote'] == 'Y') {
    $rote_action = "(Rote Action)";
}

if (($roll['8_Again'] == 'Y') && ($roll['9_Again'] == 'Y') && ($roll['10_Again'] == 'Y')) {
    $eight_again = "(8-Again)";
}

if (($roll['8_Again'] == 'N') && ($roll['9_Again'] == 'Y') && ($roll['10_Again'] == 'Y')) {
    $nine_again = "(9-Again)";
}

if (($roll['8_Again'] == 'N') && ($roll['9_Again'] == 'N') && ($roll['10_Again'] == 'N')) {
    $no_ten_again = "(No 10-Again)";
}

ob_start();
?>
<table style="width:500px;">
    <tr>
        <td>
            <span style="font-weight:bold;">Character Name:</span><br />
            <?php echo $roll['Character_Name']; ?>
        </td>
        <td>
            <span style="font-weight:bold;">Action:</span><br />
            <?php echo $roll['Description']; ?>
        </td>
    </tr>
    <tr>
        <td>
            <span style="font-weight:bold;">Number of Dice:</span><br />
            <?php echo $roll['Dice']; ?>
        </td>
        <td style="vertical-align: top;">
            <span style="font-weight:bold;">Modifiers:</span><br />
            <?php echo $wp; ?>
            <?php echo $pp; ?>
            <?php echo $chance; ?>
            <?php echo $eight_again; ?>
            <?php echo $nine_again; ?>
            <?php echo $no_ten_again; ?>
            <?php echo $ones_remove; ?>
            <?php echo $rote_action; ?>
        </td>
    </tr>
    <tr>
        <td>
            <span style="font-weight:bold;">Result:</span><br />
            <?php echo $roll['Note']; ?>
        </td>
        <td>
            <span style="font-weight:bold;">Successes:</span><br />
            <?php echo $roll['Num_of_Successes']; ?>
        </td>
    </tr>
    <tr>
        <td>
            <span style="font-weight:bold;">Individual Rolls:</span><br />
            <?php echo $roll['Result']; ?>
        </td>
        <td>
            <span style="font-weight:bold;">Roll Timestamp:</span><br />
            <?php echo $roll['Roll_Date']; ?>
        </td>
    </tr>
</table>

<?php
$page_content = ob_get_clean();