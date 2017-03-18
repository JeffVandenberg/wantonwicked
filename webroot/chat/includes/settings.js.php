<?php

/*
* include files
*
*/
include("ini.php");
include("session.php");
include("config.php");
include("functions.php");

/*
* get settings from db
*
*/

$profileOn = 0;
$profileUrl = '';
$profileRef = 0;

try {
    $dbh = db_connect();
    $params = array('');
    $query = "SELECT *   
			  FROM prochatrooms_config
			  LIMIT 1
			  ";
    $action = $dbh->prepare($query);
    $action->execute($params);

    foreach ($action as $i) {
        // profileOn
        $profileOn = $i['profileOn'];

        // profileUrl
        $profileUrl = $i['profileUrl'];

        // profileRef
        $profileRef = $i['profileRef'];

        // privateOn
        $privateOn = $i['privateOn'];

        // whisperOn
        $whisperOn = $i['whisperOn'];

        // advertsOn
        $advertsOn = $i['advertsOn'];

        // webcamsOn
        $webcamsOn = $i['webcamsOn'];

        // enableUrl
        $enableUrl = $i['enableUrl'];

        // enableEmail
        $enableEmail = $i['enableEmail'];

        // enableShoutFilter
        $enableShoutFilter = $i['enableShoutFilter'];

        // floodChat
        $floodChat = $i['floodChat'];

        // defaultSFX
        $defaultSFX = $i['defaultSFX'];

        // newPm
        $newPm = $i['newPm'];

        // newPmMin
        $newPmMin = $i['newPmMin'];

        // refreshRate
        $refreshRate = $i['refreshRate'];

        // displayMDiv
        $displayMDiv = $i['displayMDiv'];

        // totalMessages
        $totalMessages = $i['totalMessages'];

        // showMessages
        $showMessages = $i['showMessages'];

        // avatars
        $avatars = explode(",", $i['avatars']);
        $avatars_count = count($avatars) - 1;

        // badwords
        $badwords = explode(",", urldecode($i['badwords']));
        $badwords_count = count($badwords) - 1;

        // font color
        $font_color = explode(",", $i['font_color']);
        $font_color_count = count($font_color) - 1;

        // font size
        $font_size = explode(",", $i['font_size']);
        $font_size_count = count($font_size) - 1;

        // font size
        $font_family = explode(",", $i['font_family']);
        $font_family_count = count($font_family) - 1;

        // sfx
        $sfx = explode(",", $i['sfx']);
        $sfx_count = count($sfx) - 1;

        // smilies text
        $smilies_text = explode(",", $i['smilies_text']);
        $smilies_text_count = count($smilies_text) - 1;

        // sfx
        $smilies_images = explode(",", $i['smilies_images']);
        $smilies_images_count = count($smilies_images) - 1;

        // adverts
        $textAdverts = $i['textAdverts'];
        $advertsDesc = $i['textAdvertsDesc'];
        $textAdvertsRate = $i['textAdvertsRate'];

        // user status messages
        $userStatusMes = urldecode($i['userStatusMes']);

        // show time stamp (messages)
        $showTimeStamp = $i['showTimeStamp'];

        // integrated with CMS
        $integrated = $i['integrated'];

        // eCredits
        $eCredits = $i['eCredits'];
    }

    $dbh = null;
} catch (PDOException $e) {
    $error = "Function: " . __FUNCTION__ . "\n";
    $error .= "File: " . basename(__FILE__) . "\n";
    $error .= 'PDOException: ' . $e->getCode() . '-' . $e->getMessage() . "\n\n";

    debugError($error);
}
/*
* declare content header
*
*/

header("content-type: application/x-javascript");

/*
* profile details
*
*/
?>
var profileOn = <?php echo $profileOn; ?>;
var profileUrl = "<?php echo $profileUrl; ?>";
var profileRef = <?php echo $profileRef; ?>;

/*
 * enable private chat
 *
 */

var privateOn = <?php echo $privateOn; ?>;

/*
 * enable whisper
 *
 */

var whisperOn = <?php echo $whisperOn; ?>;

/*
 * enable webcams
 *
 */

var webcamsOn = <?php echo $webcamsOn; ?>;

/*
 * enable banners
 *
 */

var advertsOn = <?php echo $advertsOn; ?>;

/*
 * enable urls
 *
 */

var enableUrl = <?php echo $enableUrl; ?>;

/*
 * enable emails
 *
 */

var enableEmail = <?php echo $enableEmail; ?>;

/*
 * enable shout filter
 *
 */

var enableShoutFilter = <?php echo $enableShoutFilter; ?>;

/*
 * flood filter
 * allow new post every X seconds
 */

var floodChat = <?php echo $floodChat; ?>;

