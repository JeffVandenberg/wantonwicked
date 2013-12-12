<?php
// check page actions
use classes\core\repository\Database;

$contentHeader = $page_title = "Icon List";

// test if updating/delete
if(isset($_POST['action']))
{
	if($_POST['action'] == 'update')
	{
		$skill_list = $_POST['delete'];
		while(list($key, $value) = each($skill_list))
		{
			//echo "delete: $key: $value<br>";
			$delete_query = "delete from icons where id=$value;";
			//echo $delete_query."<br>";
			$delete_result = mysql_query($delete_query);
		}
	}
}

$page_content = <<<EOQ
<script language="JavaScript">
    function submitForm ( )
    {
        window.document.icon_list.submit();
    }
</script>
<form name="icon_list" id="icon_list" method="post" action="$_SERVER[PHP_SELF]?action=icons_list">
    <a href="icons_add.php" onClick="window.open('$_SERVER[PHP_SELF]?action=icons_add', 'addIcon', 'width=300,height=300,resizable,scrollbars');return false;">Add Icon</a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a href="#" onclick="submitForm();">Delete Icon(s)</a>
    <input type="hidden" name="action" id="action" value="update">
    <br>
    <table>
        <tr>
            <th>
            </th>
            <th>
                Name
            </th>
            <th>
                File
            </th>
            <th>
                Player Viewable
            </th>
            <th>
                ST Viewable
            </th>
            <th>
                Head ST Viewable
            </th>
        </tr>
EOQ;

$icon_query = "select * from icons order by Icon_Name";
$icons = Database::GetInstance()->Query($icon_query)->All();

foreach($icons as $icon_detail)
{
	$page_content .= <<<EOQ
<tr>
    <td>
        <input type="checkbox" name="delete[]" id="delete[]" value="$icon_detail[ID]">
    </td>
    <td>
        <a href="#" onClick="window.open('$_SERVER[PHP_SELF]?action=icons_view&id=$icon_detail[ID]', 'icon$icon_detail[ID]', 'width=300,height=300,resizable,scrollbars');return false;">$icon_detail[Icon_Name]</a>
    </td>
    <td>
        $icon_detail[Icon_ID]
    </td>
    <td>
        $icon_detail[Player_Viewable]
    </td>
    <td>
        $icon_detail[GM_Viewable]
    </td>
    <td>
        $icon_detail[Admin_Viewable]
    </td>
</tr>
EOQ;
}

$page_content .= "</table></form>";