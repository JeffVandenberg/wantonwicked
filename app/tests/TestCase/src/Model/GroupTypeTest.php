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
		'app.group_type',
		'app.group',
		'app.group_icon',
		'app.request',
		'app.request_type',
		'app.groups_request_type'
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
