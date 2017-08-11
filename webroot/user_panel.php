<?php
use classes\integration\WikiInformation;
use classes\request\repository\RequestRepository;

/* @var array $userdata */
$get_vars = "";
$is_first = true;
while (list($key, $value) = each($_GET)) {
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
    $logout = append_sid("/forum/ucp.php", "mode=logout&redirect=$redirect", true, $user->session_id);
    $up_name = <<<EOQ
<span>$userdata[username]</span>
EOQ;

    $up_loginout = <<<EOQ
<a href="$logout">Logout</a>
EOQ;
    $requestRepository = new RequestRepository();
    $requestCount = $requestRepository->getOpenByUserId($userdata['user_id']);
    $newStRequestCount = $requestRepository->getNewStRequests($userdata['user_id']);

    $userControlPanel = <<<EOQ
 <a href="forum/ucp.php">User Control Panel</a>
EOQ;

    if($requestCount) {
        $userControlPanel .= <<<EOQ
 <br><a href="/request.php">Open Requests ($requestCount)</a>
EOQ;
    }

    if($newStRequestCount) {
        $userControlPanel .= <<<EOQ
 <br><a href="/request.php?action=st_list">New Requests to Process ($newStRequestCount)</a>
EOQ;

        $userInfo['username'] = $userdata['username'];
        $userInfo['logout_link'] = $logout;
        $userInfo['request_count'] = $requestCount;
        $userInfo['new_request_count'] = $newStRequestCount;
        $userInfo['logged_in'] = true;
    }

    WikiInformation::setUpName($up_name);
    WikiInformation::setLoginOut($up_loginout);
    WikiInformation::setUcp($userControlPanel);
}


$user_panel = <<<EOQ
$up_name 
<span id="server-time"></span><br>
$up_loginout <br>
$userControlPanel
EOQ;


class UserPanel
{
    public static function getUserControlPanel()
    {
        return WikiInformation::getUcp();
    }

    public static function getUpName()
    {
        return WikiInformation::getUpName();
    }

    public static function getUpLogInOut()
    {
        return WikiInformation::getLoginOut();
    }

}
