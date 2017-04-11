<?php
namespace app\Test\TestCase\Model;

use Cake\TestSuite\TestCase;
use App\Model\Scene;

/**
 * Scene Test Case
 *
 */
class SceneTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.scenes',
		'app.run_bies',
		'app.created_bies',
		'app.updated_bies',
		'app.scene_characters',
		'app.scene_requests'
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
