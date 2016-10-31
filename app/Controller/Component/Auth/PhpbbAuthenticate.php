<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 12/27/13
 * Time: 3:18 PM
 */

App::uses('BaseAuthenticate', 'Controller/Component/Auth');

class PhpbbAuthenticate extends BaseAuthenticate {
    public function authenticate(CakeRequest $request, CakeResponse $response) {
        global $userdata;

        if(isset($userdata) && ($userdata !== null) && ($userdata['user_id'] != 1)) {
            return $userdata;
        }
        return false;
    }

    public function logout($user)
    {
        unset($user);
        return '/';
    }
}
