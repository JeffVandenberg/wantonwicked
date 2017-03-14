<?php
namespace app\Test\TestCase\Model;

use Cake\TestSuite\TestCase;
use App\Model\BeatType;

/**
 * BeatType Test Case
 */
class BeatTypeTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.beat_types',
		'app.created_bies',
		'app.updated_bies'
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
