<?php

namespace lib\Cake\Test\test_app\Template\Helper;

App::uses('Helper', 'View');

class BananaHelper extends Helper {

	public function peel() {
		return '<b>peeled</b>';
	}

}
