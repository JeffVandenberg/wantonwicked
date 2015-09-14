<?php
use classes\core\helpers\Response;use classes\core\repository\Database;$page_title = "Side Game Dieroller";
$contentHeader = $page_title;

// Action Logic
$page = (isset($_POST['page'])) ? $_POST['page'] + 0 : 1;
$page = (isset($_GET['page'])) ? $_GET['page'] + 0 : $page;
$page_size = 30;

if ($_POST['number_of_dice']) {
    $number_of_dice = $_POST['number_of_dice'] + 0;
    $sides = $_POST['sides'] + 0;
    $modifier = $_POST['modifier'] + 0;
    $total = 0;
    $rolls = "";
    if (($number_of_dice > 0) && ($sides > 0)) {
        for ($i = 0; $i < $number_of_dice; $i++) {
            $roll = mt_rand(1, $sides);
            $rolls .= (($i == 0) ? $roll : ", " . $roll);
            $total += $roll;
        }

        $total += $modifier;

        $description = addslashes(htmlspecialchars($_POST['description']));

        $query = <<<EOQ
INSERT INTO
	die_rolls
	(
		description,
		number_of_dice,
		sides,
		modifier,
		total,
		rolls,
		created_on
	)
VALUES
	(
		?,
		?,
		?,
		?,
		?,
		?,
		now()
	)
EOQ;
        $params = array(
                $description,
                $number_of_dice,
                $sides,
                $modifier,
                $total,
                $rolls
        );
        Database::getInstance()->query($query)->execute($params);

        Response::redirect('/dieroller.php?action=custom');
    }
}

// View

// get count
$count_query = <<<EOQ
SELECT
	COUNT(*) AS count
FROM
	die_rolls
EOQ;
$count_data = Database::getInstance()->query($count_query)->single();;

$count = $count_data['count'];
$pages = round($count / $page_size, 0);
if ($pages == 0) {
    $pages = 1;
}

if ($page > $pages) {
    $page = $pages;
}

$current_row = ($page - 1) * $page_size;

// get current page
$rolls_query = <<<EOQ
SELECT
	die_rolls.*
FROM
	die_rolls
ORDER BY
	created_on DESC
LIMIT
	$current_row, $page_size
EOQ;

Database::getInstance()->query($rolls_query)->all();

ob_start();
?>
    <div class="ui-widget">
        <form method="post" action="dieroller.php?action=custom" id="roll-dice">
            <div align="center">
                <div style="margin:5px">
                    Description: <input type="text" name="description" id="description" value="" maxlength="200"
                                        style="width:200px;"/>
                </div>
                <div style="margin-bottom:5px;">
                    Number: <input type="text" name="number_of_dice" id="number-of-dice" value="" maxlength="3"
                                   style="width:30px;"/>

                    Sides: <input type="text" name="sides" id="sides" value="" maxlength="3" style="width:30px;"/>

                    Modifier: <input type="text" name="modifier" id="modifier" value="0" maxlength="4"
                                     style="width:30px;"/>
                    <input type="button" value="Roll" name="roll_dice_button" id="roll-dice-button"/>
                </div>
            </div>
            </div>
        </form>

        <table>
            <tr>
                <td style="font-weight:bold;">
                    Description
                </td>
                <td style="font-weight:bold;">
                    Total
                </td>
                <td style="font-weight:bold;">
                    Number
                </td>
                <td style="font-weight:bold;">
                    Sides
                </td>
                <td style="font-weight:bold;">
                    Modifier
                </td>
                <td style="font-weight:bold;">
                    Actions
                </td>
            </tr>
            <?php foreach ($rolls_data as $key => $roll): ?>
                <tr>
                    <td>
                        <?php echo $roll['description']; ?>
                    </td>
                    <td>
                        <?php echo $roll['total']; ?>
                    </td>
                    <td>
                        <?php echo $roll['number_of_dice']; ?>
                    </td>
                    <td>
                        <?php echo $roll['sides']; ?>
                    </td>
                    <td>
                        <?php echo $roll['modifier']; ?>
                    </td>
                    <td>
                        <div rollid="<?php echo $roll['id']; ?>" class="toggle-roll-row clickable link">Show Rolls</div>
                    </td>
                </tr>
                <tr id="row<?php echo $roll['id']; ?>" style="display:none;">
                    <td colspan="6">
                        Rolls: <?php echo $roll['rolls']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <form method="get" action="dieroller.php">
            Page <input type="text" value="<?php echo $page; ?>" name="page" style="width:30px;"/>
            of <?php echo $pages; ?>
            <input type="hidden" name="action" value="custom"/>
            <input type="submit" value="Go to Page"/>
        </form>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $(document)
                .on("focus", "input, textarea", function () {
                    $(this).css("border", "solid 3px #2277cc");
                })
                .on("blur", "input, textarea", function () {
                    $(this).css("border", "none");
                });

            $("input, textarea")
                .css("border", "none");

            $("select")
                .focus(function () {
                    $(this).css("border", "3px solid #2277cc").css("height", "28px");
                })
                .blur(function () {
                    $(this).css("border-width", "0px").css("height", "22px");
                });

            $(".toggle-roll-row")
                .click(function () {
                    $("#row" + $(this).attr("rollid")).toggle();
                    e.preventDefault();
                });

            $("#roll-dice-button").click(function () {
                var isValid = true;

                if ($.trim($("#description").val()) === '') {
                    $("#description").css("border", "solid 2px #ff0000");
                    isValid = false;
                }
                if ($.trim($("#number-of-dice").val()) === '') {
                    $("#number-of-dice").css("border", "solid 2px #ff0000");
                    isValid = false;
                }
                if ($.trim($("#sides").val()) === '') {
                    $("#sides").css("border", "solid 2px #ff0000");
                    isValid = false;
                }

                if (isValid) {
                    $("#roll-dice").submit();
                }
            });
        });
    </script>
<?php
$page_content = ob_get_clean();
