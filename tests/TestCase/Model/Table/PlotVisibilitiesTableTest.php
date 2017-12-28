<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PlotVisibilitiesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PlotVisibilitiesTable Test Case
 */
class PlotVisibilitiesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PlotVisibilitiesTable
     */
    public $PlotVisibilities;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.plot_visibilities',
        'app.plots',
        'app.plot_statuses',
        'app.created_by',
        'app.groups',
        'app.users',
        'app.roles',
        'app.permissions',
        'app.permissions_users',
        'app.permissions_roles',
        'app.group_types',
        'app.group_icons',
        'app.requests',
        'app.st_groups',
        'app.request_types',
        'app.groups_request_types',
        'app.updated_by',
        'app.plot_characters',
        'app.characters',
        'app.character_status',
        'app.character_beat_records',
        'app.character_beats',
        'app.beat_types',
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
        'app.territories',
        'app.characters_territories',
        'app.plot_scenes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('PlotVisibilities') ? [] : ['className' => PlotVisibilitiesTable::class];
        $this->PlotVisibilities = TableRegistry::get('PlotVisibilities', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PlotVisibilities);

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
