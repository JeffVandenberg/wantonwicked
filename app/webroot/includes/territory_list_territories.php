<?php
$characterId = $_GET['character_id'] + 0;

include 'includes/components/territory_list_public.php';

// get list of territories with PC & NPC counts and domain holder
$territoryQuery = <<<EOQ
SELECT
	DISTINCT
	T.territory_name,
	T.id,
	T.character_id,
	C.character_name,
	T.quality,
	T.current_quality,
	T.security,
	T.is_open,
	CT.is_poaching,
	CT.id AS character_territory_id,
	IF(CT.id IS NULL, 0, 1) AS in_territory
FROM
	territories as T
	LEFT JOIN characters AS C ON T.character_id = C.id
	LEFT JOIN characters_territories AS CT ON 
    (CT.is_active = 1 
    AND T.id = CT.territory_id 
    AND CT.character_id = $characterId)
WHERE
	T.is_active = 1
	AND (CT.updated_on IS NULL OR CT.updated_on > NOW())
ORDER BY
	T.territory_name
EOQ;
$territoryResult = ExecuteQuery($territoryQuery);

$page_title = "Territory List";

$page_content = <<<EOQ
<div id="territoryPane" style="display:none;">
	<div id="territoryPaneClose">
		Close
	</div>
	<div id="territoryPaneContent">
		Territory Pane
	</div>
</div>
<h2>
	Territory Information
</h2>
EOQ;

$page_content .= CreateTerritoryListPublic($territoryResult, $characterId);

$page_content .= <<<EOQ
<script type="text/javascript">	
	$(document).ready(function(){
		$("#territoryPaneClose").click(function(e){
			$("#territoryPane").css("display", "none");
		});
		$(document).keypress(function(e){
			if(e.keyCode == 27)
			{
				$("#territoryPane").css("display", "none");
			}
		});
		$(document).keydown(function(e){
			if(e.keyCode == 27)
			{
				$("#territoryPane").css("display", "none");
			}
		});
	});
</script>
EOQ;

?>