<?php
use classes\character\nwod2\BeatService;
use classes\character\nwod2\SheetService;
use classes\core\repository\Database;

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../webroot/cgi-bin/start_of_page.php';

$db = new Database();
$beatService = new BeatService();
$sheetService = new SheetService();

/* start of month tasks */
if (date('j') == 1) {
    // bulk award 2 xp
    $sheetService->awardXpToActiveCharacters(2);
    $beatService->awardOutstandingBeats();
}
$sheetService->restoreTempWillpower();

$characterCounts = $sheetService->checkCharacterActivity();

$now = date("Y-m-d H:i:s");
$message = <<<EOQ
Maintenance completed on: $now
Idled Characters: {$characterCounts['idle']}
Inactive Characters: {$characterCounts['inactive']}
EOQ;

mail('jeffvandenberg@gmail.com', 'WaW Maintance', $message);
