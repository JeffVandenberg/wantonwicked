<?php
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
$page_title = 'Create update for ' . $character['Character_Name'];

ob_start();
?>
<script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>


<h3>Create Update for <?php echo $character['Character_Name']; ?></h3>

<form method="post">
    <table style="width:100%;">
        <tr>
            <td>
                Update Type
            </td>
            <td>
                <select>
                    <option>Color</option>
                    <option>Action</option>
                    <option>XP Request</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                Subtype
            </td>
            <td>
                Stub, could be possible functionality
            </td>
        </tr>
        <tr style="vertical-align: top;">
            <td>
                Text
            </td>
            <td>
                <textarea></textarea>
            </td>
        </tr>
    </table>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        tinymce.init({
            selector: "textarea",
            theme: "modern",
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor"
            ],
            toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            toolbar2: "print preview media | forecolor backcolor emoticons"
        });
    });
</script>
<?php
$page_content = ob_get_contents();
ob_end_clean();
