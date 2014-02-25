<?php

/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 2/24/14
 * Time: 6:33 PM
 * @property Character Character
 */

class CharacterController extends AppController {
    public $scaffold;

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }

    public function city($city = 'Savannah') {
        $this->set('characters', $this->Character->ListByCity($city));
    }

    public function type($type = 'All') {
        $this->set('characters', $this->Character->ListByCharacterType($type));
        $this->set('type', $type);
    }

    public function isAuthorized($context) {
        return true;
    }
} 