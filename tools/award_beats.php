<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 3/12/2017
 * Time: 10:17 PM
 */
use classes\character\nwod2\BeatService;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../webroot/cgi-bin/start_of_page.php';

// grab command line params
$options = getopt('c::', []);

// script variables
$characterId = null;

// parse command line params
if ($options) {
    if (isset($options['c'])) {
        $characterId = $options['c'];
    }
}

// do the actual work
$service = new BeatService();
if ($characterId) {
    echo "Award beats to " . $characterId . "\n";
    $service->awardOutstandingBeatsToCharacter($characterId);
} else {
    echo "Award beats to All\n";
    $service->awardOutstandingBeats();
}

exit(0);
