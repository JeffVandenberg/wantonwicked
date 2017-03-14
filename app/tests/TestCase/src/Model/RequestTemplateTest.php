<?php
namespace app\Test\TestCase\Model;

App::uses('RequestTemplate', 'Model');

/**
 * RequestTemplate Test Case
 *
 */
class RequestTemplateTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.request_template'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->RequestTemplate = ClassRegistry::init('RequestTemplate');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->RequestTemplate);

		parent::tearDown();
	}

}
