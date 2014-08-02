<?php
App::uses('GroupType', 'Model');

/**
 * GroupType Test Case
 *
 */
class GroupTypeTest extends CakeTestCase {

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
