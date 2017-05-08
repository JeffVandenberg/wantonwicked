<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/10/2015
 * Time: 12:01 AM
 */

namespace classes\territory;


use classes\core\repository\Database;

class Territory
{
    public static function CreateTerritoryList($territories, $mayEdit = false)
    {
        $territoryList = "";
        if ($mayEdit) {
            $territoryList .= <<<EOQ
<a href="#" onclick="return createTerritory();" class="button">Create Territory</a>
EOQ;
        }

        $territoryList .= <<<EOQ
<table>
    <thead>
    <tr>
        <th>Territory Name</th>
        <th>Type</th>
        <th>Held By</th>
        <th>PCs</th>
        <th>NPCs</th>
        <th><span title="Quality">Q.</span></th>
        <th><span title="Current Quality">C. Q.</span></th>
        <th><span title="Security">S.</span></th>
        <th><span title="Optimal Population">O. P.</span></th>
        <th></th>
    </tr>
    </thead>        
EOQ;

        if (count($territories)) {
            foreach ($territories as $i => $territoryDetail) {
                $territoryList .= <<<EOQ
    <tr id="territoryRow${territoryDetail['id']}">
        <td>${territoryDetail['territory_name']}</td>
        <td>Domain</td>
        <td>${territoryDetail['character_name']}</td>
        <td>${territoryDetail['pc_count']}</td>
        <td>${territoryDetail['npc_population']}</td>
        <td>${territoryDetail['quality']}</td>
        <td>${territoryDetail['current_quality']}</td>
        <td>${territoryDetail['security']}</td>
        <td>${territoryDetail['optimal_population']}</td>
        <td>
            <a href="#" onclick="return viewTerritory(${territoryDetail['id']});">View</a>
            <a href="/territory.php?action=edit&id=${territoryDetail['id']}">Manage</a>
        </td>
    </tr>
EOQ;
            }

            $territoryList .= '</table>';
        } else {
            $territoryList .= <<<EOQ
<div style="clear:both;">
	No territories defined.
</div>
EOQ;
        }

        return $territoryList;
    }

    public static function CreateTerritoryListPublic($territories, $characterId)
    {
        $territoryList = <<<EOQ
<div class="tableRowHeader" style="width:672px;">
	<div class="tableRowHeaderCell firstCell cell" style="width:180px;">
		Territory Name
	</div>
	<div class="tableRowHeaderCell cell" style="width:60px;">
		Type
	</div>
	<div class="tableRowHeaderCell cell" style="width:160px;">
		Held By
	</div>
	<div class="tableRowHeaderCell cell" style="width:50px;">
		Open
	</div>
	<div class="tableRowHeaderCell cell" style="width:50px;">
	Quality
	</div>
	<div class="tableRowHeaderCell cell" style="width:50px;">
		Security
	</div>
	<div class="tableRowHeaderCell cell" style="width:80px;">
		&nbsp;
	</div>
</div>
EOQ;

        $row = 0;
        if (count($territories)) {
            foreach ($territories as $territoryDetail) {
                $rowAlt = (($row++) % 2) ? "Alt" : "";

                $isOpen = $territoryDetail['is_open'] ? 'Yes' : 'No';

                $links = "";

                if ($characterId == $territoryDetail['character_id']) {
                    $links .= <<<EOQ
 <a href="/territory.php?action=manage&id=$territoryDetail[id]&character_id=$characterId">Manage</a>
EOQ;
                }

                if (!$territoryDetail['in_territory']) {
                    if ($territoryDetail['is_open']) {
                        $links .= <<<EOQ
 <a href="#" onclick="return feedFromTerritory($territoryDetail[id], $characterId, '$territoryDetail[territory_name]', this);">Feed</a>
EOQ;
                    } else {
                        $links .= <<<EOQ
 <a href="#" onclick="return poachTerritory($territoryDetail[id], $characterId, '$territoryDetail[territory_name]', this);">Poach</a>
EOQ;
                    }
                } else {
                    if (!$territoryDetail['is_poaching']) {
                        $links .= <<<EOQ
<a href="#" onclick="return leaveTerritory($territoryDetail[character_territory_id], $territoryDetail[id], '$territoryDetail[territory_name]', this);">Leave</a>
EOQ;
                    } else {
                        $links .= "&nbsp;";
                    }
                }

                $territoryList .= <<<EOQ
<div class="tableRow$rowAlt" style="clear:both;width:672px;" id="territoryRow$territoryDetail[id]">
	<div class="firstCell cell" style="width:180px;">
		$territoryDetail[territory_name]
	</div>
	<div class="cell" style="width:60px;">
		Domain
	</div>
	<div class="cell" style="width:160px;">
		$territoryDetail[character_name]
	</div>
	<div class="cell centeredText" style="width:50px;">
		$isOpen
	</div>
	<div class="cell centeredText" style="width:50px;">
		$territoryDetail[current_quality]
	</div>
	<div class="cell centeredText" style="width:50px;">
		$territoryDetail[security]
	</div>
	<div class="cell" style="width:80px;">
		$links
	</div>
</div>
EOQ;
            }
        } else {
            $territoryList .= <<<EOQ
<div style="clear:both;">
	No territories defined.
</div>
EOQ;
        }

        return $territoryList;
    }

    public static function CreateTerritoryAssociatedCharacters($id, $mayEdit = false, $showPoachers = true)
    {
        if (!$id) {
            return "None";
        }

        $query = <<<EOQ
SELECT
	CT.id,
	CT.character_id,
	C.character_name,
	CT.is_poaching,
	CT.created_on
FROM
	characters_territories AS CT
	LEFT JOIN characters AS C ON CT.character_id = C.character_id
WHERE
	CT.territory_id = ?
	AND CT.is_active = 1
	AND (CT.updated_on IS NULL OR CT.updated_on > NOW())
	AND C.is_sanctioned = 'Y'
	AND C.is_deleted = 'N'
ORDER BY
	is_poaching,
	character_name
EOQ;

        $params = array($id);
        $rows = Database::getInstance()->query($query)->all($params);

        $characterList = "";

        if (count($rows)) {
            foreach ($rows as $detail) {
                if (!$detail['is_poaching'] || ($showPoachers && ($detail['is_poaching'] == 1))) {
                    $characterList .= <<<EOQ
<div style="width:250px;">
$detail[character_name] &nbsp;&nbsp;
EOQ;
                    if ($detail['is_poaching'] == 1) {
                        $characterList .= " *Poaching* ";
                    }

                    if ($mayEdit) {
                        $characterNameWithSlashes = addslashes($detail['character_name']);
                        $characterList .= <<<EOQ
<a href="#" onclick="return adminRemoveCharacterFromTerritory($detail[id], $id, '$characterNameWithSlashes');">Remove</a>
EOQ;
                    }
                } else {
                }

                $characterList .= "</div>";
            }

        }

        if ($characterList == "") {
            $characterList = "No associated characters.";
        }
        return $characterList;
    }

    public static function GetNumberOfLeeches($territoryId)
    {
        $sql = <<<EOQ
SELECT
	COUNT(*) AS NumberOfLeeches
FROM
	territories AS T
	LEFT JOIN characters_territories AS CT ON T.id = CT.territory_id
WHERE
	T.id = ?
	AND CT.is_active = 1
	AND (CT.updated_on IS NULL OR CT.updated_on > now())
EOQ;

        $params = array(
            $territoryId
        );

        $numberOfLeeches = Database::getInstance()->query($sql)->value($params);

        return $numberOfLeeches;
    }

}
