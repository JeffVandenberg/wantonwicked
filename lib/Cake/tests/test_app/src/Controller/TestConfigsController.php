<?php

namespace lib\Cake\Test\test_app\Controller;

use App\Controller\CakeErrorController;

class TestConfigsController extends CakeErrorController {

	public $components = array(
		'RequestHandler' => array(
			'some' => 'config'
		)
	);

}
