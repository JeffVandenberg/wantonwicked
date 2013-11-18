<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jvandenberg
 * Date: 9/9/13
 * Time: 12:43 PM
 * To change this template use File | Settings | File Templates.
 */

$startTime = microtime(true);
$runCount = 10;
for($i = 0; $i < $runCount; $i++) {
    $s = file_get_contents('http://wantonwicked.gamingsandbox.com/chat/includes/getData.php?roomID=1&history=0&last=32455&s=&rnd=0.8778209236916155');
}

echo (microtime(true) - $startTime) / $runCount;