<?php
namespace app\Test\TestCase\Model;

App::uses('Scene', 'Model');

/**
 * Scene Test Case
 *
 */
class SceneTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.scene',
		'app.run_by',
		'app.created_by',
		'app.updated_by',
		'app.scene_character',
		'app.scene_request'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Scene = ClassRegistry::init('Scene');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Scene);

		parent::tearDown();
	}

}