/*
 * default sfx
 *
 */

var sfx = '<?php echo $defaultSFX; ?>';

/*
 * title bar color for private messages
 *
 */

var newPM = '<?php echo $newPm; ?>';

/*
 * displays when private window minimised
 *
 */

var newPMmin = '<?php echo $newPmMin; ?>';

/*
 * refresh rate
 * 1 sec = 1000
 */

var refreshRate = <?php echo $refreshRate; ?>;

/*
 * chat messages container
 *
 */

var displayMDiv = 'chatContainer';

/*
 * max screen messages
 *
 */

var totalMessages = <?php echo $totalMessages; ?>;

/*
 * reset message count
 *
 */

var showMessages = <?php echo $showMessages; ?>;


/*
 * show avatar array
 *
 */

var totalAvatars = <?php echo $avatars_count; ?>;
var loopAvatars = 6;
var myAvatars = [];

<?php for ($i = 0; $i <= $avatars_count; $i++): ?>
myAvatars[<?php echo $i; ?>] = "<?php echo $avatars[$i]; ?>";
<?php endfor; ?>

/*
 * replace badwords
 *
 */

<?php $badword_replacement = "****"; ?>

function filterBadword(nBadword) {
    var badwordReplacement = '<?php echo $badword_replacement; ?>';

    <?php if ($badwords['0']): ?>
    <?php for ($i = 0; $i <= $badwords_count; $i++): ?>
    nBadword = nBadword.replace(/<?php echo $badwords[$i]; ?>/gi,badwordReplacement);
    <?php endfor; ?>
    <?php endif; ?>
    return nBadword;
}

/*
 * create Font Color array
 *
 */

var totalColors = <?php echo $font_color_count; ?>;
var loopColors = 12;
var myColor = [];

<?php for ($i = 0; $i <= $font_color_count; $i++): ?>
myColor[<?php echo $i; ?>] = "<?php echo $font_color[$i]; ?>";
<?php endfor; ?>

var defaultColor = "#ffffff";

/*
 * create Font Size array
 *
 */

var totalFontSize = <?php echo $font_size_count; ?>;
var loopFontSize = 1;
var myFontSize = [];

<?php for ($i = 0; $i <= $font_size_count; $i++): ?>
myFontSize[<?php echo $i; ?>] = "<?php echo $font_size[$i]; ?>";
<?php endfor; ?>

/*
 * create Font Family array
 *
 */

var totalFontFamily = <?php echo $font_family_count; ?>;
var loopFontFamily = 1;
var myFontFamily = [];

<?php for ($i = 0; $i <= $font_family_count; $i++): ?>
myFontFamily[<?php echo $i; ?>] = "<?php echo $font_family[$i]; ?>";
<?php endfor; ?>

/*
 * create SFX array
 *
 */

var totalSFX = <?php echo $sfx_count; ?>;
var mySFX = [];

<?php for ($i = 0; $i <= $sfx_count; $i++): ?>
mySFX[<?php echo $i; ?>] = "<?php echo $sfx[$i]; ?>";
<?php endfor; ?>

/*
 * create smilie array
 *
 */

var totalSmilies = <?php echo $smilies_text_count; ?>;
var loopSmilies = 5;
var mySmilies = [];

<?php for ($i = 0; $i < $smilies_text_count; $i++): ?>
mySmilies[<?php echo $i; ?>] = "<?Php echo $smilies_text[$i]; ?>";
<?php endfor; ?>

/*
 * create smilie image array
 *
 */

var mySmiliesImg = [];

<?php for ($i = 0; $i < $smilies_images_count; $i++): ?>
mySmiliesImg[<?php echo $i; ?>] = "<img style='vertical-align:middle;' src='smilies/<?php echo $smilies_images[$i]; ?>'>";
<?php endfor; ?>

/*
 * text adverts
 *
 */

var textAdverts = <?php echo $textAdverts; ?>;
var showTextAdverts = <?php echo $textAdvertsRate; ?>;

<?php if ($textAdverts): ?>
<?php $advertsDesc = explode(",", $advertsDesc); ?>
<?php $advertsArrayLength = count($advertsDesc); ?>

var advertDesc = [];

<?php $x = 0; ?>

<?php for ($i = 0; $i < $advertsArrayLength; $i++): ?>
<?php if ($_SESSION['room'] == $advertsDesc[$i][0]): ?>
advertDesc[<?php echo $x++; ?>] = "<?php echo str_replace($advertsDesc[$i][0] . "|", "", $advertsDesc[$i]); ?>";
<?php endif; ?>
<?php endfor; ?>

<?php endif; ?>

/*
 * user status messages
 *
 */

var userStatusMes = [];

