<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jvandenberg
 * Date: 9/4/13
 * Time: 12:43 PM
 * To change this template use File | Settings | File Templates.
 */

?>

<h2>Login OOC</h2>
<form method="POST" action="/chat/index.php">
    <input type="hidden" name="action" value="login" />
    <input type="hidden" name="isGuest" value="1" />
    Login Name
    <input type="text" name="username" />
    <input type="submit" value="Login" />
</form>