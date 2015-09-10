<?php
// get list of territories with PC & NPC counts and domain holder
use classes\territory\Territory;

$territoryQuery = <<<EOQ
SELECT
	T.territory_name,
	T.id,
	C.character_name,
	(
		SELECT
			COUNT(*)
		FROM
			characters_territories AS CT
			LEFT JOIN characters as C2 ON CT.character_id = C2.id
		WHERE
			CT.is_active = 1
			AND (CT.updated_on IS NULL OR CT.updated_on > NOW())
			AND C2.is_sanctioned = 'Y'
			AND C2.is_deleted = 'N'
			AND CT.territory_id = T.id
	) AS pc_count,
	T.npc_population,
	T.quality,
	T.current_quality,
	T.max_quality,
	T.security,
	T.optimal_population
FROM
	territories as T
	LEFT JOIN characters AS C ON T.character_id = C.id
WHERE
	T.is_active = 1
	AND C.is_sanctioned = 'Y'
	AND C.is_deleted = 'N'
GROUP BY
	T.id
ORDER BY
	T.territory_name
EOQ;
$territoryResult = ExecuteQuery($territoryQuery);

$page_title = "ST Territory List";

$page_content = <<<EOQ
<div id="territoryPane" style="display:none;">
	<div id="territoryPaneClose">
		Close
	</div>
	<div id="territoryPaneContent">
		Territory Pane
	</div>
</div>
<div class="paragraph">
	<a href="/abp.php">Back to ABP Home</a>
</div>
<h2>
	Territory Information
</h2>
<a href="territory.php?action=update_all">Update Territory Quality</a>
EOQ;

$page_content .= Territory::CreateTerritoryList($territoryResult, true);

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
