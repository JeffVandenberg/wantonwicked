<?php
namespace app\Test\TestCase\Model;

App::uses('Request', 'Model');

/**
 * Request Test Case
 *
 */
class RequestTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.request',
		'app.group',
		'app.group_type',
		'app.group_icon',
		'app.request_type',
		'app.groups_request_type',
		'app.character',
		'app.user',
		'app.request_status',
		'app.created_by',
		'app.request_bluebook',
		'app.request_character',
		'app.request_note',
		'app.request_roll',
		'app.request_status_history'
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