<?php
$userStatusMes = explode(",", $userStatusMes);
$userStatusMesArrayLength = count($userStatusMes);
?>
<?php for ($i = 0; $i < $userStatusMesArrayLength; $i++): ?>
userStatusMes[<?php echo $i; ?>] = "<?php echo $userStatusMes[$i]; ?>";
<?php endfor; ?>

/*
 * timestamp for messages
 *
 */

var showMessageTimeStamp = <?php echo $showTimeStamp; ?>;

/*
 * badwords/characters
 *
 */

<?php
$_badwords = implode("|", badChars());
$_badwords = str_replace("'", "\'", $_badwords);
?>
var badChars = '<?php echo $_badwords; ?>';

/*
 * assign admin status
 *
 */

<?php
if (isset($_SESSION['adminUser'])) {
    unset($_SESSION['adminUser']);
}
?>

/* 
 * user status
 *
 */

var admin = <?php echo getAdmin($_SESSION['user_id']); ?>;
var moderator = <?php echo getModerator($_SESSION['user_id']); ?>;
var speaker = <?php echo getSpeaker($_SESSION['user_id']); ?>;

/* 
 * user messages
 *
 */

var mBold = <?php echo $CONFIG['text']['bold'];?>;
var mItalic = <?php echo $CONFIG['text']['italic'];?>;
var mUnderline = <?php echo $CONFIG['text']['underline'];?>;
var textColor = '<?php echo $CONFIG['text']['color'];?>';
var textSize = '<?php echo $CONFIG['text']['size'];?>';
var textFamily = '<?php echo $CONFIG['text']['family'];?>';

/* 
 * system messages
 *
 */

var stextColor = '<?php echo $CONFIG['text']['color'];?>';
var stextSize = '<?php echo $CONFIG['text']['size'];?>';
var stextFamily = '<?php echo $CONFIG['text']['family'];?>';

/* 
 * system variables
 *
 */

var groupChat = <?php echo $_SESSION['groupChat'];?>;
var groupPChat = <?php echo $_SESSION['groupPChat'];?>;
var groupCams = <?php echo $_SESSION['groupCams'];?>;
var groupWatch = <?php echo $_SESSION['groupWatch'];?>;
var groupRooms = <?php echo $_SESSION['groupRooms'];?>;
var groupVideo = <?php echo $_SESSION['groupVideo'];?>;

<?php if (!isset($_SESSION['groupChat'])): ?>
groupCams = 0;
groupWatch = 0;
groupChat = 0;
groupPChat = 0;
groupRooms = 0;
groupVideo = 0;
<?php endif; ?>

/* 
 * style folder
 *
 */

var styleFolder = '<?php echo $CONFIG['template'];?>';

/* 
 * silent user
 *
 */

var silent = <?php echo $CONFIG['silent'];?>;

/*
 * silence length in minutes
 * features built in anti cheat mode
 * (restarts silence counter on page reload)
 */

var isSilenced = 0;

<?php if ($_SESSION['silenceStart'] > date("U") - ($CONFIG['silent'] * 60)): ?>
isSilenced = 1;
initDoSilence = setInterval('doSilence()', 1000);
<?Php endif; ?>

/* 
 * invisible
 *
 */

var invisibleOn = 0;
var hide = 0;

/* 
 * idle timeout
 *
 */

var idleTimeout = <?php echo $CONFIG['idleTimeout'];?>;

/* 
 * idle logout timeout
 *
 */

var idleLogoutTimeout = <?php echo $CONFIG['idleLogoutTimeout'];?>;

/* 
 * copyright
 *
 */

var showCopyright = "<?php echo remBrand(); ?>";

/* 
 * software version
 *
 */

var version = '<?php echo $CONFIG['version']; ?>';

/* 
 * display last messages
 *
 */

var dispLastMess = '<?php echo $CONFIG['dispLastMess'];?>';

// set variables
var intelliBot = <?php echo $CONFIG['intelliBot'];?>;
var intelliBotName = '<?php echo $CONFIG['intelliBotName'];?>';
var intelliBotAvi = '<?php echo $CONFIG['intelliBotAvi'];?>';
var intellibotRoomID = '<?php echo $CONFIG['intellibotRoomID'];?>';
var maxTextLength = <?php echo $CONFIG['maxChars'];?>;
var hasSharePlugin = <?php echo (file_exists("../plugins/share/index.php")) ? 'true' : 'false'; ?>;
var hasGamesPlugin = <?php echo (file_exists("../plugins/games/index.php")) ? 'true' : 'false'; ?>;
<?php
$room = '';

if (isset($_SESSION['room']) && $CONFIG['singleRoom']) {
    $room = "roomID=" . $_SESSION['room'] . "&";
}
?>
var roomText = '<?php echo $room; ?>';
var textScale = 100;
