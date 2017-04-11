<?php
namespace app\Test\TestCase\Model;

use Cake\TestSuite\TestCase;
use App\Model\Request;

/**
 * Request Test Case
 *
 */
class RequestTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.requests',
		'app.groups',
		'app.group_types',
		'app.group_icons',
		'app.request_types',
		'app.groups_request_types',
		'app.characters',
		'app.users',
		'app.request_statuses',
		'app.created_bies',
		'app.request_bluebooks',
		'app.request_characters',
		'app.request_notes',
		'app.request_rolls',
		'app.request_status_histories'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Request = ClassRegistry::init('Request');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Request);

		parent::tearDown();
	}

}
