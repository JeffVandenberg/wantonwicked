<?php
namespace app\Test\TestCase\Model;

App::uses('Condition', 'Model');

/**
 * Condition Test Case
 */
class ConditionTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.condition'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Condition = ClassRegistry::init('Condition');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Condition);

		parent::tearDown();
	}

}
