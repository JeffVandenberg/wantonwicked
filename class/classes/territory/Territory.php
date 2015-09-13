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
<div class="paragraph">
	<a href="#" onclick="return createTerritory();">Create Territory</a>
</div>
EOQ;
        }

        $territoryList .= <<<EOQ
<div class="tableRowHeader" style="width:770px;">
	<div class="tableRowHeaderCell firstCell cell" style="width:150px;">
		Territory Name
	</div>
	<div class="tableRowHeaderCell cell" style="width:60px;">
		Type
	</div>
	<div class="tableRowHeaderCell cell" style="width:160px;">
		Held By
	</div>
	<div class="tableRowHeaderCell cell" style="width:30px;">
		PCs
	</div>
	<div class="tableRowHeaderCell cell" style="width:30px;">
		NPCs
	</div>
	<div class="tableRowHeaderCell cell" style="width:30px;">
		Q.
	</div>
	<div class="tableRowHeaderCell cell" style="width:30px;">
		C.Q.
	</div>
	<div class="tableRowHeaderCell cell" style="width:30px;">
		S.
	</div>
	<div class="tableRowHeaderCell cell" style="width:70px;">
		O. P.
	</div>
	<div class="tableRowHeaderCell cell" style="width:120px;">
		&nbsp;
	</div>
</div>
EOQ;

        $row = 0;
        if (count($territories)) {
            foreach ($territories as $territoryDetail) {
                $rowAlt = (($row++) % 2) ? "Alt" : "";

                $territoryList .= <<<EOQ
<div class="tableRow$rowAlt" style="clear:both;width:770px;" id="territoryRow$territoryDetail[id]">
	<div class="firstCell cell" style="width:120px;">
		$territoryDetail[territory_name]
	</div>
	<div class="cell" style="width:60px;">
		Domain
	</div>
	<div class="cell" style="width:160px;">
		$territoryDetail[character_name]
	</div>
	<div class="cell" style="width:30px;">
		$territoryDetail[pc_count]
	</div>
	<div class="cell" style="width:30px;">
		$territoryDetail[npc_population]
	</div>
	<div class="cell" style="width:30px;">
		$territoryDetail[quality]
	</div>
	<div class="cell" style="width:30px;">
		$territoryDetail[current_quality]
	</div>
	<div class="cell" style="width:30px;">
		$territoryDetail[security]
	</div>
	<div class="cell" style="width:70px;">
		$territoryDetail[optimal_population]
	</div>
	<div class="cell" style="width:150px;">
		<a href="#" onclick="return viewTerritory($territoryDetail[id]);">View</a>
		<a href="/territory.php?action=edit&id=$territoryDetail[id]">Manage</a>
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
			foreach($territories as $territoryDetail) {
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
	characters_territories as CT
	LEFT JOIN characters as C ON CT.character_id = C.character_id
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
			foreach($rows as $detail) {
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
