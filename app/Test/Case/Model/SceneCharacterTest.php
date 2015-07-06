<?php
App::uses('SceneCharacter', 'Model');

/**
 * SceneCharacter Test Case
 *
 */
class SceneCharacterTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.scene_character',
		'app.scene',
		'app.user',
		'app.scene_request',
		'app.character'
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
