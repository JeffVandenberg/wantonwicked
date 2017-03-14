<?php
namespace app\Test\TestCase\Model;

App::uses('RequestType', 'Model');

/**
 * RequestType Test Case
 *
 */
class RequestTypeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.request_type',
		'app.request',
		'app.group',
		'app.groups_request_type'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->RequestType = ClassRegistry::init('RequestType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->RequestType);

		parent::tearDown();
	}

}
