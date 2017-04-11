<?php
namespace app\Test\TestCase\Model;

use Cake\TestSuite\TestCase;
use App\Model\SceneCharacter;

/**
 * SceneCharacter Test Case
 *
 */
class SceneCharacterTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.scene_characters',
		'app.scenes',
		'app.users',
		'app.scene_requests',
		'app.characters'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SceneCharacter = ClassRegistry::init('SceneCharacter');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SceneCharacter);

		parent::tearDown();
	}

}
