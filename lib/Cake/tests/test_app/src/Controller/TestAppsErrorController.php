<?php

namespace lib\Cake\Test\test_app\Controller;

App::uses('CakeErrorController', 'Controller');

class TestAppsErrorController extends CakeErrorController {

	public $helpers = array(
		'Html',
		'Session',
		'Form',
		'Banana',
	);

}
