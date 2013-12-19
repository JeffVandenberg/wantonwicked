<?php
/* @var array $userdata */
$page_title = "List ST Permissions";
$contentHeader = $page_title;

// test if removing any permissions
if (isset($_POST['action'])) {
    if (($_POST['action'] == 'update') && isset($_POST['delete'])) {
        $list = $_POST['delete'];
        foreach ($list as $key => $value) {
            $delete_query = "delete from gm_permissions where permission_id=$value;";
            ExecuteQuery($delete_query);
        }
    }
}


// build js for the page
$java_script .= <<<EOQ
<script language="JavaScript">
function submitForm ( )
{
	window.document.gm_list.submit();
}
</script>
EOQ;

// may only add a skill if moderator or submittor and not a approved
$page_content .= <<<EOQ
<a href="$_SERVER[PHP_SELF]?action=permissions_add"Name>Add ST Permission</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="$_SERVER[PHP_SELF]?action=permissions" onClick="submitForm();return false;"Name>Delete ST Permissions</a>
EOQ;

// get details of GM Permissions from database
$login_query = <<<EOQ
SELECT
    group_concat(G.name separator ', ') as groups,
    L.user_id AS ID,
    L.username as Name,
    GP.*
FROM
    phpbb_users AS L
    INNER JOIN gm_permissions AS GP ON L.user_id = GP.ID
    LEFT JOIN st_groups AS SG ON GP.ID = SG.user_id
    LEFT JOIN groups AS G ON SG.group_id = G.id
GROUP BY
    L.user_id
ORDER BY
    GP.Side_Game DESC,
    L.username;
EOQ;
$storytellers = ExecuteQueryData($login_query);

$page_content .= <<<EOQ
<form name="gm_list" id="gm_list" method="post">
    <input type="hidden" name="action" id="action" value="update">
    <table>
        <tr>
            <th>
                Delete
            </th>
            <th>
                Login Name
            </th>
            <th>
                Group(s)
            </th>
            <th>
                Is Asst
            </th>
            <th>
                Is ST
            </th>
            <th>
                Is Head
            </th>
            <th>
                Is Admin
            </th>
            <th>
                Side Game
            </th>
            <th>
                Wiki
            </th>
        </tr>
EOQ;

// build table
foreach($storytellers as $login_detail) {
    $page_content .= <<<EOQ
  <tr>
EOQ;
    if ($userdata['is_admin'] || $login_detail['Is_Admin'] != 'Y') {
        $page_content .= <<<EOQ
	  <td>
	    <input type="checkbox" name="delete[]" id="delete[]" value="$login_detail[Permission_ID]">
	  </td>
EOQ;
    }
    else {
        $page_content .= <<<EOQ
	  <td>
	    &nbsp;
	  </td>
EOQ;
    }
    $page_content .= <<<EOQ
	  <td>
	    <a href="$_SERVER[PHP_SELF]?action=permissions_view&permission_id=$login_detail[Permission_ID]"Name>$login_detail[Name]</a>
	  </td>
	  <td>
        $login_detail[groups]
	  </td>
	  <td>
	    $login_detail[Is_Asst]
	  </td>
	  <td>
	    $login_detail[Is_GM]
	  </td>
	  <td>
	    $login_detail[Is_Head]
	  </td>
	  <td>
	    $login_detail[Is_Admin]
	  </td>
	  <td>
	    $login_detail[Side_Game]
	  </td>
	  <td>
	    $login_detail[Wiki_Manager]
	  </td>
	</tr>
EOQ;
}

$page_content .= "</form></table>";
