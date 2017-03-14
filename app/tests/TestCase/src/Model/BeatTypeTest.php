<?php
namespace app\Test\TestCase\Model;

App::uses('BeatType', 'Model');

/**
 * BeatType Test Case
 */
class BeatTypeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.beat_type',
		'app.created_by',
		'app.updated_by'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->BeatType = ClassRegistry::init('BeatType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->BeatType);

		parent::tearDown();
	}

}
