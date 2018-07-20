<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LocationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LocationsTable Test Case
 */
class LocationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LocationsTable
     */
    public $Locations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.locations',
        'app.districts',
        'app.created_bies',
        'app.updated_bies',
        'app.characters',
        'app.users',
        'app.groups',
        'app.group_types',
        'app.group_icons',
        'app.requests',
        'app.request_types',
        'app.groups_request_types',
        'app.request_statuses',
        'app.request_status_histories',
        'app.created_by',
        'app.roles',
        'app.permissions',
        'app.permissions_users',
        'app.permissions_roles',
        'app.updated_by',
        'app.request_bluebooks',
        'app.bluebooks',
        'app.request_characters',
        'app.request_notes',
        'app.request_rolls',
        'app.rolls',
        'app.scene_requests',
        'app.scenes',
        'app.run_by',
        'app.scene_statuses',
        'app.scene_characters',
        'app.plot_scenes',
        'app.plots',
        'app.tagged',
        'app.tags',
        'app.tags_tagged',
        'app.scenes_tags',
        'app.plots_tags',
        'app.plot_statuses',
        'app.plot_visibilities',
        'app.plot_characters',
        'app.request_requests',
        'app.from_request',
        'app.to_request',
        'app.st_groups',
        'app.character_statuses',
        'app.character_beat_records',
        'app.character_beats',
        'app.beat_types',
        'app.beat_statuses',
        'app.character_logins',
        'app.character_notes',
        'app.character_powers',
        'app.character_updates',
        'app.log_characters',
        'app.territories',
        'app.characters_territories',
        'app.location_types',
        'app.location_traits'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Locations') ? [] : ['className' => LocationsTable::class];
        $this->Locations = TableRegistry::getTableLocator()->get('Locations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Locations);

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
