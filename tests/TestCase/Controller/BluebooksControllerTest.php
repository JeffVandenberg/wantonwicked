<?php
namespace App\Test\TestCase\Controller;

use App\Controller\BluebooksController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\BluebooksController Test Case
 */
class BluebooksControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.bluebooks',
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
//        'app.created_by',
        'app.roles',
        'app.permissions',
        'app.permissions_users',
        'app.permissions_roles',
//        'app.updated_by',
        'app.request_bluebooks',
        'app.request_characters',
        'app.request_notes',
        'app.request_rolls',
        'app.rolls',
        'app.scene_requests',
        'app.scenes',
//        'app.run_by',
        'app.scene_statuses',
        'app.scene_characters',
        'app.plot_scenes',
        'app.plots',
        'app.plot_statuses',
        'app.plot_visibilities',
        'app.plot_characters',
        'app.request_requests',
//        'app.from_request',
//        'app.to_request',
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
        'app.locations',
        'app.log_characters',
//        'app.territories',
//        'app.characters_territories'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
