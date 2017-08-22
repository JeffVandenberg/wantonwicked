<?php
use classes\integration\WikiInformation;
use classes\request\repository\RequestRepository;

/* @var array $userdata */
$get_vars = "";
$is_first = true;
foreach($_GET as $key => $value) {
    if ($key != 'sid') {
        if ($is_first) {
            $get_vars .= "?$key=$value";
            $is_first = false;
        } else {
            $get_vars .= "&$key=$value";
        }
    }
}
reset($_GET);

$get_vars = str_replace("&", "|", $get_vars);
$redirect = $_SERVER['PHP_SELF'] . $get_vars;

$up_name = <<<EOQ
<a href="/forum/ucp.php?mode=login&redirect=$redirect">Login</a>
EOQ;

$up_loginout = <<<EOQ
<a href="/forum/ucp.php?mode=register&redirect=$redirect">Register</a>
EOQ;

$userInfo = [];
$userInfo['redirect'] = $redirect;
$userInfo['username'] = 'Login';
$userInfo['logged_in'] = false;

$userControlPanel = "";
if ($userdata['user_id'] != 1) {
    // build logged in panel
    $logout = append_sid("/forum/ucp.php", "mode=logout&redirect=$redirect", true, $user->session_id);

    $requestRepository = new RequestRepository();
    $requestCount = $requestRepository->getOpenByUserId($userdata['user_id']);
    $newStRequestCount = $requestRepository->getNewStRequests($userdata['user_id']);

    // build user panel
    ob_start(); ?>
<span id="server-time"></span>
<?php if($newStRequestCount): ?>
<a href="/request.php" class="button-badge">
    <i class="fa fi-clipboard storyteller-action" title="ST Request Dashboard"></i>
    <span class="badge badge-primary warning" title="New Requests"><?php echo $newStRequestCount; ?></span>
    </a>
<?php endif; ?>
<a href="/request.php" class="button-badge">
    <i class="fa fi-clipboard" title="Your Requests"></i>
    <?php if($requestCount): ?>
    <span class="badge badge-primary warning" title="Open Requests"><?php echo $requestCount; ?></span>
    <?php endif; ?>
</a>
<button class="button" type="button" data-toggle="user-dropdown">
    <?php echo $userdata['username']; ?>
</button>
<div class="dropdown-pane" id="user-dropdown" data-dropdown>
    <div><a href="/forum/ucp.php">User Control Panel</a></div>
    <div><a href="<?php echo $logout; ?>">Logout</a></div>
</div>

    <?php
    $user_panel = ob_get_clean();

    // setup userInfo
    $userInfo['username'] = $userdata['username'];
    $userInfo['logout_link'] = $logout;
    $userInfo['request_count'] = $requestCount;
    $userInfo['new_request_count'] = $newStRequestCount;
    $userInfo['logged_in'] = true;

    WikiInformation::setUcp($user_panel);
} else {
    $user_panel = <<<EOQ
<span id="server-time"></span>
$up_name 
$up_loginout
EOQ;
    WikiInformation::setUcp($user_panel);
}

$user_panel = <<<EOQ
<span id="server-time"></span>
$up_name 
$up_loginout
EOQ;
