<?php
namespace app\Test\TestCase\Model;

use Cake\TestSuite\TestCase;
use App\Model\GroupType;

/**
 * GroupType Test Case
 *
 */
class GroupTypeTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.group_types',
		'app.groups',
		'app.group_icons',
		'app.requests',
		'app.request_types',
		'app.groups_request_types'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->GroupType = ClassRegistry::init('GroupType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->GroupType);

		parent::tearDown();
	}

}
