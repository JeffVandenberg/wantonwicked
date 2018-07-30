<?php
/* @var array $userdata */
use classes\core\helpers\UserdataHelper;

$contentHeader = $page_title = 'View Upcoming events';

ob_start();
?>
<?php if(UserdataHelper::isSt($userdata)): ?>
    <div style="text-align: center;">
        <a class="button" href="https://www.google.com/calendar/render?eid=aW43MDhyam5ucTRtdTBwZ2htaTB1bDY2NGMgNjExZG9tanJ0bmJzbGxudm00YXZmM21jMW9AZw&ctz=America/Chicago&sf=true&output=xml" target="_blank">Add Event</a>
    </div>
<?php endif; ?>
<iframe src="https://www.google.com/calendar/embed?title=Events&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=611domjrtnbsllnvm4avf3mc1o%40group.calendar.google.com&amp;color=%232F6309&amp;ctz=America%2FChicago" style=" border-width:0 " width="740" height="600" frameborder="0" scrolling="no"></iframe>

<?php
$page_content = ob_get_clean();
