<?php
// get past rolls
use classes\core\repository\Database;

$roll_query = "select * from wod_dierolls order by roll_id desc limit 10;";

$rolls = Database::getInstance()->query($roll_query)->all();

ob_start();
?>
<div id="dice-list">
    <table>
        <?php foreach ($rolls as $i => $roll): ?>
            <?php $row_color = ($i % 2) ? "#443a33" : "";
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
            ?>
            <tr style="vertical-align: top;">
                <td>
                    <a href="/dieroller.php?action=view_roll&r=<?php echo $roll['Roll_ID']; ?>" target="_blank">View</a>
                </td>
                <td>
                    <?php echo $roll['Character_Name'] . ' ' . $roll['Description']; ?> <br>
                    Dice: <?php echo $roll['Dice'].' '.$wp.' '.$pp.' '.$chance.' '.$eight_again.' '.$nine_again.' '.$no_ten_again.' '.$ones_remove.' '.$rote_action; ?><br>
                </td>
                <td>
                    Successes: <?php echo $roll['Num_of_Successes']; ?>
                    Result: <?php echo $roll['Note']; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div style="text-align: center;">
        <input type="button" class="button" value="Update" id="dice-list-update" />
    </div>
    <script>
        $(function() {
            $("#dice-list-update").click(function() {
                $("#dice-list").load('/dieroller.php?action=list');
            });
        })
    </script>
</div>
<?php
$page_content = ob_get_clean();
