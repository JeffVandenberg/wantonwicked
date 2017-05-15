<?php
// get list of territories with PC & NPC counts and domain holder
use classes\territory\service\TerritoryService;
use classes\territory\Territory;

$service = new TerritoryService();

$territories  = $service->listTerritoriesWithPopulation();

$page_title = "ST Territory List";

ob_start();
?>
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
<a href="/territory.php?action=update_all" class="button">Update Territory Quality</a>

<?php echo Territory::CreateTerritoryList($territories, true); ?>

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
<?php
$page_content = ob_get_clean();
