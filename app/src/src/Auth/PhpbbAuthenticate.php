<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 12/27/13
 * Time: 3:18 PM
 */namespace app\Auth;



use App\Controller\Component\Auth\BaseAuthenticate;

class PhpbbAuthenticate extends BaseAuthenticate {
    public function authenticate(Request $request, Response $response) {
        global $userdata;

        if(isset($userdata) && ($userdata !== null) && ($userdata['user_id'] != 1)) {
            return $userdata;
        }
        return false;
    }

    public function logout($user)
    {
        $user = null;
        return '/';
    }
}
