<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RequestStatusHistoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RequestStatusHistoriesTable Test Case
 */
class RequestStatusHistoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RequestStatusHistoriesTable
     */
    public $RequestStatusHistories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.request_status_histories',
        'app.requests',
        'app.groups',
        'app.users',
        'app.roles',
        'app.permissions',
        'app.permissions_users',
        'app.permissions_roles',
        'app.group_types',
        'app.group_icons',
        'app.st_groups',
        'app.request_types',
        'app.groups_request_types',
        'app.characters',
        'app.updated_by',
        'app.character_statuses',
        'app.character_beat_records',
        'app.character_beats',
        'app.beat_types',
        'app.created_by',
        'app.beat_statuses',
        'app.character_logins',
        'app.character_notes',
        'app.character_powers',
        'app.character_updates',
        'app.locations',
        'app.log_characters',
        'app.request_characters',
        'app.scene_characters',
        'app.scenes',
        'app.run_by',
        'app.scene_statuses',
        'app.scene_requests',
        'app.plot_scenes',
        'app.plots',
        'app.plot_statuses',
        'app.plot_visibilities',
        'app.plot_characters',
        'app.territories',
        'app.characters_territories',
        'app.request_statuses',
        'app.created_bies',
        'app.updated_bies',
        'app.request_bluebooks',
        'app.bluebooks',
        'app.request_notes',
        'app.request_rolls',
        'app.rolls'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('RequestStatusHistories') ? [] : ['className' => RequestStatusHistoriesTable::class];
        $this->RequestStatusHistories = TableRegistry::getTableLocator()->get('RequestStatusHistories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RequestStatusHistories);

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
