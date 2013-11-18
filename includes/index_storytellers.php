<?php
// check page actions
$contentHeader = "Storytellers";
$page_title = "WantonWicked Storytellers";

$page_content = <<<EOQ
Below is a list of the current Storytellers for WantonWicked. When able I have email accounts set up for the gamemasters, which are easy to remember.  Feel free to email the GMs any particular questions that you may have regarding the game.<br>
<br>
EOQ;

// build list of Head GMs
$head_gm_query = <<<EOQ
select
    gm_permissions.*,
    U.username as Name
from
    gm_permissions
    inner join phpbb_users as U
        on gm_permissions.id = U.user_id
where
    gm_permissions.position != 'Hidden'
    and gm_permissions.is_head='Y'
order by
    U.username;
EOQ;
$headGms = ExecuteQueryData($head_gm_query);

$page_content .= <<<EOQ
<h2>
    Administrators
</h2>
<table>
    <tr>
        <th>
            Name
        </th>
    </tr>
EOQ;

foreach ($headGms as $headGm) {
    $page_content .= <<<EOQ
<tr>
    <td>
        $headGm[Name]
    </td>
</tr>
EOQ;
}

$page_content .= "</table><br>";

// build list of regular GMs
$gm_query = <<<EOQ
SELECT
    group_concat(G.name separator ', ') as groups,
    GP.*,
    U.username as Name
FROM
    gm_permissions AS GP
    inner join phpbb_users AS U ON GP.id = U.user_id
    LEFT JOIN st_groups AS SG ON GP.ID = SG.user_id
    LEFT JOIN groups AS G ON SG.group_id = G.id
WHERE
    GP.position != 'Hidden'
    and GP.is_head='N'
    and GP.is_gm = 'Y'
    AND GP.side_game = 'N'
    AND GP.position != 'Venerable Ancestor'
GROUP BY
    U.user_id
ORDER BY
    U.username;
EOQ;
$gms = ExecuteQueryData($gm_query);

$page_content .= <<<EOQ
<h2>
    Storytellers
</h2>
<table>
    <tr>
        <th>
            Name
        </th>
        <th>
            Groups
        </th>
    </tr>
EOQ;

foreach ($gms as $gm) {
    $page_content .= <<<EOQ
	<tr bgcolor="$row_color">
  	    <td>
	        $gm[Name]
	    </td>
	    <td>
	        $gm[groups]
	    </td>
	</tr>
EOQ;
}

$page_content .= "</table><br>";

$asstGmQuery = <<<EOQ
SELECT
    group_concat(G.name separator ', ') as groups,
    GP.*,
    U.username AS Name
FROM
    gm_permissions AS GP
    inner join phpbb_users AS U ON GP.id = U.user_id
    LEFT JOIN st_groups AS SG ON GP.ID = SG.user_id
    LEFT JOIN groups AS G ON SG.group_id = G.id
WHERE
    GP.position != 'Hidden'
    and GP.is_head='N'
    and GP.is_gm = 'N'
    AND GP.side_game = 'N'
    AND GP.is_asst = 'Y'
    AND GP.position != 'Venerable Ancestor'
GROUP BY
    U.user_id
ORDER BY
    U.username;
EOQ;
$asstGms = ExecuteQueryData($asstGmQuery);

$page_content .= <<<EOQ
<h2>
    Asst STs
</h2>
<table>
  <tr>
    <th>
      Name
    </th>
    <th>
      Section
    </th>
    <th>
      Position
    </th>
    <th>
      Email
    </th>
  </tr>
EOQ;

foreach($asstGms as $gm) {
    $page_content .= <<<EOQ
	<tr>
	  <td>
	    $gm[Name]
	  </td>
	  <td>
	    $gm[groups]
	  </td>
EOQ;
}

$page_content .= "</table><br />";

// build list of Wiki Managers
$wikiMgrQuery = <<<EOQ
SELECT
    gm_permissions.*,
    U.username as Name
FROM
    gm_permissions
    INNER JOIN phpbb_users AS U
        ON gm_permissions.id = U.user_id
WHERE
    gm_permissions.position != 'Hidden'
    AND gm_permissions.wiki_manager = 'Y'
ORDER BY
    U.username;
EOQ;
$wikiMgrs = ExecuteQueryData($wikiMgrQuery);

$page_content .= <<<EOQ
<h2>
    Wiki Managers
</h2>
<table>
  <tr>
    <th>
      Name
    </th>
  </tr>
EOQ;

foreach ($wikiMgrs as $wikiMgr) {
    $page_content .= <<<EOQ
	<tr>
	  <td>
	    $wikiMgr[Name]
	  </td>
	</tr>
EOQ;
}

$page_content .= "</table><br />";

// build list of Site Games STs
$sideGameQuery = <<<EOQ
SELECT
    gm_permissions.*,
    U.username as Name
FROM
    gm_permissions
    INNER JOIN phpbb_users AS U
        ON gm_permissions.id = U.user_id
WHERE 
    gm_permissions.site_id=1004 
    AND gm_permissions.position != 'Hidden' 
    AND Side_Game = 'Y' 
ORDER BY 
    U.username;
EOQ;
$sideGameGms = ExecuteQueryData($sideGameQuery);

$page_content .= <<<EOQ
<h2>
    Side Game STs
</h2>
<table>
  <tr>
    <th>
      Name
    </th>
  </tr>
EOQ;

foreach ($sideGameGms as $sideGameGm) {
    $page_content .= <<<EOQ
	<tr>
	  <td>
	    $sideGameGm[Name]
	  </td>
	</tr>
EOQ;
}

$page_content .= "</table><br />";