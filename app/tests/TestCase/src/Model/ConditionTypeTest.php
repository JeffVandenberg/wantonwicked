<?php
App::uses('ConditionType', 'Model');

/**
 * ConditionType Test Case
 */
class ConditionTypeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.condition_type'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ConditionType = ClassRegistry::init('ConditionType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ConditionType);

		parent::tearDown();
	}

}
