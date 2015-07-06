<?php
App::uses('SceneStatus', 'Model');

/**
 * SceneStatus Test Case
 *
 */
class SceneStatusTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.scene_status'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SceneStatus = ClassRegistry::init('SceneStatus');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SceneStatus);

		parent::tearDown();
	}

}
