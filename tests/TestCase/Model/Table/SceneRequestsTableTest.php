<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SceneRequestsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SceneRequestsTable Test Case
 */
class SceneRequestsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SceneRequestsTable
     */
    public $SceneRequests;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.scene_requests',
        'app.scenes',
        'app.run_bies',
        'app.created_bies',
        'app.updated_bies',
        'app.scene_statuses',
        'app.scene_characters',
        'app.characters',
        'app.users',
        'app.groups',
        'app.roles',
        'app.character_beat_records',
        'app.character_beats',
        'app.character_logins',
        'app.character_notes',
        'app.character_powers',
        'app.character_updates',
        'app.locations',
        'app.log_characters',
        'app.request_characters',
        'app.requests',
        'app.territories',
        'app.characters_territories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SceneRequests') ? [] : ['className' => 'App\Model\Table\SceneRequestsTable'];
        $this->SceneRequests = TableRegistry::get('SceneRequests', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SceneRequests);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
