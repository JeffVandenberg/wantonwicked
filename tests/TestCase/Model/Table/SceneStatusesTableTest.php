<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SceneStatusesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SceneStatusesTable Test Case
 */
class SceneStatusesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SceneStatusesTable
     */
    public $SceneStatuses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.scene_statuses',
        'app.scenes',
        'app.run_bies',
        'app.created_bies',
        'app.updated_bies',
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
        'app.characters_territories',
        'app.scene_requests'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SceneStatuses') ? [] : ['className' => 'App\Model\Table\SceneStatusesTable'];
        $this->SceneStatuses = TableRegistry::get('SceneStatuses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SceneStatuses);

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
}
