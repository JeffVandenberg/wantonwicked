<?php
namespace app\Test\TestCase\Model;

use Cake\TestSuite\TestCase;
use App\Model\SceneRequest;

/**
 * SceneRequest Test Case
 *
 */
class SceneRequestTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.scene_requests',
		'app.scenes',
		'app.users',
		'app.scene_characters',
		'app.characters',
		'app.requests',
		'app.groups',
		'app.group_types',
		'app.group_icons',
		'app.request_types',
		'app.groups_request_types',
		'app.request_statuses',
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
		$this->SceneRequest = ClassRegistry::init('SceneRequest');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SceneRequest);

		parent::tearDown();
	}

}
