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
		'app.scene_request',
		'app.scene',
		'app.user',
		'app.scene_character',
		'app.character',
		'app.request',
		'app.group',
		'app.group_type',
		'app.group_icon',
		'app.request_type',
		'app.groups_request_type',
		'app.request_status',
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
